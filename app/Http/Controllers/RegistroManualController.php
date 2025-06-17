<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class RegistroManualController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nombre_usuario' => 'required|string|max:255|unique:usuarios',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:8',
            'ciudad' => 'nullable|string|max:255',
            'pais' => 'nullable|string|max:255',
            'foto' => 'nullable|image|max:2048',
        ]);

        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('fotos', 'public');
        }

        Usuario::create([
            'nombre' => $request->nombre,
            'nombre_usuario' => $request->nombre_usuario,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'ciudad' => $request->ciudad,
            'pais' => $request->pais,
            'foto' => $foto,
            'rol' => 'usuario',
            'fecha_alta' => now(), // o el que uses por defecto
        ]);

        return ;
    }
}
