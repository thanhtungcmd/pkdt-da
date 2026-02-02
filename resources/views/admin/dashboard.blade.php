@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')

<!-- Filter Thời Gian -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-bold"><i class="bi bi-calendar3"></i> Khoảng thời gian</label>
                <select name="filter" id="filterSelect" class="form-select" onchange="toggleCustomDate()">
                    <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Hôm nay</option>
                    <option value="7" {{ $filter == '7' ? 'selected' : '' }}>7 ngày qua</option>
                    <option value="30" {{ $filter == '30' ? 'selected' : '' }}>30 ngày qua</option>
                    <option value="90" {{ $filter == '90' ? 'selected' : '' }}>90 ngày qua</option>
                    <option value="this_month" {{ $filter == 'this_month' ? 'selected' : '' }}>Tháng này</option>
                    <option value="last_month" {{ $filter == 'last_month' ? 'selected' : '' }}>Tháng trước</option>
                    <option value="this_year" {{ $filter == 'this_year' ? 'selected' : '' }}>Năm này</option>
                    <option value="last_year" {{ $filter == 'last_year' ? 'selected' : '' }}>Năm trước</option>
                    <option value="custom" {{ $filter == 'custom' ? 'selected' : '' }}>Tùy chỉnh</option>
                </select>
            </div>
            
            <div class="col-md-3" id="tuNgayDiv" style="display: {{ $filter == 'custom' ? 'block' : 'none' }};">
                <label class="form-label fw-bold">Từ ngày</label>
                <input type="date" name="tu_ngay" class="form-control" value="{{ $tuNgay }}">
            </div>
            
            <div class="col-md-3" id="denNgayDiv" style="display: {{ $filter == 'custom' ? 'block' : 'none' }};">
                <label class="form-label fw-bold">Đến ngày</label>
                <input type="date" name="den_ngay" class="form-control" value="{{ $denNgay }}">
            </div>
            
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-funnel-fill"></i> Lọc
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </a>
            </div>
        </form>
        
        <div class="mt-2">
            <small class="text-muted">
                <i class="bi bi-calendar-check-fill"></i> 
                Đang xem: <strong>{{ $startDate->format('d/m/Y') }}</strong> đến <strong>{{ $endDate->format('d/m/Y') }}</strong>
            </small>
        </div>
    </div>
</div>

