<?php

namespace App\Services;

use App\Models\Colocation;

class BalanceService
{
    public function calculateBalances(Colocation $colocation)
    {
        // Get all active members (owner + members)
        $allMembers = collect([$colocation->owner])->merge($colocation->members);
        $memberCount = $allMembers->count();

        if ($memberCount === 0) {
            return [];
        }

        // Get all expenses for this colocation
        $expenses = $colocation->expenses;
        $totalExpenses = $expenses->sum('amount');
        $sharePerPerson = $totalExpenses / $memberCount;

        // Calculate balance for each member
        $balances = [];
        foreach ($allMembers as $member) {
            $totalPaid = $expenses->where('payer_id', $member->id)->sum('amount');
            $balance = $totalPaid - $sharePerPerson;
            
            $balances[] = [
                'user' => $member,
                'total_paid' => $totalPaid,
                'share' => $sharePerPerson,
                'balance' => $balance,
            ];
        }

        return collect($balances)->sortByDesc('balance')->values()->all();
    }

    public function calculateSettlements(Colocation $colocation)
    {
        $balances = collect($this->calculateBalances($colocation));
        
        // Separate creditors (positive balance) and debtors (negative balance)
        $creditors = $balances->filter(fn($b) => $b['balance'] > 0.01)->sortByDesc('balance')->values();
        $debtors = $balances->filter(fn($b) => $b['balance'] < -0.01)->sortBy('balance')->values();

        $settlements = [];

        $i = 0;
        $j = 0;

        while ($i < $creditors->count() && $j < $debtors->count()) {
            $creditor = $creditors[$i];
            $debtor = $debtors[$j];

            $amount = min($creditor['balance'], abs($debtor['balance']));

            if ($amount > 0.01) {
                $settlements[] = [
                    'from' => $debtor['user'],
                    'to' => $creditor['user'],
                    'amount' => round($amount, 2),
                ];
            }

            $creditors[$i]['balance'] -= $amount;
            $debtors[$j]['balance'] += $amount;

            if (abs($creditors[$i]['balance']) < 0.01) {
                $i++;
            }
            if (abs($debtors[$j]['balance']) < 0.01) {
                $j++;
            }
        }

        return $settlements;
    }
}
