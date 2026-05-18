@extends('admin.layouts.app')
@section('title', 'Tambah Aplikasi')
@section('page-title', 'Tambah Aplikasi Baru')

@section('content')
<div class="card">
    <form action="{{ route('admin.aplikasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-grid">
            <div class="form-group full">
                <label>Nama Aplikasi Lengkap *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Contoh: SIEDA (Sistem Informasi E-Dasawisma)">
                <small style="color:var(--text-muted)">Nama lengkap aplikasi yang akan ditampilkan</small>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Nama Singkat *</label>
                <input type="text" name="short_name" class="form-control" value="{{ old('short_name') }}" required placeholder="Contoh: SIEDA" style="text-transform:uppercase">
                <small style="color:var(--text-muted)">Singkatan unik untuk icon dan display</small>
            </div>
            <div class="form-group">
                <label>Kategori *</label>
                <select name="category" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="layanan" {{ old('category') == 'layanan' ? 'selected' : '' }}>Layanan</option>
                    <option value="aplikasi" {{ old('category') == 'aplikasi' ? 'selected' : '' }}>Aplikasi</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi Lengkap *</label>
            <textarea name="description" class="form-control" rows="3" required placeholder="Deskripsi detail tentang aplikasi, fitur, dan fungsionalitas">{{ old('description') }}</textarea>
            <small style="color:var(--text-muted)">Deskripsi akan ditampilkan di landing page</small>
        </div>

        <div class="form-group">
            <label>Poin-Poin Fitur Aplikasi</label>
            <small style="color:var(--text-muted);display:block;margin-bottom:1rem">
                Tambahkan 2-5 poin keunggulan/fitur aplikasi (minimal 2, maksimal 5)
            </small>
            
            <div id="features-container">
                {{-- Fitur 1 --}}
                <div class="feature-item" style="display:flex;gap:8px;margin-bottom:8px">
                    <input type="text" name="features[]" class="form-control" 
                        value="{{ old('features.0', 'Terintegrasi dengan data PKK') }}" 
                        placeholder="Contoh: Terintegrasi dengan data PKK" required>
                    <button type="button" class="btn btn-outline" onclick="removeFeature(this)" 
                            style="padding:0.5rem;min-width:36px" title="Hapus poin" disabled>🗑️</button>
                </div>
                {{-- Fitur 2 --}}
                <div class="feature-item" style="display:flex;gap:8px;margin-bottom:8px">
                    <input type="text" name="features[]" class="form-control" 
                        value="{{ old('features.1', 'Akses real-time 24/7') }}" 
                        placeholder="Contoh: Akses real-time 24/7" required>
                    <button type="button" class="btn btn-outline" onclick="removeFeature(this)" 
                            style="padding:0.5rem;min-width:36px" title="Hapus poin" disabled>🗑️</button>
                </div>
                {{-- Fitur 3 --}}
                <div class="feature-item" style="display:flex;gap:8px;margin-bottom:8px">
                    <input type="text" name="features[]" class="form-control" 
                        value="{{ old('features.2', 'Keamanan data terjamin') }}" 
                        placeholder="Contoh: Keamanan data terjamin" required>
                    <button type="button" class="btn btn-outline" onclick="removeFeature(this)" 
                            style="padding:0.5rem;min-width:36px" title="Hapus poin">🗑️</button>
                </div>
            </div>
            
            <button type="button" id="add-feature-btn" class="btn btn-outline" onclick="addFeature()" 
                    style="margin-top:0.5rem;width:100%">
                ➕ Tambah Poin Fitur
            </button>
            
            <small id="features-warning" style="color:#e53e3e;display:none;margin-top:0.5rem">
                ⚠️ Maksimal 5 poin fitur
            </small>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Status Aplikasi *</label>
                <select name="status" class="form-control" required onchange="toggleUrlField(this.value)">
                    <option value="">-- Pilih Status --</option>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>✅ Aktif - Siap Digunakan</option>
                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>🔧 Dalam Maintenance - Sedang Perbaikan</option>
                    <option value="development" {{ old('status') == 'development' ? 'selected' : '' }}>🚧 Dalam Pengembangan - Coming Soon</option>
                </select>
                <small style="color:var(--text-muted);margin-top:4px;display:block">
                    Status menentukan tampilan di landing page
                </small>
            </div>
            <div class="form-group" id="urlField">
                <label>URL Aplikasi</label>
                <input type="url" name="url" class="form-control" value="{{ old('url') }}" placeholder="https://example.com">
                <small style="color:var(--text-muted)">Link untuk mengakses aplikasi (kosongkan jika dalam pengembangan)</small>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Icon/Logo Aplikasi</label>
                <input type="file" name="icon" class="form-control" accept="image/*" id="iconInput">
                
                {{-- Preview hanya muncul jika user memilih file --}}
                <div id="iconPreview" style="margin-top:10px; display:none">
                    <img id="previewImg" src="" style="width:100px;height:100px;border-radius:12px;object-fit:cover;box-shadow:0 4px 8px rgba(0,0,0,0.1);border:2px solid var(--border)">
                    <span style="display:block;font-size:0.8rem;color:var(--text-muted);margin-top:4px">Preview Icon</span>
                </div>
                
                <small style="color:var(--text-muted);display:block;margin-top:4px">
                    Format: JPG/PNG/WebP, maks 2MB. Ukuran ideal: 200x200px
                </small>
            </div>

            <script>
            // Preview icon saat dipilih
            document.getElementById('iconInput')?.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('previewImg').src = e.target.result;
                        document.getElementById('iconPreview').style.display = 'block';
                        document.getElementById('placeholderPreview').style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Update placeholder saat short_name berubah
            document.querySelector('input[name="short_name"]')?.addEventListener('input', function(e) {
                const initial = e.target.value.charAt(0).toUpperCase() || 'A';
                const placeholderDiv = document.querySelector('#placeholderPreview div');
                if (placeholderDiv) {
                    placeholderDiv.textContent = initial;
                }
            });
            </script>
            <div class="form-group">
                <label>Urutan Tampil</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                <small style="color:var(--text-muted)">Semakin kecil angka, semakin awal tampil</small>
            </div>
        </div>

        <div class="form-group">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width:18px;height:18px;cursor:pointer">
                <span style="font-weight:600">Tampilkan di Website</span>
            </label>
            <small style="color:var(--text-muted);display:block;margin-top:4px;margin-left:26px">
                Jika dicentang, aplikasi akan tampil di landing page. Jika tidak, aplikasi disembunyikan sementara.
            </small>
        </div>

        <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem">
            <a href="{{ route('admin.aplikasi.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">💾 Simpan Aplikasi</button>
        </div>
    </form>
