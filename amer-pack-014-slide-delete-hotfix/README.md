# AMER Slideshow Delete Hotfix

Fixes the 500 TypeError caused by passing a null image or video path to Laravel storage deletion.

## Install

Copy all files into the AMER project root and replace the existing controller, then run:

```powershell
powershell -ExecutionPolicy Bypass -File .\INSTALL-SLIDE-DELETE-HOTFIX.ps1
```

This hotfix does not modify the database, products, orders, customers, or store settings.
