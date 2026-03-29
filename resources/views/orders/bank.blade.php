@extends('layouts.app')

@section('title', 'Thanh toán chuyển khoản')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow">
                <div class="card-body text-center p-5">

                    <h3 class="mb-3 text-primary">Thanh toán chuyển khoản ngân hàng</h3>
                    <p class="text-muted">Quét mã QR bằng app ngân hàng để thanh toán</p>

                    <div class="my-4">
                        <img src="{{ $qrUrl }}" alt="VietQR" class="img-fluid" style="max-width:300px;">
                    </div>

                    <div class="alert alert-warning text-start">
                        <strong>Nội dung chuyển khoản bắt buộc:</strong><br>
                        <span class="text-danger fw-bold">DH {{ $donHang->MaDonHang }}</span>
                    </div>

                    <div class="text-start border rounded p-3 bg-light">
                        <p class="mb-1"><strong>Ngân hàng:</strong> MB Bank</p>
                        <p class="mb-1"><strong>Số tài khoản:</strong> {{ $accountNo }}</p>
                        <p class="mb-1"><strong>Chủ tài khoản:</strong> {{ accountName }}</p>
                        <p class="mb-0"><strong>Số tiền:</strong> 
                            <span class="text-danger fw-bold">
                                {{ number_format($donHang->TongTien, 0, ',', '.') }}₫
                            </span>
                        </p>
                    </div>

                    <div class="alert alert-info mt-4">
                        Sau khi chuyển khoản thành công, hệ thống sẽ xác nhận đơn hàng cho bạn.
                    </div>

                    <a href="{{ route('orders.success', $donHang->MaDonHang) }}" 
                       class="btn btn-success mt-3">
                        Tôi đã chuyển khoản
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection