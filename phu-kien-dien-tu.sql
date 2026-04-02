-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for phu_kien_dien_tu
DROP DATABASE IF EXISTS `phu_kien_dien_tu`;
CREATE DATABASE IF NOT EXISTS `phu_kien_dien_tu` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `phu_kien_dien_tu`;

-- Dumping structure for table phu_kien_dien_tu.binh_luan
DROP TABLE IF EXISTS `binh_luan`;
CREATE TABLE IF NOT EXISTS `binh_luan` (
  `MaBinhLuan` bigint unsigned NOT NULL AUTO_INCREMENT,
  `MaBinhLuanCha` bigint unsigned DEFAULT NULL COMMENT 'Mã bình luận cha (null = bình luận gốc)',
  `MaNguoiDung` bigint unsigned NOT NULL,
  `MaSanPham` bigint unsigned NOT NULL,
  `NoiDung` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `TrangThai` enum('cho_duyet','da_duyet','bi_an') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'da_duyet' COMMENT 'Trạng thái kiểm duyệt bình luận',
  `DaXem` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Admin đã xem bình luận chưa',
  `NgayBinhLuan` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaBinhLuan`),
  KEY `binh_luan_manguoidung_foreign` (`MaNguoiDung`),
  KEY `binh_luan_masanpham_foreign` (`MaSanPham`),
  KEY `binh_luan_mabinhluancha_foreign` (`MaBinhLuanCha`),
  CONSTRAINT `binh_luan_mabinhluancha_foreign` FOREIGN KEY (`MaBinhLuanCha`) REFERENCES `binh_luan` (`MaBinhLuan`) ON DELETE CASCADE,
  CONSTRAINT `binh_luan_manguoidung_foreign` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoi_dung` (`MaNguoiDung`) ON DELETE CASCADE,
  CONSTRAINT `binh_luan_masanpham_foreign` FOREIGN KEY (`MaSanPham`) REFERENCES `san_pham` (`MaSanPham`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.binh_luan: ~0 rows (approximately)
REPLACE INTO `binh_luan` (`MaBinhLuan`, `MaBinhLuanCha`, `MaNguoiDung`, `MaSanPham`, `NoiDung`, `TrangThai`, `DaXem`, `NgayBinhLuan`, `created_at`, `updated_at`) VALUES
	(1, NULL, 1, 2, 'sản phẩm tốt', 'da_duyet', 1, '2026-02-12 14:23:14', '2026-02-12 07:23:14', '2026-02-12 07:23:14');

-- Dumping structure for table phu_kien_dien_tu.ct_don_hang
DROP TABLE IF EXISTS `ct_don_hang`;
CREATE TABLE IF NOT EXISTS `ct_don_hang` (
  `MaCTDonHang` bigint unsigned NOT NULL AUTO_INCREMENT,
  `MaDonHang` bigint unsigned NOT NULL,
  `MaCTSanPham` bigint unsigned NOT NULL,
  `SoLuong` int NOT NULL,
  `DonGia` decimal(18,2) NOT NULL,
  `ThanhTien` decimal(18,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaCTDonHang`),
  KEY `ct_don_hang_madonhang_foreign` (`MaDonHang`),
  KEY `ct_don_hang_mactsanpham_foreign` (`MaCTSanPham`),
  CONSTRAINT `ct_don_hang_mactsanpham_foreign` FOREIGN KEY (`MaCTSanPham`) REFERENCES `ct_san_pham` (`MaCTSanPham`) ON DELETE CASCADE,
  CONSTRAINT `ct_don_hang_madonhang_foreign` FOREIGN KEY (`MaDonHang`) REFERENCES `don_hang` (`MaDonHang`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.ct_don_hang: ~8 rows (approximately)
REPLACE INTO `ct_don_hang` (`MaCTDonHang`, `MaDonHang`, `MaCTSanPham`, `SoLuong`, `DonGia`, `ThanhTien`, `created_at`, `updated_at`) VALUES
	(2, 2, 3, 1, 37690000.00, 37690000.00, '2026-03-14 08:59:04', '2026-03-14 08:59:04'),
	(3, 3, 3, 1, 37690000.00, 37690000.00, '2026-03-20 08:51:22', '2026-03-20 08:51:22'),
	(4, 4, 3, 1, 37690000.00, 37690000.00, '2026-03-20 08:56:40', '2026-03-20 08:56:40'),
	(5, 5, 3, 1, 37690000.00, 37690000.00, '2026-03-29 06:14:27', '2026-03-29 06:14:27'),
	(6, 6, 3, 1, 37690000.00, 37690000.00, '2026-03-29 07:01:30', '2026-03-29 07:01:30'),
	(7, 7, 3, 1, 37690000.00, 37690000.00, '2026-03-29 07:02:43', '2026-03-29 07:02:43'),
	(8, 8, 3, 1, 37690000.00, 37690000.00, '2026-03-31 06:02:21', '2026-03-31 06:02:21'),
	(9, 9, 3, 1, 37690000.00, 37690000.00, '2026-03-31 06:03:13', '2026-03-31 06:03:13');

-- Dumping structure for table phu_kien_dien_tu.ct_gio_hang
DROP TABLE IF EXISTS `ct_gio_hang`;
CREATE TABLE IF NOT EXISTS `ct_gio_hang` (
  `MaCTGioHang` bigint unsigned NOT NULL AUTO_INCREMENT,
  `MaNguoiDung` bigint unsigned NOT NULL,
  `MaCTSanPham` bigint unsigned NOT NULL,
  `SoLuong` int NOT NULL,
  `DonGia` decimal(18,2) NOT NULL,
  `NgayThem` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaCTGioHang`),
  KEY `ct_gio_hang_manguoidung_foreign` (`MaNguoiDung`),
  KEY `ct_gio_hang_mactsanpham_foreign` (`MaCTSanPham`),
  CONSTRAINT `ct_gio_hang_mactsanpham_foreign` FOREIGN KEY (`MaCTSanPham`) REFERENCES `ct_san_pham` (`MaCTSanPham`) ON DELETE CASCADE,
  CONSTRAINT `ct_gio_hang_manguoidung_foreign` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoi_dung` (`MaNguoiDung`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.ct_gio_hang: ~0 rows (approximately)

-- Dumping structure for table phu_kien_dien_tu.ct_san_pham
DROP TABLE IF EXISTS `ct_san_pham`;
CREATE TABLE IF NOT EXISTS `ct_san_pham` (
  `MaCTSanPham` bigint unsigned NOT NULL AUTO_INCREMENT,
  `MaSanPham` bigint unsigned NOT NULL,
  `MauSac` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DungLuong` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `KichThuoc` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DonGia` decimal(18,2) NOT NULL,
  `SoLuongTon` int NOT NULL DEFAULT '0',
  `AnhMinhHoa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TrangThai` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = Hoạt động, 0 = Ẩn',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaCTSanPham`),
  KEY `ct_san_pham_masanpham_foreign` (`MaSanPham`),
  CONSTRAINT `ct_san_pham_masanpham_foreign` FOREIGN KEY (`MaSanPham`) REFERENCES `san_pham` (`MaSanPham`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.ct_san_pham: ~25 rows (approximately)
REPLACE INTO `ct_san_pham` (`MaCTSanPham`, `MaSanPham`, `MauSac`, `DungLuong`, `KichThuoc`, `DonGia`, `SoLuongTon`, `AnhMinhHoa`, `TrangThai`, `created_at`, `updated_at`) VALUES
	(3, 2, 'Cam', '256GB', NULL, 37690000.00, 92, '/images/variants/1770391593_69860829bc70c.webp', 1, '2026-02-06 08:26:33', '2026-03-31 06:03:13'),
	(4, 2, 'Xanh', '128GB', 'S', 30000000.00, 30, '/images/variants/1770906324_698de2d493dc5.webp', 1, '2026-02-12 07:19:28', '2026-02-12 07:25:24'),
	(5, 2, 'Bạc', '256GB', 'L', 33000000.00, 50, '/images/variants/1770906293_698de2b53c199.webp', 1, '2026-02-12 07:24:53', '2026-02-12 07:24:53'),
	(6, 4, 'Đen', NULL, NULL, 24890000.00, 100, '/images/variants/1774880990_69ca88de317af.webp', 1, '2026-03-30 07:29:50', '2026-03-30 07:29:50'),
	(7, 4, 'Xanh', NULL, NULL, 24890000.00, 100, '/images/variants/1774881013_69ca88f513df2.webp', 1, '2026-03-30 07:30:13', '2026-03-30 07:30:13'),
	(8, 4, 'Tím', NULL, NULL, 24890000.00, 100, '/images/variants/1774881035_69ca890bb6537.webp', 1, '2026-03-30 07:30:35', '2026-03-30 07:30:43'),
	(9, 4, 'Xanh', NULL, NULL, 24890000.00, 100, '/images/variants/1774881066_69ca892a5d783.webp', 1, '2026-03-30 07:31:06', '2026-03-30 07:31:06'),
	(10, 5, 'Tím', NULL, NULL, 32990000.00, 200, '/images/variants/1774881790_69ca8bfe4c520.webp', 1, '2026-03-30 07:43:10', '2026-03-30 07:43:10'),
	(11, 5, 'Đen', NULL, NULL, 32990000.00, 200, '/images/variants/1774881814_69ca8c16bbe49.webp', 1, '2026-03-30 07:43:34', '2026-03-30 07:43:34'),
	(12, 5, 'Xanh', NULL, NULL, 32990000.00, 200, '/images/variants/1774881837_69ca8c2da8291.webp', 1, '2026-03-30 07:43:57', '2026-03-30 07:43:57'),
	(13, 6, 'Xanh', NULL, NULL, 40990000.00, 120, '/images/variants/1774882090_69ca8d2a12cb1.webp', 1, '2026-03-30 07:48:10', '2026-03-30 07:48:10'),
	(14, 6, 'Đen', NULL, NULL, 40990000.00, 120, '/images/variants/1774882107_69ca8d3b72816.webp', 1, '2026-03-30 07:48:27', '2026-03-30 07:48:27'),
	(15, 6, 'Trắng', NULL, NULL, 40990000.00, 150, '/images/variants/1774882123_69ca8d4b5767c.webp', 1, '2026-03-30 07:48:43', '2026-03-30 07:48:43'),
	(16, 7, 'Xanh', NULL, NULL, 12990000.00, 300, '/images/variants/1774882385_69ca8e51c5601.webp', 1, '2026-03-30 07:53:05', '2026-03-30 07:53:05'),
	(17, 7, 'Nâu', NULL, NULL, 12990000.00, 200, '/images/variants/1774882405_69ca8e6520875.webp', 1, '2026-03-30 07:53:25', '2026-03-30 07:53:25'),
	(18, 7, 'Đen', NULL, NULL, 12990000.00, 250, '/images/variants/1774882421_69ca8e75c2eb0.webp', 1, '2026-03-30 07:53:41', '2026-03-30 07:53:41'),
	(19, 8, 'Tím', NULL, NULL, 8000000.00, 500, '/images/variants/1774886347_69ca9dcb87c14.webp', 1, '2026-03-30 08:59:07', '2026-03-30 08:59:07'),
	(20, 8, 'Đen', NULL, NULL, 8000000.00, 400, '/images/variants/1774886379_69ca9deb2ec04.webp', 1, '2026-03-30 08:59:39', '2026-03-30 08:59:39'),
	(21, 8, 'Nâu', NULL, NULL, 8000000.00, 300, '/images/variants/1774886403_69ca9e03703f2.webp', 1, '2026-03-30 09:00:03', '2026-03-30 09:00:12'),
	(22, 9, 'Đen', NULL, NULL, 4000000.00, 200, '/images/variants/1774888822_69caa776b8e37.webp', 1, '2026-03-30 09:40:22', '2026-03-30 09:40:22'),
	(23, 9, 'Trắng', NULL, NULL, 5000000.00, 400, '/images/variants/1774888843_69caa78bbe122.webp', 1, '2026-03-30 09:40:43', '2026-03-30 09:40:51'),
	(24, 9, 'Xanh', NULL, NULL, 5000000.00, 300, '/images/variants/1774888876_69caa7ac32473.webp', 1, '2026-03-30 09:41:16', '2026-03-30 09:41:16'),
	(25, 10, 'Xanh', NULL, NULL, 13000000.00, 200, '/images/variants/1774889111_69caa8979b367.webp', 1, '2026-03-30 09:45:11', '2026-03-30 09:45:11'),
	(26, 10, 'Hồng', NULL, NULL, 17000000.00, 200, '/images/variants/1774889132_69caa8acd4740.webp', 1, '2026-03-30 09:45:32', '2026-03-30 09:45:32'),
	(27, 10, 'Đen', NULL, NULL, 14000000.00, 300, '/images/variants/1774889149_69caa8bd8e1c5.webp', 1, '2026-03-30 09:45:49', '2026-03-30 09:45:56'),
	(28, 11, 'Đen', NULL, NULL, 20000000.00, 200, '/images/variants/1774889328_69caa970bf83b.webp', 1, '2026-03-30 09:48:48', '2026-03-30 09:48:48'),
	(29, 11, 'Đỏ', NULL, NULL, 22000000.00, 300, '/images/variants/1774889352_69caa988a3cc2.webp', 1, '2026-03-30 09:49:12', '2026-03-30 09:49:12'),
	(30, 11, 'Xanh', NULL, NULL, 21000000.00, 200, '/images/variants/1774889384_69caa9a8830b2.webp', 1, '2026-03-30 09:49:44', '2026-03-30 09:49:44');

-- Dumping structure for table phu_kien_dien_tu.danh_gia
DROP TABLE IF EXISTS `danh_gia`;
CREATE TABLE IF NOT EXISTS `danh_gia` (
  `MaDanhGia` bigint unsigned NOT NULL AUTO_INCREMENT,
  `MaNguoiDung` bigint unsigned NOT NULL,
  `MaSanPham` bigint unsigned NOT NULL,
  `MaDonHang` bigint unsigned NOT NULL,
  `SoSao` tinyint NOT NULL COMMENT '1-5 sao',
  `NoiDung` text COLLATE utf8mb4_unicode_ci COMMENT 'Nội dung đánh giá',
  `HinhAnh` json DEFAULT NULL COMMENT 'Danh sách ảnh đánh giá (tối đa 5 ảnh)',
  `TrangThai` enum('cho_duyet','da_duyet','bi_an') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'da_duyet',
  `DaXem` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Admin đã xem đánh giá chưa',
  `PhanHoiShop` text COLLATE utf8mb4_unicode_ci COMMENT 'Phản hồi từ shop/admin',
  `NguoiPhanHoi` bigint unsigned DEFAULT NULL,
  `NgayPhanHoi` datetime DEFAULT NULL COMMENT 'Ngày admin phản hồi',
  `NgayDanhGia` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaDanhGia`),
  UNIQUE KEY `unique_danh_gia` (`MaNguoiDung`,`MaSanPham`,`MaDonHang`),
  KEY `danh_gia_masanpham_foreign` (`MaSanPham`),
  KEY `danh_gia_madonhang_foreign` (`MaDonHang`),
  KEY `danh_gia_nguoiphanhoi_foreign` (`NguoiPhanHoi`),
  CONSTRAINT `danh_gia_madonhang_foreign` FOREIGN KEY (`MaDonHang`) REFERENCES `don_hang` (`MaDonHang`) ON DELETE CASCADE,
  CONSTRAINT `danh_gia_manguoidung_foreign` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoi_dung` (`MaNguoiDung`) ON DELETE CASCADE,
  CONSTRAINT `danh_gia_masanpham_foreign` FOREIGN KEY (`MaSanPham`) REFERENCES `san_pham` (`MaSanPham`) ON DELETE CASCADE,
  CONSTRAINT `danh_gia_nguoiphanhoi_foreign` FOREIGN KEY (`NguoiPhanHoi`) REFERENCES `nguoi_dung` (`MaNguoiDung`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.danh_gia: ~0 rows (approximately)

-- Dumping structure for table phu_kien_dien_tu.danh_gia_huu_ich
DROP TABLE IF EXISTS `danh_gia_huu_ich`;
CREATE TABLE IF NOT EXISTS `danh_gia_huu_ich` (
  `MaHuuIch` bigint unsigned NOT NULL AUTO_INCREMENT,
  `MaDanhGia` bigint unsigned NOT NULL,
  `MaNguoiDung` bigint unsigned NOT NULL,
  `HuuIch` tinyint(1) NOT NULL COMMENT 'true = hữu ích, false = không hữu ích',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaHuuIch`),
  UNIQUE KEY `danh_gia_huu_ich_madanhgia_manguoidung_unique` (`MaDanhGia`,`MaNguoiDung`),
  KEY `danh_gia_huu_ich_manguoidung_foreign` (`MaNguoiDung`),
  CONSTRAINT `danh_gia_huu_ich_madanhgia_foreign` FOREIGN KEY (`MaDanhGia`) REFERENCES `danh_gia` (`MaDanhGia`) ON DELETE CASCADE,
  CONSTRAINT `danh_gia_huu_ich_manguoidung_foreign` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoi_dung` (`MaNguoiDung`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.danh_gia_huu_ich: ~0 rows (approximately)

-- Dumping structure for table phu_kien_dien_tu.danh_muc
DROP TABLE IF EXISTS `danh_muc`;
CREATE TABLE IF NOT EXISTS `danh_muc` (
  `MaDanhMuc` bigint unsigned NOT NULL AUTO_INCREMENT,
  `TenDanhMuc` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MoTa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TrangThai` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = Hiển thị, 0 = Ẩn',
  `AnhMinhHoa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaDanhMuc`),
  UNIQUE KEY `danh_muc_tendanhmuc_unique` (`TenDanhMuc`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.danh_muc: ~5 rows (approximately)
REPLACE INTO `danh_muc` (`MaDanhMuc`, `TenDanhMuc`, `MoTa`, `TrangThai`, `AnhMinhHoa`, `created_at`, `updated_at`) VALUES
	(4, 'Điện thoại', 'Điện thoại', 1, '/images/categories/1770045288_6980bf6885f9d.webp', '2026-02-02 08:14:48', '2026-02-02 08:14:48'),
	(5, 'Máy tính bảng', 'Máy tính bảng', 1, '/images/categories/1770045334_6980bf96a4736.webp', '2026-02-02 08:15:34', '2026-02-02 08:15:34'),
	(6, 'Laptop', 'Laptop', 1, '/images/categories/1770045373_6980bfbd5e823.webp', '2026-02-02 08:16:13', '2026-02-02 08:16:13'),
	(7, 'Màn hình', 'Màn hình', 1, '/images/categories/1770045451_6980c00b830a2.webp', '2026-02-02 08:17:31', '2026-02-02 08:17:31'),
	(8, 'Máy tính để bàn', 'Máy tính để bàn', 1, '/images/categories/1770045488_6980c03096653.webp', '2026-02-02 08:18:08', '2026-02-02 08:18:08'),
	(9, 'Phụ kiện', 'Phụ kiện', 1, '/images/categories/1770045515_6980c04b74ee7.webp', '2026-02-02 08:18:35', '2026-02-02 08:18:35');

-- Dumping structure for table phu_kien_dien_tu.don_hang
DROP TABLE IF EXISTS `don_hang`;
CREATE TABLE IF NOT EXISTS `don_hang` (
  `MaDonHang` bigint unsigned NOT NULL AUTO_INCREMENT,
  `MaNguoiDung` bigint unsigned NOT NULL,
  `NgayDat` datetime NOT NULL,
  `TongTien` decimal(18,2) NOT NULL,
  `MaGiamGia` bigint unsigned DEFAULT NULL,
  `SoTienGiam` decimal(18,2) NOT NULL DEFAULT '0.00',
  `DiaChiGiaoHang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `PTThanhToan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'COD, Bank, VNPay',
  `TrangThai` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Chờ xác nhận',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaDonHang`),
  KEY `don_hang_manguoidung_foreign` (`MaNguoiDung`),
  KEY `don_hang_mamagiamgia_foreign` (`MaGiamGia`),
  CONSTRAINT `don_hang_mamagiamgia_foreign` FOREIGN KEY (`MaGiamGia`) REFERENCES `ma_giam_gia` (`MaGiamGia`) ON DELETE SET NULL,
  CONSTRAINT `don_hang_manguoidung_foreign` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoi_dung` (`MaNguoiDung`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.don_hang: ~9 rows (approximately)
REPLACE INTO `don_hang` (`MaDonHang`, `MaNguoiDung`, `NgayDat`, `TongTien`, `MaGiamGia`, `SoTienGiam`, `DiaChiGiaoHang`, `PTThanhToan`, `TrangThai`, `created_at`, `updated_at`) VALUES
	(1, 2, '2026-01-20 14:37:28', 1000000.00, NULL, 0.00, 'HN', 'Bank', 'Đã giao hàng', '2026-01-20 07:37:28', '2026-02-13 07:17:22'),
	(2, 2, '2026-03-14 15:59:04', 37690000.00, NULL, 0.00, '123', 'Bank', 'Chờ xác nhận', '2026-03-14 08:59:04', '2026-03-14 08:59:04'),
	(3, 2, '2026-03-20 15:51:22', 37690000.00, NULL, 0.00, '123', 'Bank', 'Chờ xác nhận', '2026-03-20 08:51:22', '2026-03-20 08:51:22'),
	(4, 2, '2026-03-20 15:56:40', 37690000.00, NULL, 0.00, '123', 'VNPay', 'Chờ xác nhận', '2026-03-20 08:56:40', '2026-03-20 08:56:40'),
	(5, 2, '2026-03-29 13:14:27', 37690000.00, NULL, 0.00, '123123', 'Bank', 'Chờ xác nhận', '2026-03-29 06:14:27', '2026-03-29 06:14:27'),
	(6, 2, '2026-03-29 14:01:30', 37690000.00, NULL, 0.00, '12345', 'Bank', 'Chờ xác nhận', '2026-03-29 07:01:30', '2026-03-29 07:01:30'),
	(7, 2, '2026-03-29 14:02:43', 37690000.00, NULL, 0.00, '123455', 'Bank', 'Đã giao hàng', '2026-03-29 07:02:43', '2026-03-31 07:52:25'),
	(8, 2, '2026-03-31 13:02:21', 37690000.00, NULL, 0.00, 'hà nội', 'Bank', 'Chờ xác nhận', '2026-03-31 06:02:21', '2026-03-31 06:02:21'),
	(9, 2, '2026-03-31 13:03:13', 37690000.00, NULL, 0.00, 'hn', 'Bank', 'Đã xác nhận', '2026-03-31 06:03:13', '2026-03-31 07:52:09');

-- Dumping structure for table phu_kien_dien_tu.lich_su_ma_giam_gia
DROP TABLE IF EXISTS `lich_su_ma_giam_gia`;
CREATE TABLE IF NOT EXISTS `lich_su_ma_giam_gia` (
  `MaLichSu` bigint unsigned NOT NULL AUTO_INCREMENT,
  `MaGiamGia` bigint unsigned NOT NULL,
  `MaNguoiDung` bigint unsigned DEFAULT NULL,
  `MaDonHang` bigint unsigned DEFAULT NULL,
  `SoTienGiam` decimal(18,2) NOT NULL,
  `ThoiGianSuDung` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaLichSu`),
  KEY `lich_su_ma_giam_gia_mamagiamgia_foreign` (`MaGiamGia`),
  KEY `lich_su_ma_giam_gia_manguoidung_foreign` (`MaNguoiDung`),
  KEY `lich_su_ma_giam_gia_madonhang_foreign` (`MaDonHang`),
  CONSTRAINT `lich_su_ma_giam_gia_madonhang_foreign` FOREIGN KEY (`MaDonHang`) REFERENCES `don_hang` (`MaDonHang`) ON DELETE SET NULL,
  CONSTRAINT `lich_su_ma_giam_gia_mamagiamgia_foreign` FOREIGN KEY (`MaGiamGia`) REFERENCES `ma_giam_gia` (`MaGiamGia`) ON DELETE CASCADE,
  CONSTRAINT `lich_su_ma_giam_gia_manguoidung_foreign` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoi_dung` (`MaNguoiDung`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.lich_su_ma_giam_gia: ~0 rows (approximately)

-- Dumping structure for table phu_kien_dien_tu.ma_giam_gia
DROP TABLE IF EXISTS `ma_giam_gia`;
CREATE TABLE IF NOT EXISTS `ma_giam_gia` (
  `MaGiamGia` bigint unsigned NOT NULL AUTO_INCREMENT,
  `MaCode` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `LoaiGiam` enum('fixed','percent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `GiaTri` decimal(18,2) NOT NULL,
  `DonToiThieu` decimal(18,2) DEFAULT NULL,
  `GiamToiDa` decimal(18,2) DEFAULT NULL,
  `GioiHanSuDung` int DEFAULT NULL,
  `GioiHanMoiNguoi` int DEFAULT NULL COMMENT 'Số lần tối đa mỗi người dùng được sử dụng (NULL = không giới hạn)',
  `DaSuDung` int NOT NULL DEFAULT '0',
  `NgayBatDau` datetime DEFAULT NULL,
  `NgayKetThuc` datetime DEFAULT NULL,
  `TrangThai` tinyint(1) NOT NULL DEFAULT '1',
  `MoTa` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaGiamGia`),
  UNIQUE KEY `ma_giam_gia_macode_unique` (`MaCode`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.ma_giam_gia: ~1 rows (approximately)
REPLACE INTO `ma_giam_gia` (`MaGiamGia`, `MaCode`, `LoaiGiam`, `GiaTri`, `DonToiThieu`, `GiamToiDa`, `GioiHanSuDung`, `GioiHanMoiNguoi`, `DaSuDung`, `NgayBatDau`, `NgayKetThuc`, `TrangThai`, `MoTa`, `created_at`, `updated_at`) VALUES
	(1, 'GIAMGIA', 'fixed', 100000.00, NULL, NULL, 5, NULL, 0, '2026-01-31 21:55:00', '2029-03-31 21:55:00', 1, NULL, '2026-03-15 07:55:24', '2026-03-15 07:56:13');

-- Dumping structure for table phu_kien_dien_tu.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.migrations: ~0 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(2, '2025_11_16_084403_create_danh_muc_table', 1),
	(3, '2025_11_16_085644_create_nguoi_dung_table', 1),
	(4, '2025_11_16_085650_create_san_pham_table', 1),
	(5, '2025_11_16_085909_create_tin_tuc_table', 1),
	(6, '2025_11_16_085920_create_phan_hoi_table', 1),
	(7, '2025_11_16_085948_create_don_hang_table', 1),
	(8, '2025_11_16_085957_create_ct_san_pham_table', 1),
	(9, '2025_11_16_090056_create_binh_luan_table', 1),
	(10, '2025_11_16_090158_create_ct_gio_hang_table', 1),
	(11, '2025_11_16_090244_create_ct_don_hang_table', 1),
	(12, '2025_11_17_173438_add_thuong_hieu_to_san_pham', 1),
	(13, '2025_11_26_140503_add_columns_to_binh_luan_table', 1),
	(14, '2025_11_27_113914_create_danh_gia_table', 1),
	(15, '2025_11_27_135938_add_hinh_anh_to_danh_gia_table', 1),
	(16, '2025_11_27_140134_create_danh_gia_huu_ich_table', 1),
	(17, '2025_11_27_140203_add_phan_hoi_shop_to_danh_gia_table', 1),
	(18, '2025_11_29_054811_create_ma_giam_gia_table', 1),
	(19, '2025_11_29_081355_add_da_xem_to_danh_gia_table', 1),
	(20, '2025_12_01_130506_create_tra_loi_phan_hoi_table', 1),
	(21, '2025_12_01_135154_create_notifications_table', 1),
	(22, '2025_12_02_115508_add_limit_per_user_to_ma_giam_gia_table', 1),
	(23, '2026_02_02_142847_add_anh_minh_hoa_to_danh_muc_table', 2);

-- Dumping structure for table phu_kien_dien_tu.nguoi_dung
DROP TABLE IF EXISTS `nguoi_dung`;
CREATE TABLE IF NOT EXISTS `nguoi_dung` (
  `MaNguoiDung` bigint unsigned NOT NULL AUTO_INCREMENT,
  `TenDangNhap` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MatKhau` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `HoTen` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `AnhDaiDien` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SoDienThoai` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DiaChi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `VaiTro` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = Khách hàng, 1 = Admin',
  `TrangThai` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = Hoạt động, 0 = Khóa',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaNguoiDung`),
  UNIQUE KEY `nguoi_dung_tendangnhap_unique` (`TenDangNhap`),
  UNIQUE KEY `nguoi_dung_email_unique` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.nguoi_dung: ~2 rows (approximately)
REPLACE INTO `nguoi_dung` (`MaNguoiDung`, `TenDangNhap`, `Email`, `MatKhau`, `HoTen`, `AnhDaiDien`, `SoDienThoai`, `DiaChi`, `VaiTro`, `TrangThai`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'admin', '$2y$12$zfaT8sCuEXbf34jWV3iMXe852MPK899H0SnHX3hZiY7.dvpowHORq', 'admin', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL),
	(2, 'khachhang', 'khachhang@gmail.com', '$2y$12$E1rKrpLqEP448nJS6yqvg.YuMyjzbNsJZRo.Y2gG4fAHRJrWYPspG', 'khachhang', NULL, '1234567890', NULL, 0, 1, NULL, '2026-01-20 07:35:41', '2026-01-20 07:37:28');

-- Dumping structure for table phu_kien_dien_tu.notifications
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `MaThongBao` bigint unsigned NOT NULL AUTO_INCREMENT,
  `MaNguoiDung` bigint unsigned NOT NULL,
  `LoaiThongBao` enum('comment_reply','review_reply','feedback_reply','order_confirmed') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại thông báo',
  `TieuDe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tiêu đề thông báo',
  `NoiDung` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung thông báo',
  `Link` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL chuyển hướng khi click',
  `DaDoc` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'true = đã đọc, false = chưa đọc',
  `ThoiGian` datetime NOT NULL COMMENT 'Thời gian tạo thông báo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaThongBao`),
  KEY `notifications_manguoidung_dadoc_index` (`MaNguoiDung`,`DaDoc`),
  KEY `notifications_thoigian_index` (`ThoiGian`),
  CONSTRAINT `notifications_manguoidung_foreign` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoi_dung` (`MaNguoiDung`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.notifications: ~3 rows (approximately)
REPLACE INTO `notifications` (`MaThongBao`, `MaNguoiDung`, `LoaiThongBao`, `TieuDe`, `NoiDung`, `Link`, `DaDoc`, `ThoiGian`, `created_at`, `updated_at`) VALUES
	(1, 2, 'order_confirmed', 'Đơn hàng đã được xác nhận', 'Đơn hàng #1 của bạn đã được xác nhận và đang được chuẩn bị', 'http://localhost:8000/orders/1', 0, '2026-02-13 14:17:14', '2026-02-13 07:17:14', '2026-02-13 07:17:14'),
	(2, 2, 'order_confirmed', 'Đơn hàng đã được xác nhận', 'Đơn hàng #9 của bạn đã được xác nhận và đang được chuẩn bị', 'http://localhost:8000/orders/9', 0, '2026-03-31 14:52:09', '2026-03-31 07:52:09', '2026-03-31 07:52:09'),
	(3, 2, 'order_confirmed', 'Đơn hàng đã được xác nhận', 'Đơn hàng #7 của bạn đã được xác nhận và đang được chuẩn bị', 'http://localhost:8000/orders/7', 0, '2026-03-31 14:52:20', '2026-03-31 07:52:20', '2026-03-31 07:52:20');

-- Dumping structure for table phu_kien_dien_tu.personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table phu_kien_dien_tu.phan_hoi
DROP TABLE IF EXISTS `phan_hoi`;
CREATE TABLE IF NOT EXISTS `phan_hoi` (
  `MaPhanHoi` bigint unsigned NOT NULL AUTO_INCREMENT,
  `MaNguoiDung` bigint unsigned NOT NULL,
  `TieuDe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `NoiDung` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `NgayGui` datetime NOT NULL,
  `TrangThai` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = Chưa xem, 1 = Đã xem',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaPhanHoi`),
  KEY `phan_hoi_manguoidung_foreign` (`MaNguoiDung`),
  CONSTRAINT `phan_hoi_manguoidung_foreign` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoi_dung` (`MaNguoiDung`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.phan_hoi: ~1 rows (approximately)
REPLACE INTO `phan_hoi` (`MaPhanHoi`, `MaNguoiDung`, `TieuDe`, `NoiDung`, `NgayGui`, `TrangThai`, `created_at`, `updated_at`) VALUES
	(1, 2, 'Hỗ trợ', 'Hỗ trợ Hỗ trợ Hỗ trợ Hỗ trợ', '2026-03-15 11:32:16', 1, '2026-03-15 04:32:16', '2026-03-15 07:37:20');

-- Dumping structure for table phu_kien_dien_tu.san_pham
DROP TABLE IF EXISTS `san_pham`;
CREATE TABLE IF NOT EXISTS `san_pham` (
  `MaSanPham` bigint unsigned NOT NULL AUTO_INCREMENT,
  `MaDanhMuc` bigint unsigned NOT NULL,
  `TenSanPham` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ThuongHieu` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AnhChinh` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ThongSoKyThuat` text COLLATE utf8mb4_unicode_ci,
  `MoTa` text COLLATE utf8mb4_unicode_ci,
  `TrangThai` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = Hoạt động, 0 = Ẩn',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaSanPham`),
  KEY `san_pham_madanhmuc_foreign` (`MaDanhMuc`),
  CONSTRAINT `san_pham_madanhmuc_foreign` FOREIGN KEY (`MaDanhMuc`) REFERENCES `danh_muc` (`MaDanhMuc`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.san_pham: ~8 rows (approximately)
REPLACE INTO `san_pham` (`MaSanPham`, `MaDanhMuc`, `TenSanPham`, `ThuongHieu`, `AnhChinh`, `ThongSoKyThuat`, `MoTa`, `TrangThai`, `created_at`, `updated_at`) VALUES
	(2, 4, 'iPhone 17 Pro Max', 'Apple', '/images/products/1770390786_69860502acb24.webp', '{"Chip":"A5","RAM":"16 Gb"}', 'iPhone 17 Pro Max', 1, '2026-02-06 08:13:06', '2026-03-29 07:44:38'),
	(4, 4, 'iPhone 16 Plus', 'Apple', '/images/products/1774880941_69ca88ad929ac.webp', '{"B\\u1ed9 nh\\u1edb trong":"256 GB","H\\u1ec7 \\u0111i\\u1ec1u h\\u00e0nh":"iOS 26"}', 'iPhone 16 Plus', 1, '2026-03-30 07:29:01', '2026-03-30 07:29:01'),
	(5, 4, 'Samsung Galaxy S26 Ultra', 'Samsung', '/images/products/1774881742_69ca8bced194a.webp', '{"RAM":"12GB","K\\u00edch th\\u01b0\\u1edbc m\\u00e0n h\\u00ecnh":"6.9 inches"}', 'Samsung Galaxy S26 Ultra', 1, '2026-03-30 07:42:22', '2026-03-30 07:42:22'),
	(6, 4, 'Samsung Galaxy Z Fold7', 'Samsung', '/images/products/1774882045_69ca8cfd4f34c.webp', '{"K\\u00edch th\\u01b0\\u1edbc m\\u00e0n h\\u00ecnh":"8.0 inches"}', 'Samsung Galaxy Z Fold7', 1, '2026-03-30 07:47:25', '2026-03-30 07:47:25'),
	(7, 4, 'Xiaomi 14T Pro', 'Xiaomi', '/images/products/1774882351_69ca8e2fb17f3.webp', '{"K\\u00edch th\\u01b0\\u1edbc m\\u00e0n h\\u00ecnh":"6.67 inches","B\\u1ed9 nh\\u1edb trong":"512 GB"}', 'Xiaomi 14T Pro', 1, '2026-03-30 07:52:31', '2026-03-30 07:52:31'),
	(8, 4, 'Xiaomi Redmi Note 14 Pro', 'Xiaomi', '/images/products/1774886310_69ca9da6e5c04.webp', '[]', 'Xiaomi Redmi Note 14 Pro', 1, '2026-03-30 08:58:30', '2026-03-30 08:58:30'),
	(9, 4, 'Honor X7d', 'Honor', '/images/products/1774888792_69caa758d3795.webp', '[]', 'Honor X7d', 1, '2026-03-30 09:39:52', '2026-03-30 09:39:52'),
	(10, 4, 'iPhone 15', 'Apple', '/images/products/1774889083_69caa87bb50a1.webp', '[]', 'iPhone 15', 1, '2026-03-30 09:44:43', '2026-03-30 09:44:43'),
	(11, 4, 'Samsung Galaxy Z Flip7', 'Samsung', '/images/products/1774889292_69caa94c7de43.webp', '[]', 'Samsung Galaxy Z Flip7', 1, '2026-03-30 09:48:12', '2026-03-30 09:48:12');

-- Dumping structure for table phu_kien_dien_tu.tin_tuc
DROP TABLE IF EXISTS `tin_tuc`;
CREATE TABLE IF NOT EXISTS `tin_tuc` (
  `MaTinTuc` bigint unsigned NOT NULL AUTO_INCREMENT,
  `MaNguoiDung` bigint unsigned NOT NULL,
  `TieuDe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `NoiDung` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `AnhMinhHoa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `NgayDang` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaTinTuc`),
  UNIQUE KEY `tin_tuc_tieude_unique` (`TieuDe`),
  KEY `tin_tuc_manguoidung_foreign` (`MaNguoiDung`),
  CONSTRAINT `tin_tuc_manguoidung_foreign` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoi_dung` (`MaNguoiDung`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.tin_tuc: ~0 rows (approximately)

-- Dumping structure for table phu_kien_dien_tu.tra_loi_phan_hoi
DROP TABLE IF EXISTS `tra_loi_phan_hoi`;
CREATE TABLE IF NOT EXISTS `tra_loi_phan_hoi` (
  `MaTraLoi` bigint unsigned NOT NULL AUTO_INCREMENT,
  `MaPhanHoi` bigint unsigned NOT NULL,
  `MaAdmin` bigint unsigned NOT NULL,
  `NoiDung` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `NgayTraLoi` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaTraLoi`),
  KEY `tra_loi_phan_hoi_maphanhoi_foreign` (`MaPhanHoi`),
  KEY `tra_loi_phan_hoi_maadmin_foreign` (`MaAdmin`),
  CONSTRAINT `tra_loi_phan_hoi_maadmin_foreign` FOREIGN KEY (`MaAdmin`) REFERENCES `nguoi_dung` (`MaNguoiDung`) ON DELETE CASCADE,
  CONSTRAINT `tra_loi_phan_hoi_maphanhoi_foreign` FOREIGN KEY (`MaPhanHoi`) REFERENCES `phan_hoi` (`MaPhanHoi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table phu_kien_dien_tu.tra_loi_phan_hoi: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
