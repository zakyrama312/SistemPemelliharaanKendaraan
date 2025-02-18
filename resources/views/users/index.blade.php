@extends('layouts.main')
@section('main')
  <main id="main" class="main">

    <div class="pagetitle">
    <h1>Data Pegawai</h1>

    </div><!-- End Page Title -->

    <section class="section">
    <div class="row">
      <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
        <h5 class="card-title"><a href="/pegawai/tambah-pegawai" class="btn btn-outline-primary">Tambah
          Pegawai</a>
        </h5>
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
            <th>NIP</th>
            <th>Nama</th>
            {{-- <th>No. Telp</th> --}}
            <th>Username</th>
            <th>Role</th>
            <th>Aksi</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($users as $user)
        <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $user->nip }}</td>
        <td>{{ $user->name }}</td>
        {{-- <td>{{ $user->telp }}</td> --}}
        <td>{{ $user->username  }}</td>
        <td>{{ $user->role == 'user' ? 'Pengguna' : $user->role }}</td>
        <td>
        <a href="{{ url('pegawai/' . $user->slug . '/edit') }}"><i
          class="bi bi-pencil-square text-warning"></i></a>
        <a href="#" data-bs-toggle="modal" data-bs-target="#basicModal{{ $user->id }}"><i
          class="bi bi-trash text-danger"></i></a>
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
  @include('users.users-delete')
@endsection