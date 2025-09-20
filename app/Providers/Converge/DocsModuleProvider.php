<?php

declare(strict_types=1);

namespace App\Providers\Converge;

use Converge\Enums\HighlighterName;
use Converge\Enums\Layout;
use Converge\Enums\Spotlight;
use Converge\MenuItems\MenuItem;
use Converge\MenuItems\MenuItems;
use Converge\Module;
use Converge\Providers\ModuleProvider;
use Converge\Support\Themes;
use Converge\Theme\Theme;

final class DocsModuleProvider extends ModuleProvider
{
    /**
     * Register New Module Service Provider.
     */
    public function module(Module $module): Module
    {
        return $module
            ->default()
            ->id('docs')
            ->routePath('docs')
            ->in(base_path('docs'))
            ->brandLogo('Filamentry')
            ->theme(fn(Theme $theme): Theme => $this->theme($theme))
            ->defineMenuItems(function (MenuItems $menuItems): void {
                $menuItems->add(
                    fn (MenuItem $menuItem): MenuItem => $menuItem->url('https://github.com/akrista/filamentry')
                        ->openUrlInNewTab()
                        ->label('Github')
                );
            });
    }

    private function theme(Theme $theme): Theme
    {
        return $theme
            ->layout(Layout::Default)
            ->spotlight(Spotlight::Hive)
            ->highlighterTheme(
                darkmodeHighlighter: HighlighterName::Monokai,
                lightmodeHighlighter: HighlighterName::Solarized_light
            )
            ->theme(
                Themes::overrideDark([
                    '--prose-bg' => 'black',
                    '--color-base-100' => 'oklch(20% 0 0)',
                    '--color-base-200' => 'oklch(14.1% 0.005 285.823)',
                    '--color-base-300' => '#3838382e',
                    '--color-base-content' => 'oklch(0.872 0.01 258.338)',
                    '--color-primary' => '#F22B02',
                    '--color-primary-content' => 'white',
                    '--color-secondary' => '#4B5563',
                    '--color-secondary-content' => 'white',
                    '--color-accent' => 'oklch(77% 0.152 181.912)',
                    '--color-accent-content' => 'oklch(38% 0.063 188.416)',
                    '--color-neutral' => 'oklch(14% 0.005 285.823)',
                    '--color-neutral-content' => 'oklch(92% 0.004 286.32)',
                    '--color-info' => '#3B82F6',
                    '--color-info-content' => 'white',
                    '--color-success' => '#10B981',
                    '--color-success-content' => 'white',
                    '--color-warning' => '#F59E0B',
                    '--color-warning-content' => 'white',
                    '--color-error' => '#EF4444',
                    '--color-error-content' => 'white',
                    '--radius-selector' => '0.5rem',
                    '--radius-field' => '.75rem',
                    '--radius-box' => '1rem',
                    '--size-selector' => '0.25rem',
                    '--size-field' => '0.25rem',
                    '--border' => '.1px',
                    '--depth' => '0.25rem',
                    '--noise' => '0.5',
                    '--text-sm' => '0.88rem',
                    '--text-base' => '0.94rem',
                    '--font-weight' => '400',
                ]),
                Themes::overrideLight([
                    '--color-base-100' => 'oklch(97.788% 0.004 56.375)',
                    '--color-base-200' => 'oklch(0.985 0.001 106.423)',
                    '--color-base-300' => 'oklch(91.586% 0.006 53.44)',
                    '--color-base-content' => 'oklch(23.574% 0.066 313.189)',
                    '--color-primary' => '#F22B02',
                    '--color-primary-content' => 'white',
                    '--color-secondary' => '#4B5563',
                    '--color-secondary-content' => 'white',
                    '--color-accent' => '#2878ad',
                    '--color-accent-content' => 'oklch(47% 0.157 37.304)',
                    '--color-neutral' => 'oklch(27% 0.006 286.033)',
                    '--color-neutral-content' => 'oklch(92% 0.004 286.32)',
                    '--color-info' => '#3B82F6',
                    '--color-info-content' => 'white',
                    '--color-success' => '#10B981',
                    '--color-success-content' => 'white',
                    '--color-warning' => '#F59E0B',
                    '--color-warning-content' => 'white',
                    '--color-error' => '#EF4444',
                    '--color-error-content' => 'white',
                    '--radius-selector' => '0.5rem',
                    '--radius-field' => '0.75rem',
                    '--radius-box' => '1rem',
                    '--size-selector' => '0.25rem',
                    '--size-field' => '0.25rem',
                    '--border' => '.1px',
                    '--depth' => '0.25rem',
                    '--noise' => '0.5',
                    '--text-sm' => '0.88rem',
                    '--text-base' => '0.94rem',
                    '--font-weight' => '400',
                ]),
            );
    }
}
