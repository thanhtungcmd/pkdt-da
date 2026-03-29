@extends('admin.layouts.app')

@section('title', 'Thêm sản phẩm')
@section('page-title', 'Thêm sản phẩm mới')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Danh mục *</label>
                        <select name="MaDanhMuc" class="form-select @error('MaDanhMuc') is-invalid @enderror" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($danhMuc as $dm)
                                <option value="{{ $dm->MaDanhMuc }}" {{ old('MaDanhMuc') == $dm->MaDanhMuc ? 'selected' : '' }}>
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
                               value="{{ old('TenSanPham') }}" required>
                        @error('TenSanPham')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Thương hiệu</label>
                        <input type="text" name="ThuongHieu" class="form-control" 
                               value="{{ old('ThuongHieu') }}" placeholder="Apple, Samsung, Logitech...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ảnh sản phẩm *</label>
                        <input type="file" name="AnhChinh" 
                               class="form-control @error('AnhChinh') is-invalid @enderror" 
                               accept="image/*" required onchange="previewImage(event)">
                        @error('AnhChinh')
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
                        <label class="form-label">Thông số kỹ thuật</label>
                        <div id="specs-wrapper">
                            <div class="row mb-2 spec-item">
                                <div class="col-md-5">
                                    <input type="text" name="spec_key[]" class="form-control" placeholder="Ví dụ: Kích thước">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="spec_value[]" class="form-control" placeholder="Ví dụ: 10 x 20 x 5 cm">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger w-100" onclick="removeSpec(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary mt-2" onclick="addSpec()">
                            <i class="bi bi-plus-circle"></i> Thêm thông số
                        </button>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="MoTa" class="form-control" rows="4">{{ old('MoTa') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Trạng thái *</label>
                        <select name="TrangThai" class="form-select" required>
                            <option value="1" selected>Hiển thị</option>
                            <option value="0">Ẩn</option>
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Sau khi tạo sản phẩm, bạn cần thêm biến thể (màu sắc, giá...)
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Lưu
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function addSpec() {
    const html = `
    <div class="row mb-2 spec-item">
        <div class="col-md-5">
            <input type="text" name="spec_key[]" class="form-control" placeholder="Tên thông số">
        </div>
        <div class="col-md-5">
            <input type="text" name="spec_value[]" class="form-control" placeholder="Giá trị">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger w-100" onclick="removeSpec(this)">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>`;
    document.getElementById('specs-wrapper').insertAdjacentHTML('beforeend', html);
}

function removeSpec(btn) {
    btn.closest('.spec-item').remove();
}
</script>
@endsection