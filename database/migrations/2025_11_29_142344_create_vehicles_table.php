<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained()->onDelete('cascade');
            $table->string('plate_number', 20)->unique()->comment('Nomor polisi');
            $table->string('vehicle_type', 50)->comment('Jenis bus (ekonomi, patas, executive, dll)');
            $table->string('brand', 50)->nullable()->comment('Merk bus');
            $table->integer('seat_capacity')->comment('Kapasitas tempat duduk');
            $table->integer('year')->nullable()->comment('Tahun pembuatan');
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor'])->default('good');
            $table->date('last_maintenance')->nullable();
            $table->date('kir_expiry')->nullable()->comment('Tanggal habis KIR');
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['plate_number', 'status']);
            $table->index('operator_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
