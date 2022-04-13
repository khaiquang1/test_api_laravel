<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Money extends Model
{
    use HasFactory;
    protected $table = 'money';
    protected $fillable = [
        'id_user',
        'amount_money',
        'currency_id',
        'rate',
        'detail',
        'action_type',
        'fee',
        'wallet_address',
        'bank_id',
        'name_user_bank',
        'number_bank',
        'note',
        'hash',
        'status',
        'id_user_to',
    ];

    public function money_bank(){
        return $this->hasMany('App\Models\Bank','bank_id','id');
    }

}
