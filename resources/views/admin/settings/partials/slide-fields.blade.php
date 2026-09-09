@php
    $s = $slide ?? null;
    $fonts = ['Poppins', 'Inter', 'Playfair Display', 'Montserrat', 'Manrope', 'DM Sans'];
@endphp
<div class="slide-editor-grid">
    <section class="slide-controls">
        <h3>Content & media</h3>
        <div class="admin-grid">
            <label class="wide">Title (press Enter for a new line)
                <textarea name="title" rows="2" required data-preview-title>{{ old('title', $s?->title) }}</textarea>
            </label>
            <label class="wide">Subtitle (multiple lines supported)
                <textarea name="subtitle" rows="2" data-preview-subtitle>{{ old('subtitle', $s?->subtitle) }}</textarea>
            </label>
            <label>Slide picture<input type="file" name="image" accept="image/jpeg,image/png,image/webp" data-preview-image></label>
            <label>Picture Fit<select name="image_fit"><option value="cover" @selected(old('image_fit', $s?->image_fit ?? 'cover') === 'cover')>Auto Cover</option><option value="contain" @selected(old('image_fit', $s?->image_fit) === 'contain')>Contain Full Picture</option><option value="original" @selected(old('image_fit', $s?->image_fit) === 'original')>Original Proportion</option></select></label>
            <label>Horizontal Focus (%)<input type="range" name="image_focal_x" min="0" max="100" value="{{ old('image_focal_x', $s?->image_focal_x ?? 50) }}"></label>
            <label>Vertical Focus (%)<input type="range" name="image_focal_y" min="0" max="100" value="{{ old('image_focal_y', $s?->image_focal_y ?? 50) }}"></label>
            <label>Upload video<input type="file" name="video" accept="video/mp4,video/webm" data-preview-video></label>
            <label class="wide">External video URL<input type="url" name="video_url" value="{{ old('video_url', $s?->video_url) }}" placeholder="https://.../video.mp4"></label>
            <label>Button text<input name="button_text" value="{{ old('button_text', $s?->button_text) }}" data-preview-button></label>
            <label>Button URL<input name="button_url" value="{{ old('button_url', $s?->button_url) }}"></label>
        </div>

        <h3>Title & subtitle typography</h3>
        <div class="admin-grid">
            <label>Title Font<select name="title_font" data-style="fontFamily">@foreach($fonts as $font)<option value="{{ $font }}" @selected(old('title_font', $s?->title_font ?? 'Manrope') === $font)>{{ $font }}</option>@endforeach</select></label>
            <label>Font Weight<select name="font_weight" data-style="fontWeight"><option value="400" @selected(old('font_weight', $s?->font_weight ?? '400') == '400')>Regular</option><option value="500" @selected(old('font_weight', $s?->font_weight) == '500')>Medium</option><option value="700" @selected(old('font_weight', $s?->font_weight) == '700')>Bold</option></select></label>
            <label>Title Desktop (px)<input type="number" name="title_size_desktop" min="18" max="140" value="{{ old('title_size_desktop', $s?->title_size_desktop ?? 72) }}" data-style="titleSize"></label>
            <label>Title Tablet (px)<input type="number" name="title_size_tablet" min="16" max="110" value="{{ old('title_size_tablet', $s?->title_size_tablet ?? 52) }}"></label>
            <label>Title Mobile (px)<input type="number" name="title_size_mobile" min="14" max="80" value="{{ old('title_size_mobile', $s?->title_size_mobile ?? 34) }}"></label>
            <label>Title Colour<input type="color" name="title_color" value="{{ old('title_color', $s?->title_color ?? '#161616') }}" data-style="titleColor"></label>
            <label>Subtitle Desktop (px)<input type="number" name="subtitle_size_desktop" min="12" max="60" value="{{ old('subtitle_size_desktop', $s?->subtitle_size_desktop ?? 20) }}" data-style="subtitleSize"></label>
            <label>Subtitle Tablet (px)<input type="number" name="subtitle_size_tablet" min="12" max="50" value="{{ old('subtitle_size_tablet', $s?->subtitle_size_tablet ?? 18) }}"></label>
            <label>Subtitle Mobile (px)<input type="number" name="subtitle_size_mobile" min="10" max="40" value="{{ old('subtitle_size_mobile', $s?->subtitle_size_mobile ?? 15) }}"></label>
            <label>Subtitle Colour<input type="color" name="subtitle_color" value="{{ old('subtitle_color', $s?->subtitle_color ?? '#161616') }}" data-style="subtitleColor"></label>
            <label>Alignment<select name="text_alignment" data-style="textAlign"><option value="left" @selected(old('text_alignment', $s?->text_alignment ?? 'left') === 'left')>Left</option><option value="center" @selected(old('text_alignment', $s?->text_alignment) === 'center')>Center</option><option value="right" @selected(old('text_alignment', $s?->text_alignment) === 'right')>Right</option></select></label>
            <label>Position<select name="text_position" data-position><option value="top" @selected(old('text_position', $s?->text_position) === 'top')>Top</option><option value="middle" @selected(old('text_position', $s?->text_position ?? 'middle') === 'middle')>Middle</option><option value="bottom" @selected(old('text_position', $s?->text_position) === 'bottom')>Bottom</option></select></label>
            <label>Line Height<input type="number" step="0.05" min="0.8" max="2.5" name="line_height" value="{{ old('line_height', $s?->line_height ?? 1) }}" data-style="lineHeight"></label>
            <label>Letter Spacing (px)<input type="number" step="0.1" min="-2" max="15" name="letter_spacing" value="{{ old('letter_spacing', $s?->letter_spacing ?? 0) }}" data-style="letterSpacing"></label>
            <label>Text Shadow<select name="text_shadow" data-shadow><option value="none" @selected(old('text_shadow', $s?->text_shadow ?? 'none') === 'none')>None</option><option value="soft" @selected(old('text_shadow', $s?->text_shadow) === 'soft')>Soft</option><option value="strong" @selected(old('text_shadow', $s?->text_shadow) === 'strong')>Strong</option></select></label>
        </div>

        <h3>Overlay & button</h3>
        <div class="admin-grid">
            <label>Overlay Colour<input type="color" name="overlay_color" value="{{ old('overlay_color', $s?->overlay_color ?? '#FFFFFF') }}" data-overlay-color></label>
            <label>Overlay Opacity (%)<input type="range" name="overlay_opacity" min="0" max="90" value="{{ old('overlay_opacity', $s?->overlay_opacity ?? 25) }}" data-overlay-opacity><output data-opacity-output>{{ old('overlay_opacity', $s?->overlay_opacity ?? 25) }}%</output></label>
            <label>Button Font<select name="button_font" data-button-style="fontFamily">@foreach($fonts as $font)<option value="{{ $font }}" @selected(old('button_font', $s?->button_font ?? 'Inter') === $font)>{{ $font }}</option>@endforeach</select></label>
            <label>Button Size (px)<input type="number" name="button_size" min="10" max="32" value="{{ old('button_size', $s?->button_size ?? 16) }}" data-button-style="fontSize"></label>
            <label>Button Text Colour<input type="color" name="button_text_color" value="{{ old('button_text_color', $s?->button_text_color ?? '#FFFFFF') }}" data-button-style="color"></label>
            <label>Button Background<input type="color" name="button_background_color" value="{{ old('button_background_color', $s?->button_background_color ?? '#161616') }}" data-button-style="backgroundColor"></label>
            <label>Order<input type="number" name="sort_order" value="{{ old('sort_order', $s?->sort_order ?? 0) }}" min="0"></label>
            <label>Status<select name="is_active"><option value="1" @selected(old('is_active', $s?->is_active ?? true))>Active</option><option value="0" @selected(!old('is_active', $s?->is_active ?? true))>Inactive</option></select></label>
        </div>
    </section>

    <aside class="slide-preview-wrap">
        <h3>Live Preview <small>(desktop preview)</small></h3>
        <div class="slide-live-preview position-{{ old('text_position', $s?->text_position ?? 'middle') }}" data-slide-preview data-existing-image="{{ $s?->image_path ? asset('storage/'.$s->image_path) : '' }}" data-existing-video="{{ $s?->video_path ? asset('storage/'.$s->video_path) : ($s?->video_url ?? '') }}">
            <div class="preview-media" data-preview-media></div><div class="preview-overlay" data-preview-overlay></div>
            <div class="preview-copy"><h2 data-live-title>{{ old('title', $s?->title ?? 'Your slide title') }}</h2><p data-live-subtitle>{{ old('subtitle', $s?->subtitle ?? 'Your subtitle appears here') }}</p><span data-live-button>{{ old('button_text', $s?->button_text ?? 'Shop now') }}</span></div>
        </div>
    </aside>
</div>
