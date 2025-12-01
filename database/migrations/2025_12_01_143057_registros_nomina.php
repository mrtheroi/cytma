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
        Schema::create('registros_nomina', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periodo_nomina_id')->constrained('periodos_nomina')->onDelete('cascade');
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');

            $table->decimal('sueldo_pactado', 10, 2)->default(0)->comment('Sueldo base acordado con el empleado para el período');
            $table->decimal('percepciones_totales', 10, 2)->default(0)->comment('Total de percepciones: sueldo, bonos, horas extra y demás ingresos');
            $table->decimal('deducciones_totales', 10, 2)->default(0)->comment('Total de deducciones aplicadas: ISR, IMSS, préstamos, etc.');
            $table->integer('total_horas_extras')->default(0)->comment('Cantidad total de horas extras trabajadas en el período');
            $table->decimal('neto', 10, 2)->default(0)->comment('Monto final a pagar al empleado (percepciones - deducciones)');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros_nomina');
    }
};
