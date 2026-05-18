<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - PKK Kabupaten Toba')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Admin -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
            --header-height: 64px;
            --primary: #14b8a6;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: rgba(20,184,166,0.15);
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* RESET & BASE */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9; /* Abu-abu muda agar konten jelas */
            color: #334155;
            overflow-x: hidden; /* Mencegah scroll horizontal */
        }

        /* LAYOUT WRAPPER */
        .admin-layout {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--text-light);
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
            border-right: 1px solid var(--border);
        }

        /* Collapsed State */
        .admin-layout.collapsed .sidebar {
            width: var(--sidebar-collapsed-width);
        }

        /* Sidebar Header */
        .sidebar-header {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            gap: 0.75rem;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
            overflow: hidden;
        }
        .sidebar-logo {
            width: 36px; height: 36px;
            background: var(--primary);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 1.1rem; flex-shrink: 0;
        }
        .sidebar-title {
            transition: var(--transition);
            opacity: 1;
            width: auto;
        }
        .admin-layout.collapsed .sidebar-title {
            opacity: 0;
            width: 0;
        }
        .sidebar-title h1 { font-size: 1rem; font-weight: 700; line-height: 1.2; }
        .sidebar-title small { font-size: 0.7rem; color: var(--text-muted); font-weight: 500; }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 1rem 0.75rem;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .nav-section-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            padding: 0.5rem 0.5rem;
            margin-top: 1rem;
            white-space: nowrap;
            transition: var(--transition);
        }
        .admin-layout.collapsed .nav-section-title {
            opacity: 0;
            height: 0;
            padding: 0;
            margin: 0;
        }

        /* MAIN CONTENT WRAPPER */
        .main-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            min-width: 0; /* PENTING: Mencegah flex item melebar */
        }
        .admin-layout.collapsed .main-wrapper {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* TOP HEADER */
        .top-header {
            height: var(--header-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 900;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .toggle-btn {
            width: 36px; height: 36px;
            display: flex; align-items: center; justify-content: center;
            background: transparent;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            color: #64748b;
            transition: var(--transition);
        }
        .toggle-btn:hover { background: #f1f5f9; color: var(--primary); }
        .toggle-btn svg { width: 20px; height: 20px; }

        .header-right { display: flex; align-items: center; gap: 1rem; }
        .user-info { font-size: 0.9rem; font-weight: 600; color: #334155; }

        /* CONTENT AREA */
        .content-area {
            padding: 2rem;
            flex: 1;
            width: 100%;
            overflow-x: hidden; /* Mencegah konten melebar */
        }

        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .admin-layout.mobile-open .sidebar { transform: translateX(0); }
            .main-wrapper { margin-left: 0 !important; }
            .sidebar-overlay {
                display: none; position: fixed; inset: 0;
                background: rgba(0,0,0,0.5); z-index: 999;
                transition: opacity 0.3s;
            }
            .admin-layout.mobile-open .sidebar-overlay { display: block; opacity: 1; }
            /* Reset collapsed state on mobile */
            .admin-layout.collapsed .sidebar { width: var(--sidebar-width); }
            .admin-layout.collapsed .nav-item .nav-text { opacity: 1; width: auto; position: static; box-shadow: none; }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="admin-layout" id="adminLayout">
        
        <!-- Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">PKK</div>
                <div class="sidebar-title">
                    <h1>Admin Panel</h1>
                    <small>Kabupaten Toba</small>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section-title">Main Navigation</div>
                
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <div class="nav-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    </div>
                    <span class="nav-text">Beranda</span>
                </a>
                <a href="{{ route('admin.hero-sliders.index') }}" class="nav-item {{ request()->routeIs('admin.hero-sliders.*') ? 'active' : '' }}">
                    <div class="nav-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    </div>
                    <span class="nav-text">Kelola Beranda</span>
                </a>
                <a href="{{ route('admin.struktur.index') }}" class="nav-item {{ request()->routeIs('admin.struktur.*') ? 'active' : '' }}">
                    <div class="nav-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <span class="nav-text">Struktur</span>
                </a>
                <a href="{{ route('admin.aplikasi.index') }}" class="nav-item {{ request()->routeIs('admin.aplikasi.*') ? 'active' : '' }}">
                    <div class="nav-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    </div>
                    <span class="nav-text">Aplikasi</span>
                </a>
                <a href="{{ route('admin.berita.index') }}" class="nav-item {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
                    <div class="nav-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
                    </div>
                    <span class="nav-text">Berita</span>
                </a>
                <a href="{{ route('admin.desa.index') }}" class="nav-item {{ request()->routeIs('admin.desa.*') ? 'active' : '' }}">
                    <div class="nav-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    <span class="nav-text">Desa</span>
                </a>
                <a href="{{ route('admin.sk.index') }}" class="nav-item {{ request()->routeIs('admin.sk.*') ? 'active' : '' }}">
                    <div class="nav-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </div>
                    <span class="nav-text">SK & Dokumen</span>
                </a>
                <a href="{{ route('admin.template.index') }}" class="nav-item {{ request()->routeIs('admin.template.*') ? 'active' : '' }}">
                    <div class="nav-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                    </div>
                    <span class="nav-text">Template</span>
                </a>
                <a href="{{ route('admin.tentang.index') }}" class="nav-item {{ request()->routeIs('admin.tentang.*') ? 'active' : '' }}">
                    <div class="nav-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    </div>
                    <span class="nav-text">Tentang</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-wrapper">
            <header class="top-header">
                <button class="toggle-btn" id="toggleBtn" title="Toggle Sidebar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="9" y1="3" x2="9" y2="21"></line>
                    </svg>
                </button>
                <div class="header-right">
                    <span class="user-info">{{ Auth::user()->name ?? 'Admin PKK' }}</span>
                </div>
            </header>

            <main class="content-area">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const layout = document.getElementById('adminLayout');
            const toggleBtn = document.getElementById('toggleBtn');
            const overlay = document.getElementById('sidebarOverlay');
            const navItems = document.querySelectorAll('.nav-item');

            // 1. Restore sidebar state from localStorage
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            const isMobile = window.innerWidth <= 1024;

            if (!isMobile && isCollapsed) {
                layout.classList.add('collapsed');
            }

            // 2. Toggle Function
            toggleBtn.addEventListener('click', () => {
                if (window.innerWidth <= 1024) {
                    // Mobile: open/close overlay
                    layout.classList.toggle('mobile-open');
                } else {
                    // Desktop: collapse/expand
                    layout.classList.toggle('collapsed');
                    localStorage.setItem('sidebarCollapsed', layout.classList.contains('collapsed'));
                }
            });

            // 3. Close sidebar on nav click (mobile)
            navItems.forEach(item => {
                item.addEventListener('click', () => {
                    if (window.innerWidth <= 1024) {
                        layout.classList.remove('mobile-open');
                    }
                });
            });

            // 4. Close overlay when clicking outside
            overlay.addEventListener('click', () => {
                layout.classList.remove('mobile-open');
            });

            // 5. Handle window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth > 1024) {
                    layout.classList.remove('mobile-open');
                    if (localStorage.getItem('sidebarCollapsed') === 'true') {
                        layout.classList.add('collapsed');
                    } else {
                        layout.classList.remove('collapsed');
                    }
                } else {
                    layout.classList.remove('collapsed');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>