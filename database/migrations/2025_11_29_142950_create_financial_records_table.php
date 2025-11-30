<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('terminal_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('type', ['revenue', 'expense']);
            $table->enum('category', [
                'retribution',    // Retribusi
                'parking',        // Parkir
                'commercial',     // Komersial (tenant)
                'operational',    // Operasional
                'maintenance',    // Pemeliharaan
                'utilities',      // Listrik, air, dll
                'salary',         // Gaji
                'other'
            ]);
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->string('reference_number', 50)->nullable();
            $table->timestamps();

            $table->index(['terminal_id', 'date', 'type']);
            $table->index(['terminal_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_records');
    }
};
