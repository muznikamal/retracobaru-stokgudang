<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('device_name')->nullable(); // Nama device (tablet/PC)
            $table->string('device_token',255); // Token unik device
            $table->string('user_agent')->nullable(); // Info browser/OS
            $table->string('ip_address')->nullable(); // IP terakhir
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('is_approved')->default(false); // Admin harus setujui
            $table->timestamps();

            $table->unique(['user_id', 'device_token'],'unique_user_device');
        });
    }

    public function down(): void {
        Schema::dropIfExists('user_devices');
    }
};
