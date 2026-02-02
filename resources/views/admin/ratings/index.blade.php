@extends('admin.layouts.app')

@section('title', 'Quản lý đánh giá')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="bi bi-star"></i> Quản lý đánh giá
            @if($chuaXem > 0)
                <span class="badge bg-danger">{{ $chuaXem }} mới</span>
            @endif
        </h2>
        
        @if($chuaXem > 0)
        <form action="{{ route('admin.ratings.markAllAsRead') }}" method="POST">
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
            <form method="GET" action="{{ route('admin.ratings.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Trạng thái</label>
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
                    <label class="form-label">Số sao</label>
                    <select name="so_sao" class="form-select">
                        <option value="">Tất cả</option>
                        @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('so_sao') == $i ? 'selected' : '' }}>
                            {{ $i }} sao
                        </option>
                        @endfor
                    </select>
                </div>
                
                <div class="col-md-2">
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
                
                <div class="col-md-3">
                    <label class="form-label">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Tìm theo nội dung..." 
                           value="{{ request('search') }}">
                </div>
                
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Danh sách đánh giá -->
    <div class="card">
        <div class="card-body">
            @if($danhGias->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="15%">Người dùng</th>
                            <th width="20%">Sản phẩm</th>
                            <th width="10%">Đơn hàng</th>
                            <th width="10%">Số sao</th>
                            <th width="25%">Nội dung</th>
                            <th width="10%">Ngày đánh giá</th>
                            <th width="10%">Trạng thái</th>
                            <th width="5%" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($danhGias as $dg)
                        <tr class="{{ !$dg->DaXem ? 'table-warning' : '' }}">
                            <td>
                                {{ $dg->MaDanhGia }}
                                @if(!$dg->DaXem)
                                    <span class="badge bg-danger">Mới</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $dg->nguoiDung->HoTen }}</strong><br>
                                <small class="text-muted">{{ $dg->nguoiDung->Email }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.ratings.viewProduct', $dg->MaDanhGia) }}" target="_blank">
                                    {{ Str::limit($dg->sanPham->TenSanPham, 40) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $dg->MaDonHang) }}">
                                    #{{ $dg->MaDonHang }}
                                </a>
                            </td>
                            <td>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $dg->SoSao)
                                            <i class="bi bi-star-fill"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <small class="text-muted">{{ $dg->SoSao }}/5</small>
                            </td>
                            <td>
                                @if($dg->NoiDung)
                                    {{ Str::limit($dg->NoiDung, 60) }}
                                @else
                                    <span class="text-muted">Không có nhận xét</span>
                                @endif
                                @if($dg->HinhAnh && count($dg->HinhAnh) > 0)
                                    <br><small class="text-info">
                                        <i class="bi bi-image"></i> {{ count($dg->HinhAnh) }} ảnh
                                    </small>
                                @endif
                                @if($dg->PhanHoiShop)
                                    <br><small class="badge bg-success">
                                        <i class="bi bi-reply"></i> Đã trả lời
                                    </small>
                                @endif
                            </td>
                            <td>
                                <small>{{ $dg->NgayDanhGia->format('d/m/Y H:i') }}</small><br>
                                <small class="text-muted">{{ $dg->NgayDanhGia->diffForHumans() }}</small>
                            </td>
                            <td>
                                <form action="{{ route('admin.ratings.updateStatus', $dg->MaDanhGia) }}" 
                                      method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="TrangThai" class="form-select form-select-sm" 
                                            onchange="this.form.submit()">
                                        <option value="cho_duyet" {{ $dg->TrangThai == 'cho_duyet' ? 'selected' : '' }}>
                                            Chờ duyệt
                                        </option>
                                        <option value="da_duyet" {{ $dg->TrangThai == 'da_duyet' ? 'selected' : '' }}>
                                            Đã duyệt
                                        </option>
                                        <option value="bi_an" {{ $dg->TrangThai == 'bi_an' ? 'selected' : '' }}>
                                            Bị ẩn
                                        </option>
                                    </select>
                                </form>
                            </td>
                            <td class="text-center">
                                <!-- Nút xem chi tiết và trả lời (TỰ ĐỘNG ĐÁNH DẤU ĐÃ XEM) -->
                                <button class="btn btn-sm btn-info mb-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detailModal{{ $dg->MaDanhGia }}"
                                        onclick="markAsReadOnView({{ $dg->MaDanhGia }})"
                                        title="Xem chi tiết & Trả lời">
                                    <i class="bi bi-eye"></i>
                                </button>
                                
                                <!-- Nút xóa -->
                                <form action="{{ route('admin.ratings.destroy', $dg->MaDanhGia) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Xóa đánh giá này?')"
                                            title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        
                        <!-- Modal chi tiết đánh giá -->
                        <div class="modal fade" id="detailModal{{ $dg->MaDanhGia }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Chi tiết đánh giá #{{ $dg->MaDanhGia }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <strong>Người dùng:</strong> {{ $dg->nguoiDung->HoTen }}<br>
                                            <strong>Sản phẩm:</strong> {{ $dg->sanPham->TenSanPham }}<br>
                                            <strong>Số sao:</strong> 
                                            <span class="text-warning">
                                                @for($i = 1; $i <= $dg->SoSao; $i++)
                                                    <i class="bi bi-star-fill"></i>
                                                @endfor
                                            </span>
                                        </div>
                                        
                                        @if($dg->NoiDung)
                                        <div class="mb-3">
                                            <strong>Nội dung:</strong>
                                            <p>{{ $dg->NoiDung }}</p>
                                        </div>
                                        @endif
                                        
                                        @if($dg->HinhAnh && count($dg->HinhAnh) > 0)
                                        <div class="mb-3">
                                            <strong>Hình ảnh:</strong>
                                            <div class="row g-2 mt-2">
                                                @foreach($dg->HinhAnh as $img)
                                                <div class="col-3">
                                                    <img src="{{ asset($img) }}" class="img-fluid rounded">
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($dg->PhanHoiShop)
                                        <div class="p-3 mb-3 border border-success rounded" style="background:#f1fff1">
                                            <strong><i class="bi bi-reply"></i> Trả lời từ Shop:</strong>
                                            <p class="mb-1 mt-2">{{ $dg->PhanHoiShop }}</p>
                                            <small class="text-muted">
                                                Bởi {{ $dg->nguoiPhanHoi->HoTen }} - {{ $dg->NgayPhanHoi->format('d/m/Y H:i') }}
                                            </small>
                                            <form action="{{ route('admin.ratings.deleteReply', $dg->MaDanhGia) }}" 
                                                  method="POST" class="mt-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Xóa trả lời này?')">
                                                    <i class="bi bi-trash"></i> Xóa trả lời
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                        <form action="{{ route('admin.ratings.reply', $dg->MaDanhGia) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label"><strong>Trả lời từ Shop:</strong></label>
                                                <textarea name="PhanHoiShop" class="form-control" rows="3" 
                                                          placeholder="Nhập trả lời của bạn..." required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-send"></i> Gửi trả lời
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $danhGias->links() }}
            </div>
            @else
            <p class="text-center text-muted">Chưa có đánh giá nào</p>
            @endif
        </div>
    </div>