<!-- Stats Cards Row 1: Doanh thu & Đơn hàng -->
<div class="row mb-4">
    <!-- Doanh thu -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-warning border-4 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2 text-uppercase" style="font-size: 0.85rem;">
                            <i class="bi bi-cash-stack"></i> Doanh thu
                        </h6>
                        <h3 class="mb-1 fw-bold">{{ number_format($tongDoanhThu, 0, ',', '.') }}₫</h3>
                        @if($phanTramDoanhThu != 0)
                            <span class="badge bg-{{ $phanTramDoanhThu > 0 ? 'success' : 'danger' }}">
                                <i class="bi bi-{{ $phanTramDoanhThu > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                {{ number_format(abs($phanTramDoanhThu), 1) }}%
                            </span>
                            <small class="text-muted">vs kỳ trước</small>
                        @endif
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                        <i class="bi bi-cash-stack text-warning" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Đơn hàng - THÊM LINK -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
            <div class="card border-start border-success border-4 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2 text-uppercase" style="font-size: 0.85rem;">
                                <i class="bi bi-cart-fill"></i> Đơn hàng
                            </h6>
                            <h3 class="mb-1 fw-bold text-dark">{{ $tongDonHang }}</h3>
                            @if($phanTramDonHang != 0)
                                <span class="badge bg-{{ $phanTramDonHang > 0 ? 'success' : 'danger' }}">
                                    <i class="bi bi-{{ $phanTramDonHang > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ number_format(abs($phanTramDonHang), 1) }}%
                                </span>
                            @endif
                            @if($donHangChoXacNhan > 0)
                                <span class="badge bg-warning text-dark ms-1">{{ $donHangChoXacNhan }} mới</span>
                            @endif
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-3">
                            <i class="bi bi-cart-fill text-success" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <!-- Giá trị đơn TB -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-info border-4 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2 text-uppercase" style="font-size: 0.85rem;">
                            <i class="bi bi-graph-up-arrow"></i> Giá trị đơn TB
                        </h6>
                        <h3 class="mb-0 fw-bold">{{ number_format($giaTriDonTrungBinh, 0, ',', '.') }}₫</h3>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-3">
                        <i class="bi bi-graph-up-arrow text-info" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tổng sản phẩm -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-primary border-4 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2 text-uppercase" style="font-size: 0.85rem;">
                            <i class="bi bi-box-seam-fill"></i> Tổng sản phẩm
                        </h6>
                        <h3 class="mb-0 fw-bold">{{ $tongSanPham }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                        <i class="bi bi-box-seam-fill text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards Row 2: Khách hàng - THÊM LINK -->
<div class="row mb-4">
    <!-- KH mới - THÊM LINK -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
            <div class="card border-start border-purple border-4 shadow-sm h-100 hover-card" style="border-color: #6f42c1 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2 text-uppercase" style="font-size: 0.85rem;">
                                <i class="bi bi-person-plus-fill"></i> KH mới
                            </h6>
                            <h3 class="mb-1 fw-bold text-dark">{{ $khachHangMoi }}</h3>
                            @if($phanTramKhachHang != 0)
                                <span class="badge bg-{{ $phanTramKhachHang > 0 ? 'success' : 'danger' }}">
                                    <i class="bi bi-{{ $phanTramKhachHang > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ number_format(abs($phanTramKhachHang), 1) }}%
                                </span>
                            @endif
                        </div>
                        <div class="bg-opacity-10 p-3 rounded-3" style="background-color: #6f42c1;">
                            <i class="bi bi-person-plus-fill" style="font-size: 2.5rem; color: #6f42c1;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-success border-4 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2 text-uppercase" style="font-size: 0.85rem;">
                            <i class="bi bi-bag-check-fill"></i> KH đã mua
                        </h6>
                        <h3 class="mb-0 fw-bold">{{ $khachHangDaMua }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-3">
                        <i class="bi bi-bag-check-fill text-success" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-info border-4 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2 text-uppercase" style="font-size: 0.85rem;">
                            <i class="bi bi-arrow-repeat"></i> KH quay lại
                        </h6>
                        <h3 class="mb-0 fw-bold">{{ $khachHangQuayLai }}</h3>
                        <small class="text-muted">Mua ≥ 2 đơn</small>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-3">
                        <i class="bi bi-arrow-repeat text-info" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tổng KH - THÊM LINK -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
            <div class="card border-start border-secondary border-4 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2 text-uppercase" style="font-size: 0.85rem;">
                                <i class="bi bi-people-fill"></i> Tổng KH
                            </h6>
                            <h3 class="mb-0 fw-bold text-dark">{{ $tongKhachHang }}</h3>
                            <small class="text-muted">Toàn hệ thống</small>
                        </div>
                        <div class="bg-secondary bg-opacity-10 p-3 rounded-3">
                            <i class="bi bi-people-fill text-secondary" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<style>
/* Hover effect cho card có link */
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
<!-- Thông báo (1 dòng ngang - 4 cột) -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="bi bi-bell-fill"></i> Thông báo
                </h5>
                <div class="row text-center">
                    <!-- Đơn chờ xác nhận -->
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="p-3 border rounded h-100">
                            <i class="bi bi-cart-fill text-warning" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2 mb-1 fw-bold text-warning">{{ $donHangChoXacNhan }}</h3>
                            <p class="mb-2 text-muted">đơn chờ xác nhận</p>
                            @if($donHangChoXacNhan > 0)
                                <a href="{{ route('admin.orders.index', ['trang_thai' => 'Chờ xác nhận']) }}" 
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-eye"></i> Xem ngay
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Bình luận mới -->
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="p-3 border rounded h-100">
                            <i class="bi bi-chat-dots-fill text-success" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2 mb-1 fw-bold text-success">{{ $binhLuanMoi }}</h3>
                            <p class="mb-2 text-muted">bình luận mới</p>
                            @if($binhLuanMoi > 0)
                                <a href="{{ route('admin.comments.index') }}" 
                                   class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-eye"></i> Xem ngay
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Đánh giá chưa xem -->
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="p-3 border rounded h-100">
                            <i class="bi bi-star-fill text-primary" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2 mb-1 fw-bold text-primary">{{ $danhGiaChuaXem }}</h3>
                            <p class="mb-2 text-muted">đánh giá chưa xem</p>
                            @if($danhGiaChuaXem > 0)
                                <a href="{{ route('admin.ratings.index', ['da_xem' => '0']) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Xem ngay
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Phản hồi chưa xem -->
                    <div class="col-md-3">
                        <div class="p-3 border rounded h-100">
                            <i class="bi bi-envelope-fill text-info" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2 mb-1 fw-bold text-info">{{ $phanHoiChuaXem }}</h3>
                            <p class="mb-2 text-muted">phản hồi chưa xem</p>
                            @if($phanHoiChuaXem > 0)
                                <a href="{{ route('admin.feedback.index', ['trang_thai' => 0]) }}" 
                                   class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i> Xem ngay
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Biểu đồ Line: Doanh thu & Đơn hàng (Full width) -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-graph-up"></i> Biểu đồ Doanh thu & Đơn hàng theo ngày
                </h5>
                @if($bieuDoDoanhThu->count() > 0)
                    <canvas id="revenueChart" height="80"></canvas>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-graph-up" style="font-size: 3rem;"></i>
                        <p class="mt-2">Chưa có dữ liệu</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- 2 cột: Tỷ lệ doanh thu + Top bán chạy -->
<div class="row mb-4">
    <!-- Tỷ lệ doanh thu theo danh mục (Biểu đồ Doughnut) -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-pie-chart-fill"></i> Tỷ lệ doanh thu theo danh mục
                </h5>
                @if($doanhThuTheoDanhMuc->count() > 0)
                    <div style="max-width: 350px; margin: 0 auto;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-pie-chart-fill" style="font-size: 3rem;"></i>
                        <p class="mt-2">Chưa có dữ liệu</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Top Bán chạy -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-fire text-danger"></i> Top 5 Sản phẩm BÁN CHẠY
                </h5>
                <hr>
                @if($sanPhamBanChay->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đã bán</th>
                                    <th>Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sanPhamBanChay as $sp)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $sp->AnhChinh }}" alt="" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            <span class="small">{{ Str::limit($sp->TenSanPham, 30) }}</span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">{{ $sp->TongSoLuong }}</span></td>
                                    <td><strong class="text-primary small">{{ number_format($sp->TongDoanhThu, 0, ',', '.') }}₫</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-fire" style="font-size: 2.5rem;"></i>
                        <p class="mt-2">Chưa có dữ liệu</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- 2 cột: Thống kê đơn hàng + Biểu đồ trạng thái đơn hàng -->
<div class="row mb-4">
    <!-- Thống kê đơn hàng theo trạng thái -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-bar-chart-fill"></i> Thống kê đơn hàng theo trạng thái
                </h5>
                <hr>
                @if($thongKeDonHang->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Trạng thái</th>
                                    <th>Số lượng</th>
                                    <th>Tỷ lệ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $colors = [
                                        'Chờ xác nhận' => 'warning',
                                        'Đã xác nhận' => 'info',
                                        'Đang giao hàng' => 'primary',
                                        'Đã giao hàng' => 'success',
                                        'Đã hủy' => 'danger'
                                    ];
                                @endphp
                                @foreach($thongKeDonHang as $trangThai => $soLuong)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $colors[$trangThai] ?? 'secondary' }}">
                                            {{ $trangThai }}
                                        </span>
                                    </td>
                                    <td><strong>{{ $soLuong }}</strong></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{ $colors[$trangThai] ?? 'secondary' }}" 
                                                 style="width: {{ $tongDonHang > 0 ? ($soLuong / $tongDonHang) * 100 : 0 }}%">
                                                <small>{{ $tongDonHang > 0 ? number_format(($soLuong / $tongDonHang) * 100, 1) : 0 }}%</small>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-bar-chart-fill" style="font-size: 2.5rem;"></i>
                        <p class="mt-2">Chưa có dữ liệu</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Biểu đồ Pie: Biểu đồ trạng thái đơn hàng -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-pie-chart-fill"></i> Biểu đồ trạng thái đơn hàng
                </h5>
                @if($thongKeDonHang->count() > 0)
                    <div style="max-width: 350px; margin: 0 auto;">
                        <canvas id="statusChart"></canvas>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-pie-chart-fill" style="font-size: 3rem;"></i>
                        <p class="mt-2">Chưa có dữ liệu</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Sản phẩm cần chú ý (Full width - 1 dòng riêng) -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-exclamation-triangle-fill text-warning"></i> Sản phẩm cần chú ý
                </h5>
                <hr>
                
                <div class="row">
                    <!-- Bán chậm -->
                    <div class="col-md-4">
                        <h6 class="text-warning"><i class="bi bi-arrow-down-circle-fill"></i> Bán chậm ({{ $sanPhamBanCham->count() }})</h6>
                        @if($sanPhamBanCham->count() > 0)
                            <ul class="list-unstyled small">
                                @foreach($sanPhamBanCham as $sp)
                                    <li class="mb-1">
                                        • <a href="{{ route('admin.products.edit', $sp->MaSanPham) }}" class="text-decoration-none">
                                            {{ Str::limit($sp->TenSanPham, 40) }}
                                        </a>
                                        <span class="badge bg-warning text-dark">{{ $sp->TongSoLuong }} sp</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted small">Không có</p>
                        @endif
                    </div>
                    
                    <!-- Không bán được -->
                    <div class="col-md-4">
                        <h6 class="text-danger"><i class="bi bi-x-circle-fill"></i> Không bán được ({{ $sanPhamKhongBan->count() }})</h6>
                        @if($sanPhamKhongBan->count() > 0)
                            <ul class="list-unstyled small">
                                @foreach($sanPhamKhongBan as $sp)
                                    <li class="mb-1">
                                        • <a href="{{ route('admin.products.edit', $sp->MaSanPham) }}" class="text-decoration-none">
                                            {{ Str::limit($sp->TenSanPham, 40) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted small">Không có</p>
                        @endif
                    </div>
                    
                    <!-- Sắp hết hàng -->
                    <div class="col-md-4" id="sanPhamSapHet">
                        <h6 class="text-danger"><i class="bi bi-box-seam"></i> Sắp hết hàng ({{ $sanPhamSapHetChiTiet->count() }})</h6>
                        @if($sanPhamSapHetChiTiet->count() > 0)
                            <ul class="list-unstyled small">
                                @foreach($sanPhamSapHetChiTiet as $sp)
                                    <li class="mb-1">
                                        • <a href="{{ route('admin.products.edit', $sp->MaSanPham) }}" class="text-decoration-none">
                                            {{ Str::limit($sp->TenSanPham, 30) }} - {{ $sp->MauSac }}
                                        </a>
                                        <span class="badge bg-danger">Còn {{ $sp->SoLuongTon }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted small">Không có</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Đơn hàng gần đây -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history"></i> Đơn hàng gần đây
                    </h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">
                        Xem tất cả
                    </a>
                </div>
                @if($donHangGanDay->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donHangGanDay as $dh)
                                <tr>
                                    <td><strong>#{{ $dh->MaDonHang }}</strong></td>
                                    <td>{{ $dh->nguoiDung->HoTen }}</td>
                                    <td>{{ $dh->NgayDat->format('d/m/Y H:i') }}</td>
                                    <td><strong class="text-primary">{{ number_format($dh->TongTien, 0, ',', '.') }}₫</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $dh->mauTrangThai() }}">
                                            {{ $dh->TrangThai }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $dh->MaDonHang) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye-fill"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                        <p class="mt-2">Chưa có đơn hàng nào</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Toggle custom date inputs
function toggleCustomDate() {
    const filter = document.getElementById('filterSelect').value;
    const tuNgayDiv = document.getElementById('tuNgayDiv');
    const denNgayDiv = document.getElementById('denNgayDiv');
    
    if (filter === 'custom') {
        tuNgayDiv.style.display = 'block';
        denNgayDiv.style.display = 'block';
    } else {
        tuNgayDiv.style.display = 'none';
        denNgayDiv.style.display = 'none';
    }
}

// ========== BIỂU ĐỒ 1: LINE CHART - Doanh thu & Đơn hàng ==========
@if($bieuDoDoanhThu->count() > 0)
const ctxRevenue = document.getElementById('revenueChart').getContext('2d');

const labels = {!! json_encode($bieuDoDoanhThu->pluck('Ngay')->map(function($date) {
    return \Carbon\Carbon::parse($date)->format('d/m');
})) !!};

const doanhThuData = {!! json_encode($bieuDoDoanhThu->pluck('DoanhThu')) !!};
const donHangData = {!! json_encode($bieuDoDoanhThu->pluck('SoDon')) !!};

new Chart(ctxRevenue, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Doanh thu (₫)',
                data: doanhThuData,
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                tension: 0.4,
                yAxisID: 'y',
                fill: true,
            },
            {
                label: 'Số đơn hàng',
                data: donHangData,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4,
                yAxisID: 'y1',
                fill: true,
            }
        ]
    },
    options: {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    font: {
                        size: 13
                    }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            if (context.datasetIndex === 0) {
                                label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + '₫';
                            } else {
                                label += context.parsed.y + ' đơn';
                            }
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN').format(value) + '₫';
                    }
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                grid: {
                    drawOnChartArea: false,
                },
                ticks: {
                    callback: function(value) {
                        return value + ' đơn';
                    }
                }
            },
        }
    }
});
@endif

