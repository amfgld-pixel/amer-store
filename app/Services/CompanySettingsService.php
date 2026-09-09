<?php
namespace App\Services;
use App\Models\AuditLog;
use App\Models\Company;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
class CompanySettingsService
{
    public function update(Company $company, array $data): Company
    {
        return DB::transaction(function () use ($company, $data) {
            $old = $company->toArray();
            $company->update(Arr::only($data, $company->getFillable()));
            AuditLog::create([
                'user_id'=>auth()->id(),'event'=>'updated','auditable_type'=>Company::class,
                'auditable_id'=>$company->id,'old_values'=>$old,'new_values'=>$company->fresh()->toArray(),
                'ip_address'=>request()->ip(),'user_agent'=>request()->userAgent(),'created_at'=>now(),
            ]);
            return $company->fresh();
        });
    }
}
