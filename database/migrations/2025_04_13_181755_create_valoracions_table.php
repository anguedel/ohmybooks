<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('valoraciones', function (Blueprint $table) {
        $table->id(); // ID_valoracion
        $table->unsignedBigInteger('id_usuario');
        $table->unsignedBigInteger('id_libro');
        $table->tinyInteger('puntuacion'); // valor entre 1 y 5
        $table->date('fecha');
        $table->timestamps();

        // Claves foráneas
        $table->foreign('id_usuario')->references('id')->on('usuarios')->onDelete('cascade');
        $table->foreign('id_libro')->references('id')->on('libros')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valoracions');
    }
};
