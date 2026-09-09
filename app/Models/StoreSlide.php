<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSlide extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'image_path', 'video_path', 'video_url', 'button_text', 'button_url',
        'sort_order', 'is_active', 'title_font', 'title_size_desktop', 'title_size_tablet',
        'title_size_mobile', 'subtitle_size_desktop', 'subtitle_size_tablet', 'subtitle_size_mobile',
        'title_color', 'subtitle_color', 'font_weight', 'text_alignment', 'text_position',
        'line_height', 'letter_spacing', 'text_shadow', 'overlay_color', 'overlay_opacity',
        'button_font', 'button_size', 'button_text_color', 'button_background_color',
        'image_path_tablet', 'image_path_mobile', 'image_original_width', 'image_original_height',
        'image_fit', 'image_focal_x', 'image_focal_y',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'title_size_desktop' => 'integer',
            'title_size_tablet' => 'integer',
            'title_size_mobile' => 'integer',
            'subtitle_size_desktop' => 'integer',
            'subtitle_size_tablet' => 'integer',
            'subtitle_size_mobile' => 'integer',
            'line_height' => 'decimal:2',
            'letter_spacing' => 'decimal:2',
            'overlay_opacity' => 'integer',
            'button_size' => 'integer',
            'image_original_width' => 'integer',
            'image_original_height' => 'integer',
            'image_focal_x' => 'integer',
            'image_focal_y' => 'integer',
        ];
    }
}
