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
        Schema::table('danh_gia', function (Blueprint $table) {
            $table->text('PhanHoiShop')->nullable()->after('TrangThai')->comment('Phản hồi từ shop/admin');
            $table->foreignId('NguoiPhanHoi')->nullable()->after('PhanHoiShop')->constrained('nguoi_dung', 'MaNguoiDung')->onDelete('set null')->comment('Admin phản hồi');
            $table->dateTime('NgayPhanHoi')->nullable()->after('NguoiPhanHoi')->comment('Ngày admin phản hồi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('danh_gia', function (Blueprint $table) {
            $table->dropForeign(['NguoiPhanHoi']);
            $table->dropColumn(['PhanHoiShop', 'NguoiPhanHoi', 'NgayPhanHoi']);
        });
    }
};