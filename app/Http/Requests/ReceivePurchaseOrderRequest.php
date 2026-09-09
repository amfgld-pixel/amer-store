<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceivePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->canAccess('purchases.receive') ?? false; }
    public function rules(): array
    {
        return ['items'=>['required','array','min:1'],'items.*.id'=>['required','distinct','exists:purchase_order_items,id'],'items.*.quantity'=>['required','numeric','min:0']];
    }
}
