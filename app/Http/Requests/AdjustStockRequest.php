<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdjustStockRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->canAccess('inventory.adjust') ?? false; }
    public function rules(): array
    {
        return ['product_variant_id'=>['required','exists:product_variants,id'],'inventory_location_id'=>['required','exists:inventory_locations,id'],'quantity'=>['required','numeric','not_in:0'],'unit_cost'=>['nullable','numeric','min:0'],'reason'=>['required','string','max:500']];
    }
}
