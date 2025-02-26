@extends('layouts.main')
@section('main')
    @php
        use App\Helpers\FormatHelper;
    @endphp
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dashboard</h1>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-4">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Kendaraan</h5>
                            <h1><strong>{{ $totalKendaraan }} Kendaraan</strong></h1>
                            <ul class="list-group overflow-auto mt-3" style="max-height: 135px;">
                                @foreach ($jumlahKendaraanPerJenis as $jenis => $total)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ ucfirst($jenis) }}
                                        <span class="badge bg-primary rounded-pill">{{ $total }}</span>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Daftar Kendaraan yang Perlu Pemeliharan</h5>

                            <!-- List group With Scrollable -->
                            <ul class="list-group overflow-auto" style="max-height: 300px;">
                                <li class="list-group-item"><i class="bi bi-star me-1 text-success"></i> An item</li>
                                <li class="list-group-item"><i class="bi bi-collection me-1 text-primary"></i> A second item
                                </li>
                                <li class="list-group-item"><i class="bi bi-check-circle me-1 text-danger"></i> A third item
                                </li>
                                <li class="list-group-item"><i class="bi bi-exclamation-octagon me-1 text-warning"></i> A
                                    fourth item</li>
                                <li class="list-group-item"><i class="bi bi-star me-1 text-success"></i> Item 5</li>
                                <li class="list-group-item"><i class="bi bi-collection me-1 text-primary"></i> Item 6</li>
                                <li class="list-group-item"><i class="bi bi-check-circle me-1 text-danger"></i> Item 7</li>
                                <li class="list-group-item"><i class="bi bi-exclamation-octagon me-1 text-warning"></i> Item
                                    8</li>
                            </ul><!-- End List group With Scrollable -->

                        </div>
                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Daftar Kendaraan yang Perlu Membayar Pajak</h5>

                            <!-- List group With Scrollable -->
                            <ul class="list-group overflow-auto" style="max-height: 300px;">
                                @foreach ($pajakTerbaru as $index => $p)
                                    @if ($p['status'] != 'safe')
                                        <a href="{{ $p['route'] }}{{ $p['slug'] }}/show">
                                            <div class="alert alert-{{ $p['status'] }} alert-dismissible fade show" role="alert">
                                                <i class="bi {{ $p['icon'] }} me-1"></i>
                                                {!! $p['peringatan'] !!}
                                            </div>
                                        </a>
                                    @endif

                                @endforeach

                            </ul><!-- End List group With Scrollable -->

                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Biaya Pemeliharaan</h5>
                            <h2><strong>{{ FormatHelper::formatRupiah($totalBiayaPemeliharaan) }}</strong>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card border-danger">
                        <div class="card-body">
                            <h5 class="card-title">Biaya Pengisian BBM</h5>
                            <h2><strong>{{ FormatHelper::formatRupiah($totalBiayaBBM) }}</strong>
                                </h>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Biaya Pajak Tahunan</h5>
                            <h2><strong>{{ FormatHelper::formatRupiah($totalBiayaPajakTahunan) }}</strong>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Biaya Pajak Plat</h5>
                            <h2><strong>{{ FormatHelper::formatRupiah($totalBiayaPajakPlat) }}</strong>
                                </h>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Pengeluaran</h5>
                            <h2><strong>{{ FormatHelper::formatRupiah($totalPengeluaran) }}</strong></h2>
                            <small class="mt-4">Sisa Saldo</small>
                            <ul class="list-group overflow-auto mt-3" style="max-height: 350px;">
                                @foreach ($rekening as $r)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $r->nama_rekening }}
                                        <span>{{ FormatHelper::formatRupiah($r->saldo_akhir) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Chart Pengeluaran</h5>
                            <div id="chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch('/chart-data')
                .then(response => response.json())
                .then(data => {
                    var options = {
                        chart: {
                            type: 'area',
                            height: 350
                        },
                        series: data.series,
                        xaxis: {
                            categories: data.labels
                        },
                        stroke: {
                            curve: 'smooth'
                        },
                        colors: ['#FF5733', '#33B5E5', '#FFC107', '#28A745']
                    };

                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                });
        });
    </script>
@endsection