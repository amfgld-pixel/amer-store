<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('super-admin') ?? false;
    }

    public function rules(): array
    {
        $mediaRules = $this->isMethod('post')
            ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'required_without_all:video,video_url']
            : ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'];

        return [
            'title' => ['nullable', 'string', 'max:500'],
            'subtitle' => ['nullable', 'string', 'max:1000'],
            'image' => $mediaRules,
            'video' => $this->isMethod('post')
                ? ['nullable', 'file', 'mimes:mp4,webm', 'max:51200', 'required_without_all:image,video_url']
                : ['nullable', 'file', 'mimes:mp4,webm', 'max:51200'],
            'video_url' => $this->isMethod('post')
                ? ['nullable', 'url', 'max:500', 'required_without_all:image,video']
                : ['nullable', 'url', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:80'],
            'button_url' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'title_font' => ['required', Rule::in(['Poppins', 'Inter', 'Playfair Display', 'Montserrat', 'Manrope', 'DM Sans'])],
            'title_size_desktop' => ['required', 'integer', 'between:18,140'],
            'title_size_tablet' => ['required', 'integer', 'between:16,110'],
            'title_size_mobile' => ['required', 'integer', 'between:14,80'],
            'subtitle_size_desktop' => ['required', 'integer', 'between:12,60'],
            'subtitle_size_tablet' => ['required', 'integer', 'between:12,50'],
            'subtitle_size_mobile' => ['required', 'integer', 'between:10,40'],
            'title_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'subtitle_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'font_weight' => ['required', Rule::in(['400', '500', '700'])],
            'text_alignment' => ['required', Rule::in(['left', 'center', 'right'])],
            'text_position' => ['required', Rule::in(['top', 'middle', 'bottom'])],
            'line_height' => ['required', 'numeric', 'between:0.8,2.5'],
            'letter_spacing' => ['required', 'numeric', 'between:-2,15'],
            'text_shadow' => ['required', Rule::in(['none', 'soft', 'strong'])],
            'overlay_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'overlay_opacity' => ['required', 'integer', 'between:0,90'],
            'button_font' => ['required', Rule::in(['Poppins', 'Inter', 'Playfair Display', 'Montserrat', 'Manrope', 'DM Sans'])],
            'button_size' => ['required', 'integer', 'between:10,32'],
            'button_text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'button_background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'image_fit' => ['required', Rule::in(['cover', 'contain', 'original'])],
            'image_focal_x' => ['required', 'integer', 'between:0,100'],
            'image_focal_y' => ['required', 'integer', 'between:0,100'],
        ];
    }
}
