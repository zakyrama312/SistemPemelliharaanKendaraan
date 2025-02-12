<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RekeningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rekening = Rekening::all();
        return view('rekening.index', compact('rekening'));
    }

    /**
     * Menampilkan form tambah rekening.
     */
    public function create()
    {
        return view('rekening.rekening-create');
    }

    /**
     * Menyimpan data rekening baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_rek' => 'required|string|max:255',
            'saldo' => 'required|min:0',
        ], [
            'nama_rek.required' => 'Nama Rekening wajib diisi!',
            'nama_rek.string' => 'Nama Rekening harus berupa teks!',
            'saldo.required' => 'Saldo wajib diisi!',
            // 'saldo.numeric' => 'Saldo harus berupa angka!',
            'saldo.min' => 'Saldo tidak boleh kurang dari 0!',
        ]);

        Rekening::create([
            'nama_rekening' => $request->nama_rek,
            'slug' => Str::slug($request->nama_rek),
            'saldo_awal' => $request->saldo,
            'saldo_akhir' => $request->saldo,
            'tanggal' => now(),
        ]);

        return redirect('rekening')->with('success', 'Data Rekening berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit rekening.
     */
    public function edit(string $slug)
    {
        $rekening = Rekening::where('slug', $slug)->first();
        return view('rekening.rekening-edit', compact('rekening'));
    }

    /**
     * Menyimpan perubahan data rekening.
     */
    public function update(Request $request, string $slug)
    {

        $rekening = Rekening::where('slug', $slug)->firstOrFail();
        $request->validate([
            'nama_rek' => 'required|string|max:255',
            'saldo' => 'required|min:0',
        ], [
            'nama_rek.required' => 'Nama Rekening wajib diisi!',
            'nama_rek.string' => 'Nama Rekening harus berupa teks!',
            'saldo.required' => 'Saldo wajib diisi!',
            // 'saldo.numeric' => 'Saldo harus berupa angka!',
            'saldo.min' => 'Saldo tidak boleh kurang dari 0!',
        ]);

        Rekening::where('id', $rekening->id)->update([
            'nama_rekening' => $request->nama_rek,
            'slug' => Str::slug($request->nama_rek),
            'saldo_awal' => $request->saldo,
            'saldo_akhir' => $request->saldo,
        ]);

        return redirect('rekening')->with('success', 'Data Rekening berhasil diperbarui!');
    }

    /**
     * Menghapus rekening.
     */
    public function destroy(string $slug)
    {
        $rekening = Rekening::where('slug', $slug)->first();
        $rekening->delete();

        return redirect('rekening')->with('success', 'Data Rekening berhasil dihapus!');
    }
}