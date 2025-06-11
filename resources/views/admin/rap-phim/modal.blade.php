 <!-- Modal chung để thêm sơ đồ ghế -->
 <div class="modal fade" id="modalSoDoGhe" tabindex="-1" aria-labelledby="modalSoDoGheLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content shadow-lg rounded-3">
             <form id="formSoDoGhe" method="POST" action="{{ route('admin.so-do-ghe.store') }}">
                 @csrf
                 <div class="modal-header bg-primary text-white rounded-top">
                     <h5 class="modal-title" id="modalSoDoGheLabel">Tạo sơ đồ ghế cho phòng chiếu: <span
                             id="tenPhong"></span></h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                 </div>
                 <div class="modal-body p-4">
                     <input type="hidden" id="phong_id" name="phong_id">
                     <input type="hidden" name="ma_tran_ghe" id="ma_tran_ghe">
                     <!-- Chọn mẫu sơ đồ -->
                     <div class="mb-4">
                         <label for="mau_so_do" class="form-label">Mẫu sơ đồ</label>
                         <select id="mau_so_do" name="mau_so_do"
                             class="form-select form-select-lg shadow-sm @error('mau_so_do') is-invalid @enderror">
                             <option value="">-- Chọn mẫu --</option>
                             <option value="8x12">Sơ đồ ghế 8x12 (tối đa 96 chỗ ngồi)</option>
                             <option value="10x12">Sơ đồ ghế 10x12 (tối đa 120 chỗ ngồi)</option>
                             <option value="12x14">Sơ đồ ghế 12x14 (tối đa 168 chỗ ngồi)</option>
                             <option value="14x16">Sơ đồ ghế 14x16 (tối đa 224 chỗ ngồi)</option>
                             <option value="18x20">Sơ đồ ghế 18x20 (tối đa 360 chỗ ngồi)</option>
                         </select>
                         @error('mau_so_do')
                             <div class="text-danger">{{ $message }}</div>
                         @enderror
                     </div>

                     <div class="mb-4 d-flex align-items-center gap-4">
                         <div class="flex-fill">
                             <label class="form-label">Số hàng tối đa</label>
                             <input name="so_hang" type="number" id="so_hang"
                                 class="form-control form-control-lg shadow-sm @error('so_hang') is-invalid @enderror"
                                 min="1" readonly placeholder="Số hàng">
                             @error('so_hang')
                                 <div class="text-danger">{{ $message }}</div>
                             @enderror
                         </div>
                         <div class="flex-fill">
                             <label class="form-label">Số cột tối đa</label>
                             <input name="so_cot" type="number" id="so_cot"
                                 class="form-control form-control-lg shadow-sm @error('so_cot') is-invalid @enderror"
                                 min="1" readonly placeholder="Số cột">
                             @error('so_cot')
                                 <div class="text-danger">{{ $message }}</div>
                             @enderror
                         </div>
                     </div>

                     <div class="mb-4">
                         <label class="form-label" for="loai_ghe_ids">Loại ghế</label>
                         <select id="loai_ghe_ids" name="loai_ghe_ids[]"
                             class="form-select form-select-lg shadow-sm select2 @error('loai_ghe_ids') is-invalid @enderror"
                             multiple>
                             @foreach ($loaiGhes as $loaiGhe)
                                 <option value="{{ $loaiGhe->id }}"
                                     {{ in_array($loaiGhe->id, old('loai_ghe_ids', [])) ? 'selected' : '' }}>
                                     {{ $loaiGhe->ten_loai_ghe }}
                                 </option>
                             @endforeach
                         </select>
                         @error('loai_ghe_ids')
                             <div class="text-danger">{{ $message }}</div>
                         @enderror
                     </div>

                     <!-- Loại ghế -->
                     <div class="mb-4">
                         <div class="d-flex gap-4" id="input_container"></div>
                         @foreach ($loaiGhes as $loaiGhe)
                             <div class="text-danger" id="error_loai_ghe_{{ $loaiGhe->id }}">
                                 @error('so_hang_' . $loaiGhe->id)
                                     {{ $message }}
                                 @enderror
                             </div>
                         @endforeach
                         <!-- Lỗi tổng số ghế -->
                         @error('tong_ghe')
                             <div class="text-danger">{{ $message }}</div>
                         @enderror
                     </div>

                 </div>
                 <div class="modal-footer">
                     <button type="submit" class="btn btn-primary btn-lg shadow-sm">Lưu sơ đồ ghế</button>
                     <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Đóng</button>
                 </div>
             </form>
         </div>
     </div>
 </div>
