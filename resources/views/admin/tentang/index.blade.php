@extends('admin.layouts.app')
@section('title', 'Kelola Tentang Kami')
@section('page-title', 'Kelola Halaman Tentang Kami')

@section('content')
<div style="margin-bottom:2rem">
    @if(session('success'))
    <div style="background:#f0fff4;border-left:4px solid var(--success);padding:1rem;margin-bottom:1.5rem;border-radius:8px;color:#276749">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <form action="{{ route('admin.tentang.update') }}" method="POST">
            @csrf
            
            <div style="padding:1.5rem;border-bottom:1px solid var(--border);margin-bottom:1.5rem">
                <h3 style="font-size:1.1rem;font-weight:700;color:var(--primary);margin-bottom:0.5rem">📝 Informasi Umum</h3>
                <p style="color:var(--text-muted);font-size:0.9rem;margin:0">Edit judul dan deskripsi halaman</p>
            </div>
            
            <div style="padding:0 1.5rem 1.5rem">
                <div class="form-group">
                    <label>Judul Halaman *</label>
                    <input type="text" name="judul" class="form-control" value="{{ old('judul', $tentang->judul) }}" required>
                </div>
                
                <div class="form-group">
                    <label>Subjudul *</label>
                    <input type="text" name="subjudul" class="form-control" value="{{ old('subjudul', $tentang->subjudul) }}" required>
                </div>
                
                <div class="form-group">
                    <label>Heading Utama *</label>
                    <input type="text" name="heading" class="form-control" value="{{ old('heading', $tentang->heading) }}" required 
                           placeholder="Contoh: Memberdayakan Keluarga, Mensejahterakan Masyarakat">
                </div>
                
                <div class="form-group">
                    <label>Deskripsi *</label>
                    <textarea name="deskripsi" class="form-control" rows="4" required>{{ old('deskripsi', $tentang->deskripsi) }}</textarea>
                </div>
            </div>
            
            <div style="padding:1.5rem;border-bottom:1px solid var(--border);margin-bottom:1.5rem;background:#f8fafc">
                <h3 style="font-size:1.1rem;font-weight:700;color:var(--primary);margin-bottom:0.5rem">📋 Daftar Program</h3>
                <p style="color:var(--text-muted);font-size:0.9rem;margin:0">Tambahkan atau edit program-program PKK</p>
            </div>
            
            <div style="padding:0 1.5rem 1.5rem">
                <div id="programsContainer">
                    @foreach(old('programs', $tentang->program_list) as $index => $program)
                    <div class="program-item" style="display:flex;gap:0.5rem;margin-bottom:0.75rem">
                        <input type="text" name="programs[]" class="form-control" value="{{ $program }}" 
                               placeholder="Nama program" required style="flex:1">
                        <button type="button" onclick="this.parentElement.remove()" 
                                style="padding:0 1rem;background:#ef4444;color:#fff;border:none;border-radius:8px;cursor:pointer"
                                title="Hapus program">🗑️</button>
                    </div>
                    @endforeach
                </div>
                
                <button type="button" onclick="addProgram()" 
                        style="padding:0.5rem 1rem;background:var(--primary);color:#fff;border:none;border-radius:8px;cursor:pointer;margin-top:0.5rem">
                    + Tambah Program
                </button>
            </div>
            
            <div style="padding:1.5rem;border-bottom:1px solid var(--border);margin-bottom:1.5rem;background:#f0f9ff">
                <h3 style="font-size:1.1rem;font-weight:700;color:var(--primary);margin-bottom:0.5rem">📍 Lokasi Google Maps</h3>
                <p style="color:var(--text-muted);font-size:0.9rem;margin:0">Embed peta lokasi kantor PKK</p>
            </div>
            
            <div style="padding:0 1.5rem 1.5rem">
                <div class="form-group">
                    <label>Embed Code Google Maps *</label>
                    <textarea name="maps_embed_code" class="form-control" rows="4" required 
                              placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;"></iframe>'>{{ old('maps_embed_code', $tentang->maps_embed_code) }}</textarea>
                    <small style="color:var(--text-muted);display:block;margin-top:0.5rem">
                        💡 Cara mendapatkan: Buka Google Maps → Cari lokasi → Share → Embed a map → Copy HTML
                    </small>
                </div>
                
                <div class="form-group">
                    <label>Link Google Maps (Opsional)</label>
                    <input type="url" name="maps_link" class="form-control" value="{{ old('maps_link', $tentang->maps_link) }}" 
                           placeholder="https://goo.gl/maps/xxx">
                </div>
                
                {{-- Preview Maps --}}
                <div style="margin-top:1rem;padding:1rem;background:#f8fafc;border-radius:8px">
                    <label style="font-weight:600;margin-bottom:0.5rem;display:block">Preview Peta:</label>
                    <div style="border-radius:8px;overflow:hidden">
                        {!! $tentang->maps_embed_code !!}
                    </div>
                </div>
            </div>
            
            <div style="padding:1.5rem;border-top:1px solid var(--border);display:flex;gap:1rem;justify-content:flex-end">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function addProgram() {
    const container = document.getElementById('programsContainer');
    const div = document.createElement('div');
    div.className = 'program-item';
    div.style.cssText = 'display:flex;gap:0.5rem;margin-bottom:0.75rem';
    div.innerHTML = `
        <input type="text" name="programs[]" class="form-control" placeholder="Nama program" required style="flex:1">
        <button type="button" onclick="this.parentElement.remove()" 
                style="padding:0 1rem;background:#ef4444;color:#fff;border:none;border-radius:8px;cursor:pointer"
                title="Hapus program">🗑️</button>
    `;
    container.appendChild(div);
}
</script>
@endsection