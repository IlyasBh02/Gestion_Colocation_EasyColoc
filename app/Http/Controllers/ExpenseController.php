<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Colocation;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function store(Request $request, Colocation $colocation)
    {
        $user = Auth::user();
        
        if (!$colocation->members->contains($user) && $colocation->owner_id !== $user->id) {
            return back()->with('error', 'Only active members can add expenses.');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        $colocation->expenses()->create([
            'category_id' => $validated['category_id'],
            'payer_id' => $user->id,
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'date' => $validated['date'],
        ]);

        return back()->with('success', 'Expense added successfully.');
    }

    public function update(Request $request, Colocation $colocation, Expense $expense)
    {
        $user = Auth::user();
        
        if ($expense->payer_id !== $user->id && $colocation->owner_id !== $user->id) {
            return back()->with('error', 'Only the payer or owner can edit this expense.');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        $expense->update($validated);

        return back()->with('success', 'Expense updated successfully.');
    }

    public function destroy(Colocation $colocation, Expense $expense)
    {
        $user = Auth::user();
        
        if ($expense->payer_id !== $user->id && $colocation->owner_id !== $user->id) {
            return back()->with('error', 'Only the payer or owner can delete this expense.');
        }

        $expense->delete();

        return back()->with('success', 'Expense deleted successfully.');
    }
}
