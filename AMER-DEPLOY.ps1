param(
    [switch]$SkipComposer,
    [switch]$SkipFrontend
)

$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

if (-not (Test-Path '.\artisan') -or -not (Test-Path '.\composer.json')) {
    throw 'Run this script from the Laravel project root containing artisan and composer.json.'
}

if (-not (Test-Path '.\.env')) {
    throw '.env is missing. Configure it before deployment.'
}

$envText = Get-Content '.env' -Raw
if ($envText -match '(?m)^APP_DEBUG\s*=\s*true\s*$') {
    throw 'APP_DEBUG=true is unsafe for production. Change it to false.'
}

Write-Host 'Enabling maintenance mode...' -ForegroundColor Cyan
php artisan down --retry=60

try {
    if (-not $SkipComposer) {
        composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
    }

    if (-not $SkipFrontend -and (Test-Path '.\package.json')) {
        npm.cmd install
        npm.cmd run build
    }

    php artisan migrate --force
    php artisan storage:link
    php artisan optimize:clear
    php artisan config:cache
    php artisan event:cache
    php artisan route:cache
    php artisan view:cache

    Write-Host 'Deployment commands completed.' -ForegroundColor Green
}
finally {
    php artisan up
}

Write-Host 'AMER is out of maintenance mode.' -ForegroundColor Green

