<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\TransactionItemController;

Route::apiResource('users', UserController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('expenses', ExpenseController::class);
Route::apiResource('transactions', TransactionController::class);
Route::apiResource('transaction-items', TransactionItemController::class);
