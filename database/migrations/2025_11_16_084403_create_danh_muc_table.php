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
        Schema::create('danh_muc', function (Blueprint $table) {
            $table->id('MaDanhMuc');
            $table->string('TenDanhMuc', 100)->unique();
            $table->string('MoTa', 255)->nullable();
            $table->boolean('TrangThai')->default(1)->comment('1 = Hiển thị, 0 = Ẩn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danh_muc');
    }
};
