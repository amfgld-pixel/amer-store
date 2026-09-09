param(
    [Parameter(Mandatory = $true)]
    [string]$Url
)

$ErrorActionPreference = 'Stop'
$baseUrl = $Url.TrimEnd('/')
$targets = @(
    @{ Name = 'Homepage'; Url = $baseUrl },
    @{ Name = 'Health'; Url = "$baseUrl/up" }
)

$failed = $false
foreach ($target in $targets) {
    try {
        $response = Invoke-WebRequest -Uri $target.Url -Method Get -TimeoutSec 30 -UseBasicParsing
        if ($response.StatusCode -ge 200 -and $response.StatusCode -lt 400) {
            Write-Host "$($target.Name): HTTP $($response.StatusCode)" -ForegroundColor Green
        }
        else {
            Write-Host "$($target.Name): HTTP $($response.StatusCode)" -ForegroundColor Red
            $failed = $true
        }
    }
    catch {
        Write-Host "$($target.Name): FAILED - $($_.Exception.Message)" -ForegroundColor Red
        $failed = $true
    }
}

if ($failed) {
    exit 1
}

Write-Host 'AMER public health check passed.' -ForegroundColor Green

