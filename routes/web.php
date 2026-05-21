<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/transactions/history/pdf', [App\Http\Controllers\TransactionController::class, 'exportPdf'])->name('transactions.history.pdf');
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return redirect()->route('cashier.index');
    })->name('dashboard');

    // Refresh CSRF Token
    Route::get('/refresh-csrf', function () {
        return response()->json(['token' => csrf_token()]);
    });

    // Cashier Routes
    Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.index');
    Route::get('/cashier/products', [CashierController::class, 'getProducts'])->name('cashier.products');

    // Transaction Routes
    Route::post('/transactions', [TransactionController::class, 'processPayment'])->name('transactions.store');
    Route::get('/payment', [TransactionController::class, 'showPayment'])->name('transactions.payment');
    Route::post('/payment/process', [TransactionController::class, 'processPayment'])->name('transactions.process');
    Route::get('/receipt/{id}', [TransactionController::class, 'receipt'])->name('transactions.receipt');
    Route::get('/transactions', [TransactionController::class, 'history'])->name('transactions.history');
    Route::get('/transactions/export-pdf', [TransactionController::class, 'exportPdf'])->name('transactions.export-pdf');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{id}/pdf', [TransactionController::class, 'downloadPdf'])->name('transactions.pdf');
    Route::get('/transactions/{id}/print', [TransactionController::class, 'print'])->name('transactions.print');

    // Report Routes
    Route::get('/transactions/history/pdf', [TransactionController::class, 'exportPdf'])->name('transactions.history.pdf');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportController::class, 'downloadPdf'])->name('reports.pdf');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/account', [ProfileController::class, 'account'])->name('profile.account');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
