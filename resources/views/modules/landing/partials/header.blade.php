<nav class="navbar" id="navbar">
    <div class="navbar-inner">
        <div class="navbar-brand" onclick="navigateTo('beranda')">
            <img src="{{ asset('assets/landing/images/PKK-Logo.png') }}" alt="PKK Logo" class="navbar-logo">
            <div class="navbar-title">
                <span>PKK KAB. TOBA</span>
                <span>Kabupaten Toba, Sumatera Utara</span>
            </div>
        </div>
        <ul class="navbar-links" id="navLinks">
            <li><a onclick="navigateTo('beranda')" class="nav-link active-link" data-page="beranda">Beranda</a></li>
            <li><a onclick="navigateTo('aplikasi')" class="nav-link" data-page="aplikasi">Aplikasi</a></li>
            <li><a onclick="navigateTo('berita')" class="nav-link" data-page="berita">Berita</a></li>
            <li><a onclick="navigateTo('desa')" class="nav-link" data-page="desa">Desa</a></li>
            <li><a onclick="navigateTo('sk')" class="nav-link" data-page="sk">SK & Dokumen</a></li>
            <li><a onclick="navigateTo('template')" class="nav-link" data-page="template">Template</a></li>
            <li><a onclick="navigateTo('tentang')" class="nav-link" data-page="tentang">Tentang</a></li>
        </ul>
        <div class="hamburger" id="hamburger" onclick="toggleMenu()">
            <span></span><span></span><span></span>
        </div>
    </div>
</nav>