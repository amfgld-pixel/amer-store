<?php
namespace App\Policies;
use App\Models\Company;
use App\Models\User;
class CompanyPolicy
{
    public function view(User $user, Company $company): bool { return $user->canAccess('settings.view'); }
    public function update(User $user, Company $company): bool { return $user->canAccess('settings.update'); }
    public function delete(User $user, Company $company): bool { return false; }
}
