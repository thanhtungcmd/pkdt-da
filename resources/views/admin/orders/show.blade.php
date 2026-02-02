@extends('admin.layouts.app')
@section('title', 'Chi tiết đơn hàng')
@section('page-title', 'Chi tiết đơn hàng #' . $donHang->MaDonHang)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <h5><i class="bi bi-info-circle"></i> Thông tin khách hàng</h5>
                <hr>
                <p><strong>Họ tên:</strong> {{ $donHang->nguoiDung->HoTen }}</p>
                <p><strong>Email:</strong> {{ $donHang->nguoiDung->Email }}</p>
                <p><strong>Số điện thoại:</strong> {{ $donHang->nguoiDung->SoDienThoai }}</p>
                <p><strong>Địa chỉ:</strong> {{ $donHang->DiaChiGiaoHang }}</p>
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
                            <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                            <td><h5 class="text-primary mb-0">{{ number_format($donHang->TongTien, 0, ',', '.') }}₫</h5></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5><i class="bi bi-gear"></i> Cập nhật trạng thái</h5>
                <hr>
                
                {{-- Hiển thị trạng thái hiện tại - CỰC KỲ NỔI BẬT --}}
                <div class="mb-4 text-center p-3 bg-light rounded">
                    <small class="text-muted d-block mb-2 fw-bold">TRẠNG THÁI HIỆN TẠI</small>
                    <h3 class="mb-0">
                        <span class="badge bg-{{ $donHang->mauTrangThai() }} px-4 py-2" style="font-size: 1.2rem;">
                            <i class="bi bi-
                                @if($donHang->TrangThai == 'Chờ xác nhận') clock
                                @elseif($donHang->TrangThai == 'Đã xác nhận') check-circle
                                @elseif($donHang->TrangThai == 'Đang giao hàng') truck
                                @elseif($donHang->TrangThai == 'Đã giao hàng') check-circle-fill
                                @elseif($donHang->TrangThai == 'Đã hủy') x-circle
                                @endif
                            "></i>
                            {{ $donHang->TrangThai }}
                        </span>
                    </h3>
                </div>
                
                @if(in_array($donHang->TrangThai, ['Đã hủy', 'Đã giao hàng']))
                    {{-- Nếu đơn đã hủy hoặc đã giao hàng, không cho phép thay đổi --}}
                    <div class="alert alert-{{ $donHang->TrangThai == 'Đã hủy' ? 'danger' : 'success' }}">
                        <i class="bi bi-{{ $donHang->TrangThai == 'Đã hủy' ? 'x-circle' : 'check-circle' }}"></i> 
                        <strong>{{ $donHang->TrangThai }}</strong><br>
                        @if($donHang->TrangThai == 'Đã hủy')
                            Đơn hàng đã bị hủy bởi khách hàng. Không thể thay đổi trạng thái.
                        @else
                            Đơn hàng đã hoàn thành. Không thể thay đổi trạng thái.
                        @endif
                    </div>
                @else
                    {{-- Form cập nhật trạng thái --}}
                    <form action="{{ route('admin.orders.updateStatus', $donHang->MaDonHang) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn trạng thái</label>
                            <select name="TrangThai" class="form-select form-select-lg">
                                @foreach($trangThaiAdmin as $tt)
                                    <option value="{{ $tt }}" {{ $donHang->TrangThai == $tt ? 'selected' : '' }}>
                                        {{ $tt }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> 
                                @if(count($trangThaiAdmin) > 1)
                                    Trạng thái tiếp theo: <strong class="text-primary">{{ $trangThaiAdmin[1] }}</strong>
                                @else
                                    Không thể thay đổi trạng thái
                                @endif
                            </small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="bi bi-check-lg"></i> Cập nhật trạng thái
                        </button>
                    </form>
                @endif
                
                <hr>
                
                <div>
                    <p><strong>Ngày đặt:</strong><br>{{ $donHang->NgayDat->format('d/m/Y H:i') }}</p>
                    <p><strong>Thanh toán:</strong><br>
                        <span class="badge bg-info fs-6">{{ $donHang->PTThanhToan }}</span>
                    </p>
                </div>
                
                {{-- Quy trình đơn hàng --}}
                <hr>
                <h6><i class="bi bi-diagram-3"></i> Quy trình xử lý</h6>
                <div class="mt-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <i class="bi bi-{{ $donHang->TrangThai == 'Chờ xác nhận' ? 'circle-fill text-warning' : 'circle text-muted' }}" style="font-size: 1.2rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <strong class="{{ $donHang->TrangThai == 'Chờ xác nhận' ? 'text-warning' : 'text-muted' }}">
                                Chờ xác nhận
                            </strong>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <i class="bi bi-{{ $donHang->TrangThai == 'Đã xác nhận' ? 'circle-fill text-info' : 'circle text-muted' }}" style="font-size: 1.2rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <strong class="{{ $donHang->TrangThai == 'Đã xác nhận' ? 'text-info' : 'text-muted' }}">
                                Đã xác nhận
                            </strong>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <i class="bi bi-{{ $donHang->TrangThai == 'Đang giao hàng' ? 'circle-fill text-primary' : 'circle text-muted' }}" style="font-size: 1.2rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <strong class="{{ $donHang->TrangThai == 'Đang giao hàng' ? 'text-primary' : 'text-muted' }}">
                                Đang giao hàng
                            </strong>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-{{ $donHang->TrangThai == 'Đã giao hàng' ? 'check-circle-fill text-success' : 'circle text-muted' }}" style="font-size: 1.2rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <strong class="{{ $donHang->TrangThai == 'Đã giao hàng' ? 'text-success' : 'text-muted' }}">
                                Đã giao hàng
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection