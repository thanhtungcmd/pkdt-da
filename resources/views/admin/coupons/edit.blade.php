@extends('admin.layouts.app')

@section('page-title', 'Chỉnh sửa mã giảm giá')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2><i class="bi bi-tags-fill"></i> Chỉnh sửa mã giảm giá</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            @include('admin.coupons.create')
        </div>
    </div>

    {{-- Thống kê & Lịch sử sử dụng --}}
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Lịch sử sử dụng</h5>
        </div>
        <div class="card-body">
            @if($maGiamGia->lichSuSuDung->count() > 0)
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Thời gian</th>
                            <th>Người dùng</th>
                            <th>Đơn hàng</th>
                            <th class="text-end">Giảm giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($maGiamGia->lichSuSuDung()->latest('ThoiGianSuDung')->take(20)->get() as $lichSu)
                        <tr>
                            <td>
                                <small>
                                    <i class="bi bi-calendar"></i> {{ $lichSu->ThoiGianSuDung->format('d/m/Y') }}
                                    <i class="bi bi-clock"></i> {{ $lichSu->ThoiGianSuDung->format('H:i') }}
                                </small>
                            </td>
                            <td>
                                @if($lichSu->nguoiDung)
                                    <i class="bi bi-person"></i> {{ $lichSu->nguoiDung->HoTen }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($lichSu->donHang)
                                    <a href="{{ route('admin.orders.show', $lichSu->MaDonHang) }}" class="text-decoration-none">
                                        <i class="bi bi-receipt"></i> #{{ $lichSu->MaDonHang }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <strong class="text-success">{{ number_format($lichSu->SoTienGiam, 0, ',', '.') }}đ</strong>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="3" class="text-end"><strong>Tổng đã giảm:</strong></td>
                            <td class="text-end">
                                <strong class="text-danger">
                                    {{ number_format($maGiamGia->lichSuSuDung->sum('SoTienGiam'), 0, ',', '.') }}đ
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="text-center text-muted py-4">
                <i class="bi bi-inbox" style="font-size: 48px;"></i>
                <p class="mt-2 mb-0">Chưa có lượt sử dụng nào</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection