<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContentPageRequest;
use App\Models\ContentPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ContentPageController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.index', ['pages' => ContentPage::query()->latest()->paginate(30)]);
    }

    public function create(): View
    {
        return view('admin.pages.form', ['page' => new ContentPage()]);
    }

    public function store(StoreContentPageRequest $request): RedirectResponse
    {
        $page = ContentPage::query()->create($this->payload($request));

        return redirect()->route('admin.pages.edit', $page)->with('status', 'Page created.');
    }

    public function edit(ContentPage $page): View
    {
        return view('admin.pages.form', compact('page'));
    }

    public function update(StoreContentPageRequest $request, ContentPage $page): RedirectResponse
    {
        $page->update($this->payload($request, $page));

        return back()->with('status', 'Page updated.');
    }

    public function destroy(ContentPage $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('admin.pages.index')->with('status', 'Page deleted.');
    }

    private function payload(StoreContentPageRequest $request, ?ContentPage $page = null): array
    {
        $data = $request->safe()->except('hero_image');
        if ($request->hasFile('hero_image')) {
            if ($page?->hero_image_path) {
                Storage::disk('public')->delete($page->hero_image_path);
            }
            $data['hero_image_path'] = $request->file('hero_image')->store('store/pages', 'public');
        }

        return $data;
    }
}

