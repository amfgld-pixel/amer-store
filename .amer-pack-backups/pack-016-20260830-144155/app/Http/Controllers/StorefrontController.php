<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\StoreSetting;
use App\Models\StoreSlide;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function home()
    {
        return view('storefront.home', [
            'company' => Company::where('is_active', true)->first(),
            'settings' => StoreSetting::query()->pluck('value', 'key'),
            'slides' => StoreSlide::where('is_active', true)->orderBy('sort_order')->get(),
            'categories' => Category::where('is_active', true)->orderBy('sort_order')->limit(6)->get(),
            'newArrivals' => Product::with(['brand', 'images', 'variants'])->where('status', 'active')->latest('id')->limit(8)->get(),
            'featured' => Product::with(['brand', 'images', 'variants'])->where('status', 'active')->where('is_featured', true)->orderBy('sort_order')->limit(8)->get(),
        ]);
    }

    public function shop(Request $request, ?Category $category = null)
    {
        $query = Product::with(['brand', 'images', 'variants', 'categories'])->where('status', 'active');
        if ($category) {
            $query->whereHas('categories', fn ($q) => $q->whereKey($category->id));
        }
        if ($search = trim((string) $request->query('q'))) {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('name_bn', 'like', "%{$search}%"));
        }

        return view('storefront.shop', [
            'company' => Company::where('is_active', true)->first(),
            'categories' => Category::where('is_active', true)->orderBy('sort_order')->get(),
            'currentCategory' => $category,
            'products' => $query->orderBy('sort_order')->paginate(24)->withQueryString(),
        ]);
    }

    public function product(Product $product)
    {
        abort_unless($product->status === 'active', 404);
        $product->load(['images', 'variants.attributeValues.attribute', 'categories', 'brand']);

        return view('storefront.product', [
            'company' => Company::where('is_active', true)->first(),
            'product' => $product,
        ]);
    }
}

