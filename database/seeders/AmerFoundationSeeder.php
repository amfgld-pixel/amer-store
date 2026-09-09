<?php
namespace Database\Seeders;
use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class AmerFoundationSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::firstOrCreate(['name'=>'AMER'], [
            'name_bn'=>'আমের','email'=>'amer@classicdin.com','phone'=>'+8801349482160',
            'whatsapp'=>'+8801349482160','facebook_url'=>'https://www.facebook.com/AMERFT/',
            'country_code'=>'BD','currency_code'=>'BDT','timezone'=>'Asia/Dhaka','locale'=>'en','is_active'=>true,
        ]);
        $permissions = collect([
            ['Settings View','settings.view','Settings'],['Settings Update','settings.update','Settings'],
            ['Users View','users.view','Users'],['Users Manage','users.manage','Users'],
            ['Audit View','audit.view','Security'],['Dashboard View','dashboard.view','Dashboard'],
        ])->map(fn ($p) => Permission::firstOrCreate(['slug'=>$p[1]], ['name'=>$p[0],'group'=>$p[2]]));
        $role = Role::firstOrCreate(['slug'=>'super-admin'], ['name'=>'Super Admin','is_system'=>true]);
        $role->permissions()->sync($permissions->pluck('id'));
        $user = User::firstOrCreate(['email'=>'admin@amer.com'], ['name'=>'AMER Super Admin','password'=>Hash::make('ChangeMe@123'),'email_verified_at'=>now()]);
        $user->roles()->syncWithoutDetaching([$role->id]);
    }
}
