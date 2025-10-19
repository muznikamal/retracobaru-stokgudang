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
        Schema::create('barang_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('nama_petugas')->nullable();
            $table->foreignId('barang_id')->constrained()->onDelete('cascade'); // relasi ke barang
            $table->foreignId('user_id')->constrained()->onDelete('cascade');   // siapa yg input
            $table->integer('jumlah'); // jumlah barang keluar
            $table->enum('tipe_keluar', ['penjualan', 'kendala'])->default('penjualan'); 
            $table->string('catatan')->nullable(); // catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluars');
    }
};
