<?php
// app/Http/Controllers/Admin/NewsController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TinTuc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\ImageHelper;

class NewsController extends Controller
{
    // Danh sách tin tức
    public function index()
    {
        $tinTuc = TinTuc::with('nguoiDung')->latest('NgayDang')->paginate(10);
        return view('admin.news.index', compact('tinTuc'));
    }

    // Form thêm mới
    public function create()
    {
        return view('admin.news.create');
    }

    // Lưu tin tức mới
    public function store(Request $request)
    {
        $request->validate([
            'TieuDe' => 'required|unique:tin_tuc,TieuDe|max:255',
            'NoiDung' => 'required',
            'AnhMinhHoa' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'TieuDe.required' => 'Vui lòng nhập tiêu đề',
            'TieuDe.unique' => 'Tiêu đề đã tồn tại',
            'NoiDung.required' => 'Vui lòng nhập nội dung',
            'AnhMinhHoa.required' => 'Vui lòng chọn ảnh minh họa',
            'AnhMinhHoa.image' => 'Ảnh minh họa phải là tệp hình ảnh',
            'AnhMinhHoa.mimes' => 'Ảnh minh họa phải có định dạng jpeg, png, jpg hoặc gif',
            'AnhMinhHoa.max' => 'Ảnh minh họa không được vượt quá 2MB',
        ]);
    
        $data = $request->except('AnhMinhHoa');

        if ($request->hasFile('AnhMinhHoa')) {
            $file = $request->file('AnhMinhHoa');
            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images/news'), $fileName);
            $data['AnhMinhHoa'] = '/images/news/'.$fileName;
        }

        $data['MaNguoiDung'] = Auth::id();
        $data['NgayDang'] = Carbon::now();

        TinTuc::create($data);

        return redirect()->route('admin.news.index')->with('success', 'Đã thêm tin tức!');
    }


    // Form sửa
    public function edit($id)
    {
        $tinTuc = TinTuc::findOrFail($id);
        return view('admin.news.edit', compact('tinTuc'));
    }

    // Cập nhật tin tức
    public function update(Request $request, $id)
    {
        $tinTuc = TinTuc::findOrFail($id);

        $request->validate([
            'TieuDe' => 'required|max:255|unique:tin_tuc,TieuDe,' . $id . ',MaTinTuc',
            'NoiDung' => 'required',
            'AnhMinhHoa' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = $request->except('AnhMinhHoa');
        
        // Upload ảnh mới
        if ($request->hasFile('AnhMinhHoa')) {
            // Xóa ảnh cũ
            if ($tinTuc->AnhMinhHoa && file_exists(public_path($tinTuc->AnhMinhHoa))) {
                unlink(public_path($tinTuc->AnhMinhHoa));
            }
            
            $file = $request->file('AnhMinhHoa');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/news'), $fileName);
            $data['AnhMinhHoa'] = '/images/news/' . $fileName;
        }

        $tinTuc->update($data);

        return redirect()->route('admin.news.index')->with('success', 'Đã cập nhật tin tức!');
    }

    // Xóa tin tức
    public function destroy($id)
    {
        $tinTuc = TinTuc::findOrFail($id);
        $tinTuc->delete();

        return redirect()->route('admin.news.index')->with('success', 'Đã xóa tin tức!');
    }
}