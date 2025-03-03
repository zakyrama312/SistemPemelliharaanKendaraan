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
                $query->orderByDesc('tanggal_pemeliharaan_sebelumnya')->limit(1); // Ambil pemeliharaan terbaru
            }
        ])
            ->withCount('pemeliharaan') // Hitung frekuensi pemeliharaan
            ->withSum('pemeliharaan', 'biaya') // Hitung total biaya
            ->get();
        // Tambahkan status berdasarkan tanggal pemeliharaan berikutnya
        $kendaraanData->transform(function ($kendaraan) {
            $tanggalBerikutnya = optional($kendaraan->pemeliharaan->first())->tanggal_pemeliharaan_berikutnya;

            // if ($tanggalBerikutnya) {
            //     $hariIni = now();
            //     $selisihHari = $hariIni->diffInDays($tanggalBerikutnya, false);

            //     // Format "H+X" atau "H-X"
            //     $kendaraan->status_hari = $selisihHari >= 0 ? "H+$selisihHari" : "H$selisihHari";

            //     // Tentukan status berdasarkan selisih hari
            //     if ($selisihHari < 0) {
            //         $kendaraan->status_pemeliharaan = "Sudah lewat jatuh tempo pemeliharaan";
            //         $kendaraan->icon = "bi-exclamation-octagon";
            //         $kendaraan->alert = "alert-danger";
            //     } elseif ($selisihHari <= 5) {
            //         $kendaraan->status_pemeliharaan = "Persiapan memasuki masa pemeliharaan";
            //         $kendaraan->icon = "bi-exclamation-triangle";
            //         $kendaraan->alert = "alert-warning";
            //     } else {
            //         $kendaraan->status_pemeliharaan = "Masih dalam masa aman";
            //         $kendaraan->icon = "bi-check-circle";
            //         $kendaraan->alert = "alert-success";
            //     }
            // } else {
            //     $kendaraan->status_hari = "H-?";
            //     $kendaraan->status_pemeliharaan = "Jadwal pemeliharaan belum tersedia";
            //     $kendaraan->warna_status = "bg-gray-500 text-white";
            // }
            if ($tanggalBerikutnya) {
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
        $kendaraanData = Pemeliharaan::with('kendaraan')->get();
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
        ], [
            'nama_bengkel.required' => 'Nama Bengkel wajib diisi!',
            'biaya.required' => 'Biaya wajib diisi!',
            'biaya.min' => 'Biaya tidak boleh kurang dari 0!',
            'deskripsi.required' => 'Deskripsi wajib diisi!',
        ]);



        $pemeliharaan = Pemeliharaan::create([
            'id_kendaraan' => $request->id_kendaraan,
            'tanggal_pemeliharaan_sebelumnya' => now(),
            'bengkel' => $request->nama_bengkel ?? '-',
            'deskripsi' => $request->deskripsi ?? '-',
            'interval_bulan' => $request->frekuensi,
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
            'tanggal' => now(),
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
        $view_pemeliharaan = Pemeliharaan::where('id_kendaraan', $kendaraan->id)
            ->with('kendaraan', 'rekening')
            ->orderBy('created_at', 'desc') // Urut dari yang terbaru
            ->get();
        $pemeliharaan = Pemeliharaan::where('id_kendaraan', $kendaraan->id)->with('kendaraan', 'rekening')->first();


        return view('pemeliharaan.pemeliharaan-create', compact('pemeliharaan', 'view_pemeliharaan'));
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
            'frekuensi' => 'required|integer|min:1',
        ], [
            'nama_bengkel.required' => 'Nama Bengkel wajib diisi!',
            'biaya.required' => 'Biaya wajib diisi!',
            'biaya.min' => 'Biaya tidak boleh kurang dari 0!',
            'deskripsi.required' => 'Deskripsi wajib diisi!',
            'frekuensi.required' => 'Frekuensi wajib diisi!',
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

            // Update data pemeliharaan
            $pemeliharaan->update([
                'interval_bulan' => $request->frekuensi,
                'tanggal_pemeliharaan_berikutnya' => $tanggalBerikutnya,
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
        // Gunakan transaction untuk memastikan data konsisten
        DB::beginTransaction();

        try {
            // Ambil data pemeliharaan yang akan dihapus
            $pemeliharaan = Pemeliharaan::findOrFail($id);

            // Ambil data kendaraan terkait
            $kendaraan = Kendaraan::where('id', $pemeliharaan->id_kendaraan)->first();

            // Ambil data rekening terkait
            $rekening = Rekening::findOrFail($pemeliharaan->id_rekening);

            // Ambil data keuangan yang terkait dengan pemeliharaan ini
            $keuangan = Keuangan::where('id_sumber', $pemeliharaan->id)
                ->where('sumber_transaksi', 'Pemeliharaan')
                ->first();

            // Jika ada data di keuangan, hapus terlebih dahulu
            if ($keuangan) {
                $keuangan->delete();
            }

            // Kembalikan biaya pemeliharaan ke saldo rekening
            $rekening->saldo_akhir += $pemeliharaan->biaya;
            $rekening->save();

            // Hapus data pemeliharaan
            $pemeliharaan->delete();

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return redirect('pemeliharaan/' . $kendaraan->slug . '/show')
                ->with('success', 'Data pemeliharaan berhasil dihapus dan saldo rekening diperbarui.');
        } catch (\Exception $e) {
            // Rollback jika ada kesalahan
            DB::rollBack();
            return redirect('pemeliharaan/' . $kendaraan->slug . '/show')
                ->with('error', 'Gagal menghapus data pemeliharaan: ' . $e->getMessage());
        }
    }
}