@extends('admin.layouts.app')
@section('title', 'SK & Dokumen')
@section('page-title', 'Manajemen SK & Dokumen')

@section('content')
<div style="margin-bottom:2rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <div>
            <h1 style="font-size:1.75rem;font-weight:700;color:var(--primary);margin-bottom:0.25rem">SK & Dokumen</h1>
            <p style="color:var(--text-muted);margin:0">Kelola surat keputusan dan dokumen dari pusat</p>
        </div>
        <a href="{{ route('admin.sk.create') }}" class="btn btn-primary">+ Tambah Dokumen</a>
    </div>

    @if(session('success'))
    <div style="background:#f0fff4;border-left:4px solid var(--success);padding:1rem;margin-bottom:1.5rem;border-radius:8px;color:#276749">
        {{ session('success') }}
    </div>
    @endif

    {{-- ✅ FILTER FORM: Method GET + Action Laravel Route --}}
    <div class="card" style="margin-bottom:1.5rem">
        <form id="filterForm" method="GET" action="{{ route('admin.sk.index') }}" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:center;padding:1rem">
            
            {{-- Search Input --}}
            <input type="text" id="searchInput" name="search" placeholder="🔍 Cari nama dokumen..." 
                   value="{{ request('search') }}" 
                   class="form-control" style="flex:1;min-width:200px" autocomplete="off">
            
            {{-- Status Dropdown --}}
            <select id="statusSelect" name="status" class="form-control" style="min-width:150px">
                <option value="">Semua Status</option>
                <option value="published" {{ request('status')==='published'?'selected':'' }}>Published</option>
                <option value="draft" {{ request('status')==='draft'?'selected':'' }}>Draft</option>
            </select>
            
            {{-- Reset Button (only show if filter active) --}}
            @if(request('search') || request('status'))
                <a href="{{ route('admin.sk.index') }}" class="btn btn-outline" style="white-space:nowrap">🔄 Reset</a>
            @endif
            
            <span id="filterStatus" style="font-size:0.85rem;color:var(--text-muted);display:none;margin-left:auto">
                ✅ Filter diterapkan
            </span>
        </form>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width:50px">No</th>
                        <th>Nama Dokumen</th>
                        <th>Tanggal</th>
                        <th>Ukuran</th>
                        <th>Status</th>
                        <th style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($dokumens as $index => $doc)
                    <tr>
                        <td>{{ ($dokumens->currentPage()-1)*$dokumens->perPage() + $index+1 }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.75rem">
                                <div style="width:36px;height:36px;border-radius:8px;background:rgba(85,60,154,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;color:var(--sk-color)">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                        <polyline points="10 9 9 9 8 9"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-weight:600">{{ $doc->name }}</div>
                                    <div style="font-size:0.8rem;color:var(--text-muted)">{{ $doc->file_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $doc->document_date?->format('d M Y') }}</td>
                        <td><span style="background:#f1f5f9;padding:0.25rem 0.5rem;border-radius:4px;font-size:0.85rem">{{ $doc->file_size }}</span></td>
                        <td>
                            @if($doc->status === 'published')
                                <span class="tag tag-active">Published</span>
                            @else
                                <span class="tag tag-pending">Draft</span>
                            @endif
                        </td>
                        <td class="actions">
                            <a href="{{ $doc->file_url }}" target="_blank" class="btn-edit" title="Preview" style="color:var(--primary)">👁️</a>
                            <a href="{{ route('admin.sk.edit', $doc) }}" class="btn-edit" title="Edit">✏️</a>
                            <form action="{{ route('admin.sk.destroy', $doc) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus dokumen ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del" title="Hapus">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:3rem;color:var(--text-muted)">
                            Belum ada dokumen. <a href="{{ route('admin.sk.create') }}" style="color:var(--primary);text-decoration:underline">Tambah dokumen pertama →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($dokumens->hasPages())
        <div id="paginationContainer" style="padding:1rem;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem">
            <div style="color:var(--text-muted);font-size:0.9rem">
                Menampilkan {{ $dokumens->firstItem() }}-{{ $dokumens->lastItem() }} dari {{ $dokumens->total() }} dokumen
            </div>
            {!! $dokumens->withQueryString()->links() !!}
        </div>
        @endif
    </div>
</div>

{{-- ✅ SIMPLE JAVASCRIPT: Submit form directly --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const statusSelect = document.getElementById('statusSelect');
    const filterStatus = document.getElementById('filterStatus');
    
    let debounceTimer;
    
    // Function to submit form (Laravel will handle the query)
    function submitFilter() {
        // Show status indicator
        filterStatus.style.display = 'inline';
        setTimeout(() => { filterStatus.style.display = 'none'; }, 1500);
        
        // Submit the form (method=GET, action=route)
        form.submit();
    }
    
    // Debounced search input (wait 600ms after typing stops)
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(submitFilter, 600);
    });
    
    // Immediate submit for dropdown change
    statusSelect.addEventListener('change', submitFilter);
    
    // Allow Enter key to trigger immediately
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitFilter();
        }
    });
});
</script>

{{-- CSS for status tags --}}
<style>
.tag { display:inline-block; padding:0.25rem 0.75rem; border-radius:20px; font-size:0.75rem; font-weight:600; }
.tag-active { background:#dcfce7; color:#166534; }
.tag-pending { background:#fef3c7; color:#92400e; }
</style>
@endsection