@extends('layouts.app')

@section('title', 'Trang chủ - Phụ Kiện Điện Tử')

@section('content')

    <!-- Start Hero Area -->
    <section class="hero-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12 custom-padding-right">
                    <div class="slider-head">
                        <!-- Start Hero Slider -->
                        <div class="hero-slider">
                            <!-- Start Single Slider -->
                            <div class="single-slider"
                                style="background-image: url(assets/images/hero/slider-bg1.jpg);">
                                <div class="content">
                                    <h2><span>No restocking fee ($35 savings)</span>
                                        M75 Sport Watch
                                    </h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur elit, sed do eiusmod tempor incididunt ut
                                        labore dolore magna aliqua.</p>
                                    <h3><span>Now Only</span> $320.99</h3>
                                    <div class="button">
                                        <a href="product-grids.html" class="btn">Shop Now</a>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Slider -->
                            <!-- Start Single Slider -->
                            <div class="single-slider"
                                style="background-image: url(assets/images/hero/slider-bg2.jpg);">
                                <div class="content">
                                    <h2><span>Big Sale Offer</span>
                                        Get the Best Deal on CCTV Camera
                                    </h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur elit, sed do eiusmod tempor incididunt ut
                                        labore dolore magna aliqua.</p>
                                    <h3><span>Combo Only:</span> $590.00</h3>
                                    <div class="button">
                                        <a href="product-grids.html" class="btn">Shop Now</a>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Slider -->
                        </div>
                        <!-- End Hero Slider -->
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-12 md-custom-padding">
                            <!-- Start Small Banner -->
                            <div class="hero-small-banner"
                                style="background-image: url('assets/images/hero/slider-bnr.jpg');">
                                <div class="content">
                                    <h2>
                                        <span>New line required</span>
                                        iPhone 12 Pro Max
                                    </h2>
                                    <h3>$259.99</h3>
                                </div>
                            </div>
                            <!-- End Small Banner -->
                        </div>
                        <div class="col-lg-12 col-md-6 col-12">
                            <!-- Start Small Banner -->
                            <div class="hero-small-banner style2">
                                <div class="content">
                                    <h2>Weekly Sale!</h2>
                                    <p>Saving up to 50% off all online store items this week.</p>
                                    <div class="button">
                                        <a class="btn" href="product-grids.html">Shop Now</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Start Small Banner -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
                    <a href="#">
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
    <section class="product-grids">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="title">Sản phẩm bán chạy</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row">

                        <div class="col-3">
                            <!-- Start Single Product -->
                            <div class="single-product">
                                <div class="product-image">
                                    <img src="assets/images/products/product-1.jpg" alt="#">
                                    <div class="button">
                                        <a href="product-details.html" class="btn"><i
                                                class="lni lni-cart"></i> Add to Cart</a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <span class="category">Watches</span>
                                    <h4 class="title">
                                        <a href="product-grids.html">Xiaomi Mi Band 5</a>
                                    </h4>
                                    <ul class="review">
                                        <li><i class="lni lni-star-filled"></i></li>
                                        <li><i class="lni lni-star-filled"></i></li>
                                        <li><i class="lni lni-star-filled"></i></li>
                                        <li><i class="lni lni-star-filled"></i></li>
                                        <li><i class="lni lni-star"></i></li>
                                        <li><span>4.0 Review(s)</span></li>
                                    </ul>
                                    <div class="price">
                                        <span>32.990.000đ</span>
                                        <span style="text-decoration-line: line-through; color: oklch(70.7% .022 261.325); font-size: 15px;">32.990.000đ</span>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Product -->
                        </div>

                        <div class="col-3">
                            <!-- Start Single Product -->
                            <div class="single-product">
                                <div class="product-image">
                                    <img src="assets/images/products/product-1.jpg" alt="#">
                                    <div class="button">
                                        <a href="product-details.html" class="btn"><i
                                                class="lni lni-cart"></i> Add to Cart</a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <span class="category">Watches</span>
                                    <h4 class="title">
                                        <a href="product-grids.html">Xiaomi Mi Band 5</a>
                                    </h4>
                                    <ul class="review">
                                        <li><i class="lni lni-star-filled"></i></li>
                                        <li><i class="lni lni-star-filled"></i></li>
                                        <li><i class="lni lni-star-filled"></i></li>
                                        <li><i class="lni lni-star-filled"></i></li>
                                        <li><i class="lni lni-star"></i></li>
                                        <li><span>4.0 Review(s)</span></li>
                                    </ul>
                                    <div class="price">
                                        <span>$199.00</span>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Product -->
                        </div>

                        <div class="col-3">
                            <!-- Start Single Product -->
                            <div class="single-product">
                                <div class="product-image">
                                    <img src="assets/images/products/product-1.jpg" alt="#">
                                    <div class="button">
                                        <a href="product-details.html" class="btn"><i
                                                class="lni lni-cart"></i> Add to Cart</a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <span class="category">Watches</span>
                                    <h4 class="title">
                                        <a href="product-grids.html">Xiaomi Mi Band 5</a>
                                    </h4>
                                    <ul class="review">
                                        <li><i class="lni lni-star-filled"></i></li>
                                        <li><i class="lni lni-star-filled"></i></li>
                                        <li><i class="lni lni-star-filled"></i></li>
                                        <li><i class="lni lni-star-filled"></i></li>
                                        <li><i class="lni lni-star"></i></li>
                                        <li><span>4.0 Review(s)</span></li>
                                    </ul>
                                    <div class="price">
                                        <span>$199.00</span>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Product -->
                        </div>

                        <div class="col-3">
                            <!-- Start Single Product -->
                            <div class="single-product">
                                <div class="product-image">
                                    <img src="assets/images/products/product-1.jpg" alt="#">
                                    <div class="button">
                                        <a href="product-details.html" class="btn"><i
                                                class="lni lni-cart"></i> Add to Cart</a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <span class="category">Watches</span>
                                    <h4 class="title">
                                        <a href="product-grids.html">Xiaomi Mi Band 5</a>
                                    </h4>
                                    <ul class="review">
                                        <li><i class="lni lni-star-filled"></i></li>
                                        <li><i class="lni lni-star-filled"></i></li>
                                        <li><i class="lni lni-star-filled"></i></li>
                                        <li><i class="lni lni-star-filled"></i></li>
                                        <li><i class="lni lni-star"></i></li>
                                        <li><span>4.0 Review(s)</span></li>
                                    </ul>
                                    <div class="price">
                                        <span>$199.00</span>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Product -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Product Grids -->

@endsection