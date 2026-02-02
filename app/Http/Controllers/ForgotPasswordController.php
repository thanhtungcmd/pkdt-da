<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // Hiển thị form quên mật khẩu
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Xử lý gửi mật khẩu tạm thời (offline)
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:nguoi_dung,Email',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.exists' => 'Email không tồn tại trong hệ thống',
        ]);

        $tempPassword = Str::random(8); // tạo mật khẩu tạm thời

        // Cập nhật mật khẩu tạm thời
        NguoiDung::where('Email', $request->email)
            ->update(['MatKhau' => Hash::make($tempPassword)]);

        // Hiển thị mật khẩu tạm thời (offline)
        return view('auth.show-temp-password', [
            'email' => $request->email,
            'tempPassword' => $tempPassword
        ]);
    }

    // Hiển thị form reset mật khẩu (nếu muốn dùng token)
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Xử lý reset mật khẩu
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:nguoi_dung,Email',
            'password' => 'required|min:6|confirmed',
        ]);

        NguoiDung::where('Email', $request->email)
            ->update(['MatKhau' => Hash::make($request->password)]);

        return redirect()->route('login')->with('success', 'Đã đổi mật khẩu thành công!');
    }
}