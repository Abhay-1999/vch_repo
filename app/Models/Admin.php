<?php

// app/Models/Admin.php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Admin extends Model implements Authenticatable
{
    use HasFactory, AuthenticatableTrait;

    protected $fillable = ['name','userid','mob_no', 'email', 'password'];

    // You can add any additional methods or properties here
}
