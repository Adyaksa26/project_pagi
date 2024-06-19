<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdukController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('apiproduk', [produkController::class, 'index']);
Route::get('apiproduk/{id}', [ProdukController::class, 'show']);