$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

$failures = New-Object System.Collections.Generic.List[string]
$warnings = New-Object System.Collections.Generic.List[string]

foreach ($required in @('artisan', 'composer.json', 'app', 'routes', '.env')) {
    if (-not (Test-Path $required)) {
        $failures.Add("Missing: $required")
    }
}

foreach ($command in @('php', 'composer', 'npm.cmd')) {
    if (-not (Get-Command $command -ErrorAction SilentlyContinue)) {
        $failures.Add("Command not available in PATH: $command")
    }
}

if (Test-Path '.env') {
    $envText = Get-Content '.env' -Raw
    if ($envText -match '(?m)^APP_DEBUG\s*=\s*true\s*$') {
        $warnings.Add('APP_DEBUG is true. Production must use false.')
    }
    if ($envText -match '(?m)^APP_ENV\s*=\s*local\s*$') {
        $warnings.Add('APP_ENV is local. Production should use production.')
    }
    if ($envText -notmatch '(?m)^APP_KEY\s*=\s*base64:.+') {
        $failures.Add('APP_KEY is missing or invalid.')
    }
}

if ($warnings.Count -gt 0) {
    Write-Host 'Warnings:' -ForegroundColor Yellow
    $warnings | ForEach-Object { Write-Host " - $_" -ForegroundColor Yellow }
}

if ($failures.Count -gt 0) {
    Write-Host 'Pre-flight failed:' -ForegroundColor Red
    $failures | ForEach-Object { Write-Host " - $_" -ForegroundColor Red }
    exit 1
}

php artisan about
php artisan migrate:status
php artisan route:list --except-vendor

Write-Host 'AMER pre-flight check passed.' -ForegroundColor Green

