<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Services\CatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
class CategoryController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->canAccess('categories.view'), 403);

        return view('admin.catalog.categories', [
            'categories' => Category::with(['parent', 'children'])
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->paginate(30),
            'parentOptions' => Category::whereNull('parent_id')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }
    public function store(StoreCategoryRequest $request,CatalogService $service): RedirectResponse { $service->saveCategory($request->validated()); return back()->with('status','Category created.'); }
    public function update(StoreCategoryRequest $request,Category $category,CatalogService $service): RedirectResponse { $service->saveCategory($request->validated(),$category); return back()->with('status','Category updated.'); }
}
