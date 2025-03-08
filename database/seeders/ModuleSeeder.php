<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'name' => 'Dashboard',
                'description' => 'Übersicht aller Module und Aktivitäten',
                'version' => '1.0.0',
                'icon' => 'fa-tachometer-alt',
                'route' => 'dashboard',
                'active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Benutzerverwaltung',
                'description' => 'Verwaltung von Benutzern und Rollen',
                'version' => '1.0.0',
                'icon' => 'fa-users',
                'route' => 'users',
                'active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Kommunikation',
                'description' => 'Kommunikationssystem (ähnlich des 4fach Vordruck)',
                'version' => '1.0.0',
                'icon' => 'fa-comments',
                'route' => 'communication',
                'active' => false,
                'order' => 3,
            ],
            [
                'name' => 'Lagekarte',
                'description' => 'Lagekartendarstellung nach DV102',
                'version' => '1.0.0',
                'icon' => 'fa-map',
                'route' => 'map',
                'active' => false,
                'order' => 4,
            ],
            [
                'name' => 'Einsatzübersicht',
                'description' => 'Einsatz- und Einsatzmittelübersicht',
                'version' => '1.0.0',
                'icon' => 'fa-tasks',
                'route' => 'operations',
                'active' => false,
                'order' => 5,
            ],
            [
                'name' => 'Einsatztagebuch',
                'description' => 'Dokumentation von Entscheidungen und Maßnahmen',
                'version' => '1.0.0',
                'icon' => 'fa-book',
                'route' => 'journal',
                'active' => false,
                'order' => 6,
            ],
        ];

        foreach ($modules as $module) {
            DB::table('modules')->insert([
                'name' => $module['name'],
                'description' => $module['description'],
                'version' => $module['version'],
                'icon' => $module['icon'],
                'route' => $module['route'],
                'active' => $module['active'],
                'order' => $module['order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
