$ErrorActionPreference = 'Stop'
$Expected = 'G:\Website build\amer-store'
if (-not (Get-Location).Path.Equals($Expected, [System.StringComparison]::OrdinalIgnoreCase)) { throw "Run from AMER Store project root: $Expected" }
& php (Join-Path $PSScriptRoot 'VERIFY-PACK-016.php'); if ($LASTEXITCODE -ne 0) { throw 'AMER Pack-016 file verification failed.' }
& php artisan route:list --name=admin.work-orders; if ($LASTEXITCODE -ne 0) { throw 'Work Order routes are not registered.' }
& php artisan route:list --name=admin.proforma-invoices; if ($LASTEXITCODE -ne 0) { throw 'Proforma Invoice routes are not registered.' }
& php artisan view:cache; if ($LASTEXITCODE -ne 0) { throw 'Blade view compilation failed.' }
& php artisan optimize:clear; if ($LASTEXITCODE -ne 0) { throw 'Final cache clearing failed.' }
Write-Host 'AMER Pack-016 verification passed.' -ForegroundColor Green
