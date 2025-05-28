<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
{
    return array_merge(parent::share($request), [
        'auth' => [
            'user' => $request->user() ? [
                'id' => $request->user()->id,
                'nombre' => $request->user()->nombre,
                'nombre_usuario' => $request->user()->nombre_usuario,
                'email' => $request->user()->email,
                'ciudad' => $request->user()->ciudad,
                'pais' => $request->user()->pais,
                'foto' => $request->user()->foto,
                'rol' => $request->user()->rol,
            ] : null,
        ],
        'flash' => [
            'message' => fn () => $request->session()->get('message'),
        ],
    ]);
}

}
