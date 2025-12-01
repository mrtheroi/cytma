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
        Schema::create('detalles_nomina', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registro_nomina_id')->constrained('registros_nomina')->onDelete('cascade');
            $table->foreignId('concepto_nomina_id')->constrained('conceptos_nomina')->onDelete('restrict');

            $table->decimal('monto', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_nomina');
    }
};
