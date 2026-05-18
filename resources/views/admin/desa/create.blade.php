@extends('admin.layouts.app')
@section('title', 'Tambah Desa')
@section('page-title', 'Tambah Desa Baru')

@section('content')
<div class="card">
    <form action="{{ route('admin.desa.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label>Kecamatan *</label>
            <select name="kecamatan_id" id="kecamatanSelect" class="form-control" required>
                <option value="">-- Memuat data kecamatan... --</option>
            </select>
            <small style="color:var(--text-muted)">Data kecamatan dari database</small>
        </div>

        <div class="form-group">
            <label>Desa / Kelurahan *</label>
            <select name="desa_code" id="desaSelect" class="form-control" required disabled>
                <option value="">-- Pilih Kecamatan Terlebih Dahulu --</option>
            </select>
            <input type="hidden" name="desa_name" id="desaNameInput">
            <small id="desaHelp" style="color:var(--text-muted)">Data desa otomatis dari API wilayah.id</small>
            <small id="desaError" style="color:#ef4444;display:none;margin-top:0.25rem"></small>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Jumlah Penduduk</label>
                <input type="number" name="population" class="form-control" value="{{ old('population', 0) }}" min="0">
            </div>
            <div class="form-group">
                <label>Jumlah KK (Kepala Keluarga)</label>
                <input type="number" name="households" class="form-control" value="{{ old('households', 0) }}" min="0">
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Foto Desa</label>
                <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                <small style="color:var(--text-muted)">JPG/PNG, maks 2MB</small>
                <div id="imagePreview" style="margin-top:10px;display:none">
                    <img id="previewImg" src="" style="width:120px;height:90px;border-radius:8px;object-fit:cover;border:2px solid var(--border)">
                </div>
            </div>
            <div class="form-group">
                <label>Urutan Tampil</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
            </div>
        </div>

        <div class="form-group">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width:18px;height:18px">
                <span style="margin-left:8px">Tampilkan di Website</span>
            </label>
        </div>

        <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem">
            <a href="{{ route('admin.desa.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">💾 Simpan Desa</button>
        </div>
    </form>
</div>

<script>
const kecamatanSelect = document.getElementById('kecamatanSelect');
const desaSelect = document.getElementById('desaSelect');
const desaNameInput = document.getElementById('desaNameInput');
const desaError = document.getElementById('desaError');
const desaHelp = document.getElementById('desaHelp');

// 1. Load Kecamatan from our API
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const res = await fetch('/api/v1/kecamatans');
        const json = await res.json();
        
        if (!json.success) throw new Error(json.message || 'Gagal memuat kecamatan');
        if (!json.data || json.data.length === 0) throw new Error('Data kecamatan kosong');
        
        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        
        json.data.forEach(k => {
            const opt = document.createElement('option');
            opt.value = k.id;              // ID dari database (1, 2, 3...)
            opt.dataset.wilayahCode = k.code; // Kode wilayah dari API (12.12.01...)
            opt.textContent = k.name;
            kecamatanSelect.appendChild(opt);
        });
        
    } catch (error) {
        console.error('Error loading kecamatan:', error);
        kecamatanSelect.innerHTML = '<option value="">❌ Gagal memuat kecamatan</option>';
    }
});

// 2. Load Desa when Kecamatan changes (via PROXY Laravel)
kecamatanSelect.addEventListener('change', async function() {
    const selectedOption = this.options[this.selectedIndex];
    const wilayahCode = selectedOption.dataset.wilayahCode;
    const kecName = selectedOption.textContent;
    
    // Reset
    desaSelect.innerHTML = '<option value="">⏳ Memuat data desa...</option>';
    desaSelect.disabled = true;
    desaNameInput.value = '';
    desaError.style.display = 'none';
    desaError.textContent = '';
    desaHelp.style.display = 'block';
    
    if (!wilayahCode) {
        desaSelect.innerHTML = '<option value="">-- Pilih Kecamatan Terlebih Dahulu --</option>';
        return;
    }
    
    try {
        // ✅ GUNAKAN PROXY LARAWAL (bukan fetch langsung ke wilayah.id)
        const proxyUrl = `/api/v1/wilayah/proxy/desa/${wilayahCode}`;
        
        const res = await fetch(proxyUrl);
        const json = await res.json();
        
        if (!json.success) {
            throw new Error(json.message || 'Gagal mengambil data desa');
        }
        
        if (!Array.isArray(json.data)) {
            throw new Error('Format data tidak valid');
        }
        
        if (json.data.length === 0) {
            desaSelect.innerHTML = '<option value="">Tidak ada desa di kecamatan ini</option>';
            desaHelp.style.display = 'none';
            return;
        }
        
        // Populate dropdown
        desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';
        desaSelect.disabled = false;
        
        json.data.forEach(d => {
            const opt = document.createElement('option');
            opt.value = d.code;
            opt.textContent = d.name;
            desaSelect.appendChild(opt);
        });
        
        desaHelp.textContent = `✅ ${json.data.length} desa ditemukan di ${kecName}`;
        
    } catch (error) {
        console.error('Error loading desa:', error);
        desaSelect.innerHTML = '<option value="">❌ Gagal memuat desa</option>';
        desaSelect.disabled = true;
        
        desaError.style.display = 'block';
        desaError.textContent = 'Gagal: ' + error.message;
        desaHelp.style.display = 'none';
    }
});

// 3. Update hidden input when desa is selected
desaSelect.addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    desaNameInput.value = this.value ? selected.textContent : '';
});

// 4. Image preview
document.getElementById('imageInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection