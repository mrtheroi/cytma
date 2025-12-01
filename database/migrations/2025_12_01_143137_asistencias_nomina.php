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
        Schema::create('asistencias_nomina', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registro_nomina_id')->constrained('registros_nomina');

            $table->boolean('lunes')->default(false);
            $table->integer('horas_extra_lunes')->default(0);

            $table->boolean('martes')->default(false);
            $table->integer('horas_extra_martes')->default(0);

            $table->boolean('miercoles')->default(false);
            $table->integer('horas_extra_miercoles')->default(0);

            $table->boolean('jueves')->default(false);
            $table->integer('horas_extra_jueves')->default(0);

            $table->boolean('viernes')->default(false);
            $table->integer('horas_extra_viernes')->default(0);

            $table->boolean('sabado')->default(false);
            $table->integer('horas_extra_sabado')->default(0);

            $table->boolean('domingo')->default(false);
            $table->integer('horas_extra_domingo')->default(0);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias_nomina');
    }
};
