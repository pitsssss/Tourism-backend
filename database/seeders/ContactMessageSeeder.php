<?php

use Illuminate\Database\Seeder;
use App\Models\ContactMessage;

class ContactMessageSeeder extends Seeder
{
    public function run()
    {
        ContactMessage::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'I want to know more about booking in Palmyra.'
        ]);
    }
}

