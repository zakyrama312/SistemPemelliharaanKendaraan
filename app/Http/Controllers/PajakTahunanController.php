<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pajak;
use App\Models\Keuangan;
use App\Models\Rekening;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PajakTahunanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pajakTahunan = Kendaraan::with([
            'pajak'
        ])
            ->withCount([
                'pajak as total_pajak_tahunan' => function ($query) {
                    $query->where('jenis_pajak', 'pajak_tahunan');
                }
            ])
            ->withSum('pajak', 'nominal') // Hitung total biaya
            ->whereHas('pajak', function ($query) {
                $query->where('jenis_pajak', 'pajak_tahunan');
            })
            ->get();

        return view('pajaktahunan.index', compact('pajakTahunan'));
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
            'masa_berlaku' => 'required'
        ], [
            'biaya.required' => 'Biaya wajib diisi!',
            'biaya.min' => 'Biaya tidak boleh kurang dari 0!',
            'masa_berlaku.required' => 'Masa Berlaku wajib diisi!',
        ]);

        DB::beginTransaction();
        try {
            // Ambil saldo rekening dulu
            $rekening = Rekening::findOrFail($request->id_rekening);

            // Cek apakah saldo cukup
            if ($rekening->saldo_akhir < $request->biaya) {
                return redirect()->back()->with('error', 'Saldo rekening tidak mencukupi!');
            }

            $masaBerlaku = Carbon::createFromFormat('d/m/Y', $request->masa_berlaku)->format('Y-m-d');

            $pajak = Pajak::create([
                'id_kendaraan' => $request->id_kendaraan,
                'id_rekening' => $request->id_rekening,
                'masa_berlaku' => $masaBerlaku,
                'jenis_pajak' => 'pajak_tahunan',
                'nominal' => $request->biaya
            ]);
            // Kurangi saldo rekening
            $rekening = Rekening::findOrFail($request->id_rekening);
            $saldo_akhir = $rekening->saldo_akhir - $request->biaya;
            $rekening->update(['saldo_akhir' => $saldo_akhir]);

            // Simpan keuangan (pengeluaran)
            Keuangan::create([
                'id_rekening' => $request->id_rekening,
                'jenis_transaksi' => 'pengeluaran',
                'id_sumber' => $pajak->id, // Relasi ke tabel pengeluaran Pajak
                'sumber_transaksi' => 'Pajak Tahunan',
                'nominal' => $request->biaya,
                'tanggal' => now(),
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
        $kendaraan = Kendaraan::where('slug', $slug)->with('rekening', 'pajak')->first();
        $view_pajakTahunan = Pajak::where('id_kendaraan', $kendaraan->id)
            ->where('jenis_pajak', 'pajak_tahunan')
            ->with('kendaraan', 'rekening')
            ->orderBy('created_at', 'desc') // Urut dari yang terbaru
            ->get();
        $pajakTerbaru = Pajak::where('id_kendaraan', $kendaraan->id)
            ->latest('masa_berlaku') // Urutkan dari yang terbaru
            ->first();
        $masa_berlaku = $pajakTerbaru ? Carbon::parse($pajakTerbaru->masa_berlaku)->format('d/m/Y') : null;

        return view('pajaktahunan.pajaktahunan-create', compact('kendaraan', 'view_pajakTahunan', 'masa_berlaku'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            // Cari data bahan bakar
            $pajakTahunan = Pajak::findOrFail($id);
            $rekening = Rekening::findOrFail($pajakTahunan->id_rekening);

            // Ambil transaksi keuangan terkait bahan bakar ini
            $keuangan = Keuangan::where('id_sumber', $pajakTahunan->id)
                ->where('sumber_transaksi', 'Pajak Tahunan')
                ->first();

            if (!$keuangan) {
                return redirect()->back()->with('error', 'Data keuangan tidak ditemukan.');
            }

            // Kembalikan saldo rekening dengan nominal lama
            $rekening->saldo_akhir += $pajakTahunan->nominal;

            // Cek apakah saldo cukup untuk biaya baru
            if ($rekening->saldo_akhir < $request->biaya) {
                return redirect()->back()->with('error', 'Saldo rekening tidak mencukupi!');
            }

            // Kurangi saldo rekening dengan biaya baru
            $rekening->saldo_akhir -= $request->biaya;
            $rekening->save();

            $masaBerlaku = Carbon::createFromFormat('d/m/Y', $request->masa_berlaku)->format('Y-m-d');

            $pajakTahunan->update([
                'masa_berlaku' => $masaBerlaku,
                'nominal' => $request->biaya
            ]);


            // Update data keuangan
            $keuangan->update([
                'nominal' => $request->biaya,
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
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            // Ambil data Pajak yang akan dihapus
            $pajakTahunan = Pajak::findOrFail($id);

            // Ambil data rekening terkait
            $rekening = Rekening::findOrFail($pajakTahunan->id_rekening);

            // Ambil data keuangan yang terkait dengan bahan bakar ini
            $keuangan = Keuangan::where('id_sumber', $pajakTahunan->id)
                ->where('sumber_transaksi', 'Pajak Tahunan')
                ->first();

            // Jika data keuangan ada, hapus dulu sebelum hapus bahan bakar
            if ($keuangan) {
                $keuangan->delete();
            }

            // Kembalikan saldo rekening dengan nominal bahan bakar yang dihapus
            $rekening->saldo_akhir += $pajakTahunan->nominal;
            $rekening->save();


            // Hapus data bahan bakar
            $pajakTahunan->delete();

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return redirect()->back()->with('success', 'Data Pajak Tahunan berhasil dihapus dan saldo rekening diperbarui.');
        } catch (\Exception $e) {
            // Rollback jika ada kesalahan
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data Pajak Tahunan: ' . $e->getMessage());
        }
    }
}