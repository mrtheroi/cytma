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
        Schema::create('diesel_loads', function (Blueprint $table) {
            $table->id();

            $table->date('date'); // Fecha de carga

            $table->foreignId('business_unit_id')
                ->constrained('business_units')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('supplier_id')
                ->constrained('suppliers')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('equipment_id')
                ->constrained('equipment')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Si tu tabla se llama "empleados"
            $table->foreignId('empleado_id')
                ->constrained('empleados')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->decimal('hour_meter', 10, 2)->nullable(); // Horómetro
            $table->decimal('liters', 12, 2);                 // Litros cargados

            $table->text('notes')->nullable();                // Nota opcional

            // opcional: quién capturó
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Índices útiles para filtros/reportes
            $table->index(['date']);
            $table->index(['business_unit_id', 'date']);
            $table->index(['equipment_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diesel_loads');
    }
};
