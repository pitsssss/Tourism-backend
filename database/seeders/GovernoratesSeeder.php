<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GovernoratesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run()
    {
        DB::table('governorates')->insert([
            ['name' => 'دمشق'],
            ['name' => 'ريف دمشق'],
            ['name' => 'حلب'],
            ['name' => 'اللاذقية'],
            ['name' => 'طرطوس'],
            ['name' => 'حمص'],
            ['name' => 'حماة'],
            ['name' => 'إدلب'],
            ['name' => 'درعا'],
            ['name' => 'السويداء'],
            ['name' => 'دير الزور'],
            ['name' => 'الرقة'],
            ['name' => 'الحسكة'],
        ]);
    }
}
