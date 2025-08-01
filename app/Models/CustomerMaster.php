<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMaster extends Model
{
    use HasFactory;

    protected $table = 'customer_masters';

    protected $fillable = ['name','address','mob_no','gst_no','comp_name'];
}
