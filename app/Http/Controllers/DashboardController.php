<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pajak;
use App\Models\Keuangan;
use App\Models\Rekening;
use App\Models\Kendaraan;
use App\Models\Bahanbakar;
use App\Models\Pemeliharaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user();
        if ($role->role == 'admin') {

            $rekening = Rekening::all();
            $totalBiayaPemeliharaan = Pemeliharaan::sum('biaya');
            $totalBiayaBBM = Bahanbakar::sum('nominal');
            $totalBiayaPajakPlat = Pajak::where('jenis_pajak', 'pajak_plat')->sum('nominal');
            $totalBiayaPajakTahunan = Pajak::where('jenis_pajak', 'pajak_tahunan')->sum('nominal');

            $totalPengeluaran = $totalBiayaPemeliharaan + $totalBiayaBBM + $totalBiayaPajakPlat + $totalBiayaPajakTahunan;
            $totalKendaraan = Kendaraan::count(); // Hitung total kendaraan
            $jumlahKendaraanPerJenis = Kendaraan::selectRaw('jenis, COUNT(*) as total')
                ->groupBy('jenis')
                ->pluck('total', 'jenis')
                ->toArray();


            $pajakTerbaru = Kendaraan::with([
                'pajak' => function ($query) {
                    $query->whereIn('jenis_pajak', ['pajak_plat', 'pajak_tahunan'])
                        ->whereIn('id', function ($subquery) {
                            $subquery->select(DB::raw('MAX(id)'))
                                ->from('pajak')
                                ->whereIn('jenis_pajak', ['pajak_plat', 'pajak_tahunan'])
                                ->groupBy('id_kendaraan', 'jenis_pajak');
                        });
                }
            ])->get()->flatMap(function ($kendaraan) {
                return $kendaraan->pajak->map(function ($pajak) use ($kendaraan) {
                    if (!$pajak->masa_berlaku) {
                        $peringatan = null;
                        $status = 'safe';
                    } else {

                        $masaBerlaku = Carbon::parse($pajak->masa_berlaku->format('Y-m-d'));
                        $hariSisa = ceil(now()->diffInDays($masaBerlaku, false));

                        $jenis_pajak = $pajak->jenis_pajak == 'pajak_tahunan' ? 'Pajak Tahunan' : 'Pajak Plat';
                        $routes = $pajak->jenis_pajak == 'pajak_tahunan' ? 'pajak-tahunan/' : 'pajak-plat/';
                        $no_plat = $kendaraan->no_polisi . '-' . $kendaraan->model;

                        if ($hariSisa > 0 && $hariSisa <= 7) {
                            $peringatan = "<strong>$no_plat $hariSisa hari</strong> lagi segera membayar ";
                            $status = 'warning';
                            $icon = 'bi-exclamation-triangle';
                            $route = $routes;
                        } elseif ($hariSisa < 0) {
                            $peringatan = "<strong>$no_plat</strong> Sudah Jatuh Tempo  <strong>" . abs($hariSisa) . " hari</strong> $jenis_pajak";
                            $status = 'danger';
                            $icon = 'bi-exclamation-octagon';
                            $route = $routes;
                        } else {
                            $peringatan = "Aman";
                            $status = 'safe';
                            $icon = 'bi-check-circle';
                            $route = '-';
                        }
                    }

                    return [
                        'id' => $kendaraan->id,
                        'slug' => $kendaraan->slug,
                        'nomor_polisi' => $kendaraan->no_polisi,
                        'merk' => $kendaraan->merk,
                        'model' => $kendaraan->model,
                        'masa_berlaku' => $pajak->masa_berlaku,
                        'peringatan' => $peringatan,
                        'status' => $status,
                        'icon' => $icon,
                        'route' => $route,
                    ];
                });
            });
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
                    $hariIni = now();
                    $batasPeringatan = (now()->addDays(5));
                    $masaBerlaku = Carbon::parse($tanggalBerikutnya);
                    $hariSisa = ceil(now()->diffInDays($masaBerlaku, false)); // Menghitung selisih hari dengan negatif jika terlambat

                    if ($hariSisa < 0) {
                        $no_plat = $kendaraan->no_polisi . '-' . $kendaraan->model;
                        $kendaraan->status_pemeliharaan = "<strong>$no_plat</strong> Sudah lewat jatuh tempo pemeliharaan <strong>" . abs($hariSisa) . " hari</strong>";
                        $kendaraan->icon = "bi-exclamation-octagon";
                        $kendaraan->alert = "alert-danger";
                    } elseif ($hariSisa <= 5) {
                        $no_plat = $kendaraan->no_polisi . '-' . $kendaraan->model;
                        $kendaraan->status_pemeliharaan = "<strong>$no_plat $hariSisa hari</strong> lagi segera servis";
                        $kendaraan->icon = "bi-exclamation-triangle";
                        $kendaraan->alert = "alert-warning";
                    } else {
                        $no_plat = $kendaraan->no_polisi . '-' . $kendaraan->model;
                        $kendaraan->status_pemeliharaan = "✅ Aman";
                        $kendaraan->icon = "bi-exclamation-triangle";
                        $kendaraan->alert = "alert-success";
                    }
                } else {
                    $kendaraan->status_pemeliharaan = "❓ Tidak Ada Jadwal";
                }

                return $kendaraan;
            });


            return view('dashboard.index', compact('totalBiayaPemeliharaan', 'totalBiayaBBM', 'totalBiayaPajakPlat', 'totalBiayaPajakTahunan', 'totalKendaraan', 'jumlahKendaraanPerJenis', 'totalPengeluaran', 'rekening', 'pajakTerbaru', 'kendaraanData'));

        } elseif ($role->role == 'user') {
            $id_user = Auth::user()->id;
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
                ->where('id_users', $id_user)
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
            return view('dashboard.index', compact('kendaraanData', 'pemeliharaan', 'bbm'));
        }
    }


    public function getChartData()
    {
        // Ambil data pemeliharaan, pengeluaran BBM, dan pajak dalam 6 bulan terakhir
        $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']; // Bisa dibuat dinamis

        $keuangan = Keuangan::select(
            DB::raw('MONTH(tanggal) as bulan'),
            'sumber_transaksi',
            DB::raw('SUM(nominal) as total')
        )
            ->where('jenis_transaksi', 'pengeluaran') // Hanya ambil pengeluaran
            ->whereYear('tanggal', Carbon::now()->year)
            ->groupBy('bulan', 'sumber_transaksi')
            ->orderBy('bulan')
            ->get();

        // Siapkan array kosong untuk menyimpan data
        $dataPemeliharaan = array_fill(1, 6, 0);
        $dataBBM = array_fill(1, 6, 0);
        $dataPajakPlat = array_fill(1, 6, 0);
        $dataPajakTahunan = array_fill(1, 6, 0);

        // Isi data berdasarkan sumber_transaksi
        foreach ($keuangan as $item) {
            switch ($item->sumber_transaksi) {
                case 'Pemeliharaan':
                    $dataPemeliharaan[$item->bulan] = $item->total;
                    break;
                case 'Pengeluaran BBM':
                    $dataBBM[$item->bulan] = $item->total;
                    break;
                case 'Pajak Plat':
                    $dataPajakPlat[$item->bulan] = $item->total;
                    break;
                case 'Pajak Tahunan':
                    $dataPajakTahunan[$item->bulan] = $item->total;
                    break;
            }
        }

        // Format data untuk chart
        $data = [
            'labels' => $bulan,
            'series' => [
                [
                    'name' => 'Pemeliharaan',
                    'data' => array_values($dataPemeliharaan)
                ],
                [
                    'name' => 'BBM',
                    'data' => array_values($dataBBM)
                ],
                [
                    'name' => 'Pajak Plat',
                    'data' => array_values($dataPajakPlat)
                ],
                [
                    'name' => 'Pajak Tahunan',
                    'data' => array_values($dataPajakTahunan)
                ],
            ]
        ];

        return response()->json($data);

        // Ambil data unik bulan & tahun dari transaksi keuangan
        // $bulanTahun = Keuangan::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan_tahun")
        //     ->distinct()
        //     ->orderBy('bulan_tahun')
        //     ->pluck('bulan_tahun')
        //     ->toArray();

        // // Siapkan array untuk menyimpan total per sumber_transaksi
        // $dataPemeliharaan = array_fill_keys($bulanTahun, 0);
        // $dataBBM = array_fill_keys($bulanTahun, 0);
        // $dataPajakPlat = array_fill_keys($bulanTahun, 0);
        // $dataPajakTahunan = array_fill_keys($bulanTahun, 0);

        // // Ambil total transaksi per bulan dan sumber_transaksi
        // $keuangan = Keuangan::selectRaw("
        //     DATE_FORMAT(tanggal, '%Y-%m') as bulan_tahun, 
        //     sumber_transaksi, 
        //     SUM(nominal) as total
        // ")
        //     ->where('jenis_transaksi', 'pengeluaran') // Hanya pengeluaran
        //     ->groupBy('bulan_tahun', 'sumber_transaksi')
        //     ->orderBy('bulan_tahun')
        //     ->get();

        // // Masukkan data sesuai sumber_transaksi
        // foreach ($keuangan as $item) {
        //     switch ($item->sumber_transaksi) {
        //         case 'Pemeliharaan':
        //             $dataPemeliharaan[$item->bulan_tahun] = $item->total;
        //             break;
        //         case 'Pengeluaran BBM':
        //             $dataBBM[$item->bulan_tahun] = $item->total;
        //             break;
        //         case 'Pajak Plat':
        //             $dataPajakPlat[$item->bulan_tahun] = $item->total;
        //             break;
        //         case 'Pajak Tahunan':
        //             $dataPajakTahunan[$item->bulan_tahun] = $item->total;
        //             break;
        //     }
        // }

        // // Format data untuk chart
        // $data = [
        //     'labels' => array_keys($dataPemeliharaan), // Format YYYY-MM
        //     'series' => [
        //         [
        //             'name' => 'Pemeliharaan',
        //             'data' => array_values($dataPemeliharaan)
        //         ],
        //         [
        //             'name' => 'BBM',
        //             'data' => array_values($dataBBM)
        //         ],
        //         [
        //             'name' => 'Pajak Plat',
        //             'data' => array_values($dataPajakPlat)
        //         ],
        //         [
        //             'name' => 'Pajak Tahunan',
        //             'data' => array_values($dataPajakTahunan)
        //         ],
        //     ]
        // ];

        // return response()->json($data);
    }


}