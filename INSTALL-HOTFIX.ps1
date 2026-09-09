$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

if (-not (Test-Path '.\artisan')) {
    throw 'Copy the hotfix contents into the Laravel project root containing artisan.'
}

php artisan view:clear
if ($LASTEXITCODE -ne 0) { throw 'Unable to clear compiled Blade views.' }

php artisan optimize:clear
if ($LASTEXITCODE -ne 0) { throw 'Unable to clear Laravel caches.' }

Write-Host 'AMER Pack-013 v1.2 homepage hotfix installed.' -ForegroundColor Green

