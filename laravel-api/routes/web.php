<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'apidocs');

// Web test dashboard
Route::get('/web-test', function () {
    return response()->file(public_path('web-test.html'));
});
