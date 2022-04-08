<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyTransfer extends Model
{
    use HasFactory;

    protected $table = 'money_transfers';
    protected $fillable = [
        'id_user',
        'id_user_to',
        'amount_money',
        'note',
        'status',
    ];
}
