@extends('admin.layouts.app')
@section('title', 'Tambah Anggota Struktur')
@section('page-title', 'Tambah Anggota Struktur')

@section('content')
<div class="card">
    <form action="{{ route('admin.struktur.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-grid">
            <div class="form-group">
                <label>1. Pilih Kelompok *</label>
                <select name="group" id="groupSelect" class="form-control" required onchange="updatePositions()">
                    <option value="">-- Pilih Kelompok --</option>
                    <option value="pengurus">Pengurus Inti (Atas)</option>
                    <option value="pokja1">Pokja I</option>
                    <option value="pokja2">Pokja II</option>
                    <option value="pokja3">Pokja III</option>
                    <option value="pokja4">Pokja IV</option>
                </select>
                <small style="color:var(--text-muted);margin-top:4px;display:block">Menentukan di bagian mana data akan muncul</small>
            </div>
            
            <div class="form-group">
                <label>2. Pilih Jabatan *</label>
                <select name="position" id="positionSelect" class="form-control" required>
                    <option value="">-- Pilih Kelompok Dulu --</option>
                </select>
                <small style="color:var(--text-muted);margin-top:4px;display:block">Jabatan akan otomatis menyesuaikan kelompok</small>
            </div>
        </div>

        <div class="form-group full">
            <label>3. Nama Lengkap *</label>
            <input type="text" name="name" class="form-control" required placeholder="Contoh: INDAH KARUNIA PRATIWI SITUMEANG, SH">
        </div>

        <div class="form-group full">
            <label>Deskripsi / Catatan (Opsional)</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Misal: NIP, Riwayat singkat, atau catatan internal"></textarea>
        </div>

        <div class="form-group">
            <label>Foto Pengurus</label>
            <input type="file" name="photo" class="form-control" accept="image/*">
            <small style="color:var(--text-muted)">JPG/PNG, maks 2MB. Latar transparan disarankan.</small>
        </div>

        <div class="modal-footer" style="padding:0;margin-top:1.5rem">
            <a href="{{ route('admin.struktur.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Data</button>
        </div>
    </form>
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

// If editing or old input exists
document.addEventListener('DOMContentLoaded', () => {
    const groupVal = document.getElementById('groupSelect')?.value;
    if (groupVal) updatePositions();
});
</script>
@endsection