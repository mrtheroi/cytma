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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno')->nullable();

            $table->string('turno')->nullable()->comment('Matutino | Vespertino');
            $table->string('categoria')->nullable()->comment('Puesto');
            $table->string('unidad_negocio')->nullable()->comment('Unidad de negocio a la que está adscrito el empleado');
            $table->string('adscrito')->nullable()->comment('Lugar físico donde trabaja el empleado');
            $table->decimal('sueldo_pactado', 10, 2)->default(0)->comment('Sueldo base acordado con el empleado para el período');
            $table->decimal('costo_dia', 10, 2)->default(0);
            $table->decimal('costo_hora', 10, 2)->default(0);
            $table->decimal('costo_hora_extra', 10, 2)->default(0);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
