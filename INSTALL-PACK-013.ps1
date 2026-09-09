$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

function Invoke-Native {
    param([Parameter(Mandatory = $true)][scriptblock]$Command)
    & $Command
    if ($LASTEXITCODE -ne 0) {
        throw "Command failed with exit code $LASTEXITCODE."
    }
}

if (-not (Test-Path '.\artisan')) {
    throw 'Copy Pack-013 files into the Laravel project root containing artisan.'
}

Invoke-Native { composer dump-autoload }
Invoke-Native { php artisan optimize:clear }
Invoke-Native { php artisan migrate --force }

if (-not (Test-Path '.\public\storage')) {
    Invoke-Native { php artisan storage:link }
}
else {
    Write-Host 'public/storage already exists; storage link step skipped.' -ForegroundColor Yellow
}

if (Test-Path '.\package.json') {
    Invoke-Native { npm.cmd run build }
}

$artisanCommands = php artisan list --raw
if ($LASTEXITCODE -ne 0) {
    throw 'Unable to read the Artisan command list.'
}

if ($artisanCommands -match '(?m)^test\s') {
    Invoke-Native { php artisan test --filter=Pack013 }
}
elseif (Test-Path '.\vendor\bin\phpunit.bat') {
    Invoke-Native { .\vendor\bin\phpunit.bat --filter=Pack013 }
}
else {
    Write-Host 'Test runner is unavailable because development dependencies are not installed; test step skipped.' -ForegroundColor Yellow
}

Invoke-Native { php artisan route:list }
Write-Host 'AMER Pack-013 v1.1 installation verification completed.' -ForegroundColor Green
