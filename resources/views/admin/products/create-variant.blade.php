@extends('admin.layouts.app')
@section('title', 'Thêm biến thể')
@section('page-title', 'Thêm biến thể cho: ' . $sanPham->TenSanPham)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                {{-- QUAN TRỌNG: Thêm enctype="multipart/form-data" để upload file --}}
                <form action="{{ route('admin.products.variants.store', $sanPham->MaSanPham) }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf
                    
                    {{-- Hiển thị lỗi nếu có --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Màu sắc *</label>
                            <input type="text" name="MauSac" class="form-control @error('MauSac') is-invalid @enderror" 
                                   value="{{ old('MauSac') }}" placeholder="Đen, Trắng, Xanh..." required>
                            @error('MauSac')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dung lượng</label>
                            <input type="text" name="DungLuong" class="form-control @error('DungLuong') is-invalid @enderror" 
                                   value="{{ old('DungLuong') }}" placeholder="128GB, 256GB...">
                            @error('DungLuong')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kích thước</label>
                        <input type="text" name="KichThuoc" class="form-control @error('KichThuoc') is-invalid @enderror" 
                               value="{{ old('KichThuoc') }}" placeholder="S, M, L hoặc Red Switch, Blue Switch...">
                        @error('KichThuoc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Đơn giá *</label>
                            <input type="number" name="DonGia" class="form-control @error('DonGia') is-invalid @enderror" 
                                   value="{{ old('DonGia') }}" min="0" step="1000" required>
                            @error('DonGia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số lượng tồn *</label>
                            <input type="number" name="SoLuongTon" class="form-control @error('SoLuongTon') is-invalid @enderror" 
                                   value="{{ old('SoLuongTon', 0) }}" min="0" required>
                            @error('SoLuongTon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ảnh minh họa *</label>
                        <input type="file" name="AnhMinhHoa" class="form-control @error('AnhMinhHoa') is-invalid @enderror" 
                            accept="image/*" required onchange="previewImage(event)">
                        @error('AnhMinhHoa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <img id="imagePreview" src="" class="img-thumbnail mt-2" style="max-width: 200px; display: none;">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Trạng thái *</label>
                        <select name="TrangThai" class="form-select @error('TrangThai') is-invalid @enderror" required>
                            <option value="1" {{ old('TrangThai', '1') == '1' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ old('TrangThai') == '0' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                        @error('TrangThai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Lưu
                        </button>
                        <a href="{{ route('admin.products.variants', $sanPham->MaSanPham) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    }
}
</script>
@endsection