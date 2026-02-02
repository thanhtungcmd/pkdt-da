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
        Schema::create('danh_gia', function (Blueprint $table) {
            $table->id('MaDanhGia');
            $table->foreignId('MaNguoiDung')->constrained('nguoi_dung', 'MaNguoiDung')->onDelete('cascade');
            $table->foreignId('MaSanPham')->constrained('san_pham', 'MaSanPham')->onDelete('cascade');
            $table->foreignId('MaDonHang')->constrained('don_hang', 'MaDonHang')->onDelete('cascade');
            $table->tinyInteger('SoSao')->comment('1-5 sao');
            $table->text('NoiDung')->nullable()->comment('Nội dung đánh giá');
            $table->enum('TrangThai', ['cho_duyet', 'da_duyet', 'bi_an'])->default('da_duyet');
            $table->dateTime('NgayDanhGia');
            $table->timestamps();
            
            // Mỗi người chỉ đánh giá 1 lần cho 1 sản phẩm trong 1 đơn hàng
            $table->unique(['MaNguoiDung', 'MaSanPham', 'MaDonHang'], 'unique_danh_gia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danh_gia');
    }
};