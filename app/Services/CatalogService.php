<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CatalogService
{
    public function saveCategory(array $data, ?Category $category = null): Category
    {
        return DB::transaction(function () use ($data, $category) {
            $old = $category?->toArray();
            $category ??= new Category();
            $category->fill($data)->save();
            $this->audit($old ? 'updated' : 'created', $category, $old, $category->fresh()->toArray());

            return $category->fresh();
        });
    }

    public function saveProduct(array $data, ?Product $product = null): Product
    {
        return DB::transaction(function () use ($data, $product) {
            $mainImage = Arr::pull($data, 'main_image');
            $galleryImages = Arr::pull($data, 'gallery_images', []);
            $removeImageIds = Arr::pull($data, 'remove_image_ids', []);
            $primaryImageId = Arr::pull($data, 'primary_image_id');

            $old = $product?->load('variants', 'categories', 'images')->toArray();
            $product ??= new Product();
            $product->fill(Arr::except($data, ['category_ids', 'variants']))->save();
            $product->categories()->sync($data['category_ids']);

            $kept = [];
            foreach ($data['variants'] as $index => $variantData) {
                $variant = $product->variants()->updateOrCreate(
                    ['sku' => $variantData['sku']],
                    $variantData + ['sort_order' => $index]
                );
                $kept[] = $variant->id;
            }
            $product->variants()->whereNotIn('id', $kept)->delete();

            $this->removeImages($product, $removeImageIds);

            if ($mainImage instanceof UploadedFile) {
                $product->images()->update(['is_primary' => false]);
                $this->storeImage($product, $mainImage, true, 0);
            }

            $nextOrder = ((int) $product->images()->max('sort_order')) + 1;
            foreach ($galleryImages as $image) {
                if ($image instanceof UploadedFile) {
                    $this->storeImage($product, $image, false, $nextOrder++);
                }
            }

            if (!$mainImage && $primaryImageId && $product->images()->whereKey($primaryImageId)->exists()) {
                $product->images()->update(['is_primary' => false]);
                $product->images()->whereKey($primaryImageId)->update(['is_primary' => true]);
            }

            if (!$product->images()->where('is_primary', true)->exists()) {
                $product->images()->orderBy('sort_order')->orderBy('id')->first()?->update(['is_primary' => true]);
            }

            $fresh = $product->fresh(['variants', 'categories', 'images']);
            $this->audit($old ? 'updated' : 'created', $fresh, $old, $fresh->toArray());

            return $fresh;
        });
    }

    private function storeImage(Product $product, UploadedFile $image, bool $primary, int $sortOrder): void
    {
        $product->images()->create([
            'path' => $image->store('products/'.$product->id, $this->mediaDiskName()),
            'alt_text' => $product->name,
            'is_primary' => $primary,
            'sort_order' => $sortOrder,
        ]);
    }

    private function removeImages(Product $product, array $ids): void
    {
        $images = $product->images()->whereIn('id', $ids)->get();
        foreach ($images as $image) {
            Storage::disk($this->mediaDiskName())->delete($image->path);
            $image->delete();
        }
    }

    private function mediaDiskName(): string
    {
        $name = (string) config('filesystems.default', 'public');

        return $name === 'local' ? 'public' : $name;
    }

    private function audit(string $event, object $model, ?array $old, array $new): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'auditable_type' => $model::class,
            'auditable_id' => $model->id,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
