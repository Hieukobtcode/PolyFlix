@extends('layouts.admin')
@section('content')
    <div class="row g-3">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <strong>Rạp thuộc: {{ $chiNhanh->ten_chi_nhanh }}</strong>
                </div>
                <div class="card-body p-3">
                    <div class="row mb-4 g-2 align-items-center">

                        <!-- Tên Rạp -->
                        <div class="col-md-3">
                            <input type="text" id="searchTenRap" class="form-control" placeholder="Tìm theo tên rạp...">
                        </div>

                    </div>
                    @if ($chiNhanh->rapPhims->isEmpty())
                        <p class="text-muted mb-0"><i class="fas fa-info-circle me-1"></i> Không có rạp nào thuộc chi
                            nhánh này.</p>
                    @else
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <a href="{{ route('admin.rap-phim.create', ['chiNhanhId' => $chiNhanh->id]) }}"
                                    class="btn btn-sm btn-primary d-inline-flex align-items-center gap-2 py-2 px-3">
                                    <i class="ti ti-plus"></i> Thêm rạp chiếu
                                </a>

                            </div>
                            <table class="table text-nowrap align-middle mb-0">
                                <thead class="bg-gradient-dark text-white text-center small">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">Tên Rạp</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">Địa Chỉ</h6>
                                        </th>
                                        <th style="width: 15%">
                                            <h6 class="fs-4 fw-semibold mb-0">Trạng Thái</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">Quản Lý</h6>
                                        </th>
                                        <th style="width: 20%">
                                            <h6 class="fs-4 fw-semibold mb-0">Thao Tác</h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="rapTable">
                                    @foreach ($chiNhanh->rapPhims as $index => $rap)
                                        @php
                                            $statusColors = [
                                                'đang hoạt động' => 'bg-success-subtle text-success',
                                                'bảo trì' => 'bg-warning-subtle text-warning',
                                                'đã đóng' => 'bg-secondary-subtle text-muted',
                                            ];
                                            $statusLabels = [
                                                'đang hoạt động' => 'Hoạt động',
                                                'bảo trì' => 'Tạm dừng',
                                                'đã đóng' => 'Đóng cửa',
                                            ];
                                            $statusClass =
                                                $statusColors[$rap->trang_thai] ?? 'bg-secondary-subtle text-muted';
                                            $statusText = $statusLabels[$rap->trang_thai] ?? 'Không rõ';
                                        @endphp
                                        @php $tenRapLower = strtolower(strip_tags($rap->ten_rap)); @endphp
                                        <tr class="data-row" data-ten-rap="{{ $tenRapLower }}">
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $rap->ten_rap }}</td>
                                            <td>{{ $rap->dia_chi }}</td>
                                            <td class="text-center">
                                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if ($rap->quan_ly_id)
                                                    @if (Auth::user()->vai_tro_id == 1)
                                                        <a href="{{ route('admin.users.show', $rap->quan_ly_id) }}"
                                                            class="text-decoration-none fw-medium">
                                                            {{ $rap->quanLy->name ?? 'ID: ' . $rap->quan_ly_id }}
                                                        </a>
                                                    @elseif(Auth::user()->vai_tro_id == 2 && $rap->chiNhanh->quan_ly_id == Auth::id())
                                                        <span class="text-decoration-none fw-medium">
                                                            {{ $rap->quanLy->name ?? 'ID: ' . $rap->quan_ly_id }}
                                                        </span>
                                                    @endif
                                                @elseif (array_key_exists($rap->id, $pendingRapEmails))
                                                    <button type="button"
                                                        class="badge bg-warning-subtle text-warning border-0"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#cancelInviteModalRap{{ $rap->id }}">
                                                        Đang phân công
                                                    </button>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-muted">Chưa phân
                                                        công</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown dropstart">
                                                    <a href="javascript:void(0)" class="text-muted"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical fs-6"></i>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center gap-2"
                                                                href="{{ route('admin.rap-phim.show', $rap->id) }}">
                                                                <i class="ti ti-eye fs-5"></i> Xem rạp
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center gap-2"
                                                                href="{{ route('admin.rap-phim.edit', $rap->id) }}">
                                                                <i class="ti ti-edit fs-5"></i> Chỉnh sửa
                                                            </a>
                                                        </li>
                                                        @if (Auth::user()->vai_tro_id !== 2)
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center gap-2"
                                                                    href="{{ route('admin.phong-chieu.create', ['rap_phim_id' => $rap->id]) }}">
                                                                    <i class="ti ti-plus fs-5"></i> Thêm phòng
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if (!$rap->quan_ly_id && !in_array($rap->id, $pendingRapInvites))
                                                            <li>
                                                                <button
                                                                    class="dropdown-item d-flex align-items-center gap-2"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#inviteModal{{ $rap->id }}">
                                                                    <i class="ti ti-user-plus fs-5 text-warning"></i>
                                                                    Phân công quản lý
                                                                </button>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>


                            @foreach ($chiNhanh->rapPhims as $rap)
                                @if (!$rap->quan_ly_id && !in_array($rap->id, $pendingRapInvites))
                                    <div class="modal fade" id="inviteModal{{ $rap->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-md">
                                            <form method="POST" action="{{ route('invite.send') }}">
                                                @csrf
                                                <input type="hidden" name="loai_quan_ly" value="2">
                                                <input type="hidden" name="rap_phim_id" value="{{ $rap->id }}">
                                                <div class="modal-content">
                                                    <div class="modal-header modal-colored-header bg-success text-white">
                                                        <h4 class="modal-title text-white">Phân công quản lý rạp phim
                                                        </h4>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Đóng"></button>
                                                    </div>
                                                    <div class="modal-body py-4 px-4">
                                                        <label class="fw-bold mb-2">Email người quản lý</label>
                                                        <input type="email" name="email" class="form-control" required>
                                                    </div>
                                                    <div class="modal-footer justify-content-between px-3 pb-3">
                                                        <button type="button"
                                                            class="btn btn-light btn-sm rounded-pill px-4"
                                                            data-bs-dismiss="modal">
                                                            Hủy
                                                        </button>
                                                        <button type="submit"
                                                            class="btn bg-success-subtle text-success btn-sm rounded-pill px-4">
                                                            <i class="ti ti-send"></i> Gửi lời mời
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            {{-- Modal hủy lời mời Rạp --}}
                            @foreach ($chiNhanh->rapPhims as $rap)
                                @if (array_key_exists($rap->id, $pendingRapEmails))
                                    <div class="modal fade" id="cancelInviteModalRap{{ $rap->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-md">
                                            <div class="modal-content">
                                                <div
                                                    class="modal-header modal-colored-header bg-warning text-white py-2 px-3">
                                                    <h5 class="modal-title text-white mb-0">Lời mời đang chờ</h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Đóng"></button>
                                                </div>
                                                <div class="modal-body text-center py-3 px-3">
                                                    <p class="mb-3"><strong>Email:</strong><br>
                                                        <span
                                                            class="text-muted">{{ $pendingRapEmails[$rap->id] ?? 'Không rõ' }}</span>
                                                    </p>
                                                    <p class="text-muted mb-0">Bạn có chắc chắn muốn hủy lời mời này?</p>
                                                </div>
                                                <div class="modal-footer justify-content-between px-3 pb-3">
                                                    <button type="button" class="btn btn-light btn-sm rounded-pill px-4"
                                                        data-bs-dismiss="modal">Đóng</button>
                                                    <form action="{{ route('admin.invite.cancel') }}" method="POST"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn hủy lời mời gửi đến {{ $pendingRapEmails[$rap->id] ?? '' }}?')">
                                                        @csrf
                                                        <input type="hidden" name="rap_phim_id"
                                                            value="{{ $rap->id }}">
                                                        <input type="hidden" name="loai_quan_ly" value="2">
                                                        <button type="submit"
                                                            class="btn bg-warning-subtle text-warning btn-sm rounded-pill px-4">
                                                            <i class="ti ti-trash"></i> Hủy lời mời
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Tìm kiếm theo tên rạp
            const searchInput = document.getElementById('searchTenRap');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const keyword = this.value.toLowerCase().trim();
                    let matchCount = 0;

                    document.querySelectorAll('#rapTable tr.data-row').forEach(row => {
                        const name = (row.dataset.tenRap || '').toLowerCase();

                        if (name.includes(keyword)) {
                            row.style.display = '';
                            matchCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Xử lý khi không có kết quả
                    const noResult = document.querySelector('#rapTable .no-result');
                    if (matchCount === 0) {
                        if (!noResult) {
                            const row = document.createElement('tr');
                            row.classList.add('no-result');
                            row.innerHTML = `
                                <td colspan="6" class="text-center text-muted py-3">
                                    <i class="fas fa-folder-open me-1"></i> Không tìm thấy kết quả phù hợp
                                </td>
                            `;
                            document.querySelector('#rapTable').appendChild(row);
                        }
                    } else {
                        if (noResult) noResult.remove();
                    }
                });
            }
        });

        function toggleCancelBtn(id) {
            const form = document.getElementById('cancel-form-' + id);
            if (form) {
                form.style.display = (form.style.display === 'none') ? 'inline-block' : 'none';
            }
        }

        function confirmCancelInvite(form, email) {
            return confirm(`Bạn có chắc chắn muốn hủy lời mời đã gửi đến ${email}?`);
        }
    </script>
@endsection
