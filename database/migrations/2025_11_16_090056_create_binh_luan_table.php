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
        Schema::create('binh_luan', function (Blueprint $table) {
            $table->id('MaBinhLuan');
            $table->foreignId('MaNguoiDung')->constrained('nguoi_dung', 'MaNguoiDung')->onDelete('cascade');
            $table->foreignId('MaSanPham')->constrained('san_pham', 'MaSanPham')->onDelete('cascade');
            $table->text('NoiDung');
            $table->dateTime('NgayBinhLuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('binh_luan');
    }
};