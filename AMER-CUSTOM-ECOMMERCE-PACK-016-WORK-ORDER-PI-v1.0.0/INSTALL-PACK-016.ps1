$ErrorActionPreference = 'Stop'
$Project = (Get-Location).Path
$Expected = 'G:\Website build\amer-store'
if (-not $Project.Equals($Expected, [System.StringComparison]::OrdinalIgnoreCase)) { throw "Run from AMER Store project root: $Expected" }
if (-not (Test-Path -LiteralPath (Join-Path $Project 'artisan'))) { throw 'Laravel artisan file not found.' }
$Payload = Join-Path $PSScriptRoot 'files'
$Backup = Join-Path $Project ('.amer-pack-backups\pack-016-' + (Get-Date -Format 'yyyyMMdd-HHmmss'))
New-Item -ItemType Directory -Path $Backup -Force | Out-Null
$Existing=@('app\Http\Controllers\StorefrontController.php','routes\web.php','resources\views\layouts\admin.blade.php','resources\views\storefront\home.blade.php')
foreach ($Relative in $Existing) { $Source = Join-Path $Project $Relative; if (Test-Path -LiteralPath $Source) { $Destination = Join-Path $Backup $Relative; New-Item -ItemType Directory -Path (Split-Path $Destination -Parent) -Force | Out-Null; Copy-Item -LiteralPath $Source -Destination $Destination -Force } }
Get-ChildItem -LiteralPath $Payload -Filter '*.php' -File -Recurse | ForEach-Object { $Lint = & php -l $_.FullName 2>&1; if ($LASTEXITCODE -ne 0) { throw "PHP syntax validation failed: $Lint" } }
foreach ($Check in @('INSTALL-PATCH.php','VERIFY-PACK-016.php')) { $Lint = & php -l (Join-Path $PSScriptRoot $Check) 2>&1; if ($LASTEXITCODE -ne 0) { throw "Pack script syntax validation failed: $Lint" } }
Get-ChildItem -LiteralPath $Payload -File -Recurse | ForEach-Object { $Relative = $_.FullName.Substring($Payload.Length).TrimStart('\'); $Destination = Join-Path $Project $Relative; New-Item -ItemType Directory -Path (Split-Path $Destination -Parent) -Force | Out-Null; Copy-Item -LiteralPath $_.FullName -Destination $Destination -Force }
& php (Join-Path $PSScriptRoot 'INSTALL-PATCH.php'); if ($LASTEXITCODE -ne 0) { throw 'Route/navigation/storefront patch failed.' }
& php artisan migrate --force; if ($LASTEXITCODE -ne 0) { throw 'Pack-016 database migration failed.' }
& php artisan optimize:clear; if ($LASTEXITCODE -ne 0) { throw 'Laravel cache clearing failed.' }
Write-Host "AMER Pack-016 installed. Backup: $Backup" -ForegroundColor Green
Write-Host 'Storefront 500 fix, Automatic Work Orders and Proforma Invoices are ready.' -ForegroundColor Green
