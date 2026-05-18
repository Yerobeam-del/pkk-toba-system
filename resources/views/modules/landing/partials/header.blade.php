<nav class="navbar" id="navbar">
    <style>
        /* --- CSS NAVBAR UTAMA --- */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0;
            z-index: 1000;
            /* PERBAIKAN 1: Transparan secara default */
            background: transparent; 
            transition: all 0.3s ease;
        }

        /* Efek saat scroll ke bawah (Menjadi Solid) */
        .navbar.scrolled {
            background: rgba(20, 83, 76, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .navbar-inner {
            max-width: 1400px; margin: 0 auto;
            padding: 1rem 2rem;
            display: flex; align-items: center; justify-content: space-between;
            position: relative;
        }

        /* Logo */
        .navbar-brand { display: flex; align-items: center; gap: 12px; cursor: pointer; z-index: 1002; }
        .navbar-logo { width: 40px; height: 40px; object-fit: contain; }
        .navbar-title { display: flex; flex-direction: column; line-height: 1.2; }
        .navbar-title span:first-child { font-weight: 800; font-size: 1.05rem; color: #fff; letter-spacing: 0.5px; }
        .navbar-title span:last-child { font-size: 0.7rem; color: rgba(255,255,255,0.7); font-weight: 500; }

        /* Desktop Links */
        .navbar-links { display: flex; align-items: center; gap: 8px; list-style: none; margin: 0; padding: 0; }
        .nav-link {
            color: rgba(255,255,255,0.9); text-decoration: none; font-size: 0.875rem; font-weight: 600;
            padding: 6px 10px; border-radius: 6px; transition: all 0.2s ease; cursor: pointer;
        }
        .nav-link:hover { color: #fff; background: rgba(255,255,255,0.15); }
        
        /* Warna Aktif (Kuning) */
        .nav-link.active-link { color: #fbbf24; background: rgba(251, 191, 36, 0.15); font-weight: 700; }

        /* Hamburger Button */
        .hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; padding: 5px; z-index: 1002; background: none; border: none; }
        .hamburger span { width: 24px; height: 2px; background: #fff; border-radius: 2px; transition: all 0.3s ease; }
        
        /* Animasi X pada Hamburger */
        .hamburger.active span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }
        .hamburger.active span:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }
        .hamburger.active span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        /* --- MOBILE MENU DROPDOWN --- */
        .mobile-menu {
            position: absolute; top: 100%; left: 0; right: 0;
            background: rgba(20, 83, 76, 0.98);
            backdrop-filter: blur(15px);
            padding: 1rem 2rem 2rem 2rem;
            border-radius: 0 0 16px 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            
            /* Animasi Muncul */
            opacity: 0; visibility: hidden; transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 999;
            
            /* Layout Vertikal */
            display: flex; flex-direction: column; gap: 0.5rem; 
        }

        .mobile-menu.open { opacity: 1; visibility: visible; transform: translateY(0); }

        /* Link di dalam Mobile Menu */
        .mobile-menu .nav-link {
            display: block; width: 100%; padding: 12px 15px;
            border-radius: 8px; border-bottom: 1px solid rgba(255,255,255,0.05);
            font-size: 1rem; text-align: left;
        }
        .mobile-menu .nav-link:hover { background: rgba(255,255,255,0.05); }

        /* Responsive Logic */
        @media (max-width: 1100px) {
            .navbar-links { display: none; }
            .hamburger { display: flex; }
            .navbar-title span:last-child { display: none; }
        }
        @media (min-width: 1101px) {
            .mobile-menu { display: none !important; }
        }
    </style>

    <div class="navbar-inner">
        <div class="navbar-brand" onclick="navigateTo('beranda'); updateActiveNav('beranda')">
            <img src="{{ asset('assets/landing/images/PKK-Logo.png') }}" alt="Logo" class="navbar-logo">
            <div class="navbar-title">
                <span>PKK KAB. TOBA</span>
                <span>Kabupaten Toba, Sumatera Utara</span>
            </div>
        </div>
        
        {{-- Menu Desktop --}}
        <ul class="navbar-links" id="navLinks">
            <li><a onclick="navigateTo('beranda'); updateActiveNav('beranda')" class="nav-link active-link" data-page="beranda">Beranda</a></li>
            <li><a onclick="navigateTo('struktur'); updateActiveNav('struktur')" class="nav-link" data-page="struktur">Struktur</a></li>
            <li><a onclick="navigateTo('aplikasi'); updateActiveNav('aplikasi')" class="nav-link" data-page="aplikasi">Aplikasi</a></li>
            <li><a onclick="navigateTo('berita'); updateActiveNav('berita')" class="nav-link" data-page="berita">Berita</a></li>
            <li><a onclick="navigateTo('desa'); updateActiveNav('desa')" class="nav-link" data-page="desa">Desa</a></li>
            <li><a onclick="navigateTo('sk'); updateActiveNav('sk')" class="nav-link" data-page="sk">SK & Dokumen</a></li>
            <li><a onclick="navigateTo('template'); updateActiveNav('template')" class="nav-link" data-page="template">Template</a></li>
            <li><a onclick="navigateTo('tentang'); updateActiveNav('tentang')" class="nav-link" data-page="tentang">Tentang</a></li>
        </ul>

        {{-- Tombol Hamburger --}}
        <button class="hamburger" id="hamburgerBtn" onclick="toggleMobileMenu()">
            <span></span><span></span><span></span>
        </button>
    </div>

    {{-- Menu Mobile (Dropdown Vertikal) --}}
    <div class="mobile-menu" id="mobileMenu">
        <a onclick="handleMobileClick('beranda')" class="nav-link" data-page="beranda">Beranda</a>
        <a onclick="handleMobileClick('struktur')" class="nav-link" data-page="struktur">Struktur</a>
        <a onclick="handleMobileClick('aplikasi')" class="nav-link" data-page="aplikasi">Aplikasi</a>
        <a onclick="handleMobileClick('berita')" class="nav-link" data-page="berita">Berita</a>
        <a onclick="handleMobileClick('desa')" class="nav-link" data-page="desa">Desa</a>
        <a onclick="handleMobileClick('sk')" class="nav-link" data-page="sk">SK & Dokumen</a>
        <a onclick="handleMobileClick('template')" class="nav-link" data-page="template">Template</a>
        <a onclick="handleMobileClick('tentang')" class="nav-link" data-page="tentang">Tentang</a>
    </div>
</nav>

<script>
    // 1. Fungsi Utama saat klik menu di HP
    function handleMobileClick(pageId) {
        if (typeof navigateTo === 'function') navigateTo(pageId);
        updateActiveNav(pageId);
        toggleMobileMenu(); // Tutup menu
    }

    // 2. Fungsi Toggle Hamburger
    function toggleMobileMenu() {
        const btn = document.getElementById('hamburgerBtn');
        const menu = document.getElementById('mobileMenu');
        btn.classList.toggle('active');
        menu.classList.toggle('open');
    }

    // 3. Fungsi PENTING: Update Highlight Navbar (Kuning)
    function updateActiveNav(pageId) {
        // Hapus kelas aktif dari SEMUA link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active-link');
        });
        
        // Tambah kelas aktif ke link yang sesuai (Desktop & Mobile)
        document.querySelectorAll(`.nav-link[data-page="${pageId}"]`).forEach(link => {
            link.classList.add('active-link');
        });
    }

    // 4. Scroll Effect (Transparan -> Solid)
    window.addEventListener('scroll', () => {
        const nav = document.getElementById('navbar');
        if (window.scrollY > 50) nav.classList.add('scrolled');
        else nav.classList.remove('scrolled');
    });

    // 5. FIX: Aktifkan menu SAAT RELOAD
    document.addEventListener('DOMContentLoaded', () => {
        // Cek apakah ada hash di URL, jika tidak default ke 'beranda'
        const hash = window.location.hash.replace('#', '');
        const currentPage = hash || 'beranda';
        
        // Panggil fungsi highlight agar menu menyala kuning
        updateActiveNav(currentPage);
        
        // Cek posisi scroll saat load agar background navbar sesuai
        if (window.scrollY > 50) {
             document.getElementById('navbar').classList.add('scrolled');
        }
    });
</script>