</div>

<style>
/* Style cho cột thao tác */
.table td.text-center .btn {
    min-width: 32px;
}

.table td.text-center form {
    margin: 0;
}

.table-warning {
    background-color: #fff3cd !important;
    border-left: 4px solid #ffc107;
}
</style>

<script>
// Đánh dấu đã xem khi mở modal (AJAX)
function markAsReadOnView(id) {
    fetch(`/admin/ratings/${id}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    }).then(response => {
        if (response.ok) {
            // Xóa highlight warning nếu có
            const row = document.querySelector(`tr[data-rating-id="${id}"]`);
            if (row) {
                row.classList.remove('table-warning');
            }
            
            // Xóa badge "Mới"
            const badge = document.querySelector(`#badge-${id}`);
            if (badge) {
                badge.remove();
            }
            
            // Cập nhật số đếm badge ở header nếu cần
            const headerBadge = document.querySelector('h2 .badge');
            if (headerBadge) {
                let count = parseInt(headerBadge.textContent);
                if (count > 0) {
                    count--;
                    if (count === 0) {
                        headerBadge.remove();
                        // Reload trang để ẩn nút "Đánh dấu tất cả"
                        setTimeout(() => location.reload(), 500);
                    } else {
                        headerBadge.textContent = count + ' mới';
                    }
                }
            }
        }
    }).catch(error => {
        console.error('Error marking as read:', error);
    });
}
</script>
@endsection