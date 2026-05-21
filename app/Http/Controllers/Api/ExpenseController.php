<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        return response()->json(Expense::all());
    }

    public function show(Expense $expense)
    {
        return response()->json($expense);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric',
            'expense_date' => 'required|date',
            'category' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $expense = Expense::create($data);

        return response()->json($expense, 201);
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'description' => 'sometimes|required|string',
            'amount' => 'sometimes|required|numeric',
            'expense_date' => 'sometimes|required|date',
            'category' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $expense->update($data);

        return response()->json($expense);
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return response()->json(null, 204);
    }
}
