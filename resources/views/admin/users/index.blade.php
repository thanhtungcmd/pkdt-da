@extends('admin.layouts.app')

@section('title', 'Quản lý người dùng')
@section('page-title', 'Người dùng')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5><i class="bi bi-people-fill"></i> Danh sách người dùng</h5>
            
            <!-- THÊM NÚT TẠO NGƯỜI DÙNG MỚI -->
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Thêm người dùng
            </a>
        </div>
        
        <!-- Form tìm kiếm và lọc -->
        <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2 mb-3">
            <select name="vai_tro" class="form-select" style="max-width: 200px;" onchange="this.form.submit()">
                <option value="">Tất cả vai trò</option>
                <option value="0" {{ request('vai_tro') === '0' ? 'selected' : '' }}>Khách hàng</option>
                <option value="1" {{ request('vai_tro') === '1' ? 'selected' : '' }}>Admin</option>
            </select>
            
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." 
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
            </button>
        </form>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số ĐT</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nguoiDung as $nd)
                    <tr>
                        <td><strong>#{{ $nd->MaNguoiDung }}</strong></td>
                        <td>{{ $nd->TenDangNhap }}</td>
                        <td>{{ $nd->HoTen }}</td>
                        <td>{{ $nd->Email }}</td>
                        <td>{{ $nd->SoDienThoai }}</td>
                        <td>
                            <span class="badge bg-{{ $nd->VaiTro ? 'danger' : 'primary' }}">
                                {{ $nd->VaiTro ? 'Admin' : 'Khách hàng' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $nd->TrangThai ? 'success' : 'secondary' }}">
                                {{ $nd->TrangThai ? 'Hoạt động' : 'Khóa' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.users.edit', $nd->MaNguoiDung) }}" 
                               class="btn btn-sm btn-warning" title="Sửa">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            
                            <form action="{{ route('admin.users.toggle', $nd->MaNguoiDung) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-{{ $nd->TrangThai ? 'secondary' : 'success' }}" 
                                        title="{{ $nd->TrangThai ? 'Khóa' : 'Mở khóa' }}">
                                    <i class="bi bi-{{ $nd->TrangThai ? 'lock' : 'unlock' }}"></i>
                                </button>
                            </form>
                            
                            @if($nd->VaiTro == 0)
                            <form action="{{ route('admin.users.destroy', $nd->MaNguoiDung) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Xóa người dùng này?')"
                                        title="Xóa">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-3">
            {{ $nguoiDung->links() }}
        </div>
    </div>
</div>
@endsection