<?php

namespace Database\Seeders;

use App\Models\Seccion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Seccion::insert([
            ['name' => 'Sección A', 'capacidad' => 30, 'year_id' => 1, 'ano_escolar_id' => 1], // Asignado al Primer Año
            ['name' => 'Sección B', 'capacidad' => 30, 'year_id' => 1, 'ano_escolar_id' => 1], // Asignado al Primer Año
            ['name' => 'Sección C', 'capacidad' => 30, 'year_id' => 1, 'ano_escolar_id' => 1], // Asignado al Primer Año
            ['name' => 'Sección D', 'capacidad' => 30, 'year_id' => 1, 'ano_escolar_id' => 1], // Asignado al Primer Año

            ['name' => 'Sección A', 'capacidad' => 25, 'year_id' => 2, 'ano_escolar_id' => 1], // Asignado al Segundo Año
            ['name' => 'Sección B', 'capacidad' => 25, 'year_id' => 2, 'ano_escolar_id' => 1], // Asignado al Segundo Año
            ['name' => 'Sección C', 'capacidad' => 25, 'year_id' => 2, 'ano_escolar_id' => 1], // Asignado al Segundo Año

            ['name' => 'Sección A', 'capacidad' => 20, 'year_id' => 3, 'ano_escolar_id' => 1], // Asignado al Tercer Año
            ['name' => 'Sección B', 'capacidad' => 20, 'year_id' => 3, 'ano_escolar_id' => 1], // Asignado al Tercer Año
            ['name' => 'Sección C', 'capacidad' => 20, 'year_id' => 3, 'ano_escolar_id' => 1], // Asignado al Tercer Año

            ['name' => 'Sección A', 'capacidad' => 15, 'year_id' => 4, 'ano_escolar_id' => 1], // Asignado al Cuarto Año
            ['name' => 'Sección B', 'capacidad' => 10, 'year_id' => 4, 'ano_escolar_id' => 1], // Asignado al Quinto Año

            ['name' => 'Sección A', 'capacidad' => 20, 'year_id' => 5, 'ano_escolar_id' => 1], // Asignado al Quinto Año
            ['name' => 'Sección B', 'capacidad' => 20, 'year_id' => 5, 'ano_escolar_id' => 1], // Asignado al Quinto Año
            ['name' => 'Sección C', 'capacidad' => 20, 'year_id' => 5, 'ano_escolar_id' => 1], // Asignado al Quinto Año

        ]);
    }
}
