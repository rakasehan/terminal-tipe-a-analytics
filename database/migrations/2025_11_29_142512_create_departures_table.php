<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('terminal_id')->constrained()->onDelete('cascade');
            $table->foreignId('route_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('operator_id')->constrained()->onDelete('cascade');
            $table->date('departure_date');
            $table->time('scheduled_time')->comment('Jadwal keberangkatan');
            $table->time('actual_time')->nullable()->comment('Waktu keberangkatan actual');
            $table->integer('passengers')->comment('Jumlah penumpang');
            $table->integer('seat_capacity')->comment('Kapasitas dari kendaraan');
            $table->decimal('occupancy_rate', 5, 2)->comment('Persentase okupansi');
            $table->decimal('revenue', 12, 2)->nullable()->comment('Pendapatan');
            $table->string('gate_number', 10)->nullable()->comment('Nomor jalur keberangkatan');
            $table->enum('status', ['scheduled', 'departed', 'cancelled', 'delayed'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['terminal_id', 'departure_date', 'status']);
            $table->index(['route_id', 'departure_date']);
            $table->index('operator_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departures');
    }
};
