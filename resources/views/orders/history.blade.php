@extends('layouts.app')

@section('title', 'Lịch sử đơn hàng')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">
        <i class="bi bi-clock-history"></i> Lịch Sử Đơn Hàng
    </h2>
    
    @if($donHang->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donHang as $dh)
                <tr>
                    <td><strong>#{{ $dh->MaDonHang }}</strong></td>
                    <td>{{ $dh->NgayDat->format('d/m/Y H:i') }}</td>
                    <td><strong class="text-primary">{{ number_format($dh->TongTien, 0, ',', '.') }}₫</strong></td>
                    <td>
                        <span class="badge bg-info">{{ $dh->PTThanhToan }}</span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $dh->mauTrangThai() }}">{{ $dh->TrangThai }}</span>
                    </td>
                    <td>
                        <a href="{{ route('orders.detail', $dh->MaDonHang) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> Xem chi tiết
                        </a>
                        
                        {{-- Nút hủy đơn - CHỈ hiện khi "Chờ xác nhận" --}}
                        @if($dh->TrangThai == 'Chờ xác nhận')
                            <form action="{{ route('orders.cancel', $dh->MaDonHang) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Bạn có chắc muốn hủy đơn hàng #{{ $dh->MaDonHang }}?\n\nSản phẩm sẽ được hoàn lại vào kho.')">
                                    <i class="bi bi-x-circle"></i> Hủy đơn
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-center mt-3">
        {{ $donHang->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-bag-x" style="font-size: 100px; color: #ccc;"></i>
        <h4 class="mt-3">Bạn chưa có đơn hàng nào</h4>
        <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
            <i class="bi bi-shop"></i> Mua sắm ngay
        </a>
    </div>
    @endif
</div>
@endsection