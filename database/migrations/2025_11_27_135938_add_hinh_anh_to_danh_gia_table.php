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
            $table->json('HinhAnh')->nullable()->after('NoiDung')->comment('Danh sách ảnh đánh giá (tối đa 5 ảnh)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('danh_gia', function (Blueprint $table) {
            $table->dropColumn('HinhAnh');
        });
    }
};