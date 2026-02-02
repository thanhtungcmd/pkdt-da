@extends('admin.layouts.app')

@section('page-title', 'Lịch sử sử dụng mã giảm giá')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.coupons.index') }}">Mã giảm giá</a>
                    </li>
                    <li class="breadcrumb-item active">Lịch sử sử dụng</li>
                </ol>
            </nav>
            <h2><i class="bi bi-clock-history"></i> Lịch sử sử dụng mã giảm giá</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Thông tin mã giảm giá -->
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-tag-fill"></i> Thông tin mã: <strong>{{ $maGiamGia->MaCode }}</strong></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <p class="mb-1"><strong>Loại giảm:</strong></p>
                    @if($maGiamGia->LoaiGiam === 'fixed')
                        <span class="badge bg-info">Cố định: {{ number_format($maGiamGia->GiaTri, 0, ',', '.') }}đ</span>
                    @else
                        <span class="badge bg-warning text-dark">Phần trăm: {{ $maGiamGia->GiaTri }}%</span>
                    @endif
                </div>
                <div class="col-md-3">
                    <p class="mb-1"><strong>Đơn tối thiểu:</strong></p>
                    <p class="mb-0">{{ $maGiamGia->DonToiThieu ? number_format($maGiamGia->DonToiThieu, 0, ',', '.') . 'đ' : 'Không giới hạn' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="mb-1"><strong>Đã sử dụng:</strong></p>
                    <p class="mb-0">
                        <span class="badge bg-secondary fs-6">
                            {{ $maGiamGia->DaSuDung }} / {{ $maGiamGia->GioiHanSuDung ?? '∞' }} lượt
                        </span>
                    </p>
                </div>
                <div class="col-md-3">
                    <p class="mb-1"><strong>Trạng thái:</strong></p>
                    @php $validation = $maGiamGia->kiemTraHopLe(); @endphp
                    @if($validation['valid'])
                        <span class="badge bg-success fs-6">
                            <i class="bi bi-check-circle"></i> Đang hoạt động
                        </span>
                    @else
                        <span class="badge bg-danger fs-6">
                            <i class="bi bi-x-circle"></i> {{ $validation['message'] }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary">{{ $maGiamGia->lichSuSuDung->count() }}</h3>
                    <p class="text-muted mb-0">Lượt sử dụng</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">{{ number_format($maGiamGia->lichSuSuDung->sum('SoTienGiam'), 0, ',', '.') }}đ</h3>
                    <p class="text-muted mb-0">Tổng tiền đã giảm</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info">{{ $maGiamGia->lichSuSuDung->unique('MaNguoiDung')->count() }}</h3>
                    <p class="text-muted mb-0">Người dùng khác nhau</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng lịch sử -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> Chi tiết lịch sử sử dụng</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Thời gian</th>
                            <th>Người dùng</th>
                            <th>Mã đơn hàng</th>
                            <th>Số tiền giảm</th>
                            <th>Trạng thái đơn</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($maGiamGia->lichSuSuDung as $index => $lichSu)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <i class="bi bi-calendar-event"></i> {{ $lichSu->ThoiGianSuDung->format('d/m/Y') }}<br>
                                <small class="text-muted"><i class="bi bi-clock"></i> {{ $lichSu->ThoiGianSuDung->format('H:i:s') }}</small>
                            </td>
                            <td>
                                @if($lichSu->nguoiDung)
                                    <i class="bi bi-person-fill"></i> {{ $lichSu->nguoiDung->HoTen }}<br>
                                    <small class="text-muted">{{ $lichSu->nguoiDung->Email }}</small>
                                @else
                                    <span class="text-muted">Người dùng đã xóa</span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-primary">#{{ $lichSu->MaDonHang }}</strong>
                            </td>
                            <td>
                                <strong class="text-success">-{{ number_format($lichSu->SoTienGiam, 0, ',', '.') }}đ</strong>
                            </td>
                            <td>
                                @if($lichSu->donHang)
                                    @php
                                        $statusColors = [
                                            'Chờ xác nhận' => 'warning',
                                            'Đã xác nhận' => 'info',
                                            'Đang giao' => 'primary',
                                            'Đã giao' => 'success',
                                            'Đã hủy' => 'danger'
                                        ];
                                        $color = $statusColors[$lichSu->donHang->TrangThai] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ $lichSu->donHang->TrangThai }}</span>
                                @else
                                    <span class="badge bg-secondary">Không rõ</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($lichSu->donHang)
                                    <a href="{{ route('admin.orders.show', $lichSu->MaDonHang) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Xem đơn hàng">
                                        <i class="bi bi-eye"></i> Chi tiết
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox" style="font-size: 48px;"></i>
                                <p class="mt-2 mb-0">Chưa có lịch sử sử dụng nào</p>
                                <small>Mã giảm giá này chưa được sử dụng</small>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection