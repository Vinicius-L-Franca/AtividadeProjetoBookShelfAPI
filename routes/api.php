<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\GenreController;
use Illuminate\Support\Facades\Route;

Route::apiResource('genres', GenreController::class);
Route::apiResource('books', BookController::class);