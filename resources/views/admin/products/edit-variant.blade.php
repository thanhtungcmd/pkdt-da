@extends('admin.layouts.app')
@section('title', 'Sửa biến thể')
@section('page-title', 'Sửa biến thể')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.products.variants.update', [$sanPham->MaSanPham, $bienThe->MaCTSanPham]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Màu sắc *</label>
                            <input type="text" name="MauSac" class="form-control" 
                                   value="{{ old('MauSac', $bienThe->MauSac) }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dung lượng</label>
                            <input type="text" name="DungLuong" class="form-control" 
                                   value="{{ old('DungLuong', $bienThe->DungLuong) }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kích thước</label>
                        <input type="text" name="KichThuoc" class="form-control" 
                               value="{{ old('KichThuoc', $bienThe->KichThuoc) }}">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Đơn giá *</label>
                            <input type="number" name="DonGia" class="form-control" 
                                   value="{{ old('DonGia', $bienThe->DonGia) }}" min="0" step="1000" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số lượng tồn *</label>
                            <input type="number" name="SoLuongTon" class="form-control" 
                                   value="{{ old('SoLuongTon', $bienThe->SoLuongTon) }}" min="0" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ảnh minh họa *</label>

                        {{-- Ảnh hiện tại --}}
                        <img src="{{ asset('uploads/' . $bienThe->AnhMinhHoa) }}" 
                            class="img-thumbnail mb-2" 
                            style="max-width: 200px;">

                        {{-- Chọn ảnh mới (không bắt buộc) --}}
                        <input type="file" name="AnhMinhHoa" class="form-control" 
                            accept="image/*" onchange="previewImage(event)">

                        {{-- Preview ảnh mới (ẩn nếu chưa chọn) --}}
                        <img id="imagePreview" src="" class="img-thumbnail mt-2"
                            style="max-width: 200px; display: none;">
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
                            <option value="1" {{ $bienThe->TrangThai == 1 ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ $bienThe->TrangThai == 0 ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Cập nhật
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
@endsection