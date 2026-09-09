<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\WorkOrder;
use App\Services\CommercialDocumentService;
use Illuminate\Http\Request;
class WorkOrderController extends Controller
{
    public function __construct(private CommercialDocumentService $service){}
    public function index(){return view('admin.work-orders.index',['workOrders'=>WorkOrder::with('order')->latest()->paginate(50)]);}
    public function create(){return view('admin.work-orders.create',['orders'=>Order::with('items')->latest()->limit(500)->get()]);}
    public function store(Request $r){$d=$r->validate(['order_id'=>'required|exists:orders,id','issue_date'=>'required|date','required_date'=>'nullable|date|after_or_equal:issue_date','priority'=>'required|in:normal,urgent,critical','assigned_to'=>'nullable|string|max:150','instructions'=>'nullable|string|max:5000']);$w=$this->service->createWorkOrder(Order::findOrFail($d['order_id']),$d);return redirect()->route('admin.work-orders.show',$w)->with('status','Automatic Work Order created as Draft.');}
    public function show(WorkOrder $workOrder){return view('admin.work-orders.show',['workOrder'=>$workOrder->load('order','items')]);}
    public function status(Request $r,WorkOrder $workOrder){$d=$r->validate(['status'=>'required|in:released,completed,cancelled']);$this->service->workOrderStatus($workOrder,$d['status']);return back()->with('status','Work Order status updated.');}
}

