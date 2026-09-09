<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Services\CompanySettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
class CompanySettingsController extends Controller
{
    public function edit(Company $company): View { Gate::authorize('view', $company); return view('admin.settings.company', compact('company')); }
    public function update(UpdateCompanyRequest $request, Company $company, CompanySettingsService $service): RedirectResponse
    {
        Gate::authorize('update', $company); $service->update($company, $request->validated());
        return back()->with('status', 'Company settings updated successfully.');
    }
}
