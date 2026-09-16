$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

function Invoke-Native {
    param([Parameter(Mandatory = $true)][scriptblock]$Command)
    & $Command
    if ($LASTEXITCODE -ne 0) { throw "Command failed with exit code $LASTEXITCODE." }
}

if (-not (Test-Path '.\artisan')) {
    throw 'Copy all extracted hotfix files into the Laravel project root containing artisan.'
}

$required = @(
    '.\app\Services\SlideImageProcessor.php',
    '.\app\Http\Controllers\Admin\StoreDesignController.php',
    '.\resources\views\storefront\home.blade.php',
    '.\resources\views\admin\settings\partials\slide-fields.blade.php'
)

foreach ($file in $required) {
    if (-not (Test-Path $file)) { throw "Cloud slider hotfix file is missing: $file" }
}

Invoke-Native { php -l '.\app\Services\SlideImageProcessor.php' }
Invoke-Native { php -l '.\app\Http\Controllers\Admin\StoreDesignController.php' }
Invoke-Native { php artisan optimize:clear }

Write-Host 'AMER Pack-015 v1.3 Cloud Slider Image hotfix installed successfully.' -ForegroundColor Green
