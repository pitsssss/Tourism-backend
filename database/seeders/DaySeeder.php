<?php

namespace Database\Seeders;

use App\Models\Day;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DaySeeder extends Seeder {
    /**
    * Run the database seeds.
    */

    public function run(): void {
        Day::create( [
            'name' => 'Monday',
            'TripDay'=>'Day 1',
            'tripable_id' => 1,
            'tripable_type' => 'App\Models\Trip',
            'date'=>Carbon::now()
        ] );
        Day::create( [
            'name' => 'tuesday',
            'TripDay'=>'Day 2',
            'tripable_id' => 1,
            'tripable_type' => 'App\Models\Trip',
            'date'=>Carbon::now()
        ] );
        Day::create( [
            'name' => 'wednesday',
            'TripDay'=>'Day 3',
            'tripable_id' => 1,
            'tripable_type' => 'App\Models\Trip',
            'date'=>Carbon::now()
        ] );
        Day::create( [
            'name' => 'thursday',
            'TripDay'=>'Day 4',
           'tripable_id' => 1,
            'tripable_type' => 'App\Models\Trip',
            'date'=>Carbon::now()
        ] );

        //
        //

    //     Day::create( [
    //         'name' => 'Saturday',
    //         'TripDay'=>'Day 1',
    //         'trip_id' =>1,
    //         'date'=>Carbon::now()
    //     ] );

    //     Day::create( [
    //         'name' => 'Sunday',
    //         'TripDay'=>'Day 2',
    //         'trip_id' =>1,
    //         'date'=>Carbon::now()
    //     ] );
    //     Day::create( [
    //         'name' => 'Monday',
    //         'TripDay'=>'Day 3',
    //         'trip_id' =>1,
    //         'date'=>Carbon::now()
    //     ] );
    //     Day::create( [
    //         'name' => 'tuesday',
    //         'TripDay'=>'Day 4',
    //         'trip_id' =>1,
    //         'date'=>Carbon::now()
    //     ] );
    //     Day::create( [
    //         'name' => 'wednesday',
    //         'TripDay'=>'Day 5',
    //         'trip_id' =>1,
    //         'date'=>Carbon::now()
    //     ] );
    //     Day::create( [
    //         'name' => 'thursday',
    //         'TripDay'=>'Day 6',
    //         'trip_id' =>1,
    //         'date'=>'2023/12/20'
    //     ] );
    //     Day::create( [
    //         'name' => 'friday',
    //         'TripDay'=>'Day 7',
    //         'trip_id' =>1,
    //         'date'=>'2023/12/20'
    //     ] );

     }
}
