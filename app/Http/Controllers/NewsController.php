<?php
// app/Http/Controllers/NewsController.php

namespace App\Http\Controllers;

use App\Models\TinTuc;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    // Danh sách tin tức
    public function index()
    {
        $tinTuc = TinTuc::with('nguoiDung')
            ->latest('NgayDang')
            ->paginate(9);

        return view('news.index', compact('tinTuc'));
    }

    // Chi tiết tin tức
    public function show($id)
    {
        $tinTuc = TinTuc::with('nguoiDung')->findOrFail($id);

        // Tin tức liên quan
        $tinTucLienQuan = TinTuc::where('MaTinTuc', '!=', $id)
            ->latest('NgayDang')
            ->take(4)
            ->get();

        return view('news.show', compact('tinTuc', 'tinTucLienQuan'));
    }
}