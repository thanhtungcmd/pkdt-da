@extends('admin.layouts.app')

@section('title', 'Quản lý phản hồi')
@section('page-title', 'Phản hồi khách hàng')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
            <h5><i class="bi bi-envelope-fill"></i> Danh sách phản hồi</h5>
            
            <form action="{{ route('admin.feedback.index') }}" method="GET">
                <select name="trang_thai" class="form-select" onchange="this.form.submit()">
                    <option value="">Tất cả trạng thái</option>
                    <option value="0" {{ request('trang_thai') === '0' ? 'selected' : '' }}>Chưa xem</option>
                    <option value="1" {{ request('trang_thai') === '1' ? 'selected' : '' }}>Đã xem</option>
                </select>
            </form>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người gửi</th>
                        <th>Tiêu đề</th>
                        <th>Nội dung</th>
                        <th>Ngày gửi</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($phanHoi as $ph)
                    <tr class="{{ $ph->TrangThai == 0 ? 'table-warning' : '' }}">
                        <td><strong>#{{ $ph->MaPhanHoi }}</strong></td>
                        <td>
                            <strong>{{ $ph->nguoiDung->HoTen }}</strong><br>
                            <small class="text-muted">{{ $ph->nguoiDung->Email }}</small>
                        </td>
                        <td>{{ $ph->TieuDe }}</td>
                        <td>{{ Str::limit($ph->NoiDung, 50) }}</td>
                        <td>{{ $ph->NgayGui->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge bg-{{ $ph->TrangThai ? 'success' : 'warning' }}">
                                {{ $ph->TrangThai ? 'Đã xem' : 'Chưa xem' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.feedback.show', $ph->MaPhanHoi) }}" 
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> Xem
                            </a>
                            <form action="{{ route('admin.feedback.destroy', $ph->MaPhanHoi) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Xóa phản hồi này?')">
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
            {{ $phanHoi->links() }}
        </div>
    </div>
</div>
@endsection