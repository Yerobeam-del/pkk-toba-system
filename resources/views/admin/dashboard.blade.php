@extends('admin.layouts.app')

@section('content')
<div class="dashboard-header">
    <div>
        <h1 class="page-title">Selamat Datang di Admin Panel</h1>
        <p class="page-subtitle">Ini adalah dashboard admin untuk mengelola konten website PKK Kabupaten Toba.</p>
    </div>
</div>

{{-- Stats Grid --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(15, 107, 99, 0.1); color: var(--primary);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                <path d="M18 14h-8"/>
                <path d="M15 18h-5"/>
                <path d="M10 6h8v4h-8V6Z"/>
            </svg>
        </div>
        <div class="stat-content">
            <h3>Total Berita</h3>
            <p class="stat-number">0</p>
            <span class="stat-label">Berita & Artikel</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(234, 179, 8, 0.1); color: #eab308;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </div>
        <div class="stat-content">
            <h3>Total Desa</h3>
            <p class="stat-number">231</p>
            <span class="stat-label">Desa & Kelurahan</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div class="stat-content">
            <h3>Pengurus</h3>
            <p class="stat-number">12</p>
            <span class="stat-label">Anggota Aktif</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <line x1="3" y1="9" x2="21" y2="9"/>
                <line x1="9" y1="21" x2="9" y2="9"/>
            </svg>
        </div>
        <div class="stat-content">
            <h3>Template</h3>
            <p class="stat-number">8</p>
            <span class="stat-label">Dokumen Tersedia</span>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="card">
    <h2 class="card-title">Aksi Cepat</h2>
    <div class="quick-actions">
        <a href="{{ route('admin.berita.create') }}" class="quick-action-btn">
            <div class="action-icon" style="background: rgba(15, 107, 99, 0.1); color: var(--primary);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                    <path d="M18 14h-8"/>
                    <path d="M15 18h-5"/>
                    <path d="M10 6h8v4h-8V6Z"/>
                </svg>
            </div>
            <span>Tambah Berita</span>
        </a>
        
        <a href="{{ route('admin.desa.index') }}" class="quick-action-btn">
            <div class="action-icon" style="background: rgba(234, 179, 8, 0.1); color: #eab308;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <span>Kelola Desa</span>
        </a>
        
        <a href="{{ route('admin.struktur.index') }}" class="quick-action-btn">
            <div class="action-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <span>Struktur Organisasi</span>
        </a>
        
        <a href="{{ route('admin.template.index') }}" class="quick-action-btn">
            <div class="action-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <line x1="3" y1="9" x2="21" y2="9"/>
                    <line x1="9" y1="21" x2="9" y2="9"/>
                </svg>
            </div>
            <span>Template Dokumen</span>
        </a>
    </div>
</div>
@endsection