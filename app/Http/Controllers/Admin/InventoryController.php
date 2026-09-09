<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdjustStockRequest;
use App\Models\InventoryItem;
use App\Models\InventoryLocation;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Services\InventoryService;

class InventoryController extends Controller
{
    public function index()
    {
        return view('admin.inventory.index', ['items'=>InventoryItem::with(['variant.product','location'])->paginate(40),'locations'=>InventoryLocation::where('is_active',true)->get(),'variants'=>ProductVariant::with('product')->where('is_active',true)->get()]);
    }
    public function movements() { return view('admin.inventory.movements', ['movements'=>StockMovement::with(['variant.product','location'])->latest('occurred_at')->paginate(50)]); }
    public function adjust(AdjustStockRequest $request, InventoryService $service) { $v=$request->validated(); $service->adjust((int)$v['product_variant_id'],(int)$v['inventory_location_id'],(float)$v['quantity'],(float)($v['unit_cost']??0),$v['reason'],(int)$request->user()->id); return back()->with('status','Stock adjustment posted.'); }
}
