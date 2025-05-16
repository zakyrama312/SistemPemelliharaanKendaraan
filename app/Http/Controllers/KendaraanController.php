<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\User;
use App\Models\Pajak;
use App\Models\Keuangan;
use App\Models\Rekening;
use App\Models\Kendaraan;
use App\Models\Bahanbakar;
use Illuminate\Support\Str;
use App\Models\Pemeliharaan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Requests\UpdateKendaraanRequest;

class KendaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kendaraan = Kendaraan::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('kendaraan.index', compact('kendaraan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::where('role', 'user')->get();
        $rekening = Rekening::all();
        return view('kendaraan.kendaraan-create', compact('user', 'rekening'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'no_polisi' => 'required|string|max:20|unique:kendaraan,no_polisi',
            'kode_barang' => 'required|string',
            'no_register' => 'required|string',
            'nama_barang' => 'required|string',
            'merk' => 'required|string|max:50',
            // 'model' => 'required|string|max:50',
            'warna' => 'required|string|max:30',
            'bahan_bakar' => 'required|string|max:30',
            'jenis' => 'required|in:Mobil,Motor,Truk,Alat Berat',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'tahun_pembuatan' => 'required',
            'masa_aktif_pajak_tahunan' => 'required',
            'masa_aktif_plat' => 'required',
            // 'tanggal_pemeliharaan' => 'required',
            'biaya_pemeliharaan' => 'required|integer',
            'no_rangka' => 'required|string|max:50|unique:kendaraan,no_rangka',
            'no_mesin' => 'required|string|max:50|unique:kendaraan,no_mesin',
            'jumlah_roda' => 'nullable|integer|min:2',
            'bidang' => 'required|string|max:50',
            'id_users' => 'required|exists:users,id',
            'id_rek' => 'required|exists:rekening,id',
            'penanggungjawab' => 'required|string|max:50',
        ], [
            'no_polisi.required' => 'Nomor Polisi wajib diisi.',
            'kode_barang.required' => 'Nomor Kode Barang wajib diisi.',
            'no_register.required' => 'Nomor Register wajib diisi.',
            'nama_barang.required' => 'Nama Barang wajib diisi.',
            'no_polisi.unique' => 'Nomor Polisi sudah digunakan.',
            'merk.required' => 'Merk wajib diisi.',
            // 'model.required' => 'Model wajib diisi.',
            'warna.required' => 'Warna wajib diisi.',
            'bahan_bakar.required' => 'Bahan bakar wajib diisi.',
            'jenis.required' => 'Jenis kendaraan wajib dipilih.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus dalam format jpeg, png, atau jpg.',
            'foto.max' => 'Ukuran foto tidak boleh lebih dari 1MB.',
            'tahun_pembuatan.required' => 'Tahun pembuatan wajib diisi.',
            'masa_aktif_pajak_tahunan.required' => 'Masa aktif pajak tahunan wajib diisi.',
            'masa_aktif_plat.required' => 'Masa aktif plat wajib diisi.',
            // 'tanggal_pemeliharaan.required' => 'Tanggal pemeliharaan wajib diisi.',
            'biaya_pemeliharaan.required' => 'Biaya pemeliharaan sebelumnya wajib diisi.',
            'biaya_pemeliharaan.integer' => 'Biaya pemeliharaan harus berupa angka.',
            'no_rangka.required' => 'Nomor rangka wajib diisi.',
            'no_rangka.unique' => 'Nomor rangka sudah digunakan.',
            'no_mesin.required' => 'Nomor mesin wajib diisi.',
            'no_mesin.unique' => 'Nomor mesin sudah digunakan.',
            'jumlah_roda.integer' => 'Jumlah roda harus berupa angka.',
            'jumlah_roda.min' => 'Jumlah roda minimal 2.',
            'bidang.required' => 'Bidang wajib diisi.',
            'penanggungjawab.required' => 'Penanggung jawab wajib diisi.',
            'id_users.required' => 'Pengguna wajib dipilih.',
            'id_users.exists' => 'Pengguna tidak valid.',
            'id_rek.required' => 'Rekening wajib dipilih.',
            'id_rek.exists' => 'Rekening tidak valid.',
        ]);
        DB::beginTransaction();

        try {


            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '.jpg';

                $manager = new ImageManager(new Driver());
                $image = $manager->read($file)
                    ->scale(width: 800)
                    ->toJpeg(75);

                $image->save(public_path("kendaraanImage/{$filename}"));
                $fotoPath = "{$filename}";
            }

            $tahun_pembuatan = Carbon::createFromFormat('d/m/Y', $request->tahun_pembuatan)->format('Y-m-d');
            $masa_aktif_pajak_tahunan = Carbon::createFromFormat('d/m/Y', $request->masa_aktif_pajak_tahunan)->format('Y-m-d');
            $masa_aktif_plat = Carbon::createFromFormat('d/m/Y', $request->masa_aktif_plat)->format('Y-m-d');

            $kendaraan = Kendaraan::create([
                'id_users' => $request->id_users,
                'kode_barang' => $request->kode_barang,
                'no_register' => $request->no_register,
                'nama_barang' => $request->nama_barang,
                'no_polisi' => $request->no_polisi,
                'slug' => Str::slug($request->no_polisi),
                'merk' => $request->merk,
                'model' => "",
                'jenis' => $request->jenis,
                'foto' => $fotoPath,
                'tahun_pembuatan' => $tahun_pembuatan,
                'masa_aktif_pajak_tahunan' => $masa_aktif_pajak_tahunan,
                'masa_aktif_plat' => $masa_aktif_plat,
                'warna' => $request->warna,
                'no_rangka' => $request->no_rangka,
                'no_mesin' => $request->no_mesin,
                'bahan_bakar' => $request->bahan_bakar,
                'jumlah_roda' => $request->jumlah_roda,
                'bidang' => $request->bidang,
                'status' => 'aktif',
                'id_rekening' => $request->id_rek,
                'penanggung_jawab' => $request->penanggungjawab,
            ]);

            Pajak::create([
                'id_kendaraan' => $kendaraan->id,
                'id_rekening' => $request->id_rek,
                'masa_berlaku' => $kendaraan->masa_aktif_pajak_tahunan,
                'jenis_pajak' => 'pajak_tahunan',
                'nominal' => 0
            ]);

            Pajak::create([
                'id_kendaraan' => $kendaraan->id,
                'id_rekening' => $request->id_rek,
                'masa_berlaku' => $kendaraan->masa_aktif_plat,
                'jenis_pajak' => 'pajak_plat',
                'nominal' => 0
            ]);

            // $tanggal_pemeliharaan = Carbon::createFromFormat('d/m/Y', $request->tanggal_pemeliharaan)->format('Y-m-d');

            $intervalBulan = $kendaraan->interval_bulan ?? 3;
            // $tanggalPemeliharaanBerikutnya = date('Y-m-d', strtotime($tanggal_pemeliharaan . " +{$intervalBulan} months"));

            $pemeliharaan = Pemeliharaan::create([
                'id_kendaraan' => $kendaraan->id,
                'id_rekening' => $request->id_rek,
                // 'tanggal_pemeliharaan_sebelumnya' => now(),
                // 'tanggal_pemeliharaan_berikutnya' => now(),
                'bengkel' => '-',
                'interval_bulan' => 0,
                'deskripsi' => '-',
                'biaya' => 0
            ]);

            // Kurangi saldo rekening
            $rekening = Rekening::findOrFail($request->id_rek);
            $saldo_akhir = $rekening->saldo_akhir - $request->biaya_pemeliharaan;
            $rekening->update(['saldo_akhir' => $saldo_akhir]);
            // Tambahkan transaksi keuangan untuk pengeluaran pemeliharaan
            Keuangan::create([
                'id_rekening' => $request->id_rek,
                'id_sumber' => $pemeliharaan->id,
                'tanggal' => now(),
                'jenis_transaksi' => 'pengeluaran',
                'sumber_transaksi' => 'Pemeliharaan',
                'nominal' => $request->biaya_pemeliharaan,
                'saldo_setelah' => $saldo_akhir
            ]);

            DB::commit();

            return redirect('kendaraan')->with('success', 'Kendaraan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('kendaraan')->with('error', 'Gagal menambahkan kendaraan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function detail(string $slug)
    {
        $kendaraanData = Kendaraan::with([
            'pemeliharaan' => function ($query) {
                $query->orderByDesc('tanggal_pemeliharaan_sebelumnya')->limit(1);
            },
            'pajak' => function ($query) { // Ambil Pajak Plat & Pajak Tahunan
                $query->whereIn('jenis_pajak', ['pajak_plat', 'pajak_tahunan'])
                    ->latest('masa_berlaku');
            },
            'user',
            'pengeluaran_bbm'
        ])
            ->where('slug', $slug)
            ->withCount('pemeliharaan') // Hitung frekuensi pemeliharaan
            ->withCount('pengeluaran_bbm') // Hitung frekuensi pemeliharaan
            ->withSum('pemeliharaan', 'biaya') // Hitung total biaya pemeliharaan
            ->withSum('pengeluaran_bbm', 'nominal') // Hitung total biaya pemeliharaan
            ->withCount([
                'pajak as total_pajak_plat' => function ($query) {
                    $query->where('jenis_pajak', 'pajak_plat');
                },
                'pajak as total_pajak_tahunan' => function ($query) {
                    $query->where('jenis_pajak', 'pajak_tahunan');
                }
            ])
            ->firstOrFail();

        // ===========================
// CEK STATUS PAJAK PLAT
// ===========================
        $pajakPlat = $kendaraanData->pajak->where('jenis_pajak', 'pajak_plat')->sortByDesc('masa_berlaku')->first();

        if ($pajakPlat) {
            $masaBerlakuPlat = Carbon::parse($pajakPlat->masa_berlaku->format('Y-m-d'));
            $hariSisaPlat = ceil(now()->diffInDays($masaBerlakuPlat, false));

            if ($hariSisaPlat > 0 && $hariSisaPlat <= 7) {
                $peringatanPajakPlat = "<strong>$hariSisaPlat hari</strong> lagi segera membayar pajak";
                $statusPajakPlat = 'warning';
                $iconsPlat = 'bi-exclamation-triangle';
            } elseif ($hariSisaPlat < 0) {
                $peringatanPajakPlat = "Sudah Melewati Jatuh Tempo <strong>" . abs($hariSisaPlat) . " hari</strong>";
                $statusPajakPlat = 'danger';
                $iconsPlat = 'bi-exclamation-octagon';
            } else {
                $peringatanPajakPlat = "Aman";
                $statusPajakPlat = 'success';
                $iconsPlat = 'bi-check-circle';
            }

            // Tambahkan ke kendaraanData
            $kendaraanData->masa_berlaku_pajak_plat = $pajakPlat->masa_berlaku;
            $kendaraanData->peringatan_pajak_plat = $peringatanPajakPlat;
            $kendaraanData->status_pajak_plat = $statusPajakPlat;
            $kendaraanData->icons_plat = $iconsPlat;
        } else {
            $kendaraanData->peringatan_pajak_plat = "Tidak ada data pajak plat";
            $kendaraanData->status_pajak_plat = 'unknown';
        }

        // ===========================
        // CEK STATUS PAJAK TAHUNAN
        // ===========================

        $pajakTahunan = $kendaraanData->pajak->where('jenis_pajak', 'pajak_tahunan')->sortByDesc('masa_berlaku')->first();
        if ($pajakTahunan) {
            $masaBerlakuTahunan = Carbon::parse($pajakTahunan->masa_berlaku->format('Y-m-d'));
            $hariSisaTahunan = ceil(now()->diffInDays($masaBerlakuTahunan, false));

            if ($hariSisaTahunan > 0 && $hariSisaTahunan <= 7) {
                $peringatanPajakTahunan = "<strong>$hariSisaTahunan hari</strong> lagi segera membayar pajak";
                $statusPajakTahunan = 'warning';
                $iconsTahunan = 'bi-exclamation-triangle';
            } elseif ($hariSisaTahunan < 0) {
                $peringatanPajakTahunan = "Sudah Melewati Jatuh Tempo <strong>" . abs($hariSisaTahunan) . " hari</strong>";
                $statusPajakTahunan = 'danger';
                $iconsTahunan = 'bi-exclamation-octagon';
            } else {
                $peringatanPajakTahunan = "Aman";
                $statusPajakTahunan = 'success';
                $iconsTahunan = 'bi-check-circle';
            }

            // Tambahkan ke kendaraanData
            $kendaraanData->masa_berlaku_pajak_tahunan = $pajakTahunan->masa_berlaku;
            $kendaraanData->peringatan_pajak_tahunan = $peringatanPajakTahunan;
            $kendaraanData->status_pajak_tahunan = $statusPajakTahunan;
            $kendaraanData->icons_tahunan = $iconsTahunan;
        } else {
            $kendaraanData->peringatan_pajak_tahunan = "Tidak ada data pajak tahunan";
            $kendaraanData->status_pajak_tahunan = 'unknown';
        }

        // ===========================
        // CEK STATUS PEMELIHARAAN
        // ===========================
        $tanggalBerikutnya = optional($kendaraanData->pemeliharaan->first())->tanggal_pemeliharaan_berikutnya;

        if ($tanggalBerikutnya) {
            $hariIni = now()->format('Y-m-d');
            $batasPeringatan = now()->addDays(5)->format('Y-m-d');

            if ($tanggalBerikutnya < $hariIni) {
                $kendaraanData->status_pemeliharaan = "Sudah lewat jatuh tempo pemeliharaan";
                $kendaraanData->icon = "bi-exclamation-octagon";
                $kendaraanData->alert = "alert-danger";
            } elseif ($tanggalBerikutnya <= $batasPeringatan) {
                $kendaraanData->status_pemeliharaan = "Persiapan memasuki masa pemeliharaan";
                $kendaraanData->icon = "bi-exclamation-triangle";
                $kendaraanData->alert = "alert-warning";
            } else {
                $kendaraanData->status_pemeliharaan = "Masih dalam masa aman";
                $kendaraanData->icon = "bi-check-circle";
                $kendaraanData->alert = "alert-success";
            }
        } else {
            $kendaraanData->status_pemeliharaan = "❓ Tidak Ada Jadwal";
        }



        $pemeliharaan = Pemeliharaan::where('id_kendaraan', $kendaraanData->id)
            ->orderBy('created_at', 'desc') // Urutkan dari terbaru ke terlama
            ->limit(3) // Ambil hanya 3 data
            ->get();
        $bbm = Bahanbakar::where('id_kendaraan', $kendaraanData->id)
            ->orderBy('created_at', 'desc') // Urutkan dari terbaru ke terlama
            ->limit(3) // Ambil hanya 3 data
            ->get();

        return view('kendaraan.kendaraan-detail', compact('kendaraanData', 'pemeliharaan', 'bbm'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $rekening = Rekening::all();
        $user = User::where('role', 'user')->get();
        $kendaraan = Kendaraan::where('slug', $slug)->firstOrFail();
        $pemeliharaan = Pemeliharaan::where('id_kendaraan', $kendaraan->id)->firstOrFail();
        ;
        return view('kendaraan.kendaraan-edit', compact('kendaraan', 'user', 'rekening', 'pemeliharaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kendaraan $kendaraan, string $slug)
    {

        $kendaraan = Kendaraan::where('slug', $slug)->firstOrFail();
        $request->validate([
            'kode_barang' => 'required|string',
            'no_register' => 'required|string',
            'nama_barang' => 'required|string',
            'no_polisi' => [
                'required',
                'string',
                'max:20',
                Rule::unique('kendaraan', 'no_polisi')->ignore($kendaraan->id),
            ],
            'merk' => 'required|string|max:50',
            // 'model' => 'required|string|max:50',
            'warna' => 'required|string|max:30',
            'bahan_bakar' => 'required|string|max:30',
            'jenis' => 'required|in:Mobil,Motor,Truk,Alat Berat',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'tahun_pembuatan' => 'required|date',
            'masa_aktif_pajak_tahunan' => 'required|date',
            'masa_aktif_plat' => 'required|date',
            'no_rangka' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kendaraan', 'no_rangka')->ignore($kendaraan->id),
            ],
            'no_mesin' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kendaraan', 'no_mesin')->ignore($kendaraan->id),
            ],
            'jumlah_roda' => 'nullable|integer|min:2',
            'bidang' => 'required|string|max:50',
            'id_users' => 'required|exists:users,id',
            'id_rek' => 'required|exists:rekening,id',
            'status' => 'required|in:aktif,nonaktif',
            'penanggung_jawab' => 'required|string|max:50',
        ], [
            'kode_barang.required' => 'Nomor Kode Barang wajib diisi.',
            'no_register.required' => 'Nomor Register wajib diisi.',
            'nama_barang.required' => 'Nama Barang wajib diisi.',
            'no_polisi.required' => 'Nomor Polisi wajib diisi.',
            'no_polisi.unique' => 'Nomor Polisi sudah digunakan.',
            'merk.required' => 'Merk wajib diisi.',
            // 'model.required' => 'Model wajib diisi.',
            'warna.required' => 'Warna wajib diisi.',
            'bahan_bakar.required' => 'Bahan bakar wajib diisi.',
            'jenis.required' => 'Jenis kendaraan wajib dipilih.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus dalam format jpeg, png, atau jpg.',
            'foto.max' => 'Ukuran foto tidak boleh lebih dari 1MB.',
            'tahun_pembuatan.required' => 'Tahun pembuatan wajib diisi.',
            'masa_aktif_pajak_tahunan.required' => 'Masa aktif pajak tahunan wajib diisi.',
            'masa_aktif_plat.required' => 'Masa aktif plat wajib diisi.',
            'no_rangka.required' => 'Nomor rangka wajib diisi.',
            'no_rangka.unique' => 'Nomor rangka sudah digunakan.',
            'no_mesin.required' => 'Nomor mesin wajib diisi.',
            'no_mesin.unique' => 'Nomor mesin sudah digunakan.',
            'jumlah_roda.integer' => 'Jumlah roda harus berupa angka.',
            'jumlah_roda.min' => 'Jumlah roda minimal 2.',
            'bidang.required' => 'Bidang wajib diisi.',
            'penanggung_jawab.required' => 'Penanggung jawab wajib diisi.',
            'id_users.required' => 'Pengguna wajib dipilih.',
            'id_users.exists' => 'Pengguna tidak valid.',
            'id_rek.required' => 'Rekening wajib dipilih.',
            'status.required' => 'Status wajib dipilih.',
            'id_rek.exists' => 'Rekening tidak valid.',
        ]);
        $fotoPath = $kendaraan->foto;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '.jpg';

            // Hapus foto lama jika ada
            if ($kendaraan->foto && file_exists(public_path('kendaraanImage/' . $kendaraan->foto))) {
                unlink(public_path('kendaraanImage/' . $kendaraan->foto));
            }
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file)
                ->scale(width: 800)
                ->toJpeg(75);

            $image->save(public_path("kendaraanImage/{$filename}"));
            $fotoPath = "{$filename}";
        }


        Kendaraan::where('id', $kendaraan->id)->update([
            'id_users' => $request->id_users,
            'kode_barang' => $request->kode_barang,
            'no_register' => $request->no_register,
            'nama_barang' => $request->nama_barang,
            'no_polisi' => $request->no_polisi,
            'slug' => Str::slug($request->no_polisi),
            'merk' => $request->merk,
            // 'model' => $request->model,
            'jenis' => $request->jenis,
            'foto' => $fotoPath,
            'tahun_pembuatan' => $request->tahun_pembuatan,
            'masa_aktif_pajak_tahunan' => $request->masa_aktif_pajak_tahunan,
            'masa_aktif_plat' => $request->masa_aktif_plat,
            'warna' => $request->warna,
            'no_rangka' => $request->no_rangka,
            'no_mesin' => $request->no_mesin,
            'bahan_bakar' => $request->bahan_bakar,
            'jumlah_roda' => $request->jumlah_roda,
            'bidang' => $request->bidang,
            'penanggung_jawab' => $request->penanggung_jawab,
            'id_rekening' => $request->id_rek,
            'status' => $request->status,
        ]);

        // Update atau insert pajak tahunan
        Pajak::updateOrCreate(
            ['id_kendaraan' => $kendaraan->id, 'jenis_pajak' => 'pajak_tahunan'],
            [
                'id_rekening' => $request->id_rek,
                'masa_berlaku' => $kendaraan->masa_aktif_pajak_tahunan,
            ]
        );

        // Update atau insert pajak plat
        Pajak::updateOrCreate(
            ['id_kendaraan' => $kendaraan->id, 'jenis_pajak' => 'pajak_plat'],
            [
                'id_rekening' => $request->id_rek,
                'masa_berlaku' => $kendaraan->masa_aktif_plat,
            ]
        );

        Pemeliharaan::where('id_kendaraan', $kendaraan->id)->update([
            'id_kendaraan' => $kendaraan->id,
            'id_rekening' => $request->id_rek,
        ]);
        Bahanbakar::where('id_kendaraan', $kendaraan->id)->update([
            'id_kendaraan' => $kendaraan->id,
            'id_rekening' => $request->id_rek,
        ]);

        return redirect('kendaraan')->with('success', 'Kendaraan berhasil diedit.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        // $kendaraanslug = Kendaraan::where('slug', $slug)->firstOrFail();
        DB::beginTransaction();

        try {
            // Ambil kendaraan yang akan dihapus
            $kendaraan = Kendaraan::where('slug', $slug)->firstOrFail();
            $id = $kendaraan->id;

            // Ambil semua pajak, pemeliharaan, dan pengeluaran BBM terkait kendaraan ini
            // $pajaks = Pajak::where('id_kendaraan', $id)->get();
            $pemeliharaans = Pemeliharaan::where('id_kendaraan', $id)->get();
            $pengeluaranBBMs = Bahanbakar::where('id_kendaraan', $id)->get();

            // Proses pengembalian saldo ke rekening terkait
            // foreach ($pajaks as $pajak) {
            //     $rekening = Rekening::find($pajak->id_rekening);
            //     if ($rekening) {
            //         $rekening->saldo_akhir += $pajak->nominal;
            //         $rekening->save();
            //     }
            // }

            foreach ($pemeliharaans as $pemeliharaan) {
                $rekening = Rekening::find($pemeliharaan->id_rekening);
                if ($rekening) {
                    $rekening->saldo_akhir += $pemeliharaan->biaya;
                    $rekening->save();
                }
            }

            foreach ($pengeluaranBBMs as $pengeluaranBBM) {
                $rekening = Rekening::find($pengeluaranBBM->id_rekening);
                if ($rekening) {
                    $rekening->saldo_akhir += $pengeluaranBBM->nominal;
                    $rekening->save();
                }
            }

            // Hapus semua data terkait sebelum menghapus kendaraan
            // Pajak::where('id_kendaraan', $id)->delete();
            Pemeliharaan::where('id_kendaraan', $id)->delete();
            Bahanbakar::where('id_kendaraan', $id)->delete();

            // Hapus kendaraan setelah semua data terkait dihapus
            $kendaraan->delete();

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return redirect('kendaraan')->with('success', 'Kendaraan dan semua data terkait berhasil dihapus, saldo rekening telah diperbarui.');
        } catch (\Exception $e) {
            // Rollback jika ada kesalahan
            DB::rollBack();
            return redirect('kendaraan')->with('success', 'Gagal menghapus kendaraan: ' . $e->getMessage());
        }
    }
}