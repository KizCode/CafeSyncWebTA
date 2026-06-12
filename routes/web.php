<?php

use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Cashier\ProductController as CashierProductController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\QueueSettingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Warehouse\DashboardController as WarehouseDashboardController;
use App\Http\Controllers\Warehouse\IngredientController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/transactions/history/pdf', [TransactionController::class, 'exportPdf'])->name('transactions.history.pdf');

Route::get('/locale/{locale}', LocaleController::class)->name('locale.switch');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route(auth()->user()->homeRoute());
    })->name('dashboard');

    Route::get('/refresh-csrf', function () {
        return response()->json(['token' => csrf_token()]);
    });

    // Profile — semua role
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/account', [ProfileController::class, 'account'])->name('profile.account');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kasir + Admin — riwayat transaksi & struk
    Route::middleware(['role:Kasir,Administrator'])->group(function () {
        Route::get('/transactions', [TransactionController::class, 'history'])->name('transactions.history');
        Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('/transactions/{id}/struk', [TransactionController::class, 'struk'])->name('transactions.struk');
        Route::get('/transactions/{id}/receipt-fragment', [TransactionController::class, 'receiptFragment'])->name('transactions.receipt-fragment');
        Route::get('/transactions/{id}/pdf', [TransactionController::class, 'downloadPdf'])->name('transactions.pdf');
        Route::get('/transactions/{id}/print', [TransactionController::class, 'print'])->name('transactions.print');
    });

    // Kasir — hanya role Kasir
    Route::middleware(['role:Kasir'])->group(function () {
        Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.index');

        Route::post('/transactions', [TransactionController::class, 'processPayment'])->name('transactions.store');
        Route::get('/payment', [TransactionController::class, 'showPayment'])->name('transactions.payment');
        Route::post('/payment/process', [TransactionController::class, 'processPayment'])->name('transactions.process');
        Route::get('/receipt/{id}', [TransactionController::class, 'receipt'])->name('transactions.receipt');

        Route::get('/queue', [QueueController::class, 'index'])->name('queue.index');
        Route::post('/queue/reorder', [QueueController::class, 'reorder'])->name('queue.reorder');
        Route::patch('/queue/{transaction}/status', [QueueController::class, 'updateOrderStatus'])->name('queue.update-status');
        Route::patch('/queue/{transaction}/name', [QueueController::class, 'updateOrderName'])->name('queue.update-name');

        Route::resource('cashier/products', CashierProductController::class)
            ->names('cashier.products')
            ->except(['show']);
    });

    // Admin
    Route::middleware(['role:Administrator'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', AdminProductController::class)->except(['show']);
    });

    Route::middleware(['role:Administrator'])->group(function () {
        Route::get('/transactions/export-pdf', [TransactionController::class, 'exportPdf'])->name('transactions.export-pdf');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/preview', [ReportController::class, 'preview'])->name('reports.preview');
        Route::get('/reports/pdf', [ReportController::class, 'streamPdf'])->name('reports.pdf');
        Route::get('/reports/pdf/download', [ReportController::class, 'downloadPdf'])->name('reports.pdf.download');

        Route::get('/settings/queue', [QueueSettingController::class, 'edit'])->name('settings.queue');
        Route::put('/settings/queue', [QueueSettingController::class, 'update'])->name('settings.queue.update');
        Route::post('/settings/queue/statuses', [QueueSettingController::class, 'storeStatus'])->name('settings.queue.statuses.store');
        Route::put('/settings/queue/statuses/{status}', [QueueSettingController::class, 'updateStatus'])->name('settings.queue.statuses.update');
        Route::delete('/settings/queue/statuses/{status}', [QueueSettingController::class, 'destroyStatus'])->name('settings.queue.statuses.destroy');
    });

    // Gudang — hanya role Gudang
    Route::middleware(['role:Gudang'])->prefix('gudang')->name('warehouse.')->group(function () {
        Route::get('/', [WarehouseDashboardController::class, 'index'])->name('index');
        Route::resource('bahan-baku', IngredientController::class)
            ->parameters(['bahan-baku' => 'ingredient'])
            ->names('ingredients')
            ->except(['show']);
        Route::post('bahan-baku/{ingredient}/stok-masuk', [IngredientController::class, 'stockIn'])->name('ingredients.stock-in');
        Route::post('bahan-baku/{ingredient}/stok-sesuaikan', [IngredientController::class, 'adjust'])->name('ingredients.adjust');
        Route::get('bahan-baku/{ingredient}/riwayat', [IngredientController::class, 'movements'])->name('ingredients.movements');
    });
});

require __DIR__ . '/auth.php';
