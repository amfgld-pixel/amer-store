$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

if (-not (Test-Path '.\artisan')) {
    throw 'Run this installer from the Laravel project root containing artisan.'
}

$requiredFiles = @(
    '.\app\Services\StoreDesignService.php',
    '.\resources\views\layouts\storefront.blade.php'
)

foreach ($file in $requiredFiles) {
    if (-not (Test-Path $file)) {
        throw "Required hotfix file is missing: $file"
    }
}

php -l '.\app\Services\StoreDesignService.php'
if ($LASTEXITCODE -ne 0) {
    throw 'StoreDesignService.php syntax validation failed.'
}

php artisan optimize:clear
if ($LASTEXITCODE -ne 0) {
    throw 'Laravel cache clear failed.'
}

Write-Host ''
Write-Host 'AMER Pack-015 v1.6 Cloud Logo/Favicon fix installed.' -ForegroundColor Green
Write-Host 'For Laravel Cloud: commit, push and deploy, then upload Logo and Favicon again from Store Design.' -ForegroundColor Yellow
