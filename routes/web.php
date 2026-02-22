<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

if (!config('fillakit.only_filament')) {
    Route::get('/', fn() => Inertia::render('App', [
        'canLogin' => Route::has('filament.admin.auth.login'),
        'canRegister' => Route::has('filament.admin.auth.register'),
        'loginUrl' => Route::has('filament.admin.auth.login') ? route('filament.admin.auth.login') : null,
        'registerUrl' => Route::has('filament.admin.auth.register') ? route('filament.admin.auth.register') : null,
        'dashboardUrl' => Route::has('filament.admin.pages.dashboard') ? route('filament.admin.pages.dashboard') : null,
    ]))->name('home');
}

Route::get('filament/switch-language/{code}', static function (string $code): Illuminate\Http\RedirectResponse {
    request()->session()->put('locale', $code);

    return back();
})->middleware(['web'])->name('language-switcher.switch');
