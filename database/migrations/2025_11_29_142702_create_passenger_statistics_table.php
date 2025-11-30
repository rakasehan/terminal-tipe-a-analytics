<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passenger_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('terminal_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('total_arrivals')->default(0)->comment('Total kedatangan');
            $table->integer('total_departures')->default(0)->comment('Total keberangkatan');
            $table->integer('peak_hour_start')->nullable()->comment('Jam sibuk mulai (0-23)');
            $table->integer('peak_hour_end')->nullable()->comment('Jam sibuk selesai (0-23)');
            $table->integer('peak_hour_passengers')->nullable()->comment('Penumpang di jam sibuk');
            $table->json('hourly_distribution')->nullable()->comment('Distribusi penumpang per jam');
            $table->json('route_distribution')->nullable()->comment('Distribusi per rute');
            $table->decimal('average_waiting_time', 8, 2)->nullable()->comment('Rata-rata waktu tunggu dalam menit');
            $table->timestamps();

            $table->unique(['terminal_id', 'date']);
            $table->index(['terminal_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passenger_statistics');
    }
};
