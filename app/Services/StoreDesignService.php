<?php

namespace App\Services;

use App\Models\StoreSetting;
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
        if ($setting->value) {
            Storage::disk('public')->delete($setting->value);
        }

        $setting->fill([
            'value' => $file->store('store/design', 'public'),
            'type' => 'file',
            'group' => 'design',
        ])->save();
    }
}

