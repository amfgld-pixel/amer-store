$ErrorActionPreference = 'Stop'

Write-Host 'AMER installation verification starting...' -ForegroundColor Cyan

if (-not (Test-Path (Join-Path $PSScriptRoot 'artisan'))) {
    Write-Host ''
    Write-Host 'ERROR: artisan was not found beside this script.' -ForegroundColor Red
    Write-Host 'Copy the consolidated source files into the Laravel project root first.' -ForegroundColor Yellow
    Write-Host 'The correct folder must contain artisan, composer.json, app, routes and this script.' -ForegroundColor Yellow
    exit 1
}

Set-Location $PSScriptRoot

if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    throw 'PHP is not available in PATH.'
}

if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    throw 'Composer is not available in PATH.'
}

composer dump-autoload
php artisan optimize:clear
php artisan migrate --seed

if (Test-Path '.\package.json') {
    if (-not (Get-Command npm.cmd -ErrorAction SilentlyContinue)) {
        throw 'npm.cmd is not available in PATH.'
    }

    npm.cmd install
    npm.cmd run build
}

php artisan test
php artisan route:list

Write-Host ''
Write-Host 'AMER v1.0 verification completed successfully.' -ForegroundColor Green
