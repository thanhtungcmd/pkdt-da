<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ma_giam_gia', function (Blueprint $table) {
            $table->integer('GioiHanMoiNguoi')->nullable()->after('GioiHanSuDung')
                  ->comment('Số lần tối đa mỗi người dùng được sử dụng (NULL = không giới hạn)');
        });
    }

    public function down()
    {
        Schema::table('ma_giam_gia', function (Blueprint $table) {
            $table->dropColumn('GioiHanMoiNguoi');
        });
    }
};