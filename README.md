# AMER Toys to Kids Category Hotfix

This update changes the existing `Toys` category to `Kids` without creating a duplicate category.

## Changes

- `Toys` name becomes `Kids`
- `toys` slug becomes `kids`
- Existing Toys-category products remain attached to Kids
- Header and mobile navigation link to Kids
- Men, Women and Accessories remain unchanged
- Future catalog seeding creates Kids instead of Toys

## Install

Copy all extracted files into the Laravel project root and replace matching files. Then run:

```powershell
cd "G:\Website build\amer-store"
powershell -ExecutionPolicy Bypass -File .\INSTALL-TOYS-TO-KIDS-HOTFIX.ps1
```

For Laravel Cloud, commit, push and deploy. The deploy command should include `php artisan migrate --force`.
