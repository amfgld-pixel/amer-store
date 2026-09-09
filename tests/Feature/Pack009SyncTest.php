<?php
namespace Tests\Feature;use Illuminate\Foundation\Testing\RefreshDatabase;use Tests\TestCase;
class Pack009SyncTest extends TestCase{use RefreshDatabase;public function test_sync_endpoint_requires_authentication():void{$this->postJson('/admin/pos/sync',[])->assertUnauthorized();}public function test_client_uuid_is_unique():void{$this->seed();$this->assertTrue(\Illuminate\Support\Facades\Schema::hasColumn('pos_sales','client_uuid'));}}
