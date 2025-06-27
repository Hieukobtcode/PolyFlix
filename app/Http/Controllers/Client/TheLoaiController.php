<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\TheLoaiPhim; // hoặc TheLoai
use Illuminate\Http\Request;

class TheLoaiController extends Controller
{
    public function show($id)
    {
        $theLoai = TheLoaiPhim::findOrFail($id);
        $phims = $theLoai->phims()->paginate(12); // hoặc lấy tất cả
        return view('client.the-loai.show', compact('theLoai', 'phims'));
    }
}
