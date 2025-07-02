@extends('layouts.main')
@section('main')
@php
use App\Helpers\FormatHelper;
@endphp
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Laporan Pengeluaran</h1>

    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="card-title">


                        </div>
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
                            <table class="table table-hover" id="tableKeuangan">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-400 px-4 py-2 text-center">No</th>
                                        <th class="border border-gray-400 px-4 py-2">Nomor Polisi</th>
                                        <th class="border border-gray-400 px-4 py-2">Tanggal Pengeluaran</th>
                                        <th class="border border-gray-400 px-4 py-2">Jenis Transaksi</th>
                                        <th class="border border-gray-400 px-4 py-2">Nominal</th>
                                        <!-- <th class="border border-gray-400 px-4 py-2 text-center">Aksi</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($keuangan as $p)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $p->no_polisi }} - {{ $p->merk }}</td>
                                        <td data-tanggal="{{ \Carbon\Carbon::parse($p->tanggal)->format('Y-m-d') }}">
                                            {{ FormatHelper::formatTanggal($p->tanggal) }}
                                        </td>
                                        <td>{{ $p->sumber_transaksi }}</td>
                                        <td data-nominal="{{ $p->nominal }}">
                                            {{ FormatHelper::formatRupiah($p->nominal) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align:right">Total</th>
                                        <th id="totalNominal">Rp 0</th>
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