// ========== BIỂU ĐỒ 2: PIE CHART - Biểu đồ trạng thái đơn hàng ==========
@if($thongKeDonHang->count() > 0)
const ctxStatus = document.getElementById('statusChart').getContext('2d');

const statusLabels = {!! json_encode($thongKeDonHang->keys()) !!};
const statusData = {!! json_encode($thongKeDonHang->values()) !!};

new Chart(ctxStatus, {
    type: 'pie',
    data: {
        labels: statusLabels,
        datasets: [{
            data: statusData,
            backgroundColor: [
                '#ffc107', // Chờ xác nhận
                '#0dcaf0', // Đã xác nhận
                '#0d6efd', // Đang giao hàng
                '#198754', // Đã giao hàng
                '#dc3545'  // Đã hủy
            ],
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: {
                        size: 13
                    },
                    padding: 15,
                    boxWidth: 15
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                        return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                    }
                }
            }
        }
    }
});
@endif

// ========== BIỂU ĐỒ 3: DOUGHNUT CHART - Tỷ lệ doanh thu theo danh mục ==========
@if($doanhThuTheoDanhMuc->count() > 0)
const ctxCategory = document.getElementById('categoryChart').getContext('2d');

const categoryLabels = {!! json_encode($doanhThuTheoDanhMuc->pluck('TenDanhMuc')) !!};
const categoryData = {!! json_encode($doanhThuTheoDanhMuc->pluck('TongDoanhThu')) !!};

