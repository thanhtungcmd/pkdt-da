@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">
        <i class="bi bi-receipt"></i> Chi Tiết Đơn Hàng #{{ $donHang->MaDonHang }}
    </h2>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h5><i class="bi bi-info-circle"></i> Thông tin đơn hàng</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Ngày đặt:</strong><br>{{ $donHang->NgayDat->format('d/m/Y H:i') }}</p>
                            <p><strong>Thanh toán:</strong><br>
                                <span class="badge bg-info">{{ $donHang->PTThanhToan }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Trạng thái:</strong><br>
                                <span class="badge bg-{{ $donHang->mauTrangThai() }}">{{ $donHang->TrangThai }}</span>
                            </p>
                            <p><strong>Địa chỉ giao hàng:</strong><br>{{ $donHang->DiaChiGiaoHang }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <h5><i class="bi bi-bag"></i> Sản phẩm</h5>
                    <hr>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donHang->chiTiet as $ct)
                            <tr>
                                <td>{{ $ct->sanPham->sanPham->TenSanPham }} - {{ $ct->sanPham->MauSac }}</td>
                                <td>{{ $ct->SoLuong }}</td>
                                <td>{{ number_format($ct->DonGia, 0, ',', '.') }}₫</td>
                                <td><strong>{{ number_format($ct->ThanhTien, 0, ',', '.') }}₫</strong></td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end"><h5>Tổng cộng:</h5></td>
                                <td><h5 class="text-primary">{{ number_format($donHang->TongTien, 0, ',', '.') }}₫</h5></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            {{-- Card trạng thái và hủy đơn --}}
            <div class="card mb-3">
                <div class="card-body">
                    <h5><i class="bi bi-truck"></i> Trạng thái giao hàng</h5>
                    <hr>
                    <div class="timeline">
                        @foreach(\App\Models\DonHang::TRANG_THAI as $index => $tt)
                        <div class="mb-3 {{ $donHang->TrangThai == $tt ? 'text-primary fw-bold' : 'text-muted' }}">
                            @if($donHang->TrangThai == $tt)
                                <i class="bi bi-check-circle-fill"></i>
                            @else
                                <i class="bi bi-circle"></i>
                            @endif
                            {{ $tt }}
                        </div>
                        @endforeach
                    </div>
                    
                    <hr>
                    
                    {{-- Phần hủy đơn hàng --}}
                    @if($donHang->TrangThai == 'Chờ xác nhận')
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            Bạn có thể hủy đơn hàng này miễn phí vì đơn hàng chưa được shop xác nhận.
                        </div>
                        
                        <form action="{{ route('orders.cancel', $donHang->MaDonHang) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100" 
                                    onclick="return confirm('Bạn có chắc muốn hủy đơn hàng #{{ $donHang->MaDonHang }}?\n\nSản phẩm sẽ được hoàn lại vào kho.')">
                                <i class="bi bi-x-circle-fill"></i> Hủy đơn hàng
                            </button>
                        </form>
                        
                    @elseif($donHang->TrangThai == 'Đã xác nhận')
                        <div class="alert alert-warning">
                            <i class="bi bi-box-seam"></i> 
                            <strong>Đơn hàng đang được chuẩn bị.</strong><br>
                            Đơn hàng đã được shop tiếp nhận nên không thể tự hủy. 
                            Vui lòng liên hệ: <strong>1900-xxxx</strong> để được hỗ trợ.
                        </div>
                    
                    @elseif($donHang->TrangThai == 'Đang giao hàng')
                        <div class="alert alert-primary">
                            <i class="bi bi-truck"></i> 
                            <strong>Đơn hàng đang được vận chuyển.</strong><br>
                            Bạn không thể tự hủy ở giai đoạn này. 
                            Nếu cần hỗ trợ, vui lòng liên hệ: <strong>1900-xxxx</strong>.
                        </div>
                            
                    @elseif($donHang->TrangThai == 'Đã hủy')
                        <div class="alert alert-secondary">
                            <i class="bi bi-x-circle"></i> 
                            Đơn hàng đã được hủy.
                        </div>
                        
                    @elseif($donHang->TrangThai == 'Đã giao hàng')
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill"></i> 
                            Đơn hàng đã được giao thành công!
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="{{ route('orders.history') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>
@endsection