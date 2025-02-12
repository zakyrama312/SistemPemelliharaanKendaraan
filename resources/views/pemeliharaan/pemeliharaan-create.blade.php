@extends('layouts.main')
@section('main')
@php
use App\Helpers\FormatHelper;
@endphp
<main id="main" class="main">

    <div class="pagetitle">
        {{-- <h1>Data Rekening</h1> --}}
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/pemeliharaan">Kembali</a></li>
                <li class="breadcrumb-item active">Tambah Data Pemeliharaan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tambah Pemeliharaan</h5>

                        <!-- General Form Elements -->
                        <form action="{{ route('pemeliharaan.store') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Kendaraan </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('kendaraan') is-invalid @enderror"
                                        name="kendaraan"
                                        value="{{ old('kendaraan', $pemeliharaan->kendaraan->no_polisi) }}" readonly>
                                    @error('nama_rek')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Merk </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('merk') is-invalid @enderror"
                                        name="merk" value="{{ old('merk', $pemeliharaan->kendaraan->merk) }}" readonly>
                                    @error('merk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Model </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('model') is-invalid @enderror"
                                        name="model" value="{{ old('model', $pemeliharaan->kendaraan->model) }}"
                                        readonly>
                                    @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Frekuensi per berapa bulan <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control @error('frekuensi') is-invalid @enderror"
                                        name="frekuensi"
                                        value="{{ old('frekuensi', $pemeliharaan->kendaraan->frekuensi_bulan) }}">

                                    @error('frekuensi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <label class="col-sm-2 col-form-label">Bulan</label>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-10">
                                    <input type="hidden" readonly class="form-control " name="id_kendaraan"
                                        value="{{ old('id_kendaraan', $pemeliharaan->id_kendaraan) }}">
                                    <input type="hidden" readonly class="form-control " name="id_rekening"
                                        value="{{ old('id_rekening', $pemeliharaan->id_rekening) }}">


                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Nama Bengkel <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('nama_bengkel') is-invalid @enderror"
                                        name="nama_bengkel" value="{{ old('nama_bengkel') }}">
                                    @error('nama_bengkel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Biaya <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('biaya') is-invalid @enderror"
                                        name="biaya" value="{{ old('biaya') }}">
                                    @error('biaya')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Deskripsi Pemeliharaan <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-10">
                                    <textarea name="deskripsi"
                                        class="form-control @error('deskripsi') is-invalid @enderror"
                                        id="">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <small><i>Tanda bintang (<sup class="text-danger"> * </sup>) di samping label
                                            kontrol formulir
                                            menunjukkan bahwa kolom tersebut wajib diisi</i></small>
                                </div>
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-primary float-end ms-3">Simpan</button>
                                    <a href="/pemeliharaan" class="btn btn-outline-primary float-end">Kembali</a>
                                </div>
                            </div>
                        </form>
                        <!-- End General Form Elements -->



                        <h5 class="card-title">Detail Pemeliharaan</h5>
                        <div class="table-responsive">
                            <table class="table datatable ">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-400 px-4 py-2">No.</th>
                                        <th class="border border-gray-400 px-4 py-2">Tanggal Pemeliharaan</th>
                                        <th class="border border-gray-400 px-4 py-2">Jadwal Berikutnya</th>
                                        <th class="border border-gray-400 px-4 py-2">Bengkel</th>
                                        <th class="border border-gray-400 px-4 py-2">Deskripsi</th>
                                        <th class="border border-gray-400 px-4 py-2">Biaya</th>
                                        <th class="border border-gray-400 px-4 py-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($view_pemeliharaan as $pm)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ FormatHelper::formatTanggal($pm->tanggal_pemeliharaan) }}</td>
                                        <td>{{ FormatHelper::formatTanggal($pm->jadwal_pemeliharaan) }}</td>
                                        <!-- <td>{{ $pm->kendaraan->no_polisi }}</td> -->

                                        <td>{{ $pm->bengkel }}</td>
                                        <td>{{ $pm->deskripsi }}</td>
                                        <td>{{ FormatHelper::formatRupiah($pm->biaya) }}</td>

                                        <td>


                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#largeModal{{ $pm->id }}"><i
                                                    class="bi bi-pencil-square text-warning"></i></a>

                                        </td>
                                    </tr>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>

{{-- Modal Update --}}
@include('pemeliharaan.pemeliharaan-edit')
{{-- Modal Delete --}}
@include('pemeliharaan.pemeliharaan-delete')
@endsection