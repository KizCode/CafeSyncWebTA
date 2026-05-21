<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransactionItem;
use Illuminate\Http\Request;

class TransactionItemController extends Controller
{
    public function index()
    {
        return response()->json(TransactionItem::with('product')->get());
    }

    public function show(TransactionItem $transactionItem)
    {
        return response()->json($transactionItem->load('product'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric',
            'total_price' => 'required|numeric',
        ]);

        $item = TransactionItem::create($data);

        return response()->json($item, 201);
    }

    public function update(Request $request, TransactionItem $transactionItem)
    {
        $data = $request->validate([
            'quantity' => 'sometimes|required|integer|min:1',
            'unit_price' => 'sometimes|required|numeric',
            'total_price' => 'sometimes|required|numeric',
        ]);

        $transactionItem->update($data);

        return response()->json($transactionItem);
    }

    public function destroy(TransactionItem $transactionItem)
    {
        $transactionItem->delete();
        return response()->json(null, 204);
    }
}
