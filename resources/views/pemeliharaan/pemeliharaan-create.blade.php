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
                                    <label for="inputEmail" class="col-sm-2 col-form-label">Nama Rekening </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('nama_rek') is-invalid @enderror"
                                            name="nama_rek"
                                            value="{{ old('nama_rek', $pemeliharaan->rekening->nama_rekening) }}" readonly>
                                        @error('nama_rek')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">Tanggal Pemeliharaan
                                        <sup class="text-danger">*</sup></label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control format-tanggal @error('tanggal') is-invalid @enderror"
                                            name="tanggal" value="{{ old('tanggal') }}">

                                        @error('tanggal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!-- <label class="col-sm-2 col-form-label">Bulan</label> -->
                                </div>
                                <div class="row mb-3">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">Jadwal Berikutnya
                                        <sup class="text-danger">*</sup></label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control format-tanggal @error('jadwal') is-invalid @enderror"
                                            name="jadwal" value="{{ old('jadwal') }}">

                                        @error('jadwal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!-- <label class="col-sm-2 col-form-label">Bulan</label> -->
                                </div>

                                <div class="row ">
                                    <div class="col-sm-10">
                                        <input type="hidden" readonly class="form-control " name="id_kendaraan"
                                            value="{{ old('id_kendaraan', $pemeliharaan->id_kendaraan) }}">
                                        <input type="hidden" readonly class="form-control " name="id_rekening"
                                            value="{{ old('id_rekening', $pemeliharaan->id_rekening) }}">
                                        <input type="hidden" readonly class="form-control " name="slug"
                                            value="{{ old('slug', $pemeliharaan->kendaraan->slug) }}">


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
                                        <input type="text" inputmode="numeric"
                                            class="form-control format-rupiah @error('biaya') is-invalid @enderror"
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



                            <h5 class="card-title">Detail Pemeliharaan</h5>
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
                                <table class="table datatable table-hover">
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
                                                <!-- <td>{{ FormatHelper::formatTanggal($pm->tanggal_pemeliharaan_sebelumnya) }}</td> -->
                                                <td>
                                                    @if ($pm->tanggal_pemeliharaan_sebelumnya)
                                                        <span
                                                            class="">{{ FormatHelper::formatTanggal($pm->tanggal_pemeliharaan_sebelumnya) }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($pm->tanggal_pemeliharaan_berikutnya)
                                                        <span
                                                            class="">{{ FormatHelper::formatTanggal($pm->tanggal_pemeliharaan_berikutnya) }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>

                                                <td>{{ $pm->bengkel }}</td>
                                                <td>{{ $pm->deskripsi }}</td>
                                                <td>{{ FormatHelper::formatRupiah($pm->biaya) }}</td>
                                                <td class="text-center">
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#largeModal{{ $pm->id }}"><i
                                                            class="bi bi-pencil-square text-warning"></i></a>
                                                    @if (Auth::user()->role == 'admin')<a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#basicModal{{ $pm->id }}"><i
                                                            class="bi bi-trash text-danger"></i></a>

                                                    @endif

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