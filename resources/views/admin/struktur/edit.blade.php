@extends('admin.layouts.app')
@section('title', 'Edit Anggota Struktur')
@section('page-title', 'Edit Anggota Struktur')

@section('content')
<div class="card">
    <form action="{{ route('admin.struktur.update', $struktur) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        @php
            // Tentukan group berdasarkan pokja_id
            $currentGroup = is_null($struktur->pokja_id) ? 'pengurus' : 'pokja' . $struktur->pokja_id;
            
            // Normalisasi position untuk matching dengan dropdown
            $currentPosition = $struktur->position;
            if ($struktur->pokja_id && $currentPosition === 'Sekretaris') {
                $currentPosition = 'Sekretaris Pokja'; // Agar match dengan dropdown
            }
        @endphp

        <div class="form-grid">
            <div class="form-group">
                <label>1. Pilih Kelompok *</label>
                <select name="group" id="groupSelect" class="form-control" required onchange="updatePositions()">
                    <option value="">-- Pilih Kelompok --</option>
                    <option value="pengurus" {{ $currentGroup == 'pengurus' ? 'selected' : '' }}>Pengurus Inti (Atas)</option>
                    <option value="pokja1" {{ $currentGroup == 'pokja1' ? 'selected' : '' }}>Pokja I</option>
                    <option value="pokja2" {{ $currentGroup == 'pokja2' ? 'selected' : '' }}>Pokja II</option>
                    <option value="pokja3" {{ $currentGroup == 'pokja3' ? 'selected' : '' }}>Pokja III</option>
                    <option value="pokja4" {{ $currentGroup == 'pokja4' ? 'selected' : '' }}>Pokja IV</option>
                </select>
                <small style="color:var(--text-muted);margin-top:4px;display:block">Menentukan di bagian mana data akan muncul</small>
            </div>
            
            <div class="form-group">
                <label>2. Pilih Jabatan *</label>
                <select name="position" id="positionSelect" class="form-control" required>
                    <option value="">-- Pilih Kelompok Dulu --</option>
                    {{-- Options will be populated by JS --}}
                </select>
                <small style="color:var(--text-muted);margin-top:4px;display:block">Jabatan akan otomatis menyesuaikan kelompok</small>
            </div>
        </div>

        <div class="form-group full">
            <label>3. Nama Lengkap *</label>
            <input type="text" name="name" class="form-control" 
                   value="{{ old('name', $struktur->name) }}" 
                   required 
                   placeholder="Contoh: INDAH KARUNIA PRATIWI SITUMEANG, SH">
        </div>

        <div class="form-group full">
            <label>Deskripsi / Catatan (Opsional)</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Misal: NIP, Riwayat singkat, atau catatan internal">{{ old('description', $struktur->description) }}</textarea>
        </div>

        <div class="form-group">
            <label>Foto Pengurus</label>
            <input type="file" name="photo" class="form-control" accept="image/*">
            <small style="color:var(--text-muted)">JPG/PNG, maks 2MB. Latar transparan disarankan.</small>
            
            {{-- Preview Foto Saat Ini --}}
            @if($struktur->photo_path)
            <div style="margin-top:10px">
                <img src="{{ asset('storage/' . $struktur->photo_path) }}" 
                     alt="{{ $struktur->name }}" 
                     style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid var(--border)">
                <span style="display:block;font-size:0.8rem;color:var(--text-muted);margin-top:4px">
                    Foto saat ini <small>(upload baru untuk mengganti)</small>
                </span>
            </div>
            @endif
        </div>

        <div class="modal-footer" style="padding:0;margin-top:1.5rem">
            <a href="{{ route('admin.struktur.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Update Data</button>
        </div>
    </form>
    
    {{-- Menampilkan Error Validasi --}}
    @if($errors->any())
    <div style="margin-top:1rem;padding:1rem;background:#fff5f5;border-left:4px solid var(--danger);border-radius:8px;color:#c53030">
        <strong style="display:block;margin-bottom:0.5rem">Ada kesalahan input:</strong>
        <ul style="padding-left:1.25rem">
            @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
        </ul>
    </div>
    @endif
</div>

<script>
const positions = {
    pengurus: ['Ketua Pembina', 'Ketua TP PKK', 'Staf Ahli', 'Sekretaris', 'Bendahara', 'Ketua I', 'Ketua II', 'Ketua III', 'Ketua IV'],
    pokja1: ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Anggota'],
    pokja2: ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Anggota'],
    pokja3: ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Anggota'],
    pokja4: ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Anggota']
};

function updatePositions() {
    const group = document.getElementById('groupSelect').value;
    const posSelect = document.getElementById('positionSelect');
    posSelect.innerHTML = '<option value="">-- Pilih Jabatan --</option>';
    
    if (group && positions[group]) {
        positions[group].forEach(pos => {
            const opt = document.createElement('option');
            opt.value = pos;
            opt.textContent = pos;
            posSelect.appendChild(opt);
        });
    }
}

// Initialize on load with existing data
document.addEventListener('DOMContentLoaded', () => {
    const groupSelect = document.getElementById('groupSelect');
    const posSelect = document.getElementById('positionSelect');
    const currentGroup = @json($currentGroup);
    const currentPosition = @json($currentPosition);
    
    // 1. Set group & populate positions
    if (currentGroup) {
        groupSelect.value = currentGroup;
        updatePositions();
    }
    
    // 2. Set selected position after options are populated
    if (currentPosition) {
        // Small delay to ensure options are rendered
        setTimeout(() => {
            if (posSelect.querySelector(`option[value="${currentPosition}"]`)) {
                posSelect.value = currentPosition;
            }
        }, 50);
    }
});
</script>
@endsection