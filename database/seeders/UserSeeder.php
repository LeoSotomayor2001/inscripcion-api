<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Juan',
                'apellido' => 'Pérez',
                'email' => 'juan.perez@example.com',
                'password' => Hash::make('password123'), // Hash de la contraseña
                'admin' => 1, // 1 = true (es admin)
                'cedula' => '12345678',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maria',
                'apellido' => 'Gonzalez',
                'email' => 'maria.gonzalez@example.com',
                'password' => Hash::make('password123'),
                'admin' => 0, // 0 = false (no es admin)
                'cedula' => '87654321',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Carlos',
                'apellido' => 'Rodriguez',
                'email' => 'carlos.rodriguez@example.com',
                'password' => Hash::make('password123'),
                'admin' => 1, 
                'cedula' => '23456789',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
