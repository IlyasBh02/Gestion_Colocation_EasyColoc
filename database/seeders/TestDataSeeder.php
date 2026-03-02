<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Colocation;
use App\Models\Category;
use App\Models\Invitation;
use App\Models\Expense;
use App\Models\ExpenseShare;
use App\Models\Payment;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create 20 Users
        $users = [];
        for ($i = 1; $i <= 20; $i++) {
            $users[] = User::create([
                'name' => "User $i",
                'email' => "user$i@example.com",
                'password' => bcrypt('password'),
                'reputation' => rand(0, 100),
                'is_admin' => $i === 1,
                'is_banned' => false,
            ]);
        }

        // Create 10 Colocations
        $colocations = [];
        for ($i = 1; $i <= 10; $i++) {
            $colocations[] = Colocation::create([
                'name' => "Colocation $i",
                'status' => rand(0, 1) ? 'active' : 'cancelled',
                'owner_id' => $users[rand(0, 9)]->id,
            ]);
        }

        // Attach users to colocations (Memberships)
        foreach ($colocations as $colocation) {
            $memberCount = rand(2, 5);
            $selectedUsers = collect($users)->random($memberCount);
            
            foreach ($selectedUsers as $user) {
                $colocation->members()->attach($user->id, [
                    'role' => $user->id === $colocation->owner_id ? 'owner' : 'member',
                    'joined_at' => now()->subDays(rand(1, 365)),
                    'left_at' => rand(0, 10) > 8 ? now()->subDays(rand(1, 30)) : null,
                ]);
            }
        }

        // Create 30 Categories
        $categories = [];
        foreach ($colocations as $colocation) {
            for ($i = 1; $i <= 3; $i++) {
                $categories[] = Category::create([
                    'name' => ['Groceries', 'Rent', 'Utilities', 'Internet', 'Cleaning'][$i - 1],
                    'colocation_id' => $colocation->id,
                ]);
            }
        }

        // Create 50 Expenses
        $expenses = [];
        for ($i = 1; $i <= 50; $i++) {
            $colocation = $colocations[rand(0, 9)];
            $category = collect($categories)->where('colocation_id', $colocation->id)->random();
            
            $expenses[] = Expense::create([
                'title' => "Expense $i",
                'amount' => rand(10, 500),
                'date' => now()->subDays(rand(1, 90)),
                'payer_id' => $users[rand(0, 19)]->id,
                'colocation_id' => $colocation->id,
                'category_id' => $category->id,
            ]);
        }

        // Create 100 Expense Shares
        foreach ($expenses as $expense) {
            $shareCount = rand(2, 4);
            $shareAmount = $expense->amount / $shareCount;
            
            for ($i = 0; $i < $shareCount; $i++) {
                ExpenseShare::create([
                    'expense_id' => $expense->id,
                    'user_id' => $users[rand(0, 19)]->id,
                    'amount' => round($shareAmount, 2),
                    'is_paid' => rand(0, 1),
                ]);
            }
        }

        // Create 30 Payments
        for ($i = 1; $i <= 30; $i++) {
            $payer = $users[rand(0, 19)];
            $receiver = $users[rand(0, 19)];
            
            if ($payer->id !== $receiver->id) {
                Payment::create([
                    'payer_id' => $payer->id,
                    'receiver_id' => $receiver->id,
                    'amount' => rand(10, 200),
                ]);
            }
        }

        // Create 20 Invitations
        for ($i = 1; $i <= 20; $i++) {
            Invitation::create([
                'email' => "invite$i@example.com",
                'token' => Str::random(32),
                'status' => ['pending', 'accepted', 'refused'][rand(0, 2)],
                'colocation_id' => $colocations[rand(0, 9)]->id,
            ]);
        }
    }
}
