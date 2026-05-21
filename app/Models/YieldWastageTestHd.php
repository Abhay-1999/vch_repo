<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YieldWastageTestHd extends Model
{
    protected $table = 'yield_wastage_test_hd';

    protected $fillable = [
        'test_no',
        'test_date',
        'tested_by',
        'remarks'
    ];

    public function details()
    {
        return $this->hasMany(YieldWastageTestDt::class,'test_hd_id');
    }
}