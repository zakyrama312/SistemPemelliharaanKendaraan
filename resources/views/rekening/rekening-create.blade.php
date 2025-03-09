@extends('layouts.main')
@section('main')

    <main id="main" class="main">

        <div class="pagetitle">
            {{-- <h1>Data Rekening</h1> --}}
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/rekening">Kembali</a></li>
                    <li class="breadcrumb-item active">Tambah Data Rekening</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tambah Data Rekening</h5>

                            <!-- General Form Elements -->
                            <form action="{{ url('rekening') }}" method="POST">
                                @csrf
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-2 col-form-label">Nama Rekening <sup
                                            class="text-danger">*</sup></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('nama_rek') is-invalid @enderror"
                                            name="nama_rek" value="{{ old('nama_rek') }}">
                                        @error('nama_rek')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">Saldo <sup
                                            class="text-danger">*</sup></label>
                                    <div class="col-sm-10">
                                        <input type="text" inputmode="numeric"
                                            class="form-control format-rupiah @error('saldo') is-invalid @enderror"
                                            name="saldo" value="{{ old('saldo') }}">
                                        @error('saldo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <small><i>Tanda bintang (<sup class="text-danger"> * </sup>) di samping label kolom
                                                formulir
                                                menunjukkan bahwa kolom tersebut wajib diisi</i></small>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-primary float-end ms-3">Simpan</button>
                                        <a href="/rekening" class="btn btn-outline-primary float-end">Kembali</a>
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