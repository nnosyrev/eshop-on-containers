<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\TypeController;
use Illuminate\Support\Facades\Route;

Route::apiResource('brands', BrandController::class)->only('index');
Route::apiResource('types', TypeController::class)->only('index');
