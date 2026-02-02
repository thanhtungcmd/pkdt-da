<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Phụ Kiện Điện Tử')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary: #0066FF;
            --sidebar-width: 250px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(0, 102, 255, 0.2);
            color: white;
            border-left: 3px solid var(--primary);
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        .stat-card {
            border-left: 4px solid var(--primary);
        }
        
        .navbar-admin {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="p-3 text-center border-bottom border-secondary">
            <h4><i class="bi bi-lightning-charge-fill"></i> Admin Panel</h4>
        </div>
        
        <nav class="nav flex-column p-2">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            
            <div class="nav-link text-muted small mt-2">QUẢN LÝ</div>
            
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i> Danh mục
            </a>
            
            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Sản phẩm
            </a>
            
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-cart-fill"></i> Đơn hàng
            </a>
            
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Người dùng
            </a>
            
            <a href="{{ route('admin.comments.index') }}" class="nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                <i class="bi bi-chat-dots-fill"></i> Bình luận
            </a>
            
            <a href="{{ route('admin.news.index') }}" class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <i class="bi bi-newspaper"></i> Tin tức
            </a>
            
            <a href="{{ route('admin.feedback.index') }}" class="nav-link {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}">
                <i class="bi bi-envelope-fill"></i> Phản hồi
            </a>
            
            <a href="{{ route('admin.ratings.index') }}" class="nav-link {{ request()->routeIs('admin.ratings.*') ? 'active' : '' }}">
                <i class="bi bi-star"></i> Đánh giá
            </a>

            <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <i class="bi bi-tags-fill"></i> Mã giảm giá
            </a>

            <div class="nav-link text-muted small mt-3">KHÁC</div>
            
            <a href="{{ route('home') }}" class="nav-link" target="_blank">
                <i class="bi bi-house-fill"></i> Xem website
            </a>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link btn btn-link text-start w-100">
                    <i class="bi bi-box-arrow-right"></i> Đăng xuất
                </button>
            </form>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-admin rounded">
            <div class="container-fluid">
                <span class="navbar-brand">
                    @yield('page-title', 'Dashboard')
                </span>
                <div class="d-flex align-items-center">
                    <span class="me-3">
                        <i class="bi bi-person-circle"></i> {{ auth()->user()->HoTen }}
                    </span>
                </div>
            </div>
        </nav>
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <!-- Content -->
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Tự động ẩn thông báo sau 5 giây --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000); // 5 giây
        });
    });
    </script>
    
    @yield('scripts')
</body>
</html>