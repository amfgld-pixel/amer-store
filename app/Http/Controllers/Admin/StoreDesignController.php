<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSlideRequest;
use App\Http\Requests\BulkStoreSlidesRequest;
use App\Http\Requests\UpdateStoreDesignRequest;
use App\Models\StoreSetting;
use App\Models\StoreSlide;
use App\Services\StoreDesignService;
use App\Services\SlideImageProcessor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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

    public function storeSlide(StoreSlideRequest $request, SlideImageProcessor $processor): RedirectResponse
    {
        StoreSlide::query()->create($this->slideData($request, $processor));

        return back()->with('status', 'Slide and typography added.');
    }

    public function updateSlide(StoreSlideRequest $request, StoreSlide $slide, SlideImageProcessor $processor): RedirectResponse
    {
        $data = $this->slideData($request, $processor, $slide);
        $slide->update($data);

        return back()->with('status', 'Slide typography updated.');
    }

    public function storeBulkSlides(BulkStoreSlidesRequest $request, SlideImageProcessor $processor): RedirectResponse
    {
        $data = $request->validated();
        $createdPaths = [];

        try {
            DB::transaction(function () use ($request, $processor, $data, &$createdPaths): void {
                foreach ($request->file('images') as $index => $image) {
                    $responsive = $processor->process(
                        $image,
                        $data['fit_modes'][$index],
                        (int) $data['focal_x'][$index],
                        (int) $data['focal_y'][$index],
                    );
                    array_push($createdPaths, $responsive['image_path'], $responsive['image_path_tablet'], $responsive['image_path_mobile']);

                    StoreSlide::query()->create(array_merge($responsive, [
                        'title' => $data['titles'][$index],
                        'subtitle' => $data['subtitles'][$index] ?? null,
                        'button_text' => $data['button_texts'][$index] ?? null,
                        'button_url' => $data['button_urls'][$index] ?? null,
                        'sort_order' => (int) $data['start_order'] + $index,
                        'is_active' => true,
                        'title_font' => $data['title_font'],
                        'title_color' => $data['title_color'],
                        'subtitle_color' => $data['subtitle_color'],
                        'text_alignment' => $data['text_alignment'],
                        'text_position' => $data['text_position'],
                        'overlay_color' => $data['overlay_color'],
                        'overlay_opacity' => $data['overlay_opacity'],
                    ]));
                }
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($createdPaths);
            throw $exception;
        }

        return back()->with('status', count($request->file('images')).' responsive picture slides created.');
    }

    public function destroySlide(StoreSlide $slide): RedirectResponse
    {
        $paths = array_values(array_filter([$slide->image_path, $slide->image_path_tablet,
            $slide->image_path_mobile, $slide->video_path],
            static fn (mixed $path): bool => is_string($path) && trim($path) !== ''));
        if ($paths !== []) {
            Storage::disk('public')->delete($paths);
        }
        $slide->delete();

        return back()->with('status', 'Slide removed.');
    }

    private function slideData(StoreSlideRequest $request, SlideImageProcessor $processor, ?StoreSlide $slide = null): array
    {
        $data = $request->safe()->except('image', 'video');
        if ($request->hasFile('image')) {
            $oldPaths = array_values(array_filter([$slide?->image_path, $slide?->image_path_tablet, $slide?->image_path_mobile]));
            $data = array_merge($data, $processor->process(
                $request->file('image'),
                $data['image_fit'],
                (int) $data['image_focal_x'],
                (int) $data['image_focal_y'],
            ));
            Storage::disk('public')->delete($oldPaths);
        }
        if ($request->hasFile('video')) {
            if ($slide?->video_path) {
                Storage::disk('public')->delete($slide->video_path);
            }
            $data['video_path'] = $request->file('video')->store('store/videos', 'public');
        }

        return $data;
    }
}
