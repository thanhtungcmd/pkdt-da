@extends('admin.layouts.app')

@section('title', 'Quản lý bình luận')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="bi bi-chat-dots"></i> Quản lý bình luận
            @if($chuaXem > 0)
                <span class="badge bg-danger">{{ $chuaXem }} mới</span>
            @endif
        </h2>
        
        @if($chuaXem > 0)
        <form action="{{ route('admin.comments.markAllAsRead') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-all"></i> Đánh dấu tất cả đã xem
            </button>
        </form>
        @endif
    </div>
    
    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.comments.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Trạng thái duyệt</label>
                    <select name="trang_thai" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="cho_duyet" {{ request('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>
                            Chờ duyệt
                        </option>
                        <option value="da_duyet" {{ request('trang_thai') == 'da_duyet' ? 'selected' : '' }}>
                            Đã duyệt
                        </option>
                        <option value="bi_an" {{ request('trang_thai') == 'bi_an' ? 'selected' : '' }}>
                            Bị ẩn
                        </option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Trạng thái xem</label>
                    <select name="da_xem" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="0" {{ request('da_xem') == '0' ? 'selected' : '' }}>
                            Chưa xem
                        </option>
                        <option value="1" {{ request('da_xem') == '1' ? 'selected' : '' }}>
                            Đã xem
                        </option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Tìm theo nội dung..." 
                           value="{{ request('search') }}">
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Lọc
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Danh sách bình luận -->
    <div class="card">
        <div class="card-body">
            @if($binhLuans->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="15%">Người dùng</th>
                            <th width="20%">Sản phẩm</th>
                            <th width="30%">Nội dung</th>
                            <th width="10%">Ngày gửi</th>
                            <th width="10%">Trạng thái</th>
                            <th width="10%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($binhLuans as $bl)
                        <tr class="{{ !$bl->DaXem ? 'table-warning' : '' }}">
                            <td>
                                {{ $bl->MaBinhLuan }}
                                @if(!$bl->DaXem)
                                    <span class="badge bg-danger">Mới</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $bl->nguoiDung->HoTen }}</strong><br>
                                <small class="text-muted">{{ $bl->nguoiDung->Email }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.comments.viewProduct', $bl->MaBinhLuan) }}" target="_blank">
                                    {{ Str::limit($bl->sanPham->TenSanPham, 40) }}
                                </a>
                                @if($bl->MaBinhLuanCha)
                                    <br><small class="text-info">
                                        <i class="bi bi-arrow-return-right"></i> Trả lời BL #{{ $bl->MaBinhLuanCha }}
                                    </small>
                                @endif
                            </td>
                            <td>{{ Str::limit($bl->NoiDung, 60) }}</td>
                            <td>
                                <small>{{ $bl->NgayBinhLuan->format('d/m/Y H:i') }}</small><br>
                                <small class="text-muted">{{ $bl->NgayBinhLuan->diffForHumans() }}</small>
                            </td>
                            <td>
                                <form action="{{ route('admin.comments.updateStatus', $bl->MaBinhLuan) }}" 
                                      method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="TrangThai" class="form-select form-select-sm" 
                                            onchange="this.form.submit()">
                                        <option value="cho_duyet" {{ $bl->TrangThai == 'cho_duyet' ? 'selected' : '' }}>
                                            Chờ duyệt
                                        </option>
                                        <option value="da_duyet" {{ $bl->TrangThai == 'da_duyet' ? 'selected' : '' }}>
                                            Đã duyệt
                                        </option>
                                        <option value="bi_an" {{ $bl->TrangThai == 'bi_an' ? 'selected' : '' }}>
                                            Bị ẩn
                                        </option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                @if(!$bl->DaXem)
                                <form action="{{ route('admin.comments.markAsRead', $bl->MaBinhLuan) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" 
                                            title="Đánh dấu đã xem">
                                        <i class="bi bi-check"></i>
                                    </button>
                                </form>
                                @endif
                                
                                <form action="{{ route('admin.comments.destroy', $bl->MaBinhLuan) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Xóa bình luận này?')"
                                            title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $binhLuans->links() }}
            </div>
            @else
            <p class="text-center text-muted">Chưa có bình luận nào</p>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.table-warning {
    background-color: #fff3cd !important;
}
</style>
@endsection