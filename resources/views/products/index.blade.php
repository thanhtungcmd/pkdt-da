@extends('layouts.app')

@section('title', 'Sản phẩm')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-funnel-fill"></i> Lọc sản phẩm
                    </h5>
                    <hr>
                    
                    <form action="{{ route('products.index') }}" method="GET" id="filterForm">
                        <!-- Danh mục -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Danh mục</label>
                            <select name="danh_muc" class="form-select" onchange="this.form.submit()">
                                <option value="">Tất cả danh mục</option>
                                @foreach($danhMuc as $dm)
                                    <option value="{{ $dm->MaDanhMuc }}" {{ request('danh_muc') == $dm->MaDanhMuc ? 'selected' : '' }}>
                                        {{ $dm->TenDanhMuc }} ({{ $dm->sanPham->count() }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Thương hiệu -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Thương hiệu</label>
                            <select name="thuong_hieu" class="form-select" onchange="this.form.submit()">
                                <option value="">Tất cả thương hiệu</option>
                                @php
                                    $brands = \App\Models\SanPham::distinct()->pluck('ThuongHieu')->filter();
                                @endphp
                                @foreach($brands as $brand)
                                    <option value="{{ $brand }}" {{ request('thuong_hieu') == $brand ? 'selected' : '' }}>
                                        {{ $brand }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Đánh giá -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Đánh giá</label>
                            <select name="rating" class="form-select" onchange="this.form.submit()">
                                <option value="">Tất cả đánh giá</option>
                                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>
                                    ⭐⭐⭐⭐⭐ 5 sao
                                </option>
                                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>
                                    ⭐⭐⭐⭐ Từ 4 sao trở lên
                                </option>
                                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>
                                    ⭐⭐⭐ Từ 3 sao trở lên
                                </option>
                                
                            </select>
                        </div>

                        <!-- Khoảng giá -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Khoảng giá</label>
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <input type="number" 
                                           name="gia_tu" 
                                           class="form-control form-control-sm" 
                                           placeholder="Từ"
                                           value="{{ request('gia_tu') }}"
                                           min="0">
                                </div>
                                <div class="col-6">
                                    <input type="number" 
                                           name="gia_den" 
                                           class="form-control form-control-sm" 
                                           placeholder="Đến"
                                           value="{{ request('gia_den') }}"
                                           min="0">
                                </div>
                            </div>
                            <!-- Nút lọc nhanh -->
                            <div class="d-grid gap-1">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setPrice(0, 1000000)">
                                    Dưới 1 triệu
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setPrice(1000000, 3000000)">
                                    1 - 3 triệu
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setPrice(3000000, 5000000)">
                                    3 - 5 triệu
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setPrice(5000000, 10000000)">
                                    5 - 10 triệu
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setPrice(10000000, 999999999)">
                                    Trên 10 triệu
                                </button>
                            </div>
                        </div>
                        
                        <!-- Sắp xếp -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sắp xếp</label>
                            <select name="sort" class="form-select" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá thấp → cao</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá cao → thấp</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-search"></i> Áp dụng
                        </button>
                        
                        @if(request()->hasAny(['danh_muc', 'thuong_hieu', 'gia_tu', 'gia_den', 'sort']))
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-clockwise"></i> Xóa bộ lọc
                        </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>
                    <i class="bi bi-grid-fill"></i> Sản phẩm
                    <span class="badge bg-primary">{{ $sanPham->total() }}</span>
                </h3>
            </div>
            
            <!-- Hiển thị bộ lọc đang áp dụng -->
            @if(request()->hasAny(['danh_muc', 'thuong_hieu', 'gia_tu', 'gia_den']))
            <div class="alert alert-info mb-3">
                <strong>Đang lọc:</strong>
                @if(request('danh_muc'))
                    <span class="badge bg-secondary">
                        Danh mục: {{ $danhMuc->find(request('danh_muc'))->TenDanhMuc ?? '' }}
                    </span>
                @endif
                @if(request('thuong_hieu'))
                    <span class="badge bg-secondary">Thương hiệu: {{ request('thuong_hieu') }}</span>
                @endif
                @if(request('gia_tu'))
                    <span class="badge bg-secondary">Từ: {{ number_format(request('gia_tu')) }}đ</span>
                @endif
                @if(request('gia_den'))
                    <span class="badge bg-secondary">Đến: {{ number_format(request('gia_den')) }}đ</span>
                @endif
            </div>
            @endif
            
            @if($sanPham->count() > 0)
                <div class="row g-4">
                    @foreach($sanPham as $sp)
                    <div class="col-md-6 col-lg-4">
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
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $sanPham->appends(request()->query())->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Không tìm thấy sản phẩm nào!
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function setPrice(from, to) {
    document.querySelector('input[name="gia_tu"]').value = from;
    document.querySelector('input[name="gia_den"]').value = to;
    document.getElementById('filterForm').submit();
}
</script>
@endsection