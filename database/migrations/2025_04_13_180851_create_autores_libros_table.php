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
    Schema::create('autores_libros', function (Blueprint $table) {
        $table->unsignedBigInteger('id_libro');
        $table->unsignedBigInteger('id_autor');

        // Clave primaria compuesta
        $table->primary(['id_libro', 'id_autor']);

        // Claves foráneas
        $table->foreign('id_libro')->references('id')->on('libros')->onDelete('cascade');
        $table->foreign('id_autor')->references('id')->on('autores')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autores_libros');
    }
};
