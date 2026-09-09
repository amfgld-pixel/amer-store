<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSlideRequest;
use App\Http\Requests\UpdateStoreDesignRequest;
use App\Models\StoreSetting;
use App\Models\StoreSlide;
use App\Services\StoreDesignService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StoreDesignController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.store-design', [
            'settings' => StoreSetting::query()->pluck('value', 'key'),
            'slides' => StoreSlide::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function update(UpdateStoreDesignRequest $request, StoreDesignService $service): RedirectResponse
    {
        $service->updateSettings($request->validated());

        return back()->with('status', 'Store design updated.');
    }

    public function storeSlide(StoreSlideRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image', 'video');
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('store/slides', 'public');
        }
        if ($request->hasFile('video')) {
            $data['video_path'] = $request->file('video')->store('store/videos', 'public');
        }
        StoreSlide::query()->create($data);

        return back()->with('status', 'Slide added.');
    }

    public function destroySlide(StoreSlide $slide): RedirectResponse
    {
        $paths = array_values(array_filter([
            $slide->image_path,
            $slide->video_path,
        ], static fn (mixed $path): bool => is_string($path) && trim($path) !== ''));

        if ($paths !== []) {
            Storage::disk('public')->delete($paths);
        }

        $slide->delete();

        return back()->with('status', 'Slide removed.');
    }
}
