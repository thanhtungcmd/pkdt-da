@extends('admin.layouts.app')

@section('title', 'Sửa sản phẩm')
@section('page-title', 'Sửa sản phẩm')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.products.update', $sanPham->MaSanPham) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Danh mục *</label>
                        <select name="MaDanhMuc" class="form-select @error('MaDanhMuc') is-invalid @enderror" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($danhMuc as $dm)
                                <option value="{{ $dm->MaDanhMuc }}" 
                                    {{ old('MaDanhMuc', $sanPham->MaDanhMuc) == $dm->MaDanhMuc ? 'selected' : '' }}>
                                    {{ $dm->TenDanhMuc }}
                                </option>
                            @endforeach
                        </select>
                        @error('MaDanhMuc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tên sản phẩm *</label>
                        <input type="text" name="TenSanPham" 
                               class="form-control @error('TenSanPham') is-invalid @enderror" 
                               value="{{ old('TenSanPham', $sanPham->TenSanPham) }}" required>
                        @error('TenSanPham')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Thương hiệu</label>
                        <input type="text" name="ThuongHieu" class="form-control" 
                               value="{{ old('ThuongHieu', $sanPham->ThuongHieu) }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ảnh sản phẩm</label>
                        <input type="file" name="AnhChinh" 
                               class="form-control @error('AnhChinh') is-invalid @enderror" 
                               accept="image/*" onchange="previewImage(event)">
                        @error('AnhChinh')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <img id="imagePreview" src="{{ $sanPham->AnhChinh }}" class="img-thumbnail mt-2" style="max-width: 200px;">
                        <small class="text-muted d-block">Để trống nếu không đổi ảnh</small>
                    </div>
                    
                    <script>
                    function previewImage(event) {
                        const preview = document.getElementById('imagePreview');
                        preview.src = URL.createObjectURL(event.target.files[0]);
                    }
                    </script>
                    
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="MoTa" class="form-control" rows="4">{{ old('MoTa', $sanPham->MoTa) }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Trạng thái *</label>
                        <select name="TrangThai" class="form-select" required>
                            <option value="1" {{ $sanPham->TrangThai == 1 ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ $sanPham->TrangThai == 0 ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Cập nhật
                        </button>
                        <a href="{{ route('admin.products.variants', $sanPham->MaSanPham) }}" class="btn btn-info">
                            <i class="bi bi-palette"></i> Quản lý biến thể
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection