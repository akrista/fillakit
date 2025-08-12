<?php

declare(strict_types=1);

return Rector\Config\RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/tests',
    ])
    ->withPhpVersion(
        Rector\ValueObject\PhpVersion::PHP_84
    )
    ->withSets([
        Rector\Set\ValueObject\SetList::DEAD_CODE,
        Rector\Set\ValueObject\SetList::EARLY_RETURN,
        Rector\Set\ValueObject\SetList::TYPE_DECLARATION,
        Rector\Set\ValueObject\SetList::CODE_QUALITY,
        Rector\Set\ValueObject\SetList::CODING_STYLE,
        Rector\Set\ValueObject\SetList::STRICT_BOOLEANS,
        Rector\Set\ValueObject\SetList::PRIVATIZATION,
        RectorLaravel\Set\LaravelLevelSetList::UP_TO_LARAVEL_120,
        RectorLaravel\Set\LaravelSetList::LARAVEL_CODE_QUALITY,
        RectorLaravel\Set\LaravelSetList::LARAVEL_COLLECTION,
    ])
    ->withRules([
        Rector\Php84\Rector\Param\ExplicitNullableParamTypeRector::class,
    ]);
