<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContentPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('super-admin') ?? false;
    }

    public function rules(): array
    {
        $page = $this->route('page');

        return [
            'title' => ['required', 'string', 'max:180'],
            'title_bn' => ['nullable', 'string', 'max:180'],
            'slug' => ['required', 'alpha_dash', 'max:200', Rule::unique('content_pages')->ignore($page?->id)],
            'content' => ['nullable', 'string'],
            'content_bn' => ['nullable', 'string'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'meta_title' => ['nullable', 'string', 'max:160'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'is_published' => ['required', 'boolean'],
        ];
    }
}
