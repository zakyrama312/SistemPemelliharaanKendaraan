@extends('layouts.main')
@section('main')
@php
use App\Helpers\FormatHelper;
@endphp
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Pajak Tahunan Kendaraan</h1>

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
                        <!-- Table with stripped rows -->
                        <div class="table-responsive">
                            <table class="table datatable table-hover">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-400 px-4 py-2 text-center">No</th>
                                        <th class="border border-gray-400 px-4 py-2">Status</th>
                                        <th class="border border-gray-400 px-4 py-2">Nomor Polisi</th>
                                        <th class="border border-gray-400 px-4 py-2">Tanggal Bayar Pajak Tahunan</th>
                                        <th class="border border-gray-400 px-4 py-2 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pajakTahunan as $index => $pajak)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">
                                            @if ($pajak['status'] == 'danger')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <i class="bi bi-exclamation-octagon me-1"></i>
                                                {!! $pajak['peringatan'] !!}
                                            </div>
                                            @elseif ($pajak['status'] == 'warning')
                                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                {!! $pajak['peringatan'] !!}
                                            </div>
                                            @else
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Aman
                                            </div>
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $pajak['nomor_polisi'] }} - {{ $pajak['merk'] }}
                                        </td>
                                        <td class="align-middle">
                                            {{ FormatHelper::formatTanggal($pajak['masa_berlaku']) }}
                                        </td>

                                        <td class="text-center align-middle">

                                            <a href="{{ url('pajak-tahunan/' . $pajak['slug'] . '/show') }}"><span
                                                    class="btn btn-warning "><i
                                                        class="bi bi-exclamation-triangle me-1"></i> Bayar</span></a>

                                            <a href="{{ url('kendaraan/detail-kendaraan/' . $pajak['slug']) }}"><span
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
