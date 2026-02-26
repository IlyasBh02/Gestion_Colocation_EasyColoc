<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Colocation;
use App\Models\Invitation;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    public function store(Request $request, Colocation $colocation)
    {
        $this->authorize('update', $colocation);

        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        // Check if user is already a member
        $user = \App\Models\User::where('email', $validated['email'])->first();
        if ($user && ($colocation->members->contains($user) || $colocation->owner_id === $user->id)) {
            return back()->with('error', 'This user is already a member or the owner.');
        }

        // Check if there is already a pending invitation
        if ($colocation->invitations()->where('email', $validated['email'])->where('status', 'pending')->exists()) {
            return back()->with('error', 'An invitation is already pending for this email.');
        }

        $invitation = $colocation->invitations()->create([
            'email' => $validated['email'],
            'token' => Invitation::generateUniqueToken(),
            'status' => 'pending',
        ]);

        // In a real app, you would send an email here
        // Mail::to($validated['email'])->send(new InvitationMail($invitation));

        return back()->with('success', 'Invitation sent successfully. Token: ' . $invitation->token);
    }

    public function accept($token)
    {
        $invitation = Invitation::where('token', $token)->where('status', 'pending')->firstOrFail();
        $colocation = $invitation->colocation;
        $user = Auth::user();

        if ($user->email !== $invitation->email) {
            return redirect()->route('dashboard')->with('error', 'This invitation was sent to another email address.');
        }

        // Add user to members
        $colocation->members()->attach($user->id);

        // Update invitation status
        $invitation->update(['status' => 'accepted']);

        return redirect()->route('colocations.show', $colocation)->with('success', 'You have joined the colocation!');
    }
}
