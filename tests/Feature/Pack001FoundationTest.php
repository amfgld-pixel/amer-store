<?php
namespace Tests\Feature;
use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class Pack001FoundationTest extends TestCase
{
    use RefreshDatabase;
    public function test_guest_cannot_open_admin_dashboard(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }
    public function test_super_admin_can_open_dashboard(): void
    {
        $this->seed(\Database\Seeders\AmerFoundationSeeder::class);
        $user = User::where('email','admin@amer.com')->firstOrFail();
        $this->actingAs($user)->get('/admin')->assertOk()->assertSee('Good morning');
    }
    public function test_permissions_are_seeded(): void
    {
        $this->seed(\Database\Seeders\AmerFoundationSeeder::class);
        $this->assertDatabaseHas('companies',['name'=>'AMER']);
        $this->assertDatabaseHas('roles',['slug'=>'super-admin']);
        $this->assertDatabaseHas('permissions',['slug'=>'settings.update']);
    }
}
