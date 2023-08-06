<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShipDistrictsSeeder extends Seeder
{
    
    public function run(): void
    {
        $stateId = 37;
        $district_data = [
'Karaikal',
'Mahe',
'Puducherry',
'Yanam'

        ];


        foreach ($district_data as $district) {
            DB::table('ship_districts')->insert([
                'state_id' => $stateId,
                'districts_name' => $district,
            ]);
        }
    }
}
