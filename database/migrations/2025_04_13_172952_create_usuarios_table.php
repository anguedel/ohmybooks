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
    Schema::create('usuarios', function (Blueprint $table) {
        $table->id(); // ID autoincremental
        $table->string('nombre');
        $table->string('nombre_usuario')->unique();
        $table->string('password');
        $table->string('email')->unique();
        $table->enum('rol', ['Usuario', 'admin']);
        $table->date('fecha_alta');
        $table->string('foto')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
