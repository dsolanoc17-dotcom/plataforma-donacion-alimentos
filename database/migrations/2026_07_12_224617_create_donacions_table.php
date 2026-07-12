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
        Schema::create('donaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donante_id')->constrained('users')->onDelete('cascade');
            $table->string('tipo_alimento');
            $table->decimal('cantidad', 8, 2);
            $table->string('unidad'); // kg, litros, unidades, etc.
            $table->date('fecha_vencimiento');
            $table->string('estado')->default('disponible'); // disponible, reservada, entregada, vencida
            $table->string('ubicacion_recojo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donaciones');
    }
};
