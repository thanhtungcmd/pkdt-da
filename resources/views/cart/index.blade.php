@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container my-5">
    
    <!-- THÔNG BÁO MÃ GIẢM GIÁ BỊ HỦY (THÊM MỚI) -->
    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    
    <h2 class="mb-4">
        <i class="bi bi-cart-fill"></i> Giỏ hàng của bạn
    </h2>
    
    @if($gioHang->count() > 0)
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div id="checkoutWrapper">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="checkAll" onchange="toggleCheckAll()">
                                        </th>
                                        <th>Sản phẩm</th>
                                        <th>Đơn giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($gioHang as $item)
                                <tr data-item-id="{{ $item->MaCTGioHang }}" 
                                    data-price="{{ $item->DonGia }}"
                                    data-quantity="{{ $item->SoLuong }}"
                                    data-max-stock="{{ $item->sanPham->SoLuongTon }}">
                                    <td>
                                        <input type="checkbox" 
                                               name="items[]" 
                                               value="{{ $item->MaCTGioHang }}" 
                                               class="item-checkbox" 
                                               onchange="toggleItemControls(this)">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->sanPham->AnhMinhHoa }}" 
                                                 alt="{{ $item->sanPham->tenDayDu() }}" 
                                                 class="img-thumbnail me-3" style="width: 80px;">
                                            <div style="width: 100%;">
                                                <h6 class="mb-1">{{ $item->sanPham->sanPham->TenSanPham }}</h6>
                                                
                                                <!-- Dropdown chọn màu sắc -->
                                                @php
                                                    $allVariants = $item->sanPham->sanPham->bienThe()
                                                        ->where('TrangThai', 1)
                                                        ->where('SoLuongTon', '>', 0)
                                                        ->get();
                                                    $colors = $allVariants->pluck('MauSac')->unique();
                                                @endphp
                                                
                                                @if($colors->count() > 1)
                                                <div class="mb-2">
                                                    <label class="small text-muted">Màu sắc:</label>
                                                    <select class="form-select form-select-sm variant-selector" 
                                                            data-item-id="{{ $item->MaCTGioHang }}"
                                                            data-current-variant="{{ $item->MaCTSanPham }}"
                                                            data-attribute="color"
                                                            onchange="updateVariant(this)"
                                                            disabled>
                                                        @foreach($colors as $color)
                                                        <option value="{{ $color }}" 
                                                                {{ $item->sanPham->MauSac == $color ? 'selected' : '' }}>
                                                            {{ $color }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @else
                                                <small class="text-muted d-block">{{ $item->sanPham->MauSac }}</small>
                                                @endif

                                                <!-- Dropdown chọn kích thước -->
                                                @php
                                                    $sizes = $allVariants->where('MauSac', $item->sanPham->MauSac)
                                                        ->pluck('KichThuoc')->filter()->unique();
                                                @endphp
                                                
                                                @if($sizes->count() > 1)
                                                <div class="mb-2">
                                                    <label class="small text-muted">Kích thước:</label>
                                                    <select class="form-select form-select-sm variant-selector" 
                                                            data-item-id="{{ $item->MaCTGioHang }}"
                                                            data-current-variant="{{ $item->MaCTSanPham }}"
                                                            data-attribute="size"
                                                            onchange="updateVariant(this)"
                                                            disabled>
                                                        @foreach($sizes as $size)
                                                        <option value="{{ $size }}" 
                                                                {{ $item->sanPham->KichThuoc == $size ? 'selected' : '' }}>
                                                            {{ $size }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @elseif($item->sanPham->KichThuoc)
                                                <small class="text-muted d-block">{{ $item->sanPham->KichThuoc }}</small>
                                                @endif

                                                <!-- Dropdown chọn dung lượng -->
                                                @php
                                                    $capacities = $allVariants->where('MauSac', $item->sanPham->MauSac)
                                                        ->pluck('DungLuong')->filter()->unique();
                                                @endphp
                                                
                                                @if($capacities->count() > 1)
                                                <div class="mb-2">
                                                    <label class="small text-muted">Dung lượng:</label>
                                                    <select class="form-select form-select-sm variant-selector" 
                                                            data-item-id="{{ $item->MaCTGioHang }}"
                                                            data-current-variant="{{ $item->MaCTSanPham }}"
                                                            data-attribute="capacity"
                                                            onchange="updateVariant(this)"
                                                            disabled>
                                                        @foreach($capacities as $capacity)
                                                        <option value="{{ $capacity }}" 
                                                                {{ $item->sanPham->DungLuong == $capacity ? 'selected' : '' }}>
                                                            {{ $capacity }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @elseif($item->sanPham->DungLuong)
                                                <small class="text-muted d-block">{{ $item->sanPham->DungLuong }}</small>
                                                @endif
                                                
                                                <!-- Hiển thị tồn kho -->
                                                <small class="text-muted">Còn lại: <span class="stock-available">{{ $item->sanPham->SoLuongTon }}</span> sản phẩm</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-primary fw-bold item-price">
                                            {{ number_format($item->DonGia, 0, ',', '.') }}₫
                                        </span>
                                    </td>
                                    <td>
                                        <div class="input-group quantity-control" style="width: 120px;">
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-secondary btn-decrease" 
                                                    onclick="changeQuantity({{ $item->MaCTGioHang }}, -1)"
                                                    disabled>
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <input type="number" 
                                                   class="form-control form-control-sm text-center quantity-input" 
                                                   id="qty-{{ $item->MaCTGioHang }}"
                                                   value="{{ $item->SoLuong }}" 
                                                   min="1" 
                                                   max="20"
                                                   onchange="updateQuantity({{ $item->MaCTGioHang }}, this.value)"
                                                   disabled>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-secondary btn-increase" 
                                                    onclick="changeQuantity({{ $item->MaCTGioHang }}, 1)"
                                                    disabled>
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-primary item-total">
                                            {{ number_format($item->thanhTien(), 0, ',', '.') }}₫
                                        </strong>
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.remove', $item->MaCTGioHang) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Xóa sản phẩm khỏi giỏ hàng?')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" 
                                    onclick="return confirm('Xóa toàn bộ giỏ hàng?')">
                                <i class="bi bi-trash-fill"></i> Xóa giỏ hàng
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
         <!-- Cart Summary -->
        <div class="col-lg-4">
            <!-- CARD MÃ GIẢM GIÁ -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-tags-fill"></i> Mã giảm giá
                    </h5>
                    <hr>
                    
                    @if(session('applied_coupon'))
                        <!-- Hiển thị mã đã áp dụng -->
                        <div class="alert alert-success d-flex justify-content-between align-items-center mb-0 alert-persistent">
                            <div>
                                <i class="bi bi-check-circle-fill"></i>
                                <strong>{{ session('applied_coupon')['MaCode'] }}</strong>
                                <br>
                                <small>Giảm: <strong>{{ number_format(session('applied_coupon')['SoTienGiam'], 0, ',', '.') }}đ</strong></small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCoupon()">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>
                    @else
                        <!-- Form nhập mã -->
                        <div id="couponForm">
                            <div class="input-group">
                                <input type="text" class="form-control" id="couponCode" 
                                       placeholder="Nhập mã giảm giá" 
                                       style="text-transform: uppercase;">
                                <button class="btn btn-primary" type="button" onclick="applyCoupon()">
                                    Áp dụng
                                </button>
                            </div>
                            <div id="couponMessage" class="mt-2"></div>
                        </div>
                    @endif
                </div>
            </div>
           
            <!-- CARD TỔNG ĐƠN HÀNG - ĐÃ SỬA -->           
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-receipt"></i> Tổng đơn hàng
                    </h5>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Số sản phẩm đã chọn:</span>
                        <strong id="selectedItemCount">0</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <strong id="subtotalDisplay">0₫</strong>
                    </div>
                    
                    <!-- HIỂN THỊ GIẢM GIÁ NẾU CÓ -->
                    @if(session('applied_coupon'))
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>
                            <i class="bi bi-tag-fill"></i> Giảm giá 
                            <small>({{ session('applied_coupon')['MaCode'] }})</small>:
                        </span>
                        <strong id="discountAmount">-{{ number_format(session('applied_coupon')['SoTienGiam'], 0, ',', '.') }}₫</strong>
                    </div>
                    @endif
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển:</span>
                        <strong class="text-success">Miễn phí</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <h5>Tổng cộng:</h5>
                        <h5 class="text-primary" id="total">0₫</h5>
                    </div>

                    <form action="{{ route('orders.checkout') }}" method="POST" id="checkoutForm">
                        @csrf
                        <div id="checkoutHiddenInputs"></div>
                        <button type="button" class="btn btn-primary w-100 py-2" onclick="checkAndSubmit()">
                            <i class="bi bi-credit-card-fill"></i> Tiến hành thanh toán
                        </button>
                    </form>

                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Vui lòng chọn sản phẩm để thanh toán
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Benefits -->
            <div class="card mt-3">
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success"></i> Miễn phí vận chuyển
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success"></i> Bảo hành chính hãng
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success"></i> Đổi trả trong 7 ngày
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill text-success"></i> Thanh toán an toàn
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-cart-x" style="font-size: 100px; color: #ccc;"></i>
        <h3 class="mt-4">Giỏ hàng trống</h3>
        <p class="text-muted">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
            <i class="bi bi-grid-fill"></i> Khám phá sản phẩm
        </a>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
// ============================================
// BIẾN GLOBAL
// ============================================
const MAX_QUANTITY = 20;
let appliedDiscount = {{ session('applied_coupon')['SoTienGiam'] ?? 0 }};

// ============================================
// LƯU VÀ KHÔI PHỤC TRẠNG THÁI - THÊM MỚI
// ============================================
function saveSelectedItems() {
    const selectedIds = [];
    document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
        selectedIds.push(cb.value);
    });
    sessionStorage.setItem('selected_cart_items', JSON.stringify(selectedIds));
}

