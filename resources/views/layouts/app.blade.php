<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>ShopGrids - Bootstrap 5 eCommerce HTML Template.</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/favicon.svg" />

    <!-- ========================= CSS here ========================= -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/assets/css/LineIcons.3.0.css" />
    <link rel="stylesheet" href="/assets/css/tiny-slider.css" />
    <link rel="stylesheet" href="/assets/css/glightbox.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css" />

</head>
<body>

    <!-- Start Header Area -->
    <header class="header navbar-area">
        <!-- Start Header Middle -->
        <div class="header-middle">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-3 col-7">
                        <!-- Start Header Logo -->
                        <a class="navbar-brand" href="/">
                            <img src="/assets/images/logo/logo.png" alt="Logo">
                        </a>
                        <!-- End Header Logo -->
                    </div>
                    <div class="col-lg-5 col-md-7 d-xs-none">
                        <!-- Start Main Menu Search -->
                        <div class="main-menu-search position-relative">
                            <!-- navbar search start -->
                            <div class="navbar-search search-style-5">
                                <div class="search-input">
                                    <input type="text" id="live-search" placeholder="Tìm kiếm">
                                </div>
                                <div class="search-btn">
                                    <button><i class="lni lni-search-alt"></i></button>
                                </div>
                            </div>
                             <div id="search-result" class="search-dropdown shadow"></div>
                            <!-- navbar search Ends -->
                        </div>
                        <!-- End Main Menu Search -->
                    </div>
                    <div class="col-lg-4 col-md-2 col-5">
                        <div class="middle-right-area">
                            <div class="nav-hotline">
                            </div>
                            <div class="navbar-cart">
                                @auth
                                    @if(auth()->user()->VaiTro == 0)
                                    <div class="cart-items">
                                        <a href="{{ route('cart.index') }}" class="main-btn">
                                            Giỏ hàng &nbsp;
                                            <i class="lni lni-cart"></i>
                                            @php
                                                $cartCount = \App\Models\CTGioHang::where('MaNguoiDung', auth()->id())->count();
                                            @endphp
                                            @if($cartCount > 0)
                                            <span class="total-items">{{ $cartCount }}</span>
                                            @endif
                                        </a>
                                    </div>
                                    @endif
                                    <div class="cart-items">
                                        <a href="javascript:void(0)" class="main-btn">
                                            {{ auth()->user()->HoTen }} &nbsp;
                                            <i class="lni lni-user"></i>
                                        </a>
                                        <div class="shopping-item">
                                            @if(auth()->user()->VaiTro == 1)
                                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary w-100">Trang quản trị</a>
                                            @endif
                                            <a href="{{ route('profile.edit') }}" class="btn btn-primary w-100 mt-1">Thông tin cá nhân</a>
                                            <a href="{{ route('orders.history') }}" class="btn btn-primary w-100 mt-1">Đơn hàng của tôi</a>
                                            <a href="{{ route('feedback.create') }}" class="btn btn-primary w-100 mt-1">Gửi phản hồi</a>
                                            <a href="{{ route('logout') }}" class="btn btn-primary w-100 mt-1">Đăng xuất</a>
                                        </div>
                                    </div>
                                @else
                                    <div class="cart-items">
                                        <a href="{{ route('login') }}" class="main-btn">
                                            Đăng nhập &nbsp;
                                            <i class="lni lni-user"></i>
                                        </a>
                                    </div>
                                    <div class="cart-items">
                                        <a href="{{ route('register') }}" class="main-btn">
                                            Đăng ký &nbsp;
                                            <i class="lni lni-user"></i>
                                        </a>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Header Middle -->

    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Start Footer Area -->
    <footer class="footer">
        <!-- Start Footer Middle -->
        <div class="footer-middle">
            <div class="container">
                <div class="bottom-inner">
                    <div class="row">

                        <div class="col-lg-2 col-md-6 col-12">
                            <div class="single-footer f-contact">
                                <a href="index.html">
                                    <img src="/assets/images/logo/logo.png" alt="#">
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-4  offset-lg-1 col-md-6 col-12">
                            <!-- Single Widget -->
                            <div class="single-footer f-contact">
                                <h3>Liên hệ với chúng tôi</h3>
                                <p class="phone">SĐT: 0943 990 753</p>
                                <p class="mail">
                                    <a href="mailto:buithanhtung265@gmail.com">Email:
                                         buithanhtung265@gmail.com</a>
                                </p>
                            </div>
                            <!-- End Single Widget -->
                        </div>
                        
                        <div class="col-lg-4 col-md-6 col-12">
                            <!-- Single Widget -->
                            <div class="single-footer f-link">
                                <h3>Danh mục</h3>
                                <ul>
                                    <li><a href="products?danh_muc=4">Điện thoại</a></li>
                                    <li><a href="products?danh_muc=5">Máy tính bảng</a></li>
                                    <li><a href="products?danh_muc=6">Laptop</a></li>
                                    <li><a href="products?danh_muc=4">Màn hình</a></li>
                                    <li><a href="products?danh_muc=8">Máy tính để bàn</a></li>
                                </ul>
                            </div>
                            <!-- End Single Widget -->
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- End Footer Middle -->
        <!-- Start Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="inner-content">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <div class="copyright">
                                <p>@Copyright Thanh Tung Mobile</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Footer Bottom -->
    </footer>
    <!--/ End Footer Area -->


    <!-- ========================= scroll-top ========================= -->
    <a href="index.html#" class="scroll-top">
        <i class="lni lni-chevron-up"></i>
    </a>

    <!-- ========================= JS here ========================= -->
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/tiny-slider.js"></script>
    <script src="/assets/js/glightbox.min.js"></script>
    <script src="/assets/js/main.js"></script>

    @yield('scripts')
    <script>
        let typingTimer;
        let doneTypingInterval = 300;

        const input = document.getElementById('live-search');
        const resultBox = document.getElementById('search-result');

        input.addEventListener('keyup', function () {
            clearTimeout(typingTimer);

            let keyword = this.value.trim();

            if (keyword.length < 2) {
                resultBox.style.display = "none";
                return;
            }

            typingTimer = setTimeout(() => {
                fetch(`/search?q=${keyword}`)
                    .then(res => res.json())
                    .then(data => {
                        let html = '';

                        if (data.length === 0) {
                            html = `<div class="p-2">Không tìm thấy sản phẩm</div>`;
                        } else {
                            data.forEach(p => {
                                html += `
                                <a href="/products/${p.MaSanPham}" class="search-item text-decoration-none text-dark">
                                    <img src="${p.AnhChinh}">
                                    <div>
                                        <div>${p.TenSanPham}</div>
                                    </div>
                                </a>`;
                            });
                        }

                        resultBox.innerHTML = html;
                        resultBox.style.display = "block";
                    });
            }, doneTypingInterval);
        });

        // Ẩn khi click ra ngoài
        document.addEventListener('click', function(e){
            if(!document.querySelector('.main-menu-search').contains(e.target)){
                resultBox.style.display = "none";
            }
        });
        </script>
    
</body>

</html>