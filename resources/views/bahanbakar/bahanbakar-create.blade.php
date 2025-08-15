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
                <li class="breadcrumb-item"><a href="/pengeluaran-bbm">Kembali</a></li>
                <li class="breadcrumb-item active">Tambah Pengeluaran Bahan Bakar</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tambah Pengeluaran Bahan Bakar</h5>

                        <!-- General Form Elements -->
                        <form action="{{ route('pengeluaran-bbm.store') }}" method="POST" enctype="multipart/form-data">
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

                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Nama SPBU </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('spbu') is-invalid @enderror"
                                        name="spbu" value="{{ old('spbu') }}">
                                    @error('spbu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Jumlah Liter <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-3">
                                    <input type="number" min="0"
                                        class="form-control @error('jumlah_liter') is-invalid @enderror"
                                        name="jumlah_liter" id="jumlah_liter" value="{{ old('jumlah_liter') }}">

                                    @error('jumlah_liter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <label class="col-sm-2 col-form-label">Liter</label>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Harga BBM / Liter <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text"
                                        class="form-control format-rupiah @error('harga_bbm') is-invalid @enderror"
                                        name="harga_bbm" id="harga_bbm" inputmode="numeric"
                                        value="{{ old('harga_bbm') }}">
                                    @error('harga_bbm')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                <label for="inputEmail" class="col-sm-2 col-form-label">Tanggal Pengisian
                                    <sup class="text-danger">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text"
                                        class="form-control format-tanggalBBM @error('tanggal') is-invalid @enderror"
                                        name="tanggal" value="{{ old('tanggal') }}">

                                    @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- <label class="col-sm-2 col-form-label">Bulan</label> -->
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Foto Struk</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control @error('foto_struk') is-invalid @enderror"
                                        name="foto_struk" value="{{ old('foto_struk') }}">
                                    @error('foto_struk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Biaya <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text"
                                        class="form-control format-rupiah1 @error('biaya') is-invalid @enderror"
                                        name="biaya" id="biaya" inputmode="numeric" value="{{ old('biaya') }}" readonly>
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
                                    <a href="/pengeluaran-bbm" class="btn btn-outline-primary float-end">Kembali</a>
                                </div>
                            </div>
                        </form>
                        <!-- End General Form Elements -->

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

                        <h5 class="card-title">Detail Pengeluaran Bahan Bakar</h5>
                        <div class="col-md-12">
                            <div class="row">
                                <label for="" style="color: black" class="mb-3">Filter Tanggal</label>
                                <div class="col-md-4">
                                    <input type="date" id="start_date" name="start_date" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <input type="date" id="end_date" name="end_date" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <a href="#" id="btnPrintBbm" class="btn btn-primary mb-3 float-end" target="_blank">
                                        <i class="fa fa-print"></i> Print Rekap BBM
                                    </a>
                                </div>
                            </div>
                        </div>


                        <div class="table-responsive">
                            <table class="table table-hover" id="tableBahanBakar">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-400  text-center">No.</th>
                                        <th class="border border-gray-400 px-4 py-2 ">Nama SPBU</th>
                                        <th class="border border-gray-400 px-4 py-2">Jumlah Liter</th>
                                        <th class="border border-gray-400 px-4 py-2">Harga / Liter</th>
                                        <th class="border border-gray-400 px-4 py-2">Biaya</th>
                                        <th class="border border-gray-400 px-4 py-2">Foto Struk</th>
                                        <th class="border border-gray-400 px-4 py-2">Tanggal</th>
                                        <th class="border border-gray-400 px-4 py-2 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($view_bbm as $pm)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $pm->spbu }}</td>
                                        <td>{{ $pm->jumlah_liter }}</td>
                                        <td>{{ FormatHelper::formatRupiah($pm->harga_bbm) }}</td>
                                        <td data-nominal="{{ $pm->nominal }}">
                                            {{ FormatHelper::formatRupiah($pm->nominal) }}
                                        </td>
                                        <td>
                                            @if ($pm->foto_struk != null)
                                            <a href="{{ asset('strukImage/' . $pm->foto_struk) }}"
                                                data-lightbox="gallery">
                                                <img src="{{ asset('strukImage/' . $pm->foto_struk) }}" width="100">
                                            </a>
                                            @else
                                            <span>-</span>
                                            @endif
                                        </td>
                                        <td
                                            data-tanggal="{{ \Carbon\Carbon::parse($pm->tanggal_pengisian)->format('Y-m-d') }}">
                                            {{ $pm->tanggal_pengisian != null ? FormatHelper::formatTanggal($pm->tanggal_pengisian) : '-' }}
                                        </td>
                                        <td class="text-center">
                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#largeModal{{ $pm->id }}"><i
                                                    class="bi bi-pencil-square text-warning"></i></a>
                                            @if (Auth::user()->role == 'admin')
                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#basicModal{{ $pm->id }}"><i
                                                    class="bi bi-trash text-danger"></i></a>
                                            @endif

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
                                        <th id="totalNominalBiaya">Rp 0</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>

{{-- Modal Update --}}
@include('bahanbakar.bahanbakar-edit')
{{-- Modal Delete --}}
@include('bahanbakar.bahanbakar-delete')
@endsection