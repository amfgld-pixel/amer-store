$ErrorActionPreference = 'Stop'

$projectRoot = $PSScriptRoot
$controller = Join-Path $projectRoot 'app\Http\Controllers\Admin\StoreDesignController.php'

if (-not (Test-Path $controller)) {
    throw "Controller file was not found: $controller"
}

Push-Location $projectRoot
try {
    php -l $controller
    if ($LASTEXITCODE -ne 0) {
        throw 'PHP syntax verification failed.'
    }

    php artisan optimize:clear
    if ($LASTEXITCODE -ne 0) {
        throw 'Laravel cache clear failed.'
    }

    $routeText = (php artisan route:list --name=settings.slides.destroy | Out-String)
    if ($LASTEXITCODE -ne 0 -or $routeText -notmatch 'settings\.slides\.destroy') {
        throw 'Slide delete route was not found.'
    }

    Write-Host 'AMER slideshow delete hotfix installed successfully.' -ForegroundColor Green
}
finally {
    Pop-Location
}
