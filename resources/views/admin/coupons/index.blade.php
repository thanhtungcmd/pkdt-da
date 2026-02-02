@extends('admin.layouts.app')

@section('page-title', 'Quản lý mã giảm giá')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h2><i class="bi bi-tags-fill"></i> Quản lý mã giảm giá</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tạo mã mới
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Mã</th>
                            <th>Loại</th>
                            <th>Giá trị</th>
                            <th>Đơn tối thiểu</th>
                            <th>Đã dùng/Giới hạn</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($maGiamGia as $item)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $item->MaCode }}</strong>
                                @if($item->MoTa)
                                <br><small class="text-muted">{{ Str::limit($item->MoTa, 40) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($item->LoaiGiam === 'fixed')
                                    <span class="badge bg-info">Cố định</span>
                                @else
                                    <span class="badge bg-warning text-dark">Phần trăm</span>
                                @endif
                            </td>
                            <td>
                                @if($item->LoaiGiam === 'fixed')
                                    <strong>{{ number_format($item->GiaTri, 0, ',', '.') }}đ</strong>
                                @else
                                    <strong>{{ $item->GiaTri }}%</strong>
                                    @if($item->GiamToiDa)
                                        <br><small class="text-muted">Max: {{ number_format($item->GiamToiDa, 0, ',', '.') }}đ</small>
                                    @endif
                                @endif
                            </td>
                            <td>
                                {{ $item->DonToiThieu ? number_format($item->DonToiThieu, 0, ',', '.') . 'đ' : '-' }}
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $item->DaSuDung }}/{{ $item->GioiHanSuDung ?? '∞' }}
                                </span>
                                @if($item->GioiHanMoiNguoi)
                                    <br><small class="text-muted">({{ $item->GioiHanMoiNguoi }}x/người)</small>
                                @endif
                            </td>
                            <td>
                                @if($item->NgayBatDau || $item->NgayKetThuc)
                                    @if($item->NgayBatDau)
                                        <small><i class="bi bi-calendar-check"></i> {{ $item->NgayBatDau->format('d/m/Y') }}</small><br>
                                    @endif
                                    @if($item->NgayKetThuc)
                                        <small><i class="bi bi-calendar-x"></i> {{ $item->NgayKetThuc->format('d/m/Y') }}</small>
                                    @endif
                                @else
                                    <small class="text-muted">Không giới hạn</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $validation = $item->kiemTraHopLe();
                                @endphp
                                @if($validation['valid'])
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Hoạt động
                                    </span>
                                @else
                                    <span class="badge bg-danger" title="{{ $validation['message'] }}">
                                        <i class="bi bi-x-circle"></i> Không khả dụng
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.coupons.history', $item->MaMaGiamGia) }}" 
                                    class="btn btn-sm btn-outline-info" title="Lịch sử sử dụng">
                                        <i class="bi bi-clock-history"></i>
                                    </a>
                                    <a href="{{ route('admin.coupons.edit', $item->MaMaGiamGia) }}" 
                                    class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.coupons.destroy', $item->MaMaGiamGia) }}" 
                                        method="POST" class="d-inline" 
                                        onsubmit="return confirm('Bạn có chắc muốn xóa mã {{ $item->MaCode }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox" style="font-size: 48px;"></i>
                                <p class="mt-2 mb-0">Chưa có mã giảm giá nào</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($maGiamGia->hasPages())
            <div class="mt-3">
                {{ $maGiamGia->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection