<?php

namespace Tests\Feature;

use App\Models\InventoryItem;
use App\Models\InventoryLocation;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use App\Services\InventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class Pack003InventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_received_purchase_updates_stock_and_ledger_once(): void
    {
        $this->seed();
        $user=User::firstOrFail(); $supplier=Supplier::create(['code'=>'TEST-SUP','name'=>'Test Supplier','country_code'=>'BD','currency'=>'BDT','is_active'=>true]); $location=InventoryLocation::firstOrFail(); $variant=$this->variant();
        $order=PurchaseOrder::create(['uuid'=>(string)Str::uuid(),'number'=>'PO-TEST-1','supplier_id'=>$supplier->id,'inventory_location_id'=>$location->id,'order_date'=>now(),'status'=>'approved','currency'=>'BDT','exchange_rate'=>1,'grand_total'=>1000,'created_by'=>$user->id]);
        $line=PurchaseOrderItem::create(['purchase_order_id'=>$order->id,'product_variant_id'=>$variant->id,'ordered_quantity'=>10,'unit_cost'=>100,'line_total'=>1000]);
        app(InventoryService::class)->receive($order,[['id'=>$line->id,'quantity'=>10]],$user->id);
        $this->assertEquals('10.0000',InventoryItem::firstOrFail()->quantity_on_hand);
        $this->assertDatabaseCount('stock_movements',1); $this->assertEquals('received',$order->fresh()->status);
    }

    public function test_negative_adjustment_cannot_create_negative_stock(): void
    {
        $this->seed(); $this->expectException(\Illuminate\Validation\ValidationException::class);
        app(InventoryService::class)->adjust($this->variant()->id,InventoryLocation::firstOrFail()->id,-1,0,'Invalid',User::firstOrFail()->id);
    }

    private function variant(): ProductVariant
    {
        $product=Product::create(['name'=>'Test Product','slug'=>'test-product-'.Str::random(6),'product_type'=>'variable','status'=>'active']);
        return ProductVariant::create(['product_id'=>$product->id,'name'=>'Default','sku'=>'TEST-'.Str::upper(Str::random(8)),'cost_price'=>100,'price'=>200,'track_inventory'=>true,'is_active'=>true]);
    }
}
