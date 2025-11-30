<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terminals', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Kode terminal');
            $table->string('name')->comment('Nama terminal');
            $table->enum('type', ['A', 'B', 'C'])->default('A');
            $table->text('address');
            $table->string('city', 100);
            $table->string('province', 100);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('capacity')->comment('Kapasitas kendaraan per hari');
            $table->integer('boarding_gates')->comment('Jumlah jalur keberangkatan');
            $table->integer('parking_slots')->comment('Slot parkir kendaraan');
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['code', 'status']);
            $table->index('city');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terminals');
    }
};