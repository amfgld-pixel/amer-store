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
    '.\resources\views\layouts\storefront.blade.php',
    '.\resources\views\storefront\shop.blade.php',
    '.\database\seeders\AmerCatalogSeeder.php',
    '.\database\migrations\2026_11_01_000018_rename_toys_category_to_kids.php',
    '.\database\migrations\2026_11_01_000019_order_kids_before_accessories.php'
)

foreach ($file in $required) {
    if (-not (Test-Path $file)) { throw "Hotfix file is missing: $file" }
}

Invoke-Native { php -l '.\database\migrations\2026_11_01_000018_rename_toys_category_to_kids.php' }
Invoke-Native { php -l '.\database\migrations\2026_11_01_000019_order_kids_before_accessories.php' }
Invoke-Native { php -l '.\database\seeders\AmerCatalogSeeder.php' }
Invoke-Native { composer dump-autoload }
Invoke-Native { php artisan optimize:clear }
Invoke-Native { php artisan migrate --force }

$migrationText = (php artisan migrate:status | Out-String)
if ($LASTEXITCODE -ne 0 -or $migrationText -notmatch '2026_11_01_000018_rename_toys_category_to_kids') {
    throw 'Toys-to-Kids migration was not registered.'
}

if ($migrationText -notmatch '2026_11_01_000019_order_kids_before_accessories') {
    throw 'Kids-before-Accessories order migration was not registered.'
}

Write-Host ''
Write-Host 'AMER Toys-to-Kids category hotfix v1.1 installed successfully.' -ForegroundColor Green
Write-Host 'Men, Women and Accessories remain unchanged.' -ForegroundColor Green
