@extends('layouts.auth')

@section('title', 'Quên mật khẩu')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-key text-primary" style="font-size: 4rem;"></i>
                        <h3 class="mt-3">Quên Mật Khẩu?</h3>
                        <p class="text-muted">Nhập email để nhận mật khẩu tạm thời</p>
                    </div>
                    
                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                            <i class="bi bi-send"></i> Nhận mật khẩu tạm thời
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
</div>
@endsection