function restoreSelectedItems() {
    const savedItems = sessionStorage.getItem('selected_cart_items');
    if (savedItems) {
        const selectedIds = JSON.parse(savedItems);
        selectedIds.forEach(id => {
            const checkbox = document.querySelector(`.item-checkbox[value="${id}"]`);
            if (checkbox) {
                checkbox.checked = true;
                toggleItemControls(checkbox);
            }
        });
        updateTotal();
    }
}

// ============================================
// CHỨC NĂNG GIỎ HÀNG
// ============================================
function toggleCheckAll() {
    const checkAll = document.getElementById('checkAll').checked;
    document.querySelectorAll('.item-checkbox').forEach(cb => {
        cb.checked = checkAll;
        toggleItemControls(cb);
    });
    updateTotal();
}

function toggleItemControls(checkbox) {
    const row = checkbox.closest('tr');
    const isChecked = checkbox.checked;
    
    const selectors = row.querySelectorAll('.variant-selector');
    selectors.forEach(select => {
        select.disabled = !isChecked;
    });
    
    const quantityInput = row.querySelector('.quantity-input');
    const btnDecrease = row.querySelector('.btn-decrease');
    const btnIncrease = row.querySelector('.btn-increase');
    
    if (quantityInput) quantityInput.disabled = !isChecked;
    if (btnDecrease) btnDecrease.disabled = !isChecked;
    if (btnIncrease) btnIncrease.disabled = !isChecked;
    
    updateTotal();
}

