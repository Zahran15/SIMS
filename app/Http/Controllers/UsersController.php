<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
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
            'nama'              => $request->nama,
            'email'             => $request->email,
            'password'          => $request->password,
            'no_hp'             => $request->no_hp,
            'role'              => $request->role,
            'status'            => $request->status,
        ]);
        return redirect()->back()->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Mengambil data satu user untuk Edit/Detail (Response JSON)
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('owner.master_data.users.edit', compact('user'));    
    }

    /**
     * Memperbarui data user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $data = [
            'nama'              => $request->nama,
            'email'             => $request->email,
            'no_hp'             => $request->no_hp,
            'role'              => $request->role,
            'status'            => $request->status,
        ];
        if ($user->role === 'owner') {
            $data = $request->except('role');
        } else {
            $data = $request->all();
        }
            $user->update($data);
        return redirect()->back()->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Menghapus data user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'User berhasil dihapus');
    }

    /**
     * Menampilkan detail user
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('owner.master_data.users.detail', compact('user'));    
    }

}