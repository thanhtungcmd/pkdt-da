@extends('layouts.app')

@section('title', 'Tin tức')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">
        <i class="bi bi-newspaper"></i> Tin Tức & Bài Viết
    </h2>
    
    <div class="row g-4">
        @foreach($tinTuc as $tt)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <a href="{{ route('news.show', $tt->MaTinTuc) }}">
                    <img src="{{ $tt->AnhMinhHoa }}" class="card-img-top" alt="{{ $tt->TieuDe }}" 
                     style="height: 220px; object-fit: cover;">
                </a>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">
                        <a href="{{ route('news.show', $tt->MaTinTuc) }}" class="text-decoration-none text-dark">
                            {{ $tt->TieuDe }}
                        </a>
                    </h5>
                    <p class="card-text text-muted flex-grow-1">{{ $tt->tomTat(120) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-calendar"></i> {{ $tt->NgayDang->format('d/m/Y') }}
                        </small>
                        <a href="{{ route('news.show', $tt->MaTinTuc) }}" class="btn btn-sm btn-outline-primary">
                            Đọc thêm <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    @if($tinTuc->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-newspaper" style="font-size: 100px; color: #ccc;"></i>
        <h4 class="mt-3">Chưa có tin tức nào</h4>
    </div>
    @endif
    
    <div class="d-flex justify-content-center mt-4">
        {{ $tinTuc->links() }}
    </div>
</div>
@endsection