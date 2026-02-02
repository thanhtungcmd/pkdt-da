<?php
// database/seeders/TinTucSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TinTuc;
use Carbon\Carbon;

class TinTucSeeder extends Seeder
{
    public function run(): void
    {
        $tinTuc = [
            [
                'MaNguoiDung' => 1,
                'TieuDe' => '5 tai nghe gaming tốt nhất năm 2024',
                'NoiDung' => '<p>Tai nghe gaming đang trở thành phụ kiện không thể thiếu cho game thủ. Với âm thanh vòm 7.1, mic chống ồn và thiết kế ergonomic, các sản phẩm như Razer BlackShark V2, SteelSeries Arctis 7 đang dẫn đầu thị trường.</p><p>Đặc biệt, công nghệ chống ồn chủ động ANC giúp game thủ tập trung tuyệt đối vào trận đấu.</p>',
                'AnhMinhHoa' => 'https://via.placeholder.com/800x450/0066FF/FFFFFF?text=Gaming+Headset',
                'NgayDang' => Carbon::now()->subDays(5),
            ],
            [
                'MaNguoiDung' => 1,
                'TieuDe' => 'So sánh chuột gaming vs chuột văn phòng',
                'NoiDung' => '<p>Chuột gaming thường có DPI cao (16000-25000), nhiều nút lập trình, đèn RGB và thiết kế ergonomic cho game thủ. Trong khi đó, chuột văn phòng tập trung vào sự thoải mái, pin lâu và thiết kế gọn nhẹ.</p><p>Logitech MX Master 3S là lựa chọn tốt nhất cho văn phòng, còn G502 Hero phù hợp với gaming.</p>',
                'AnhMinhHoa' => 'https://via.placeholder.com/800x450/00D4FF/FFFFFF?text=Mouse+Comparison',
                'NgayDang' => Carbon::now()->subDays(10),
            ],
            [
                'MaNguoiDung' => 1,
                'TieuDe' => 'Hướng dẫn chọn bàn phím cơ cho người mới',
                'NoiDung' => '<p>Bàn phím cơ có 3 loại switch phổ biến: Red (linear, êm), Blue (clicky, click rõ), Brown (tactile, trung gian).</p><p>Người mới nên chọn Brown switch vì phù hợp cả gaming lẫn typing. Các thương hiệu như Keychron, Akko, Filco đều có sản phẩm chất lượng với giá hợp lý.</p>',
                'AnhMinhHoa' => 'https://via.placeholder.com/800x450/00C853/FFFFFF?text=Mechanical+Keyboard',
                'NgayDang' => Carbon::now()->subDays(15),
            ],
        ];

        foreach ($tinTuc as $tt) {
            TinTuc::create($tt);
        }
    }
}