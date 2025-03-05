<?php

use App\Http\Controllers\AutorController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/admin', function () {
    return Inertia::render('admin');
})->name('admin');

require __DIR__.'/api.php';

