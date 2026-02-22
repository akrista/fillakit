<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use InvalidArgumentException;

final readonly class PwaIconService
{
    private ImageManager $imageManager;

    private array $iconSizes;

    private array $splashSizes;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());

        $this->iconSizes = [
            72 => 'icon-72x72.png',
            96 => 'icon-96x96.png',
            128 => 'icon-128x128.png',
            144 => 'icon-144x144.png',
            152 => 'icon-152x152.png',
            192 => 'icon-192x192.png',
            384 => 'icon-384x384.png',
            512 => 'icon-512x512.png',
        ];

        $this->splashSizes = [
            [640, 1136, 'splash-640x1136.png'],
            [750, 1334, 'splash-750x1334.png'],
            [828, 1792, 'splash-828x1792.png'],
            [1125, 2436, 'splash-1125x2436.png'],
            [1242, 2208, 'splash-1242x2208.png'],
            [1242, 2688, 'splash-1242x2688.png'],
            [1536, 2048, 'splash-1536x2048.png'],
            [1668, 2224, 'splash-1668x2224.png'],
            [1668, 2388, 'splash-1668x2388.png'],
            [2048, 2732, 'splash-2048x2732.png'],
        ];
    }

    public function generateFromUpload(string $uploadedPath, ?string $backgroundColor = null): bool
    {
        if (!File::exists($uploadedPath)) {
            Log::error('Source image does not exist: ' . $uploadedPath);

            throw new InvalidArgumentException('Source image file does not exist.');
        }

        try {
            $pathToProcess = $uploadedPath;

            if (str_ends_with(mb_strtolower($uploadedPath), '.svg')) {
                $tempPngPath = sys_get_temp_dir() . '/pwa-icon-' . uniqid() . '.png';
                exec('magick ' . escapeshellarg($uploadedPath) . ' -background none -resize 512x512 PNG32:' . escapeshellarg($tempPngPath));

                throw_unless(File::exists($tempPngPath), InvalidArgumentException::class, 'Failed to convert SVG to PNG');

                $pathToProcess = $tempPngPath;
            }

            $sourceImage = $this->imageManager->read($pathToProcess);

            $this->generateAppIcons($sourceImage);
            $this->generateSplashScreens($sourceImage, $backgroundColor);
            $this->generateFavicons($sourceImage);

            if (isset($tempPngPath) && File::exists($tempPngPath)) {
                File::delete($tempPngPath);
            }

            Log::info('PWA icons generated successfully from: ' . $uploadedPath);

            return true;
        } catch (Exception $exception) {
            if (isset($tempPngPath) && File::exists($tempPngPath)) {
                File::delete($tempPngPath);
            }

            Log::error('Error generating PWA icons: ' . $exception->getMessage());

            throw $exception;
        }
    }

    private function generateAppIcons(\Intervention\Image\Interfaces\ImageInterface $sourceImage): void
    {
        $iconsPath = public_path('images/icons');

        if (!File::isDirectory($iconsPath)) {
            File::makeDirectory($iconsPath, 0755, true);
        }

        foreach ($this->iconSizes as $size => $filename) {
            $icon = clone $sourceImage;
            $icon->scale(width: $size, height: $size);

            $icon->toPng()->save($iconsPath . '/' . $filename);

            Log::debug('Generated icon: ' . $filename);
        }
    }

    private function generateSplashScreens(\Intervention\Image\Interfaces\ImageInterface $sourceImage, ?string $backgroundColor): void
    {
        $iconsPath = public_path('images/icons');

        if (!File::isDirectory($iconsPath)) {
            File::makeDirectory($iconsPath, 0755, true);
        }

        foreach ($this->splashSizes as [$width, $height, $filename]) {
            $iconSize = (int) ($height * 0.4);

            if (!$backgroundColor || $backgroundColor === 'transparent') {
                $splash = $this->imageManager->create($width, $height)->fill('rgba(0, 0, 0, 0)');
            } else {
                $splash = $this->imageManager->create($width, $height)->fill($backgroundColor);
            }

            $icon = clone $sourceImage;
            $icon->scale(width: $iconSize, height: $iconSize);

            $x = (int) (($width - $iconSize) / 2);
            $y = (int) (($height - $iconSize) / 2);

            $splash->place($icon, 'top-left', $x, $y);

            $splash->toPng()->save($iconsPath . '/' . $filename);

            Log::debug('Generated splash screen: ' . $filename);
        }
    }

    private function generateFavicons(\Intervention\Image\Interfaces\ImageInterface $sourceImage): void
    {
        $faviconIco = clone $sourceImage;
        $faviconIco->scale(width: 32, height: 32);
        $faviconIco->toPng()->save(public_path('favicon.ico'));

        $appleTouchIcon = clone $sourceImage;
        $appleTouchIcon->scale(width: 180, height: 180);
        $appleTouchIcon->toPng()->save(public_path('apple-touch-icon.png'));

        Log::debug('Generated favicon.ico and apple-touch-icon.png');
    }
}
