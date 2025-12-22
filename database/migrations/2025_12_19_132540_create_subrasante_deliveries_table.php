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
        Schema::create('subrasante_deliveries', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('truck_id')
                ->constrained('trucks')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->date('date');                 // FECHA
            $table->string('delivery_note', 50);  // # REMISION

            $table->string('material_description', 255); // DESCRIPCION MATERIAL
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Useful indexes
            $table->index(['date']);
            $table->index(['truck_id', 'date']);
            $table->index(['customer_id', 'date']);
            $table->index(['delivery_note']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subrasante_deliveries');
    }
};
