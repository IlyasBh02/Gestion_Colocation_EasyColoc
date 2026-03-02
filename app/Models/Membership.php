<?php

namespace App\Models;

use App\Models\ExpenseShare;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\DB;

class Membership extends Pivot
{
    protected $table = 'colocation_user';

    protected $fillable = [
        'role',
        'joined_at',
        'left_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
    ];

    public function TransferDebt(){
        $colocation_id = $this->colocation_id;
        $user_id = $this->user_id;

        $unpaidShares = ExpenseShare::where('user_id', $user_id)
                ->where('is_paid', false)
                ->whereHas('expense', function ($query) use ($colocation_id) {
                    $query->where('colocation_id', $colocation_id);
                });

        $total_Debt = $unpaidShares->sum('amount');

        if ($total_Debt > 0 ) {
            DB::transaction(function () use ($user_id, $colocation_id, $unpaidShares) {

                $owner_id = Colocation::where('id', $colocation_id)->value('owner_id');

                if ($owner_id) {
                    $unpaidShares->update([
                        'user_id' => $owner_id
                    ]);
                }
            });
        }
        return;
    }
}
