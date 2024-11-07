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
        $civil_statuses = [
            "Soltero",
            "Casado",
            "Unión Libre",
            "Divorciado",
            "Viudo",
        ];

        $data = array_map(function ($civil_status) {
            return [
                'civil_status' => $civil_status,
                'created_at' => now(),
            ];
        }, $civil_statuses);

        DB::table('civil_statuses')->insert($data);
    }
}