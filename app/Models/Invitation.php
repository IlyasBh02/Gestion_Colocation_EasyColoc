<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'colocation_id',
        'email',
        'token',
        'status',
    ];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public static function generateUniqueToken()
    {
        do {
            $token = \Illuminate\Support\Str::random(32);
        } while (self::where('token', $token)->exists());

        return $token;
    }
}
