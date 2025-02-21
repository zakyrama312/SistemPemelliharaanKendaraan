@extends('layouts.main')
@section('main')
    @php
        use App\Helpers\FormatHelper;
    @endphp
    <main id="main" class="main">

        <div class="pagetitle">
            {{-- <h1>Edit Data Rekening</h1> --}}
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/rekening">Kembali</a></li>
                    <li class="breadcrumb-item active">Edit Data Rekening</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Edit Data Rekening</h5>

                            <!-- General Form Elements -->
                            <form action="{{ url('rekening/' . $rekening->slug) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Nama Rekening <sup
                                            class="text-danger">*</sup></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('nama_rek') is-invalid @enderror"
                                            name="nama_rek" value="{{ old('nama_rek', $rekening->nama_rekening) }}">
                                        @error('nama_rek')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Saldo <sup class="text-danger">*</sup></label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control format-rupiah @error('saldo') is-invalid @enderror"
                                            name="saldo"
                                            value="{{ old('saldo', FormatHelper::formatRupiah($rekening->saldo_awal)) }}">

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
                                        <button type="submit" class="btn btn-primary float-end ms-3">Update</button>
                                        <a href="/rekening" class="btn btn-outline-primary float-end">Kembali</a>
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