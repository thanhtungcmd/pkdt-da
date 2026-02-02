@extends('layouts.app')

@section('title', 'Thông báo')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-bell-fill"></i> Tất cả thông báo
                    </h5>
                    <div>
                        <form action="{{ route('notifications.read.all') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light">
                                <i class="bi bi-check-all"></i> Đánh dấu tất cả đã đọc
                            </button>
                        </form>
                        <form action="{{ route('notifications.clear.read') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-light" 
                                    onclick="return confirm('Xóa tất cả thông báo đã đọc?')">
                                <i class="bi bi-trash"></i> Xóa đã đọc
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @if($thongBaos->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-bell-slash" style="font-size: 64px; color: #ccc;"></i>
                            <p class="text-muted mt-3">Chưa có thông báo nào</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($thongBaos as $notif)
                                <a href="{{ route('notifications.read', $notif->MaThongBao) }}" 
                                   class="list-group-item list-group-item-action {{ !$notif->DaDoc ? 'list-group-item-primary' : '' }}">
                                    <div class="d-flex align-items-start">
                                        <i class="bi {{ explode(' ', $notif->icon())[0] }} me-3" 
                                           style="font-size: 28px;" 
                                           class="{{ explode(' ', $notif->icon())[1] }}"></i>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $notif->TieuDe }}</h6>
                                                    <p class="mb-1 text-muted small">{{ $notif->NoiDung }}</p>
                                                    <small class="text-muted">
                                                        <i class="bi bi-clock"></i> {{ $notif->thoiGianHienThi() }}
                                                    </small>
                                                </div>
                                                <div>
                                                    @if(!$notif->DaDoc)
                                                        <span class="badge bg-primary">Mới</span>
                                                    @endif
                                                    <form action="{{ route('notifications.destroy', $notif->MaThongBao) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onclick="event.stopPropagation();">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-link text-danger p-0 ms-2"
                                                                onclick="return confirm('Xóa thông báo này?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $thongBaos->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .list-group-item-primary {
        background-color: #e7f3ff !important;
        border-left: 4px solid #0066FF;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection