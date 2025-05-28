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
    Schema::create('textos', function (Blueprint $table) {
        $table->id(); // ID_texto
        $table->unsignedBigInteger('usuario_id');
        $table->string('titulo');
        $table->text('contenido');
        $table->timestamps();

        $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('textos');
    }
};
