<?php

namespace App\Providers;

use App\Models\Ano_escolar;
use App\Models\User;
use App\Policies\Ano_escolarPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
   
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->createAdminUserIfNotExists();
        Gate::policy(Ano_escolar::class, Ano_escolarPolicy::class);
    }
    protected function createAdminUserIfNotExists()
    {
        if (User::where('admin', true)->count() === 0) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'apellido' => 'Admin',
                'cedula' => '123456987',
                'password' => bcrypt('password'), // Cambia esto por una contraseña más segura
                'admin' => true,
            ]);
            Log::info('Usuario administrador creado por defecto.');
        } else {
            Log::info('Ya existe un usuario administrador.');
        }
    }
}
