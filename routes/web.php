<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

if (!config('filamentry.only_filament')) {
    Route::get('/', function () {
        return view('welcome');
    });
}
