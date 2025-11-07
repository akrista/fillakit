<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

if (!config('fillakit.only_filament')) {
    Route::get('/', fn() => Inertia::render('welcome'))->name('home');
}
