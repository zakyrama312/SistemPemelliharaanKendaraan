@extends('layouts.main')
@section('main')
<main id="main" class="main">
    <div class="pagetitle">
        {{-- <h1>Tambah Data Kendaraan</h1> --}}
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/kendaraan">Kembali</a></li>
                <li class="breadcrumb-item active">Tambah Kendaraan</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tambah Data Kendaraan</h5>
                        <form action="{{ route('kendaraan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            {{-- @method('POST') --}}
                            <div class="row mb-3">
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">No Polisi <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control @error('no_polisi') is-invalid @enderror"
                                        autofocus name="no_polisi" value="{{ old('no_polisi') }}">
                                    @error('no_polisi')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Merk <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control @error('merk') is-invalid @enderror"
                                        name="merk" value="{{ old('merk') }}">
                                    @error('merk')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Model <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control @error('model') is-invalid @enderror"
                                        name="model" value="{{ old('model') }}">
                                    @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Warna <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control @error('warna') is-invalid @enderror"
                                        name="warna" value="{{ old('warna') }}">
                                    @error('warna')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Bahan Bakar<sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control @error('bahan_bakar') is-invalid @enderror"
                                        name="bahan_bakar" value="{{ old('bahan_bakar') }}">
                                    @error('bahan_bakar')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Jenis Kendaraan <sup
                                            class="text-danger">*</sup></label>

                                    <select class="form-control @error('jenis') is-invalid @enderror" name="jenis">
                                        <option value="">-- Pilih Jenis Kendaraan --</option>
                                        <option value="Motor">Motor</option>
                                        <option value="Mobil">Mobil</option>
                                        <option value="Truk">Truk</option>
                                        <option value="Alat Berat">Alat Berat</option>
                                    </select>
                                    @error('jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror

                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Foto Kendaraan</label>
                                    <input type="file" class="form-control" name="foto">
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class=" col-form-label">Tahun Pembuatan <sup
                                            class="text-danger">*</sup></label>
                                    <input type="date"
                                        class="form-control @error('tahun_pembuatan') is-invalid @enderror"
                                        name="tahun_pembuatan" value="{{ old('tahun_pembuatan') }}">
                                    @error('tahun_pembuatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Masa Aktif Pajak Tahunan <sup
                                            class="text-danger">*</sup></label>
                                    <input type="date"
                                        class="form-control @error('masa_aktif_pajak_tahunan') is-invalid @enderror"
                                        name="masa_aktif_pajak_tahunan" value="{{ old('masa_aktif_pajak_tahunan') }}">
                                    @error('masa_aktif_pajak_tahunan')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Masa Aktif Plat <sup
                                            class="text-danger">*</sup></label>
                                    <input type="date"
                                        class="form-control @error('masa_aktif_plat') is-invalid @enderror"
                                        name="masa_aktif_plat" value="{{ old('masa_aktif_plat') }}">
                                    @error('masa_aktif_plat')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Tanggal Pemeliharaan Sebelumnya <sup
                                            class="text-danger">*</sup></label>
                                    <input type="date"
                                        class="form-control @error('tanggal_pemeliharaan') is-invalid @enderror"
                                        name="tanggal_pemeliharaan" value="{{ old('tanggal_pemeliharaan') }}">
                                    @error('tanggal_pemeliharaan')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Biaya Pemeliharaan Sebelumnya <sup
                                            class="text-danger">*</sup></label>
                                    <input type="number"
                                        class="form-control @error('biaya_pemeliharaan') is-invalid @enderror"
                                        name="biaya_pemeliharaan" value="{{ old('biaya_pemeliharaan') }}">
                                    @error('biaya_pemeliharaan')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Nomor Rangka <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control @error('no_rangka') is-invalid @enderror"
                                        name="no_rangka" value="{{ old('no_rangka') }}">
                                    @error('no_rangka')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Nomor Mesin <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control @error('no_mesin') is-invalid @enderror"
                                        name="no_mesin" value="{{ old('no_mesin') }}">
                                    @error('no_mesin')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Jumlah Roda</label>
                                    <input type="text" class="form-control" name="jumlah_roda"
                                        value="{{ old('jumlah_roda') }}">
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Bidang <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control @error('bidang') is-invalid @enderror"
                                        name="bidang" value="{{ old('bidang') }}">
                                    @error('bidang')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Pengguna <sup class="text-danger">*</sup></label>
                                    <select class="form-control @error('id_users') is-invalid @enderror"
                                        name="id_users">
                                        <option value="">--Pilih Pengguna--</option>
                                        @foreach ($user as $usr)
                                            <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_users')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Rekening <sup class="text-danger">*</sup></label>
                                    <select class="form-control @error('id_rek') is-invalid @enderror" name="id_rek">
                                        <option value="">--Pilih Rekening--</option>
                                        @foreach ($rekening as $rek)
                                            <option value="{{ $rek->id }}">{{ $rek->nama_rekening }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_rek')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <small><i>Tanda bintang (<sup class="text-danger"> * </sup>) di samping label
                                            kontrol formulir menunjukkan bahwa kolom tersebut wajib diisi</i></small>
                                </div>
                                <div class="col-sm-6 ">
                                    <button type="submit" class="btn btn-primary float-end ms-3">Simpan</button>
                                    <a href="/kendaraan" class="btn btn-outline-primary float-end">Kembali</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection