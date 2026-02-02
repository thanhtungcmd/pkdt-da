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
        Schema::create('ct_san_pham', function (Blueprint $table) {
            $table->id('MaCTSanPham');
            $table->foreignId('MaSanPham')->constrained('san_pham', 'MaSanPham')->onDelete('cascade');
            $table->string('MauSac', 100);
            $table->string('DungLuong', 100)->nullable();
            $table->string('KichThuoc', 100)->nullable();
            $table->decimal('DonGia', 18, 2);
            $table->integer('SoLuongTon')->default(0);
            $table->string('AnhMinhHoa', 255);
            $table->boolean('TrangThai')->default(1)->comment('1 = Hoạt động, 0 = Ẩn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ct_san_pham');
    }
};
