<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use Illuminate\Http\Request;

class DanhSachBaiVietController extends Controller
{
    public function index()
    {
        $baiViet = BaiViet::where('status', '!=', 'draft')->get(); 

        return view('client.bai-viet', compact('baiViet'));
    }
    public function show($id)
    {
        $baiViet = BaiViet::findOrFail($id);
        return view('client.bai-viet-show', compact('baiViet'));
    }

}
