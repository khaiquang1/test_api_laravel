<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestMulti extends Model
{
    use HasFactory;

    protected $table = 'test_multi';

    protected $fillable = [
        'title',
        'parent_id',
        'desc_test',
        'status',	
    ];

    public function scopeRoot($query){
        $query->whereNull('parent_id');
    }

    public function children(){
        return $this->hasMany(TestMulti::class,'parent_id', 'id');
    }
}
