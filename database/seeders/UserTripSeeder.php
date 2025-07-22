<?php

namespace Database\Seeders;
use App\Models\user_trips;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
class UserTripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
//     public function run(): void
//     {
     
//         DB::table('user_trips')->truncate(); // إذا حابة تبدأ من الصفر
//  $now = Carbon::now();
//         // رحلة ما بلشت (upcoming)
//         user_trips::create([
//             'user_id' => 1,
//             'trip_id' => 1,
//             'type' => 'ready',
//             'governorate_id' => 1,
//             'status' => 'upcoming',
//             'created_at' => $now,
//             'updated_at' => $now,
//         ]);

//         // رحلة جارية (in_progress)
//         user_trips::create([
//             'user_id' => 1,
//             'trip_id' => 2,
//             'type' => 'ready',
//             'governorate_id' => 2,
//             'status' => 'in_progress',
//             'created_at' => $now,
//             'updated_at' => $now,
//         ]);

//         // رحلة منتهية (finished)
//         user_trips::create([
//             'user_id' => 1,
//             'trip_id' => 3,
//             'type' => 'custom',
//             'governorate_id' => 3,
//             'status' => 'finished',
//             'created_at' => $now,
//             'updated_at' => $now,
//         ]);
    
//     }
}
