@extends('layouts.app')

@section('title', 'Gửi phản hồi')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-chat-dots-fill text-primary" style="font-size: 4rem;"></i>
                        <h3 class="mt-3">Gửi Phản Hồi</h3>
                        <p class="text-muted">Chúng tôi luôn lắng nghe ý kiến của bạn</p>
                    </div>
                    
                    <form action="{{ route('feedback.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề *</label>
                            <input type="text" name="TieuDe" 
                                   class="form-control @error('TieuDe') is-invalid @enderror" 
                                   value="{{ old('TieuDe') }}" 
                                   placeholder="Nhập tiêu đề phản hồi" required>
                            @error('TieuDe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nội dung *</label>
                            <textarea name="NoiDung" 
                                      class="form-control @error('NoiDung') is-invalid @enderror" 
                                      rows="8" 
                                      placeholder="Nhập nội dung phản hồi, góp ý hoặc câu hỏi của bạn..." 
                                      required>{{ old('NoiDung') }}</textarea>
                            @error('NoiDung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tối thiểu 20 ký tự</small>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send-fill"></i> Gửi phản hồi
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection