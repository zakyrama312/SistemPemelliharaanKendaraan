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
                <li class="breadcrumb-item"><a href="/pajak-tahunan">Kembali</a></li>
                <li class="breadcrumb-item active">Tambah Data Pajak Tahunan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tambah Pajak Tahunan</h5>

                        <!-- General Form Elements -->
                        <form action="{{ route('pajak-tahunan.store') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Kendaraan </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('kendaraan') is-invalid @enderror"
                                        name="kendaraan" value="{{ old('kendaraan', $kendaraan->no_polisi) }}" readonly>
                                    @error('nama_rek')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Merk </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('merk') is-invalid @enderror"
                                        name="merk" value="{{ old('merk', $kendaraan->merk) }}" readonly>
                                    @error('merk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Nama Rekening </label>
                                <div class="col-sm-10">
                                    <select class="form-control @error('id_rekening') is-invalid @enderror"
                                        name="id_rekening">
                                        <option value="">--Pilih Rekening--</option>
                                        @foreach ($rekening as $rek)
                                        <option value="{{ $rek->id }}">{{ $rek->nama_rekening }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_rekening')
                                    <div class=" invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-10">
                                    <input type="hidden" readonly class="form-control " name="id_kendaraan"
                                        value="{{ old('id_kendaraan', $kendaraan->id) }}">
                                    <input type="hidden" readonly class="form-control " name="slug"
                                        value="{{ old('slug', $kendaraan->slug) }}">


                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Masa Berlaku <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text"
                                        class="form-control format-tanggal @error('masa_berlaku') is-invalid @enderror"
                                        name="masa_berlaku" value="{{ old('masa_berlaku') }}">
                                    @error('masa_berlaku')
                                    <div class=" invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Biaya <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" inputmode="numeric"
                                        class="form-control format-rupiah @error('biaya') is-invalid @enderror"
                                        name="biaya" value="{{ old('biaya') }}">
                                    @error('biaya')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <small><i>Tanda bintang (<sup class="text-danger"> * </sup>) di samping label
                                            kolom formulir
                                            menunjukkan bahwa kolom tersebut wajib diisi</i></small>
                                </div>
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-primary float-end ms-3">Simpan</button>
                                    <a href="/pajak-tahunan" class="btn btn-outline-primary float-end">Kembali</a>
                                </div>
                            </div>
                        </form>
                        <!-- End General Form Elements -->



                        <h5 class="card-title">Detail Pajak Tahunan</h5>
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
                        <div class="table-responsive">
                            <table class="table datatable ">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-400 px-4 py-2">No.</th>
                                        <th class="border border-gray-400 px-4 py-2">Masa Aktif</th>
                                        <th class="border border-gray-400 px-4 py-2">Biaya</th>
                                        <th class="border border-gray-400 px-4 py-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($view_pajakTahunan as $pm)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ FormatHelper::formatTanggal($pm->masa_berlaku) }}</td>
                                        <td>{{ FormatHelper::formatRupiah($pm->nominal) }}</td>
                                        <td class="text-center">
                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#largeModal{{ $pm->id }}"><i
                                                    class="bi bi-pencil-square text-warning"></i></a>
                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#basicModal{{ $pm->id }}"><i
                                                    class="bi bi-trash text-danger"></i></a>

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
@include('pajaktahunan.pajaktahunan-edit')
{{-- Modal Delete --}}
@include('pajaktahunan.pajaktahunan-delete')
@endsection