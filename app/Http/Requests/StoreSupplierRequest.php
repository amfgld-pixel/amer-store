<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->canAccess('suppliers.manage') ?? false; }
    public function rules(): array
    {
        return [
            'code' => ['required','string','max:40',Rule::unique('suppliers')->ignore($this->route('supplier'))],
            'name' => ['required','string','max:150'], 'name_bn' => ['nullable','string','max:150'],
            'contact_person' => ['nullable','string','max:150'], 'email' => ['nullable','email','max:150'],
            'phone' => ['nullable','string','max:30'], 'address' => ['nullable','string','max:1000'],
            'country_code' => ['required','string','size:2'], 'currency' => ['required','string','size:3'],
            'payment_terms' => ['nullable','string','max:255'], 'is_active' => ['sometimes','boolean'],
        ];
    }
}
