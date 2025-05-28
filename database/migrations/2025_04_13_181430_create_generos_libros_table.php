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
    Schema::create('generos_libros', function (Blueprint $table) {
        $table->unsignedBigInteger('id_libro');
        $table->unsignedBigInteger('id_genero');

        // Clave primaria compuesta
        $table->primary(['id_libro', 'id_genero']);

        // Claves foráneas
        $table->foreign('id_libro')->references('id')->on('libros')->onDelete('cascade');
        $table->foreign('id_genero')->references('id')->on('generos')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generos_libros');
    }
};
