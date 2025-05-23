@extends('layouts.admin')

@section('title', 'Sơ đồ ghế')
@section('page-title', 'Sơ đồ ghế')
@section('breadcrumb', 'Sơ đồ ghế')

@section('content')
@php
    $cauTrucGhes = $soDoGhe->cau_truc_ghe;
    $rows = [];
    foreach ($cauTrucGhes as $seat => $type) {
        $row = substr($seat, 0, 1);
        $col = substr($seat, 1);
        $rows[$row][$col] = $type;
    }
    ksort($rows);
    foreach ($rows as &$cols) {
        ksort($cols);
    }
    unset($cols);
@endphp

<style>
    .layout {
        display: flex;
        gap: 40px;
        align-items: flex-start;
        margin-top: 32px;
    }

    .seat-map-grid {
        display: grid;
        grid-template-columns: repeat({{ count(reset($rows)) + 2 }}, 48px);
        gap: 12px;
        background: #f8fafc;
        padding: 32px 24px;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(44, 62, 80, 0.08);
    }

    .seat-label,
    .seat-row-label {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #34495e;
        font-size: 18px;
        letter-spacing: 1px;
    }

    .seat {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        font-size: 15px;
        box-shadow: 0 2px 8px rgba(44, 62, 80, 0.08);
        transition: transform 0.1s;
        cursor: pointer;
        border: 2px solid #fff;
    }

    .seat:hover {
        transform: scale(1.08);
        border-color: #6366f1;
    }

    .seat.vip {
        background: linear-gradient(135deg, #e74c3c 60%, #f39c12 100%);
        color: #fff;
    }

    .seat.thuong {
        background: linear-gradient(135deg, #2ecc71 60%, #27ae60 100%);
        color: #fff;
    }

    .seat.doi {
        background: linear-gradient(135deg, #95a5a6 60%, #7f8c8d 100%);
        color: #fff;
    }

    .seat.default {
        background: #bdc3c7;
        color: #fff;
    }

    .seat-action-btns {
        display: flex;
        gap: 6px;
        align-items: center;
        justify-content: center;
    }

    .seat-action-btn {
        width: 28px;
        height: 28px;
        border: none;
        border-radius: 6px;
        color: #fff;
        font-size: 18px;
        cursor: pointer;
        transition: background 0.15s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .seat-action-btn.add {
        background: #22c55e;
    }

    .seat-action-btn.add:hover {
        background: #16a34a;
    }

    .seat-action-btn.delete {
        background: #ef4444;
    }

    .seat-action-btn.delete:hover {
        background: #b91c1c;
    }

    .right-panel {
        width: 260px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .panel-box {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        padding: 20px;
    }

    .panel-box h4 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 12px;
        color: #1f2937;
    }

    .panel-box p {
        font-size: 14px;
        margin: 4px 0;
        color: #4b5563;
    }

    .legend-item {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
        font-size: 14px;
        color: #374151;
    }

    .legend-color {
        width: 24px;
        height: 24px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    .legend-thuong {
        background-color: #fef3c7;
    }

    .legend-vip {
        background-color: #f3f4f6;
    }

    .legend-doi {
        background-color: #fce7f3;
    }

    .btn-group {
        display: flex;
        gap: 12px;
        margin-top: 12px;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }

    .btn-save {
        background-color: #e5e7eb;
        color: #111827;
    }

    .btn-publish {
        background-color: #1e3a8a;
        color: #fff;
    }
</style>

<div class="layout">
    <div class="seat-map-grid">
        <div></div>
        @foreach (array_keys(reset($rows)) as $col)
            <div class="seat-label">{{ $col }}</div>
        @endforeach
        <div></div>

        @foreach ($rows as $rowKey => $cols)
            <div class="seat-row-label">{{ $rowKey }}</div>
            @foreach ($cols as $colKey => $type)
                @php
                    $class = match ($type) {
                        'vip' => 'vip',
                        'thuong' => 'thuong',
                        'doi' => 'doi',
                        default => 'default',
                    };
                @endphp
                <button><input class="seat {{ $class }}" type="text"></button>
                {{-- <div class="seat {{ $class }}" title="{{ $rowKey . $colKey }}">+</div> --}}
            @endforeach
            <div class="seat-action-btns">
                <button type="button" title="Thêm hàng {{ $rowKey }}" class="seat-action-btn add">+</button>
                <button type="button" title="Xoá hàng {{ $rowKey }}" class="seat-action-btn delete">🗑</button>
            </div>
        @endforeach
    </div>

    <!-- CỘT PHẢI - PANEL -->
    <div class="right-panel">
        <!-- Cập nhật -->
        <div class="panel-box">
            <h4>Cập nhật</h4>
            <p><strong>Hoạt động:</strong> Chưa hoạt động</p>
            <div class="btn-group">
                <button class="btn btn-publish">Cập nhật</button>
            </div>
        </div>

        <!-- Chú thích -->
        <div class="panel-box">
            <h4>Chú thích</h4>
            <div class="legend-item">
                <span>Hàng ghế thường</span>
                <div class="legend-color legend-thuong"></div>
            </div>
            <div class="legend-item">
                <span>Hàng ghế vip</span>
                <div class="legend-color legend-vip"></div>
            </div>
            <div class="legend-item">
                <span>Hàng ghế đôi</span>
                <div class="legend-color legend-doi"></div>
            </div>
        </div>
    </div>
</div>
@endsection
