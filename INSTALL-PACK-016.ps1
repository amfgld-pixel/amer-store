$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

function Invoke-Native {
    param([Parameter(Mandatory = $true)][scriptblock]$Command)
    & $Command
    if ($LASTEXITCODE -ne 0) { throw "Command failed with exit code $LASTEXITCODE." }
}

if (-not (Test-Path '.\artisan')) {
    throw 'Copy all extracted Pack-016 files into the Laravel project root containing artisan.'
}

$requiredFiles = @(
    '.\app\Http\Controllers\Admin\ProductController.php',
    '.\app\Http\Requests\StoreProductRequest.php',
    '.\app\Services\CatalogService.php',
    '.\resources\views\admin\catalog\product-form.blade.php',
    '.\resources\views\storefront\partials\product-card.blade.php',
    '.\resources\views\storefront\product.blade.php'
)

foreach ($file in $requiredFiles) {
    if (-not (Test-Path $file)) { throw "Pack-016 file is missing: $file" }
}

Invoke-Native { php -l '.\app\Http\Controllers\Admin\ProductController.php' }
Invoke-Native { php -l '.\app\Http\Requests\StoreProductRequest.php' }
Invoke-Native { php -l '.\app\Services\CatalogService.php' }
Invoke-Native { composer dump-autoload }
Invoke-Native { php artisan optimize:clear }

$routeText = (php artisan route:list --name=admin.products.create | Out-String)
if ($LASTEXITCODE -ne 0 -or $routeText -notmatch 'admin\.products\.create') {
    throw 'Product create route is not registered.'
}

Write-Host ''
Write-Host 'AMER Pack-016 Product Picture Gallery installed successfully.' -ForegroundColor Green
Write-Host 'For Laravel Cloud: commit, push and deploy. No new migration is required.' -ForegroundColor Yellow
