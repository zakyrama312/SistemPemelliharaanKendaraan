<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link {{ Request::is('/') ? '' : 'collapsed' }}" href="/">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li><!-- End Dashboard Nav -->
    <li class="nav-heading">Master Data</li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('pegawai') ? 'active' : 'collapsed' }}" href="/pegawai">
        <i class="bi bi-people"></i>
        <span>Data Pegawai</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('rekening') ? 'active' : 'collapsed' }}" href="/rekening">
        <i class="bi bi-wallet"></i>
        <span>Data Rekening</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('kendaraan') ? 'active' : 'collapsed' }}" href="/kendaraan">
        <i class="bi bi-car-front"></i>
        <span>Data Kendaraan</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('pengeluaran') ? 'active' : 'collapsed' }}" href="/pengeluaran">
        <i class="bi bi-journal"></i>
        <span>Data Pengeluaran</span>
      </a>
    </li>

    <li class="nav-heading">Pengeluaran</li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('pemeliharaan') ? 'active' : 'collapsed' }}" href="/pemeliharaan">
        <i class="bi bi-gear-wide-connected"></i>
        <span>Pemeliharaan</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('pengeluaran-bbm') ? 'active' : 'collapsed' }}" href="/pengeluaran-bbm">
        <i class="bi bi-fuel-pump"></i>
        <span>Bahan Bakar</span>
      </a>
    </li>

    <li class="nav-heading">Pajak</li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('pajak-tahunan') ? 'active' : 'collapsed' }}" href="/pajak-tahunan">
        <i class="bi bi-cash"></i>
        <span>Pajak Tahunan</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('pajak-plat') ? 'active' : 'collapsed' }}" href="/pajak-plat">
        <i class="bi bi-cash"></i>
        <span>Pajak Plat</span>
      </a>
    </li>




  </ul>

</aside>