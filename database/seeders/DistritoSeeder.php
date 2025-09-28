<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Distrito;

class DistritoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $distritos = [];

        for ($i = 1; $i <= 24; $i++) {
            $distritos[] = [                
                'titulo' => "Distrito $i",
                'descripcion' => 'La Paz',
            ];
        }

        foreach ($distritos as $distrito) {
            Distrito::create($distrito);
        }
    }
}
