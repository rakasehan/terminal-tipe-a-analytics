<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('terminal_id')->nullable()->after('email')->constrained()->onDelete('set null');
            $table->string('phone', 20)->nullable()->after('terminal_id');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('phone');
            
            $table->index(['terminal_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['terminal_id']);
            $table->dropIndex(['terminal_id', 'status']);
            $table->dropColumn(['terminal_id', 'phone', 'status']);
        });
    }
};