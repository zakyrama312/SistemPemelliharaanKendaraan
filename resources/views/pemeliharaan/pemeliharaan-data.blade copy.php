@extends('layouts.main')

@section('main')
@php
use App\Helpers\FormatHelper;
use Carbon\Carbon;
@endphp

{{-- CSS untuk kartu summary --}}
<style>
    .summary-card-container {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .summary-card {
        flex: 1;
        padding: 1.5rem;
        border-radius: 8px;
        color: #fff;
        min-width: 200px;
    }

    .summary-card h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: normal;
        color: rgba(255, 255, 255, 0.8);
    }

    .summary-card p {
        margin: 0.5rem 0 0;
        font-size: 1.75rem;
        font-weight: bold;
    }

    .bg-periode-a {
        background-color: #0d6efd;
    }

    .bg-periode-b {
        background-color: #6c757d;
    }

    .bg-perbedaan {
        background-color: #198754;
    }

    .bg-perbedaan.minus {
        background-color: #dc3545;
    }

    /* Style untuk pesan data kosong */
    .empty-data-message {
        text-align: center;
        padding: 3rem 1rem;
        background-color: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        margin: 1rem 0;
    }

    .empty-data-message i {
        font-size: 3rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }

    .empty-data-message h5 {
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .empty-data-message p {
        color: #6c757d;
        margin: 0;
    }
</style>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Laporan Pemeliharaan Kendaraan</h1>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Filter Laporan</h5>

                        {{-- FORM FILTER BARU --}}
                        <form action="{{ route('pemeliharaan.data') }}" method="GET" id="filterForm">
                            <div class="row align-items-end mb-3">
                                <div class="col-md-3">
                                    <label for="mode" class="form-label">Pilih Jenis Laporan</label>
                                    <select class="form-select" id="mode" name="mode">
                                        <option value="periode" @if($mode=='periode' ) selected @endif>Periode Tertentu</option>
                                        <option value="compare_month" @if($mode=='compare_month' ) selected @endif>Perbandingan Bulan ke Bulan</option>
                                        <option value="compare_year" @if($mode=='compare_year' ) selected @endif>Perbandingan Bulan di Tahun Berbeda</option>
                                    </select>
                                </div>

                                {{-- Filter untuk Periode Tertentu --}}
                                <div id="filter-periode" class="col-md-6 row">
                                    <div class="col-md-6">
                                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                                    </div>
                                </div>

                                {{-- Filter untuk Perbandingan Bulan ke Bulan --}}
                                <div id="filter-compare-month" class="col-md-4 row">
                                    <div class="col-md-7">
                                        <label for="month_cm" class="form-label">Pilih Bulan</label>
                                        <select class="form-select" name="month" id="month_cm">
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ request('month', date('m')) == $i ? 'selected' : '' }}>{{ Carbon::create()->month($i)->isoFormat('MMMM') }}</option>
                                                @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="year_cm" class="form-label">Tahun</label>
                                        <input type="number" name="year" id="year_cm" class="form-control" value="{{ request('year', date('Y')) }}" placeholder="Tahun">
                                    </div>
                                </div>

                                {{-- Filter untuk Perbandingan Tahun --}}
                                <div id="filter-compare-year" class="col-md-6 row">
                                    <div class="col-md-4">
                                        <label for="month_cy" class="form-label">Pilih Bulan</label>
                                        <select class="form-select" name="month_cy" id="month_cy">
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ request('month', date('m')) == $i ? 'selected' : '' }}>{{ Carbon::create()->month($i)->isoFormat('MMMM') }}</option>
                                                @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="year1_cy" class="form-label">Tahun 1</label>
                                        <input type="number" name="year_cy" id="year1_cy" class="form-control" value="{{ request('year', date('Y')) }}" placeholder="Tahun 1">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="year2_cy" class="form-label">Tahun 2</label>
                                        <input type="number" name="year2_cy" id="year2_cy" class="form-control" value="{{ request('year2', date('Y')-1) }}" placeholder="Tahun 2">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                                </div>
                            </div>
                        </form>

                        <hr>

                        {{-- TAMPILAN HASIL --}}
                        @if ($mode === 'compare_month' || $mode === 'compare_year')
                        {{-- CEK APAKAH ADA DATA UNTUK PERBANDINGAN --}}
                        @if($dataA->isEmpty() && $dataB->isEmpty())
                        {{-- PESAN KETIKA KEDUA DATA KOSONG --}}
                        <div class="empty-data-message">
                            <i class="bi bi-inbox"></i>
                            <h5>Tidak Ada Data Pemeliharaan</h5>
                            <p>Tidak ditemukan data pemeliharaan untuk periode {{ $labelA }} dan {{ $labelB }}.</p>
                            <p class="mt-2"><small>Silakan pilih periode lain atau pastikan data sudah diinput dengan benar.</small></p>
                        </div>
                        @else
                        {{-- BAGIAN RINGKASAN PERBANDINGAN --}}
                        <h5 class="card-title">Ringkasan Perbandingan: {{ $labelA }} vs {{ $labelB }}</h5>
                        @php
                        $diff_biaya = $summaryA['total_biaya'] - $summaryB['total_biaya'];
                        $diff_servis = $summaryA['jumlah_servis'] - $summaryB['jumlah_servis'];
                        @endphp
                        <div class="summary-card-container">
                            <div class="summary-card bg-periode-a">
                                <h5>Total Biaya ({{ $labelA }})</h5>
                                <p>{{ FormatHelper::formatRupiah($summaryA['total_biaya']) }}</p>
                                <span>{{ $summaryA['jumlah_servis'] }} Servis</span>
                            </div>
                            <div class="summary-card bg-periode-b">
                                <h5>Total Biaya ({{ $labelB }})</h5>
                                <p>{{ FormatHelper::formatRupiah($summaryB['total_biaya']) }}</p>
                                <span>{{ $summaryB['jumlah_servis'] }} Servis</span>
                            </div>
                            <div class="summary-card {{ $diff_biaya >= 0 ? 'bg-perbedaan' : 'bg-perbedaan minus' }}">
                                <h5>Perbedaan Biaya</h5>
                                <p>{{ $diff_biaya >= 0 ? '+' : '' }} {{ FormatHelper::formatRupiah($diff_biaya) }}</p>
                                <span>{{ $diff_servis >= 0 ? '+' : '' }} {{ $diff_servis }} Servis</span>
                            </div>
                        </div>

                        {{-- BAGIAN TABEL PERBANDINGAN --}}
                        <div class="row">
                            <div class="col-lg-6">
                                <h5 class="card-title">{{ $labelA }}</h5>
                                @if($dataA->isEmpty())
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i>
                                    Tidak ada data pemeliharaan untuk periode {{ $labelA }}
                                </div>
                                @else
                                @include('pemeliharaan._tabel_pemeliharaan', ['data' => $dataA, 'tableId' => 'tableA'])
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <h5 class="card-title">{{ $labelB }}</h5>
                                @if($dataB->isEmpty())
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i>
                                    Tidak ada data pemeliharaan untuk periode {{ $labelB }}
                                </div>
                                @else
                                @include('pemeliharaan._tabel_pemeliharaan', ['data' => $dataB, 'tableId' => 'tableB'])
                                @endif
                            </div>
                        </div>
                        @endif

                        @else
                        {{-- Tampilan Laporan Periode Biasa --}}
                        @if($dataA->isEmpty())
                        <div class="empty-data-message">
                            <i class="bi bi-inbox"></i>
                            <h5>Tidak Ada Data Pemeliharaan</h5>
                            <p>Tidak ditemukan data pemeliharaan untuk {{ $labelA }}.</p>
                            <p class="mt-2"><small>Silakan pilih periode lain atau pastikan data sudah diinput dengan benar.</small></p>
                        </div>
                        @else
                        <h5 class="card-title">{{ $labelA }}</h5>
                        @include('pemeliharaan._tabel_pemeliharaan', ['data' => $dataA, 'tableId' => 'tablePemeliharaan'])
                        @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection