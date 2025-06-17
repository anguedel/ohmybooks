<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\FraseCelebreController;
use App\Http\Controllers\RinconController;
use App\Http\Controllers\ListaController;
use App\Http\Controllers\ListasUsuarioController;
use App\Http\Controllers\GeneroController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\ValoracionController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\LibroSugeridoController;
use App\Http\Controllers\EscritoUsuarioController;
use App\Http\Controllers\RegistroManualController;



require __DIR__.'/auth.php';


// ========================================================================
// 🌐 PÁGINAS PÚBLICAS
// ========================================================================

Route::get('/', [InicioController::class, 'index'])->name('inicio');

Route::get('/inicio', fn () => Inertia::render('Inicio'))->name('inicio');

Route::get('/generos', [GeneroController::class, 'librosPorGenero'])->name('generos');

Route::get('/libro/{id}', [LibroController::class, 'show'])->name('libros.show');

Route::get('/rincon', [RinconController::class, 'index'])->name('rincon');

Route::get('/frases', [FraseCelebreController::class, 'index'])->name('frases');

Route::post('/frases/{id}/like', [FraseCelebreController::class, 'like'])->name('frases.like');

Route::get('/listaspredefinidas', [ListaController::class, 'index'])->name('listas.predefinidas');

Route::get('/listaspredefinidascontenido', fn () => Inertia::render('ListasPredefinidasContenido'))->name('listas.predefinidasContenido');

Route::post('/registro-manual', [RegistroManualController::class, 'store'])->middleware('guest')->name('registro.manual');


// ========================================================================
// 🔐 AUTENTICACIÓN Y PERFIL
// ========================================================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');





// ========================================================================
// 👤 USUARIO AUTENTICADO
// ========================================================================

Route::middleware('auth')->group(function () {
    Route::get('/perfilUsuario', [UsuarioController::class, 'index'])->name('perfil.usuario');
    Route::get('/perfilUsuario/listasusuario', [ListasUsuarioController::class, 'index'])->name('listas.usuario');
    Route::get('/listasusuariocontenido', fn () => Inertia::render('ListasUsuarioContenido'))->name('listas.usuarioContenido');

    Route::put('/usuario/actualizar', [UsuarioController::class, 'update'])->name('usuario.update');
   

    Route::post('/votar', [ValoracionController::class, 'store'])->name('votar');

    Route::post('/comentarios', [ComentarioController::class, 'store'])->name('comentarios.store');
    Route::put('/comentarios/{id}', [ComentarioController::class, 'update'])->name('comentarios.update');
    Route::delete('/comentarios/{id}', [ComentarioController::class, 'destroy'])->name('comentarios.destroy');

    Route::post('/libros-sugeridos', [LibroSugeridoController::class, 'store'])->name('libros.sugeridos.store');

    Route::post('/listas', [ListaController::class, 'store'])->name('listas.store');
    Route::put('/listas/{id}', [ListaController::class, 'update'])->name('listas.update');
    Route::get('/listas/{id}', [ListaController::class, 'show'])->name('listas.show');
    Route::post('/listas/agregar-libro', [ListaController::class, 'agregarLibro'])->name('listas.agregarLibro');
    Route::delete('/listas/{id}', [ListaController::class, 'destroy'])->name('listas.destroy');

    Route::post('/escritos', [EscritoUsuarioController::class, 'store'])->name('escritos.store');
    Route::put('/escritos/{escrito}', [EscritoUsuarioController::class, 'update'])->name('escritos.update');
    Route::delete('/escritos/{escrito}', [EscritoUsuarioController::class, 'destroy'])->name('escritos.destroy');
    Route::get('/escritos', [EscritoUsuarioController::class, 'adminIndex'])->name('escritos.index');

    
    Route::post('/admin/usuarios', [UsuarioController::class, 'store'])->name('admin.usuarios.store');


});


// ========================================================================
// 🛡️ PANEL DE ADMINISTRACIÓN (solo para usuarios con rol 'admin')
// ========================================================================

Route::middleware(['auth', 'es_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/panel', [UsuarioController::class, 'panelAdmin'])->name('dashboard');
   
    Route::post('/libros', [LibroController::class, 'store'])->name('admin.libros.store');
    Route::get('/usuarios', fn () => Inertia::render('Admin/GestionUsuarios'))->name('admin.usuarios.index');
    Route::get('/libros', fn () => Inertia::render('Admin/GestionLibros'))->name('libros.index');
    Route::get('/listas', fn () => Inertia::render('Admin/GestionListas'))->name('listas.index');
    Route::post('/listas', [ListaController::class, 'store'])->name('admin.listas.store');
    Route::get('/escritos', [EscritoUsuarioController::class, 'adminIndex'])->name('escritos.index'); 
    Route::post('/escritos', [EscritoUsuarioController::class, 'store'])->name('admin.escritos.store');
    Route::get('/escritos/{id}', [EscritoUsuarioController::class, 'show'])->name('escritos.show');
    Route::get('/escritos/{id}/edit', [EscritoUsuarioController::class, 'edit'])->name('escritos.edit');
    Route::put('/escritos/{escrito}', [EscritoUsuarioController::class, 'update'])->name('escritos.update');
    Route::delete('/escritos/{escrito}', [EscritoUsuarioController::class, 'destroy'])->name('escritos.destroy');
    Route::get('/frases', fn () => Inertia::render('Admin/GestionFrases'))->name('frases.index');
    Route::post('/frases', [FraseCelebreController::class, 'store'])->name('admin.frases.store');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('admin.usuarios.update');
    Route::put('/frases/{id}', [FraseCelebreController::class, 'update'])->name('frases.update');
    Route::delete('/frases/{id}', [FraseCelebreController::class, 'destroy'])->name('frases.destroy');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy');
     Route::post('/usuarios', [UsuarioController::class, 'store'])->name('admin.usuarios.store');
    Route::put('/libros/{id}', [LibroController::class, 'update'])->name('admin.libros.update');
    Route::delete('/libros/{id}', [LibroController::class, 'destroy'])->name('admin.libros.destroy');
    Route::delete('/libros-sugeridos/{id}', [LibroSugeridoController::class, 'destroy'])->name('libros.sugeridos.destroy');

    Route::get('/sugerencias', fn () => Inertia::render('Admin/GestionSugerencias'))->name('sugerencias.index');

    Route::resource('listas', ListaController::class)->except(['show', 'create'])->names([
    'index' => 'listas.index', // esta ya está definida como vista, puedes eliminar si quieres
    'store' => 'admin.listas.store',
    'update' => 'admin.listas.update',
    'destroy' => 'admin.listas.destroy',
    'edit' => 'admin.listas.edit',
]);
});


