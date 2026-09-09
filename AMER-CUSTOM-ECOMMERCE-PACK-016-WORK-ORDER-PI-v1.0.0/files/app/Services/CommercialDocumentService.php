<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Order;
use App\Models\ProformaInvoice;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;

class CommercialDocumentService
{
    public function createWorkOrder(Order $order,array $data):WorkOrder
    {
        return DB::transaction(function()use($order,$data):WorkOrder{
            $order=Order::with('items')->lockForUpdate()->findOrFail($order->id);
            $workOrder=WorkOrder::create(['number'=>$this->next('work_order','WO'),'order_id'=>$order->id,'status'=>'draft','priority'=>$data['priority'],'issue_date'=>$data['issue_date'],'required_date'=>$data['required_date']??null,'assigned_to'=>$data['assigned_to']??null,'instructions'=>$data['instructions']??null,'created_by'=>auth()->id()]);
            foreach($order->items as $i=>$item)$workOrder->items()->create(['order_item_id'=>$item->id,'line_order'=>$i+1,'product_name'=>$item->product_name,'variant_name'=>$item->variant_name,'sku'=>$item->sku,'quantity'=>$item->quantity,'uom'=>'PCS']);
            $this->audit('created','work_order',$workOrder->id,[],['number'=>$workOrder->number,'order_id'=>$order->id]);return $workOrder;
        });
    }
    public function createPi(Order $order,array $data):ProformaInvoice
    {
        return DB::transaction(function()use($order,$data):ProformaInvoice{
            $order=Order::with('items')->lockForUpdate()->findOrFail($order->id);
            $pi=ProformaInvoice::create(['number'=>$this->next('proforma_invoice','PI'),'order_id'=>$order->id,'status'=>'draft','pi_date'=>$data['pi_date'],'valid_until'=>$data['valid_until']??null,'customer_name'=>$order->customer_name,'email'=>$order->email,'phone'=>$order->phone,'address'=>collect([$order->address,$order->city,$order->division,$order->postal_code,$order->country_code])->filter()->implode(', '),'currency'=>$order->currency,'subtotal'=>$order->subtotal,'discount_total'=>$order->discount_total,'shipping_total'=>$order->shipping_total,'tax_total'=>$order->tax_total,'grand_total'=>$order->grand_total,'payment_terms'=>$data['payment_terms']??null,'delivery_terms'=>$data['delivery_terms']??null,'bank_details'=>$data['bank_details']??null,'notes'=>$data['notes']??null,'created_by'=>auth()->id()]);
            foreach($order->items as $i=>$item)$pi->items()->create(['order_item_id'=>$item->id,'line_order'=>$i+1,'product_name'=>$item->product_name,'variant_name'=>$item->variant_name,'sku'=>$item->sku,'quantity'=>$item->quantity,'unit_price'=>$item->unit_price,'line_total'=>$item->line_total]);
            $this->audit('created','proforma_invoice',$pi->id,[],['number'=>$pi->number,'order_id'=>$order->id]);return $pi;
        });
    }
    public function workOrderStatus(WorkOrder $workOrder,string $status):void
    {
        DB::transaction(function()use($workOrder,$status):void{$locked=WorkOrder::lockForUpdate()->findOrFail($workOrder->id);abort_unless($locked->status==='draft'||($locked->status==='released'&&$status==='completed'),422,'This Work Order is locked.');$old=$locked->toArray();$values=['status'=>$status];if($status==='released')$values+=['released_by'=>auth()->id(),'released_at'=>now()];if($status==='completed')$values['completed_at']=now();$locked->update($values);$this->audit('status_'.$status,'work_order',$locked->id,$old,$locked->fresh()->toArray());});
    }
    public function piStatus(ProformaInvoice $pi,string $status):void
    {
        DB::transaction(function()use($pi,$status):void{$locked=ProformaInvoice::lockForUpdate()->findOrFail($pi->id);abort_unless($locked->status==='draft',422,'This PI is locked.');$old=$locked->toArray();$values=['status'=>$status];if($status==='approved')$values+=['approved_by'=>auth()->id(),'approved_at'=>now()];$locked->update($values);$this->audit('status_'.$status,'proforma_invoice',$locked->id,$old,$locked->fresh()->toArray());});
    }
    private function next(string $key,string $prefix):string
    {
        $row=DB::table('commercial_sequences')->where('key',$key)->lockForUpdate()->first();if(!$row){DB::table('commercial_sequences')->insert(['key'=>$key,'prefix'=>$prefix,'next_number'=>1,'created_at'=>now(),'updated_at'=>now()]);$row=DB::table('commercial_sequences')->where('key',$key)->lockForUpdate()->first();}$number=$row->prefix.'-'.now()->format('Ym').'-'.str_pad((string)$row->next_number,5,'0',STR_PAD_LEFT);DB::table('commercial_sequences')->where('id',$row->id)->update(['next_number'=>$row->next_number+1,'updated_at'=>now()]);return $number;
    }
    private function audit(string $event,string $type,int $id,array $old,array $new):void{AuditLog::create(['user_id'=>auth()->id(),'event'=>$event,'auditable_type'=>$type,'auditable_id'=>$id,'old_values'=>$old,'new_values'=>$new,'ip_address'=>request()->ip(),'user_agent'=>request()->userAgent(),'created_at'=>now()]);}
}

