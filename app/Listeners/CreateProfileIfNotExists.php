<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Profile;

class CreateProfileIfNotExists
{
    public function handle(Login $event)
    {
        $user = $event->user;

        if (!$user->profile) {
            Profile::create([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'date_of_birth' => null,
                'phoneNumber' => null,
                'address' => null,
                'image' => null,
            ]);
        }
    }
}
