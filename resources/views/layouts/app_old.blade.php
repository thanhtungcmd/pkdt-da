<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Phụ Kiện Điện Tử')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS File -->
    <!-- <link rel="stylesheet" href="{{ asset('css/custom.css') }}?v={{ time() }}"> -->
    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #003A78;      
            --secondary: #0059B3;   
            --dark: #1a1a2e;
            --light: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            box-shadow: 0 2px 10px rgba(0, 102, 255, 0.2);
            height: 60px; /* chỉnh tùy ý: 65–75 */
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: white !important;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background: var(--primary);
            border: none;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 102, 255, 0.3);
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 102, 255, 0.2);
        }
        
        .product-card img {
            height: 250px;
            object-fit: cover;
        }
        
        .footer {
            background: var(--dark);
            color: white;
            padding: 40px 0 20px;
            margin-top: 50px;
        }
        
        .price {
            color: var(--primary);
            font-size: 1.3rem;
            font-weight: bold;
        }
        
        .badge-custom {
            background: var(--primary);
        }
        
        .logo {
            max-height: 50px;
            width: auto;
            object-fit: contain;
        }
        
        /* ============================================ */
        /* CSS THÔNG BÁO MỚI */
        /* ============================================ */
        .notification-icon {
            position: relative;
            cursor: pointer;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 10px;
            padding: 2px 6px;
            font-size: 11px;
            font-weight: bold;
            min-width: 18px;
            text-align: center;
        }
        
        .notification-dropdown {
            min-width: 350px;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .notification-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .notification-item:hover {
            background: #f8f9fa;
        }
        
        .notification-item.unread {
            background: #e7f3ff;
        }
        
        .notification-item .icon {
            font-size: 24px;
            margin-right: 12px;
        }
        
        .notification-item .content {
            flex: 1;
        }
        
        .notification-item .title {
            font-weight: 600;
            margin-bottom: 4px;
            font-size: 14px;
        }
        
        .notification-item .text {
            font-size: 13px;
            color: #666;
            margin-bottom: 4px;
        }
        
        .notification-item .time {
            font-size: 12px;
            color: #999;
        }
        
        .notification-empty {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="/images/logo.png" alt="TechBox" class="logo me-2">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="bi bi-house-fill"></i> Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">
                            <i class="bi bi-grid-fill"></i> Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('news.index') }}">
                            <i class="bi bi-newspaper"></i> Tin tức
                        </a>
                    </li>
                </ul>
                
                <!-- Search Form -->
                <form class="d-flex me-3" action="{{ route('products.search') }}" method="GET">
                    <input class="form-control me-2" type="search" name="q" placeholder="Tìm kiếm..." required>
                    <button class="btn btn-outline-light" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
                
                <ul class="navbar-nav">
                    @auth
                        {{-- ============================================ --}}
                        {{-- ICON THÔNG BÁO MỚI - CHỈ CHO KHÁCH HÀNG --}}
                        {{-- ============================================ --}}
                        @if(auth()->user()->VaiTro == 0)
                            <li class="nav-item dropdown me-3">
                                <a class="nav-link notification-icon" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-bell-fill" style="font-size: 20px;"></i>
                                    <span class="notification-badge" id="notif-badge" style="display: none;">0</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end notification-dropdown" id="notif-dropdown">
                                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                        <h6 class="mb-0">Thông báo</h6>
                                        <a href="#" class="text-primary small" id="mark-all-read">Đánh dấu tất cả đã đọc</a>
                                    </div>
                                    <div id="notif-list">
                                        <div class="notification-empty">
                                            <i class="bi bi-bell-slash" style="font-size: 48px; color: #ccc;"></i>
                                            <p class="mt-2">Chưa có thông báo</p>
                                        </div>
                                    </div>
                                    <div class="text-center py-2 border-top">
                                        <a href="{{ route('notifications.index') }}" class="text-primary small">Xem tất cả</a>
                                    </div>
                                </div>
                            </li>
                        @endif
                        
                        {{-- Chỉ hiển thị giỏ hàng cho USER THƯỜNG (VaiTro = 0) --}}
                        @if(auth()->user()->VaiTro == 0)
                            <!-- Giỏ hàng -->
                            <li class="nav-item">
                                <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                                    <i class="bi bi-cart-fill"></i> Giỏ hàng
                                    @php
                                        $cartCount = \App\Models\CTGioHang::where('MaNguoiDung', auth()->id())->count();
                                    @endphp
                                    @if($cartCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $cartCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endif
                        
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->HoTen }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(auth()->user()->VaiTro == 1)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2"></i> Trang quản trị
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person-fill"></i> Thông tin cá nhân
                                    </a>
                                </li>
                                
                                {{-- Chỉ hiển thị "Đơn hàng của tôi" cho USER THƯỜNG --}}
                                @if(auth()->user()->VaiTro == 0)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('orders.history') }}">
                                            <i class="bi bi-bag-fill"></i> Đơn hàng của tôi
                                        </a>
                                    </li>
                                @endif
                                
                                {{-- CHỈ HIỂN THỊ "Gửi phản hồi" cho USER THƯỜNG (VaiTro = 0) --}}
                                @if(auth()->user()->VaiTro == 0)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('feedback.create') }}">
                                            <i class="bi bi-chat-dots-fill"></i> Gửi phản hồi
                                        </a>
                                    </li>
                                @endif
                                
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus-fill"></i> Đăng ký
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <img src="/images/logo.png" alt="TechBox" style="height: 50px;" class="me-2">
                    <p>Cửa hàng phụ kiện điện tử uy tín, chất lượng cao với giá tốt nhất thị trường.</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Liên kết</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('products.index') }}" class="text-white-50">Sản phẩm</a></li>
                        <li><a href="{{ route('news.index') }}" class="text-white-50">Tin tức</a></li>
                        {{-- Ẩn link "Liên hệ" trong footer với admin --}}
                        @if(!Auth::check() || Auth::user()->VaiTro != 1)
                            <li><a href="{{ route('feedback.create') }}" class="text-white-50">Liên hệ</a></li>
                        @endif
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Liên hệ</h5>
                    <p class="text-white-50">
                        <i class="bi bi-geo-alt-fill"></i> Hà Nội, Việt Nam<br>
                        <i class="bi bi-telephone-fill"></i> 0901234567<br>
                        <i class="bi bi-envelope-fill"></i> contact@phukien.com
                    </p>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="text-center text-white-50">
                <p>&copy; 2024 Phụ Kiện Điện Tử. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Tự động ẩn thông báo sau 5 giây --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-persistent)');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000); // 5 giây
        });
    });
    </script>
    
    {{-- ============================================ --}}
    {{-- JAVASCRIPT POLLING THÔNG BÁO - MỚI --}}
    {{-- ============================================ --}}
    @auth
        @if(auth()->user()->VaiTro == 0)
        <script>
        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Hàm lấy số lượng thông báo chưa đọc
        function fetchUnreadCount() {
            fetch('{{ route('notifications.unread.count') }}')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('notif-badge');
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.style.display = 'block';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(err => console.error('Error fetching notifications:', err));
        }
        
        // Hàm lấy danh sách thông báo
        function fetchNotifications() {
            fetch('{{ route('notifications.list') }}')
                .then(res => res.json())
                .then(data => {
                    const listEl = document.getElementById('notif-list');
                    
                    if (data.notifications.length === 0) {
                        listEl.innerHTML = `
                            <div class="notification-empty">
                                <i class="bi bi-bell-slash" style="font-size: 48px; color: #ccc;"></i>
                                <p class="mt-2">Chưa có thông báo</p>
                            </div>
                        `;
                        return;
                    }
                    
                    let html = '';
                    data.notifications.forEach(notif => {
                        html += `
                            <div class="notification-item ${notif.read ? '' : 'unread'}" 
                                 onclick="markAsReadAndRedirect(${notif.id}, '${notif.link}')">
                                <div class="d-flex align-items-start">
                                    <i class="bi ${notif.icon.split(' ')[0]} icon ${notif.icon.split(' ')[1]}"></i>
                                    <div class="content">
                                        <div class="title">${notif.title}</div>
                                        <div class="text">${notif.content}</div>
                                        <div class="time">${notif.time}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    listEl.innerHTML = html;
                })
                .catch(err => console.error('Error fetching notification list:', err));
        }
        
        // Đánh dấu đã đọc và chuyển hướng
        function markAsReadAndRedirect(id, link) {
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            }).then(() => {
                if (link) {
                    window.location.href = link;
                }
            });
        }
        
        // Đánh dấu tất cả đã đọc
        document.getElementById('mark-all-read').addEventListener('click', function(e) {
            e.preventDefault();
            fetch('{{ route('notifications.read.all') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            }).then(() => {
                fetchUnreadCount();
                fetchNotifications();
            });
        });
        
        // Load thông báo khi click vào icon
        document.querySelector('.notification-icon').addEventListener('click', function() {
            fetchNotifications();
        });
        
        // POLLING: Cập nhật mỗi 30 giây
        fetchUnreadCount(); // Gọi ngay khi load trang
        setInterval(fetchUnreadCount, 30000); // 30 giây
        </script>
        @endif
    @endauth
    
    @yield('scripts')
</body>
</html>