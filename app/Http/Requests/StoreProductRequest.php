<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAccess('products.manage') ?? false;
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'brand_id' => ['nullable', 'exists:brands,id'],
            'name' => ['required', 'string', 'max:200'],
            'name_bn' => ['nullable', 'string', 'max:200'],
            'slug' => ['required', 'alpha_dash', 'max:220', Rule::unique('products')->ignore($product?->id)],
            'product_type' => ['required', 'in:simple,variable'],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'short_description_bn' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'description_bn' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,active,archived'],
            'is_featured' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'meta_title' => ['nullable', 'string', 'max:160'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'category_ids' => ['required', 'array', 'min:1'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.sku' => ['required', 'string', 'max:100', 'distinct'],
            'variants.*.barcode' => ['nullable', 'string', 'max:100', 'distinct'],
            'variants.*.name' => ['nullable', 'string', 'max:150'],
            'variants.*.cost_price' => ['required', 'numeric', 'min:0'],
            'variants.*.price' => ['required', 'numeric', 'min:0'],
            'variants.*.compare_at_price' => ['nullable', 'numeric', 'gte:variants.*.price'],
            'variants.*.weight' => ['nullable', 'numeric', 'min:0'],
            'variants.*.is_active' => ['required', 'boolean'],
            'main_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'gallery_images' => ['nullable', 'array', 'max:10'],
            'gallery_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'remove_image_ids' => ['nullable', 'array'],
            'remove_image_ids.*' => [
                'integer',
                Rule::exists('product_images', 'id')->where(
                    fn ($query) => $query->where('product_id', $product?->id ?? 0)
                ),
            ],
            'primary_image_id' => [
                'nullable',
                'integer',
                Rule::exists('product_images', 'id')->where(
                    fn ($query) => $query->where('product_id', $product?->id ?? 0)
                ),
            ],
        ];
    }
}
