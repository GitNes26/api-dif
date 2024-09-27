<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'role' => 'SuperAdmin',
                'description' => 'Rol dedicado para la completa configuracion del sistema desde el area de desarrollo.',
                'read' => 'todas',
                'create' => 'todas',
                'update' => 'todas',
                'delete' => 'todas',
                'more_permissions' => 'todas',
                'page_index' => '/app',
                'created_at' => now(),
            ],
            [
                'role' => 'Administrador',
                'description' => 'Rol dedicado para usuarios que gestionaran el sistema.',
                'read' => 'todas',
                'create' => 'todas',
                'update' => 'todas',
                'delete' => 'todas',
                'more_permissions' => 'todas',
                'page_index' => '/app',
                'created_at' => now(),
            ],
            [
                'role' => 'Ciudadano',
                'description' => 'Rol dedicado para el ciudadano.',
                'read' => '',
                'create' => '',
                'update' => '',
                'delete' => '',
                'more_permissions' => '',
                'page_index' => '/app',
                'created_at' => now(),
            ]
        ]);
    }
}