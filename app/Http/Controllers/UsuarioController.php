<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use App\Models\Usuario;
use App\Models\Genero;
use App\Models\Escrito;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $usuario = Auth::user();

    // 📊 Valoraciones agrupadas por nota
    $valoraciones = $usuario->valoraciones()
        ->selectRaw('
            COUNT(CASE WHEN puntuacion >= 8 THEN 1 END) as excelente,
            COUNT(CASE WHEN puntuacion BETWEEN 6 AND 7 THEN 1 END) as muy_bueno,
            COUNT(CASE WHEN puntuacion = 5 THEN 1 END) as regular,
            COUNT(CASE WHEN puntuacion BETWEEN 3 AND 4 THEN 1 END) as flojo,
            COUNT(CASE WHEN puntuacion BETWEEN 0 AND 2 THEN 1 END) as malo
        ')
        ->first();

    $usuarioId = $usuario->id;

    // 📚 Valoraciones agrupadas por género
    $valoracionesPorGenero = \DB::table('generos')
    ->leftJoin('libros', 'generos.id', '=', 'libros.id_genero')
    ->leftJoin('valoraciones', function ($join) use ($usuarioId) {
        $join->on('libros.id', '=', 'valoraciones.id_libro')
             ->where('valoraciones.id_usuario', '=', $usuarioId);
    })
    ->select('generos.genero', \DB::raw('COUNT(valoraciones.id) as total'))
    ->groupBy('generos.genero')
    ->orderByDesc('total')
    ->get();

    $valoracionesPorAutor = \DB::table('valoraciones')
    ->join('libros', 'valoraciones.id_libro', '=', 'libros.id')
    ->where('valoraciones.id_usuario', $usuario->id)
    ->select('libros.autor as autor', \DB::raw('COUNT(*) as total'))
    ->groupBy('libros.autor')
    ->orderByDesc('total')
    ->get();


    return Inertia::render('PerfilUsuario', [
        'user' => [
            'nombre' => $usuario->nombre,
            'nombre_usuario' => $usuario->nombre_usuario,
            'email' => $usuario->email,
            'ciudad' => $usuario->ciudad,
            'pais' => $usuario->pais,
            'foto' => $usuario->foto,
        ],
        'votacionesCount' => $usuario->valoraciones()->count(),
        'comentariosCount' => $usuario->comentarios()->count(),
        'librosSugeridosCount' => $usuario->librosSugeridos()->count(),
        'listas' => $usuario->listas()
            ->withCount('libros')
            ->select('id', 'nombre')
            ->get()
            ->map(fn ($lista) => [
                'id' => $lista->id,
                'nombre' => $lista->nombre,
                'total' => $lista->libros_count ?? 0,
            ]),
        'valoracionesPorNota' => [
            'excelente' => $valoraciones->excelente,
            'muy_bueno' => $valoraciones->muy_bueno,
            'regular' => $valoraciones->regular,
            'flojo' => $valoraciones->flojo,
            'malo' => $valoraciones->malo,
        ],
        // ✅ ESTA ES LA LÍNEA QUE TE FALTABA
        'valoracionesPorGenero' => $valoracionesPorGenero,
        'valoracionesPorAutor' => $valoracionesPorAutor,
    ]);
}

public function adminUsuariosIndex()
{
    $usuarios = \App\Models\Usuario::select('id', 'nombre', 'email', 'rol')->get();

    return Inertia::render('PanelControlAdmin', [ // ✅ nombre del archivo de Pages
        'usuarios' => $usuarios
    ]);
}










    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'nombre_usuario' => 'required|string|max:255|unique:usuarios',
        'email' => 'required|email|unique:usuarios',
        'password' => 'required|string|min:6',
        'ciudad' => 'nullable|string|max:255',
        'pais' => 'nullable|string|max:255',
        'foto' => 'nullable|image|max:2048',
    ]);

    $usuario = new \App\Models\Usuario($request->except('foto'));
    $usuario->password = bcrypt($request->password);

    if ($request->hasFile('foto')) {
        $usuario->foto = basename($request->file('foto')->store('public/perfiles'));
    }

    $usuario->rol = 'usuario'; // o lo que corresponda
    $usuario->fecha_alta = now();
    $usuario->save();

    return back()->with('message', 'Usuario creado correctamente');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    \Log::info('💾 DATOS RECIBIDOS:', $request->all());

    $user = Usuario::findOrFail($id); // <- Aquí usas el ID de la URL, no el Auth

    $request->validate([
        'nombre' => 'nullable|string|max:255',
        'nombre_usuario' => 'sometimes|required|string|max:255',
        'email' => 'sometimes|required|email|max:255',
        'ciudad' => 'nullable|string|max:255',
        'pais' => 'nullable|string|max:255',
        'foto' => 'nullable|image|max:2048',
    ]);

    $user->nombre = $request->filled('nombre') ? $request->input('nombre') : $user->nombre;
    $user->nombre_usuario = $request->filled('nombre_usuario') ? $request->input('nombre_usuario') : $user->nombre_usuario;
    $user->email = $request->filled('email') ? $request->input('email') : $user->email;
    $user->ciudad = $request->filled('ciudad') ? $request->input('ciudad') : $user->ciudad;
    $user->pais = $request->filled('pais') ? $request->input('pais') : $user->pais;

    if ($request->hasFile('foto')) {
        $foto = $request->file('foto');
        $ruta = $foto->store('public/perfiles');
        $user->foto = basename($ruta);
    }

    $user->save();

    return back()->with('message', 'Usuario actualizado correctamente');
}



public function panelAdmin()
{
    $usuarios = Usuario::select('id', 'nombre', 'email', 'rol', 'nombre_usuario', 'ciudad', 'pais')->get();


    $libros = \App\Models\Libro::with('generos')->paginate(10);

    $generos = \App\Models\Genero::select('id', 'genero')->get();

    $listas = \App\Models\Lista::select('id', 'id_usuario', 'nombre')->get();

    $escritos = \App\Models\Escrito::select('id', 'usuario_id', 'titulo', 'contenido')->get();

    $frases = \App\Models\FraseCelebre::select('id', 'usuario_id', 'texto', 'autor', 'categoria')->get();

    $sugerencias = \App\Models\LibroSugerido::select('id', 'id_usuario', 'titulo', 'descripcion')->get();



    return Inertia::render('PanelControlAdmin', [
        'usuarios' => $usuarios,
        'libros' => $libros,
        'generos' => $generos,
        'listas' => $listas,
        'escritos'=> $escritos,
        'frases' => $frases,
        'sugerencias' => $sugerencias
    ]);
}
    public function destroy($id)
{
    $usuario = Usuario::findOrFail($id);
    $usuario->delete();

    return redirect()->back();
}

}
