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
    Schema::create('listas_libros', function (Blueprint $table) {
        $table->unsignedBigInteger('id_lista');
        $table->unsignedBigInteger('id_libro');

        // Clave primaria compuesta
        $table->primary(['id_lista', 'id_libro']);

        // Claves foráneas
        $table->foreign('id_lista')->references('id')->on('listas')->onDelete('cascade');
        $table->foreign('id_libro')->references('id')->on('libros')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listas_libros');
    }
};
