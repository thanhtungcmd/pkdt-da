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
        Schema::create('nguoi_dung', function (Blueprint $table) {
            $table->id('MaNguoiDung');
            $table->string('TenDangNhap', 50)->unique();
            $table->string('Email', 100)->unique();
            $table->string('MatKhau', 100);
            $table->string('HoTen', 100);
            $table->string('AnhDaiDien', 255)->nullable();
            $table->string('SoDienThoai', 20)->nullable();
            $table->string('DiaChi', 255)->nullable();
            $table->boolean('VaiTro')->default(0)->comment('0 = Khách hàng, 1 = Admin');
            $table->boolean('TrangThai')->default(1)->comment('1 = Hoạt động, 0 = Khóa');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguoi_dung');
    }
};
