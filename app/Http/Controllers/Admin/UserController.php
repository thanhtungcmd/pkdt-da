<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Danh sách người dùng
    public function index(Request $request)
    {
        $query = NguoiDung::query();

        // Lọc theo vai trò
        if ($request->has('vai_tro') && $request->vai_tro != '') {
            $query->where('VaiTro', $request->vai_tro);
        }

        // Tìm kiếm
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('TenDangNhap', 'like', '%' . $request->search . '%')
                  ->orWhere('Email', 'like', '%' . $request->search . '%')
                  ->orWhere('HoTen', 'like', '%' . $request->search . '%');
            });
        }

        $nguoiDung = $query->paginate(15);

        return view('admin.users.index', compact('nguoiDung'));
    }

    // ==================== THÊM MỚI ====================
    // Form tạo người dùng mới
    public function create()
    {
        return view('admin.users.create');
    }

    // Lưu người dùng mới
    public function store(Request $request)
    {
        $request->validate([
            'TenDangNhap' => 'required|unique:nguoi_dung,TenDangNhap|max:50|regex:/^[a-zA-Z0-9_]+$/',
            'MatKhau' => 'required|min:6|confirmed',
            'HoTen' => 'required|max:100',
            'Email' => 'required|email|unique:nguoi_dung,Email',
            'SoDienThoai' => 'nullable|regex:/^[0-9]{10}$/',
            'DiaChi' => 'nullable|max:255',
            'VaiTro' => 'required|boolean',
            'TrangThai' => 'required|boolean',
        ], [
            'TenDangNhap.required' => 'Vui lòng nhập tên đăng nhập',
            'TenDangNhap.unique' => 'Tên đăng nhập đã tồn tại',
            'TenDangNhap.regex' => 'Tên đăng nhập chỉ chứa chữ, số và gạch dưới',
            'MatKhau.required' => 'Vui lòng nhập mật khẩu',
            'MatKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'MatKhau.confirmed' => 'Xác nhận mật khẩu không khớp',
            'HoTen.required' => 'Vui lòng nhập họ tên',
            'Email.required' => 'Vui lòng nhập email',
            'Email.email' => 'Email không đúng định dạng',
            'Email.unique' => 'Email đã được sử dụng',
            'SoDienThoai.regex' => 'Số điện thoại phải có 10 chữ số',
        ]);

        NguoiDung::create([
            'TenDangNhap' => $request->TenDangNhap,
            'MatKhau' => Hash::make($request->MatKhau),
            'HoTen' => $request->HoTen,
            'Email' => $request->Email,
            'SoDienThoai' => $request->SoDienThoai,
            'DiaChi' => $request->DiaChi,
            'VaiTro' => $request->VaiTro,
            'TrangThai' => $request->TrangThai,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Đã tạo người dùng mới thành công!');
    }
    // ==================== KẾT THÚC THÊM MỚI ====================

    // Form sửa
    public function edit($id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);
        return view('admin.users.edit', compact('nguoiDung'));
    }

    // Cập nhật người dùng
    public function update(Request $request, $id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);

        $request->validate([
            'HoTen' => 'required|max:100',
            'Email' => 'required|email|unique:nguoi_dung,Email,' . $id . ',MaNguoiDung',
            'SoDienThoai' => 'nullable|regex:/^[0-9]{10}$/',
            'DiaChi' => 'nullable|max:255',
            'VaiTro' => 'required|boolean',
            'TrangThai' => 'required|boolean',
        ]);

        $nguoiDung->update($request->only([
            'HoTen', 'Email', 'SoDienThoai', 'DiaChi', 'VaiTro', 'TrangThai'
        ]));

        return redirect()->route('admin.users.index')->with('success', 'Đã cập nhật người dùng!');
    }

    // Khóa/Mở khóa tài khoản
    public function toggleStatus($id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);
        $nguoiDung->update([
            'TrangThai' => !$nguoiDung->TrangThai,
        ]);

        $message = $nguoiDung->TrangThai ? 'Đã mở khóa tài khoản!' : 'Đã khóa tài khoản!';

        return back()->with('success', $message);
    }

    // Xóa người dùng
    public function destroy($id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);

        if ($nguoiDung->VaiTro == 1) {
            return back()->with('error', 'Không thể xóa tài khoản Admin!');
        }

        $nguoiDung->delete();

        return redirect()->route('admin.users.index')->with('success', 'Đã xóa người dùng!');
    }
}