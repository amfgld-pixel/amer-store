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
    '.\app\Http\Requests\StoreSlideRequest.php',
    '.\app\Http\Requests\BulkStoreSlidesRequest.php',
    '.\app\Http\Controllers\Admin\StoreDesignController.php',
    '.\database\migrations\2026_09_10_000017_make_store_slide_text_optional.php',
    '.\resources\views\admin\settings\partials\slide-fields.blade.php',
    '.\resources\views\admin\settings\partials\bulk-slide-builder.blade.php',
    '.\resources\views\admin\settings\store-design.blade.php',
    '.\resources\views\storefront\home.blade.php'
)

foreach ($file in $required) {
    if (-not (Test-Path $file)) { throw "Optional slide text hotfix file is missing: $file" }
}

Invoke-Native { php -l '.\app\Http\Requests\StoreSlideRequest.php' }
Invoke-Native { php -l '.\app\Http\Requests\BulkStoreSlidesRequest.php' }
Invoke-Native { php -l '.\app\Http\Controllers\Admin\StoreDesignController.php' }
Invoke-Native { php -l '.\database\migrations\2026_09_10_000017_make_store_slide_text_optional.php' }
Invoke-Native { composer dump-autoload }
Invoke-Native { php artisan optimize:clear }
Invoke-Native { php artisan migrate --force }

$migrationText = (php artisan migrate:status | Out-String)
if ($LASTEXITCODE -ne 0 -or $migrationText -notmatch '2026_09_10_000017_make_store_slide_text_optional') {
    throw 'Optional slide text migration was not registered.'
}

Write-Host 'AMER Pack-015 v1.2 Optional Slide Text hotfix installed successfully.' -ForegroundColor Green
