<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->canAccess('purchases.create') ?? false; }
    public function rules(): array
    {
        return [
            'supplier_id' => ['required','exists:suppliers,id'], 'inventory_location_id' => ['required','exists:inventory_locations,id'],
            'order_date' => ['required','date'], 'expected_date' => ['nullable','date','after_or_equal:order_date'],
            'currency' => ['required','string','size:3'], 'exchange_rate' => ['required','numeric','gt:0'],
            'discount_total' => ['nullable','numeric','min:0'], 'tax_total' => ['nullable','numeric','min:0'],
            'shipping_total' => ['nullable','numeric','min:0'], 'notes' => ['nullable','string','max:3000'],
            'items' => ['required','array','min:1'], 'items.*.product_variant_id' => ['required','distinct','exists:product_variants,id'],
            'items.*.ordered_quantity' => ['required','numeric','gt:0'], 'items.*.unit_cost' => ['required','numeric','min:0'],
            'items.*.discount' => ['nullable','numeric','min:0'], 'items.*.tax' => ['nullable','numeric','min:0'],
        ];
    }
}
