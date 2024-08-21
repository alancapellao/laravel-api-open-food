<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductsController;

Route::apiResource('products', ProductsController::class);
