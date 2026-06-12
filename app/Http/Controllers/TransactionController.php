<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Services\ProductionQueueService;
use App\Services\StockService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function showPayment(Request $request)
    {
        // Data akan diambil dari sessionStorage via JavaScript
        return view('cashier.payment');
    }

    public function processPayment(Request $request)
    {
        $this->normalizePaymentRequest($request);

        try {
            $validated = $request->validate([
                'payment_method' => 'required|in:cash,tunai,qris,debit,credit',
                'customer_name' => ['required', 'string', 'min:2', 'max:50'],
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'nullable|integer|exists:products,id',
                'items.*.id' => 'nullable|integer|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit_price' => 'required|numeric|min:0',
                'items.*.price' => 'nullable|numeric|min:0',
                'subtotal' => 'required|numeric',
                'discount_amount' => 'nullable|numeric',
                'tax_amount' => 'nullable|numeric',
                'grand_total' => 'required|numeric|min:0',
                'total_amount' => 'nullable|numeric|min:0',
                'paid_amount' => 'nullable|numeric|min:0',
                'cash_received' => 'nullable|numeric|min:0',
                'change_amount' => 'nullable|numeric|min:0',
            ], [
                'customer_name.required' => 'Nama antrian wajib diisi.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?? 'Data pembayaran tidak valid.',
                'errors' => $e->errors(),
            ], 422);
        }

        $queueDisplayName = trim((string) $validated['customer_name']);

        ProductionQueueService::bootstrapStatuses();

        DB::beginTransaction();
        try {
            $cartItems = $validated['items'];

            $paymentMethod = $validated['payment_method'] === 'cash' ? 'tunai' : $validated['payment_method'];
            $paidAmount = $this->resolvePaidAmount($request, $validated);
            $changeAmount = $request->has('change_amount')
                ? (float) $request->input('change_amount')
                : max(0, $paidAmount - (float) $validated['grand_total']);

            $transactionPayload = [
                'invoice_number' => Transaction::generateInvoiceNumber(),
                'subtotal' => $validated['subtotal'],
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'discount_type' => $request->input('discount_type') ?: null,
                'discount_value' => $request->input('discount_value', 0),
                'is_tax_enabled' => filter_var($request->input('is_tax_enabled'), FILTER_VALIDATE_BOOLEAN),
                'tax_amount' => $validated['tax_amount'] ?? 0,
                'grand_total' => $validated['grand_total'],
                'payment_method' => $paymentMethod,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'status' => 'lunas',
            ];

            if (Schema::hasColumn('transactions', 'customer_name')) {
                $transactionPayload['customer_name'] = $queueDisplayName;
            }

            $queueOnCreate = ProductionQueueService::queueAttributesForNewPayment($queueDisplayName);

            $transaction = Transaction::create(array_merge($transactionPayload, $queueOnCreate));

            app(StockService::class)->validateSale($cartItems);

            // Create transaction items and update stock
            foreach ($cartItems as $item) {
                $productId = $item['product_id'] ?? $item['id'] ?? null;
                $unitPrice = $item['price'] ?? $item['unit_price'] ?? 0;
                $quantity = (int) ($item['quantity'] ?? 0);

                if (! $productId || $quantity < 1) {
                    throw new \InvalidArgumentException('Item keranjang tidak valid.');
                }

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $unitPrice * $quantity,
                ]);
            }

            app(StockService::class)->deductForSale(
                $cartItems,
                $request->user()?->id,
                $transaction->id
            );

            $enqueue = ProductionQueueService::attachPaidOrderToQueue($transaction, $queueDisplayName);
            if (! $enqueue['success']) {
                throw new \RuntimeException(
                    ProductionQueueService::queueFailureMessage($enqueue['reason'] ?? null)
                );
            }

            $transaction->refresh();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil. Pesanan masuk antrian.',
                'invoice_number' => $transaction->invoice_number,
                'transaction_id' => $transaction->id,
                'queue_number' => $transaction->queue_number,
                'queued' => true,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $this->paymentErrorMessage($e),
            ], 500);
        }
    }

    public function receipt($id)
    {
        $transaction = Transaction::with('items.product')->findOrFail($id);

        return view('cashier.receipt', compact('transaction'));
    }

    public function history(Request $request)
    {
        $query = Transaction::with('items');

        // Filter by date if provided
        if ($request->start_date && $request->end_date) {
            $timezone = config('app.timezone');
            $start = Carbon::parse($request->start_date, $timezone)->startOfDay();
            $end = Carbon::parse($request->end_date, $timezone)->endOfDay();

            $query->whereBetween('created_at', [$start, $end]);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        $layout = $request->user()?->role?->name === 'Kasir'
            ? 'layouts.cashier'
            : 'layouts.admin';

        return view('transactions.history', compact('transactions', 'layout'));
    }

    public function show($id)
    {
        $transaction = Transaction::with('items.product')->findOrFail($id);

        return view('transactions.show', compact('transaction'));
    }

    public function struk($id)
    {
        $transaction = Transaction::with('items.product')->findOrFail($id);

        return view('transactions.struk', compact('transaction'));
    }

    public function receiptFragment($id)
    {
        $transaction = Transaction::with('items.product')->findOrFail($id);

        return view('components.transaction-receipt', [
            'transaction' => $transaction,
        ]);
    }

    public function downloadPdf($id)
    {
        $transaction = Transaction::with('items.product')->findOrFail($id);

        return view('transactions.pdf', compact('transaction'));
    }

    public function print($id)
    {
        $transaction = Transaction::with('items.product')->findOrFail($id);

        return view('transactions.print', compact('transaction'));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $timezone = config('app.timezone');
        $rangeStart = Carbon::parse($startDate, $timezone)->startOfDay();
        $rangeEnd = Carbon::parse($endDate, $timezone)->endOfDay();

        $transactions = Transaction::with('items.product')
            ->whereBetween('created_at', [$rangeStart, $rangeEnd])
            ->where('status', 'lunas')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = $transactions->sum('grand_total');
        $totalTransactions = $transactions->count();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('transactions.export-pdf', compact('transactions', 'startDate', 'endDate', 'totalRevenue', 'totalTransactions'));
        return $pdf->download('riwayat-transaksi.pdf');
    }

    private function normalizePaymentRequest(Request $request): void
    {
        $grandTotal = $request->input('grand_total', $request->input('total_amount'));
        $paidAmount = $request->input('paid_amount', $request->input('cash_received'));

        $request->merge([
            'grand_total' => $grandTotal,
            'total_amount' => $request->input('total_amount', $grandTotal),
            'paid_amount' => $paidAmount ?? $grandTotal,
            'cash_received' => $request->input('cash_received', $paidAmount ?? $grandTotal),
        ]);

        $items = $request->input('items');

        if (is_string($items)) {
            $decoded = json_decode($items, true);
            if (is_array($decoded)) {
                $items = $decoded;
            }
        }

        if (! is_array($items) && $request->filled('cart_items')) {
            $decoded = json_decode((string) $request->input('cart_items'), true);
            if (is_array($decoded)) {
                $items = $decoded;
            }
        }

        if (! is_array($items)) {
            return;
        }

        $items = array_values(array_map(static function ($item): array {
            $item = is_array($item) ? $item : [];
            $item['product_id'] = $item['product_id'] ?? $item['id'] ?? null;
            $item['unit_price'] = $item['unit_price'] ?? $item['price'] ?? 0;

            return $item;
        }, $items));

        $request->merge(['items' => $items]);
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function resolvePaidAmount(Request $request, array $validated): float
    {
        foreach (['paid_amount', 'cash_received'] as $field) {
            if ($request->filled($field)) {
                return (float) $request->input($field);
            }
        }

        return (float) $validated['grand_total'];
    }

    private function paymentErrorMessage(\Throwable $e): string
    {
        if ($e instanceof \InvalidArgumentException) {
            return $e->getMessage();
        }

        $message = $e->getMessage();

        if (str_contains($message, "Field 'paid_amount'")) {
            return 'Jumlah pembayaran wajib diisi.';
        }

        if (config('app.debug')) {
            return 'Terjadi kesalahan: '.$message;
        }

        return 'Gagal menyimpan transaksi. Silakan coba lagi.';
    }

}
