@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">
        <i class="bi bi-credit-card-fill"></i> Thanh toán
    </h2>
    
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        
        {{-- Truyền danh sách sản phẩm đã chọn --}}
        @foreach($gioHang as $item)
            <input type="hidden" name="items[]" value="{{ $item->MaCTGioHang }}">
            {{-- Nếu là mua ngay, cần truyền thêm số lượng --}}
            @if(strpos($item->MaCTGioHang, 'buy_now_') === 0)
                <input type="hidden" name="so_luong_{{ $item->MaCTSanPham }}" value="{{ $item->SoLuong }}">
            @endif
        @endforeach
        
        <div class="row">
            <!-- Thông tin giao hàng -->
            <div class="col-lg-7">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-person-fill"></i> Thông tin giao hàng
                        </h5>
                        <hr>
                        
                        <div class="mb-3">
                            <label class="form-label">Họ tên *</label>
                            <input type="text" name="HoTen" class="form-control @error('HoTen') is-invalid @enderror" 
                                   value="{{ old('HoTen', $nguoiDung->HoTen) }}" required>
                            @error('HoTen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại *</label>
                            <input type="text" name="SoDienThoai" class="form-control @error('SoDienThoai') is-invalid @enderror" 
                                   value="{{ old('SoDienThoai', $nguoiDung->SoDienThoai) }}" required>
                            @error('SoDienThoai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ giao hàng *</label>
                            <textarea name="DiaChi" class="form-control @error('DiaChi') is-invalid @enderror" 
                                      rows="3" required>{{ old('DiaChi', $nguoiDung->DiaChi) }}</textarea>
                            @error('DiaChi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Ghi chú (tùy chọn)</label>
                            <textarea name="GhiChu" class="form-control" rows="2" 
                                      placeholder="Ghi chú về đơn hàng...">{{ old('GhiChu') }}</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Phương thức thanh toán -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-wallet-fill"></i> Phương thức thanh toán
                        </h5>
                        <hr>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="PTThanhToan" id="cod" 
                                   value="COD" checked>
                            <label class="form-check-label" for="cod">
                                <i class="bi bi-cash-coin"></i> <strong>Thanh toán khi nhận hàng (COD)</strong>
                                <br><small class="text-muted">Thanh toán bằng tiền mặt khi nhận hàng</small>
                            </label>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="PTThanhToan" id="bank" value="Bank">
                            <label class="form-check-label" for="bank">
                                <i class="bi bi-bank"></i> <strong>Chuyển khoản ngân hàng</strong>
                                <br><small class="text-muted">Chuyển khoản trước khi giao hàng</small>
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="PTThanhToan" id="vnpay" value="VNPay">
                            <label class="form-check-label" for="vnpay">
                                <i class="bi bi-credit-card-2-front"></i> <strong>VNPay</strong>
                                <br><small class="text-muted">Thanh toán qua cổng VNPay (Giả lập)</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Đơn hàng -->
            <div class="col-lg-5">
                <div class="card sticky-top" style="top: 100px;">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-bag-fill"></i> Đơn hàng ({{ $gioHang->count() }} sản phẩm)
                        </h5>
                        <hr>
                        
                        <div style="max-height: 300px; overflow-y: auto;">
                            @foreach($gioHang as $item)
                            @php
                            // Chuẩn hóa dữ liệu: item->sanPham là CTSanPham
                            $ctSanPham = $item->sanPham;
                            $sp = $ctSanPham->sanPham ?? $ctSanPham;
                            @endphp
                            <div class="d-flex mb-3">
                                <img src="{{ $ctSanPham->AnhMinhHoa }}" alt="" 
                                    class="img-thumbnail me-2" style="width: 60px; height: 60px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 small">{{ $sp->TenSanPham }}</h6>
                                    <small class="text-muted">
                                        {{ $ctSanPham->MauSac ?? '' }}
                                        @if($ctSanPham->KichThuoc) - {{ $ctSanPham->KichThuoc }} @endif
                                        @if($ctSanPham->DungLuong) - {{ $ctSanPham->DungLuong }} @endif
                                    </small>
                                    <div class="d-flex justify-content-between">
                                        <small>SL: {{ $item->SoLuong }}</small>
                                        <strong class="text-primary">
                                            {{ number_format($item->SoLuong * $ctSanPham->DonGia, 0, ',', '.') }}₫
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <strong>{{ number_format($tongTien, 0, ',', '.') }}₫</strong>
                        </div>
                        
                        {{-- FORM ÁP MÃ GIẢM GIÁ - THÊM MỚI --}}
                        {{-- ============================================ --}}
                        @if(!session('applied_coupon'))
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-tag-fill"></i> Mã giảm giá
                            </label>
                            <div class="input-group">
                                <input type="text" id="couponCodeCheckout" class="form-control" 
                                    placeholder="Nhập mã giảm giá" style="text-transform: uppercase;">
                                <button class="btn btn-outline-primary" type="button" onclick="applyCouponCheckout()">
                                    Áp dụng
                                </button>
                            </div>
                            <div id="couponMessageCheckout" class="mt-2"></div>
                        </div>
                        <hr>
                        @endif
                        
                        {{-- ============================================ --}}
                        {{-- HIỂN THỊ MÃ GIẢM GIÁ - THÊM MỚI --}}
                        {{-- ============================================ --}}
                        @if(session('applied_coupon'))
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-success">
                                <i class="bi bi-tag-fill"></i> Giảm giá 
                                <small>({{ session('applied_coupon')['MaCode'] }})</small>
                            </span>
                            <div class="text-end">
                                <strong class="text-success">-{{ number_format(session('applied_coupon')['SoTienGiam'], 0, ',', '.') }}₫</strong>
                                <br>
                                <button type="button" class="btn btn-link btn-sm text-danger p-0" 
                                        style="font-size: 11px; text-decoration: none;"
                                        onclick="removeCouponCheckout()">
                                    <i class="bi bi-x-circle"></i> Hủy mã
                                </button>
                            </div>
                        </div>
                        @php
                            $tongTienSauGiam = $tongTien - session('applied_coupon')['SoTienGiam'];
                        @endphp
                        @else
                        @php
                            $tongTienSauGiam = $tongTien;
                        @endphp
                        @endif
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển:</span>
                            <strong class="text-success">Miễn phí</strong>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <h5>Tổng cộng:</h5>
                            <h5 class="text-primary">{{ number_format($tongTienSauGiam, 0, ',', '.') }}₫</h5>
                        </div>
                        
                        {{-- Hiển thị thông báo nếu có mã giảm giá --}}
                        @if(session('applied_coupon'))
                        <div class="alert alert-success py-2 mb-3">
                            <i class="bi bi-check-circle-fill"></i>
                            <small>Bạn đã tiết kiệm được <strong>{{ number_format(session('applied_coupon')['SoTienGiam'], 0, ',', '.') }}₫</strong></small>
                        </div>
                        @endif
                        
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-check-circle-fill"></i> Đặt hàng
                        </button>
                        
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="bi bi-arrow-left"></i> Quay lại giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- ============================================ --}}
{{-- XỬ LÝ HỦY MÃ --}}
{{-- ============================================ --}}
<script>
function removeCouponCheckout() {
    if (!confirm('Bạn có chắc muốn hủy mã giảm giá?')) {
        return;
    }
    
    fetch('{{ route("coupon.remove") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload lại trang checkout
            window.location.reload();
        } else {
            alert('Có lỗi xảy ra khi hủy mã giảm giá');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra, vui lòng thử lại');
    });
}
// ÁP MÃ GIẢM GIÁ TẠI TRANG CHECKOUT
// ============================================
function applyCouponCheckout() {
    const code = document.getElementById('couponCodeCheckout').value.trim().toUpperCase();
    
    if (!code) {
        showCouponMessageCheckout('Vui lòng nhập mã giảm giá', 'danger');
        return;
    }
    
    const tongTien = {{ $tongTien }};
    
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    
    fetch('{{ route("coupon.apply") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            MaCode: code,
            TongTien: tongTien
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showCouponMessageCheckout('✓ ' + data.message, 'success');
            setTimeout(() => {
                window.location.reload();
            }, 800);
        } else {
            showCouponMessageCheckout('✗ ' + data.message, 'danger');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showCouponMessageCheckout('Có lỗi xảy ra', 'danger');
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function showCouponMessageCheckout(message, type) {
    const messageDiv = document.getElementById('couponMessageCheckout');
    messageDiv.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show mb-0 py-2">
        <small>${message}</small>
        <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
    </div>`;
}

// Hỗ trợ Enter key
document.addEventListener('DOMContentLoaded', function() {
    const couponInput = document.getElementById('couponCodeCheckout');
    if (couponInput) {
        couponInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyCouponCheckout();
            }
        });
    }
});
</script>
@endsection