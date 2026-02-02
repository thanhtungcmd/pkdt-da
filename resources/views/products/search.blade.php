@extends('layouts.app')

@section('title', 'Kết quả tìm kiếm: ' . $keyword)

@section('content')
<div class="container my-5">
    <h3 class="mb-4">
        <i class="bi bi-search"></i> Kết quả tìm kiếm: "{{ $keyword }}"
        <span class="badge bg-primary">{{ $sanPham->total() }}</span>
    </h3>
    
    @if($sanPham->count() > 0)
        <div class="row g-4">
            @foreach($sanPham as $sp)
            <div class="col-md-6 col-lg-3">
                <div class="card product-card h-100">
                    <a href="{{ route('products.show', $sp->MaSanPham) }}">
                        <img src="{{ $sp->AnhChinh }}" class="card-img-top" alt="{{ $sp->TenSanPham }}">
                    </a>
                    <div class="card-body">
                        <span class="badge badge-custom mb-2">{{ $sp->danhMuc->TenDanhMuc }}</span>
                        <h6 class="card-title">
                            <a href="{{ route('products.show', $sp->MaSanPham) }}" class="text-decoration-none text-dark">
                                {{ $sp->TenSanPham }}
                            </a>
                        </h6>
                        <p class="price mb-2">
                            @if($sp->giaThapNhat() == $sp->giaCaoNhat())
                                {{ number_format($sp->giaThapNhat(), 0, ',', '.') }}₫
                            @else
                                {{ number_format($sp->giaThapNhat(), 0, ',', '.') }}₫ - {{ number_format($sp->giaCaoNhat(), 0, ',', '.') }}₫
                            @endif
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-box-seam"></i> {{ $sp->bienThe->sum('SoLuongTon') }} sẵn có
                            </small>
                            <a href="{{ route('products.show', $sp->MaSanPham) }}" class="btn btn-sm btn-primary">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $sanPham->appends(['q' => $keyword])->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-search" style="font-size: 100px; color: #ccc;"></i>
            <h4 class="mt-3">Không tìm thấy sản phẩm nào</h4>
            <p class="text-muted">Thử tìm kiếm với từ khóa khác</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
                <i class="bi bi-grid-fill"></i> Xem tất cả sản phẩm
            </a>
        </div>
    @endif
</div>
@endsection