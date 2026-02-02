@extends('admin.layouts.app')

@section('page-title', isset($maGiamGia) ? 'Chỉnh sửa mã giảm giá' : 'Tạo mã giảm giá')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2><i class="bi bi-tags-fill"></i> {{ isset($maGiamGia) ? 'Chỉnh sửa' : 'Tạo' }} mã giảm giá</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ isset($maGiamGia) ? route('admin.coupons.update', $maGiamGia->MaMaGiamGia) : route('admin.coupons.store') }}" 
                  method="POST">
                @csrf
                @if(isset($maGiamGia))
                    @method('PUT')
                @endif

                <div class="row">
                    <!-- Mã giảm giá -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mã giảm giá <span class="text-danger">*</span></label>
                        <input type="text" name="MaCode" class="form-control @error('MaCode') is-invalid @enderror" 
                               value="{{ old('MaCode', $maGiamGia->MaCode ?? '') }}" 
                               placeholder="VD: SUMMER2024" required style="text-transform: uppercase;">
                        @error('MaCode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Mã sẽ tự động chuyển thành chữ in hoa
                        </small>
                    </div>

                    <!-- Loại giảm giá -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
                        <select name="LoaiGiam" class="form-select @error('LoaiGiam') is-invalid @enderror" 
                                id="discountType" required>
                            <option value="fixed" {{ old('LoaiGiam', $maGiamGia->LoaiGiam ?? '') == 'fixed' ? 'selected' : '' }}>
                                Giảm cố định (VNĐ)
                            </option>
                            <option value="percent" {{ old('LoaiGiam', $maGiamGia->LoaiGiam ?? '') == 'percent' ? 'selected' : '' }}>
                                Giảm theo phần trăm (%)
                            </option>
                        </select>
                        @error('LoaiGiam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Giá trị -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Giá trị <span class="text-danger">*</span></label>
                        <input type="number" name="GiaTri" class="form-control @error('GiaTri') is-invalid @enderror" 
                               value="{{ old('GiaTri', $maGiamGia->GiaTri ?? '') }}" 
                               step="0.01" min="0" required>
                        @error('GiaTri')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted" id="valueHint">
                            <i class="bi bi-info-circle"></i> Nhập số tiền hoặc phần trăm
                        </small>
                    </div>

                    <!-- Giảm tối đa (chỉ cho %) -->
                    <div class="col-md-6 mb-3" id="maxDiscountGroup">
                        <label class="form-label">Giảm tối đa (VNĐ)</label>
                        <input type="number" name="GiamToiDa" class="form-control @error('GiamToiDa') is-invalid @enderror" 
                               value="{{ old('GiamToiDa', $maGiamGia->GiamToiDa ?? '') }}" 
                               step="0.01" min="0">
                        @error('GiamToiDa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Để trống nếu không giới hạn
                        </small>
                    </div>

                    <!-- Đơn hàng tối thiểu -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Đơn hàng tối thiểu (VNĐ)</label>
                        <input type="number" name="DonToiThieu" class="form-control @error('DonToiThieu') is-invalid @enderror" 
                               value="{{ old('DonToiThieu', $maGiamGia->DonToiThieu ?? '') }}" 
                               step="0.01" min="0">
                        @error('DonToiThieu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Để trống nếu không giới hạn
                        </small>
                    </div>

                    <!-- Giới hạn số lần sử dụng -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Giới hạn số lần sử dụng</label>
                        <input type="number" name="GioiHanSuDung" class="form-control @error('GioiHanSuDung') is-invalid @enderror" 
                               value="{{ old('GioiHanSuDung', $maGiamGia->GioiHanSuDung ?? '') }}" 
                               min="1">
                        @error('GioiHanSuDung')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Để trống nếu không giới hạn
                            @if(isset($maGiamGia))
                                | Đã dùng: <strong>{{ $maGiamGia->DaSuDung }}</strong> lần
                            @endif
                        </small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Giới hạn mỗi người</label>
                        <input type="number" name="GioiHanMoiNguoi" class="form-control" 
                            value="{{ old('GioiHanMoiNguoi') }}" min="1" 
                            placeholder="Để trống = không giới hạn">
                        <small class="text-muted">Mỗi người dùng được dùng tối đa bao nhiêu lần</small>
                    </div>

                    <!-- Ngày bắt đầu -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày bắt đầu</label>
                        <input type="datetime-local" name="NgayBatDau" class="form-control @error('NgayBatDau') is-invalid @enderror" 
                               value="{{ old('NgayBatDau', isset($maGiamGia) && $maGiamGia->NgayBatDau ? $maGiamGia->NgayBatDau->format('Y-m-d\TH:i') : '') }}">
                        @error('NgayBatDau')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Ngày kết thúc -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày kết thúc</label>
                        <input type="datetime-local" name="NgayKetThuc" class="form-control @error('NgayKetThuc') is-invalid @enderror" 
                               value="{{ old('NgayKetThuc', isset($maGiamGia) && $maGiamGia->NgayKetThuc ? $maGiamGia->NgayKetThuc->format('Y-m-d\TH:i') : '') }}">
                        @error('NgayKetThuc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="TrangThai" id="TrangThai" 
                                   value="1" {{ old('TrangThai', $maGiamGia->TrangThai ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="TrangThai">
                                Kích hoạt mã giảm giá
                            </label>
                        </div>
                    </div>

                    <!-- Mô tả -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="MoTa" class="form-control @error('MoTa') is-invalid @enderror" 
                                  rows="3" placeholder="Mô tả chi tiết về chương trình giảm giá...">{{ old('MoTa', $maGiamGia->MoTa ?? '') }}</textarea>
                        @error('MoTa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> {{ isset($maGiamGia) ? 'Cập nhật' : 'Tạo mới' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const discountType = document.getElementById('discountType');
    const maxDiscountGroup = document.getElementById('maxDiscountGroup');
    const valueHint = document.getElementById('valueHint');

    function toggleMaxDiscount() {
        if (discountType.value === 'percent') {
            maxDiscountGroup.style.display = 'block';
            valueHint.innerHTML = '<i class="bi bi-info-circle"></i> Nhập phần trăm (VD: 10 = giảm 10%)';
        } else {
            maxDiscountGroup.style.display = 'none';
            valueHint.innerHTML = '<i class="bi bi-info-circle"></i> Nhập số tiền giảm (VNĐ)';
        }
    }

    discountType.addEventListener('change', toggleMaxDiscount);
    toggleMaxDiscount();
});
</script>
@endsection