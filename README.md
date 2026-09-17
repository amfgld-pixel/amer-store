# AMER Pack-016 — Product Picture Gallery

Adds the missing product picture management system to New/Edit Product.

## Included

- One main product image
- Up to ten gallery images per save
- JPG, PNG and WebP support up to 10 MB per image
- Preview selected pictures before saving
- View existing pictures on Edit Product
- Choose a different primary picture
- Remove saved pictures
- Laravel Cloud Object Storage support
- Cloud image URLs on product cards and product details

The existing `product_images` database table is used, so no new migration is required.

## Install

Copy all extracted files into the Laravel project root and replace the matching files. Then run:

```powershell
cd "G:\Website build\amer-store"
powershell -ExecutionPolicy Bypass -File .\INSTALL-PACK-016.ps1
```

For Laravel Cloud, commit the files, push to GitHub and deploy.
