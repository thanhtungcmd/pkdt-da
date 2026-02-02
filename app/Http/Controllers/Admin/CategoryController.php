<?php
// app/Http/Controllers/Admin/CategoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhMuc;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Danh sách danh mục
    public function index()
    {
        $danhMuc = DanhMuc::withCount('sanPham')->paginate(10);
        return view('admin.categories.index', compact('danhMuc'));
    }

    // Form thêm mới
    public function create()
    {
        return view('admin.categories.create');
    }

    // Lưu danh mục mới
    public function store(Request $request)
    {
        $request->validate([
            'TenDanhMuc' => 'required|unique:danh_muc,TenDanhMuc|max:100',
            'MoTa' => 'nullable|max:255',
            'TrangThai' => 'required|boolean',
            'AnhMinhHoa' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'TenDanhMuc.required' => 'Vui lòng nhập tên danh mục',
            'TenDanhMuc.unique' => 'Tên danh mục đã tồn tại',
            'AnhMinhHoa.required' => 'Vui lòng chọn ảnh sản phẩm',
            'AnhMinhHoa.image' => 'File phải là ảnh',
            'AnhMinhHoa.max' => 'Ảnh không được quá 2MB',
        ]);

        $data = $request->except('AnhMinhHoa');

        // Upload ảnh chính
        if ($request->hasFile('AnhMinhHoa')) {
            $file = $request->file('AnhMinhHoa');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/categories'), $fileName);
            $data['AnhMinhHoa'] = '/images/categories/' . $fileName;
        }

        DanhMuc::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Đã thêm danh mục!');
    }

    // Form sửa
    public function edit($id)
    {
        $danhMuc = DanhMuc::findOrFail($id);
        return view('admin.categories.edit', compact('danhMuc'));
    }

    // Cập nhật danh mục
    public function update(Request $request, $id)
    {
        $danhMuc = DanhMuc::findOrFail($id);

        $request->validate([
            'TenDanhMuc' => 'required|max:100|unique:danh_muc,TenDanhMuc,' . $id . ',MaDanhMuc',
            'MoTa' => 'nullable|max:255',
            'TrangThai' => 'required|boolean',
            'AnhMinhHoa' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'TenDanhMuc.required' => 'Vui lòng nhập tên danh mục',
            'TenDanhMuc.unique' => 'Tên danh mục đã tồn tại',
        ]);

        $data = $request->all();

        if ($request->hasFile('AnhMinhHoa')) {
            // Xóa ảnh cũ
            if ($danhMuc->AnhMinhHoa && file_exists(public_path($danhMuc->AnhMinhHoa))) {
                unlink(public_path($danhMuc->AnhMinhHoa));
            }

            $file = $request->file('AnhMinhHoa');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/products'), $fileName);
            $data['AnhMinhHoa'] = '/images/products/' . $fileName;
        } else {
            // Nếu không upload ảnh → giữ ảnh cũ
            $data['AnhMinhHoa'] = $danhMuc->AnhMinhHoa;
        }

        $danhMuc->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Đã cập nhật danh mục!');
    }

    // Xóa danh mục
    public function destroy($id)
    {
        $danhMuc = DanhMuc::findOrFail($id);

        if ($danhMuc->sanPham()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục có sản phẩm!');
        }

        $danhMuc->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa danh mục!');
    }
}