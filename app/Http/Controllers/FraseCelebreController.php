<?php

namespace App\Http\Controllers;
use App\Models\FraseCelebre;    
use Inertia\Inertia;
use Illuminate\Http\Request;

class FraseCelebreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $categorias = ['Filosofía', 'Estoicismo', 'Persuasión', 'Motivación', 'Poesía'];
    $usuarioId = auth()->id();

    $frasesPorCategoria = [];

    foreach ($categorias as $categoria) {
        $frases = FraseCelebre::where('categoria', $categoria)
            ->withCount('usuariosQueDieronLike')
            ->with(['usuariosQueDieronLike' => function ($q) use ($usuarioId) {
                $q->where('usuario_id', $usuarioId);
            }])
            ->orderByDesc('id')
            ->take(10)
            ->get()
            ->map(function ($frase) {
                return [
                    'id' => $frase->id,
                    'texto' => $frase->texto,
                    'autor' => $frase->autor,
                    'likes' => $frase->usuarios_que_dieron_like_count,
                    'yaDioLike' => $frase->usuariosQueDieronLike->isNotEmpty(),
                ];
            });

        $frasesPorCategoria[$categoria] = $frases;
    }

    return Inertia::render('FrasesCelebres', [
        'frasesPorCategoria' => $frasesPorCategoria,
        'categorias' => $categorias,
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
        'texto' => 'required|string|max:1000',
        'autor' => 'required|string|max:255',
        'categoria' => 'required|string|in:Filosofía,Estoicismo,Persuasión,Motivación,Poesía',
    ]);

    \App\Models\FraseCelebre::create([
        'usuario_id' => auth()->id(),
        'texto' => $request->texto,
        'autor' => $request->autor,
        'categoria' => $request->categoria,
    ]);

    return redirect()->back();
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
    // app/Http/Controllers/FraseCelebreController.php

public function update(Request $request, $id)
{
    $request->validate([
        'texto' => 'required|string',
        'autor' => 'required|string|max:255',
        'categoria' => 'required|string|max:100',
    ]);

    $frase = FraseCelebre::findOrFail($id);
    $frase->update([
        'texto' => $request->texto,
        'autor' => $request->autor,
        'categoria' => $request->categoria,
    ]);

    return back();
}

public function destroy($id)
{
    $frase = FraseCelebre::findOrFail($id);
    $frase->delete();

    return back();
}


    public function like($id)
    {
        $user = auth()->user();
        $frase = FraseCelebre::findOrFail($id);
    
        if ($frase->usuariosQueDieronLike()->where('usuario_id', $user->id)->exists()) {
            $frase->usuariosQueDieronLike()->detach($user->id); // Quitar like
        } else {
            $frase->usuariosQueDieronLike()->attach($user->id); // Dar like
        }
    
        return redirect()->back();
    }
    


}
