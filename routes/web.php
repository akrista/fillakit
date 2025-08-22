<?php

use Illuminate\Support\Facades\Route;

if (!config('filamentry.only_filament')) {
    Route::get('/', function () {
        return view('welcome');
    });
}
