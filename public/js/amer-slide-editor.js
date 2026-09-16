document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-slide-editor]').forEach((form) => {
        const q = (selector) => form.querySelector(selector);
        const preview = q('[data-slide-preview]');
        const media = q('[data-preview-media]');
        const title = q('[data-live-title]');
        const subtitle = q('[data-live-subtitle]');
        const button = q('[data-live-button]');
        const copy = q('.preview-copy');

        if (! preview) {
            return;
        }

        const value = (name) => q(`[name="${name}"]`)?.value;

        const applyMedia = () => {
            const element = media.querySelector('img, video');
            if (! element) {
                return;
            }

            element.style.objectFit = value('image_fit') === 'cover' ? 'cover' : 'contain';
            element.style.objectPosition = `${value('image_focal_x') || 50}% ${value('image_focal_y') || 50}%`;
        };

        const setMedia = (url, isVideo) => {
            media.innerHTML = url
                ? (isVideo
                    ? `<video src="${url}" autoplay muted loop playsinline></video>`
                    : `<img src="${url}" alt="">`)
                : '';
            applyMedia();
        };

        const applyContentVisibility = () => {
            title.hidden = title.textContent.trim() === '';
            subtitle.hidden = subtitle.textContent.trim() === '';
            button.hidden = button.textContent.trim() === '';
        };

        const existingImage = preview.dataset.existingImage;
        const existingVideo = preview.dataset.existingVideo;
        if (existingImage) {
            setMedia(existingImage, false);
        } else if (existingVideo) {
            setMedia(existingVideo, true);
        }

        q('[data-preview-image]')?.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                setMedia(URL.createObjectURL(file), false);
            }
        });

        q('[data-preview-video]')?.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                setMedia(URL.createObjectURL(file), true);
            }
        });

        q('[data-preview-title]')?.addEventListener('input', (event) => {
            title.textContent = event.target.value;
            applyContentVisibility();
        });
        q('[data-preview-subtitle]')?.addEventListener('input', (event) => {
            subtitle.textContent = event.target.value;
            applyContentVisibility();
        });
        q('[data-preview-button]')?.addEventListener('input', (event) => {
            button.textContent = event.target.value;
            applyContentVisibility();
        });

        const apply = () => {
            copy.style.fontFamily = value('title_font');
            copy.style.fontWeight = value('font_weight');
            copy.style.textAlign = value('text_alignment');
            title.style.fontSize = `${value('title_size_desktop')}px`;
            title.style.color = value('title_color');
            subtitle.style.fontSize = `${value('subtitle_size_desktop')}px`;
            subtitle.style.color = value('subtitle_color');

            [title, subtitle].forEach((element) => {
                element.style.lineHeight = value('line_height');
                element.style.letterSpacing = `${value('letter_spacing')}px`;
            });

            const shadows = {
                none: 'none',
                soft: '0 2px 8px rgba(0,0,0,.35)',
                strong: '0 3px 12px rgba(0,0,0,.8)',
            };
            title.style.textShadow = subtitle.style.textShadow = shadows[value('text_shadow')];
            preview.className = `slide-live-preview position-${value('text_position')}`;
            q('[data-preview-overlay]').style.backgroundColor = value('overlay_color');
            q('[data-preview-overlay]').style.opacity = Number(value('overlay_opacity')) / 100;
            q('[data-opacity-output]').textContent = `${value('overlay_opacity')}%`;
            button.style.fontFamily = value('button_font');
            button.style.fontSize = `${value('button_size')}px`;
            button.style.color = value('button_text_color');
            button.style.backgroundColor = value('button_background_color');
            applyMedia();
            applyContentVisibility();
        };

        form.querySelectorAll('input, select, textarea').forEach((element) => {
            element.addEventListener('input', apply);
            element.addEventListener('change', apply);
        });
        apply();
    });
});
