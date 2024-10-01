<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menuTable = array("id" => 1, "path" => "/");
        $menuSettings = array("id" => 4, "path" => "configuraciones");
        $menuCatalogs = array("id" => 10, "path" => "catalogos");

        // DASHBOARD
        $order = 0;
        DB::table('menus')->insert([ #1 Tablero
            'menu' => 'Principal',
            'caption' => '',
            'type' => 'group',
            'belongs_to' => 0,
            'order' => 1,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #2 Dashboard Administrativo
            'menu' => 'Tablero',
            'caption' => '',
            'type' => 'item',
            'belongs_to' => $menuTable["id"],
            'url' => "/app",
            'icon' => 'IconDashboard',
            'order' => $order,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #3 Tablero para el ciudadano
            'menu' => 'Noticias',
            'caption' => '',
            'type' => 'item',
            'belongs_to' => $menuTable["id"],
            'url' => "/app/noticias",
            'icon' => 'IconFileDollar',
            'order' => $order,
            'created_at' => now(),
        ]);

        // CONFIGURACIONES
        $order = 0;
        DB::table('menus')->insert([ #4
            'menu' => 'Configuraciones',
            'caption' => 'Control del sistema, usuarios y roles',
            'type' => 'group',
            'belongs_to' => 0,
            'order' => 2,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #5 Menus
            'menu' => 'Menus',
            'type' => 'item',
            'belongs_to' => $menuSettings["id"],
            'url' => "/app/$menuSettings[path]/menus",
            'icon' => 'IconCategory2',
            'order' => $order,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #6 Roles
            'menu' => 'Roles y Permisos',
            'type' => 'item',
            'belongs_to' => $menuSettings["id"],
            'url' => "/app/$menuSettings[path]/roles-y-permisos",
            'icon' => 'IconPaperBag',
            'others_permissions' => "Asignar Permisos",
            'order' => $order,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #7 Usuarios
            'menu' => 'Usuarios',
            'type' => 'item',
            'belongs_to' => $menuSettings["id"],
            'url' => "/app/$menuSettings[path]/usuarios",
            'icon' => 'IconUsers',
            'order' => $order,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #8 Respuestas y puntajes
            'menu' => 'Respuestas y Puntajes',
            'type' => 'item',
            'belongs_to' => $menuSettings["id"],
            'url' => "/app/$menuSettings[path]/respuestas-y-puntajes",
            'icon' => 'IconAbacus',
            'order' => $order,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #9 Ajustes
            'menu' => 'Ajustes',
            'type' => 'item',
            'belongs_to' => $menuSettings["id"],
            'url' => "/app/$menuSettings[path]/ajustes",
            'icon' => 'IconAdjustmentsAlt',
            'order' => $order,
            'created_at' => now(),
        ]);

        // Catalogos
        $order = 0;
        DB::table('menus')->insert([ #10
            'menu' => 'Catalogos',
            'caption' => 'Gestión de Catalogos',
            'type' => 'group',
            'belongs_to' => 0,
            'order' => 3,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #11 Departamentos
            'menu' => 'Departamentos',
            'type' => 'item',
            'belongs_to' => $menuCatalogs["id"],
            'url' => "/app/$menuCatalogs[path]/departamentos",
            'icon' => 'IconBuildingSkyscraper',
            'order' => $order,
            'created_at' => now(),
        ]);
    }
}