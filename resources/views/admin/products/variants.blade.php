@extends('admin.layouts.app')
@section('title', 'Quản lý biến thể')
@section('page-title', 'Biến thể sản phẩm: ' . $sanPham->TenSanPham)

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
            <h5><i class="bi bi-palette"></i> Danh sách biến thể</h5>
            <div>
                <a href="{{ route('admin.products.variants.create', $sanPham->MaSanPham) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Thêm biến thể
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
        
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ảnh</th>
                    <th>Màu sắc</th>
                    <th>Dung lượng/Kích thước</th>
                    <th>Đơn giá</th>
                    <th>Tồn kho</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sanPham->bienThe as $bt)
                <tr>
                    <td>#{{ $bt->MaCTSanPham }}</td>
                    <td>
                        <img src="{{ $bt->AnhMinhHoa }}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                    </td>
                    <td><strong>{{ $bt->MauSac }}</strong></td>
                    <td>{{ $bt->DungLuong ?? $bt->KichThuoc ?? '-' }}</td>
                    <td><strong class="text-primary">{{ number_format($bt->DonGia, 0, ',', '.') }}₫</strong></td>
                    <td>
                        <span class="badge bg-{{ $bt->SoLuongTon > 0 ? 'success' : 'danger' }}">
                            {{ $bt->SoLuongTon }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $bt->TrangThai ? 'success' : 'secondary' }}">
                            {{ $bt->TrangThai ? 'Hiển thị' : 'Ẩn' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.products.variants.edit', [$sanPham->MaSanPham, $bt->MaCTSanPham]) }}" 
                           class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.products.variants.destroy', [$sanPham->MaSanPham, $bt->MaCTSanPham]) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" 
                                    onclick="return confirm('Xóa biến thể?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection