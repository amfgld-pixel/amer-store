$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

function Invoke-Native {
    param([Parameter(Mandatory = $true)][scriptblock]$Command)
    & $Command
    if ($LASTEXITCODE -ne 0) { throw "Command failed with exit code $LASTEXITCODE." }
}

if (-not (Test-Path '.\artisan')) {
    throw 'Copy all hotfix contents into the Laravel project root containing artisan.'
}

Invoke-Native { php -l '.\routes\admin.php' }
Invoke-Native { php -l '.\app\Http\Controllers\Admin\SettingsHubController.php' }
Invoke-Native { php artisan optimize:clear }

$routes = (php artisan route:list --name=admin.settings.index | Out-String)
if ($LASTEXITCODE -ne 0 -or $routes -notmatch 'admin\.settings\.index') {
    throw 'Settings Hub route is still unavailable.'
}

Write-Host 'AMER Pack-015 Settings Hub route hotfix installed successfully.' -ForegroundColor Green
