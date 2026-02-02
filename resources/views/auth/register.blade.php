@extends('layouts.auth')

@section('title', 'Đăng ký')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    {{-- THÊM LOGO --}}
                    <img src="/images/logo.png" alt="TechBox Shop" style="max-height: 80px; width: auto; object-fit: contain;">
                    <h3 class="mt-3">Đăng ký tài khoản</h3>
                    <p class="text-muted">Tạo tài khoản để mua sắm dễ dàng hơn</p>
                </div>
                
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-person-fill"></i> Tên đăng nhập *
                            </label>
                            <input type="text" name="TenDangNhap" class="form-control @error('TenDangNhap') is-invalid @enderror" 
                                   value="{{ old('TenDangNhap') }}" required>
                            @error('TenDangNhap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-envelope-fill"></i> Email *
                            </label>
                            <input type="email" name="Email" class="form-control @error('Email') is-invalid @enderror" 
                                   value="{{ old('Email') }}" required>
                            @error('Email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-person-badge-fill"></i> Họ tên *
                        </label>
                        <input type="text" name="HoTen" class="form-control @error('HoTen') is-invalid @enderror" 
                               value="{{ old('HoTen') }}" required>
                        @error('HoTen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-lock-fill"></i> Mật khẩu *
                            </label>
                            <input type="password" name="MatKhau" class="form-control @error('MatKhau') is-invalid @enderror" required>
                            @error('MatKhau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-lock-fill"></i> Xác nhận mật khẩu *
                            </label>
                            <input type="password" name="MatKhauXacNhan" class="form-control @error('MatKhauXacNhan') is-invalid @enderror" required>
                            @error('MatKhauXacNhan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-telephone-fill"></i> Số điện thoại
                        </label>
                        <input type="text" name="SoDienThoai" class="form-control @error('SoDienThoai') is-invalid @enderror" 
                               value="{{ old('SoDienThoai') }}" placeholder="0901234567">
                        @error('SoDienThoai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-geo-alt-fill"></i> Địa chỉ
                        </label>
                        <textarea name="DiaChi" class="form-control" rows="2">{{ old('DiaChi') }}</textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                        <i class="bi bi-person-plus-fill"></i> Đăng ký
                    </button>
                    
                    <div class="text-center">
                        <p class="text-muted">Đã có tài khoản? 
                            <a href="{{ route('login') }}" class="text-decoration-none">Đăng nhập ngay</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection