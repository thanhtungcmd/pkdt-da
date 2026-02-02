@extends('admin.layouts.app')

@section('title', 'Tạo người dùng mới')
@section('page-title', 'Tạo người dùng mới')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Tên đăng nhập *</label>
                        <input type="text" name="TenDangNhap" 
                               class="form-control @error('TenDangNhap') is-invalid @enderror" 
                               value="{{ old('TenDangNhap') }}" 
                               placeholder="Chỉ chứa chữ, số và gạch dưới" required>
                        @error('TenDangNhap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Tên đăng nhập không thể thay đổi sau khi tạo</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mật khẩu *</label>
                            <input type="password" name="MatKhau" 
                                   class="form-control @error('MatKhau') is-invalid @enderror" 
                                   placeholder="Tối thiểu 6 ký tự" required>
                            @error('MatKhau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Xác nhận mật khẩu *</label>
                            <input type="password" name="MatKhau_confirmation" 
                                   class="form-control @error('MatKhau') is-invalid @enderror" 
                                   placeholder="Nhập lại mật khẩu" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Họ tên *</label>
                        <input type="text" name="HoTen" 
                               class="form-control @error('HoTen') is-invalid @enderror" 
                               value="{{ old('HoTen') }}" required>
                        @error('HoTen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="Email" 
                               class="form-control @error('Email') is-invalid @enderror" 
                               value="{{ old('Email') }}" required>
                        @error('Email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="SoDienThoai" 
                               class="form-control @error('SoDienThoai') is-invalid @enderror" 
                               value="{{ old('SoDienThoai') }}"
                               placeholder="Nhập 10 chữ số">
                        @error('SoDienThoai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <textarea name="DiaChi" class="form-control" rows="2">{{ old('DiaChi') }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vai trò *</label>
                            <select name="VaiTro" class="form-select" required>
                                <option value="0" {{ old('VaiTro') == '0' ? 'selected' : '' }}>Khách hàng</option>
                                <option value="1" {{ old('VaiTro') == '1' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Trạng thái *</label>
                            <select name="TrangThai" class="form-select" required>
                                <option value="1" {{ old('TrangThai', '1') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('TrangThai') == '0' ? 'selected' : '' }}>Khóa</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tạo người dùng
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-info-circle"></i> Lưu ý</h6>
                <ul class="small mb-0">
                    <li>Tên đăng nhập không thể thay đổi sau khi tạo</li>
                    <li>Mật khẩu mặc định có thể thay đổi sau</li>
                    <li>Email phải là duy nhất trong hệ thống</li>
                    <li>Số điện thoại phải có đúng 10 chữ số</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection