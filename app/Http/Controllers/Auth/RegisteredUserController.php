<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario; // Asegúrate de que este sea tu modelo real
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\File;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Mostrar la vista de registro (si la usas).
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Registrar al nuevo usuario.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nombre_usuario' => 'required|string|max:255|unique:usuarios,nombre_usuario',
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'password' => 'required|string|min:6',
            'ciudad' => 'nullable|string|max:100',
            'pais' => 'nullable|string|max:100',
            'foto' => [
                'nullable',
                File::image()->max(2048)
            ],
        ]);

        $rutaFoto = null;

        if ($request->hasFile('foto')) {
            $rutaFoto = $request->file('foto')->store('usuarios', 'public');
        }

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'nombre_usuario' => $request->nombre_usuario,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'ciudad' => $request->ciudad,
            'pais' => $request->pais,
            'foto' => $rutaFoto,
            'rol' => 'Usuario',
            'fecha_alta' => now(),
        ]);

        Auth::login($usuario);

        return redirect()->intended('/');
    }
}
