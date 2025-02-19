<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use App\Models\Kendaraan;
use App\Models\Bahanbakar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BahanbakarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kendaraanData = Kendaraan::with([
            'pengeluaran_bbm'
        ])
            ->withCount('pengeluaran_bbm') // Hitung frekuensi pengeluaran_bbm
            ->withSum('pengeluaran_bbm', 'nominal') // Hitung total biaya
            ->get();

        return view('bahanbakar.index', compact('kendaraanData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'biaya' => 'required|numeric|min:0',
            'jumlah_liter' => 'required|numeric|min:0'
        ], [
            'biaya.required' => 'Biaya wajib diisi!',
            'biaya.min' => 'Biaya tidak boleh kurang dari 0!',
            'jumlah_liter.required' => 'Jumlah liter wajib diisi!',
            'jumlah_liter.min' => 'Jumlah liter tidak boleh kurang dari 0!',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_struk')) {
            $file = $request->file('foto_struk');
            $filename = time() . '.jpg';

            $manager = new ImageManager(new Driver());
            $image = $manager->read($file)
                ->scale(width: 800)
                ->toJpeg(75);

            $image->save(public_path("strukImage/{$filename}"));
            $fotoPath = "{$filename}";


        }
        Bahanbakar::create([
            'id_kendaraan' => $request->id_kendaraan,
            'id_rekening' => $request->id_rekening,
            'foto_struk' => $fotoPath,
            'jumlah_liter' => $request->jumlah_liter,
            'nominal' => $request->biaya
        ]);

        Rekening::where('id', $request->id_rekening)->decrement('saldo_akhir', $request->biaya);

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $kendaraan = Kendaraan::where('slug', $slug)->with('rekening')->first();
        $view_bbm = Bahanbakar::where('id_kendaraan', $kendaraan->id)
            ->with('kendaraan', 'rekening')
            ->orderBy('created_at', 'desc') // Urut dari yang terbaru
            ->get();
        return view('bahanbakar.bahanbakar-create', compact('kendaraan', 'view_bbm'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bahanbakar $bahanbakar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Cari data pemeliharaan yang ingin diupdate
        $bahanbakar = Bahanbakar::findOrFail($id);

        $rekening = Rekening::find($bahanbakar->id_rekening);

        if (!$rekening) {
            return redirect()->back()->with('error', 'Rekening tidak ditemukan.');
        }

        // Kembalikan biaya lama ke saldo_akhir rekening
        $rekening->saldo_akhir += $bahanbakar->nominal;

        // Kurangi saldo dengan biaya baru
        $rekening->saldo_akhir -= $request->biaya;

        // Simpan perubahan saldo rekening
        $rekening->save();
        $fotoPath = $bahanbakar->foto_struk;
        if ($request->hasFile('foto_struk')) {
            $file = $request->file('foto_struk');
            $filename = time() . '.jpg';

            // Hapus foto_struk lama jika ada
            if ($bahanbakar->foto_struk && file_exists(public_path('strukImage/' . $bahanbakar->foto_struk))) {
                unlink(public_path('strukImage/' . $bahanbakar->foto_struk));
            }
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file)
                ->scale(width: 800)
                ->toJpeg(75);

            $image->save(public_path("strukImage/{$filename}"));
            $fotoPath = "{$filename}";
        }
        $bahanbakar->update([
            'foto_struk' => $fotoPath,
            'jumlah_liter' => $request->jumlah_liter,
            'nominal' => $request->biaya
        ]);

        return redirect()->back()->with('success', 'Data berhasil diedit!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            // Ambil data pemeliharaan yang akan dihapus
            $bahanbakar = Bahanbakar::findOrFail($id);
            $kendaraan = Kendaraan::where('id', $bahanbakar->id_kendaraan)->first();

            // Ambil data rekening terkait
            $rekening = Rekening::find($bahanbakar->id_rekening);

            if (!$rekening) {
                return redirect()->back()->with('error', 'Rekening tidak ditemukan.');
            }

            // Kembalikan biaya bahanbakar ke saldo akhir rekening
            $rekening->saldo_akhir += $bahanbakar->nominal;
            $rekening->save();

            // Hapus data bahanbakar
            $bahanbakar->delete();

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return redirect()->back()->with('success', 'Data pemeliharaan berhasil dihapus dan saldo rekening diperbarui.');
        } catch (\Exception $e) {
            // Rollback jika ada kesalahan
            DB::rollBack();
            return redirect()->back()->with('success', 'Gagal menghapus data pemeliharaan: ' . $e->getMessage());
        }
    }
}