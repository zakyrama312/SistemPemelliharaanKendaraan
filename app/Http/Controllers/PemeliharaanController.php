<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Keuangan;
use App\Models\Rekening;
use App\Models\Kendaraan;
use App\Models\Pemeliharaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeliharaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kendaraanData = Kendaraan::with([
            'pemeliharaan' => function ($query) {
                $query->whereNotNull('tanggal_pemeliharaan_sebelumnya') // Pastikan tidak null
                    ->orderByDesc('tanggal_pemeliharaan_berikutnya')
                    ->limit(1); // Ambil pemeliharaan terbaru
            }
        ])
            ->withCount([
                'pemeliharaan as total_pemeliharaan' => function ($query) {
                    $query->whereNotNull('tanggal_pemeliharaan_sebelumnya')
                        ->where('biaya', '!=', 0);
                }
            ]) // Hitung frekuensi pemeliharaan hanya jika tanggal tidak kosong
            ->withSum([
                'pemeliharaan as total_biaya_pemeliharaan' => function ($query) {
                    $query->whereNotNull('tanggal_pemeliharaan_sebelumnya')
                        ->where('biaya', '!=', 0);
                }
            ], 'biaya') // Hitung total biaya hanya jika tanggal tidak kosong
            ->orderBy('created_at', 'desc')
            ->get();
        // Tambahkan status berdasarkan tanggal pemeliharaan berikutnya
        $kendaraanData->transform(function ($kendaraan) {
            $tanggalBerikutnya = optional($kendaraan->pemeliharaan->first())->tanggal_pemeliharaan_berikutnya;

            // Pastikan tanggal valid sebelum menggunakan Carbon
            if (!empty($tanggalBerikutnya) && $tanggalBerikutnya !== '-' && strtotime($tanggalBerikutnya) !== false) {
                $tanggalBerikutnya = Carbon::parse($tanggalBerikutnya)->format('Y-m-d');
                $hariIni = now()->format('Y-m-d');
                $batasPeringatan = now()->addDays(5)->format('Y-m-d');

                if ($tanggalBerikutnya < $hariIni) {
                    $kendaraan->status_pemeliharaan = "🚨 Terlambat";
                } elseif ($tanggalBerikutnya <= $batasPeringatan) {
                    $kendaraan->status_pemeliharaan = "⚠️ Segera Servis";
                } else {
                    $kendaraan->status_pemeliharaan = "✅ Aman";
                }
            } else {
                $kendaraan->status_pemeliharaan = "❓ Tidak Ada Jadwal";
            }

            return $kendaraan;
        });
        $pemeliharaan = Pemeliharaan::all();
        if ($pemeliharaan->isEmpty()) {
            return redirect()->route('kendaraan.index');
        }
        return view('pemeliharaan.index', compact('kendaraanData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function data()
    {
        $kendaraanData = Pemeliharaan::with('kendaraan')
            ->where('biaya', '!=', 0)
            ->orderBy('tanggal_pemeliharaan_sebelumnya', 'desc')
            ->get();
        return view('pemeliharaan.pemeliharaan-data', compact('kendaraanData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_bengkel' => 'required|string|max:255',
            'biaya' => 'required|min:0',
            'tanggal' => 'required',
            'jadwal' => 'required',
            'id_rekening' => 'required',
        ], [
            'nama_bengkel.required' => 'Nama Bengkel wajib diisi!',
            'biaya.required' => 'Biaya wajib diisi!',
            'biaya.min' => 'Biaya tidak boleh kurang dari 0!',
            'deskripsi.required' => 'Deskripsi wajib diisi!',
            'tanggal.required' => 'Tanggal wajib diisi!',
            'id_rekening.required' => 'Rekening wajib diisi!',
            'jadwal.required' => 'Jadwal wajib diisi!',
        ]);

        $tanggal = Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d');
        $jadwal = Carbon::createFromFormat('d/m/Y', $request->jadwal)->format('Y-m-d');

        $pemeliharaan = Pemeliharaan::create([
            'id_kendaraan' => $request->id_kendaraan,
            'tanggal_pemeliharaan_sebelumnya' => $tanggal,
            'tanggal_pemeliharaan_berikutnya' => $jadwal,
            'bengkel' => $request->nama_bengkel ?? '-',
            'deskripsi' => $request->deskripsi ?? '-',
            'interval_bulan' => 0,
            'biaya' => $request->biaya ?? 0,
            'id_rekening' => $request->id_rekening
        ]);

        // Rekening::where('id', $request->id_rekening)->decrement('saldo_akhir', $request->biaya);

        // Kurangi saldo rekening
        $rekening = Rekening::findOrFail($request->id_rekening);
        $saldo_akhir = $rekening->saldo_akhir - $request->biaya;
        $rekening->update(['saldo_akhir' => $saldo_akhir]);

        // Simpan transaksi keuangan
        Keuangan::create([
            'id_rekening' => $request->id_rekening,
            'tanggal' => $tanggal,
            'jenis_transaksi' => 'pengeluaran',
            'sumber_transaksi' => 'Pemeliharaan', // Menandakan transaksi dari pemeliharaan
            'id_sumber' => $pemeliharaan->id, // ID dari pemeliharaan
            'nominal' => $request->biaya,
            'saldo_setelah' => $saldo_akhir
        ]);
        return redirect('pemeliharaan/' . $request->slug . '/show')
            ->with('success', 'Data Pemeliharaan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $kendaraan = Kendaraan::where('slug', $slug)->first();
        $rekening = Rekening::all();
        $view_pemeliharaan = Pemeliharaan::where('id_kendaraan', $kendaraan->id)
            ->with('kendaraan')
            ->orderBy('created_at', 'desc') // Urut dari yang terbaru
            ->get();
        $pemeliharaan = Pemeliharaan::where('id_kendaraan', $kendaraan->id)->with('kendaraan', 'rekening')->first();


        return view('pemeliharaan.pemeliharaan-create', compact('pemeliharaan', 'view_pemeliharaan', 'rekening'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pemeliharaan $pemeliharaan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'nama_bengkel' => 'required|string|max:255',
            'biaya' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'tanggal' => 'required',
            'jadwal' => 'required',
        ], [
            'nama_bengkel.required' => 'Nama Bengkel wajib diisi!',
            'biaya.required' => 'Biaya wajib diisi!',
            'biaya.min' => 'Biaya tidak boleh kurang dari 0!',
            'deskripsi.required' => 'Deskripsi wajib diisi!',
            'tanggal.required' => 'Tanggal Pemeliharaan wajib diisi!',
            'jadwal.required' => 'Jadwal wajib diisi!',
        ]);
        DB::beginTransaction(); // Mulai transaksi database

        try {
            // Cari data pemeliharaan yang ingin diupdate
            $pemeliharaan = Pemeliharaan::findOrFail($id);
            $rekening = Rekening::findOrFail($pemeliharaan->id_rekening);

            // Kembalikan biaya lama ke saldo rekening
            $saldo_sementara = $rekening->saldo_akhir + $pemeliharaan->biaya;

            // Cek apakah saldo cukup untuk biaya baru
            if ($saldo_sementara < $request->biaya) {
                return redirect()->back()->with('error', 'Saldo rekening tidak mencukupi!');
            }

            // Update saldo rekening
            $rekening->update(['saldo_akhir' => $saldo_sementara - $request->biaya]);

            // Periksa apakah interval berubah
            if ($request->frekuensi != $pemeliharaan->interval_bulan) {
                $tanggalBerikutnya = date('Y-m-d', strtotime($pemeliharaan->tanggal_pemeliharaan_sebelumnya . " +{$request->frekuensi} months"));
            } else {
                $tanggalBerikutnya = $pemeliharaan->tanggal_pemeliharaan_berikutnya;
            }


            $tanggal = Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d');
            $jadwal = Carbon::createFromFormat('d/m/Y', $request->jadwal)->format('Y-m-d');
            // Update data pemeliharaan
            $pemeliharaan->update([

                'tanggal_pemeliharaan_sebelumnya' => $tanggal,
                'tanggal_pemeliharaan_berikutnya' => $jadwal,
                'bengkel' => $request->nama_bengkel,
                'biaya' => $request->biaya,
                'deskripsi' => $request->deskripsi,
            ]);

            // Update transaksi keuangan jika ada perubahan biaya
            $transaksi = Keuangan::where('id_sumber', $pemeliharaan->id)
                ->where('sumber_transaksi', 'Pemeliharaan')
                ->first();

            if ($transaksi) {
                $transaksi->update([
                    'nominal' => $request->biaya,
                    'tanggal' => $tanggal,
                    'saldo_setelah' => $rekening->saldo_akhir
                ]);
            }

            DB::commit(); // Simpan semua perubahan ke database

            return redirect('pemeliharaan/' . $request->slug . '/show')
                ->with('success', 'Data Pemeliharaan berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua perubahan jika ada error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            // Ambil data pemeliharaan
            $pemeliharaan = Pemeliharaan::findOrFail($id);

            // Ambil data kendaraan
            $kendaraan = Kendaraan::where('id', $pemeliharaan->id_kendaraan)->first();

            // Ambil rekening jika ada
            $rekening = null;
            if ($pemeliharaan->id_rekening) {
                $rekening = Rekening::find($pemeliharaan->id_rekening);
                if ($rekening) {
                    $rekening->saldo_akhir += $pemeliharaan->biaya;
                    $rekening->save();
                }
            }

            // Ambil data keuangan yang terkait
            $keuangan = Keuangan::where('id_sumber', $pemeliharaan->id)
                ->where('sumber_transaksi', 'Pemeliharaan')
                ->first();

            if ($keuangan) {
                $keuangan->delete();
            }

            // Hapus data pemeliharaan
            $pemeliharaan->delete();

            DB::commit();

            return redirect('pemeliharaan/' . $kendaraan->slug . '/show')
                ->with('success', 'Data pemeliharaan berhasil dihapus.' . ($rekening ? ' Saldo rekening diperbarui.' : ''));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('pemeliharaan/' . ($kendaraan->slug ?? '-') . '/show')
                ->with('error', 'Gagal menghapus data pemeliharaan: ' . $e->getMessage());
        }
    }
}
