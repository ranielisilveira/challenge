<?php

namespace App;

use App\Enum\AccountTypes;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'agency',
        'number',
        'digit',
        'corporate_name',
        'trade_name',
        'corporate_document',
        'user_id',
        'type',
    ];

    protected $appends = ['typeName'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'account_id');
    }

    function getTypeNameAttribute()
    {
        return AccountTypes::TYPES[$this->type];
    }
}