</div>

<script>
function addFeature() {
    const container = document.getElementById('features-container');
    const currentCount = container.querySelectorAll('.feature-item').length;
    
    if (currentCount >= 5) {
        document.getElementById('features-warning').style.display = 'block';
        return;
    }
    
    document.getElementById('features-warning').style.display = 'none';
    
    const div = document.createElement('div');
    div.className = 'feature-item';
    div.style.cssText = 'display:flex;gap:8px;margin-bottom:8px';
    div.innerHTML = `
        <input type="text" name="features[]" class="form-control" 
               placeholder="Masukkan poin fitur" required>
        <button type="button" class="btn btn-outline" onclick="removeFeature(this)" 
                style="padding:0.5rem;min-width:36px" title="Hapus poin">🗑️</button>
    `;
    container.appendChild(div);
    
    updateDeleteButtons();
}

function removeFeature(btn) {
    const container = document.getElementById('features-container');
    const currentCount = container.querySelectorAll('.feature-item').length;
    
    if (currentCount <= 2) {
        alert('Minimal harus ada 2 poin fitur');
        return;
    }
    
    btn.parentElement.remove();
    updateDeleteButtons();
}

function updateDeleteButtons() {
    const container = document.getElementById('features-container');
    const items = container.querySelectorAll('.feature-item');
    const addBtn = document.getElementById('add-feature-btn');
    
    // Enable/disable delete buttons
    items.forEach((item, index) => {
        const deleteBtn = item.querySelector('button[onclick="removeFeature(this)"]');
        if (items.length <= 2) {
            deleteBtn.disabled = true;
            deleteBtn.style.opacity = '0.3';
        } else {
            deleteBtn.disabled = false;
            deleteBtn.style.opacity = '1';
        }
    });
    
    // Show/hide add button
    if (items.length >= 5) {
        addBtn.style.display = 'none';
    } else {
        addBtn.style.display = 'block';
    }
}

function toggleUrlField(status) {
    const urlField = document.getElementById('urlField');
    const urlInput = urlField.querySelector('input');
    
    if (status === 'development') {
        urlField.style.opacity = '0.5';
        urlInput.disabled = true;
        urlInput.value = '#'; // ✅ Set value ke #
        urlInput.removeAttribute('required'); // ✅ Hapus required
    } else {
        urlField.style.opacity = '1';
        urlInput.disabled = false;
        if (urlInput.value === '#') urlInput.value = ''; // ✅ Clear jika bukan development
    }
}

// Init on load
document.addEventListener('DOMContentLoaded', () => {
    const statusSelect = document.querySelector('select[name="status"]');
    if (statusSelect) toggleUrlField(statusSelect.value);
});
</script>
@endsection