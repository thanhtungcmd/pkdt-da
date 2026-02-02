@extends('layouts.auth')

@section('title', 'Đặt lại mật khẩu')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    {{-- GIỮ ICON CHÌA KHÓA - PHÙ HỢP VỚI BẢO MẬT --}}
                    <i class="bi bi-shield-lock-fill text-primary" style="font-size: 4rem;"></i>
                    <h3 class="mt-3">Đặt Lại Mật Khẩu</h3>
                    <p class="text-muted">Nhập mật khẩu mới của bạn</p>
                </div>
                
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-envelope-fill"></i> Email *
                        </label>
                        <input type="email" name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-lock-fill"></i> Mật khẩu mới *
                        </label>
                        <input type="password" name="password" 
                               class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-lock-fill"></i> Xác nhận mật khẩu *
                        </label>
                        <input type="password" name="password_confirmation" 
                               class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                        <i class="bi bi-check-circle"></i> Đặt lại mật khẩu
                    </button>
                    
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="bi bi-arrow-left"></i> Quay lại đăng nhập
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection