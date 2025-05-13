<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PasswordResetCode extends Model
{

    protected $fillable = ['email', 'code', 'expires_at'];
    protected $dates = ['expires_at'];


}
