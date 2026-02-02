@extends('layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 100px;"></i>
                    </div>
                    <h2 class="text-success mb-3">Đặt hàng thành công!</h2>
                    <p class="lead">Cảm ơn bạn đã mua hàng tại Phụ Kiện Điện Tử</p>
                    <p class="text-muted">Mã đơn hàng: <strong>#{{ $donHang->MaDonHang }}</strong></p>
                    
                    <hr class="my-4">
                    
                    <div class="row text-start">
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded bg-light">
                                <h6><i class="bi bi-person-fill"></i> Thông tin người nhận:</h6>
                                <p class="mb-1"><strong>{{ $donHang->nguoiDung->HoTen }}</strong></p>
                                <p class="mb-1">{{ $donHang->nguoiDung->SoDienThoai }}</p>
                                <p class="mb-0">{{ $donHang->DiaChiGiaoHang }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded bg-light">
                                <h6><i class="bi bi-receipt"></i> Thông tin đơn hàng:</h6>
                                <p class="mb-1">Ngày đặt: <strong>{{ $donHang->NgayDat->format('d/m/Y H:i') }}</strong></p>
                                <p class="mb-1">Thanh toán: <strong>{{ $donHang->PTThanhToan }}</strong></p>
                                <p class="mb-0">Trạng thái: 
                                    <span class="badge bg-{{ $donHang->mauTrangThai() }} rounded-pill px-3">{{ $donHang->TrangThai }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive mt-4">
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
                                
                                @if($donHang->SoTienGiam > 0 && $donHang->MaMaGiamGia)
                                {{-- Có mã giảm giá - Hiển thị chi tiết --}}
                                <tr class="border-top border-2">
                                    <td colspan="3" class="text-end pt-3">Giá gốc:</td>
                                    <td class="pt-3">{{ number_format($donHang->TongTien + $donHang->SoTienGiam, 0, ',', '.') }}₫</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end text-success">
                                        <i class="bi bi-tag-fill"></i> Giảm giá:
                                    </td>
                                    <td><strong class="text-success">-{{ number_format($donHang->SoTienGiam, 0, ',', '.') }}₫</strong></td>
                                </tr>
                                <tr class="table-active border-top border-2">
                                    <td colspan="3" class="text-end pt-3"><strong>Tổng cộng:</strong></td>
                                    <td class="pt-3"><h5 class="text-danger mb-0"><strong>{{ number_format($donHang->TongTien, 0, ',', '.') }}₫</strong></h5></td>
                                </tr>
                                @else
                                {{-- Không có mã giảm giá - Hiển thị bình thường --}}
                                <tr class="border-top border-2">
                                    <td colspan="3" class="text-end pt-3"><strong>Tổng cộng:</strong></td>
                                    <td class="pt-3"><h5 class="text-danger mb-0"><strong>{{ number_format($donHang->TongTien, 0, ',', '.') }}₫</strong></h5></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="alert alert-info mt-4">
                        <i class="bi bi-info-circle-fill"></i> 
                        Đơn hàng của bạn đang được xử lý. Chúng tôi sẽ liên hệ với bạn sớm nhất!
                    </div>
                    
                    <div class="d-flex gap-2 justify-content-center mt-4">
                        <a href="{{ route('orders.history') }}" class="btn btn-primary">
                            <i class="bi bi-clock-history"></i> Xem đơn hàng của tôi
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection