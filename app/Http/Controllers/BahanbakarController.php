<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Keuangan;
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

    public function data()
    {
        $kendaraanData = Bahanbakar::with('kendaraan')
            ->where('nominal', '!=', 0)
            ->orderBy('tanggal_pengisian', 'desc')
            ->get();
        return view('bahanbakar.bahanbakar-data', compact('kendaraanData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'biaya' => 'required|numeric|min:0',
            'jumlah_liter' => 'required|numeric|min:0',
            'spbu' => 'required|string|max:255',
            'harga_bbm' => 'required|numeric|min:0',
            'tanggal' => 'required',
            'id_rekening' => 'required'
        ], [
            'biaya.required' => 'Biaya wajib diisi!',
            'biaya.min' => 'Biaya tidak boleh kurang dari 0!',
            'jumlah_liter.required' => 'Jumlah liter wajib diisi!',
            'jumlah_liter.min' => 'Jumlah liter tidak boleh kurang dari 0!',
            'spbu.required' => 'SPBU wajib diisi!',
            'spbu.string' => 'SPBU harus berupa teks!',
            'harga_bbm.required' => 'Harga BBM wajib diisi!',
            'harga_bbm.min' => 'Harga BBM tidak boleh kurang dari 0!',
            'tanggal.required' => 'Tanggal wajib diisi!',
            'id_rekening.required' => 'Rekening wajib dipilih!',
        ]);

        DB::beginTransaction();
        try {
            // Ambil saldo rekening dulu
            $rekening = Rekening::findOrFail($request->id_rekening);

            // Cek apakah saldo cukup
            if ($rekening->saldo_akhir < $request->biaya) {
                return redirect()->back()->with('error', 'Saldo rekening tidak mencukupi!');
            }

            // Proses upload gambar
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
            $tanggal = Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d');
            // Simpan transaksi pengeluaran BBM
            $bbm = Bahanbakar::create([
                'id_kendaraan' => $request->id_kendaraan,
                'id_rekening' => $request->id_rekening,
                'foto_struk' => $fotoPath,
                'jumlah_liter' => $request->jumlah_liter,
                'nominal' => $request->biaya,
                'spbu' => $request->spbu,
                'harga_bbm' => $request->harga_bbm,
                'tanggal_pengisian' => $tanggal,
            ]);
            // Kurangi saldo rekening
            $rekening = Rekening::findOrFail($request->id_rekening);
            $saldo_akhir = $rekening->saldo_akhir - $request->biaya;
            $rekening->update(['saldo_akhir' => $saldo_akhir]);

            // Simpan keuangan (pengeluaran)
            Keuangan::create([
                'id_rekening' => $request->id_rekening,
                'jenis_transaksi' => 'pengeluaran',
                'id_sumber' => $bbm->id, // Relasi ke tabel pengeluaran BBM
                'sumber_transaksi' => 'Pengeluaran BBM',
                'nominal' => $request->biaya,
                'tanggal' => $tanggal,
                'saldo_setelah' => $saldo_akhir
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil disimpan dan dicatat di keuangan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $kendaraan = Kendaraan::where('slug', $slug)->with('rekening')->first();
        $rekening = Rekening::all();
        $view_bbm = Bahanbakar::where('id_kendaraan', $kendaraan->id)
            ->with('kendaraan', 'rekening')
            ->orderBy('created_at', 'desc') // Urut dari yang terbaru
            ->get();

        return view('bahanbakar.bahanbakar-create', compact('kendaraan', 'view_bbm', 'rekening'));
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
        $request->validate([
            'biaya' => 'required|numeric|min:0',
            'jumlah_liter' => 'required|numeric|min:0',
            'foto_struk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'spbu' => 'required|string|max:255',
            'harga_bbm' => 'required|numeric|min:0',
            'tanggal' => 'required',
        ], [
            'biaya.required' => 'Biaya wajib diisi!',
            'biaya.min' => 'Biaya tidak boleh kurang dari 0!',
            'jumlah_liter.required' => 'Jumlah liter wajib diisi!',
            'jumlah_liter.min' => 'Jumlah liter tidak boleh kurang dari 0!',
            'spbu.required' => 'SPBU wajib diisi!',
            'spbu.string' => 'SPBU harus berupa teks!',
            'harga_bbm.required' => 'Harga BBM wajib diisi!',
            'harga_bbm.min' => 'Harga BBM tidak boleh kurang dari 0!',
            'tanggal.required' => 'Tanggal wajib diisi!',
        ]);

        DB::beginTransaction();
        try {
            // Cari data bahan bakar
            $bahanbakar = Bahanbakar::findOrFail($id);
            $rekening = Rekening::findOrFail($bahanbakar->id_rekening);
            $tanggal = Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d');
            // Ambil transaksi keuangan terkait bahan bakar ini
            $keuangan = Keuangan::where('id_sumber', $bahanbakar->id)
                ->where('sumber_transaksi', 'Pengeluaran BBM')
                ->first();

            if (!$keuangan) {
                return redirect()->back()->with('error', 'Data keuangan tidak ditemukan.');
            }

            // Kembalikan saldo rekening dengan nominal lama
            $rekening->saldo_akhir += $bahanbakar->nominal;

            // Cek apakah saldo cukup untuk biaya baru
            if ($rekening->saldo_akhir < $request->biaya) {
                return redirect()->back()->with('error', 'Saldo rekening tidak mencukupi!');
            }

            // Kurangi saldo rekening dengan biaya baru
            $rekening->saldo_akhir -= $request->biaya;
            $rekening->save();

            // Update foto struk jika ada yang baru
            $fotoPath = $bahanbakar->foto_struk;
            if ($request->hasFile('foto_struk')) {
                $file = $request->file('foto_struk');
                $filename = time() . '.jpg';

                // Hapus foto lama jika ada
                if ($bahanbakar->foto_struk && file_exists(public_path('strukImage/' . $bahanbakar->foto_struk))) {
                    unlink(public_path('strukImage/' . $bahanbakar->foto_struk));
                }

                // Simpan foto baru
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file)
                    ->scale(width: 800)
                    ->toJpeg(75);
                $image->save(public_path("strukImage/{$filename}"));
                $fotoPath = "{$filename}";
            }

            // Update data bahan bakar
            $bahanbakar->update([
                'foto_struk' => $fotoPath,
                'jumlah_liter' => $request->jumlah_liter,
                'nominal' => $request->biaya,
                'spbu' => $request->spbu,
                'harga_bbm' => $request->harga_bbm,
                'tanggal_pengisian' => $tanggal
            ]);

            // Update data keuangan
            $keuangan->update([
                'nominal' => $request->biaya,
                'tanggal' => $tanggal,
                'saldo_setelah' => $rekening->saldo_akhir
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            // Ambil data bahan bakar
            $bahanbakar = Bahanbakar::findOrFail($id);

            // Ambil rekening jika ada
            $rekening = null;
            if ($bahanbakar->id_rekening) {
                $rekening = Rekening::find($bahanbakar->id_rekening);
                if ($rekening) {
                    $rekening->saldo_akhir += $bahanbakar->nominal;
                    $rekening->save();
                }
            }

            // Ambil data keuangan yang terkait
            $keuangan = Keuangan::where('id_sumber', $bahanbakar->id)
                ->where('sumber_transaksi', 'Pengeluaran BBM')
                ->first();

            if ($keuangan) {
                $keuangan->delete();
            }

            // Hapus foto struk jika ada
            $strukPath = public_path('strukImage/' . $bahanbakar->foto_struk);
            if ($bahanbakar->foto_struk && file_exists($strukPath)) {
                unlink($strukPath);
            }

            // Hapus data bahan bakar
            $bahanbakar->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Data bahan bakar berhasil dihapus.' . ($rekening ? ' Saldo rekening diperbarui.' : ''));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data bahan bakar: ' . $e->getMessage());
        }
    }

    public function print(Request $request, string $slug)
    {
        $kendaraan = Kendaraan::where('slug', $slug)->with('rekening', 'user')->firstOrFail();

        $start = $request->query('start_date');
        $end = $request->query('end_date');

        $data = Bahanbakar::where('id_kendaraan', $kendaraan->id)
            ->whereBetween('tanggal_pengisian', [$start, $end])
            ->orderBy('tanggal_pengisian')
            ->get();

        $totalLiter = $data->sum('jumlah_liter');
        $totalBiaya = $data->sum('nominal');

        return view('bahanbakar.bahanbakar-print', compact('kendaraan', 'data', 'start', 'end', 'totalLiter', 'totalBiaya'));
    }
}
