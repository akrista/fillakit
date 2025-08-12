<?php

use Illuminate\Support\Facades\Route;

if (config('filamenter.only_filament')) {
    Route::get('/', function () {
        return view('welcome');
    });
}
