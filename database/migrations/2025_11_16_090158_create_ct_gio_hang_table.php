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
        Schema::create('ct_gio_hang', function (Blueprint $table) {
            $table->id('MaCTGioHang');
            $table->foreignId('MaNguoiDung')->constrained('nguoi_dung', 'MaNguoiDung')->onDelete('cascade');
            $table->foreignId('MaCTSanPham')->constrained('ct_san_pham', 'MaCTSanPham')->onDelete('cascade');
            $table->integer('SoLuong');
            $table->decimal('DonGia', 18, 2);
            $table->dateTime('NgayThem');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ct_gio_hang');
    }
};
