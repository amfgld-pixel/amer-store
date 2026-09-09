<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Order;
use App\Models\ProformaInvoice;
use App\Services\CommercialDocumentService;
use Illuminate\Http\Request;
class ProformaInvoiceController extends Controller
{
    public function __construct(private CommercialDocumentService $service){}
    public function index(){return view('admin.proforma-invoices.index',['invoices'=>ProformaInvoice::with('order')->latest()->paginate(50)]);}
    public function create(){return view('admin.proforma-invoices.create',['orders'=>Order::with('items')->latest()->limit(500)->get()]);}
    public function store(Request $r){$d=$r->validate(['order_id'=>'required|exists:orders,id','pi_date'=>'required|date','valid_until'=>'nullable|date|after_or_equal:pi_date','payment_terms'=>'nullable|string|max:5000','delivery_terms'=>'nullable|string|max:5000','bank_details'=>'nullable|string|max:5000','notes'=>'nullable|string|max:5000']);$pi=$this->service->createPi(Order::findOrFail($d['order_id']),$d);return redirect()->route('admin.proforma-invoices.show',$pi)->with('status','Proforma Invoice created as Draft.');}
    public function show(ProformaInvoice $proformaInvoice){return view('admin.proforma-invoices.show',['pi'=>$proformaInvoice->load('order','items'),'company'=>Company::where('is_active',true)->first()]);}
    public function status(Request $r,ProformaInvoice $proformaInvoice){$d=$r->validate(['status'=>'required|in:approved,cancelled']);$this->service->piStatus($proformaInvoice,$d['status']);return back()->with('status','Proforma Invoice status updated.');}
}

