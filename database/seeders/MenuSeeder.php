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
        $menuCatalogs = array("id" => 12, "path" => "catalogos");
        $menuCiudadanos = array("id" => 16, "path" => "ciudadanos");
        $menuSitutations = array("id" => 19, "path" => "casos");

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
            'icon' => 'IconFileDollar',
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
            'icon' => 'IconDashboard',
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
        DB::table('menus')->insert([ #7 Departamentos
            'menu' => 'Departamentos',
            'type' => 'item',
            'belongs_to' => $menuSettings["id"],
            'url' => "/app/$menuSettings[path]/departamentos",
            'icon' => 'IconBuildingSkyscraper',
            'order' => $order,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #8 Puestos de trabajo
            'menu' => 'Puestos de trabajo',
            'type' => 'item',
            'belongs_to' => $menuSettings["id"],
            'url' => "/app/$menuSettings[path]/puestos",
            'icon' => 'IconBuildingSkyscraper',
            'order' => $order,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #9 Empleados
            'menu' => 'Empleados',
            'type' => 'item',
            'belongs_to' => $menuSettings["id"],
            'url' => "/app/$menuSettings[path]/empleados",
            'icon' => 'IconBuildingSkyscraper',
            'order' => $order,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #10 Usuarios
            'menu' => 'Usuarios',
            'type' => 'item',
            'belongs_to' => $menuSettings["id"],
            'url' => "/app/$menuSettings[path]/usuarios",
            'icon' => 'IconUsers',
            'order' => $order,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #11 Ajustes
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
        DB::table('menus')->insert([ #12
            'menu' => 'Catalogos',
            'caption' => 'Gestión de Catalogos',
            'type' => 'group',
            'belongs_to' => 0,
            'order' => 3,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #13 Categorias
            'menu' => 'Categorias',
            'type' => 'item',
            'belongs_to' => $menuCatalogs["id"],
            'url' => "/app/$menuCatalogs[path]/categorias",
            'icon' => 'IconBuildingSkyscraper',
            'order' => $order,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #14 Subcategorias
            'menu' => 'Subcategorias',
            'type' => 'item',
            'belongs_to' => $menuCatalogs["id"],
            'url' => "/app/$menuCatalogs[path]/subcategorias",
            'icon' => 'IconBuildingSkyscraper',
            'order' => $order,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #15 Estados Civiles
            'menu' => 'Estados Civiles',
            'type' => 'item',
            'belongs_to' => $menuCatalogs["id"],
            'url' => "/app/$menuCatalogs[path]/estados-civiles",
            'icon' => 'IconBuildingSkyscraper',
            'order' => $order,
            'created_at' => now(),
        ]);

        // Ciudadanos
        $order = 0;
        DB::table('menus')->insert([ #16
            'menu' => 'Ciudadanos',
            'caption' => 'Gestión de Ciudadanos',
            'type' => 'group',
            'belongs_to' => 0,
            'order' => 4,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #17 Ciudadanos
            'menu' => 'Listado',
            'type' => 'item',
            'belongs_to' => $menuCiudadanos["id"],
            'url' => "/app/$menuCiudadanos[path]",
            'icon' => 'IconBuildingSkyscraper',
            'order' => $order,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #18 Ciudadanos
            'menu' => 'Historial',
            'type' => 'item',
            'belongs_to' => $menuCiudadanos["id"],
            'url' => "/app/$menuCiudadanos[path]/historial",
            'icon' => 'IconBuildingSkyscraper',
            'order' => $order,
            'created_at' => now(),
        ]);

        // Casos
        $order = 0;
        DB::table('menus')->insert([ #19
            'menu' => 'Casos',
            'caption' => 'Registros de casos',
            'type' => 'group',
            'belongs_to' => 0,
            'order' => 5,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #20 Casos
            'menu' => 'Listado',
            'type' => 'item',
            'belongs_to' => $menuSitutations["id"],
            'url' => "/app/$menuSitutations[path]",
            'icon' => 'IconBuildingSkyscraper',
            'order' => $order,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #21 Abiertos
            'menu' => 'Abiertos',
            'type' => 'item',
            'belongs_to' => $menuSitutations["id"],
            'url' => "/app/$menuSitutations[path]/abiertos",
            'icon' => 'IconBuildingSkyscraper',
            'order' => $order,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #22 En Seguimiento
            'menu' => 'En Seguimiento',
            'type' => 'item',
            'belongs_to' => $menuSitutations["id"],
            'url' => "/app/$menuSitutations[path]/en-seguimiento",
            'icon' => 'IconBuildingSkyscraper',
            'order' => $order,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #23 Cerrados
            'menu' => 'Cerrados',
            'type' => 'item',
            'belongs_to' => $menuSitutations["id"],
            'url' => "/app/$menuSitutations[path]/cerrados",
            'icon' => 'IconBuildingSkyscraper',
            'order' => $order,
            'created_at' => now(),
        ]);
        $order += 1;
        DB::table('menus')->insert([ #24 Cancelados
            'menu' => 'Cancelados',
            'type' => 'item',
            'belongs_to' => $menuSitutations["id"],
            'url' => "/app/$menuSitutations[path]/cancelados",
            'icon' => 'IconBuildingSkyscraper',
            'order' => $order,
            'created_at' => now(),
        ]);
    }
}