new Chart(ctxCategory, {
    type: 'doughnut',
    data: {
        labels: categoryLabels,
        datasets: [{
            data: categoryData,
            backgroundColor: [
                '#0d6efd',
                '#198754',
                '#ffc107',
                '#dc3545',
                '#0dcaf0',
                '#6f42c1',
            ],
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        cutout: '55%', // Thu nhỏ phần giữa để cân đối hơn
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: {
                        size: 13
                    },
                    padding: 12,
                    boxWidth: 15,
                    generateLabels: function(chart) {
                        const data = chart.data;
                        if (data.labels.length && data.datasets.length) {
                            return data.labels.map((label, i) => {
                                const meta = chart.getDatasetMeta(0);
                                const style = meta.controller.getStyle(i);
                                return {
                                    text: label,
                                    fillStyle: style.backgroundColor,
                                    hidden: isNaN(data.datasets[0].data[i]) || meta.data[i].hidden,
                                    index: i
                                };
                            });
                        }
                        return [];
                    }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const value = new Intl.NumberFormat('vi-VN').format(context.parsed) + '₫';
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                        return context.label + ': ' + value + ' (' + percentage + '%)';
                    }
                }
            }
        },
        layout: {
            padding: {
                bottom: 10
            }
        }
    }
});
@endif
</script>
@endsection