<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Pajak;
use App\Models\Rekening;
use App\Models\Kendaraan;
use App\Models\Pemeliharaan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Requests\UpdateKendaraanRequest;
use App\Models\Bahanbakar;

class KendaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kendaraan = Kendaraan::with('user')->get();
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
            'merk' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'warna' => 'required|string|max:30',
            'bahan_bakar' => 'required|string|max:30',
            'jenis' => 'required|in:Mobil,Motor,Truk,Alat Berat',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'tahun_pembuatan' => 'required|date',
            'masa_aktif_pajak_tahunan' => 'required|date',
            'masa_aktif_plat' => 'required|date',
            'tanggal_pemeliharaan' => 'required|date',
            'biaya_pemeliharaan' => 'required|integer',
            'no_rangka' => 'required|string|max:50|unique:kendaraan,no_rangka',
            'no_mesin' => 'required|string|max:50|unique:kendaraan,no_mesin',
            'jumlah_roda' => 'nullable|integer|min:2',
            'bidang' => 'required|string|max:50',
            'id_users' => 'required|exists:users,id',
            'id_rek' => 'required|exists:rekening,id',
        ], [
            'no_polisi.required' => 'Nomor Polisi wajib diisi.',
            'no_polisi.unique' => 'Nomor Polisi sudah digunakan.',
            'merk.required' => 'Merk wajib diisi.',
            'model.required' => 'Model wajib diisi.',
            'warna.required' => 'Warna wajib diisi.',
            'bahan_bakar.required' => 'Bahan bakar wajib diisi.',
            'jenis.required' => 'Jenis kendaraan wajib dipilih.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus dalam format jpeg, png, atau jpg.',
            'foto.max' => 'Ukuran foto tidak boleh lebih dari 1MB.',
            'tahun_pembuatan.required' => 'Tahun pembuatan wajib diisi.',
            'masa_aktif_pajak_tahunan.required' => 'Masa aktif pajak tahunan wajib diisi.',
            'masa_aktif_plat.required' => 'Masa aktif plat wajib diisi.',
            'tanggal_pemeliharaan.required' => 'Tanggal pemeliharaan wajib diisi.',
            'biaya_pemeliharaan.required' => 'Biaya pemeliharaan sebelumnya wajib diisi.',
            'biaya_pemeliharaan.integer' => 'Biaya pemeliharaan harus berupa angka.',
            'no_rangka.required' => 'Nomor rangka wajib diisi.',
            'no_rangka.unique' => 'Nomor rangka sudah digunakan.',
            'no_mesin.required' => 'Nomor mesin wajib diisi.',
            'no_mesin.unique' => 'Nomor mesin sudah digunakan.',
            'jumlah_roda.integer' => 'Jumlah roda harus berupa angka.',
            'jumlah_roda.min' => 'Jumlah roda minimal 2.',
            'bidang.required' => 'Bidang wajib diisi.',
            'id_users.required' => 'Pengguna wajib dipilih.',
            'id_users.exists' => 'Pengguna tidak valid.',
            'id_rek.required' => 'Rekening wajib dipilih.',
            'id_rek.exists' => 'Rekening tidak valid.',
        ]);
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

        $kendaraan = Kendaraan::create([
            'id_users' => $request->id_users,
            'no_polisi' => $request->no_polisi,
            'slug' => Str::slug($request->no_polisi),
            'merk' => $request->merk,
            'model' => $request->model,
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
            'status' => 'aktif',
            'id_rekening' => $request->id_rek
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

        // Ambil interval bulan dari kendaraan (default 3 bulan jika null)
        $intervalBulan = $kendaraan->interval_bulan ?? 3;

        // Hitung tanggal pemeliharaan berikutnya
        $tanggalPemeliharaanBerikutnya = date('Y-m-d', strtotime($request->tanggal_pemeliharaan . " +{$intervalBulan} months"));

        Pemeliharaan::create([
            'id_kendaraan' => $kendaraan->id,
            'id_rekening' => $request->id_rek,
            'tanggal_pemeliharaan_sebelumnya' => $request->tanggal_pemeliharaan,
            'tanggal_pemeliharaan_berikutnya' => $tanggalPemeliharaanBerikutnya,
            'bengkel' => '-',
            'interval_bulan' => 3,
            'deskripsi' => '-',
            'biaya' => $request->biaya_pemeliharaan
        ]);

        Rekening::where('id', $request->id_rek)->update([
            'saldo_akhir' => DB::raw('saldo_akhir - ' . $request->biaya_pemeliharaan)
        ]);

        return redirect('kendaraan')->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function detail(string $slug)
    {
        $kendaraanData = Kendaraan::with([
            'pemeliharaan' => function ($query) {
                $query->orderByDesc('tanggal_pemeliharaan_sebelumnya')->limit(1); // Ambil pemeliharaan terbaru
            }
        ], 'user')
            ->where('slug', $slug)
            ->withCount('pemeliharaan') // Hitung frekuensi pemeliharaan
            ->withSum('pemeliharaan', 'biaya') // Hitung total biaya
            ->firstOrFail();

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

        return view('kendaraan.kendaraan-detail', compact('kendaraanData', 'pemeliharaan'));
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
            'no_polisi' => [
                'required',
                'string',
                'max:20',
                Rule::unique('kendaraan', 'no_polisi')->ignore($kendaraan->id),
            ],
            'merk' => 'required|string|max:50',
            'model' => 'required|string|max:50',
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
        ], [
            'no_polisi.required' => 'Nomor Polisi wajib diisi.',
            'no_polisi.unique' => 'Nomor Polisi sudah digunakan.',
            'merk.required' => 'Merk wajib diisi.',
            'model.required' => 'Model wajib diisi.',
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
            'id_users.required' => 'Pengguna wajib dipilih.',
            'id_users.exists' => 'Pengguna tidak valid.',
            'id_rek.required' => 'Rekening wajib dipilih.',
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
            'no_polisi' => $request->no_polisi,
            'slug' => Str::slug($request->no_polisi),
            'merk' => $request->merk,
            'model' => $request->model,
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
            'status' => 'aktif',
            'id_rekening' => $request->id_rek,
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