<?php

namespace App\Services;

use App\Models\InventoryItem;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InventoryService
{
    public function receive(PurchaseOrder $purchaseOrder, array $lines, int $userId): PurchaseOrder
    {
        return DB::transaction(function () use ($purchaseOrder, $lines, $userId) {
            $order = PurchaseOrder::query()->lockForUpdate()->findOrFail($purchaseOrder->id);
            if (! in_array($order->status, ['approved','partially_received'], true)) {
                throw ValidationException::withMessages(['status' => 'Only approved purchase orders can be received.']);
            }

            foreach ($lines as $input) {
                $quantity = (float) $input['quantity'];
                if ($quantity <= 0) { continue; }
                $line = $order->items()->lockForUpdate()->findOrFail($input['id']);
                $remaining = (float) $line->ordered_quantity - (float) $line->received_quantity;
                if ($quantity > $remaining) {
                    throw ValidationException::withMessages(['items' => "Received quantity exceeds remaining quantity for line {$line->id}."]);
                }
                $this->increase($line->product_variant_id, $order->inventory_location_id, $quantity, (float) $line->unit_cost, $order, 'purchase_receive', $userId);
                $line->increment('received_quantity', $quantity);
            }

            $order->load('items');
            $complete = $order->items->every(fn ($item) => (float) $item->received_quantity >= (float) $item->ordered_quantity);
            $receivedAny = $order->items->contains(fn ($item) => (float) $item->received_quantity > 0);
            $order->update(['status'=>$complete ? 'received' : ($receivedAny ? 'partially_received' : 'approved'),'received_at'=>$complete ? now() : null]);
            return $order->fresh(['items','supplier','location']);
        }, 3);
    }

    public function adjust(int $variantId, int $locationId, float $quantity, float $unitCost, string $reason, int $userId): InventoryItem
    {
        return DB::transaction(function () use ($variantId, $locationId, $quantity, $unitCost, $reason, $userId) {
            $item = $this->lockedItem($variantId, $locationId);
            $balance = (float) $item->quantity_on_hand + $quantity;
            if ($balance < 0) { throw ValidationException::withMessages(['quantity'=>'Adjustment would create negative stock.']); }
            $item->update(['quantity_on_hand'=>$balance,'average_cost'=>$quantity > 0 && $unitCost > 0 ? $unitCost : $item->average_cost]);
            $this->movement($variantId, $locationId, 'adjustment', $quantity, $unitCost, $balance, null, $reason, $userId);
            return $item->fresh();
        }, 3);
    }

    private function increase(int $variantId, int $locationId, float $quantity, float $unitCost, object $reference, string $type, int $userId): void
    {
        $item = $this->lockedItem($variantId, $locationId);
        $oldQty = (float) $item->quantity_on_hand;
        $newQty = $oldQty + $quantity;
        $average = $newQty > 0 ? ((($oldQty * (float) $item->average_cost) + ($quantity * $unitCost)) / $newQty) : $unitCost;
        $item->update(['quantity_on_hand'=>$newQty,'average_cost'=>$average]);
        $this->movement($variantId, $locationId, $type, $quantity, $unitCost, $newQty, $reference, null, $userId);
    }

    private function lockedItem(int $variantId, int $locationId): InventoryItem
    {
        InventoryItem::query()->firstOrCreate(['product_variant_id'=>$variantId,'inventory_location_id'=>$locationId]);
        return InventoryItem::query()->where('product_variant_id',$variantId)->where('inventory_location_id',$locationId)->lockForUpdate()->firstOrFail();
    }

    private function movement(int $variantId, int $locationId, string $type, float $quantity, float $unitCost, float $balance, ?object $reference, ?string $reason, int $userId): void
    {
        StockMovement::create(['uuid'=>(string) Str::uuid(),'product_variant_id'=>$variantId,'inventory_location_id'=>$locationId,'type'=>$type,'quantity'=>$quantity,'unit_cost'=>$unitCost,'balance_after'=>$balance,'reference_type'=>$reference ? $reference::class : null,'reference_id'=>$reference?->id,'reason'=>$reason,'created_by'=>$userId,'occurred_at'=>now()]);
    }
}