function updateTotal() {
    let selectedCount = 0;
    let subtotal = 0;
    
    document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
        selectedCount++;
        const row = checkbox.closest('tr');
        const price = parseFloat(row.dataset.price);
        const quantity = parseInt(row.dataset.quantity);
        subtotal += price * quantity;
    });
    
    document.getElementById('selectedItemCount').textContent = selectedCount;
    document.getElementById('subtotalDisplay').textContent = formatCurrency(subtotal);
    
    // ============================================
    // FIX: TÍNH TỔNG SAU KHI TRỪ GIẢM GIÁ
    // ============================================
    let discount = appliedDiscount; // Giảm giá từ session
    
    // Nếu tổng tiền < giảm giá → giảm tối đa = tổng tiền
    if (discount > subtotal) {
        discount = subtotal;
    }
    
    const total = Math.max(0, subtotal - discount);
    
    // Cập nhật hiển thị
    document.getElementById('total').textContent = formatCurrency(total);
    
    // Cập nhật số tiền giảm hiển thị (nếu có)
    const discountElement = document.getElementById('discountAmount');
    if (discountElement) {
        discountElement.textContent = '-' + formatCurrency(discount);
    }
    
    const totalCheckboxes = document.querySelectorAll('.item-checkbox').length;
    document.getElementById('checkAll').checked = (selectedCount === totalCheckboxes && selectedCount > 0);
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', { 
        style: 'currency', 
        currency: 'VND',
        minimumFractionDigits: 0
    }).format(amount);
}

function changeQuantity(itemId, change) {
    const row = document.querySelector(`tr[data-item-id="${itemId}"]`);
    const input = document.getElementById('qty-' + itemId);
    const maxStock = parseInt(row.dataset.maxStock);
    let newValue = parseInt(input.value) + change;
    
    if (newValue < 1) {
        alert('Số lượng tối thiểu là 1');
        return;
    }
    
    const maxAllowed = Math.min(MAX_QUANTITY, maxStock);
    if (newValue > maxAllowed) {
        if (maxAllowed === MAX_QUANTITY) {
            alert(`Số lượng tối đa là ${MAX_QUANTITY} sản phẩm`);
        } else {
            alert(`Chỉ còn ${maxStock} sản phẩm trong kho`);
        }
        return;
    }
    
    input.value = newValue;
    updateQuantity(itemId, newValue);
}

