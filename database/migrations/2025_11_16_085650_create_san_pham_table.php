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
        Schema::create('san_pham', function (Blueprint $table) {
            $table->id('MaSanPham');
            $table->foreignId('MaDanhMuc')->constrained('danh_muc', 'MaDanhMuc')->onDelete('cascade');
            $table->string('TenSanPham', 255);
            $table->string('AnhChinh', 255);
            $table->text('MoTa')->nullable();
            $table->boolean('TrangThai')->default(1)->comment('1 = Hoạt động, 0 = Ẩn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('san_pham');
    }
};
