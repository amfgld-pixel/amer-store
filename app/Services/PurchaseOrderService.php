<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseOrderService
{
    public function create(array $data, int $userId): PurchaseOrder
    {
        return DB::transaction(function () use ($data, $userId) {
            $items = $data['items']; unset($data['items']);
            $subtotal = collect($items)->sum(fn ($i) => ((float) $i['ordered_quantity'] * (float) $i['unit_cost']) - (float) ($i['discount'] ?? 0) + (float) ($i['tax'] ?? 0));
            $order = PurchaseOrder::create(array_merge($data, ['uuid'=>(string) Str::uuid(),'number'=>$this->nextNumber(),'status'=>'draft','subtotal'=>$subtotal,'grand_total'=>$subtotal - (float)($data['discount_total'] ?? 0) + (float)($data['tax_total'] ?? 0) + (float)($data['shipping_total'] ?? 0),'created_by'=>$userId]));
            foreach ($items as $item) {
                $item['line_total'] = ((float)$item['ordered_quantity'] * (float)$item['unit_cost']) - (float)($item['discount'] ?? 0) + (float)($item['tax'] ?? 0);
                $order->items()->create($item);
            }
            return $order->fresh(['items','supplier','location']);
        });
    }

    public function approve(PurchaseOrder $order, int $userId): void
    {
        abort_unless($order->status === 'draft', 422, 'Only draft purchase orders can be approved.');
        $order->update(['status'=>'approved','approved_by'=>$userId,'approved_at'=>now()]);
    }

    private function nextNumber(): string
    {
        $prefix = 'PO-'.now()->format('Ym').'-';
        $last = PurchaseOrder::query()->where('number','like',$prefix.'%')->lockForUpdate()->max('number');
        return $prefix.str_pad((string)(((int)substr((string)$last,-5))+1),5,'0',STR_PAD_LEFT);
    }
}
