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
        Schema::table('checkins', function (Blueprint $table) {
            $table->timestamp('checkout_time')->nullable(); // Check-out zamanı
            $table->decimal('checkout_latitude', 10, 8)->nullable(); // Check-out konumu
            $table->decimal('checkout_longitude', 11, 8)->nullable();
            $table->text('notes')->nullable(); // Çalışan notları
            $table->enum('status', ['active', 'completed', 'missed'])->default('active'); // Mesai durumu
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checkins', function (Blueprint $table) {
            $table->dropColumn(['checkout_time', 'checkout_latitude', 'checkout_longitude', 'notes', 'status']);
        });
    }
};
