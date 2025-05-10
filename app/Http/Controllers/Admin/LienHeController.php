<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LienHe;
use App\Models\LienHeActivityLog;
use App\Models\LienHeNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;


class LienHeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $priority = $request->input('priority');
        $category = $request->input('category');
        $from = $request->input('from');
        $to = $request->input('to');
        $sort = $request->input('sort', 'create_at');
        $direction = $request->input('direction', 'desc');

        $lienHes = LienHe::search($search)
            ->status($status)
            ->priority($priority)
            ->category($category)
            ->dateRange($from, $to)
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        // Lấy danh sách các phân loại và nguồn gốc để hiển thị trong bộ lọc
        $categories = LienHe::distinct()->whereNotNull('phan_loai')->pluck('phan_loai');
        $sources = LienHe::distinct()->whereNotNull('nguon_goc')->pluck('nguon_goc');

        // Thống kê tổng quan
        $stats = [
            'total' => LienHe::count(),
            'pending' => LienHe::where('trang_thai', false)->count(),
            'completed' => LienHe::where('trang_thai', true)->count(),
            'high_priority' => LienHe::where('muc_do_uu_tien', 'cao')->count(),
        ];

        return view('admin.lien-he.index', compact(
            'lienHes',
            'search',
            'status',
            'priority',
            'category',
            'from',
            'to',
            'sort',
            'direction',
            'categories',
            'sources',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Chức năng này không được phép sử dụng vì liên hệ chỉ đến từ khách hàng
        abort(403, 'Không được phép thêm mới liên hệ từ trang quản trị');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        // Chức năng này không được phép sử dụng vì liên hệ chỉ đến từ khách hàng
        abort(403, 'Không được phép thêm mới liên hệ từ trang quản trị');
    }

    /**
     * Display the specified resource.
     */
    public function show(LienHe $lienHe)
    {
        // Lấy ghi chú và lịch sử hoạt động
        $notes = $lienHe->notes()->orderBy('created_at', 'desc')->get();
        $activities = $lienHe->activityLogs()->orderBy('created_at', 'desc')->get();

        return view('admin.lien-he.show', compact('lienHe', 'notes', 'activities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LienHe $lienHe)
    {
        // Chỉ cho phép thay đổi trạng thái, không cho phép chỉnh sửa thông tin liên hệ
        return redirect()->route('admin.lien-he.show', $lienHe)
            ->with('info', 'Chỉ có thể xem thông tin liên hệ và thay đổi trạng thái, không thể chỉnh sửa thông tin liên hệ.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LienHe $lienHe)
    {
        // Chỉ cho phép cập nhật trạng thái
        $validated = $request->validate([
            'trang_thai' => 'required|boolean',
            'ghi_chu_noi_bo' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $oldData = $lienHe->toArray();

            // Chỉ cập nhật trạng thái và ghi chú nội bộ
            $lienHe->update([
                'trang_thai' => $validated['trang_thai'],
                'ghi_chu_noi_bo' => $validated['ghi_chu_noi_bo'] ?? $lienHe->ghi_chu_noi_bo,
            ]);

            // Ghi log hoạt động
            LienHeActivityLog::create([
                'lien_he_id' => $lienHe->id,
                'hanh_dong' => 'update_status',
                'mo_ta' => 'Cập nhật trạng thái liên hệ',
                'nguoi_thuc_hien' => 'Hệ thống',
                'du_lieu_cu' => ['trang_thai' => $oldData['trang_thai']],
                'du_lieu_moi' => ['trang_thai' => $validated['trang_thai']],
            ]);

            // Nếu có ghi chú, tạo ghi chú mới
            if (isset($validated['note']) && !empty($validated['note'])) {
                LienHeNote::create([
                    'lien_he_id' => $lienHe->id,
                    'noi_dung' => $validated['note'],
                    'nguoi_tao' => 'Hệ thống',
                ]);
            }

            DB::commit();
            return redirect()->route('admin.lien-he.show', $lienHe)
                ->with('success', 'Trạng thái liên hệ đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LienHe $lienHe)
    {
        DB::beginTransaction();
        try {
            $oldData = $lienHe->toArray();
            $lienHe->delete();

            // Ghi log hoạt động (lưu vào bảng riêng vì liên hệ đã bị xóa)
            DB::table('lien_he_deleted_logs')->insert([
                'lien_he_id' => $oldData['id'],
                'hanh_dong' => 'delete',
                'mo_ta' => 'Xóa liên hệ',
                'nguoi_thuc_hien' => 'Hệ thống',
                'du_lieu_cu' => json_encode($oldData),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return redirect()->route('admin.lien-he.index')
                ->with('success', 'Liên hệ đã được xóa thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * Add a note to a contact.
     */
    public function addNote(Request $request, LienHe $lienHe)
    {
        $validated = $request->validate([
            'noi_dung' => 'required|string',
        ]);

        $note = LienHeNote::create([
            'lien_he_id' => $lienHe->id,
            'noi_dung' => $validated['noi_dung'],
            'nguoi_tao' => 'Hệ thống',
        ]);

        // Ghi log hoạt động
        LienHeActivityLog::create([
            'lien_he_id' => $lienHe->id,
            'hanh_dong' => 'add_note',
            'mo_ta' => 'Thêm ghi chú mới',
            'nguoi_thuc_hien' => 'Hệ thống',
            'du_lieu_moi' => ['note_id' => $note->id, 'noi_dung' => $validated['noi_dung']],
        ]);

        return redirect()->route('admin.lien-he.show', $lienHe)
            ->with('success', 'Ghi chú đã được thêm thành công.');
    }

    /**
     * Update contact status quickly.
     */
    public function updateStatus(Request $request, LienHe $lienHe)
    {
        $validated = $request->validate([
            'trang_thai' => 'required|boolean',
        ]);

        $oldStatus = $lienHe->trang_thai;
        $lienHe->update(['trang_thai' => $validated['trang_thai']]);

        // Ghi log hoạt động
        LienHeActivityLog::create([
            'lien_he_id' => $lienHe->id,
            'hanh_dong' => 'update_status',
            'mo_ta' => 'Cập nhật trạng thái liên hệ',
            'nguoi_thuc_hien' => 'Hệ thống',
            'du_lieu_cu' => ['trang_thai' => $oldStatus],
            'du_lieu_moi' => ['trang_thai' => $validated['trang_thai']],
        ]);

        // Kiểm tra nếu request là AJAX thì trả về JSON, ngược lại redirect
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Trạng thái đã được cập nhật thành công.']);
        }

        return redirect()->back()->with('success', 'Trạng thái đã được cập nhật thành công.');
    }

    /**
     * Export contacts to CSV.
     */
    public function export(Request $request)
    {
        try {
            // Lấy dữ liệu liên hệ dựa trên các bộ lọc
            $search = $request->input('search');
            $status = $request->input('status');
            $priority = $request->input('priority');
            $category = $request->input('category');
            $from = $request->input('from');
            $to = $request->input('to');

            $lienHes = LienHe::search($search)
                ->status($status)
                ->priority($priority)
                ->category($category)
                ->dateRange($from, $to)
                ->orderBy('create_at', 'desc')
                ->get();

            // Tạo file CSV
            $filename = 'danh-sach-lien-he-' . date('Y-m-d') . '.csv';

            // Tạo nội dung CSV
            $output = fopen('php://temp', 'w');

            // Thêm BOM để hỗ trợ UTF-8 trong Excel
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Thêm tiêu đề
            fputcsv($output, [
                'ID',
                'Tên',
                'Email',
                'Số điện thoại',
                'Nội dung',
                'Trạng thái',
                'Mức độ ưu tiên',
                'Nguồn gốc',
                'Phân loại',
                'Người phụ trách',
                'Ngày tạo'
            ]);

            // Thêm dữ liệu
            foreach ($lienHes as $lienHe) {
                fputcsv($output, [
                    $lienHe->id,
                    $lienHe->ten,
                    $lienHe->email,
                    $lienHe->so_dien_thoai,
                    $lienHe->noi_dung,
                    $lienHe->trang_thai ? 'Đã xử lý' : 'Chưa xử lý',
                    $lienHe->muc_do_uu_tien == 'cao' ? 'Cao' : ($lienHe->muc_do_uu_tien == 'thap' ? 'Thấp' : 'Bình thường'),
                    $lienHe->nguon_goc,
                    $lienHe->phan_loai,
                    $lienHe->nguoi_phu_trach,
                    $lienHe->create_at,
                ]);
            }

            // Đặt con trỏ về đầu file
            rewind($output);

            // Đọc nội dung file
            $content = stream_get_contents($output);
            fclose($output);

            // Ghi log hoạt động
            LienHeActivityLog::create([
                'hanh_dong' => 'export',
                'mo_ta' => 'Xuất danh sách liên hệ',
                'nguoi_thuc_hien' => 'Hệ thống',
            ]);

            // Trả về response với nội dung CSV
            return response($content)
                ->header('Content-Type', 'text/csv; charset=UTF-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            // Ghi log lỗi và chuyển hướng đến trang index
            Log::error('Export error: ' . $e->getMessage());
            return redirect()->route('admin.lien-he.index')
                ->with('error', 'Đã xảy ra lỗi khi xuất dữ liệu: ' . $e->getMessage());
        }
    }

    /**
     * Display dashboard with statistics.
     */
    public function dashboard()
    {
        try {
            // Thống kê tổng quan
            $stats = [
                'total' => LienHe::count(),
                'pending' => LienHe::where('trang_thai', false)->count(),
                'completed' => LienHe::where('trang_thai', true)->count(),
                'high_priority' => LienHe::where('muc_do_uu_tien', 'cao')->count(),
            ];

            // Thống kê theo phân loại
            $categoryStats = LienHe::select('phan_loai', DB::raw('count(*) as total'))
                ->whereNotNull('phan_loai')
                ->groupBy('phan_loai')
                ->get();

            // Thống kê theo nguồn gốc
            $sourceStats = LienHe::select('nguon_goc', DB::raw('count(*) as total'))
                ->whereNotNull('nguon_goc')
                ->groupBy('nguon_goc')
                ->get();

            // Thống kê theo thời gian (7 ngày gần đây)
            $dateStats = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $count = LienHe::whereDate('create_at', $date)->count();
                $dateStats[$date] = $count;
            }

            // Liên hệ mới nhất
            $latestContacts = LienHe::orderBy('create_at', 'desc')->limit(5)->get();

            // Liên hệ ưu tiên cao chưa xử lý
            $highPriorityContacts = LienHe::where('muc_do_uu_tien', 'cao')
                ->where('trang_thai', false)
                ->orderBy('create_at', 'desc')
                ->limit(5)
                ->get();

            // Kiểm tra xem view có tồn tại không
            if (!View::exists('admin.lien-he.dashboard')) {
                // Nếu view không tồn tại, chuyển hướng đến trang index
                return redirect()->route('admin.lien-he.index')
                    ->with('error', 'Trang thống kê đang được phát triển. Vui lòng quay lại sau.');
            }

            return view('admin.lien-he.dashboard', compact(
                'stats',
                'categoryStats',
                'sourceStats',
                'dateStats',
                'latestContacts',
                'highPriorityContacts'
            ));
        } catch (\Exception $e) {
            // Ghi log lỗi và chuyển hướng đến trang index
            Log::error('Dashboard error: ' . $e->getMessage());
            return redirect()->route('admin.lien-he.index')
                ->with('error', 'Đã xảy ra lỗi khi tải trang thống kê: ' . $e->getMessage());
        }
    }

    /**
     * Send email response to contact.
     */
    public function sendEmail(Request $request, LienHe $lienHe)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            // Gửi email (giả lập)
            // Mail::to($lienHe->email)->send(new ContactResponse($lienHe, $validated['subject'], $validated['message']));

            // Cập nhật trạng thái đã phản hồi
            $lienHe->update(['da_phan_hoi' => true]);

            // Ghi log hoạt động
            LienHeActivityLog::create([
                'lien_he_id' => $lienHe->id,
                'hanh_dong' => 'send_email',
                'mo_ta' => 'Gửi email phản hồi',
                'nguoi_thuc_hien' => 'Hệ thống',
                'du_lieu_moi' => [
                    'subject' => $validated['subject'],
                    'message' => $validated['message'],
                ],
            ]);

            // Thêm ghi chú về việc gửi email
            LienHeNote::create([
                'lien_he_id' => $lienHe->id,
                'noi_dung' => "Đã gửi email phản hồi với tiêu đề: {$validated['subject']}",
                'nguoi_tao' => 'Hệ thống',
            ]);

            return redirect()->route('admin.lien-he.show', $lienHe)
                ->with('success', 'Email phản hồi đã được gửi thành công.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Đã xảy ra lỗi khi gửi email: ' . $e->getMessage()]);
        }
    }

    /**
     * Bulk actions on contacts.
     */
    public function bulkAction(Request $request)
    {
        try {
            // Ghi log để debug
            Log::info('Bulk action request:', $request->all());

            // Kiểm tra xem có ids được gửi lên không
            if (!$request->has('ids') || empty($request->ids)) {
                return redirect()->route('admin.lien-he.index')
                    ->with('error', 'Vui lòng chọn ít nhất một liên hệ để thực hiện hành động.');
            }

            // Kiểm tra xem có action được gửi lên không
            if (!$request->has('action') || empty($request->action)) {
                return redirect()->route('admin.lien-he.index')
                    ->with('error', 'Vui lòng chọn một hành động để thực hiện.');
            }

            // Kiểm tra xem có priority được gửi lên không khi action là change_priority
            if ($request->action === 'change_priority' && (!$request->has('priority') || empty($request->priority))) {
                return redirect()->route('admin.lien-he.index')
                    ->with('error', 'Vui lòng chọn mức độ ưu tiên khi thay đổi mức độ ưu tiên.');
            }

            // Đảm bảo ids là một mảng
            $ids = is_array($request->ids) ? $request->ids : [$request->ids];

            $validated = $request->validate([
                'action' => 'required|in:delete,mark_processed,mark_unprocessed,change_priority',
                'priority' => 'required_if:action,change_priority|in:cao,binh_thuong,thap',
            ]);

            DB::beginTransaction();
            $count = 0;

            switch ($validated['action']) {
                case 'delete':
                    // Lưu thông tin trước khi xóa
                    $lienHes = LienHe::whereIn('id', $ids)->get();
                    foreach ($lienHes as $lienHe) {
                        DB::table('lien_he_deleted_logs')->insert([
                            'lien_he_id' => $lienHe->id,
                            'hanh_dong' => 'bulk_delete',
                            'mo_ta' => 'Xóa hàng loạt liên hệ',
                            'nguoi_thuc_hien' => 'Hệ thống',
                            'du_lieu_cu' => json_encode($lienHe->toArray()),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    $count = LienHe::whereIn('id', $ids)->delete();
                    $message = "Đã xóa {$count} liên hệ thành công.";
                    break;

                case 'mark_processed':
                    $count = LienHe::whereIn('id', $ids)->update(['trang_thai' => true]);
                    $message = "Đã đánh dấu {$count} liên hệ là đã xử lý.";
                    break;

                case 'mark_unprocessed':
                    $count = LienHe::whereIn('id', $ids)->update(['trang_thai' => false]);
                    $message = "Đã đánh dấu {$count} liên hệ là chưa xử lý.";
                    break;

                case 'change_priority':
                    $count = LienHe::whereIn('id', $ids)->update(['muc_do_uu_tien' => $validated['priority']]);
                    $priorityLabel = match ($validated['priority']) {
                        'cao' => 'Cao',
                        'thap' => 'Thấp',
                        default => 'Bình thường',
                    };
                    $message = "Đã thay đổi mức độ ưu tiên của {$count} liên hệ thành {$priorityLabel}.";
                    break;

                default:
                    throw new \Exception('Hành động không hợp lệ');
            }

            // Ghi log hoạt động
            LienHeActivityLog::create([
                'hanh_dong' => 'bulk_action',
                'mo_ta' => $message,
                'nguoi_thuc_hien' => 'Hệ thống',
                'du_lieu_moi' => json_encode([
                    'action' => $validated['action'],
                    'ids' => $ids,
                    'count' => $count,
                ]),
            ]);

            DB::commit();
            return redirect()->route('admin.lien-he.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk action error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
    }
}
