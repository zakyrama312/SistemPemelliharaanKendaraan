@php
use Laravolt\Avatar\Facade as Avatar;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Sistem Pemeliharaan Kendaraan</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="/assets/img/favicon.png" rel="icon">
    <link href="/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="/assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="/assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- lightbox -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <!-- Format Rupiah -->
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script>
    <!-- select2 -->
    <!-- <link rel="stylesheet" href="/assets/select2/select2.min.css" /> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <!-- datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Template Main CSS File -->
    <link href="/assets/css/style.css" rel="stylesheet">
    <style>
    .select2-container--bootstrap-5 .select2-selection {
        border: 1px solid #ced4da !important;
        /* Warna border Bootstrap */
        border-radius: 0.375rem;
        /* Sudut border */
        height: calc(2.25rem + 2px);
        /* Sesuaikan tinggi */
        padding: 0.375rem 0.75rem;
        /* Sesuaikan padding */
    }

    .select2-container--bootstrap-5 .select2-selection__arrow {
        height: 100%;
        /* Pastikan panah dropdown tetap sejajar */
    }

    ul.list-group::-webkit-scrollbar {
        width: 6px;
        /* Lebar scrollbar */
    }

    ul.list-group::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        /* Warna thumb (bagian yang bisa digeser) */
        border-radius: 10px;
        /* Membuat ujungnya melengkung */
    }

    ul.list-group::-webkit-scrollbar-track {
        background: transparent;
        /* Warna latar belakang scrollbar */
    }

    .dt-buttons {
        margin-top: 20px;
        margin-bottom: 10px;
    }

    .dataTables_filter {
        margin-top: 20px;
    }

    .dataTables_length {
        margin-top: 25px;
        margin-left: 15px;
        margin-bottom: 10px;
    }

    .dt-buttons .buttons-excel {
        background-color: #28a745 !important;
        /* Warna hijau */
        color: white !important;
        border-radius: 5px;
        border: none;
        padding: 8px 12px;
    }

    .dt-buttons .buttons-print {
        background-color: #007bff !important;
        /* Warna biru */
        color: white !important;
        border-radius: 5px;
        border: none;
        padding: 8px 12px;
    }

    /* Efek hover */
    .dt-buttons .btn-export-excel:hover {
        background-color: #218838 !important;
    }

    .dt-buttons .btn-export-print:hover {
        background-color: #0056b3 !important;
    }

    /* Agar ikon lebih besar */
    .dt-buttons button i {
        font-size: 16px;
    }
    </style>
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="/" class="logo d-flex align-items-center">
                <img src="/assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">Sipemda</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->


        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">


                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="{{ Avatar::create(Auth::user()->name)->toBase64() }}" alt="Profile"
                            class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>{{ Auth::user()->name }}</h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="/logout">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    @extends('layouts.sidebar')
    <!-- End Sidebar-->
    @yield('main')
    <!-- End #main -->

    @extends('layouts.footer')
