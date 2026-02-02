<?php
// database/migrations/YYYY_MM_DD_HHMMSS_create_notifications_table.php

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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('MaThongBao');
            $table->foreignId('MaNguoiDung')->constrained('nguoi_dung', 'MaNguoiDung')->onDelete('cascade');
            
            // Loại thông báo
            $table->enum('LoaiThongBao', [
                'comment_reply',      // Có người trả lời comment
                'review_reply',       // Shop trả lời đánh giá
                'feedback_reply',     // Shop trả lời phản hồi
                'order_confirmed',    // Đơn hàng đã xác nhận
            ])->comment('Loại thông báo');
            
            // Tiêu đề và nội dung
            $table->string('TieuDe', 255)->comment('Tiêu đề thông báo');
            $table->text('NoiDung')->comment('Nội dung thông báo');
            
            // Link đến trang liên quan
            $table->string('Link', 500)->nullable()->comment('URL chuyển hướng khi click');
            
            // Trạng thái đã đọc
            $table->boolean('DaDoc')->default(false)->comment('true = đã đọc, false = chưa đọc');
            
            // Thời gian
            $table->dateTime('ThoiGian')->comment('Thời gian tạo thông báo');
            
            $table->timestamps();
            
            // Index để tăng tốc query
            $table->index(['MaNguoiDung', 'DaDoc']);
            $table->index('ThoiGian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};