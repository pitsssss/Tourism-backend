<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';

    protected $casts = [
		'user_id' => 'int',
		'date_of_birth' => 'datetime'
	];

	protected $fillable = [
		'user_id',
        'user_name',
        'user_email',
		'phoneNumber',
		'address',
		'date_of_birth',
		'image'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
