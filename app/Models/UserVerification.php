<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    use HasFactory;

    protected $table = 'user_verification';
    protected $fillable = [
        'id_user','name_user','phone','address','number_cmnd','image_selfie','image_cmnd',	
    ];
    protected $primaryKey = 'id_user';
}
