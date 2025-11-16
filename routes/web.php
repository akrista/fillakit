<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

if (!config('fillakit.only_filament')) {
    Route::get('/', fn(): Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View => view('welcome'));
}
