@extends('layouts.main')
@section('main')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Data Kendaraan</h1>

        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><a href="/kendaraan/tambah-kendaraan"
                                    class="btn btn-outline-primary">Tambah
                                    Kendaraan</a></h5>
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
                                            <th>No</th>
                                            <th>Waktu Pembayaran</th>
                                            <th>Tanggal Berlaku</th>
                                            <th>Nomor Polisi</th>
                                            <th>Foto</th>
                                            <th>Pengguna</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kendaraan as $kndr)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td></td>
                                                <td>{{ $kndr->masa_aktif_pajak_tahunan }}</td>
                                                <td>{{ $kndr->no_polisi }} - {{ $kndr->merk }} - {{ $kndr->model }}</td>
                                                <td><img src="{{ asset('kendaraanImage/' . $kndr->foto) }}" alt="Foto Kendaraan"
                                                        width="100">
                                                </td>
                                                <td>{{ $kndr->user->name }}</td>
                                                <td>{{ $kndr->status }}</td>
                                                <td>
                                                    <a href="{{ url('kendaraan/' . $kndr->slug . '/edit') }}"><i
                                                            class="bi bi-pencil-square text-warning"></i></a>
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#basicModal{{ $kndr->id }}"><i
                                                            class="bi bi-trash text-danger"></i></a>
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

    {{-- Modal Delete --}}
    @include('kendaraan.kendaraan-delete')
@endsection