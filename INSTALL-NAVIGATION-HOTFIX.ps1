$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

if (-not (Test-Path '.\artisan')) {
    throw 'Copy this hotfix into the Laravel project root containing artisan.'
}

php artisan optimize:clear
if ($LASTEXITCODE -ne 0) { throw 'Unable to clear Laravel caches.' }

$routes = php artisan route:list --name=admin.settings.index
if ($LASTEXITCODE -ne 0) { throw 'Unable to verify the Settings Hub route.' }
if ($routes -notmatch 'admin\.settings\.index') { throw 'Settings Hub route was not registered.' }

Write-Host 'AMER Pack-013 v1.3 Admin Navigation installed.' -ForegroundColor Green

