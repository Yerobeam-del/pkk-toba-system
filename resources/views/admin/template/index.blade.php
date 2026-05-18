@extends('admin.layouts.app')
@section('title', 'Template PKK')
@section('page-title', 'Manajemen Template PKK')
@section('content')
<div style="margin-bottom:2rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <div>
            <h1 style="font-size:1.75rem;font-weight:700;color:var(--primary);margin-bottom:0.25rem">Template PKK</h1>
            <p style="color:var(--text-muted);margin:0">Kelola template dokumen resmi PKK</p>
        </div>
        <a href="{{ route('admin.template.create') }}" class="btn btn-primary">+ Tambah Template</a>
    </div>

    @if(session('success'))
    <div style="background:#f0fff4;border-left:4px solid var(--success);padding:1rem;margin-bottom:1.5rem;border-radius:8px;color:#276749">{{ session('success') }}</div>
    @endif

    <div class="card" style="margin-bottom:1.5rem">
        <form id="filterForm" method="GET" action="{{ route('admin.template.index') }}" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:center;padding:1rem">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" style="flex:1;min-width:200px" placeholder="🔍 Cari template..." oninput="clearTimeout(window._t);window._t=setTimeout(()=>this.form.submit(),600)">
            <select name="status" class="form-control" style="min-width:150px" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="published" {{ request('status')==='published'?'selected':'' }}>Published</option>
                <option value="draft" {{ request('status')==='draft'?'selected':'' }}>Draft</option>
            </select>
            @if(request('search')||request('status'))<a href="{{ route('admin.template.index') }}" class="btn btn-outline">Reset</a>@endif
        </form>
    </div>

    <div class="card">
        <div class="table-container">
            <table>
                <thead><tr><th style="width:50px">No</th><th>Nama Template</th><th>Tanggal</th><th>Ukuran</th><th>Status</th><th style="width:120px">Aksi</th></tr></thead>
                <tbody>
                    @forelse($templates as $i=>$t)
                    <tr>
                        <td>{{ ($templates->currentPage()-1)*$templates->perPage()+$i+1 }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.75rem">
                                <div style="width:36px;height:36px;border-radius:8px;background:rgba(85,60,154,0.1);display:flex;align-items:center;justify-content:center">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;color:var(--sk-color)"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                </div>
                                <div><div style="font-weight:600">{{ $t->name }}</div><div style="font-size:0.8rem;color:var(--text-muted)">{{ $t->file_name }}</div></div>
                            </div>
                        </td>
                        <td>{{ $t->upload_date?->format('d M Y') }}</td>
                        <td><span style="background:#f1f5f9;padding:0.25rem 0.5rem;border-radius:4px;font-size:0.85rem">{{ $t->file_size }}</span></td>
                        <td>
                            @if($t->status==='published')<span class="tag tag-active">Published</span>@else<span class="tag tag-pending">Draft</span>@endif
                        </td>
                        <td class="actions">
                            <a href="{{ $t->file_url }}" target="_blank" style="color:var(--primary)">👁️</a>
                            <a href="{{ route('admin.template.edit', $t) }}" style="color:var(--primary)">✏️</a>
                            <form action="{{ route('admin.template.destroy', $t) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus template ini?')">@csrf @method('DELETE')<button type="submit" style="background:none;border:none;color:#ef4444;cursor:pointer">🗑️</button></form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:3rem;color:var(--text-muted)">Belum ada template.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($templates->hasPages())
        <div style="padding:1rem;border-top:1px solid var(--border)">{{ $templates->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
<style>.tag{display:inline-block;padding:0.25rem 0.75rem;border-radius:20px;font-size:0.75rem;font-weight:600}.tag-active{background:#dcfce7;color:#166534}.tag-pending{background:#fef3c7;color:#92400e}</style>
@endsection