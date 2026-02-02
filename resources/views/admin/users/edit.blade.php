@extends('admin.layouts.app')

@section('title', 'Sửa người dùng')
@section('page-title', 'Sửa người dùng')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.users.update', $nguoiDung->MaNguoiDung) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Tên đăng nhập</label>
                        <input type="text" class="form-control" value="{{ $nguoiDung->TenDangNhap }}" disabled>
                        <small class="text-muted">Không thể thay đổi tên đăng nhập</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Họ tên *</label>
                        <input type="text" name="HoTen" 
                               class="form-control @error('HoTen') is-invalid @enderror" 
                               value="{{ old('HoTen', $nguoiDung->HoTen) }}" required>
                        @error('HoTen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="Email" 
                               class="form-control @error('Email') is-invalid @enderror" 
                               value="{{ old('Email', $nguoiDung->Email) }}" required>
                        @error('Email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="SoDienThoai" 
                               class="form-control @error('SoDienThoai') is-invalid @enderror" 
                               value="{{ old('SoDienThoai', $nguoiDung->SoDienThoai) }}">
                        @error('SoDienThoai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <textarea name="DiaChi" class="form-control" rows="2">{{ old('DiaChi', $nguoiDung->DiaChi) }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vai trò *</label>
                            <select name="VaiTro" class="form-select" required>
                                <option value="0" {{ $nguoiDung->VaiTro == 0 ? 'selected' : '' }}>Khách hàng</option>
                                <option value="1" {{ $nguoiDung->VaiTro == 1 ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Trạng thái *</label>
                            <select name="TrangThai" class="form-select" required>
                                <option value="1" {{ $nguoiDung->TrangThai == 1 ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ $nguoiDung->TrangThai == 0 ? 'selected' : '' }}>Khóa</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Cập nhật
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection