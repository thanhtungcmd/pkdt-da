@extends('layouts.auth')

@section('title', 'Mật khẩu tạm thời')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4 text-center">
                <h3 class="mb-3">Mật khẩu tạm thời của bạn</h3>
                <p>Sử dụng mật khẩu dưới đây để đăng nhập và đổi lại mật khẩu mới:</p>
                <h4 class="text-primary">{{ $tempPassword }}</h4>
                <a href="{{ route('login') }}" class="btn btn-success mt-4">Đăng nhập</a>
            </div>
        </div>
    </div>
</div>
@endsection