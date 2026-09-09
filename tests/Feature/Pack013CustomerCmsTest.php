<?php

namespace Tests\Feature;

use App\Models\ContentPage;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Pack013CustomerCmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_open_registration_login_and_tracking(): void
    {
        $this->get('/register')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/track-order')->assertOk();
    }

    public function test_customer_can_only_view_own_order(): void
    {
        $customer = User::factory()->create();
        $other = User::factory()->create();
        $order = Order::create([
            'uuid' => fake()->uuid(), 'number' => 'AMER-TEST-001', 'user_id' => $customer->id,
            'status' => 'confirmed', 'payment_status' => 'paid', 'currency' => 'BDT',
            'subtotal' => 1000, 'grand_total' => 1000, 'customer_name' => $customer->name,
            'email' => $customer->email, 'phone' => '01349482160', 'country_code' => 'BD',
            'city' => 'Dhaka', 'address' => 'Test address',
        ]);

        $this->actingAs($customer)->get(route('customer.orders.show', $order))->assertOk();
        $this->actingAs($other)->get(route('customer.orders.show', $order))->assertForbidden();
    }

    public function test_only_published_content_page_is_public(): void
    {
        $published = ContentPage::create(['title' => 'About', 'slug' => 'about', 'is_published' => true]);
        $draft = ContentPage::create(['title' => 'Draft', 'slug' => 'draft', 'is_published' => false]);

        $this->get(route('pages.show', $published))->assertOk();
        $this->get(route('pages.show', $draft))->assertNotFound();
    }
}
