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
        Schema::create('phan_hoi', function (Blueprint $table) {
            $table->id('MaPhanHoi');
            $table->foreignId('MaNguoiDung')->constrained('nguoi_dung', 'MaNguoiDung')->onDelete('cascade');
            $table->string('TieuDe', 255);
            $table->text('NoiDung');
            $table->dateTime('NgayGui');
            $table->boolean('TrangThai')->default(0)->comment('0 = Chưa xem, 1 = Đã xem');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phan_hoi');
    }
};
