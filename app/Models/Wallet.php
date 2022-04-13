<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $table = 'wallets';
    protected $fillable = [
        'wallet_address',
        'amount',
        'id_user',
        'type_money',
        'invest_money',
        'status',	
        'invest_money',
    ];
}
