<?php

namespace App\Services;

use App\Models\StoreSetting;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StoreDesignService
{
    public function updateSettings(array $data): void
    {
        $map = [
            'store_name' => 'text',
            'announcement' => 'text',
            'primary_color' => 'color',
            'accent_color' => 'color',
            'footer_text' => 'text',
        ];

        foreach ($map as $key => $type) {
            StoreSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $data[$key] ?? null, 'type' => $type, 'group' => 'design']
            );
        }

        foreach (['logo', 'favicon'] as $key) {
            if (($data[$key] ?? null) instanceof UploadedFile) {
                $this->replaceFileSetting($key, $data[$key]);
            }
        }
    }

    private function replaceFileSetting(string $key, UploadedFile $file): void
    {
        $setting = StoreSetting::query()->firstOrNew(['key' => $key]);
        if (is_string($setting->value) && trim($setting->value) !== '') {
            $this->mediaDisk()->delete($setting->value);
        }

        $setting->fill([
            'value' => $file->store('store/design', $this->mediaDiskName()),
            'type' => 'file',
            'group' => 'design',
        ])->save();
    }

    private function mediaDisk(): Filesystem
    {
        return Storage::disk($this->mediaDiskName());
    }

    private function mediaDiskName(): string
    {
        $name = (string) config('filesystems.default', 'public');

        return $name === 'local' ? 'public' : $name;
    }
}
