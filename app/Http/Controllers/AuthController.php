<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLogin()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ], [
            'login.required' => 'Vui lòng nhập tên đăng nhập hoặc email',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'Email' : 'TenDangNhap';
        
        $user = NguoiDung::where($loginField, $request->login)->first();

        if ($user && Hash::check($request->password, $user->MatKhau)) {
            if ($user->TrangThai == 0) {
                return back()->with('error', 'Tài khoản đã bị khóa!');
            }

            Auth::login($user);
            
            if ($user->VaiTro == 1) {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
            }
            
            return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
        }

        return back()->with('error', 'Tên đăng nhập hoặc mật khẩu không đúng!');
    }

    // Hiển thị form đăng ký
    public function showRegister()
    {
        return view('auth.register');
    }

    // Xử lý đăng ký
    public function register(Request $request)
    {
        $request->validate([
            'TenDangNhap' => 'required|unique:nguoi_dung,TenDangNhap|min:5|max:50',
            'Email' => 'required|email|unique:nguoi_dung,Email',
            'MatKhau' => 'required|min:6',
            'MatKhauXacNhan' => 'required|same:MatKhau',
            'HoTen' => 'required|max:100',
            'SoDienThoai' => 'nullable|regex:/^[0-9]{10}$/',
        ], [
            'TenDangNhap.required' => 'Vui lòng nhập tên đăng nhập',
            'TenDangNhap.unique' => 'Tên đăng nhập đã tồn tại',
            'TenDangNhap.min' => 'Tên đăng nhập tối thiểu 5 ký tự',
            'Email.required' => 'Vui lòng nhập email',
            'Email.email' => 'Email không hợp lệ',
            'Email.unique' => 'Email đã được sử dụng',
            'MatKhau.required' => 'Vui lòng nhập mật khẩu',
            'MatKhau.min' => 'Mật khẩu tối thiểu 6 ký tự',
            'MatKhauXacNhan.required' => 'Vui lòng xác nhận mật khẩu',
            'MatKhauXacNhan.same' => 'Mật khẩu xác nhận không khớp',
            'HoTen.required' => 'Vui lòng nhập họ tên',
            'SoDienThoai.regex' => 'Số điện thoại phải có 10 chữ số',
        ]);

        NguoiDung::create([
            'TenDangNhap' => $request->TenDangNhap,
            'Email' => $request->Email,
            'MatKhau' => Hash::make($request->MatKhau),
            'HoTen' => $request->HoTen,
            'SoDienThoai' => $request->SoDienThoai,
            'DiaChi' => $request->DiaChi,
            'VaiTro' => 0,
            'TrangThai' => 1,
        ]);

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }

    // Đăng xuất
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with('success', 'Đã đăng xuất!');
    }
}