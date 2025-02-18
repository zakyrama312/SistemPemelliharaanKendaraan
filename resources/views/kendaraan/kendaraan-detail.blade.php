@extends('layouts.main')
@section('main')
    @php
        use App\Helpers\FormatHelper;

        use Laravolt\Avatar\Facade as Avatar;
    @endphp
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>{{ $kendaraanData->no_polisi }} - {{ $kendaraanData->merk }} - {{ $kendaraanData->model }} </h1>

        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Status</div>
                        <div class="card-body">
                            <div class="alert {{ $kendaraanData->status == 'aktif' ? "alert-success" : "alert-danger" }}  alert-dismissible fade show mt-3"
                                role="alert">
                                <i
                                    class="bi {{ $kendaraanData->status == 'aktif' ? "bi-check-circle" : "bi-exclamation-octagon" }}  me-1"></i>
                                {{ $kendaraanData->status == 'aktif' ? "Kendaraan Aktif" : "Tidak Aktif" }}
                            </div>
                            <div class="alert {{ $kendaraanData->alert }} alert-dismissible fade show" role="alert">
                                <i class="bi {{ $kendaraanData->icon }} me-1"></i>
                                {{ $kendaraanData->status_hari }} {{ $kendaraanData->status_pemeliharaan }}
                            </div>
                            <!-- <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                                                            <i class="bi bi-exclamation-octagon me-1"></i>
                                                                            Another danger alert with icon—check it out!
                                                                        </div> -->
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">Spesifikasi Kendaraan</div>
                        <img src="{{ asset('kendaraanImage/' . $kendaraanData->foto) }}" class="card-img-top"
                            alt="foto kendaraan">
                        <div class="card-body">
                            <div class="row mt-3">
                                <label class="col-sm-3 col-form-label">Plat Nomor</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly
                                        value="{{ $kendaraanData->no_polisi }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 col-form-label">Merk</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly value="{{ $kendaraanData->merk }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 col-form-label">Model</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly value="{{ $kendaraanData->model }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 col-form-label">Warna</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly value="{{ $kendaraanData->warna }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 col-form-label">Nomor Rangka</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly
                                        value="{{ $kendaraanData->no_rangka }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 col-form-label">Nomor Mesin</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly value="{{ $kendaraanData->no_mesin }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 col-form-label">Tahun Pembuatan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly
                                        value="{{ FormatHelper::formatTanggal($kendaraanData->tahun_pembuatan) }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 col-form-label">Masa Aktif Plat</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly
                                        value="{{ FormatHelper::formatTanggal($kendaraanData->masa_aktif_plat) }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 col-form-label">Jumlah Roda</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly
                                        value="{{ $kendaraanData->jumlah_roda }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 col-form-label">Bahan Bakar</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly
                                        value="{{ $kendaraanData->bahan_bakar }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly value="{{ $kendaraanData->status }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Pemeliharaan</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="card mt-3 bg-primary">
                                        <div class="card-body text-white">
                                            <h4 class="card-title text-white">Pemeliharaan</h4>
                                            <h4 class="text-center "><b>{{ $kendaraanData->pemeliharaan_count }}x</b></h4>
                                        </div>
                                        <div class="card-footer bg-primary text-center">
                                            <a href="/pemeliharaan/{{ $kendaraanData->slug }}/show"
                                                class="text-white">Detail <i class="bi bi-arrow-down-right-square"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card mt-3 bg-warning">
                                        <div class="card-body text-white">
                                            <h4 class="card-title text-white">Total Biaya</h4>
                                            <h4 class="text-center ">
                                                <b>{{ FormatHelper::formatRupiah($kendaraanData->pemeliharaan_sum_biaya) }}</b>
                                            </h4>

                                        </div>
                                        <div class="card-footer bg-warning text-center">
                                            <a href="/pemeliharaan/{{ $kendaraanData->slug }}/show"
                                                class="text-white">Detail <i class="bi bi-arrow-down-right-square"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header">3 Pemeliharaan Terakhir</div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nama Bengkel</th>
                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th>Biaya</th>
                                </tr>
                                @foreach ($pemeliharaan as $p)
                                    <tr>
                                        <td>{{ $p->bengkel }}</td>
                                        <td>{{ FormatHelper::formatTanggal($p->tanggal_pemeliharaan_sebelumnya) }}</td>
                                        <td>{{ $p->deskripsi }}</td>
                                        <td>{{ FormatHelper::formatRupiah($p->biaya) }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header">3 Pembelian BBM Terakhir</div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Biaya</th>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header">Pengguna</div>
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header bg-success text-white d-flex align-items-center">
                                    <img src="{{ Avatar::create($kendaraanData->user->name)->toBase64() }}" width="50"
                                        alt="Profile" class="rounded-circle me-3">
                                    <div>
                                        <h5 class="mb-0">{{ $kendaraanData->user->name }}</h5>
                                        <small>NIP {{ $kendaraanData->user->nip }}</small>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p class="mb-0">Pemakai</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </main>

@endsection