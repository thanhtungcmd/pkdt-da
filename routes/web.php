<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\MaGiamGiaController;
use App\Http\Controllers\NotificationController;
// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;

/*
|--------------------------------------------------------------------------
| ROUTES PHÍA KHÁCH HÀNG
|--------------------------------------------------------------------------
*/

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Xác thực (Đăng nhập, Đăng ký, Đăng xuất)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Sản phẩm
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

// Tin tức
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');

// Quên mật khẩu offline
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password', function() {return view('auth.reset-password');})->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// Routes cần đăng nhập
Route::middleware('auth')->group(function () {
    
    // =====================================================
    // Giỏ hàng Routes - CHỈ CHO KHÁCH HÀNG (VaiTro = 0)
    // =====================================================
    Route::middleware('customer')->group(function () {
        // Giỏ hàng
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

        Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::patch('/cart/{id}/variant', [CartController::class, 'updateVariant'])->name('cart.updateVariant');
        
        Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
        Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
        
        // Đặt hàng
        Route::post('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/success/{id}', [OrderController::class, 'success'])->name('orders.success');
        Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
        Route::get('/orders/{id}', [OrderController::class, 'detail'])->name('orders.detail');
        Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        
        // VNPay giả lập
        Route::get('/vnpay/simulate/{id}', [OrderController::class, 'vnpaySimulate'])->name('vnpay.simulate');
        Route::post('/vnpay/confirm/{id}', [OrderController::class, 'vnpayConfirm'])->name('vnpay.confirm');

        // =====================================================
        // MÃ GIẢM GIÁ - CHỈ CHO KHÁCH HÀNG (THÊM MỚI)
        // =====================================================
        Route::post('/coupon/ap-dung', [MaGiamGiaController::class, 'apDung'])->name('coupon.apply');
        Route::post('/coupon/huy', [MaGiamGiaController::class, 'huyMa'])->name('coupon.remove');
    });
    
    // =====================================================
    // Bình luận - CHO TẤT CẢ USER (cả admin và khách)
    // =====================================================
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // =====================================================
    // Thông báo bình luận
    // =====================================================
    Route::get('/notifications/comments', [CommentController::class, 'notifications'])->name('notifications.comments');
    Route::post('/comments/{id}/mark-as-read', [CommentController::class, 'markAsRead'])->name('comments.markAsRead');
    Route::post('/comments/mark-all-as-read', [CommentController::class, 'markAllAsRead'])->name('comments.markAllAsRead');

    // =====================================================
    // Đánh giá sản phẩm
    // =====================================================
    Route::get('/ratings/create', [App\Http\Controllers\RatingController::class, 'create'])->name('ratings.create');
    Route::post('/ratings', [App\Http\Controllers\RatingController::class, 'store'])->name('ratings.store');
    Route::delete('/ratings/{id}', [App\Http\Controllers\RatingController::class, 'destroy'])->name('ratings.destroy');
    Route::post('/ratings/{id}/vote', [App\Http\Controllers\RatingController::class, 'voteHelpful'])->name('ratings.vote');
    
    // =====================================================
    // Phản hồi - CHO TẤT CẢ USER
    // =====================================================
    Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    
    // THÔNG BÁO - CHO TẤT CẢ USER (THÊM MỚI)
    // =====================================================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        // API cho polling
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread.count');
        Route::get('/list', [NotificationController::class, 'getNotifications'])->name('list');
        
        // Trang danh sách thông báo
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        
        // Đánh dấu đã đọc
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read.all');
        
        // Xóa thông báo
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/clear/read', [NotificationController::class, 'clearRead'])->name('clear.read');
    });
    // =====================================================
    // Thông tin cá nhân - CHO TẤT CẢ USER
    // =====================================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
});

/*
|--------------------------------------------------------------------------
| ROUTES PHÍA ADMIN
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Quản lý danh mục
    Route::resource('categories', CategoryController::class);
    
    // Quản lý sản phẩm
    Route::resource('products', AdminProductController::class);
    Route::get('products/{id}/variants', [AdminProductController::class, 'variants'])->name('products.variants');
    Route::get('products/{id}/variants/create', [AdminProductController::class, 'createVariant'])->name('products.variants.create');
    Route::post('products/{id}/variants', [AdminProductController::class, 'storeVariant'])->name('products.variants.store');
    Route::get('products/{productId}/variants/{variantId}/edit', [AdminProductController::class, 'editVariant'])->name('products.variants.edit');
    Route::put('products/{productId}/variants/{variantId}', [AdminProductController::class, 'updateVariant'])->name('products.variants.update');
    Route::delete('products/{productId}/variants/{variantId}', [AdminProductController::class, 'destroyVariant'])->name('products.variants.destroy');
    
    // Quản lý đơn hàng
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('orders/{id}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
    
    // Quản lý người dùng
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create'); // THÊM MỚI
    Route::post('users', [UserController::class, 'store'])->name('users.store'); // THÊM MỚI
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::put('users/{id}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Quản lý bình luận
    Route::get('comments', [AdminCommentController::class, 'index'])->name('comments.index');
    Route::get('comments/{id}/view-product', [AdminCommentController::class, 'viewProduct'])->name('comments.viewProduct');
    Route::post('comments/{id}/mark-as-read', [AdminCommentController::class, 'markAsRead'])->name('comments.markAsRead');
    Route::post('comments/mark-all-as-read', [AdminCommentController::class, 'markAllAsRead'])->name('comments.markAllAsRead');
    Route::put('comments/{id}/status', [AdminCommentController::class, 'updateStatus'])->name('comments.updateStatus');
    Route::delete('comments/{id}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');
    
    // Quản lý tin tức
    Route::resource('news', AdminNewsController::class);
    
    // Quản lý phản hồi
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('/', [AdminFeedbackController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminFeedbackController::class, 'show'])->name('show');
        Route::post('/{id}/reply', [AdminFeedbackController::class, 'reply'])->name('reply');
        Route::delete('/{id}', [AdminFeedbackController::class, 'destroy'])->name('destroy');
        Route::delete('/reply/{id}', [AdminFeedbackController::class, 'deleteReply'])->name('reply.delete');
    });
    
    // Quản lý đánh giá
    Route::get('ratings', [App\Http\Controllers\Admin\RatingController::class, 'index'])->name('ratings.index');
    Route::get('ratings/{id}/view-product', [App\Http\Controllers\Admin\RatingController::class, 'viewProduct'])->name('ratings.viewProduct');
    Route::post('ratings/{id}/mark-as-read', [App\Http\Controllers\Admin\RatingController::class, 'markAsRead'])->name('ratings.markAsRead');
    Route::post('ratings/mark-all-as-read', [App\Http\Controllers\Admin\RatingController::class, 'markAllAsRead'])->name('ratings.markAllAsRead');
    Route::put('ratings/{id}/status', [App\Http\Controllers\Admin\RatingController::class, 'updateStatus'])->name('ratings.updateStatus');
    Route::post('ratings/{id}/reply', [App\Http\Controllers\Admin\RatingController::class, 'reply'])->name('ratings.reply');
    Route::delete('ratings/{id}/reply', [App\Http\Controllers\Admin\RatingController::class, 'deleteReply'])->name('ratings.deleteReply');
    Route::delete('ratings/{id}', [App\Http\Controllers\Admin\RatingController::class, 'destroy'])->name('ratings.destroy');

    // =====================================================
    // QUẢN LÝ MÃ GIẢM GIÁ
    // =====================================================
    Route::get('coupons', [MaGiamGiaController::class, 'index'])->name('coupons.index');
    Route::get('coupons/create', [MaGiamGiaController::class, 'create'])->name('coupons.create');
    Route::post('coupons', [MaGiamGiaController::class, 'store'])->name('coupons.store');
    Route::get('coupons/{id}/edit', [MaGiamGiaController::class, 'edit'])->name('coupons.edit');
    Route::put('coupons/{id}', [MaGiamGiaController::class, 'update'])->name('coupons.update');
    Route::delete('coupons/{id}', [MaGiamGiaController::class, 'destroy'])->name('coupons.destroy');
    Route::get('coupons/{id}/history', [MaGiamGiaController::class, 'lichSu'])->name('coupons.history');
});