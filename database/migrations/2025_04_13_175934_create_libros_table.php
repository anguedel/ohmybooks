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
    Schema::create('libros', function (Blueprint $table) {
        $table->id(); // ID_libro
        $table->string('titulo');
        $table->text('sinopsis')->nullable();
        $table->date('fecha_publicacion')->nullable();
        $table->date('fecha_alta');
        $table->string('editorial')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
