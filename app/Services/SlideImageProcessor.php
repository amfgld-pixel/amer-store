<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class SlideImageProcessor
{
    private const SIZES = [
        'desktop' => [1920, 800],
        'tablet' => [1200, 700],
        'mobile' => [750, 900],
    ];

    public function process(UploadedFile $file, string $fit = 'contain', int $focalX = 50, int $focalY = 50): array
    {
        if (! function_exists('imagecreatefromstring') || ! function_exists('imagewebp')) {
            throw new RuntimeException('PHP GD with WebP support is required for automatic slide resizing.');
        }

        $bytes = file_get_contents($file->getRealPath());
        $source = $bytes === false ? false : imagecreatefromstring($bytes);
        if ($source === false) {
            throw new RuntimeException('The uploaded slide image could not be decoded.');
        }

        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $id = date('YmdHis').'-'.bin2hex(random_bytes(6));
        $paths = [];

        try {
            foreach (self::SIZES as $name => [$targetWidth, $targetHeight]) {
                $path = "store/slides/{$id}-{$name}.webp";
                $this->render($source, $sourceWidth, $sourceHeight, $targetWidth, $targetHeight, $fit, $focalX, $focalY, $path);
                $paths[$name] = $path;
            }
        } catch (\Throwable $exception) {
            $this->disk()->delete(array_values($paths));
            throw $exception;
        } finally {
            imagedestroy($source);
        }

        return [
            'image_path' => $paths['desktop'],
            'image_path_tablet' => $paths['tablet'],
            'image_path_mobile' => $paths['mobile'],
            'image_original_width' => $sourceWidth,
            'image_original_height' => $sourceHeight,
            'image_fit' => $fit,
            'image_focal_x' => $focalX,
            'image_focal_y' => $focalY,
        ];
    }

    private function render($source, int $sw, int $sh, int $tw, int $th, string $fit, int $focalX, int $focalY, string $path): void
    {
        $canvas = imagecreatetruecolor($tw, $th);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $background = imagecolorallocatealpha($canvas, 255, 255, 255, 0);
        imagefilledrectangle($canvas, 0, 0, $tw, $th, $background);

        if ($fit === 'cover') {
            $scale = max($tw / $sw, $th / $sh);
        } elseif ($fit === 'original') {
            $scale = min(1, min($tw / $sw, $th / $sh));
        } else {
            $scale = min($tw / $sw, $th / $sh);
        }

        $dw = max(1, (int) round($sw * $scale));
        $dh = max(1, (int) round($sh * $scale));
        $overflowX = max(0, $dw - $tw);
        $overflowY = max(0, $dh - $th);
        $dx = $overflowX > 0 ? -(int) round($overflowX * ($focalX / 100)) : (int) round(($tw - $dw) / 2);
        $dy = $overflowY > 0 ? -(int) round($overflowY * ($focalY / 100)) : (int) round(($th - $dh) / 2);

        imagecopyresampled($canvas, $source, $dx, $dy, 0, 0, $dw, $dh, $sw, $sh);
        ob_start();
        $encoded = imagewebp($canvas, null, 86);
        $webp = ob_get_clean();
        if (! $encoded || ! is_string($webp) || ! $this->disk()->put($path, $webp)) {
            imagedestroy($canvas);
            throw new RuntimeException('Responsive WebP slide generation failed.');
        }
        imagedestroy($canvas);
    }

    private function disk(): \Illuminate\Contracts\Filesystem\Filesystem
    {
        $name = (string) config('filesystems.default', 'public');

        return Storage::disk($name === 'local' ? 'public' : $name);
    }
}
