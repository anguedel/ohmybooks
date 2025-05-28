<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        //
    }


    
    public function boot()
    {
        Inertia::share('auth.user', function () {
            $user = Auth::user();
    
            return $user ? [
                'id' => $user->id,
                'nombre' => $user->nombre, // 👈 Asegúrate de incluir esta línea
                'nombre_usuario' => $user->nombre_usuario,
                'email' => $user->email,
                'ciudad' => $user->ciudad,
                'pais' => $user->pais,
                'foto' => $user->foto,
            ] : null;
        });
    }
    


}
