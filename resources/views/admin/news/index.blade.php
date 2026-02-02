@extends('admin.layouts.app')

@section('title', 'Quản lý tin tức')
@section('page-title', 'Tin tức')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5><i class="bi bi-newspaper"></i> Danh sách tin tức</h5>
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm tin tức
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Tác giả</th>
                        <th>Ngày đăng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tinTuc as $tt)
                    <tr>
                        <td><strong>#{{ $tt->MaTinTuc }}</strong></td>
                        <td>
                            <img src="{{ $tt->AnhMinhHoa }}" alt="" 
                                 style="width: 80px; height: 60px; object-fit: cover;" class="rounded">
                        </td>
                        <td>
                            <strong>{{ $tt->TieuDe }}</strong><br>
                            <small class="text-muted">{{ $tt->tomTat(80) }}</small>
                        </td>
                        <td>{{ $tt->nguoiDung->HoTen }}</td>
                        <td>{{ $tt->NgayDang->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.news.edit', $tt->MaTinTuc) }}" 
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.news.destroy', $tt->MaTinTuc) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Xóa tin tức này?')">
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
            {{ $tinTuc->links() }}
        </div>
    </div>
</div>
@endsection