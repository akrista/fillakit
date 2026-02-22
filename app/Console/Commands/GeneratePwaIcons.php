<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\PwaIconService;
use App\Settings\GeneralSettings;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Override;

final class GeneratePwaIcons extends Command
{
    #[Override]
    protected $signature = 'pwa:generate-icons';

    #[Override]
    protected $description = 'Generate all PWA icons and splash screens from the uploaded icon';

    public function handle(): int
    {
        $settings = resolve(GeneralSettings::class);

        if (!$settings->site_favicon) {
            $this->error('No favicon has been uploaded in settings.');
            $this->info('Please upload a favicon in the General Settings > Branding tab first.');

            return self::FAILURE;
        }

        $this->info('Generating PWA icons from favicon...');

        $pwaService = new PwaIconService;
        $iconPath = Storage::disk('public')->path($settings->site_favicon);

        if (!file_exists($iconPath)) {
            $this->error('Favicon file not found: ' . $iconPath);

            return self::FAILURE;
        }

        try {
            $pwaService->generateFromUpload(
                $iconPath,
                $settings->pwa_splash_background_color
            );

            $this->info('✓ Generated 8 app icons (72x72 to 512x512) with transparency');
            $this->info('✓ Generated 10 splash screens for iOS devices');
            $this->info('✓ Generated favicon.ico and apple-touch-icon.png');
            $this->newLine();
            $this->info('All PWA icons generated successfully!');

            return self::SUCCESS;
        } catch (Exception $exception) {
            $this->error('Error generating PWA icons: ' . $exception->getMessage());

            return self::FAILURE;
        }
    }
}
