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
              <h5 class="card-title"><a href="/rekening/tambah-rekening" class="btn btn-outline-primary">Tambah Rekening</a></h5>
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>No</th>
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
                            <td>{{ $loop -> iteration }}</td>
                            <td>{{ $rek->nama_rekening }}</td>
                            <td>{{ FormatHelper::formatRupiah($rek->saldo_awal) }}</td>
                            <td>{{ FormatHelper::formatRupiah($rek->saldo_akhir) }}</td>
                            <td>{{ FormatHelper::formatTanggal($rek->tanggal) }}</td>
                            <td>
                                <a href="{{ url('rekening/'.$rek->slug.'/edit') }}"><i class="bi bi-pencil-square text-warning"></i></a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#basicModal{{ $rek->id }}"><i class="bi bi-trash text-danger" ></i></a>
                            </td>
                        </tr>

                    @endforeach
                </tbody>
              </table>
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
