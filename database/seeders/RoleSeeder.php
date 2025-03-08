<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'description' => 'Administrator mit vollen Rechten',
            ],
            [
                'name' => 'Fernmelder',
                'description' => 'Zuständig für die Kommunikation',
            ],
            [
                'name' => 'Einsatzleiter',
                'description' => 'Leitung des Einsatzes',
            ],
            [
                'name' => 'Disponent',
                'description' => 'Zuständig für die Disposition von Einsatzmitteln',
            ],
            [
                'name' => 'Abschnittleiter',
                'description' => 'Leitung eines Einsatzabschnitts',
            ],
            [
                'name' => 'S1',
                'description' => 'Stabsfunktion S1 - Personal',
            ],
            [
                'name' => 'S2',
                'description' => 'Stabsfunktion S2 - Lage',
            ],
            [
                'name' => 'S3',
                'description' => 'Stabsfunktion S3 - Einsatz',
            ],
            [
                'name' => 'S4',
                'description' => 'Stabsfunktion S4 - Logistik',
            ],
            [
                'name' => 'S5',
                'description' => 'Stabsfunktion S5 - Presse- und Öffentlichkeitsarbeit',
            ],
            [
                'name' => 'S6',
                'description' => 'Stabsfunktion S6 - IT und Kommunikation',
            ],
            [
                'name' => 'Sichter',
                'description' => 'Zuständig für die Sichtung von Patienten',
            ],
            [
                'name' => 'UHS',
                'description' => 'Unfallhilfsstelle',
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'name' => $role['name'],
                'description' => $role['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
