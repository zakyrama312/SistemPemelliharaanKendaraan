<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link {{ Request::is('/') ? '' : 'collapsed' }}" href="/">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li><!-- End Dashboard Nav -->
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
      <a class="nav-link {{ Request::is('pemeliharaan') ? 'active' : 'collapsed' }}" href="/pemeliharaan">
        <i class="bi bi-gear-wide-connected"></i>
        <span>Pemeliharaan</span>
      </a>
    </li>

    <!-- <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-journal-text"></i><span>Master Data</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a class="nav-link {{ Request::is('pegawai') ? 'active' : 'collapsed' }}" href="/pegawai">
            <i class=" bi bi-people"></i><span>Data Pegawai</span>
          </a>
        </li>
        <li>
          <a class="{{ Request::is('rekening') ? 'active' : 'collapsed' }}" href="/rekening">
            <i class=" bi bi-wallet"></i><span>Data Rekening</span>
          </a>
        </li>
      </ul>
    </li> -->



  </ul>

</aside>