<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    public function index()
    {

        $keuangan = DB::query()
            ->fromSub(
                DB::table('keuangan')
                    ->join('pajak', function ($join) {
                        $join->on('keuangan.id_sumber', '=', 'pajak.id')
                            ->whereIn('keuangan.sumber_transaksi', ['Pajak Tahunan', 'Pajak Plat']);
                    })
                    ->join('kendaraan', 'pajak.id_kendaraan', '=', 'kendaraan.id')
                    ->select('keuangan.*', 'kendaraan.no_polisi', 'kendaraan.merk', 'kendaraan.model')

                    ->union(
                        DB::table('keuangan')
                            ->join('pengeluaran_bbm', function ($join) {
                                $join->on('keuangan.id_sumber', '=', 'pengeluaran_bbm.id')
                                    ->where('keuangan.sumber_transaksi', '=', 'Pengeluaran BBM');
                            })
                            ->join('kendaraan', 'pengeluaran_bbm.id_kendaraan', '=', 'kendaraan.id')
                            ->select('keuangan.*', 'kendaraan.no_polisi', 'kendaraan.merk', 'kendaraan.model')
                    )

                    ->union(
                        DB::table('keuangan')
                            ->join('pemeliharaan', function ($join) {
                                $join->on('keuangan.id_sumber', '=', 'pemeliharaan.id')
                                    ->where('keuangan.sumber_transaksi', '=', 'Pemeliharaan');
                            })
                            ->join('kendaraan', 'pemeliharaan.id_kendaraan', '=', 'kendaraan.id')
                            ->select('keuangan.*', 'kendaraan.no_polisi', 'kendaraan.merk', 'kendaraan.model')
                    ),
                'keuangan_union'
            )
            ->orderBy('keuangan_union.created_at', 'desc')
            ->where('nominal', '!=', 0)
            ->get();

        return view('pengeluaran.index', compact('keuangan'));
    }
}
