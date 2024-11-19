<?php

namespace Database\Seeders;

use App\Models\Mesa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MesasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 4; $i++) {
            $j = 0;
            Mesa::factory()->create([
                'espacios' => 4,
                'nombre' => $i.$j++
            ]);
            Mesa::factory()->create([
                'espacios' => 3,
                'nombre' => $i.$j++
            ]);
            Mesa::factory()->create([
                'espacios' => 2,
                'nombre' => $i.$j++
            ]);
            Mesa::factory()->create([
                'espacios' => 1,
                'nombre' => $i.$j++
            ]);
        }
    }
}
