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
    '.\resources\views\admin\settings\partials\slide-fields.blade.php',
    '.\resources\views\admin\settings\partials\bulk-slide-builder.blade.php',
    '.\resources\views\storefront\home.blade.php',
    '.\public\js\amer-slide-editor.js',
    '.\public\css\amer-slide-editor.css'
)

foreach ($file in $required) {
    if (-not (Test-Path $file)) { throw "No-crop slider hotfix file is missing: $file" }
}

Invoke-Native { php -l '.\app\Services\SlideImageProcessor.php' }
if (Get-Command node -ErrorAction SilentlyContinue) {
    Invoke-Native { node --check '.\public\js\amer-slide-editor.js' }
}
Invoke-Native { php artisan optimize:clear }

Write-Host 'AMER Pack-015 v1.4 No-Crop Slider hotfix installed successfully.' -ForegroundColor Green
