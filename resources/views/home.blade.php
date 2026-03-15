@extends('layouts.app')

@section('title', 'Trang chủ - Phụ Kiện Điện Tử')

@section('content')

    <!-- Start Hero Area -->
    <!-- <section class="hero-area">
        <div class="container">
            <div class="row">
                <div class="col-12 custom-padding-right">
                    <div class="slider-head">
                        <div class="hero-slider">
                            <div class="single-slider"
                                style="background-image: url(https://cdn2.cellphones.com.vn/insecure/rs:fill:1036:450/q:100/plain/https://dashboard.cellphones.com.vn/storage/GALAXYMOI-D.png);">
                            </div>
                            <div class="single-slider"
                                style="background-image: url(https://cdn2.cellphones.com.vn/insecure/rs:fill:1036:450/q:100/plain/https://dashboard.cellphones.com.vn/storage/samsung-galaxy-a56-tet-homg-26.png);">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!-- End Hero Area -->

    <!-- Start Shipping Info -->
    <section class="shipping-info">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="title">Danh mục</h2>
                </div>
            </div>
            <ul>
                <!-- Free Shipping -->
                @foreach($danhMuc as $item)
                <li>
                    <a href="{{ route('products.index', ['danh_muc' => $item->MaDanhMuc]) }}">
                        <div class="media-icon">
                            <img src="{{ $item->AnhMinhHoa }}" alt="{{ $item->TenDanhMuc }}">
                        </div>
                        <div class="media-body">
                            <h5>{{ $item->TenDanhMuc }}</h5>
                        </div>
                    </a>
                </li>
                @endforeach 
            </ul>
        </div>
    </section>
    <!-- End Shipping Info -->

    <!-- Start Product Grids -->
    <section class="product-grids pb-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="title">Sản phẩm bán chạy</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row">

                        @foreach($sanPhamMoi as $item)
                        <a href="{{ route('products.show', $item->MaSanPham) }}" class="col-3">
                            <!-- Start Single Product -->
                            <div class="single-product">
                                <div class="product-image">
                                    <img src="{{ $item->AnhChinh }}" alt="#">
                                </div>
                                <div class="product-info">
                                    <span class="category">{{ $item->danhMuc->TenDanhMuc }}</span>
                                    <h4 class="title">
                                        <div>{{ $item->TenSanPham }}</div>
                                    </h4>
                                    @if( isset($item->bienThe[0]) )
                                    <div class="price">
                                        <span>{{ number_format($item->bienThe[0]->DonGia) }} VNĐ</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <!-- End Single Product -->
                        </a>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Product Grids -->

@endsection

@section('scripts')
    <script type="text/javascript">
        //========= Hero Slider 
        tns({
            container: '.hero-slider',
            slideBy: 'page',
            autoplay: true,
            autoplayButtonOutput: false,
            mouseDrag: true,
            gutter: 0,
            items: 1,
            nav: false,
            controls: true,
            controlsText: ['<i class="lni lni-chevron-left"></i>', '<i class="lni lni-chevron-right"></i>'],
        });

        //======== Brand Slider
        tns({
            container: '.brands-logo-carousel',
            slideBy: 'page',
            autoplay: true,
            autoplayButtonOutput: false,
            mouseDrag: true,
            gutter: 0,
            items: 6,
            nav: false,
            controls: true,
            controlsText: ['<i class="lni lni-chevron-left"></i>', '<i class="lni lni-chevron-right"></i>'],
        });

    </script>
@endsection