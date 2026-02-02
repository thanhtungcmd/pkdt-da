@extends('admin.layouts.app')

@section('title', 'Sửa tin tức')
@section('page-title', 'Sửa tin tức')

@section('content')
<div class="row">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.news.update', $tinTuc->MaTinTuc) }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề *</label>
                        <input type="text" name="TieuDe" 
                               class="form-control @error('TieuDe') is-invalid @enderror" 
                               value="{{ old('TieuDe', $tinTuc->TieuDe) }}" required>
                        @error('TieuDe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ảnh minh họa *</label>
                        <input type="file" name="AnhMinhHoa" 
                               class="form-control @error('AnhMinhHoa') is-invalid @enderror">
                        @error('AnhMinhHoa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        {{-- Ảnh hiện tại --}}
                        @if ($tinTuc->AnhMinhHoa)
                            <img src="{{ $tinTuc->AnhMinhHoa }}" 
                                 class="img-thumbnail mt-2" 
                                 style="max-width: 300px;">
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nội dung *</label>
                        <textarea name="NoiDung" 
                                  class="form-control @error('NoiDung') is-invalid @enderror" 
                                  rows="10" required>{{ old('NoiDung', $tinTuc->NoiDung) }}</textarea>
                        @error('NoiDung')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Cập nhật
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