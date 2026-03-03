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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'check.banned'])->name('dashboard');

Route::get('/home', function () {
    $user = auth()->user();
    
    if ($user->role === 'admin') {
        return redirect()->route('admin.users');
    }
    
    if ($user->hasActiveMembership()) {
        return redirect()->route('colocations.show');
    }
    
    return redirect()->route('welcome');
})->middleware(['auth', 'check.banned'])->name('home');

Route::middleware(['auth', 'check.banned'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'check.banned', 'check.admin'])->group(function () {
    Route::get('/admin', function(){ return view('admin.dashboard'); })->name('admin.dashboard');
    Route::get('/admin/users', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/{id}/toggle-ban', [\App\Http\Controllers\AdminController::class, 'toggleBan'])->name('admin.toggleBan');
});

Route::middleware(['auth', 'check.banned'])->group(function () {
    Route::get('/colocations', [ColocationController::class, 'index'])->name('colocations.index');
    Route::get('/colocations/create', [ColocationController::class, 'create'])->name('colocations.create');
    Route::post('/colocations', [ColocationController::class, 'store'])->name('colocations.store');
    Route::get('/colocations/dashboard', [ColocationController::class, 'dashboard'])->name('colocations.show');
    Route::get('/colocations/{id}', [ColocationController::class, 'show'])->name('colocations.details');
    Route::get('/colocations/{id}/edit', [ColocationController::class, 'edit'])->name('colocations.edit');
    Route::match(['put', 'patch'], '/colocations/{id}', [ColocationController::class, 'update'])->name('colocations.update');
    Route::delete('/colocations/{id}', [ColocationController::class, 'destroy'])->name('colocations.destroy');
    Route::delete('/colocations/{colocationId}/members/{userId}', [ColocationController::class, 'removeMember'])->name('colocations.removeMember');
    Route::post('/colocations/leave', [ColocationController::class, 'leave'])->name('colocations.leave');
});

Route::get('/invitations/accept/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');
Route::get('/invitations/refuse/{token}', [InvitationController::class, 'refuse'])->name('invitations.refuse');
Route::post('/invitations/join', [InvitationController::class, 'accept'])->name('invitations.join.manual');

Route::middleware(['auth', 'check.banned'])->group(function () {
    Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');
    Route::get('/expenses/{id}', [ExpenceController::class, 'show'])->name('expenses.show');
    Route::post('/expenses', [ExpenceController::class, 'store'])->name('expenses.store');
    Route::delete('/expenses/{id}', [ExpenceController::class, 'destroy'])->name('expenses.destroy');
    Route::post('/expense-shares/{id}/pay', [ExpenceController::class, 'pay'])->name('expense-shares.pay');
});

require __DIR__.'/auth.php';
