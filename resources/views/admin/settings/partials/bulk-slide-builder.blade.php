<div class="panel bulk-slide-panel">
    <h2>Multiple Picture Slide Builder</h2>
    <p>Select one picture first, then use the same button to add more pictures one by one or several together. Previous selections will remain.</p>

    <form method="POST" action="{{ route('admin.settings.slides.bulk') }}" enctype="multipart/form-data" data-bulk-slide-form>
        @csrf
        <div class="bulk-upload-zone">
            <label>Add Picture / Add More Pictures
                <input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple required data-bulk-images>
            </label>
            <small>Minimum 1 and maximum 20 pictures, 10 MB each. You may choose one picture at a time.</small>
        </div>

        <h3>Shared typography defaults</h3>
        <div class="admin-grid">
            <label>Title Font<select name="title_font"><option>Poppins</option><option>Inter</option><option>Playfair Display</option><option>Montserrat</option><option selected>Manrope</option><option>DM Sans</option></select></label>
            <label>Start Order<input type="number" name="start_order" min="0" value="0" required></label>
            <label>Title Colour<input type="color" name="title_color" value="#161616"></label>
            <label>Subtitle Colour<input type="color" name="subtitle_color" value="#161616"></label>
            <label>Text Alignment<select name="text_alignment"><option value="left">Left</option><option value="center">Center</option><option value="right">Right</option></select></label>
            <label>Text Position<select name="text_position"><option value="top">Top</option><option value="middle" selected>Middle</option><option value="bottom">Bottom</option></select></label>
            <label>Overlay Colour<input type="color" name="overlay_color" value="#ffffff"></label>
            <label>Overlay Opacity (%)<input type="number" name="overlay_opacity" min="0" max="90" value="25"></label>
        </div>

        <details class="image-calculator">
            <summary>Manual Picture Measurement Calculator</summary>
            <div class="admin-grid">
                <label>Original Width<input type="number" min="1" value="1920" data-calc-source-width></label>
                <label>Original Height<input type="number" min="1" value="1080" data-calc-source-height></label>
                <label>Target Width<input type="number" min="1" value="1920" data-calc-target-width></label>
                <label>Target Height<input type="number" min="1" value="800" data-calc-target-height></label>
                <label>Calculation Mode<select data-calc-mode><option value="cover">Cover — fill and crop</option><option value="contain">Contain — show full picture</option></select></label>
                <div class="calculation-result" data-calc-result></div>
            </div>
        </details>

        <div class="bulk-picture-list" data-bulk-picture-list>
            <p class="empty-bulk-message">Select pictures to create individual slide editors.</p>
        </div>
        <button class="primary" type="submit" data-bulk-submit disabled>Create all picture slides</button>
    </form>
</div>

<template data-bulk-picture-template>
    <article class="bulk-picture-card">
        <div class="bulk-picture-preview"><img alt=""><span data-picture-number></span></div>
        <div class="bulk-picture-controls">
            <div class="bulk-picture-heading"><h3 data-picture-name></h3><button type="button" class="danger" data-remove-picture>Remove Picture</button></div>
            <p class="picture-measurement" data-picture-measurement></p>
            <label>Title (optional)<textarea rows="2" data-field="titles"></textarea></label>
            <label>Subtitle (optional)<textarea rows="2" data-field="subtitles"></textarea></label>
            <div class="admin-grid">
                <label>Button Text<input data-field="button_texts"></label>
                <label>Button URL<input data-field="button_urls"></label>
                <label>Picture Fit<select data-field="fit_modes" data-bulk-fit><option value="contain" selected>Contain Full Picture — No Crop</option><option value="cover">Auto Cover — Fill & Crop</option><option value="original">Original Proportion — No Enlargement</option></select></label>
                <label>Horizontal Focus <output data-focal-x-output>50%</output><input type="range" min="0" max="100" value="50" data-field="focal_x" data-focal-x></label>
                <label>Vertical Focus <output data-focal-y-output>50%</output><input type="range" min="0" max="100" value="50" data-field="focal_y" data-focal-y></label>
            </div>
        </div>
    </article>
</template>
