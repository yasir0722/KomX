<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| The SPA catch-all route serves the Vue application for all non-API
| routes. The Vue Router handles client-side routing from there.
|
*/

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
