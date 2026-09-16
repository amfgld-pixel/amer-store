<?php

namespace Tests\Feature;

use App\Models\StoreSlide;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Pack015SlideTypographyTest extends TestCase
{
    use RefreshDatabase;

    public function test_slide_typography_defaults_are_available(): void
    {
        $slide = StoreSlide::query()->create([
            'title' => "New Luxury\nCollection",
            'image_path' => 'store/slides/demo.webp',
        ]);

        $this->assertSame('Manrope', $slide->fresh()->title_font);
        $this->assertSame(72, $slide->fresh()->title_size_desktop);
        $this->assertSame("New Luxury\nCollection", $slide->fresh()->title);
    }

    public function test_homepage_renders_multiline_title_safely(): void
    {
        StoreSlide::query()->create([
            'title' => "First Line\nSecond Line",
            'image_path' => 'store/slides/demo.webp',
            'is_active' => true,
        ]);

        $this->get('/')->assertOk()->assertSee('First Line<br />\nSecond Line', false);
    }

    public function test_picture_only_slide_has_no_forced_amer_label(): void
    {
        StoreSlide::query()->create([
            'title' => null,
            'subtitle' => null,
            'image_path' => 'store/slides/demo.webp',
            'is_active' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('<p class="eyebrow">AMER</p>', false);
    }
}
