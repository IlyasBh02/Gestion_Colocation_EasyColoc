<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseShare;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $col_id = auth()->user()->activeMembership->colocation_id;

        $All_Expenses = Expense::where('colocation_id', $col_id)
            ->with('payer') 
            ->latest() 
            ->get();

        return view('expenses.index', compact('All_Expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'payer_id' => 'required|exists:users,id',
            'date' => 'required|date',
        ]);

        $membership = auth()->user()->colocations()
            ->wherePivot('left_at', null)
            ->first();

        if (!$membership) {
            return redirect()->back()->with('error', 'You must be in a colocation to add expenses.');
        }

        $colocation_id = $membership->id;

        $expense = Expense::create([
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
            'payer_id' => $request->payer_id,
            'colocation_id' => $colocation_id,
            'category_id' => $request->category_id,
        ]);

        $roommates = User::whereHas('colocations', function ($query) use ($colocation_id) {
            $query->where('colocations.id', $colocation_id)
                  ->whereNull('colocation_user.left_at');
        })->get();

        $shareAmount = $request->amount / $roommates->count();

        foreach ($roommates as $roommate) {
            $isPaid = $roommate->id === $request->payer_id;

            ExpenseShare::create([
                'expense_id' => $expense->id,
                'user_id' => $roommate->id,
                'amount' => round($shareAmount, 2),
                'is_paid' => $isPaid,
            ]);
        }

        return redirect()->route('colocations.show')->with('success', 'Expense added and split between roommates!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $expense = Expense::with(['payer', 'shares.user', 'category', 'colocation'])->findOrFail($id);
        
        $colocationId = $expense->colocation_id;
        
        $members = User::whereHas('colocations', function($q) use ($colocationId) {
            $q->where('colocations.id', $colocationId)->whereNull('colocation_user.left_at');
        })->get();
        
        $ownerId = DB::table('colocation_user')
            ->where('colocation_id', $colocationId)
            ->where('role', 'owner')
            ->whereNull('left_at')
            ->value('user_id');

        return view('expenses.show', compact('expense', 'members', 'ownerId'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Expense::findOrFail($id);
        
        $isOwner = DB::table('colocation_user')
            ->where('colocation_id', $expense->colocation_id)
            ->where('user_id', auth()->id())
            ->where('role', 'owner')
            ->whereNull('left_at')
            ->exists();
        
        if (!$isOwner) {
            abort(403);
        }
        
        DB::transaction(function () use ($expense) {
            $expense->shares()->delete();
            $expense->delete();
        });
        
        return redirect()->route('colocations.show')->with('success', 'Expense deleted successfully.');
    }

    public function pay($id)
    {
        $share = ExpenseShare::findOrFail($id);
        
        $colocationId = $share->expense->colocation_id;
        
        $isOwner = DB::table('colocation_user')
            ->where('colocation_id', $colocationId)
            ->where('user_id', auth()->id())
            ->where('role', 'owner')
            ->whereNull('left_at')
            ->exists();
        
        if ($isOwner || $share->user_id == auth()->id()) {
            DB::transaction(function () use ($share) {
                $share->update(['is_paid' => true]);
                
                DB::table('users')
                    ->where('id', $share->user_id)
                    ->increment('reputation', 1);
            });
            
            return redirect()->back()->with('success', 'Payment marked as paid.');
        }
        
        abort(403);
    }
}
