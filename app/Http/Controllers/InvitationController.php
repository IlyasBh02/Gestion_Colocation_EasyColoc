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
            ]);
            
            $invitation->delete();
        });

        return redirect()->route('colocations.show')
            ->with('success', 'Welcome to your new colocation!');
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
        
        return response()->json(['message' => 'Invitation sent! Token: ' . $token]);
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
