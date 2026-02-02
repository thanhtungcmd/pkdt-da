@extends('admin.layouts.app')

@section('title', 'Quản lý danh mục')
@section('page-title', 'Danh mục sản phẩm')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5><i class="bi bi-grid-fill"></i> Danh sách danh mục</h5>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm danh mục
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Mô tả</th>
                        <th>Số sản phẩm</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($danhMuc as $dm)
                    <tr>
                        <td><strong>#{{ $dm->MaDanhMuc }}</strong></td>
                        <td>{{ $dm->TenDanhMuc }}</td>
                        <td>{{ $dm->MoTa }}</td>
                        <td>
                            <span class="badge bg-info">{{ $dm->san_pham_count }} sản phẩm</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $dm->TrangThai ? 'success' : 'secondary' }}">
                                {{ $dm->TrangThai ? 'Hiển thị' : 'Ẩn' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.categories.edit', $dm->MaDanhMuc) }}" 
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $dm->MaDanhMuc) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Xóa danh mục này?')">
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
            {{ $danhMuc->links() }}
        </div>
    </div>
</div>
@endsection