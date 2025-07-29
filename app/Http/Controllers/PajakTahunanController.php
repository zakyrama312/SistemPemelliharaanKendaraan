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
            'pajak' => function ($query) {
                $query->where('jenis_pajak', 'pajak_tahunan')
                    ->latest('masa_berlaku') // Ambil pajak terbaru berdasarkan masa berlaku
                    ->take(1); // Ambil satu pajak terbaru per kendaraan
            }
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
            ->get()
            ->flatMap(function ($kendaraan) {
                return $kendaraan->pajak->map(function ($pajak) use ($kendaraan) {
                    if (!$pajak->masa_berlaku) {
                        $peringatan = null;
                        $status = 'safe';
                    } else {
                        $masaBerlaku = Carbon::parse($pajak->masa_berlaku->format('Y-m-d'));
                        $hariSisa = ceil(now()->diffInDays($masaBerlaku, false));

                        if ($hariSisa > 0 && $hariSisa <= 7) {
                            $peringatan = "<strong>$hariSisa hari</strong> lagi segera membayar pajak";
                            $status = 'warning';
                        } elseif ($hariSisa < 0) {
                            $peringatan = "Sudah Melewati Jatuh Tempo  <strong>" . abs($hariSisa) . " hari</strong>";
                            $status = 'danger';
                        } else {
                            $peringatan = "Aman";
                            $status = 'safe';
                        }
                    }

                    return [
                        'slug' => $kendaraan->slug,
                        'nomor_polisi' => $kendaraan->no_polisi,
                        'merk' => $kendaraan->merk,
                        'model' => $kendaraan->model,
                        'masa_berlaku' => $pajak->masa_berlaku,
                        'peringatan' => $peringatan,
                        'status' => $status,
                    ];
                });
            });





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
            'masa_berlaku' => 'required',
            'id_rekening' => 'required'
        ], [
            'biaya.required' => 'Biaya wajib diisi!',
            'biaya.min' => 'Biaya tidak boleh kurang dari 0!',
            'masa_berlaku.required' => 'Masa Berlaku wajib diisi!',
            'id_rekening.required' => 'Rekening wajib dipilih!'
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
        $rekening = Rekening::all();
        $view_pajakTahunan = Pajak::where('id_kendaraan', $kendaraan->id)
            ->where('jenis_pajak', 'pajak_tahunan')
            ->with('kendaraan', 'rekening')
            ->orderBy('created_at', 'desc') // Urut dari yang terbaru
            ->get();
        $pajakTerbaru = Pajak::where('id_kendaraan', $kendaraan->id)
            ->latest('masa_berlaku') // Urutkan dari yang terbaru
            ->first();
        $masa_berlaku = $pajakTerbaru ? Carbon::parse($pajakTerbaru->masa_berlaku)->format('d/m/Y') : null;

        return view('pajaktahunan.pajaktahunan-create', compact('kendaraan', 'view_pajakTahunan', 'masa_berlaku', 'rekening'));
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

            // Cek dan ambil rekening hanya jika ID-nya ada
            $rekening = null;
            if ($pajakTahunan->id_rekening) {
                $rekening = Rekening::find($pajakTahunan->id_rekening);

                // Kalau rekening ditemukan, kembalikan saldo
                if ($rekening) {
                    $rekening->saldo_akhir += $pajakTahunan->nominal;
                    $rekening->save();
                }
            }

            // Ambil data keuangan yang terkait
            $keuangan = Keuangan::where('id_sumber', $pajakTahunan->id)
                ->where('sumber_transaksi', 'Pajak Tahunan')
                ->first();

            if ($keuangan) {
                $keuangan->delete();
            }

            // Hapus data pajak tahunan
            $pajakTahunan->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Data Pajak Tahunan berhasil dihapus.' . ($rekening ? ' Saldo rekening diperbarui.' : ''));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data Pajak Tahunan: ' . $e->getMessage());
        }
    }
}
