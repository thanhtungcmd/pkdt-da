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
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }
        
        .card {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
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
        
        /* Footer đơn giản */
        .auth-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            padding: 15px 0;
            text-align: center;
            font-size: 14px;
        }
        
        .auth-footer a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            margin: 0 10px;
        }
        
        .auth-footer a:hover {
            color: white;
            text-decoration: underline;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" 
             style="z-index: 9999; min-width: 300px;" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" 
             style="z-index: 9999; min-width: 300px;" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <!-- Main Content -->
    <main class="container">
        @yield('content')
    </main>
    
    <!-- Simple Footer -->
    <footer class="auth-footer">
        <div class="container">
            <span>&copy; 2024 TechBox Shop</span>
            <span class="mx-2">|</span>
            <a href="{{ route('home') }}">Trang chủ</a>
            <a href="{{ route('products.index') }}">Sản phẩm</a>
        </div>
    </footer>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Tự động ẩn thông báo --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
    </script>
    
    @yield('scripts')
</body>
</html>