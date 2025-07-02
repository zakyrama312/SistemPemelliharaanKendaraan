@extends('layouts.main')
@section('main')
@php
use App\Helpers\FormatHelper;
@endphp
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Laporan Bahan Bakar Kendaraan</h1>

    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <!-- <a href="/rekening/tambah-rekening"
                                                                                                                                                                                                                                                                                                                                                                        class="btn btn-outline-primary">Tambah Rekening</a> -->
                        </h5>
                        @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-1"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-octagon me-1"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6"></div>

                                <label for="" style="color: black" class="mb-3">Filter Tanggal</label>
                                <div class="col-md-6">
                                    <input type="date" id="start_date" name="start_date" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <input type="date" id="end_date" name="end_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <!-- Table with stripped rows -->
                        <div class="table-responsive">
                            <table class="table table-hover" id="tableLaporanBBM" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-400 px-4 py-2 text-center align-middle"
                                            rowspan="2">No</th>
                                        <th class="border border-gray-400 px-4 py-2 text-center" colspan="2">Spesifikasi
                                            Barang</th>
                                        <th class="border border-gray-400 px-4 py-2 text-center align-middle"
                                            rowspan="2">Nama Barang</th>
                                        <th class="border border-gray-400 px-4 py-2 text-center align-middle"
                                            rowspan="2">No Polisi</th>
                                        <th class="border border-gray-400 px-4 py-2 text-center align-middle"
                                            rowspan="2">Nama SPBU</th>
                                        <th class="border border-gray-400 px-4 py-2 text-center align-middle"
                                            rowspan="2">Tanggal</th>
                                        <th class="border border-gray-400 px-4 py-2 text-center align-middle"
                                            rowspan="2">Jumlah Liter</th>
                                        <th class="border border-gray-400 px-4 py-2 text-center align-middle"
                                            rowspan="2">Harga /Liter</th>
                                        <th class="border border-gray-400 px-4 py-2 text-center align-middle"
                                            rowspan="2">Biaya</th>
                                    </tr>
                                    <tr>
                                        <th class="border border-gray-400 px-4 py-2 text-center">Kode Barang</th>
                                        <th class="border border-gray-400 px-4 py-2 text-center">No Register</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($kendaraanData as $kendaraan)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $kendaraan->kendaraan->kode_barang }}</td>
                                        <td>{{ $kendaraan->kendaraan->no_register }}</td>
                                        <td>{{ $kendaraan->kendaraan->nama_barang }}</td>
                                        <td>{{ $kendaraan->kendaraan->no_polisi }} -
                                            {{ $kendaraan->kendaraan->jenis }}
                                        </td>
                                        <td class="text-center">{{ $kendaraan->spbu }}</td>

                                        <td
                                            data-tanggal="{{ \Carbon\Carbon::parse($kendaraan->tanggal_pengisian)->format('Y-m-d') }}">
                                            {{ FormatHelper::formatTanggal($kendaraan->tanggal_pengisian) }}
                                        </td>
                                        <td class="text-center">{{ $kendaraan->jumlah_liter }}</td>
                                        <td class="text-center">{{ FormatHelper::formatRupiah($kendaraan->harga_bbm) }}
                                        </td>
                                        <td data-nominal="{{ $kendaraan->nominal }}">
                                            {{ FormatHelper::formatRupiah($kendaraan->nominal) }}
                                        </td>
                                    </tr>

                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align:right">Total</th>
                                        <th id="totalBiaya">Rp 0</th>
                                        <!-- <th></th> -->
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
    </section>

</main>
@endsection