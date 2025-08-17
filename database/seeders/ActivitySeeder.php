<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\activity_images;
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
            'governorate_id' => 1,
            
        ]);
        Activity::create([
            'name' => 'Climbing',
            'start_time' => '07:30:00',
            'end_time' => '09:00:00',
            'description' => 'climbing the hugest mountain in the area',
            'image' => 'climbing.jpg',
            'governorate_id' => 1,
            
        ]);
         Activity::create([
            'name' => 'Camel Riding',
            'start_time' => '07:30:00',
            'end_time' => '09:00:00',
            'description' => 'Experience a desert camel ride.',
            'image' => 'camel.jpg',
            'governorate_id' => 1,
            
        ]);
   
   
   $activity = Activity::create([
            'name' => 'Horseback Riding',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'description' => 'Ride horses along scenic mountain trails.',
            'image' => 'imgs\activity.img\hv_image001_2014817.jpg',
            
            'governorate_id' => 1,
        ]);

        
        activity_images::insert([
            [
                'activity_id' => $activity->id,
                'image_path' => 'imgs\activity.img\images.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_id' => $activity->id,
                'image_path' => 'imgs\activity.img\تنزيل.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
   
   
    }

