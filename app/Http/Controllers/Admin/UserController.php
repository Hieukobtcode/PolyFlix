<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VaiTro;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        if ($request->has('keyword') && $request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%')
            ->orWhere('email', 'like', '%' . $request->keyword . '%');
        }
        
        if ($request->has('status') && $request->status !== null) {
            $query->where('trang_thai', $request->status);
        }
        
        $users = $query->paginate(10);
        $vaiTros = VaiTro::all();

        return view('admin.users.index', compact('users','vaiTros'));
    }

    public function create()
    {
        $vaiTros = VaiTro::all();
        return view('admin.users.create', compact('vaiTros'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'vai_tro_id' => 'required|exists:vai_tros,id',
            'so_dien_thoai' => 'nullable|string|max:20',
            'trang_thai' => 'required|in:active,block',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['hoat_dong'] = 0; // Mặc định là 0

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Thêm người dùng thành công');
    }

    public function show($id)
    {
        $user = User::with('vaiTro')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $vaiTros = VaiTro::all();
        return view('admin.users.edit', compact('user', 'vaiTros'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'vai_tro_id' => 'required|exists:vai_tros,id',
            'dia_chi' => 'nullable|string',
            'so_dien_thoai' => 'nullable|string|max:20',
            'trang_thai' => 'required|in:active,block',
            'hoat_dong' => 'nullable|boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['hoat_dong'] = $validated['hoat_dong'] ?? 0;

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.users.index')->with('success', 'Xóa người dùng thành công');
    }
}
