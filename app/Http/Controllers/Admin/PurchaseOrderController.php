<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReceivePurchaseOrderRequest;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Models\InventoryLocation;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Services\InventoryService;
use App\Services\PurchaseOrderService;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index() { return view('admin.inventory.purchases', ['orders'=>PurchaseOrder::with(['supplier','location'])->latest()->paginate(30)]); }
    public function create() { return view('admin.inventory.purchase-form', ['suppliers'=>Supplier::where('is_active',true)->get(),'locations'=>InventoryLocation::where('is_active',true)->get(),'variants'=>ProductVariant::with('product')->where('is_active',true)->get()]); }
    public function store(StorePurchaseOrderRequest $request, PurchaseOrderService $service) { $order=$service->create($request->validated(),(int)$request->user()->id); return redirect()->route('admin.purchases.show',$order)->with('status','Purchase order created.'); }
    public function show(PurchaseOrder $purchase) { return view('admin.inventory.purchase-show', ['purchase'=>$purchase->load(['supplier','location','items.variant.product'])]); }
    public function approve(Request $request, PurchaseOrder $purchase, PurchaseOrderService $service) { abort_unless($request->user()->canAccess('purchases.approve'),403); $service->approve($purchase,(int)$request->user()->id); return back()->with('status','Purchase order approved.'); }
    public function receive(ReceivePurchaseOrderRequest $request, PurchaseOrder $purchase, InventoryService $service) { $service->receive($purchase,$request->validated('items'),(int)$request->user()->id); return back()->with('status','Goods received and stock ledger updated.'); }
}
