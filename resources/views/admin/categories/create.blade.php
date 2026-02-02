@extends('admin.layouts.app')

@section('title', 'Thêm danh mục')
@section('page-title', 'Thêm danh mục mới')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục *</label>
                        <input type="text" name="TenDanhMuc" 
                               class="form-control @error('TenDanhMuc') is-invalid @enderror" 
                               value="{{ old('TenDanhMuc') }}" required>
                        @error('TenDanhMuc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="MoTa" class="form-control" rows="3">{{ old('MoTa') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ảnh danh mục *</label>
                        <input type="file" name="AnhMinhHoa" 
                               class="form-control @error('AnhMinhHoa') is-invalid @enderror" 
                               accept="image/*" required onchange="previewImage(event)">
                        @error('AnhMinhHoa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <img id="imagePreview" src="" class="img-thumbnail mt-2" style="max-width: 200px; display: none;">
                    </div>
                    <script>
                    function previewImage(event) {
                        const preview = document.getElementById('imagePreview');
                        preview.src = URL.createObjectURL(event.target.files[0]);
                        preview.style.display = 'block';
                    }
                    </script>
                    
                    <div class="mb-3">
                        <label class="form-label">Trạng thái *</label>
                        <select name="TrangThai" class="form-select" required>
                            <option value="1" selected>Hiển thị</option>
                            <option value="0">Ẩn</option>
                        </select>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Lưu
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