<?php

namespace Database\Seeders;

use App\Models\Year;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Year::insert([
            ['year' => 1, 'descripcion' => 'Primer Año'],
            ['year' => 2, 'descripcion' => 'Segundo Año'],
            ['year' => 3, 'descripcion' => 'Tercer Año'],
            ['year' => 4, 'descripcion' => 'Cuarto Año'],
            ['year' => 5, 'descripcion' => 'Quinto Año'],
        ]);
    }
}
