<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\Phim;
use App\Models\Banner;
use App\Models\Rating;
use App\Models\BaiViet;
use App\Models\RapPhim;
use App\Models\ChiNhanh;
use App\Models\SuatChieu;
use App\Helpers\IdFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class PhimsController extends Controller
{

    public function phimDangChieu()
    {
        $phims = Phim::with(['comments.user'])
            ->where('ngay_phat_hanh', '<=', now())
            ->where('ngay_ket_thuc', '>=', now())
            ->orderBy('ngay_phat_hanh', 'desc')
            ->get();
        $ratings = Rating::all();

        $banners = Banner::where('trang_thai', 1)->orderBy('id', 'desc')->get();

        $tab = 'dang-chieu';
        $baiViet = BaiViet::where('status', '!=', 'draft')
            ->orderBy('ngay_tao', 'desc')
            ->limit(4)
            ->get();

        return view('client.phim.phim-list', compact('phims', 'ratings', 'banners', 'tab', 'baiViet'));
    }

    public function phimSapChieu()
    {
        $phims = Phim::with(['comments.user'])
            ->where('ngay_phat_hanh', '>', now())
            ->orderBy('ngay_phat_hanh', 'asc')
            ->get();
        $ratings = Rating::all();

        $banners = Banner::where('trang_thai', 1)->orderBy('id', 'desc')->get();

        $tab = 'sap-chieu';
        $baiViet = BaiViet::where('status', '!=', 'draft')
            ->orderBy('ngay_tao', 'desc')
            ->limit(4)
            ->get();

        return view('client.phim.phim-list', compact('phims', 'ratings', 'banners', 'tab', 'baiViet'));
    }

    public function show($ten_phim)
    {
        $user = Auth::user();
        if ($user && $user->vai_tro_id == 4) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này');
            // hoặc return redirect()->route('home')->with('error', 'Bạn không có quyền vào trang này');
        }
        $phim = Phim::with(['theLoais', 'dinhDangs', 'phuDes', 'chiNhanhs', 'rapPhims', 'ratings'])
            ->where('ten_phim', urldecode($ten_phim))
            ->firstOrFail();

        $raps = RapPhim::all();

        $dinhDangPhims = SuatChieu::where('phim_id', $phim->id)
            ->select('phien_ban_phim')
            ->distinct()
            ->pluck('phien_ban_phim')
            ->filter()
            ->values();

        $chiNhanhs = $phim->chiNhanhs;

        $ngay_chieu = request('ngay_bat_dau');
        $ngayChieus = SuatChieu::where('phim_id', $phim->id)
            ->select('ngay_bat_dau')
            ->distinct()
            ->pluck('ngay_bat_dau');

        $days = [];
        $today = Carbon::today();
        for ($i = 0; $i < 7; $i++) {
            $date = $today->copy()->addDays($i);
            $label = $date->translatedFormat('l');
            $label = mb_convert_case($label, MB_CASE_TITLE, "UTF-8");

            $days[] = [
                'date' => $date->toDateString(),
                'label' => $label,
                'show' => $date->format('d/m'),
            ];
        }

        $selectedDate = $ngay_chieu ?: $today->format('Y-m-d');
        $currentIndex = collect($days)->search(fn($item) => $item['date'] === $selectedDate);

        $now = Carbon::now();

        $suatChieus = SuatChieu::where('phim_id', $phim->id)
            ->when($ngay_chieu, function ($q) use ($ngay_chieu, $now) {
                $q->where('ngay_bat_dau', $ngay_chieu);

                if ($ngay_chieu == $now->toDateString()) {
                    $q->whereRaw("TIME(bat_dau) > ?", [$now->format('H:i:s')]);
                }
            })
            ->with(['rapPhim', 'dinhDangPhim'])
            ->get();

        $groupedSuatChieus = $suatChieus->groupBy(function ($sc) {
            return $sc->rapPhim && $sc->rapPhim->ten_rap ? $sc->rapPhim->ten_rap : 'Không xác định';
        })->map(function ($items) {
            return $items->groupBy(function ($sc) {
                return $sc->phien_ban_phim ?? 'Không xác định';
            });
        });

        $phimDangChieu = Phim::where('id', '!=', $phim->id)->latest()->limit(3)->get();

        $phim->diem_trung_binh = round($phim->ratings->avg('rating'), 1);
        $phim->so_danh_gia = $phim->ratings->count();

        $allPhims = Phim::all();

        return view('client.phim.chi-tiet', compact(
            'phim',
            'days',
            'currentIndex',
            'raps',
            'chiNhanhs',
            'ngayChieus',
            'phimDangChieu',
            'groupedSuatChieus',
            'dinhDangPhims',
            'allPhims'
        ));
    }

    public function loadLichChieu($id)
    {
        $ngay_chieu = request('ngay_bat_dau');
        $chi_nhanh_id = request('chi_nhanh_id');

        $query = SuatChieu::where('phim_id', $id);

        if ($ngay_chieu) {
            $query->where('ngay_bat_dau', $ngay_chieu);
        }

        if ($chi_nhanh_id) {
            $query->whereHas('phongChieu.rapPhim', function ($q) use ($chi_nhanh_id) {
                $q->where('chi_nhanh_id', $chi_nhanh_id);
            });
        }

        $dinhs = ['2D', '3D', 'IMAX', '4DX'];
        $phus = [
            'Tiếng Việt',
            'Tiếng Anh',
            'Song ngữ Việt-Anh',
            'Tiếng Nhật',
            'Lồng Tiếng'
        ];

        function toKey($str)
        {
            $str = mb_strtolower($str, 'UTF-8');
            // Loại bỏ dấu tiếng Việt
            $str = preg_replace(
                ['/[áàảãạâấầẩẫậăắằẳẵặ]/u', '/[éèẻẽẹêếềểễệ]/u', '/[íìỉĩị]/u', '/[óòỏõọôốồổỗộơớờởỡợ]/u', '/[úùủũụưứừửữự]/u', '/[ýỳỷỹỵ]/u', '/[đ]/u'],
                ['a', 'e', 'i', 'o', 'u', 'y', 'd'],
                $str
            );
            $str = str_replace([' ', '_'], '-', $str);
            return $str;
        }

        $phienBanMapping = [];
        foreach ($dinhs as $dinh) {
            foreach ($phus as $phu) {
                $key = toKey($dinh . '-' . $phu);
                $phienBanMapping[$key] = "$dinh $phu";
            }
        }

        $suatChieus = $query->with(['rapPhim', 'dinhDangPhim'])->get();

        $groupedSuatChieus = $suatChieus->groupBy(function ($sc) {
            return $sc->phongChieu?->rapPhim?->ten_rap ?? 'Không xác định';
        })->map(function ($items) use ($phienBanMapping) {
            return $items->groupBy(function ($sc) use ($phienBanMapping) {
                $key = $sc->phien_ban_phim;
                return $key && isset($phienBanMapping[$key]) ? $phienBanMapping[$key] : 'Không xác định';
            });
        });



        $html = view('client.phim.lich-chieu-list', compact('groupedSuatChieus'))->render();

        return response()->json(['html' => $html]);
    }
}
