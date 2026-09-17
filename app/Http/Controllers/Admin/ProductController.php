<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\CatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Product::class);

        return view('admin.catalog.products', [
            'products' => Product::with('brand', 'variants', 'images')->orderBy('sort_order')->paginate(30),
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Product::class);

        return view('admin.catalog.product-form', [
            'product' => new Product(),
            'categories' => Category::orderBy('sort_order')->get(),
            'brands' => Brand::orderBy('sort_order')->get(),
        ]);
    }

    public function store(StoreProductRequest $request, CatalogService $service): RedirectResponse
    {
        $product = $service->saveProduct($request->validated());

        return redirect()->route('admin.products.edit', $product)->with('status', 'Product and pictures created.');
    }

    public function edit(Product $product): View
    {
        Gate::authorize('update', $product);

        return view('admin.catalog.product-form', [
            'product' => $product->load('categories', 'variants', 'images'),
            'categories' => Category::orderBy('sort_order')->get(),
            'brands' => Brand::orderBy('sort_order')->get(),
        ]);
    }

    public function update(StoreProductRequest $request, Product $product, CatalogService $service): RedirectResponse
    {
        Gate::authorize('update', $product);
        $service->saveProduct($request->validated(), $product);

        return back()->with('status', 'Product and pictures updated.');
    }
}
