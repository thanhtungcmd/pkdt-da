@extends('layouts.app')

@section('title', $sanPham->TenSanPham)

@section('content')
<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('products.index', ['danh_muc' => $sanPham->MaDanhMuc]) }}">
                    {{ $sanPham->danhMuc->TenDanhMuc }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ $sanPham->TenSanPham }}</li>
        </ol>
    </nav>
    
    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-5 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ $sanPham->AnhChinh }}" class="img-fluid" id="mainImage" alt="{{ $sanPham->TenSanPham }}">
                </div>
            </div>
            
            <!-- Thumbnail Images -->
            <div class="row g-2 mt-2">
                <div class="col-3">
                    <img src="{{ $sanPham->AnhChinh }}" class="img-fluid img-thumbnail cursor-pointer" 
                         onclick="changeImage('{{ $sanPham->AnhChinh }}')" alt="Main">
                </div>
                @foreach($sanPham->bienThe->take(3) as $bt)
                <div class="col-3">
                    <img src="{{ $bt->AnhMinhHoa }}" class="img-fluid img-thumbnail cursor-pointer" 
                         onclick="changeImage('{{ $bt->AnhMinhHoa }}')" alt="{{ $bt->MauSac }}">
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="col-lg-7">
            <a href="{{ route('products.index', ['danh_muc' => $sanPham->MaDanhMuc]) }}" 
               class="badge bg-primary mb-2 text-decoration-none">
                {{ $sanPham->danhMuc->TenDanhMuc }}
            </a>
            
            <h2 class="mb-3">{{ $sanPham->TenSanPham }}</h2>
            
            @if($sanPham->ThuongHieu)
            <p class="text-muted">
                <strong>Thương hiệu:</strong> 
                <a href="{{ route('products.index', ['thuong_hieu' => $sanPham->ThuongHieu]) }}" 
                   class="text-decoration-none">
                    {{ $sanPham->ThuongHieu }}
                </a>
            </p>
            @endif
            
            <div class="mb-4">
                <h3 class="price" id="selectedPrice">
                    @if($sanPham->giaThapNhat() == $sanPham->giaCaoNhat())
                        {{ number_format($sanPham->giaThapNhat(), 0, ',', '.') }}₫
                    @else
                        {{ number_format($sanPham->giaThapNhat(), 0, ',', '.') }}₫ - {{ number_format($sanPham->giaCaoNhat(), 0, ',', '.') }}₫
                    @endif
                </h3>
            </div>
            
            {{-- ============================================ --}}
            {{-- CHỈ HIỂN THỊ FORM MUA HÀNG CHO USER THƯỜNG --}}
            {{-- ============================================ --}}
            @auth
                @if(auth()->user()->VaiTro == 0)
                    {{-- Form thêm vào giỏ hàng - CHỈ CHO KHÁCH HÀNG --}}
                    <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="MaCTSanPham" id="selectedVariant" required>
                        
                        <!-- Màu sắc -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-palette-fill"></i> Màu sắc:
                            </label>
                            <div class="btn-group" role="group">
                                @foreach($sanPham->bienThe->groupBy('MauSac') as $mauSac => $variants)
                                    <input type="radio" class="btn-check" name="mau_sac" id="mau_{{ $loop->index }}" 
                                           value="{{ $mauSac }}" {{ $loop->first ? 'checked' : '' }} 
                                           onchange="updateVariants('{{ $mauSac }}')">
                                    <label class="btn btn-outline-primary" for="mau_{{ $loop->index }}">
                                        {{ $mauSac }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Kích thước / Dung lượng -->
                        @if($sanPham->bienThe->whereNotNull('KichThuoc')->count() > 0 || $sanPham->bienThe->whereNotNull('DungLuong')->count() > 0)
                        <div class="mb-4" id="sizeSection">
                            <label class="form-label fw-bold">
                                <i class="bi bi-rulers"></i> Tùy chọn:
                            </label>
                            <div id="sizeOptions">
                                <!-- Will be updated by JavaScript -->
                            </div>
                        </div>
                        @endif
                        
                        <!-- Số lượng -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-bag-fill"></i> Số lượng:
                            </label>
                            <div class="input-group" style="width: 150px;">
                                <button type="button" class="btn btn-outline-secondary" onclick="decreaseQty()">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" name="SoLuong" id="quantity" class="form-control text-center" 
                                       value="1" min="1" max="{{ min($sanPham->SoLuongTon, 20) }}" required>

                                <button type="button" class="btn btn-outline-secondary" onclick="increaseQty()">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            <small class="text-muted" id="stockInfo">Còn hàng</small>
                        </div>
                        
                        <!-- Buttons -->
                        <!-- Thêm vào giỏ -->
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-cart-plus-fill"></i> Thêm vào giỏ hàng
                        </button>

                        <!-- Mua ngay -->
                        <button type="button" class="btn btn-outline-primary btn-lg" onclick="buyNow()">
                            <i class="bi bi-lightning-fill"></i> Mua ngay
                        </button>

                    </form>
                    <form id="buyNowForm" action="{{ route('orders.checkout') }}" method="POST" style="display:none;">
                        @csrf
                        <input type="hidden" name="MaCTSanPham" id="buyNowVariant">
                        <input type="hidden" name="SoLuong" id="buyNowQuantity">
                    </form>

                @else
                    {{-- Thông báo cho ADMIN --}}
                    <div class="alert alert-info mb-4 alert-persistent alert-dismissible fade show">
                        <i class="bi bi-info-circle-fill"></i> 
                        <strong>Bạn đang đăng nhập với tài khoản Admin.</strong><br>
                        Chức năng mua hàng chỉ dành cho khách hàng. 
                        <a href="{{ route('admin.dashboard') }}" class="alert-link fw-bold">
                            <i class="bi bi-speedometer2"></i> Vào trang quản trị →
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            @else
                {{-- Chưa đăng nhập --}}
                <div class="alert alert-warning alert-persistent alert-dismissible fade show">
    <i class="bi bi-exclamation-triangle-fill"></i> Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để mua hàng.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
            @endauth
            
            <!-- Description -->
            <div class="card">
                <div class="card-body">
                    <h5><i class="bi bi-info-circle-fill"></i> Mô tả sản phẩm</h5>
                    <p class="text-muted">{{ $sanPham->MoTa }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ratings Section -->
    <div class="row mt-5" id="ratings-section">
        <div class="col-12">
            <h4 class="mb-4">
                <i class="bi bi-star-fill text-warning"></i> Đánh giá sản phẩm
            </h4>
            
            <!-- Tổng quan đánh giá -->
            @if($sanPham->danhGia()->daDuyet()->count() > 0)
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            <h1 class="display-3 mb-0">{{ number_format($sanPham->trungBinhSao(), 1) }}</h1>
                            <div class="text-warning fs-5">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($sanPham->trungBinhSao()))
                                        <i class="bi bi-star-fill"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="text-muted">{{ $sanPham->danhGia()->daDuyet()->count() }} đánh giá</p>
                        </div>
                        <div class="col-md-9">
                            @for($sao = 5; $sao >= 1; $sao--)
                                @php
                                    $count = $sanPham->danhGia()->daDuyet()->where('SoSao', $sao)->count();
                                    $total = $sanPham->danhGia()->daDuyet()->count();
                                    $percent = $total > 0 ? ($count / $total) * 100 : 0;
                                @endphp
                                <div class="d-flex align-items-center mb-2">
                                    <span class="me-2" style="width: 80px;">{{ $sao }} <i class="bi bi-star-fill text-warning"></i></span>
                                    <div class="progress flex-grow-1" style="height: 10px;">
                                        <div class="progress-bar bg-warning" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="ms-2 text-muted" style="width: 60px;">{{ $count }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Nút đánh giá -->
            @auth
                @if(auth()->user()->VaiTro == 0)
                    @php
                        $daMua = \App\Models\DonHang::where('MaNguoiDung', auth()->id())
                            ->where('TrangThai', 'đã giao hàng')
                            ->whereHas('chiTiet.sanPham.sanPham', function($q) use ($sanPham) {
                                $q->where('MaSanPham', $sanPham->MaSanPham);
                            })
                            ->exists();
                        
                        $daDanhGia = \App\Models\DanhGia::where('MaNguoiDung', auth()->id())
                            ->where('MaSanPham', $sanPham->MaSanPham)
                            ->exists();
                    @endphp
                    
                    @if($daMua && !$daDanhGia)
                    <div class="mb-4">
                        <a href="{{ route('ratings.create', ['san_pham' => $sanPham->MaSanPham]) }}" 
                        class="btn btn-warning">
                            <i class="bi bi-star"></i> Viết đánh giá
                        </a>
                    </div>
                    @elseif(!$daMua)
                    <div class="alert alert-info alert-persistent alert-dismissible fade show">
                        <i class="bi bi-info-circle"></i> Bạn cần mua sản phẩm này để có thể đánh giá
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                @endif
            @else
            <div class="alert alert-info alert-persistent alert-dismissible fade show">
                <a href="{{ route('login') }}">Đăng nhập</a> để đánh giá sản phẩm
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endauth
            
            <!-- Danh sách đánh giá - CÓ PHÂN TRANG -->
            @if($danhGias->count() > 0)
                @foreach($danhGias as $dg)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong><i class="bi bi-person-circle"></i> {{ $dg->nguoiDung->HoTen }}</strong>
                                <span class="badge bg-success ms-2">Đã mua hàng</span>
                                <div class="text-warning mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $dg->SoSao)
                                            <i class="bi bi-star-fill"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <small class="text-muted">{{ $dg->NgayDanhGia->diffForHumans() }}</small>
                            </div>
                            @auth
                                @if($dg->MaNguoiDung == auth()->id())
                                <form action="{{ route('ratings.destroy', $dg->MaDanhGia) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Xóa đánh giá?')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                                @endif
                            @endauth
                        </div>
                        
                        @if($dg->NoiDung)
                        <p class="mt-2 mb-2">{{ $dg->NoiDung }}</p>
                        @endif
                        
                        <!-- Hiển thị ảnh đánh giá -->
                        @if($dg->HinhAnh && count($dg->HinhAnh) > 0)
                        <div class="row g-2 mb-2">
                            @foreach($dg->HinhAnh as $img)
                            <div class="col-3 col-md-2">
                                <img src="{{ asset($img) }}" class="img-fluid rounded cursor-pointer" 
                                    onclick="viewImage('{{ asset($img) }}')" 
                                    style="height: 80px; object-fit: cover; width: 100%;">
                            </div>
                            @endforeach
                        </div>
                        @endif
                        
                        <!-- Vote hữu ích -->
                        @auth
                            @if(auth()->user()->VaiTro == 0)
                            <div class="mt-2">
                                @php
                                    $userVote = $dg->daVoteBoi(auth()->id());
                                    $tongHuuIch = $dg->tongHuuIch();
                                    $tongKhongHuuIch = $dg->tongKhongHuuIch();
                                @endphp
                                <span class="text-muted me-2">Đánh giá này có hữu ích không?</span>
                                <form action="{{ route('ratings.vote', $dg->MaDanhGia) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="huu_ich" value="1">
                                    <button type="submit" class="btn btn-sm {{ $userVote && $userVote->HuuIch ? 'btn-success' : 'btn-outline-success' }}">
                                        <i class="bi bi-hand-thumbs-up"></i> Có ({{ $tongHuuIch }})
                                    </button>
                                </form>
                                <form action="{{ route('ratings.vote', $dg->MaDanhGia) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="huu_ich" value="0">
                                    <button type="submit" class="btn btn-sm {{ $userVote && !$userVote->HuuIch ? 'btn-danger' : 'btn-outline-danger' }}">
                                        <i class="bi bi-hand-thumbs-down"></i> Không ({{ $tongKhongHuuIch }})
                                    </button>
                                </form>
                            </div>
                            @endif
                        @endauth
                        
                        <!-- Trả lời từ Shop -->
                        @if($dg->PhanHoiShop)
                        <div class="mt-3 mb-0 p-3 border rounded bg-white shadow-sm">
                            <strong class="text-primary"><i class="bi bi-chat-right-quote"></i> Trả lời từ Shop:</strong>
                            <p class="mb-1 mt-2">{{ $dg->PhanHoiShop }}</p>
                            <small class="text-muted d-block">
                                {{ $dg->nguoiPhanHoi->HoTen }} • {{ $dg->NgayPhanHoi->diffForHumans() }}
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
                
                <!-- Phân trang đánh giá -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $danhGias->fragment('ratings-section')->links() }}
                </div>
            @else
                <div class="alert alert-secondary">
                    <i class="bi bi-inbox"></i> Chưa có đánh giá nào cho sản phẩm này
                </div>
            @endif
        </div>
    </div>
    
    <!-- Comments Section -->
    <div class="row mt-5" id="comments-section">
        <div class="col-12">
            <h4 class="mb-4">
                <i class="bi bi-chat-dots-fill"></i> Bình luận 
                ({{ $sanPham->binhLuan()->goc()->daDuyet()->count() }})
            </h4>
            
            @auth
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('comments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="MaSanPham" value="{{ $sanPham->MaSanPham }}">
                        <div class="mb-3">
                            <textarea name="NoiDung" class="form-control @error('NoiDung') is-invalid @enderror" rows="3" 
                                    placeholder="Viết bình luận của bạn..." required></textarea>
                            @error('NoiDung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send-fill"></i> Gửi bình luận
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="alert alert-info alert-persistent alert-dismissible fade show">
                <a href="{{ route('login') }}">Đăng nhập</a> để bình luận
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endauth
            
            <!-- Comments List - CÓ PHÂN TRANG -->
            @if($binhLuans->count() > 0)
                @foreach($binhLuans as $bl)
                <div class="card mb-3 {{ !$bl->DaXem && $bl->MaNguoiDung == auth()->id() && $bl->replies->count() > 0 ? 'border-warning' : '' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>
                                    <i class="bi bi-person-circle"></i> {{ $bl->nguoiDung->HoTen }}
                                    @if($bl->daMuaHang())
                                        <span class="badge bg-success">Đã mua hàng</span>
                                    @endif
                                    @auth
                                        @if(!$bl->DaXem && $bl->MaNguoiDung == auth()->id() && $bl->replies->count() > 0)
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-bell-fill"></i> Có trả lời mới
                                            </span>
                                        @endif
                                    @endauth
                                </strong>
                                <small class="text-muted ms-2">{{ $bl->NgayBinhLuan->diffForHumans() }}</small>
                            </div>
                            @auth
                                @if($bl->MaNguoiDung == auth()->id())
                                <form action="{{ route('comments.destroy', $bl->MaBinhLuan) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Xóa bình luận?')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                                @endif
                            @endauth
                        </div>
                        <p class="mt-2 mb-2">{{ $bl->NoiDung }}</p>
                        
                        @auth
                        <!-- Nút Trả lời -->
                        <button class="btn btn-sm btn-outline-primary" onclick="toggleReplyForm({{ $bl->MaBinhLuan }})">
                            <i class="bi bi-reply-fill"></i> Trả lời
                        </button>
                        
                        <!-- Form trả lời (ẩn mặc định) -->
                        <div id="replyForm{{ $bl->MaBinhLuan }}" style="display: none;" class="mt-3">
                            <form action="{{ route('comments.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="MaSanPham" value="{{ $sanPham->MaSanPham }}">
                                <input type="hidden" name="MaBinhLuanCha" value="{{ $bl->MaBinhLuan }}">
                                <div class="input-group">
                                    <textarea name="NoiDung" class="form-control" rows="2" 
                                            placeholder="Viết trả lời..." required></textarea>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send-fill"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endauth
                        
                        <!-- Hiển thị các trả lời -->
                        @if($bl->replies->count() > 0)
                        <div class="ms-4 mt-3">
                            @foreach($bl->replies as $reply)
                            <div class="card mb-2">
                                <div class="card-body py-2">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>
                                                <i class="bi bi-arrow-return-right"></i> {{ $reply->nguoiDung->HoTen }}
                                                @if($reply->daMuaHang())
                                                    <span class="badge bg-success">Đã mua hàng</span>
                                                @endif
                                            </strong>
                                            <small class="text-muted ms-2">{{ $reply->NgayBinhLuan->diffForHumans() }}</small>
                                        </div>
                                        @auth
                                            @if($reply->MaNguoiDung == auth()->id())
                                            <form action="{{ route('comments.destroy', $reply->MaBinhLuan) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Xóa trả lời?')">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                            @endif
                                        @endauth
                                    </div>
                                    <p class="mt-1 mb-0">{{ $reply->NoiDung }}</p>
                                </div>
                            </div>
                            @endforeach
                            
                            @auth
                                @if($bl->MaNguoiDung == auth()->id() && !$bl->DaXem)
                                <!-- Nút đánh dấu đã đọc -->
                                <form action="{{ route('comments.markAsRead', $bl->MaBinhLuan) }}" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-check-circle"></i> Đánh dấu đã đọc
                                    </button>
                                </form>
                                @endif
                            @endauth
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
                
                <!-- Phân trang bình luận -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $binhLuans->fragment('comments-section')->links() }}
                </div>
            @else
                <div class="alert alert-secondary">
                    <i class="bi bi-inbox"></i> Chưa có bình luận nào
                </div>
            @endif
        </div>
    </div>
    
    <!-- Related Products -->
    @if($sanPhamLienQuan->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="mb-4">
                <i class="bi bi-grid-fill"></i> Sản phẩm liên quan
            </h4>
            <div class="row g-4">
                @foreach($sanPhamLienQuan as $sp)
                <div class="col-md-3">
                    <div class="card product-card h-100">
                        <a href="{{ route('products.show', $sp->MaSanPham) }}">
                            <img src="{{ $sp->AnhChinh }}" class="card-img-top" alt="{{ $sp->TenSanPham }}">
                        </a>
                        <div class="card-body">
                            <h6 class="card-title">{{ $sp->TenSanPham }}</h6>
                            <p class="price">{{ number_format($sp->giaThapNhat(), 0, ',', '.') }}₫</p>
                            <a href="{{ route('products.show', $sp->MaSanPham) }}" class="btn btn-sm btn-primary w-100">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal xem ảnh -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <img id="modalImage" src="" class="img-fluid w-100">
            </div>
        </div>
    </div>
</div>

<script>
// ============================================
// SCRIPT CHO TRANG CHI TIẾT SẢN PHẨM - MAX 20
// ============================================

const variants = @json($sanPham->bienThe);
const MAX_QUANTITY = 20; // Giới hạn tối đa

function changeImage(src) {
    document.getElementById('mainImage').src = src;
}

function updateVariants(color) {
    const colorVariants = variants.filter(v => v.MauSac === color);
    const sizeSection = document.getElementById('sizeSection');
    const sizeOptions = document.getElementById('sizeOptions');
    
    if (colorVariants.length > 0) {
        document.getElementById('mainImage').src = colorVariants[0].AnhMinhHoa;
    }
    
    if (colorVariants.length === 1) {
        document.getElementById('selectedVariant').value = colorVariants[0].MaCTSanPham;
        updatePrice(colorVariants[0].DonGia);
        updateStock(colorVariants[0].SoLuongTon);
        if(sizeSection) sizeSection.style.display = 'none';
    } else {
        if(sizeSection) {
            sizeSection.style.display = 'block';
            let html = '<div class="btn-group" role="group">';
            colorVariants.forEach((v, i) => {
                const label = v.KichThuoc || v.DungLuong || 'Mặc định';
                html += `
                    <input type="radio" class="btn-check" name="size" id="size_${i}" 
                           value="${v.MaCTSanPham}" ${i === 0 ? 'checked' : ''} 
                           onchange="selectVariant(${v.MaCTSanPham}, ${v.DonGia}, ${v.SoLuongTon})">
                    <label class="btn btn-outline-primary" for="size_${i}">${label}</label>
                `;
            });
            html += '</div>';
            sizeOptions.innerHTML = html;
            selectVariant(colorVariants[0].MaCTSanPham, colorVariants[0].DonGia, colorVariants[0].SoLuongTon);
        }
    }
}

function selectVariant(id, price, stock) {
    document.getElementById('selectedVariant').value = id;
    updatePrice(price);
    updateStock(stock);
}

function updatePrice(price) {
    document.getElementById('selectedPrice').textContent = new Intl.NumberFormat('vi-VN').format(price) + '₫';
}

// ============================================
// SỬA: Cập nhật max = min(20, tồn kho)
// ============================================
function updateStock(stock) {
    const stockInfo = document.getElementById('stockInfo');
    const quantityInput = document.getElementById('quantity');
    
    // Tính giới hạn: min(20, tồn kho)
    const maxAllowed = Math.min(MAX_QUANTITY, stock);
    
    // Cập nhật hiển thị
    if (stock > 0) {
        if (stock >= MAX_QUANTITY) {
            stockInfo.textContent = `Còn ${stock} sản phẩm (tối đa ${MAX_QUANTITY}/đơn)`;
        } else {
            stockInfo.textContent = `Còn ${stock} sản phẩm`;
        }
    } else {
        stockInfo.textContent = 'Hết hàng';
    }
    
    // Cập nhật max cho input
    quantityInput.max = maxAllowed;
    
    // Nếu số lượng hiện tại > max mới, giảm xuống
    if (parseInt(quantityInput.value) > maxAllowed) {
        quantityInput.value = maxAllowed;
    }
}

// ============================================
// SỬA: Tăng số lượng với giới hạn
// ============================================
function increaseQty() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.max); // max = min(20, tồn kho)
    let current = parseInt(input.value);
    
    if (current < max) {
        input.value = current + 1;
    } else {
        // Hiển thị thông báo khi đạt giới hạn
        if (max === MAX_QUANTITY) {
            alert(`Số lượng tối đa là ${MAX_QUANTITY} sản phẩm/đơn hàng`);
        } else {
            alert(`Chỉ còn ${max} sản phẩm trong kho`);
        }
    }
}

function decreaseQty() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

function buyNow() {
    const selectedVariant = document.getElementById('selectedVariant').value;
    const quantity = document.getElementById('quantity').value;
    
    if (!selectedVariant) {
        alert('Vui lòng chọn biến thể sản phẩm!');
        return;
    }
    
    let form = document.getElementById('buyNowForm');
    form.querySelector('#buyNowVariant').value = selectedVariant;
    form.querySelector('#buyNowQuantity').value = quantity;
    form.submit();
}

// Toggle reply form
function toggleReplyForm(commentId) {
    const form = document.getElementById('replyForm' + commentId);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

// View image in modal
function viewImage(src) {
    document.getElementById('modalImage').src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    const firstColor = document.querySelector('input[name="mau_sac"]:checked');
    if(firstColor) {
        updateVariants(firstColor.value);
    }
});
</script>

<style>
.cursor-pointer { cursor: pointer; }
.img-thumbnail:hover { opacity: 0.8; }
</style>
@endsection