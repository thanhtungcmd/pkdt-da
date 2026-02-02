@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">
        <i class="bi bi-person-circle"></i> Thông Tin Cá Nhân
    </h2>
    
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5><i class="bi bi-person-fill"></i> Cập nhật thông tin</h5>
                    <hr>
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3 text-center">
                            <img src="{{ $nguoiDung->AnhDaiDien ?? 'https://via.placeholder.com/150' }}" 
                                 alt="Avatar" class="rounded-circle mb-3" 
                                 style="width: 150px; height: 150px; object-fit: cover;" id="avatarPreview">
                            <div>
                                <label for="AnhDaiDien" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-camera"></i> Chọn ảnh
                                </label>
                                <input type="file" name="AnhDaiDien" id="AnhDaiDien" 
                                       class="d-none" accept="image/*" onchange="previewAvatar(event)">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" value="{{ $nguoiDung->TenDangNhap }}" disabled>
                        </div>
                        
                        <script>
                        function previewAvatar(event) {
                            const reader = new FileReader();
                            reader.onload = function(){
                                document.getElementById('avatarPreview').src = reader.result;
                            }
                            reader.readAsDataURL(event.target.files[0]);
                        }
                        </script>
                        
                        <div class="mb-3">
                            <label class="form-label">Họ tên *</label>
                            <input type="text" name="HoTen" class="form-control @error('HoTen') is-invalid @enderror" 
                                   value="{{ old('HoTen', $nguoiDung->HoTen) }}" required>
                            @error('HoTen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="Email" class="form-control @error('Email') is-invalid @enderror" 
                                   value="{{ old('Email', $nguoiDung->Email) }}" required>
                            @error('Email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="SoDienThoai" class="form-control @error('SoDienThoai') is-invalid @enderror" 
                                   value="{{ old('SoDienThoai', $nguoiDung->SoDienThoai) }}">
                            @error('SoDienThoai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <textarea name="DiaChi" class="form-control" rows="2">{{ old('DiaChi', $nguoiDung->DiaChi) }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Cập nhật
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5><i class="bi bi-lock-fill"></i> Đổi mật khẩu</h5>
                    <hr>
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu cũ *</label>
                            <input type="password" name="MatKhauCu" class="form-control @error('MatKhauCu') is-invalid @enderror" required>
                            @error('MatKhauCu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu mới *</label>
                            <input type="password" name="MatKhauMoi" class="form-control @error('MatKhauMoi') is-invalid @enderror" required>
                            @error('MatKhauMoi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Xác nhận mật khẩu *</label>
                            <input type="password" name="XacNhanMatKhau" class="form-control @error('XacNhanMatKhau') is-invalid @enderror" required>
                            @error('XacNhanMatKhau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-key"></i> Đổi mật khẩu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection