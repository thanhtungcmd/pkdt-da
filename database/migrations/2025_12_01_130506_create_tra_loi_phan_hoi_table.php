<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tra_loi_phan_hoi', function (Blueprint $table) {
            $table->id('MaTraLoi');
            $table->foreignId('MaPhanHoi')->constrained('phan_hoi', 'MaPhanHoi')->onDelete('cascade');
            $table->foreignId('MaAdmin')->constrained('nguoi_dung', 'MaNguoiDung')->onDelete('cascade');
            $table->text('NoiDung');
            $table->dateTime('NgayTraLoi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tra_loi_phan_hoi');
    }
};