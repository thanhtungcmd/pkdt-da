@extends('admin.layouts.app')
@section('title', 'Quản lý đơn hàng')
@section('page-title', 'Đơn hàng')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
            <h5><i class="bi bi-cart-fill"></i> Danh sách đơn hàng</h5>
            
            <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex gap-2">
                <select name="trang_thai" class="form-select" onchange="this.form.submit()">
                    <option value="">Tất cả trạng thái</option>
                    @foreach($trangThai as $tt)
                        <option value="{{ $tt }}" {{ request('trang_thai') == $tt ? 'selected' : '' }}>
                            {{ $tt }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
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
                        <td>
                            {{ $dh->nguoiDung->HoTen }}<br>
                            <small class="text-muted">{{ $dh->nguoiDung->SoDienThoai }}</small>
                        </td>
                        <td>{{ $dh->NgayDat->format('d/m/Y H:i') }}</td>
                        <td><strong class="text-primary">{{ number_format($dh->TongTien, 0, ',', '.') }}₫</strong></td>
                        <td>
                            <span class="badge bg-info">{{ $dh->PTThanhToan }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $dh->mauTrangThai() }}">{{ $dh->TrangThai }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $dh->MaDonHang) }}" 
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{ $donHang->links() }}
    </div>
</div>
@endsection