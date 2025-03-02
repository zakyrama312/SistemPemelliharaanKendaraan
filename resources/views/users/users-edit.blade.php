@extends('layouts.main')
@section('main')

<main id="main" class="main">

    <div class="pagetitle">
        {{-- <h1>Edit Data Pegawai</h1> --}}
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/pegawai">Kembali</a></li>
                <li class="breadcrumb-item active">Edit Pegawai</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Data Pegawai</h5>

                        <form action="{{ url('pegawai/' . $pegawai->slug) }}" method="POST">
                            @csrf
                            @method('PUT') {{-- Menggunakan metode PUT untuk update data --}}

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">NIP <sup class="text-danger">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control @error('nip') is-invalid @enderror"
                                        name="nip" value="{{ old('nip', $pegawai->nip) }}">
                                    @error('nip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Nama <sup class="text-danger">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                        name="nama" value="{{ old('nama', $pegawai->name) }}">
                                    @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Username <sup class="text-danger">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                        name="username" value="{{ old('username', $pegawai->username) }}">
                                    @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Password (Kosongkan jika tidak ingin
                                    diubah)</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password">
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Pilih Role <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-10">
                                    <select class="form-select @error('role') is-invalid @enderror" name="role">
                                        <option value="">--Pilih-Role--</option>
                                        <!-- <option value="super admin"
                                            {{ old('role', $pegawai->role) == 'super admin' ? 'selected' : '' }}>                                            SuperAdmin</option> -->
                                        <option value="admin"
                                            {{ old('role', $pegawai->role) == 'admin' ? 'selected' : '' }}>Admin
                                        </option>
                                        <option value="user"
                                            {{ old('role', $pegawai->role) == 'user' ? 'selected' : '' }}>Pengguna
                                        </option>
                                    </select>
                                    @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <small><i>Tanda bintang (<sup class="text-danger"> * </sup>) di samping label
                                            kolom formulir menunjukkan bahwa kolom tersebut wajib diisi</i></small>
                                </div>
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-primary float-end ms-3">Update</button>
                                    <a href="/pegawai" class="btn btn-outline-primary float-end">Kembali</a>
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