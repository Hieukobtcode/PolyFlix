<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    #modalSoDoGhe ::placeholder {
        font-family: inherit;
        font-size: inherit;
        font-weight: inherit;
        color: #6c757d;
    }
</style>
<div class="modal fade" id="modalSoDoGhe" tabindex="-1" aria-labelledby="modalSoDoGheLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg rounded-3">
            <form id="formSoDoGhe" method="POST" action="{{ route('admin.so-do-ghe.store') }}">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalSoDoGheLabel">
                        Tạo sơ đồ ghế cho phòng chiếu: <span id="tenPhong" class="fw-bold"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Đóng"></button>
                </div>

                <div class="modal-body p-4">
                    <input type="hidden" id="phong_id" name="phong_id">
                    <input type="hidden" name="ma_tran_ghe" id="ma_tran_ghe">

                    <!-- Mẫu sơ đồ -->
                    <div class="mb-4">
                        <label for="mau_so_do" class="form-label">Mẫu sơ đồ</label>
                        <select id="mau_so_do" name="mau_so_do"
                            class="form-select form-select-lg shadow-sm @error('mau_so_do') is-invalid @enderror">
                            <option value="">-- Chọn mẫu --</option>
                            <option value="8x12">8x12 (tối đa 96 ghế)</option>
                            <option value="10x12">10x12 (tối đa 120 ghế)</option>
                            <option value="12x14">12x14 (tối đa 168 ghế)</option>
                            <option value="14x16">14x16 (tối đa 224 ghế)</option>
                            <option value="18x20">18x20 (tối đa 360 ghế)</option>
                        </select>
                        @error('mau_so_do')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hàng và Cột -->
                    <div class="mb-4 row">
                        <div class="col-md-6">
                            <label class="form-label">Số hàng</label>
                            <input name="so_hang" type="number" id="so_hang"
                                class="form-control form-control-lg shadow-sm @error('so_hang') is-invalid @enderror"
                                readonly placeholder="Số hàng">
                            @error('so_hang')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số cột</label>
                            <input name="so_cot" type="number" id="so_cot"
                                class="form-control form-control-lg shadow-sm @error('so_cot') is-invalid @enderror"
                                readonly placeholder="Số cột">
                            @error('so_cot')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="loai_ghe_ids">Loại ghế</label>
                        <select id="loai_ghe_ids" name="loai_ghe_ids[]"
                            class="select2 form-control custom-select @error('loai_ghe_ids') is-invalid @enderror"
                            multiple>
                            @foreach ($loaiGhes as $loaiGhe)
                                <option value="{{ $loaiGhe->id }}"
                                    {{ in_array($loaiGhe->id, old('loai_ghe_ids', [])) ? 'selected' : '' }}>
                                    {{ $loaiGhe->ten_loai_ghe }}
                                </option>
                            @endforeach
                        </select>

                        @error('loai_ghe_ids')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Input số lượng cho từng loại ghế -->
                    <div class="mb-4">
                        <div class="d-flex gap-3 flex-nowrap overflow-auto" id="input_container"></div>

                        @foreach ($loaiGhes as $loaiGhe)
                            <div class="text-danger mt-1" id="error_loai_ghe_{{ $loaiGhe->id }}">
                                @error('so_hang_' . $loaiGhe->id)
                                    {{ $message }}
                                @enderror
                            </div>
                        @endforeach

                        @error('tong_ghe')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="modal-footer justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success btn-lg shadow-sm">Lưu sơ đồ ghế</button>
                </div>
            </form>
        </div>
    </div>
</div>
