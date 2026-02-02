<?php
// app/Http/Controllers/FeedbackController.php

namespace App\Http\Controllers;

use App\Models\PhanHoi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Hiển thị form phản hồi
    public function create()
    {
        return view('feedback.create');
    }

    // Gửi phản hồi
    public function store(Request $request)
    {
        $request->validate([
            'TieuDe' => 'required|max:255',
            'NoiDung' => 'required|min:20',
        ], [
            'TieuDe.required' => 'Vui lòng nhập tiêu đề',
            'NoiDung.required' => 'Vui lòng nhập nội dung',
            'NoiDung.min' => 'Nội dung tối thiểu 20 ký tự',
        ]);

        PhanHoi::create([
            'MaNguoiDung' => Auth::id(),
            'TieuDe' => $request->TieuDe,
            'NoiDung' => $request->NoiDung,
            'NgayGui' => Carbon::now(),
            'TrangThai' => 0,
        ]);

        return redirect()->route('home')->with('success', 'Cảm ơn bạn đã gửi phản hồi!');
    }
}