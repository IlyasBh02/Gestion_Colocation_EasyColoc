<?php

namespace App\Http\Controllers;

use App\Mail\ColocationInvitation;
use App\Models\Colocation;
use App\Models\Invitation;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function accept(Request $request , $token = null)
    {
        $token = $request->token ?? $token;
        $invitation = Invitation::where('token' , $token)->firstOrFail();
        
        if (!auth()->check()) {
            return redirect()->route('login')->with('intended', route('invitations.accept', $token));
        }

        if (auth()->user()->hasActiveMembership()) {
            return redirect()->route('colocations.show')
            ->with('error', 'You are already a member of a colocation.'); 
        }

        DB::transaction(function () use ($invitation) {
            DB::table('colocation_user')->insert([
                'user_id' => auth()->id(),
                'colocation_id' => $invitation->colocation_id,
                'role' => 'member', 
                'joined_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Recalculate expense shares for existing unpaid expenses
            $expenses = \App\Models\Expense::where('colocation_id', $invitation->colocation_id)->get();
            
            foreach ($expenses as $expense) {
                // Get current active members count (including new member)
                $memberCount = DB::table('colocation_user')
                    ->where('colocation_id', $invitation->colocation_id)
                    ->whereNull('left_at')
                    ->count();
                
                // Delete existing shares for this expense
                \App\Models\ExpenseShare::where('expense_id', $expense->id)->delete();
                
                // Get all active members
                $members = \App\Models\User::whereHas('colocations', function($q) use ($invitation) {
                    $q->where('colocations.id', $invitation->colocation_id)
                      ->whereNull('colocation_user.left_at');
                })->get();
                
                // Recalculate share amount
                $shareAmount = $expense->amount / $memberCount;
                
                // Create new shares for all members
                foreach ($members as $member) {
                    \App\Models\ExpenseShare::create([
                        'expense_id' => $expense->id,
                        'user_id' => $member->id,
                        'amount' => round($shareAmount, 2),
                        'is_paid' => $member->id === $expense->payer_id,
                    ]);
                }
            }
            
            $invitation->update(['status' => 'accepted']);
        });

        return redirect()->route('colocations.show')
            ->with('success', 'Welcome to your new colocation!');
    }
    
    public function refuse($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();
        
        $invitation->update(['status' => 'refused']);
        
        return redirect()->route('welcome')->with('success', 'Invitation refused.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $colocationId = DB::table('colocation_user')
            ->where('user_id', $user->id)
            ->where('role', 'owner')
            ->whereNull('left_at')
            ->value('colocation_id');
        
        if (!$colocationId) {
            return response()->json(['error' => 'Only the owner can send invitations.'], 403);
        }
        
        $request->validate([
            'email' => 'required|email'
        ]);

        $colocation = Colocation::findOrFail($colocationId);
        $token = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
            
        Invitation::create([
            'email' => $request->email,
            'token' => $token,
            'colocation_id' => $colocationId
        ]);

        Mail::to($request->email)->send(new ColocationInvitation($token, $colocation->name, $user->name));
        
        return redirect()->back()->with('success', 'Invitation sent successfully! Token: ' . $token);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }
}
