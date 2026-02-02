@extends('admin.layouts.app')

@section('title', 'Sửa danh mục')
@section('page-title', 'Sửa danh mục')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.categories.update', $danhMuc->MaDanhMuc) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục *</label>
                        <input type="text" name="TenDanhMuc" 
                               class="form-control @error('TenDanhMuc') is-invalid @enderror" 
                               value="{{ old('TenDanhMuc', $danhMuc->TenDanhMuc) }}" required>
                        @error('TenDanhMuc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ảnh sản phẩm</label>
                        <input type="file" name="AnhMinhHoa" 
                               class="form-control @error('AnhMinhHoa') is-invalid @enderror" 
                               accept="image/*" onchange="previewImage(event)">
                        @error('AnhMinhHoa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <img id="imagePreview" src="{{ $danhMuc->AnhMinhHoa }}" class="img-thumbnail mt-2" style="max-width: 200px;">
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
                        <textarea name="MoTa" class="form-control" rows="3">{{ old('MoTa', $danhMuc->MoTa) }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Trạng thái *</label>
                        <select name="TrangThai" class="form-select" required>
                            <option value="1" {{ $danhMuc->TrangThai == 1 ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ $danhMuc->TrangThai == 0 ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Cập nhật
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection