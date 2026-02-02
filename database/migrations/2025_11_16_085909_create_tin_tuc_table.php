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
        Schema::create('tin_tuc', function (Blueprint $table) {
            $table->id('MaTinTuc');
            $table->foreignId('MaNguoiDung')->constrained('nguoi_dung', 'MaNguoiDung')->onDelete('cascade');
            $table->string('TieuDe', 255)->unique();
            $table->text('NoiDung');
            $table->string('AnhMinhHoa', 255);
            $table->dateTime('NgayDang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tin_tuc');
    }
};
