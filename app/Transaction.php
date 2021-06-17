<?php

namespace App;

use App\Enum\TransactionTypes;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $fillable = [
        'account_id',
        'value',
        'type',
    ];

    protected $appends = ['typeName'];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    function getTypeNameAttribute()
    {
        return TransactionTypes::TYPES[$this->type];
    }
}
