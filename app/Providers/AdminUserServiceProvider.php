<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AdminUserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }
    
    protected function createAdminUserIfNotExists()
    {
        if (User::where('admin', true)->count() === 0) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'), // Cambia esto por una contraseña más segura
                'admin' => true,
            ]);
            Log::info('Usuario administrador creado por defecto.');
        } else {
            Log::info('Ya existe un usuario administrador.');
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->createAdminUserIfNotExists();
    }

}
