@extends('layouts.main')
@section('main')
    @php
        use App\Helpers\FormatHelper;
      @endphp
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Data Rekening</h1>

        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><a href="/rekening/tambah-rekening"
                                    class="btn btn-outline-primary">Tambah
                                    Rekening</a></h5>
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
                            <!-- Table with stripped rows -->
                            <div class="table-responsive">
                                <table class="table datatable table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Rekening</th>
                                            <th>Nama Rekening</th>
                                            <th>Saldo</th>
                                            <th>Saldo Akhir</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rekening as $rek)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $rek->no_rekening }}</td>
                                                <td>{{ $rek->nama_rekening }}</td>
                                                <td>{{ FormatHelper::formatRupiah($rek->saldo_awal) }}</td>
                                                <td>{{ FormatHelper::formatRupiah($rek->saldo_akhir) }}</td>
                                                <td>{{ FormatHelper::formatTanggal($rek->tanggal) }}</td>
                                                <td>
                                                    <a href="{{ url('rekening/' . $rek->slug . '/edit') }}"><i
                                                            class="bi bi-pencil-square text-warning"></i></a>
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#basicModal{{ $rek->id }}"><i
                                                            class="bi bi-trash text-danger"></i></a>
                                                </td>
                                            </tr>

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>

    {{-- Modal Delete --}}
    @include('rekening.rekening-delete')
@endsection
