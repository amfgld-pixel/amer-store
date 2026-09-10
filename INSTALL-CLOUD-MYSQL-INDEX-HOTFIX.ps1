$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

if (-not (Test-Path '.\artisan')) {
    throw 'Copy all hotfix contents into the AMER Laravel project root containing artisan.'
}

$migration = '.\database\migrations\2026_08_23_000003_create_inventory_purchase_tables.php'
if (-not (Test-Path $migration)) {
    throw "Migration file is missing: $migration"
}

php -l $migration
if ($LASTEXITCODE -ne 0) { throw 'Migration PHP syntax validation failed.' }

$content = Get-Content $migration -Raw
if ($content -notmatch 'stock_move_variant_location_date_idx') {
    throw 'Short MySQL index name was not installed.'
}

php artisan optimize:clear
if ($LASTEXITCODE -ne 0) { throw 'Laravel cache clear failed.' }

Write-Host 'AMER Cloud MySQL index hotfix installed successfully.' -ForegroundColor Green
Write-Host 'Next: commit and push this migration to GitHub, then run migrate:fresh --force once on the empty Cloud database.' -ForegroundColor Cyan
