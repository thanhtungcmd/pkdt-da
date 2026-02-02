@extends('layouts.app')

@section('title', $tinTuc->TieuDe)

@section('content')
<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('news.index') }}">Tin tức</a></li>
            <li class="breadcrumb-item active">{{ $tinTuc->TieuDe }}</li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-lg-8">
            <article class="card">
                <img src="{{ $tinTuc->AnhMinhHoa }}" class="card-img-top" alt="{{ $tinTuc->TieuDe }}">
                <div class="card-body">
                    <h1 class="card-title">{{ $tinTuc->TieuDe }}</h1>
                    
                    <div class="d-flex gap-3 text-muted mb-4">
                        <span><i class="bi bi-person-circle"></i> {{ $tinTuc->nguoiDung->HoTen }}</span>
                        <span><i class="bi bi-calendar"></i> {{ $tinTuc->NgayDang->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="content">
                        {!! nl2br($tinTuc->NoiDung) !!}
                    </div>
                </div>
            </article>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-newspaper"></i> Bài viết liên quan
                    </h5>
                    <hr>
                    
                    @foreach($tinTucLienQuan as $tt)
                    <div class="mb-3">
                        <img src="{{ $tt->AnhMinhHoa }}" class="img-fluid rounded mb-2" alt="{{ $tt->TieuDe }}">
                        <h6>
                            <a href="{{ route('news.show', $tt->MaTinTuc) }}" class="text-decoration-none">
                                {{ $tt->TieuDe }}
                            </a>
                        </h6>
                        <small class="text-muted">
                            <i class="bi bi-calendar"></i> {{ $tt->NgayDang->format('d/m/Y') }}
                        </small>
                    </div>
                    @if(!$loop->last)
                        <hr>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection