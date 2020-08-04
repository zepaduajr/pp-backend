<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'payer_id', 'payee_id', 'value'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function userPayer()
    {
        return $this->belongsTo(User::class, 'id', 'payer_id');
    }

    public function userPayee()
    {
        return $this->belongsTo(User::class, 'id', 'payee_id');
    }
}
