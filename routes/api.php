<?php

use App\Http\Controllers\AutorController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource( 'autor',  AutorController::class);
Route::resource('post', PostController::class);
