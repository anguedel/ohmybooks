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
    Schema::create('frases_celebres', function (Blueprint $table) {
        $table->id(); // ID_frase
        $table->unsignedBigInteger('usuario_id');
        $table->text('texto');
        $table->string('autor');
        $table->timestamps();

        $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frase_celebres');
    }
};
