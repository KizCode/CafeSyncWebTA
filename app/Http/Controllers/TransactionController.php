<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function showPayment(Request $request)
    {
        // Data akan diambil dari sessionStorage via JavaScript
        return view('cashier.payment');
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,qris,debit,credit',
            'customer_name' => 'nullable|string',
            'items' => 'required|json',
            'subtotal' => 'required|numeric',
            'discount_amount' => 'nullable|numeric',
            'tax_amount' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'cash_received' => 'nullable|numeric',
            'change_amount' => 'nullable|numeric',
        ]);

        DB::beginTransaction();
        try {
            $cartItems = json_decode($request->items, true);

            // Create transaction
            $transaction = Transaction::create([
                'invoice_number' => Transaction::generateInvoiceNumber(),
                'subtotal' => $request->subtotal,
                'discount_type' => null,
                'discount_value' => 0,
                'discount_amount' => $request->discount_amount ?? 0,
                'is_tax_enabled' => ($request->tax_amount ?? 0) > 0,
                'tax_amount' => $request->tax_amount ?? 0,
                'grand_total' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'paid_amount' => $request->cash_received ?? $request->total_amount,
                'change_amount' => $request->change_amount ?? 0,
                'status' => 'lunas',
            ]);

            // Create transaction items and update stock
            foreach ($cartItems as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);

                // Update product stock
                $product = Product::find($item['id']);
                if ($product) {
                    $product->stock -= $item['quantity'];
                    $product->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil',
                'invoice_number' => $transaction->invoice_number,
                'transaction_id' => $transaction->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
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
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('transactions.history', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = Transaction::with('items.product')->findOrFail($id);

        return view('transactions.show', compact('transaction'));
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

        $transactions = Transaction::with('items.product')
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ])
            ->where('status', 'lunas')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = $transactions->sum('grand_total');
        $totalTransactions = $transactions->count();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('transactions.export-pdf', compact('transactions', 'startDate', 'endDate', 'totalRevenue', 'totalTransactions'));
        return $pdf->download('riwayat-transaksi.pdf');
    }
}
