@extends('admin.layouts.app')

@section('title', 'Quản lý sản phẩm')
@section('page-title', 'Sản phẩm')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5><i class="bi bi-box-seam"></i> Danh sách sản phẩm</h5>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm sản phẩm
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Biến thể</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sanPham as $sp)
                    <tr>
                        <td><strong>#{{ $sp->MaSanPham }}</strong></td>
                        <td>
                            <img src="{{ $sp->AnhChinh }}" alt="" 
                                 style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                        </td>
                        <td>{{ $sp->TenSanPham }}</td>
                        <td>
                            <span class="badge bg-info">{{ $sp->danhMuc->TenDanhMuc }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.products.variants', $sp->MaSanPham) }}" 
                               class="badge bg-primary">
                                {{ $sp->bienThe->count() }} biến thể
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-{{ $sp->TrangThai ? 'success' : 'secondary' }}">
                                {{ $sp->TrangThai ? 'Hiển thị' : 'Ẩn' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.products.variants', $sp->MaSanPham) }}" 
                               class="btn btn-sm btn-info" title="Quản lý biến thể">
                                <i class="bi bi-palette"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $sp->MaSanPham) }}" 
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $sp->MaSanPham) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Xóa sản phẩm này?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-3">
            {{ $sanPham->links() }}
        </div>
    </div>
</div>
@endsection