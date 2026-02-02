@extends('admin.layouts.app')

@section('title', 'Chi tiết phản hồi')
@section('page-title', 'Chi tiết phản hồi #' . $phanHoi->MaPhanHoi)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <h5>{{ $phanHoi->TieuDe }}</h5>
                    <div class="d-flex gap-3 text-muted">
                        <span><i class="bi bi-person"></i> {{ $phanHoi->nguoiDung->HoTen }}</span>
                        <span><i class="bi bi-envelope"></i> {{ $phanHoi->nguoiDung->Email }}</span>
                        <span><i class="bi bi-calendar"></i> {{ $phanHoi->NgayGui->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-4">
                    <h6>Nội dung:</h6>
                    <p style="white-space: pre-line;">{{ $phanHoi->NoiDung }}</p>
                </div>

                <!-- Danh sách trả lời -->
                @if($phanHoi->traLoi->count() > 0)
                <div class="mb-4">
                    <h6><i class="bi bi-reply-fill"></i> Các trả lời ({{ $phanHoi->traLoi->count() }}):</h6>
                    <hr>
                    @foreach($phanHoi->traLoi as $traLoi)
                    <div class="card mb-3 bg-light">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong class="text-primary">{{ $traLoi->admin->HoTen }}</strong>
                                    <small class="text-muted ms-2">
                                        <i class="bi bi-clock"></i> {{ $traLoi->NgayTraLoi->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                <form action="{{ route('admin.feedback.reply.delete', $traLoi->MaTraLoi) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Xóa trả lời này?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <p class="mb-0" style="white-space: pre-line;">{{ $traLoi->NoiDung }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Form trả lời -->
                <div class="mb-4">
                    <h6><i class="bi bi-reply"></i> Trả lời phản hồi:</h6>
                    <hr>
                    <form action="{{ route('admin.feedback.reply', $phanHoi->MaPhanHoi) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="noi_dung" class="form-control @error('noi_dung') is-invalid @enderror" 
                                      rows="5" placeholder="Nhập nội dung trả lời...">{{ old('noi_dung') }}</textarea>
                            @error('noi_dung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Gửi trả lời
                        </button>
                    </form>
                </div>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.feedback.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                    <form action="{{ route('admin.feedback.destroy', $phanHoi->MaPhanHoi) }}" 
                          method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" 
                                onclick="return confirm('Xóa phản hồi này?')">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6>Thông tin người gửi</h6>
                <hr>
                <p><strong>Họ tên:</strong><br>{{ $phanHoi->nguoiDung->HoTen }}</p>
                <p><strong>Email:</strong><br>{{ $phanHoi->nguoiDung->Email }}</p>
                <p><strong>Số điện thoại:</strong><br>{{ $phanHoi->nguoiDung->SoDienThoai }}</p>
                <p><strong>Trạng thái:</strong><br>
                    <span class="badge bg-{{ $phanHoi->TrangThai ? 'success' : 'warning' }}">
                        {{ $phanHoi->TrangThai ? 'Đã xem' : 'Chưa xem' }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection