<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;

class Users extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::select('id', 'slug', 'name', 'nip', 'telp', 'username', 'role')->get();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.users-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {

        // Simpan Data ke Database
        User::create([
            'slug' => Str::slug($request->nama),
            'nip' => $request->nip,
            'name' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('pegawai')->with('success', 'Data berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $pegawai = User::where('slug', $slug)->firstOrFail();
        return view('users.users-edit', compact('pegawai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {

        $pegawai = User::where('slug', $slug)->firstOrFail();
        $request->validate([
            'nip' => 'required|numeric|unique:users,nip,' . $pegawai->id,
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $pegawai->id,
            'password' => 'nullable|min:6',
            'role' => 'required'
        ], [
            'nip.required' => 'NIP wajib diisi!',
            'nip.numeric' => 'NIP harus berupa angka!',
            'nip.unique' => 'NIP sudah digunakan!',
            'nama.required' => 'Nama wajib diisi!',
            'username.required' => 'Username wajib diisi!',
            'username.unique' => 'Username sudah digunakan!',
            'password.min' => 'Password minimal 6 karakter!',
            'role.required' => 'Role wajib dipilih!',
        ]);

        $pegawai->nip = $request->nip;
        $pegawai->name = $request->nama;
        $pegawai->slug = Str::slug($request->nama);
        $pegawai->username = $request->username;

        if ($request->filled('password')) {
            $pegawai->password = Hash::make($request->password);
        }

        $pegawai->role = $request->role;
        $pegawai->save();

        return redirect('pegawai')->with('success', 'Data Pegawai berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $pegawai = User::where('slug', $slug)->firstOrFail();
        $pegawai->delete();

        return redirect('pegawai')->with('success', 'Data Pegawai berhasil dihapus!');
    }
}