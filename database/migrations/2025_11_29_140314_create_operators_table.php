<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operators', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Kode PO');
            $table->string('name')->comment('Nama Perusahaan Otobus');
            $table->text('address');
            $table->string('phone', 20);
            $table->string('email', 100)->nullable();
            $table->string('director_name', 100)->nullable();
            $table->string('license_number', 50)->nullable()->comment('Nomor izin operasi');
            $table->date('license_expiry')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['code', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operators');
    }
};