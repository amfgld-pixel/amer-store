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

$required = @(
    '.\app\Http\Requests\BulkStoreSlidesRequest.php',
    '.\resources\views\admin\settings\partials\bulk-slide-builder.blade.php',
    '.\public\js\amer-bulk-slide-builder.js',
    '.\public\css\amer-add-more-pictures.css'
)
foreach ($file in $required) {
    if (-not (Test-Path $file)) { throw "Hotfix file is missing: $file" }
}

Invoke-Native { php -l '.\app\Http\Requests\BulkStoreSlidesRequest.php' }
Invoke-Native { php artisan optimize:clear }

$routes = (php artisan route:list --name=admin.settings.slides.bulk | Out-String)
if ($LASTEXITCODE -ne 0 -or $routes -notmatch 'admin\.settings\.slides\.bulk') {
    throw 'Multiple picture slide route is unavailable. Install corrected Pack-015 first.'
}

Write-Host 'AMER Pack-015 Add More Pictures hotfix installed successfully.' -ForegroundColor Green
