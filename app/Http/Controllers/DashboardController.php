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

class DashboardController extends Controller
{
    public function index()
    {
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
                        $peringatan = "<strong>$no_plat</strong> Sudah Melewati Jatuh Tempo  <strong>" . abs($hariSisa) . " hari</strong> $jenis_pajak";
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
        return view('dashboard.index', compact('totalBiayaPemeliharaan', 'totalBiayaBBM', 'totalBiayaPajakPlat', 'totalBiayaPajakTahunan', 'totalKendaraan', 'jumlahKendaraanPerJenis', 'totalPengeluaran', 'rekening', 'pajakTerbaru'));
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