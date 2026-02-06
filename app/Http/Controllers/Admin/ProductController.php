<?php
// app/Http/Controllers/Admin/ProductController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use App\Models\CTSanPham;
use App\Models\DanhMuc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Danh sách sản phẩm
    public function index()
    {
        $sanPham = SanPham::with(['danhMuc', 'bienThe'])->paginate(10);
        return view('admin.products.index', compact('sanPham'));
    }

    // Form thêm mới
    public function create()
    {
        $danhMuc = DanhMuc::hienThi()->get();
        return view('admin.products.create', compact('danhMuc'));
    }

    // Lưu sản phẩm mới
    public function store(Request $request)
    {
        $request->validate([
            'MaDanhMuc' => 'required|exists:danh_muc,MaDanhMuc',
            'TenSanPham' => 'required|max:255',
            'ThuongHieu' => 'nullable|max:100',
            'AnhChinh' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'MoTa' => 'nullable',
            'TrangThai' => 'required|boolean',
        ], [
            'TenSanPham.required' => 'Vui lòng nhập tên sản phẩm',
            'AnhChinh.required' => 'Vui lòng chọn ảnh sản phẩm',
            'AnhChinh.image' => 'File phải là ảnh',
            'AnhChinh.max' => 'Ảnh không được quá 2MB',
        ]);

        $data = $request->except('AnhChinh');
        
        // Upload ảnh chính
        if ($request->hasFile('AnhChinh')) {
            $file = $request->file('AnhChinh');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/products'), $fileName);
            $data['AnhChinh'] = '/images/products/' . $fileName;
        }

        SanPham::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Đã thêm sản phẩm!');
    }

    // Form sửa
    public function edit($id)
    {
        $sanPham = SanPham::with('bienThe')->findOrFail($id);
        $danhMuc = DanhMuc::hienThi()->get();
        return view('admin.products.edit', compact('sanPham', 'danhMuc'));
    }

    // Cập nhật sản phẩm
    public function update(Request $request, $id)
{
    $sanPham = SanPham::findOrFail($id);

    $request->validate([
        'MaDanhMuc' => 'required|exists:danh_muc,MaDanhMuc',
        'TenSanPham' => 'required|max:255',
        'ThuongHieu' => 'nullable|max:100',
        'AnhChinh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'MoTa' => 'nullable',
        'TrangThai' => 'required|boolean',
    ]);

    $data = $request->all();

    if ($request->hasFile('AnhChinh')) {
        // Xóa ảnh cũ
        if ($sanPham->AnhChinh && file_exists(public_path($sanPham->AnhChinh))) {
            unlink(public_path($sanPham->AnhChinh));
        }

        $file = $request->file('AnhChinh');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/products'), $fileName);
        $data['AnhChinh'] = '/images/products/' . $fileName;
    } else {
        // Nếu không upload ảnh → giữ ảnh cũ
        $data['AnhChinh'] = $sanPham->AnhChinh;
    }

    $sanPham->update($data);

    return redirect()->route('admin.products.index')->with('success', 'Đã cập nhật sản phẩm!');
}


    // Xóa sản phẩm
    public function destroy($id)
    {
        $sanPham = SanPham::findOrFail($id);
        $sanPham->delete();

        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm!');
    }

    // Quản lý biến thể
    public function variants($id)
    {
        $sanPham = SanPham::with('bienThe')->findOrFail($id);
        return view('admin.products.variants', compact('sanPham'));
    }

    // Form thêm biến thể
    public function createVariant($id)
    {
        $sanPham = SanPham::findOrFail($id);
        return view('admin.products.create-variant', compact('sanPham'));
    }

    // Lưu biến thể mới
    public function storeVariant(Request $request, $id)
    {
        $request->validate([
            'MauSac' => 'required|max:100',
            'DungLuong' => 'nullable|max:100',
            'KichThuoc' => 'nullable|max:100',
            'DonGia' => 'required|numeric|min:0',
            'SoLuongTon' => 'required|integer|min:0',
            'AnhMinhHoa' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'TrangThai' => 'required|boolean',
        ]);
        
        $data = $request->except('AnhMinhHoa');
        
        // Upload ảnh
        if ($request->hasFile('AnhMinhHoa')) {
            $file = $request->file('AnhMinhHoa');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/variants'), $fileName);
            $data['AnhMinhHoa'] = '/images/variants/' . $fileName;
        }
        
        $data['MaSanPham'] = $id;
        CTSanPham::create($data);

        return redirect()->route('admin.products.variants', $id)->with('success', 'Đã thêm biến thể!');
    }

    // Form sửa biến thể
    public function editVariant($productId, $variantId)
    {
        $sanPham = SanPham::findOrFail($productId);
        $bienThe = CTSanPham::findOrFail($variantId);
        return view('admin.products.edit-variant', compact('sanPham', 'bienThe'));
    }

    // Cập nhật biến thể
    public function updateVariant(Request $request, $productId, $variantId)
{
    $bienThe = CTSanPham::findOrFail($variantId);
    
    $request->validate([
        'MauSac' => 'required|max:100',
        'DungLuong' => 'nullable|max:100',
        'KichThuoc' => 'nullable|max:100',
        'DonGia' => 'required|numeric|min:0',
        'SoLuongTon' => 'required|integer|min:0',
        'AnhMinhHoa' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'TrangThai' => 'required|boolean',
    ]);

    $data = $request->all();

    // Nếu có ảnh mới thì upload
    if ($request->hasFile('AnhMinhHoa')) {

        // Xóa ảnh cũ
        if ($bienThe->AnhMinhHoa && file_exists(public_path($bienThe->AnhMinhHoa))) {
            unlink(public_path($bienThe->AnhMinhHoa));
        }

        $file = $request->file('AnhMinhHoa');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/variants'), $fileName);

        $data['AnhMinhHoa'] = '/images/variants/' . $fileName;
    } else {
        // Không upload ảnh → giữ ảnh cũ
        $data['AnhMinhHoa'] = $bienThe->AnhMinhHoa;
    }

    $bienThe->update($data);

    return redirect()->route('admin.products.variants', $productId)->with('success', 'Đã cập nhật biến thể!');
}


    // Xóa biến thể
    public function destroyVariant($productId, $variantId)
    {
        $bienThe = CTSanPham::findOrFail($variantId);
        $bienThe->delete();

        return redirect()->route('admin.products.variants', $productId)->with('success', 'Đã xóa biến thể!');
    }
}