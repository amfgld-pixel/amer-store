# AMER Pack-015 v1.5 — Automatic Slider Rotation

Adds per-slide and per-video typography controls with live preview.

Title and subtitle are optional for individual and multiple picture slides. You can create a slide using only a picture.

Version 1.3 removes the forced AMER eyebrow from every slide and stores, reads, replaces and deletes slide media through the configured Laravel filesystem disk. This supports Laravel Cloud Object Storage while retaining the local public-disk fallback.

Version 1.4 makes no-crop Contain mode the default, changes Live Preview to the real 1920×800 desktop ratio, applies fit and focal-position controls in real time, and hides empty preview placeholders.

Version 1.5 automatically advances active slides every five seconds, loops back to the first slide, keeps navigation dots synchronized, pauses on hover/focus or when the browser tab is hidden, and restarts after manual navigation.

## Included

- Poppins, Inter, Playfair Display, Montserrat, Manrope and DM Sans
- Separate desktop, tablet and mobile title/subtitle sizes
- Colours, weights, alignment and vertical position
- Line height, letter spacing, shadow and overlay
- Safe multiline title/subtitle output
- Button typography and colours
- Edit controls for existing slides
- Image/video live preview before saving
- Null-safe slideshow deletion
- Upload 2–20 pictures together with a different title/subtitle per picture
- Automatic responsive WebP outputs: 1920×800, 1200×700 and 750×900
- Cover, contain and original-proportion modes
- Manual horizontal/vertical focal controls
- Manual picture measurement calculator
- Add pictures one by one without losing earlier selections
- Remove any selected picture before creating the slides

## Install

Extract the ZIP, copy all extracted contents into the Laravel project root, replace files, then run:

```powershell
cd "G:\Website build\amer-store"
powershell -ExecutionPolicy Bypass -File .\INSTALL-PACK-015.ps1
php artisan serve
```

Open `http://127.0.0.1:8000/admin/settings/store-design`.
