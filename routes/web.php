<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\ColocationController;

Route::middleware(['auth', 'banned'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('colocations', ColocationController::class);
    
    // Invitations
    Route::post('/colocations/{colocation}/invitations', [\App\Http\Controllers\InvitationController::class, 'store'])->name('invitations.store');
    Route::get('/invitations/accept/{token}', [\App\Http\Controllers\InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/colocations/{colocation}/leave', [\App\Http\Controllers\InvitationController::class, 'leave'])->name('colocations.leave');
    Route::delete('/colocations/{colocation}/members/{user}', [\App\Http\Controllers\InvitationController::class, 'removeMember'])->name('colocations.removeMember');

    // Expenses
    Route::post('/colocations/{colocation}/expenses', [\App\Http\Controllers\ExpenseController::class, 'store'])->name('expenses.store');
    Route::put('/colocations/{colocation}/expenses/{expense}', [\App\Http\Controllers\ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/colocations/{colocation}/expenses/{expense}', [\App\Http\Controllers\ExpenseController::class, 'destroy'])->name('expenses.destroy');
});

require __DIR__.'/auth.php';
