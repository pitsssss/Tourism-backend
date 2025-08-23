<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingsPage extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'content_en', 'content_ar'];
}
