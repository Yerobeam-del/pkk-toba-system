@extends('admin.layouts.app')
@section('title', 'Manajemen Struktur')
@section('page-title', 'Manajemen Struktur Organisasi')

@section('content')
<div style="margin-bottom:2rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <div>
            <h1 style="font-size:1.75rem;font-weight:700;color:var(--primary);margin-bottom:0.25rem">Struktur Organisasi</h1>
            <p style="color:var(--text-muted);margin:0">Kelola data sesuai bagan organisasi asli</p>
        </div>
        <a href="{{ route('admin.struktur.create') }}" class="btn btn-primary">+ Tambah Anggota</a>
    </div>

    @if(session('success'))
    <div style="background:#f0fff4;border-left:4px solid var(--success);padding:1rem;margin-bottom:1.5rem;border-radius:8px;color:#276749">
        {{ session('success') }}
    </div>
    @endif

    {{-- TABS: Sesuai Bagan Asli --}}
    <div class="tab-nav-struktur">
        <button class="tab-btn active" onclick="switchTab('pengurus', this)">Pengurus Inti (Ketua I-IV & Staf)</button>
        <button class="tab-btn" onclick="switchTab('pokja1', this)">Pokja I ({{ $pokjaList->find(1)?->members_count ?? 0 }})</button>
        <button class="tab-btn" onclick="switchTab('pokja2', this)">Pokja II ({{ $pokjaList->find(2)?->members_count ?? 0 }})</button>
        <button class="tab-btn" onclick="switchTab('pokja3', this)">Pokja III ({{ $pokjaList->find(3)?->members_count ?? 0 }})</button>
        <button class="tab-btn" onclick="switchTab('pokja4', this)">Pokja IV ({{ $pokjaList->find(4)?->members_count ?? 0 }})</button>
    </div>

    <div class="card">
        {{-- Tab Pengurus Inti --}}
        <div id="tab-pengurus" class="tab-content-struktur active">
            <div class="table-container">
                <table>
                    <thead><tr><th>Foto</th><th>Nama</th><th>Jabatan</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse($pengurusInti as $m)
                        <tr>
                            <td>
                                @if($m->photo_path)
                                <img src="{{ asset('storage/'.$m->photo_path) }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover">
                                @else
                                <div style="width:40px;height:40px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8">👤</div>
                                @endif
                            </td>
                            <td style="font-weight:600">{{ $m->name }}</td>
                            <td><span class="tag tag-role">{{ $m->position }}</span></td>
                            <td class="actions">
                                <a href="{{ route('admin.struktur.edit', $m) }}" class="btn-edit" title="Edit">✏️</a>
                                <form action="{{ route('admin.struktur.destroy', $m) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-del" title="Hapus">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--text-muted)">Belum ada data. Silakan tambah Ketua Pembina, Ketua TP PKK, Ketua I-IV, dll.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabs Pokja I-IV --}}
        @foreach($pokjaList as $pokja)
        <div id="tab-pokja{{ $pokja->id }}" class="tab-content-struktur">
            <div class="table-container">
                <table>
                    <thead><tr><th>Foto</th><th>Nama</th><th>Jabatan</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse($pokja->members as $m)
                        <tr>
                            <td>
                                @if($m->photo_path)
                                <img src="{{ asset('storage/'.$m->photo_path) }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover">
                                @else
                                <div style="width:40px;height:40px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8">👤</div>
                                @endif
                            </td>
                            <td style="font-weight:600">{{ $m->name }}</td>
                            <td><span class="tag tag-role">{{ $m->position }}</span></td>
                            <td class="actions">
                                <a href="{{ route('admin.struktur.edit', $m) }}" class="btn-edit">✏️</a>
                                <form action="{{ route('admin.struktur.destroy', $m) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-del">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--text-muted)">Belum ada anggota di {{ $pokja->name }}.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
function switchTab(tabId, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content-struktur').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-' + tabId).classList.add('active');
}
</script>
@endsection