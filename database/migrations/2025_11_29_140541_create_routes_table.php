<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained()->onDelete('cascade');
            $table->string('code', 30)->unique()->comment('Kode trayek');
            $table->string('origin_city', 100);
            $table->string('destination_city', 100);
            $table->enum('type', ['AKAP', 'AKDP', 'AJDP'])->comment('AKAP: Antar Kota Antar Provinsi, AKDP: Antar Kota Dalam Provinsi, AJDP: Angkutan Jemput Dalam Provinsi');
            $table->decimal('distance', 8, 2)->comment('Jarak dalam km');
            $table->decimal('base_fare', 10, 2)->comment('Tarif dasar');
            $table->integer('estimated_duration')->comment('Estimasi durasi dalam menit');
            $table->json('stops')->nullable()->comment('Pemberhentian antara');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['code', 'type', 'status']);
            $table->index(['origin_city', 'destination_city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