function updateQuantity(itemId, quantity) {
    const row = document.querySelector(`tr[data-item-id="${itemId}"]`);
    const maxStock = parseInt(row.dataset.maxStock);
    const maxAllowed = Math.min(MAX_QUANTITY, maxStock);
    
    if (quantity < 1) {
        alert('Số lượng tối thiểu là 1');
        document.getElementById('qty-' + itemId).value = row.dataset.quantity;
        return;
    }
    
    if (quantity > maxAllowed) {
        if (maxAllowed === MAX_QUANTITY) {
            alert(`Số lượng tối đa là ${MAX_QUANTITY} sản phẩm`);
        } else {
            alert(`Chỉ còn ${maxStock} sản phẩm trong kho`);
        }
        document.getElementById('qty-' + itemId).value = row.dataset.quantity;
        return;
    }
    
    const input = document.getElementById('qty-' + itemId);
    const originalValue = input.value;
    input.disabled = true;
    
    fetch(`/cart/${itemId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ SoLuong: quantity })
    })
    .then(response => response.json())
    .then(data => {
        input.disabled = false;
        
        if (data.success) {
            row.dataset.quantity = quantity;
            row.dataset.price = data.itemPrice;
            row.querySelector('.item-total').textContent = formatCurrency(data.itemTotal);
            updateTotal();
            showToast('Đã cập nhật số lượng', 'success');
        } else {
            alert(data.message || 'Có lỗi xảy ra!');
            input.value = originalValue;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật giỏ hàng!');
        input.value = originalValue;
        input.disabled = false;
    });
}

function updateVariant(selectElement) {
    const itemId = selectElement.dataset.itemId;
    const row = document.querySelector(`tr[data-item-id="${itemId}"]`);
    
    const allSelectors = row.querySelectorAll('.variant-selector');
    allSelectors.forEach(s => s.disabled = true);
    
    let selectedColor = '';
    let selectedSize = '';
    let selectedCapacity = '';
    
    allSelectors.forEach(select => {
        if (select.dataset.attribute === 'color') {
            selectedColor = select.value;
        } else if (select.dataset.attribute === 'size') {
            selectedSize = select.value;
        } else if (select.dataset.attribute === 'capacity') {
            selectedCapacity = select.value;
        }
    });
    
    fetch(`/cart/${itemId}/variant`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            color: selectedColor,
            size: selectedSize,
            capacity: selectedCapacity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.merged) {
                showToast(data.message, 'info');
                setTimeout(() => location.reload(), 1000);
                return;
            }
            
            if (data.noChange) {
                allSelectors.forEach(s => s.disabled = false);
                return;
            }
            
            row.dataset.price = data.newPrice;
            row.querySelector('.item-price').textContent = formatCurrency(data.newPrice);
            row.dataset.maxStock = data.newStock;
            row.querySelector('.stock-available').textContent = data.newStock;
            
            const qtyInput = row.querySelector('.quantity-input');
            qtyInput.max = Math.min(MAX_QUANTITY, data.newStock);
            
            const currentQty = parseInt(qtyInput.value);
            const maxAllowed = Math.min(MAX_QUANTITY, data.newStock);
            if (currentQty > maxAllowed) {
                qtyInput.value = maxAllowed;
                row.dataset.quantity = maxAllowed;
            }
            
            if (data.newImage) {
                row.querySelector('img').src = data.newImage;
            }
            
            row.querySelector('.item-total').textContent = formatCurrency(data.itemTotal);
            updateTotal();
            allSelectors.forEach(s => s.disabled = false);
            showToast('Đã cập nhật biến thể sản phẩm', 'success');
        } else {
            alert(data.message || 'Không tìm thấy biến thể phù hợp!');
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật biến thể!');
        location.reload();
    });
}

function showToast(message, type = 'info') {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'info'} border-0" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    const toastContainer = document.createElement('div');
    toastContainer.innerHTML = toastHtml;
    document.body.appendChild(toastContainer);
    
    const toastElement = toastContainer.querySelector('.toast');
    const bsToast = new bootstrap.Toast(toastElement);
    bsToast.show();
    
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastContainer.remove();
    });
}

function checkAndSubmit() {
    const checked = document.querySelectorAll('.item-checkbox:checked').length;
    if (checked === 0) {
        alert('Vui lòng chọn ít nhất 1 sản phẩm để thanh toán!');
        return false;
    }

    // ============================================
    // KIỂM TRA MÃ GIẢM GIÁ BẰNG AJAX (THÊM MỚI)
    // ============================================
    @if(session('applied_coupon'))
    // Tính tổng tiền các sản phẩm đã chọn
    let subtotal = 0;
    document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
        const row = checkbox.closest('tr');
        const price = parseFloat(row.dataset.price);
        const quantity = parseInt(row.dataset.quantity);
        subtotal += price * quantity;
    });

    // Disable nút để tránh click nhiều lần
    const submitBtn = event.target;
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang kiểm tra...';

    // Kiểm tra lại mã giảm giá qua API
    fetch('{{ route("coupon.apply") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            MaCode: '{{ session("applied_coupon")["MaCode"] }}',
            TongTien: subtotal
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mã hợp lệ → Tiếp tục checkout
            proceedToCheckout();
        } else {
            // Mã không hợp lệ → Dừng lại
            alert('⚠️ ' + data.message + '\n\nVui lòng hủy mã hoặc điều chỉnh giỏ hàng!');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi kiểm tra mã giảm giá!');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
    
    return false; // Ngăn submit form ngay lập tức
    @else
    // Không có mã giảm giá → Checkout bình thường
    proceedToCheckout();
    @endif
}

// Hàm helper để submit form
function proceedToCheckout() {
    const hiddenContainer = document.getElementById('checkoutHiddenInputs');
    hiddenContainer.innerHTML = '';

    document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'items[]';
        input.value = cb.value;
        hiddenContainer.appendChild(input);
    });

    document.getElementById('checkoutForm').submit();
}

// ============================================
// CHỨC NĂNG MÃ GIẢM GIÁ 
// ============================================
function applyCoupon() {
    const code = document.getElementById('couponCode').value.trim().toUpperCase();
    
    if (!code) {
        showCouponMessage('Vui lòng nhập mã giảm giá', 'danger');
        return;
    }
    
    // ============================================
    // FIX: CHỈ TÍNH CHO CÁC SẢN PHẨM ĐÃ CHỌN
    // ============================================
    const checkedItems = document.querySelectorAll('.item-checkbox:checked');
    
    if (checkedItems.length === 0) {
        showCouponMessage('Vui lòng chọn sản phẩm trước khi áp dụng mã', 'warning');
        return;
    }
    
    // Tính tổng tiền CHỈ CÁC SẢN PHẨM ĐÃ CHỌN
    let subtotal = 0;
    checkedItems.forEach(checkbox => {
        const row = checkbox.closest('tr');
        const price = parseFloat(row.dataset.price);
        const quantity = parseInt(row.dataset.quantity);
        subtotal += price * quantity;
    });
    
    // LƯU CÁC SẢN PHẨM ĐÃ CHỌN - QUAN TRỌNG!
    saveSelectedItems();
    
    // Disable button
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';
    
    fetch('{{ route("coupon.apply") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            MaCode: code,
            TongTien: subtotal // Gửi tổng tiền của SẢN PHẨM ĐÃ CHỌN
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showCouponMessage('✓ ' + data.message + ' - Giảm: ' + formatCurrency(data.discount), 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            sessionStorage.removeItem('selected_cart_items');
            showCouponMessage('✗ ' + data.message, 'danger');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    })
    .catch(error => {
        sessionStorage.removeItem('selected_cart_items');
        showCouponMessage('Có lỗi xảy ra, vui lòng thử lại', 'danger');
        console.error('Error:', error);
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function removeCoupon() {
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
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi hủy mã');
    });
}

function showCouponMessage(message, type) {
    const messageDiv = document.getElementById('couponMessage');
    messageDiv.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show mb-0">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
}

// ============================================
// KHỞI TẠO KHI TRANG LOAD
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // KHÔI PHỤC CÁC SẢN PHẨM ĐÃ CHỌN
    restoreSelectedItems();
    
    // XÓA TRẠNG THÁI LƯU SAU KHI ÁP MÃ THÀNH CÔNG HOẶC BỊ HỦY
    @if(session('applied_coupon') || session('warning'))
        sessionStorage.removeItem('selected_cart_items');
    @endif
    
    // Khởi tạo các checkbox
    document.querySelectorAll('.item-checkbox').forEach(cb => {
        toggleItemControls(cb);
    });
    
    // Tính tổng
    updateTotal();
    
    // Enter key cho mã giảm giá
    const couponInput = document.getElementById('couponCode');
    if (couponInput) {
        couponInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyCoupon();
            }
        });
    }
});
</script>
@endsection