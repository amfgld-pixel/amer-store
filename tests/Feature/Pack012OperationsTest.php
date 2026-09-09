<?php
namespace Tests\Feature;use Illuminate\Foundation\Testing\RefreshDatabase;use Tests\TestCase;
class Pack012OperationsTest extends TestCase{use RefreshDatabase;public function test_health_check_reports_dependencies():void{$this->get('/health/ready')->assertOk()->assertJson(['status'=>'ok','database'=>'ok','cache'=>'ok']);}public function test_security_headers_are_present():void{$this->get('/')->assertHeader('X-Content-Type-Options','nosniff')->assertHeader('X-Frame-Options','SAMEORIGIN');}}
