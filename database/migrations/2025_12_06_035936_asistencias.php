<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->date('fecha')->index();
            $table->timestamp('entrada')->nullable();
            $table->timestamp('salida')->nullable();
            $table->timestamp('entrada_extra')->nullable();
            $table->timestamp('salida_extra')->nullable();
            $table->integer('horas_extra')->default(0);

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['empleado_id', 'fecha']); // Evita duplicados por día
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
