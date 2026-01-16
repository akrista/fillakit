<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Spatie\TranslationLoader\LanguageLine;
use Spatie\TranslationLoader\TranslationLoaderManager as BaseTranslationLoaderManager;
use Spatie\TranslationLoader\TranslationLoaders\TranslationLoader;

final class TranslationLoaderManager extends BaseTranslationLoaderManager
{
    /**
     * Load the messages for the given locale.
     *
     * This override allows database translations to be loaded for namespaced
     * translation groups (e.g., filament-panels::pages/dashboard), which the
     * original Spatie implementation skips.
     */
    public function load($locale, $group, $namespace = null): array
    {
        try {
            $fileTranslations = parent::load($locale, $group, $namespace);

            $loaderTranslations = $this->getTranslationsForTranslationLoaders($locale, $group, $namespace);

            return array_replace_recursive($fileTranslations, $loaderTranslations);
        } catch (QueryException $queryException) {
            $modelClass = config('translation-loader.model');
            $model = new $modelClass();

            if ($model instanceof LanguageLine && ! Schema::hasTable($model->getTable())) {
                return parent::load($locale, $group, $namespace);
            }

            throw $queryException;
        }
    }

    protected function getTranslationsForTranslationLoaders(
        string $locale,
        string $group,
        ?string $namespace = null
    ): array {
        $fullGroup = $namespace && $namespace !== '*'
            ? sprintf('%s::%s', $namespace, $group)
            : $group;

        return collect(config('translation-loader.translation_loaders'))
            ->map(fn (string $className) => resolve($className))
            ->mapWithKeys(fn (TranslationLoader $translationLoader): array => $translationLoader->loadTranslations($locale, $fullGroup))
            ->toArray();
    }
}
