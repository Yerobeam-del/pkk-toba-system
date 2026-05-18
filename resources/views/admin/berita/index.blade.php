@extends('admin.layouts.app')

@section('title', 'Manajemen Berita')
@section('page-title', 'Manajemen Berita')

@section('content')
<div style="margin-bottom: 2rem;">
    {{-- Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--primary); margin-bottom: 0.25rem;">Daftar Berita</h1>
            <p style="color: var(--text-muted); margin: 0;">Kelola semua berita dan kegiatan PKK Kabupaten Toba</p>
        </div>
        <a href="{{ route('admin.berita.create') }}" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Berita
        </a>
    </div>

    {{-- Alert Sukses --}}
    @if(session('success'))
    <div style="background: #f0fff4; border-left: 4px solid var(--success); padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px; display: flex; align-items: center; gap: 10px; color: #276749;">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Stats Cards --}}
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-info"><h3>Total Berita</h3><p>{{ $berita->total() }}</p></div>
            <div class="stat-icon">📰</div>
        </div>
        <div class="stat-card green">
            <div class="stat-info"><h3>Dipublikasi</h3><p>{{ $berita->where('is_published', 1)->count() }}</p></div>
            <div class="stat-icon">✅</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-info"><h3>Draft</h3><p>{{ $berita->where('is_published', 0)->count() }}</p></div>
            <div class="stat-icon">📝</div>
        </div>
    </div>

    {{-- Tabel Container --}}
    <div class="card">
        <div class="table-toolbar">
            <div class="search-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                <input type="text" id="searchInput" placeholder="Cari judul atau ringkasan...">
            </div>
        </div>

        <div class="table-container">
            <table class="news-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 0.875rem 1rem; text-align: left; border-bottom: 1px solid var(--border);">Judul Berita</th>
                        <th style="padding: 0.875rem 1rem; text-align: left; border-bottom: 1px solid var(--border);">Kategori</th>
                        <th style="padding: 0.875rem 1rem; text-align: left; border-bottom: 1px solid var(--border);">Tanggal</th>
                        <th style="padding: 0.875rem 1rem; text-align: left; border-bottom: 1px solid var(--border);">Status</th>
                        <th style="padding: 0.875rem 1rem; text-align: center; border-bottom: 1px solid var(--border);">Aksi</th>
                    </tr>
                </thead>
                <tbody id="newsTableBody">
                    @forelse($berita as $item)
                    <tr class="news-row" data-title="{{ strtolower($item->title) }}" data-excerpt="{{ strtolower($item->excerpt) }}" style="transition: background 0.2s;">
                        <td style="padding: 0.875rem 1rem; border-bottom: 1px solid var(--border);">
                            <div class="news-cell">
                                @if($item->image_path)
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="news-thumb" onerror="this.src='{{ asset('assets/landing/images/berita/default.jpg') }}'">
                                @else
                                <div class="news-thumb" style="display: flex; align-items: center; justify-content: center; color: #94a3b8;">📷</div>
                                @endif
                                <div>
                                    <div class="news-title">{{ Str::limit($item->title, 50) }}</div>
                                    <p class="news-excerpt">{{ Str::limit($item->excerpt, 60) }}</p>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 0.875rem 1rem; border-bottom: 1px solid var(--border);">
                            <span class="badge badge-category">{{ $item->category }}</span>
                        </td>
                        <td style="padding: 0.875rem 1rem; border-bottom: 1px solid var(--border); color: var(--text-muted);">
                            {{ $item->published_at?->format('d M Y') ?? '-' }}
                        </td>
                        <td style="padding: 0.875rem 1rem; border-bottom: 1px solid var(--border);">
                            @if($item->is_published)
                            <span class="badge badge-published">✅ Publik</span>
                            @else
                            <span class="badge badge-draft"> Draft</span>
                            @endif
                        </td>
                        <td style="padding: 0.875rem 1rem; border-bottom: 1px solid var(--border);">
                            <div class="action-btns">
                                <a href="{{ route('admin.berita.edit', $item) }}" class="action-btn edit" title="Edit">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form action="{{ route('admin.berita.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Hapus">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                <h3>Belum ada berita</h3>
                                <p style="margin-bottom: 1rem;">Mulai tambahkan berita pertama Anda</p>
                                <a href="{{ route('admin.berita.create') }}" class="btn btn-primary">+ Tambah Berita Pertama</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($berita->hasPages())
        <div style="padding: 1rem; border-top: 1px solid var(--border);">
            {{ $berita->links() }}
        </div>
        @endif
    </div>
</div>

<script>
document.getElementById('searchInput')?.addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.news-row');
    rows.forEach(row => {
        const title = row.dataset.title || '';
        const excerpt = row.dataset.excerpt || '';
        row.style.display = (title.includes(term) || excerpt.includes(term)) ? '' : 'none';
    });
});
</script>
@endsection