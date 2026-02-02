<?php
// database/migrations/YYYY_MM_DD_HHMMSS_add_columns_to_binh_luan_table.php
// Chạy lệnh: php artisan make:migration add_columns_to_binh_luan_table

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
        Schema::table('binh_luan', function (Blueprint $table) {
            // Cột trạng thái duyệt (mặc định đã duyệt)
            $table->enum('TrangThai', ['cho_duyet', 'da_duyet', 'bi_an'])
                  ->default('da_duyet')
                  ->after('NoiDung')
                  ->comment('Trạng thái kiểm duyệt bình luận');
            
            // Cột mã bình luận cha để trả lời bình luận
            $table->unsignedBigInteger('MaBinhLuanCha')
                  ->nullable()
                  ->after('MaBinhLuan')
                  ->comment('Mã bình luận cha (null = bình luận gốc)');
            
            // Cột đã xem cho admin
            $table->boolean('DaXem')
                  ->default(false)
                  ->after('TrangThai')
                  ->comment('Admin đã xem bình luận chưa');
            
            // Foreign key cho MaBinhLuanCha
            $table->foreign('MaBinhLuanCha')
                  ->references('MaBinhLuan')
                  ->on('binh_luan')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('binh_luan', function (Blueprint $table) {
            $table->dropForeign(['MaBinhLuanCha']);
            $table->dropColumn(['TrangThai', 'MaBinhLuanCha', 'DaXem']);
        });
    }
};