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
        Schema::create('danh_gia_huu_ich', function (Blueprint $table) {
            $table->id('MaHuuIch');
            $table->foreignId('MaDanhGia')->constrained('danh_gia', 'MaDanhGia')->onDelete('cascade');
            $table->foreignId('MaNguoiDung')->constrained('nguoi_dung', 'MaNguoiDung')->onDelete('cascade');
            $table->boolean('HuuIch')->comment('true = hữu ích, false = không hữu ích');
            $table->timestamps();
            
            // Mỗi người chỉ vote 1 lần cho 1 đánh giá
            $table->unique(['MaDanhGia', 'MaNguoiDung']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danh_gia_huu_ich');
    }
};