@extends('layouts.main')
@section('main')
<main id="main" class="main">
    <div class="pagetitle">
        {{-- <h1>Edit Data Kendaraan</h1> --}}
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/kendaraan">Kembali</a></li>
                <li class="breadcrumb-item active">Edit Kendaraan</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Data Kendaraan</h5>
                        <form action="{{ url('kendaraan/' . $kendaraan->slug) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            {{-- @method('POST') --}}
                            <div class="row mb-3">
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">No Polisi <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control @error('no_polisi') is-invalid @enderror"
                                        autofocus name="no_polisi"
                                        value="{{ old('no_polisi', $kendaraan->no_polisi) }}">
                                    @error('no_polisi')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Merk <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control @error('merk') is-invalid @enderror"
                                        name="merk" value="{{ old('merk', $kendaraan->merk) }}">
                                    @error('merk')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Model <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control @error('model') is-invalid @enderror"
                                        name="model" value="{{ old('model', $kendaraan->model) }}">
                                    @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Warna <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control @error('warna') is-invalid @enderror"
                                        name="warna" value="{{ old('warna', $kendaraan->warna) }}">
                                    @error('warna')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Bahan Bakar<sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control @error('bahan_bakar') is-invalid @enderror"
                                        name="bahan_bakar" value="{{ old('bahan_bakar', $kendaraan->bahan_bakar) }}">
                                    @error('bahan_bakar')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Jenis Kendaraan <sup
                                            class="text-danger">*</sup></label>

                                    <select class="form-control @error('jenis') is-invalid @enderror" name="jenis">
                                        <option value="">-- Pilih Jenis Kendaraan --</option>
                                        <option value="Mobil"
                                            {{ old('jenis', $kendaraan->jenis) == 'Mobil' ? 'selected' : '' }}>Mobil
                                        </option>
                                        <option value="Motor"
                                            {{ old('jenis', $kendaraan->jenis) == 'Motor' ? 'selected' : '' }}>Motor
                                        </option>
                                        <option value="Truk"
                                            {{ old('jenis', $kendaraan->jenis) == 'Truk' ? 'selected' : '' }}>Truk
                                        </option>
                                        <option value="Alat Berat"
                                            {{ old('jenis', $kendaraan->jenis) == 'Alat Berat' ? 'selected' : '' }}>Alat
                                            Berat</option>
                                    </select>
                                    @error('jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror

                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Foto Kendaraan</label>
                                    <input type="file" class="form-control" name="foto" value="{{ $kendaraan->foto }}">
                                    @if ($kendaraan->foto)
                                    <img src="{{ asset('kendaraanImage/' . $kendaraan->foto) }}"
                                        class="img-thumbnail mt-2" width="150">
                                    @endif
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class=" col-form-label">Tahun Pembuatan <sup
                                            class="text-danger">*</sup></label>
                                    <input type="date"
                                        class="form-control @error('tahun_pembuatan') is-invalid @enderror"
                                        name="tahun_pembuatan"
                                        value="{{ old('tahun_pembuatan', $kendaraan->tahun_pembuatan) }}">
                                    @error('tahun_pembuatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Masa Aktif Pajak Tahunan <sup
                                            class="text-danger">*</sup></label>
                                    <input type="date"
                                        class="form-control @error('masa_aktif_pajak_tahunan') is-invalid @enderror"
                                        name="masa_aktif_pajak_tahunan"
                                        value="{{ old('masa_aktif_pajak_tahunan', $kendaraan->masa_aktif_pajak_tahunan) }}">
                                    @error('masa_aktif_pajak_tahunan')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Masa Aktif Plat <sup
                                            class="text-danger">*</sup></label>
                                    <input type="date"
                                        class="form-control @error('masa_aktif_plat') is-invalid @enderror"
                                        name="masa_aktif_plat"
                                        value="{{ old('masa_aktif_plat', $kendaraan->masa_aktif_plat) }}">
                                    @error('masa_aktif_plat')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Nomor Rangka <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control @error('no_rangka') is-invalid @enderror"
                                        name="no_rangka" value="{{ old('no_rangka', $kendaraan->no_rangka) }}">
                                    @error('no_rangka')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Nomor Mesin <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control @error('no_mesin') is-invalid @enderror"
                                        name="no_mesin" value="{{ old('no_mesin', $kendaraan->no_mesin) }}">
                                    @error('no_mesin')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Jumlah Roda</label>
                                    <input type="text" class="form-control" name="jumlah_roda"
                                        value="{{ old('jumlah_roda', $kendaraan->jumlah_roda) }}">
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Bidang <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control @error('bidang') is-invalid @enderror"
                                        name="bidang" value="{{ old('bidang', $kendaraan->bidang) }}">
                                    @error('bidang')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Pengguna <sup class="text-danger">*</sup></label>
                                    <select class="form-control @error('id_users') is-invalid @enderror select2"
                                        name="id_users">
                                        <option value="">--Pilih Pengguna--</option>
                                        @foreach ($user as $usr)
                                        <option value="{{ $usr->id }}"
                                            {{ old('id_users', $usr->id) == $kendaraan->id_users ? 'selected' : '' }}>
                                            {{ $usr->name }}
                                        </option>
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
                                        <option value="{{ $rek->id }}"
                                            {{ old('id_rek', $rek->id) == $pemeliharaan->id_rekening ? 'selected' : '' }}>
                                            {{ $rek->nama_rekening }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('id_rek')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="col-form-label">Status Kendaraan <sup
                                            class="text-danger">*</sup></label>

                                    <select class="form-control @error('status') is-invalid @enderror" name="status">
                                        <option value="">-- Pilih Status Kendaraan --</option>
                                        <option value="aktif"
                                            {{ old('status', $kendaraan->status) == 'aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="nonaktif"
                                            {{ old('status', $kendaraan->status) == 'nonaktif' ? 'selected' : '' }}>
                                            Nonaktif
                                        </option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror

                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <small><i>Tanda bintang (<sup class="text-danger"> * </sup>) di samping label
                                            kolom formulir menunjukkan bahwa kolom tersebut wajib diisi</i></small>
                                </div>
                                <div class="col-sm-6">
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