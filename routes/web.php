<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;

Route::get('/', [WebController::class, 'index']);
Route::get('/products/{category}', [WebController::class, 'products'])->name('products');
Route::get('/services/{category}', [WebController::class, 'services'])->name('services');
