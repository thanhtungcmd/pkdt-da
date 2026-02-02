@extends('admin.layouts.app')

@section('title', 'Thêm tin tức')
@section('page-title', 'Thêm tin tức mới')

@section('content')
<div class="row">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">

                <!-- THÊM enctype -->
                <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề *</label>
                        <input type="text" name="TieuDe" 
                               class="form-control @error('TieuDe') is-invalid @enderror" 
                               value="{{ old('TieuDe') }}" required>
                        @error('TieuDe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ảnh minh họa *</label>

                        <!-- ĐỔI THÀNH FILE -->
                        <input type="file" name="AnhMinhHoa" 
                               class="form-control @error('AnhMinhHoa') is-invalid @enderror"
                               accept="image/*" required>

                        @error('AnhMinhHoa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nội dung *</label>
                        <textarea name="NoiDung" 
                                  class="form-control @error('NoiDung') is-invalid @enderror" 
                                  rows="10" required>{{ old('NoiDung') }}</textarea>
                        @error('NoiDung')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Hỗ trợ HTML: &lt;p&gt;, &lt;strong&gt;, &lt;br&gt;...</small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Lưu
                        </button>
                        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Hủy
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection