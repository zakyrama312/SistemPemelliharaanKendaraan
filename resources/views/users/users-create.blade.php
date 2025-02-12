@extends('layouts.main')
@section('main')
<main id="main" class="main">

    <div class="pagetitle">
        {{-- <h1>Data Pegawai</h1> --}}
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/pegawai">Kembali</a></li>
                <li class="breadcrumb-item active">Tambah Data Pegawai</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tambah Data Pegawai</h5>

                        <!-- General Form Elements -->
                        <form action="{{ url('pegawai') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">NIP <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control @error('nip') is-invalid @enderror"
                                        name="nip" value="{{ old('nip') }}">
                                    @error('nip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Nama <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                        name="nama" value="{{ old('nama') }}">
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Username <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                        name="username" value="{{ old('username') }}">
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Password <sup
                                        class="text-danger">*</sup></label>
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
                                        <option value="super admin" {{ old('role') == 'super admin' ? 'selected' : '' }}>
                                            SuperAdmin</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin
                                        </option>
                                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Pengguna
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
                                            kontrol formulir menunjukkan bahwa kolom tersebut wajib diisi</i></small>
                                </div>
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-primary float-end ms-3">Simpan</button>
                                    <a href="/pegawai" class="btn btn-outline-primary float-end">Kembali</a>
                                </div>
                            </div>
                        </form>
                        <!-- End General Form Elements -->

                    </div>
                </div>

            </div>
        </div>
    </section>

</main>
@endsection