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
    Schema::create('listas', function (Blueprint $table) {
        $table->id(); // ID_lista
        $table->unsignedBigInteger('id_usuario');
        $table->string('nombre');
        $table->date('fecha');
        $table->timestamps();

        $table->foreign('id_usuario')->references('id')->on('usuarios')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listas');
    }
};
