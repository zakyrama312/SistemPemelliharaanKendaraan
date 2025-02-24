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
                    <li class="breadcrumb-item active">Tambah Data Pemeliharaan</li>
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
                                    <label for="inputEmail" class="col-sm-2 col-form-label">Model </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('model') is-invalid @enderror"
                                            name="model" value="{{ old('model', $kendaraan->model) }}" readonly>
                                        @error('model')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">Jumlah Liter <sup
                                            class="text-danger">*</sup></label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control @error('jumlah_liter') is-invalid @enderror"
                                            name="jumlah_liter" value="{{ old('jumlah_liter') }}">

                                        @error('jumlah_liter')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <label class="col-sm-2 col-form-label">Liter</label>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-10">
                                        <input type="hidden" readonly class="form-control " name="id_kendaraan"
                                            value="{{ old('id_kendaraan', $kendaraan->id) }}">
                                        <input type="hidden" readonly class="form-control " name="id_rekening"
                                            value="{{ old('id_rekening', $kendaraan->id_rekening) }}">
                                        <input type="hidden" readonly class="form-control " name="slug"
                                            value="{{ old('slug', $kendaraan->slug) }}">


                                    </div>
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
                                        <a href="/pemeliharaan" class="btn btn-outline-primary float-end">Kembali</a>
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

                            <h5 class="card-title">Detail Pemeliharaan</h5>
                            <div class="table-responsive">
                                <table class="table datatable table-hover">
                                    <thead>
                                        <tr>
                                            <th class="border border-gray-400  text-center">No.</th>
                                            <th class="border border-gray-400 px-4 py-2">Liter</th>
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
                                                <td>{{ $pm->jumlah_liter }}</td>
                                                <td>{{ FormatHelper::formatRupiah($pm->nominal) }}</td>
                                                <td>
                                                    <a href="{{ asset('strukImage/' . $pm->foto_struk) }}"
                                                        data-lightbox="gallery">
                                                        <img src="{{ asset('strukImage/' . $pm->foto_struk) }}" width="100">
                                                    </a>
                                                </td>
                                                <td>{{ FormatHelper::formatTanggal($pm->created_at) }}</td>
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
    @include('bahanbakar.bahanbakar-edit')
    {{-- Modal Delete --}}
    @include('bahanbakar.bahanbakar-delete')
@endsection