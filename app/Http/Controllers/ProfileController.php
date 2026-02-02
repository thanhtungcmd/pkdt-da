<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Hiển thị trang cập nhật thông tin
    public function edit()
    {
        $nguoiDung = Auth::user();
        return view('profile.edit', compact('nguoiDung'));
    }

    // Cập nhật thông tin
    public function update(Request $request)
    {
        $nguoiDung = Auth::user();

        $request->validate([
            'HoTen' => 'required|max:100',
            'Email' => 'required|email|unique:nguoi_dung,Email,' . $nguoiDung->MaNguoiDung . ',MaNguoiDung',
            'SoDienThoai' => 'nullable|regex:/^[0-9]{10}$/',
            'DiaChi' => 'nullable|max:255',
            'AnhDaiDien' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'HoTen.required' => 'Vui lòng nhập họ tên',
            'Email.required' => 'Vui lòng nhập email',
            'Email.email' => 'Email không hợp lệ',
            'Email.unique' => 'Email đã được sử dụng',
            'SoDienThoai.regex' => 'Số điện thoại phải có 10 chữ số',
            'AnhDaiDien.image' => 'File phải là ảnh',
            'AnhDaiDien.max' => 'Ảnh không được quá 2MB',
        ]);

        $data = [
            'HoTen' => $request->HoTen,
            'Email' => $request->Email,
            'SoDienThoai' => $request->SoDienThoai,
            'DiaChi' => $request->DiaChi,
        ];

        // Upload ảnh đại diện
        if ($request->hasFile('AnhDaiDien')) {
            // Xóa ảnh cũ
            if ($nguoiDung->AnhDaiDien && file_exists(public_path($nguoiDung->AnhDaiDien))) {
                unlink(public_path($nguoiDung->AnhDaiDien));
            }
            
            $file = $request->file('AnhDaiDien');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/avatars'), $fileName);
            $data['AnhDaiDien'] = '/images/avatars/' . $fileName;
        }

        $nguoiDung->update($data);

        return back()->with('success', 'Đã cập nhật thông tin!');
    }

    // Đổi mật khẩu
    public function changePassword(Request $request)
    {
        $request->validate([
            'MatKhauCu' => 'required',
            'MatKhauMoi' => 'required|min:6',
            'XacNhanMatKhau' => 'required|same:MatKhauMoi',
        ], [
            'MatKhauCu.required' => 'Vui lòng nhập mật khẩu cũ',
            'MatKhauMoi.required' => 'Vui lòng nhập mật khẩu mới',
            'MatKhauMoi.min' => 'Mật khẩu mới tối thiểu 6 ký tự',
            'XacNhanMatKhau.required' => 'Vui lòng xác nhận mật khẩu',
            'XacNhanMatKhau.same' => 'Mật khẩu xác nhận không khớp',
        ]);

        $nguoiDung = Auth::user();

        if (!Hash::check($request->MatKhauCu, $nguoiDung->MatKhau)) {
            return back()->with('error', 'Mật khẩu cũ không đúng!');
        }

        $nguoiDung->update([
            'MatKhau' => Hash::make($request->MatKhauMoi),
        ]);

        return back()->with('success', 'Đã đổi mật khẩu!');
    }
}