@extends('layouts.app')

@section('title', 'Đánh giá sản phẩm')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-star-fill text-warning"></i> Đánh giá sản phẩm
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('ratings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="MaSanPham" value="{{ $maSanPham }}">
                        <input type="hidden" name="MaDonHang" value="{{ $donHang->MaDonHang }}">
                        
                        <!-- Thông tin đơn hàng -->
                        <div class="alert alert-info mb-4 alert-persistent">
                            <strong><i class="bi bi-receipt"></i> Đơn hàng:</strong> #{{ $donHang->MaDonHang }}<br>
                            <strong><i class="bi bi-calendar"></i> Ngày mua:</strong> {{ $donHang->NgayDat->format('d/m/Y') }}
                        </div>
                        
                        <!-- Chọn số sao -->
                        <div class="mb-4 text-center">
                            <label class="form-label fw-bold fs-5">
                                <i class="bi bi-star"></i> Đánh giá của bạn
                            </label>
                            <div class="star-rating">
                                @for($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="SoSao" value="{{ $i }}" required>
                                <label for="star{{ $i }}" title="{{ $i }} sao">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                                @endfor
                            </div>
                            @error('SoSao')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Nội dung đánh giá -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-chat-text"></i> Nhận xét của bạn (Không bắt buộc)
                            </label>
                            <textarea name="NoiDung" class="form-control @error('NoiDung') is-invalid @enderror" 
                                      rows="5" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..."></textarea>
                            @error('NoiDung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tối thiểu 10 ký tự, tối đa 1000 ký tự</small>
                        </div>
                        
                        <!-- Upload ảnh -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-image"></i> Thêm ảnh (Không bắt buộc)
                            </label>
                            <input type="file" name="HinhAnh[]" class="form-control @error('HinhAnh.*') is-invalid @enderror" 
                                   multiple accept="image/jpeg,image/jpg,image/png" id="imageInput" onchange="previewImages()">
                            @error('HinhAnh.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tối đa 5 ảnh, mỗi ảnh không quá 2MB (jpeg, jpg, png)</small>
                            
                            <!-- Preview ảnh -->
                            <div id="imagePreview" class="mt-3 row g-2"></div>
                        </div>
                        
                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="bi bi-send-fill"></i> Gửi đánh giá
                            </button>
                            <a href="{{ route('products.show', $maSanPham) }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Star Rating CSS */
.star-rating {
    direction: rtl;
    display: inline-flex;
    font-size: 3rem;
}

.star-rating input[type="radio"] {
    display: none;
}

.star-rating label {
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
    margin: 0 5px;
}

.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input[type="radio"]:checked ~ label {
    color: #ffc107;
}

.image-preview-item {
    position: relative;
}

.image-preview-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
}

.remove-image {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(255, 0, 0, 0.8);
    color: white;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    cursor: pointer;
}
</style>

<script>
function previewImages() {
    const input = document.getElementById('imageInput');
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    if (input.files.length > 5) {
        alert('Chỉ được chọn tối đa 5 ảnh!');
        input.value = '';
        return;
    }
    
    Array.from(input.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const col = document.createElement('div');
            col.className = 'col-4 col-md-2';
            col.innerHTML = `
                <div class="image-preview-item">
                    <img src="${e.target.result}" alt="Preview ${index + 1}">
                    <button type="button" class="remove-image" onclick="removeImage(${index})">×</button>
                </div>
            `;
            preview.appendChild(col);
        };
        reader.readAsDataURL(file);
    });
}

function removeImage(index) {
    const input = document.getElementById('imageInput');
    const dt = new DataTransfer();
    const files = Array.from(input.files);
    
    files.forEach((file, i) => {
        if (i !== index) dt.items.add(file);
    });
    
    input.files = dt.files;
    previewImages();
}
</script>
@endsection