<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
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
        // $pemeliharaan = Pemeliharaan::with('kendaraan', 'rekening')->get();
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

        return view('pemeliharaan.index', compact('kendaraanData'));
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
            'nama_bengkel' => 'required|string|max:255',
            'biaya' => 'required|min:0',
        ], [
            'nama_bengkel.required' => 'Nama Bengkel wajib diisi!',
            'biaya.required' => 'Biaya wajib diisi!',
            'biaya.min' => 'Biaya tidak boleh kurang dari 0!',
            'deskripsi.required' => 'Deskripsi wajib diisi!',
        ]);


        Pemeliharaan::create([
            'id_kendaraan' => $request->id_kendaraan,
            'tanggal_pemeliharaan_sebelumnya' => now(),
            'bengkel' => $request->bengkel ?? '-',
            'deskripsi' => $request->deskripsi ?? '-',
            'biaya' => $request->biaya ?? 0, // Jika biaya tidak diisi, default 0
            'id_rekening' => $request->id_rekening
        ]);

        Rekening::where('id', $request->id_rekening)->decrement('saldo_akhir', $request->biaya);

        return redirect('pemeliharaan')->with('success', 'Data Pemeliharan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $kendaraan = Kendaraan::where('slug', $slug)->first();
        $view_pemeliharaan = Pemeliharaan::where('id_kendaraan', $kendaraan->id)->with('kendaraan', 'rekening')->get();
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
    public function update(Request $request, Pemeliharaan $pemeliharaan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pemeliharaan $pemeliharaan)
    {
        //
    }
}