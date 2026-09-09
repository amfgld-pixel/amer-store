<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->canAccess('products.manage'), 403);

        return view('admin.catalog.brands', ['brands' => Brand::withCount('products')->orderBy('sort_order')->paginate(30)]);
    }

    public function store(StoreBrandRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('logo');
        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('brands', 'public');
        }
        Brand::query()->create($data);

        return back()->with('status', 'Brand created successfully.');
    }

    public function update(StoreBrandRequest $request, Brand $brand): RedirectResponse
    {
        $data = $request->safe()->except('logo');
        if ($request->hasFile('logo')) {
            Storage::disk('public')->delete($brand->logo_path);
            $data['logo_path'] = $request->file('logo')->store('brands', 'public');
        }
        $brand->update($data);

        return back()->with('status', 'Brand updated successfully.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        abort_unless(auth()->user()->canAccess('products.manage'), 403);
        abort_if($brand->products()->exists(), 422, 'Move the products to another brand before deleting this brand.');
        $brand->delete();

        return back()->with('status', 'Brand deleted.');
    }
}

