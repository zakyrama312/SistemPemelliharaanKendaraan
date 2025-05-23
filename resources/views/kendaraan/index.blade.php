@extends('layouts.main')
@section('main')
    @php
        use App\Helpers\FormatHelper;
    @endphp
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
                                            <th class="border border-gray-400 px-4 py-2 text-center">No Kode Barang</th>
                                            <th class="border border-gray-400 px-4 py-2 text-center">No Register</th>
                                            <th class="border border-gray-400 px-4 py-2 text-center">Nama Barang</th>
                                            <th class="border border-gray-400 px-4 py-2 text-center">Nomor Polisi</th>
                                            <th class="border border-gray-400 px-4 py-2 text-center">Foto</th>
                                            <th class="border border-gray-400 px-4 py-2 text-center">Pengguna</th>
                                            <th class="border border-gray-400 px-4 py-2 text-center">Status</th>
                                            <th class="border border-gray-400 px-4 py-2 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kendaraan as $kndr)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $kndr->kode_barang }}</td>
                                                <td>{{ $kndr->no_register }}</td>
                                                <td>{{ $kndr->nama_barang }}</td>
                                                <td>{{ $kndr->no_polisi }} - {{ $kndr->merk }} </td>
                                                <td>
                                                    @if (empty($kndr->foto))
                                                        -
                                                    @else
                                                        <img src="{{ asset('kendaraanImage/' . $kndr->foto) }}" alt="Foto Kendaraan"
                                                            width="100">
                                                    @endif
                                                </td>
                                                <td>{{ $kndr->user->name }}</td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-{{ $kndr->status == 'aktif' ? 'success' : 'danger' }}"><i
                                                            class="bi bi-{{ $kndr->status == 'aktif' ? 'check-circle' : 'exclamation-octagon' }} me-1"></i>{{ $kndr->status == 'aktif' ? 'Aktif' : 'Tidak Aktif' }}</span>

                                                </td>
                                                <td class="text-center">
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