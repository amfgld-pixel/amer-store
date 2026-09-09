<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\View\View;
class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'company'=>Company::query()->first(),'users'=>User::query()->count(),
            'roles'=>Role::query()->count(),'activity'=>AuditLog::query()->latest('created_at')->limit(8)->get(),
        ]);
    }
}
