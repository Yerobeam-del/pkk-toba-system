@extends('admin.layouts.app')
@section('title', 'Edit Desa')
@section('page-title', 'Edit Desa')

@section('content')
<div class="card">
    <form action="{{ route('admin.desa.update', $desa) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        
        <div class="form-group">
            <label>Kecamatan *</label>
            <select name="kecamatan_id" id="kecamatanSelect" class="form-control" required>
                <option value="">-- Memuat data kecamatan... --</option>
            </select>
        </div>

        <div class="form-group">
            <label>Desa / Kelurahan *</label>
            <select name="desa_code" id="desaSelect" class="form-control" required disabled>
                <option value="">-- Pilih Kecamatan Terlebih Dahulu --</option>
            </select>
            <input type="hidden" name="desa_name" id="desaNameInput" value="{{ old('desa_name', $desa->name) }}">
            <small id="desaHelp" style="color:var(--text-muted)">Data desa otomatis dari API wilayah.id</small>
            <small id="desaError" style="color:#ef4444;display:none;margin-top:0.25rem"></small>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Jumlah Penduduk</label>
                <input type="number" name="population" class="form-control" value="{{ old('population', $desa->population) }}" min="0">
            </div>
            <div class="form-group">
                <label>Jumlah KK (Kepala Keluarga)</label>
                <input type="number" name="households" class="form-control" value="{{ old('households', $desa->households) }}" min="0">
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Foto Desa</label>
                <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                @if($desa->image)
                <div style="margin-top:10px">
                    <img src="{{ asset('storage/'.$desa->image) }}" style="width:120px;height:90px;border-radius:8px;object-fit:cover;border:2px solid var(--border)">
                    <span style="display:block;font-size:0.8rem;color:var(--text-muted);margin-top:4px">Foto saat ini</span>
                </div>
                @endif
                <div id="imagePreview" style="margin-top:10px;display:none">
                    <img id="previewImg" src="" style="width:120px;height:90px;border-radius:8px;object-fit:cover;border:2px solid var(--border)">
                </div>
            </div>
            <div class="form-group">
                <label>Urutan Tampil</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $desa->sort_order) }}" min="0">
            </div>
        </div>

        <div class="form-group">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $desa->is_active) ? 'checked' : '' }} style="width:18px;height:18px">
                <span style="margin-left:8px">Tampilkan di Website</span>
            </label>
        </div>

        <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem">
            <a href="{{ route('admin.desa.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">💾 Update Desa</button>
        </div>
    </form>
</div>

@if($errors->any())
<div style="margin-top:1rem;padding:1rem;background:#fff5f5;border-left:4px solid var(--danger);border-radius:8px;color:#c53030">
    <strong style="display:block;margin-bottom:0.5rem">Ada kesalahan input:</strong>
    <ul style="padding-left:1.25rem;margin:0">
        @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
    </ul>
</div>
@endif

<script>
const currentKecId = @json(old('kecamatan_id', $desa->kecamatan_id));
const currentDesaCode = @json(old('desa_code', $desa->kode_wilayah));
const kecamatanSelect = document.getElementById('kecamatanSelect');
const desaSelect = document.getElementById('desaSelect');
const desaNameInput = document.getElementById('desaNameInput');
const desaError = document.getElementById('desaError');
const desaHelp = document.getElementById('desaHelp');

// Function to load desa from PROXY endpoint
async function loadDesaByKecamatan(kecCode, preselectCode = null) {
    desaSelect.innerHTML = '<option value="">⏳ Memuat...</option>';
    desaSelect.disabled = true;
    desaError.style.display = 'none';
    
    if (!kecCode) {
        desaSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        return;
    }

    try {
        // ✅ GUNAKAN PROXY LARAWAL (bukan fetch langsung ke wilayah.id)
        const proxyUrl = `/api/v1/wilayah/proxy/desa/${kecCode}`;
        
        const res = await fetch(proxyUrl);
        const json = await res.json();
        
        if (!json.success) {
            throw new Error(json.message || 'Gagal memuat data desa');
        }
        
        if (!Array.isArray(json.data)) {
            throw new Error('Format data tidak valid');
        }
        
        desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';
        desaSelect.disabled = false;
        
        if (json.data.length === 0) {
            desaSelect.innerHTML = '<option value="">Tidak ada desa di kecamatan ini</option>';
            desaHelp.style.display = 'none';
            return;
        }
        
        json.data.forEach(d => {
            const opt = document.createElement('option');
            opt.value = d.code;
            opt.textContent = d.name;
            if (preselectCode && d.code === preselectCode) {
                opt.selected = true;
            }
            desaSelect.appendChild(opt);
        });
        
        // Update hidden name if preselected
        if (preselectCode) {
            const selectedOpt = desaSelect.querySelector(`option[value="${preselectCode}"]`);
            if (selectedOpt) {
                desaNameInput.value = selectedOpt.text;
                desaHelp.textContent = `✅ ${json.data.length} desa ditemukan`;
            }
        } else {
            desaHelp.textContent = `✅ ${json.data.length} desa tersedia`;
        }
        
    } catch (e) {
        console.error('Error loading desa:', e);
        desaSelect.innerHTML = '<option value="">❌ Gagal memuat desa</option>';
        desaSelect.disabled = true;
        desaError.style.display = 'block';
        desaError.textContent = 'Gagal: ' + e.message;
        desaHelp.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', async () => {
    try {
        // 1. Load all kecamatan
        const kecRes = await fetch('/api/v1/kecamatans');
        const kecJson = await kecRes.json();
        
        if (!kecJson.success) throw new Error(kecJson.message);
        
        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        let foundKecCode = null;
        
        kecJson.data.forEach(k => {
            const opt = document.createElement('option');
            opt.value = k.id;
            opt.dataset.wilayahCode = k.code; // Simpan kode wilayah
            opt.textContent = k.name;
            
            if (k.id == currentKecId) {
                opt.selected = true;
                foundKecCode = k.code;
            }
            
            kecamatanSelect.appendChild(opt);
        });
        
        // 2. Jika kecamatan sudah terpilih, load desanya
        if (foundKecCode) {
            await loadDesaByKecamatan(foundKecCode, currentDesaCode);
        }
        
    } catch (error) {
        console.error('Error initializing form:', error);
        kecamatanSelect.innerHTML = '<option value="">❌ Gagal memuat kecamatan</option>';
    }
});

// 3. Event listener when kecamatan changes
kecamatanSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const wilayahCode = selectedOption.dataset.wilayahCode;
    loadDesaByKecamatan(wilayahCode);
});

// 4. Update hidden name when desa is selected
desaSelect.addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    desaNameInput.value = this.value ? selected.textContent : '';
});

// 5. Image preview
document.getElementById('imageInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Remove old preview if exists
            const oldPreview = document.getElementById('imagePreview');
            if (oldPreview) oldPreview.remove();
            
            // Create new preview
            const preview = document.createElement('div');
            preview.id = 'imagePreview';
            preview.innerHTML = `
                <img src="${e.target.result}" style="width:120px;height:90px;border-radius:8px;object-fit:cover;border:2px solid var(--border)">
                <span style="display:block;font-size:0.8rem;color:var(--text-muted);margin-top:4px">Preview</span>
            `;
            e.target.parentElement.appendChild(preview);
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection