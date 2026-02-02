@extends('layouts.app')

@section('title', 'Thanh toán VNPay')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center p-5">
                    <img src="/images/vnpay-logo.png" alt="VNPay" class="mb-4" style="height: 50px;">
                    <h3 class="mb-4">Thanh Toán VNPay</h3>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Đây là trang giả lập thanh toán VNPay
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5>Thông tin đơn hàng</h5>
                            <hr>
                            <p><strong>Mã đơn hàng:</strong> #{{ $donHang->MaDonHang }}</p>
                            <p><strong>Số tiền:</strong> <span class="text-primary h4">{{ number_format($donHang->TongTien, 0, ',', '.') }}₫</span></p>
                        </div>
                    </div>
                    
                    <form action="{{ route('vnpay.confirm', $donHang->MaDonHang) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-2">
                            <i class="bi bi-credit-card-fill"></i> Xác nhận thanh toán
                        </button>
                    </form>
                    
                    <a href="{{ route('orders.detail', $donHang->MaDonHang) }}" class="btn btn-outline-secondary w-100">
                        Hủy
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection