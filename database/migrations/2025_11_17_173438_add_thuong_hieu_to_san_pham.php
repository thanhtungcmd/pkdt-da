<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('san_pham', function (Blueprint $table) {
            $table->string('ThuongHieu', 100)->nullable()->after('TenSanPham');
        });
    }

    public function down(): void
    {
        Schema::table('san_pham', function (Blueprint $table) {
            $table->dropColumn('ThuongHieu');
        });
    }
};