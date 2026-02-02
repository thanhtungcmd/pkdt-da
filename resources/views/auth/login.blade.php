@extends('layouts.auth')

@section('title', 'Đăng nhập')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <img src="/images/logo.png" alt="TechBox Shop" style="max-height: 80px; width: auto; object-fit: contain;">
                        <h3 class="mt-3">Đăng nhập</h3>
                        <p class="text-muted">Chào mừng bạn quay trở lại!</p>
                    </div>
                    
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-person-fill"></i> Tên đăng nhập hoặc Email
                            </label>
                            <input type="text" name="login" class="form-control @error('login') is-invalid @enderror" 
                                   value="{{ old('login') }}" required autofocus>
                            @error('login')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-lock-fill"></i> Mật khẩu
                            </label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="text-end mt-2">
                                <a href="{{ route('password.request') }}" class="text-decoration-none">
                                    Quên mật khẩu?
                                </a>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ghi nhớ đăng nhập
                            </label>
                        </div>

                        
                        <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                            <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                        </button>
                        
                        <div class="text-center">
                            <p class="text-muted">Chưa có tài khoản? 
                                <a href="{{ route('register') }}" class="text-decoration-none">Đăng ký ngay</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Test accounts -->
            <div class="alert alert-info mt-3">
                <strong><i class="bi bi-info-circle"></i> Tài khoản test:</strong><br>
                <small>
                    Admin: <code>admin / admin123</code><br>
                    Khách: <code>khach1 / 123456</code>
                </small>
            </div>
        </div>
    </div>
</div>
@endsection