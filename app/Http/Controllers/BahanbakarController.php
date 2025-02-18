<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Bahanbakar;
use Illuminate\Http\Request;

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Bahanbakar $bahanbakar)
    {
        //
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
    public function update(Request $request, Bahanbakar $bahanbakar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bahanbakar $bahanbakar)
    {
        //
    }
}