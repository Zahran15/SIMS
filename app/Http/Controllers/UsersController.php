<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        // 1. Jalankan pencarian Nama
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'LIKE', $request->search . '%');
        }

        // 2. Jalankan filter role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        // 3. Jalankan filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        $users = $query->paginate(10);
        $total_user = User::count();
        $user_aktif = User::where('status', 'aktif')->count();
        $user_nonaktif = User::where('status', 'nonaktif')->count();
        return view('owner.master_data.users.index', compact('users', 'total_user', 'user_aktif', 'user_nonaktif'));
    }

    public function create()
    {
        return view('owner.master_data.users.tambah');
    }
    
    public function store(Request $request)
    {
        User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => $request->password,
            'no_hp'    => $request->no_hp,
            'role'     => $request->role,
            'status'   => $request->status,
        ]);
        return redirect()->route('owner.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('owner.master_data.users.edit', compact('user'));    
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'nama'     => 'required',
            'email'    => 'required|email|unique:users,email,' . $id . ',id_user',
            'no_hp'    => 'required',
            'status'   => 'required',
            'password' => 'nullable',
        ]);
        $data = $request->all();
        if ($user->role === 'owner') {
            unset($data['role']); 
        }
        if ($request->filled('password')) {
            $data['password'] = $request->password; 
        } else {
            unset($data['password']); 
        }
        $user->update($data);
        return redirect()->route('owner.users.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'User berhasil dihapus');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('owner.master_data.users.detail', compact('user'));    
    }

}