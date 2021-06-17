<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $fillable = [
        'account_id',
        'value',
        'type',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}

