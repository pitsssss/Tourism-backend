<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Activity::create([
            'name' => 'Swimming',
            'start_time' => '07:30:00',
            'end_time' => '09:00:00',
            'description' => 'Swimming in the oldest and biggest sea int he world',
            'image' => 'swimming.jpg',
            'day_id'=>1
        ]);
        Activity::create([
            'name' => 'Climbing',
            'start_time' => '07:30:00',
            'end_time' => '09:00:00',
            'description' => 'climbing the hugest mountain in the area',
            'image' => 'climbing.jpg',
            'day_id'=>2
        ]);
         Activity::create([
            'name' => 'Camel Riding',
            'start_time' => '07:30:00',
            'end_time' => '09:00:00',
            'description' => 'Experience a desert camel ride.',
            'image' => 'camel.jpg',
            'day_id'=>3
        ]);
    }
}
