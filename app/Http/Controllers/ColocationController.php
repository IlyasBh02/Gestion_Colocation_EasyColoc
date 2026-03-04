<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Colocation;
use App\Models\Expense;
use App\Models\ExpenseShare;
use App\Models\Invitation;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colocations = Colocation::with('owner')->get();
        return view('colocations.index', compact('colocations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()->hasActiveMembership()) {
            return redirect()->route('colocations.show')->with('error', 'You cannot create a new colocation while you have an active membership.');
        }
        return view('colocations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if ($request->user()->hasActiveMembership()) {
            return redirect()->back()->with('error', 'You are already an active member of a colocation.');
        }

        DB::transaction(function() use ($request){
            $colocation = Colocation::create([
                'name' => $request->name,
                'owner_id' => $request->user()->id,
            ]);

            DB::table('colocation_user')->insert([
                'user_id' => $request->user()->id,
                'colocation_id' => $colocation->id,
                'role' => 'owner',
                'joined_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        });

        return redirect()->route('colocations.show')->with('success', 'Colocation created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $colocation = Colocation::with(['owner', 'members'])->findOrFail($id);
        
        $isMember = DB::table('colocation_user')
            ->where('colocation_id', $id)
            ->where('user_id', auth()->id())
            ->whereNull('left_at')
            ->exists();
        
        $pendingInvitation = null;
        if (auth()->check()) {
            $pendingInvitation = Invitation::where('colocation_id', $id)
                ->where('email', auth()->user()->email)
                ->where('status', 'pending')
                ->first();
        }
        
        $month = request()->month ?? now()->format('Y-m');
        
        $expensesQuery = Expense::where('colocation_id', $id)
            ->with(['payer', 'shares.user', 'category']);
        
        if ($month) {
            $expensesQuery->whereYear('date', substr($month, 0, 4))
                         ->whereMonth('date', substr($month, 5, 2));
        }
        
        $expenses = $expensesQuery->latest()->get();
        
        $categories = Category::all();
        
        $members = User::whereHas('colocations', function($q) use ($id) {
            $q->where('colocations.id', $id)->whereNull('colocation_user.left_at');
        })->get();
        
        $balances = [];
        foreach ($members as $member) {
            $totalPaid = Expense::where('colocation_id', $id)
                ->where('payer_id', $member->id)
                ->sum('amount');
            
            $totalShareUnpaid = ExpenseShare::whereHas('expense', function($q) use ($id) {
                $q->where('colocation_id', $id);
            })
            ->where('user_id', $member->id)
            ->where('is_paid', false)
            ->sum('amount');
            
            $totalShareAll = ExpenseShare::whereHas('expense', function($q) use ($id) {
                $q->where('colocation_id', $id);
            })
            ->where('user_id', $member->id)
            ->sum('amount');
            
            $actualBalance = $totalShareUnpaid > 0 ? -$totalShareUnpaid : ($totalPaid - $totalShareAll);
            
            $balances[] = [
                'user' => $member,
                'total_paid' => $totalPaid,
                'share' => $totalShareAll,
                'unpaid' => $totalShareUnpaid,
                'balance' => $actualBalance
            ];
        }
        
        $i = 0;
        $j = 0;
        while ($i < count($debtorsArray) && $j < count($creditorsArray)) {
            $debt = abs($debtorsArray[$i]['balance']);
            $credit = $creditorsArray[$j]['balance'];
            $amount = min($debt, $credit);
            
            $settlements[] = [
                'from' => $debtorsArray[$i]['user'],
                'to' => $creditorsArray[$j]['user'],
                'amount' => $amount
            ];
            
            $debtorsArray[$i]['balance'] += $amount;
            $creditorsArray[$j]['balance'] -= $amount;
            
            if (abs($debtorsArray[$i]['balance']) < 0.01) $i++;
            if (abs($creditorsArray[$j]['balance']) < 0.01) $j++;
        }
        
        return view('colocations.show', compact('colocation', 'isMember', 'pendingInvitation', 'expenses', 'categories', 'month', 'balances', 'settlements'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $colocation = Colocation::findOrFail($id);
        
        $userRole = DB::table('colocation_user')
            ->where('colocation_id', $id)
            ->where('user_id', auth()->id())
            ->whereNull('left_at')
            ->value('role');
        
        if ($userRole !== 'owner') {
            abort(403);
        }
        
        return view('colocations.edit', compact('colocation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $colocation = Colocation::findOrFail($id);
        
        $userRole = DB::table('colocation_user')
            ->where('colocation_id', $id)
            ->where('user_id', auth()->id())
            ->whereNull('left_at')
            ->value('role');
        
        if ($userRole !== 'owner') {
            abort(403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $colocation->update([
            'name' => $request->name,
        ]);
        
        return redirect()->back()->with('success', 'Colocation updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $colocation = Colocation::findOrFail($id);
        
        $userRole = DB::table('colocation_user')
            ->where('colocation_id', $id)
            ->where('user_id', auth()->id())
            ->whereNull('left_at')
            ->value('role');
        
        if ($userRole !== 'owner') {
            abort(403);
        }
        
        DB::transaction(function() use ($id, $colocation) {
            DB::table('expense_shares')
                ->whereIn('expense_id', function($query) use ($id) {
                    $query->select('id')->from('expenses')->where('colocation_id', $id);
                })->delete();
            
            DB::table('expenses')->where('colocation_id', $id)->delete();
            DB::table('invitations')->where('colocation_id', $id)->delete();
            DB::table('colocation_user')->where('colocation_id', $id)->delete();
            
            $colocation->delete();
        });
        
        return redirect()->route('dashboard')->with('success', 'Colocation deleted successfully!');
    }
    
    public function removeMember(Request $request, $colocationId, $userId)
    {
        $userRole = DB::table('colocation_user')
            ->where('colocation_id', $colocationId)
            ->where('user_id', auth()->id())
            ->whereNull('left_at')
            ->value('role');
        
        if ($userRole !== 'owner') {
            abort(403);
        }
        
        $ownerId = auth()->id();
        
        DB::transaction(function() use ($colocationId, $userId, $ownerId) {
            DB::table('expense_shares')
                ->whereIn('expense_id', function($query) use ($colocationId) {
                    $query->select('id')
                        ->from('expenses')
                        ->where('colocation_id', $colocationId);
                })
                ->where('user_id', $userId)
                ->where('is_paid', false)
                ->update(['user_id' => $ownerId]);
            
            DB::table('colocation_user')
                ->where('colocation_id', $colocationId)
                ->where('user_id', $userId)
                ->update(['left_at' => now()]);
            
            DB::table('users')
                ->where('id', $userId)
                ->decrement('reputation', 1);
        });
        
        return redirect()->back()->with('success', 'Member removed successfully!');
    }
    
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $membership = $user->colocations()->wherePivot('left_at', null)->first();
        
        if (!$membership) {
            return redirect()->route('welcome');
        }
        
        $colocation = $membership;
        $colocationId = $membership->id;
        $month = $request->month ?? now()->format('Y-m');
        $selectedMonth = $month;
        
        $expensesQuery = Expense::where('colocation_id', $colocationId)
            ->with(['payer', 'shares.user', 'category']);
        
        if ($selectedMonth && $selectedMonth != 'all') {
            $expensesQuery->whereMonth('date', $selectedMonth);
        }
        
        $expenses = $expensesQuery->latest()->get();
        
        $members = User::whereHas('colocations', function($q) use ($colocationId) {
            $q->where('colocations.id', $colocationId)->whereNull('colocation_user.left_at');
        })->get();
        
        $owedToMe = ExpenseShare::whereHas('expense', function($q) use ($colocationId) {
            $q->where('colocation_id', $colocationId)->where('payer_id', auth()->id());
        })
        ->where('user_id', '!=', auth()->id())
        ->where('is_paid', false)
        ->with('user')
        ->get()
        ->groupBy('user_id');
        
        $owedByMe = ExpenseShare::whereHas('expense', function($q) use ($colocationId) {
            $q->where('colocation_id', $colocationId);
        })
        ->where('user_id', auth()->id())
        ->where('is_paid', false)
        ->with('expense.payer')
        ->get()
        ->groupBy(function($share) {
            return $share->expense->payer_id;
        });
        
        $ownerId = DB::table('colocation_user')
            ->where('colocation_id', $colocationId)
            ->where('role', 'owner')
            ->whereNull('left_at')
            ->value('user_id');
        
        $categories = Category::all();
        
        // Calculate balances
        $balances = [];
        foreach ($members as $member) {
            $totalPaid = Expense::where('colocation_id', $colocationId)
                ->where('payer_id', $member->id)
                ->sum('amount');
            
            $totalShareUnpaid = ExpenseShare::whereHas('expense', function($q) use ($colocationId) {
                $q->where('colocation_id', $colocationId);
            })
            ->where('user_id', $member->id)
            ->where('is_paid', false)
            ->sum('amount');
            
            $totalShareAll = ExpenseShare::whereHas('expense', function($q) use ($colocationId) {
                $q->where('colocation_id', $colocationId);
            })
            ->where('user_id', $member->id)
            ->sum('amount');
            
            $actualBalance = $totalShareUnpaid > 0 ? -$totalShareUnpaid : ($totalPaid - $totalShareAll);
            
            $balances[] = [
                'user' => $member,
                'total_paid' => $totalPaid,
                'share' => $totalShareAll,
                'unpaid' => $totalShareUnpaid,
                'balance' => $actualBalance
            ];
        }
        
        // Calculate settlements
        $settlements = [];
        $debtorsArray = collect($balances)->filter(fn($b) => $b['balance'] < -0.01)->sortBy('balance')->values()->toArray();
        $creditorsArray = collect($balances)->filter(fn($b) => $b['balance'] > 0.01)->sortByDesc('balance')->values()->toArray();
        
        $i = 0;
        $j = 0;
        while ($i < count($debtorsArray) && $j < count($creditorsArray)) {
            $debt = abs($debtorsArray[$i]['balance']);
            $credit = $creditorsArray[$j]['balance'];
            $amount = min($debt, $credit);
            
            $settlements[] = [
                'from' => $debtorsArray[$i]['user'],
                'to' => $creditorsArray[$j]['user'],
                'amount' => $amount
            ];
            
            $debtorsArray[$i]['balance'] += $amount;
            $creditorsArray[$j]['balance'] -= $amount;
            
            if (abs($debtorsArray[$i]['balance']) < 0.01) $i++;
            if (abs($creditorsArray[$j]['balance']) < 0.01) $j++;
        }
        
        $months = [
            ['value' => '1', 'name' => 'January'],
            ['value' => '2', 'name' => 'February'],
            ['value' => '3', 'name' => 'March'],
            ['value' => '4', 'name' => 'April'],
            ['value' => '5', 'name' => 'May'],
            ['value' => '6', 'name' => 'June'],
            ['value' => '7', 'name' => 'July'],
            ['value' => '8', 'name' => 'August'],
            ['value' => '9', 'name' => 'September'],
            ['value' => '10', 'name' => 'October'],
            ['value' => '11', 'name' => 'November'],
            ['value' => '12', 'name' => 'December'],
        ];
        
        return view('colocations.show', compact('colocation', 'expenses', 'members', 'owedToMe', 'owedByMe', 'ownerId', 'categories', 'month', 'selectedMonth', 'months', 'colocationId', 'balances', 'settlements'));
    }
    
    public function leave()
    {
        $membership = auth()->user()->colocations()->wherePivot('left_at', null)->first();
        
        if (!$membership) {
            return redirect()->back()->with('error', 'You are not in a colocation.');
        }
        
        $colocationId = $membership->id;
        
        $ownerId = DB::table('colocation_user')
            ->where('colocation_id', $colocationId)
            ->where('role', 'owner')
            ->whereNull('left_at')
            ->value('user_id');
        
        if (!$ownerId) {
            return redirect()->back()->with('error', 'No owner found for this colocation.');
        }
        
        if (auth()->id() == $ownerId) {
            $memberCount = DB::table('colocation_user')
                ->where('colocation_id', $colocationId)
                ->whereNull('left_at')
                ->count();
            
            if ($memberCount > 1) {
                return redirect()->back()->with('error', 'Owner cannot leave while other members are present.');
            }
        }
        
        DB::transaction(function() use ($colocationId, $ownerId) {
            if (auth()->id() != $ownerId) {
                DB::table('expense_shares')
                    ->whereIn('expense_id', function($query) use ($colocationId) {
                        $query->select('id')
                            ->from('expenses')
                            ->where('colocation_id', $colocationId);
                    })
                    ->where('user_id', auth()->id())
                    ->where('is_paid', false)
                    ->update(['user_id' => $ownerId]);
            }
            
            DB::table('colocation_user')
                ->where('colocation_id', $colocationId)
                ->where('user_id', auth()->id())
                ->update(['left_at' => now()]);
            
            DB::table('users')
                ->where('id', auth()->id())
                ->decrement('reputation', 1);
        });
        
        return redirect()->route('welcome')->with('success', 'You have left the colocation.');
    }
}
