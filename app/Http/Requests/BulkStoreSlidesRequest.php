<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class BulkStoreSlidesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('super-admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'images' => ['required', 'array', 'min:1', 'max:20'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'titles' => ['required', 'array'],
            'titles.*' => ['nullable', 'string', 'max:500'],
            'subtitles' => ['nullable', 'array'],
            'subtitles.*' => ['nullable', 'string', 'max:1000'],
            'button_texts' => ['nullable', 'array'],
            'button_texts.*' => ['nullable', 'string', 'max:80'],
            'button_urls' => ['nullable', 'array'],
            'button_urls.*' => ['nullable', 'string', 'max:500'],
            'fit_modes' => ['required', 'array'],
            'fit_modes.*' => ['required', Rule::in(['cover', 'contain', 'original'])],
            'focal_x' => ['required', 'array'],
            'focal_x.*' => ['required', 'integer', 'between:0,100'],
            'focal_y' => ['required', 'array'],
            'focal_y.*' => ['required', 'integer', 'between:0,100'],
            'start_order' => ['required', 'integer', 'min:0'],
            'title_font' => ['required', Rule::in(['Poppins', 'Inter', 'Playfair Display', 'Montserrat', 'Manrope', 'DM Sans'])],
            'title_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'subtitle_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'text_alignment' => ['required', Rule::in(['left', 'center', 'right'])],
            'text_position' => ['required', Rule::in(['top', 'middle', 'bottom'])],
            'overlay_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'overlay_opacity' => ['required', 'integer', 'between:0,90'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $count = count($this->file('images', []));
            foreach (['titles', 'fit_modes', 'focal_x', 'focal_y'] as $field) {
                if (count((array) $this->input($field, [])) !== $count) {
                    $validator->errors()->add($field, 'Every selected picture must have its own slide settings.');
                }
            }
        });
    }
}
