<?php

use App\Http\Controllers\ColocationController;
use App\Http\Controllers\ExpenceController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->hasActiveMembership()) {
            return redirect()->route('colocations.show');
        }
    }
    return view('welcome');
})->name('welcome');

Route::get('/colocations/create', function () {
    if (!auth()->check()) {
        return redirect()->route('login')->with('intended', route('colocations.create.page'));
    }
    return view('colocations.create');
})->name('colocations.create.page');

Route::get('/colocations/join', function () {
    if (!auth()->check()) {
        return redirect()->route('login')->with('intended', route('colocations.join.page'));
    }
    return view('colocations.join');
})->name('colocations.join.page');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'check.banned'])->name('dashboard');


Route::get('/home', function () {
    $user = auth()->user();
    
    if ($user->is_admin) {
        return redirect()->route('admin.users');
    }
    
    if ($user->hasActiveMembership()) {
        return redirect()->route('colocations.show');
    }
    
    return redirect()->route('colocations.choice');
})->middleware(['auth', 'check.banned'])->name('home');

Route::middleware(['auth', 'check.banned'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/admin' ,function(){
    return view('admin.dashboard');
})->middleware(['auth','verified' , 'check.banned' , 'check.admin']);


Route::middleware(['auth', 'check.banned', 'check.admin'])->group(function () {
    Route::get('/admin/users', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/{id}/toggle-ban', [\App\Http\Controllers\AdminController::class, 'toggleBan'])->name('admin.toggleBan');
});


Route::get('/colocations/choice', function () {
    return view('welcome');
})->name('colocations.choice');


Route::get('/colocations/dashboard', [ColocationController::class, 'dashboard'])->name('colocations.show');


Route::get('/invitations/accept/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');

Route::post('/invitations/join', [InvitationController::class, 'accept'])->name('invitations.join.manual');

Route::middleware(['auth', 'check.banned'])->group(function () {
    Route::post('/colocations', [ColocationController::class, 'store'])->name('colocations.store');
    Route::get('/colocations/{id}/edit', [ColocationController::class, 'edit'])->name('colocations.edit');
    Route::put('/colocations/{id}', [ColocationController::class, 'update'])->name('colocations.update');
    Route::delete('/colocations/{id}', [ColocationController::class, 'destroy'])->name('colocations.destroy');
    Route::delete('/colocations/{colocationId}/members/{userId}', [ColocationController::class, 'removeMember'])->name('colocations.removeMember');
    Route::post('/colocations/leave', [ColocationController::class, 'leave'])->name('colocations.leave');
    Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');
    Route::get('/expenses/{id}', [ExpenceController::class, 'show'])->name('expenses.show');
    Route::post('/expenses', [ExpenceController::class, 'store'])->name('expenses.store');
    Route::delete('/expenses/{id}', [ExpenceController::class, 'destroy'])->name('expenses.destroy');
    Route::post('/expense-shares/{id}/pay', [ExpenceController::class, 'pay'])->name('expense-shares.pay');
});

require __DIR__.'/auth.php';
