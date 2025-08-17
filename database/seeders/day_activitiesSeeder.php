<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\day_activity;
class day_activitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         day_activity::create( [
            'day_id'=> 1,
            'activity_id' => 1,
            
        ] );
    }
}
