$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

if (-not (Test-Path '.\artisan')) {
    throw 'Copy all extracted hotfix files into the Laravel project root containing artisan.'
}

$required = @(
    '.\resources\views\storefront\home.blade.php',
    '.\public\css\amer-responsive-slides.css'
)

foreach ($file in $required) {
    if (-not (Test-Path $file)) { throw "Automatic slider hotfix file is missing: $file" }
}

php artisan optimize:clear
if ($LASTEXITCODE -ne 0) { throw 'Laravel cache clear failed.' }

Write-Host 'AMER Pack-015 v1.5 Automatic Slider hotfix installed successfully.' -ForegroundColor Green
