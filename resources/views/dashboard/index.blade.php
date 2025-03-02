@extends('layouts.main')
@section('main')
    @if (Auth::user()->role == 'admin')
        @include('dashboard.dashboard-admin')
    @elseif (Auth::user()->role == 'user')
        @include('dashboard.dashboard-pengguna')
    @endif
@endsection