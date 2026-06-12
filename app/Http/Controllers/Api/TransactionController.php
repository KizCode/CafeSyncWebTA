<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Services\ProductionQueueService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        return response()->json(Transaction::with('items.product')->get());
    }

    public function show(Transaction $transaction)
    {
        return response()->json($transaction->load('items.product'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|min:2|max:50',
            'subtotal' => 'required|numeric',
            'discount_amount' => 'nullable|numeric',
            'discount_type' => 'nullable|string',
            'discount_value' => 'nullable|numeric',
            'tax_amount' => 'nullable|numeric',
            'is_tax_enabled' => 'nullable|boolean',
            'grand_total' => 'required|numeric',
            'payment_method' => 'nullable|string',
            'paid_amount' => 'nullable|numeric',
            'change_amount' => 'nullable|numeric',
            'status' => 'nullable|in:lunas,belum_lunas',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $transactionData = array_merge($data, [
                'invoice_number' => Transaction::generateInvoiceNumber(),
                'status' => $data['status'] ?? 'lunas',
            ]);

            unset($transactionData['items']);

            $queueOnCreate = ProductionQueueService::queueAttributesForNewPayment($data['customer_name']);
            $transaction = Transaction::create(array_merge($transactionData, $queueOnCreate));

            foreach ($data['items'] as $item) {
                $itemData = [
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ];

                TransactionItem::create($itemData);
            }

            app(StockService::class)->deductForSale(
                $data['items'],
                null,
                $transaction->id
            );

            if (($transaction->status ?? 'lunas') === 'lunas') {
                $enqueue = ProductionQueueService::attachPaidOrderToQueue($transaction, $data['customer_name']);
                if (! $enqueue['success']) {
                    throw new \RuntimeException(
                        ProductionQueueService::queueFailureMessage($enqueue['reason'] ?? null)
                    );
                }
            }

            DB::commit();

            return response()->json($transaction->load('items.product'), 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'subtotal' => 'sometimes|required|numeric',
            'discount_amount' => 'nullable|numeric',
            'discount_type' => 'nullable|string',
            'discount_value' => 'nullable|numeric',
            'tax_amount' => 'nullable|numeric',
            'is_tax_enabled' => 'nullable|boolean',
            'grand_total' => 'sometimes|required|numeric',
            'payment_method' => 'nullable|string',
            'paid_amount' => 'nullable|numeric',
            'change_amount' => 'nullable|numeric',
            'status' => 'nullable|string',
        ]);

        $transaction->update($data);

        return response()->json($transaction->load('items.product'));
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return response()->json(null, 204);
    }
}
