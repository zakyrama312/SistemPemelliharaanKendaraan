@extends('layouts.main')
@section('main')
@php
    use App\Helpers\FormatHelper;
@endphp
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Pemeliharaan Kendaraan</h1>

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
                        <!-- Table with stripped rows -->
                        <div class="table-responsive">
                            <table class="table datatable ">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-400 px-4 py-2">Waktu Pemeliharaan</th>
                                        <th class="border border-gray-400 px-4 py-2">Nomor Polisi</th>
                                        <th class="border border-gray-400 px-4 py-2">Tanggal Pemeliharaan</th>
                                        <th class="border border-gray-400 px-4 py-2">Frekuensi (Bulan)</th>
                                        <th class="border border-gray-400 px-4 py-2">Jadwal Berikutnya</th>
                                        <th class="border border-gray-400 px-4 py-2">Total Biaya</th>
                                        <th class="border border-gray-400 px-4 py-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pemeliharaan as $pm)
                                        <tr>
                                            <!-- <td>{{ $loop->iteration }}</td> -->
                                            <td></td>
                                            <td>{{ $pm->kendaraan->no_polisi }} - {{ $pm->kendaraan->merk }} -
                                                {{ $pm->kendaraan->model }}
                                            </td>
                                            <td>{{ FormatHelper::formatTanggal($pm->tanggal_pemeliharaan) }}</td>
                                            <!-- <td>{{ $pm->kendaraan->no_polisi }}</td> -->
                                            <td></td>
                                            <td>{{ FormatHelper::formatTanggal($pm->jadwal_pemeliharaan) }}</td>
                                            <td>{{ FormatHelper::formatRupiah($pm->total_biaya) }}</td>


                                            <td>

                                                <a href="{{ url('pemeliharaan/' . $pm->kendaraan->slug . '/show') }}"><span
                                                        class="btn btn-danger "><i
                                                            class="bi bi-exclamation-triangle me-1"></i> Servis</span></a>
                                                <a href="{{ url('kendaraan/' . $pm->kendaraan->slug . '/detail') }}"><span
                                                        class="btn btn-info "><i class="bi bi-info-square me-1"></i>
                                                        Detail</span></a>


                                            </td>
                                        </tr>

                                    @endforeach
                                </tbody>
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