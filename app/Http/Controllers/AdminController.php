<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        $totalUsers = User::count();
        $totalColocations = Colocation::count();
        $totalExpenses = Expense::sum('amount');
        $activeMemberships = DB::table('colocation_user')->whereNull('left_at')->count();
        
        return view('admin.users', compact('users', 'totalUsers', 'totalColocations', 'totalExpenses', 'activeMemberships'));
    }

    public function toggleBan($id)
    {
        $user = User::findOrFail($id);
        $user->is_banned = !$user->is_banned;
        $user->save();

        return redirect()->route('admin.users')->with('success', $user->is_banned ? 'User banned successfully' : 'User unbanned successfully');
    }
}
