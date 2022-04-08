<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageInvest extends Model
{
    use HasFactory;

    protected $table = 'package_invests';
    protected $fillable = [
        'name',
        'min_money',
        'max_money',
        'percent_interest_day',
        'day_limit',
    ];
}
