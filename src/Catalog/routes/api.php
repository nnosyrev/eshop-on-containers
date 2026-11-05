<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TypeController;
use Illuminate\Support\Facades\Route;

Route::apiResource('brands', BrandController::class)->only('index');

Route::apiResource('types', TypeController::class)->only('index');

Route::get('items/by/ids', [ItemController::class, 'showByIds'])->name('items.showByIds');
Route::get('items/by/name', [ItemController::class, 'indexByName'])->name('items.indexByName');
Route::apiResource('items', ItemController::class)->only(['index', 'store', 'show', 'destroy']);
