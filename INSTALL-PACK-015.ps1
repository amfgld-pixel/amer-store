$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

function Invoke-Native {
    param([Parameter(Mandatory = $true)][scriptblock]$Command)
    & $Command
    if ($LASTEXITCODE -ne 0) { throw "Command failed with exit code $LASTEXITCODE." }
}

if (-not (Test-Path '.\artisan')) {
    throw 'Copy all extracted Pack-015 files into the Laravel project root containing artisan.'
}

$required = @(
    '.\app\Http\Controllers\Admin\StoreDesignController.php',
    '.\database\migrations\2026_08_30_000015_add_typography_to_store_slides.php',
    '.\public\js\amer-slide-editor.js',
    '.\public\css\amer-slide-typography.css',
    '.\app\Services\SlideImageProcessor.php',
    '.\database\migrations\2026_08_30_000016_add_responsive_images_to_store_slides.php',
    '.\database\migrations\2026_09_10_000017_make_store_slide_text_optional.php',
    '.\public\js\amer-bulk-slide-builder.js',
    '.\public\css\amer-responsive-slides.css'
)
foreach ($file in $required) {
    if (-not (Test-Path $file)) { throw "Pack-015 file is missing: $file" }
}

Invoke-Native { php -l '.\app\Http\Controllers\Admin\StoreDesignController.php' }
Invoke-Native { php -l '.\app\Http\Controllers\Admin\SettingsHubController.php' }
Invoke-Native { php -l '.\app\Http\Requests\StoreSlideRequest.php' }
Invoke-Native { php -l '.\app\Models\StoreSlide.php' }
Invoke-Native { php -l '.\database\migrations\2026_08_30_000015_add_typography_to_store_slides.php' }
Invoke-Native { php -l '.\database\migrations\2026_08_30_000016_add_responsive_images_to_store_slides.php' }
Invoke-Native { php -l '.\database\migrations\2026_09_10_000017_make_store_slide_text_optional.php' }
Invoke-Native { php -l '.\app\Http\Requests\BulkStoreSlidesRequest.php' }
Invoke-Native { php -l '.\app\Services\SlideImageProcessor.php' }
& php -r "exit(extension_loaded('gd') && function_exists('imagewebp') ? 0 : 1);"
if ($LASTEXITCODE -ne 0) {
    throw 'PHP GD/WebP is required. Enable extension=gd in the active php.ini, restart the terminal, and run this installer again.'
}
Invoke-Native { composer dump-autoload }
Invoke-Native { php artisan optimize:clear }
Invoke-Native { php artisan migrate --force }

$routeText = (php artisan route:list --name=admin.settings.slides.update | Out-String)
if ($LASTEXITCODE -ne 0 -or $routeText -notmatch 'admin\.settings\.slides\.update') {
    throw 'Slide typography update route was not registered.'
}

$settingsRouteText = (php artisan route:list --name=admin.settings.index | Out-String)
if ($LASTEXITCODE -ne 0 -or $settingsRouteText -notmatch 'admin\.settings\.index') {
    throw 'Settings Hub route was not registered.'
}

$migrationText = (php artisan migrate:status | Out-String)
if ($LASTEXITCODE -ne 0 -or $migrationText -notmatch '2026_08_30_000015_add_typography_to_store_slides') {
    throw 'Pack-015 typography migration was not registered.'
}

if ($migrationText -notmatch '2026_08_30_000016_add_responsive_images_to_store_slides') {
    throw 'Pack-015 responsive image migration was not registered.'
}

if ($migrationText -notmatch '2026_09_10_000017_make_store_slide_text_optional') {
    throw 'Pack-015 optional slide text migration was not registered.'
}

$bulkRouteText = (php artisan route:list --name=admin.settings.slides.bulk | Out-String)
if ($LASTEXITCODE -ne 0 -or $bulkRouteText -notmatch 'admin\.settings\.slides\.bulk') {
    throw 'Multiple picture slide route was not registered.'
}

Write-Host 'AMER Pack-015 v1.6 Cloud Logo/Favicon Fix installed successfully.' -ForegroundColor Green
