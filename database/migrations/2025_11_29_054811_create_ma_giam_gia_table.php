<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bảng mã giảm giá
        Schema::create('ma_giam_gia', function (Blueprint $table) {
            $table->id('MaGiamGia');
            $table->string('MaCode', 50)->unique(); // Mã giảm giá (VD: SUMMER2024)
            $table->enum('LoaiGiam', ['fixed', 'percent']); // Loại: giảm cố định hoặc %
            $table->decimal('GiaTri', 18, 2); // Giá trị giảm
            $table->decimal('DonToiThieu', 18, 2)->nullable(); // Đơn hàng tối thiểu
            $table->decimal('GiamToiDa', 18, 2)->nullable(); // Giảm tối đa (cho % discount)
            $table->integer('GioiHanSuDung')->nullable(); // Giới hạn số lần sử dụng
            $table->integer('DaSuDung')->default(0); // Đã sử dụng bao nhiêu lần
            $table->dateTime('NgayBatDau')->nullable(); // Ngày bắt đầu
            $table->dateTime('NgayKetThuc')->nullable(); // Ngày kết thúc
            $table->boolean('TrangThai')->default(true); // Trạng thái active
            $table->text('MoTa')->nullable(); // Mô tả
            $table->timestamps();
        });

        // Bảng lưu lịch sử sử dụng mã giảm giá
        Schema::create('lich_su_ma_giam_gia', function (Blueprint $table) {
            $table->id('MaLichSu');
            $table->foreignId('MaGiamGia')->constrained('ma_giam_gia', 'MaGiamGia')->onDelete('cascade');
            $table->foreignId('MaNguoiDung')->nullable()->constrained('nguoi_dung', 'MaNguoiDung')->onDelete('set null');
            $table->foreignId('MaDonHang')->nullable()->constrained('don_hang', 'MaDonHang')->onDelete('set null');
            $table->decimal('SoTienGiam', 18, 2); // Số tiền đã giảm
            $table->timestamp('ThoiGianSuDung');
            $table->timestamps();
        });

        // Thêm cột vào bảng don_hang
        Schema::table('don_hang', function (Blueprint $table) {
            $table->foreignId('MaGiamGia')->nullable()->after('TongTien')->constrained('ma_giam_gia', 'MaGiamGia')->onDelete('set null');
            $table->decimal('SoTienGiam', 18, 2)->default(0)->after('MaGiamGia');
        });
    }

    public function down(): void
    {
        Schema::table('don_hang', function (Blueprint $table) {
            $table->dropForeign(['MaGiamGia']);
            $table->dropColumn(['MaGiamGia', 'SoTienGiam']);
        });
        
        Schema::dropIfExists('lich_su_ma_giam_gia');
        Schema::dropIfExists('ma_giam_gia');
    }
};