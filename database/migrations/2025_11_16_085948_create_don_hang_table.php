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
        Schema::create('don_hang', function (Blueprint $table) {
            $table->id('MaDonHang');
            $table->foreignId('MaNguoiDung')->constrained('nguoi_dung', 'MaNguoiDung')->onDelete('cascade');
            $table->dateTime('NgayDat');
            $table->decimal('TongTien', 18, 2);
            $table->string('DiaChiGiaoHang', 255);
            $table->string('PTThanhToan', 50)->comment('COD, Bank, VNPay');
            $table->string('TrangThai', 50)->default('Chờ xác nhận');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('don_hang');
    }
};
