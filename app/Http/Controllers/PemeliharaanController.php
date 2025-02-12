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
        $pemeliharaan = Pemeliharaan::with('kendaraan', 'rekening')->get();
        // $tanggal_sekarang = Carbon::now();
        // $tanggal_reminder = $tanggal_sekarang->addWeek();

        // $pemeliharaan = Pemeliharaan::select(
        //     'id_kendaraan',
        //     'tanggal_pemeliharaan',
        //     'frekuensi_bulan',
        //     'bengkel',
        //     'deskripsi',
        //     'biaya',
        //     DB::raw('(tanggal_pemeliharaan + INTERVAL frekuensi_bulan MONTH) as jadwal_pemeliharaan')
        // )
        //     ->whereRaw('(tanggal_pemeliharaan + INTERVAL frekuensi_bulan MONTH) BETWEEN NOW() AND ?', [$tanggal_reminder])
        //     ->orderBy('id_kendaraan', 'desc')
        //     ->get();
        // $pemeliharaan = Pemeliharaan::select(
        //     'id_kendaraan',
        //     'tanggal_pemeliharaan',
        //     'frekuensi_bulan',
        //     'bengkel',
        //     'deskripsi',
        //     'biaya',
        //     DB::raw('(tanggal_pemeliharaan + INTERVAL frekuensi_bulan MONTH) as jadwal_pemeliharaan'),
        //     DB::raw('(SELECT SUM(biaya) FROM pemeliharaan WHERE pemeliharaan.id_kendaraan = p.id_kendaraan) as total_biaya')
        // )
        //     ->from('pemeliharaan as p')
        //     ->whereRaw('(tanggal_pemeliharaan + INTERVAL frekuensi_bulan MONTH) BETWEEN NOW() AND ?', [$tanggal_reminder])
        //     ->orderBy('id_kendaraan', 'desc')
        //     ->get();

        return view('pemeliharaan.index', compact('pemeliharaan'));
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
            'tanggal_pemeliharaan' => now(),
            'bengkel' => $request->nama_bengkel,
            'deskripsi' => $request->deskripsi,
            'biaya' => $request->biaya,
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