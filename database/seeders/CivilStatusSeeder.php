<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CivilStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('civil_statuses')->insert([
            [
                'civil_status' => 'Soltero',
                'created_at' => now(),
            ],
            [
                'civil_status' => 'Casado',
                'created_at' => now(),
            ],
            [
                'civil_status' => 'Unión Libre',
                'created_at' => now(),
            ],
            [
                'civil_status' => 'Divorciado',
                'created_at' => now(),
            ],
            [
                'civil_status' => 'Viudo',
                'created_at' => now(),
            ],
        ]);
    }
}
