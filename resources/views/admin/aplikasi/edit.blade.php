@extends('admin.layouts.app')
@section('title', 'Edit Aplikasi')
@section('page-title', 'Edit Aplikasi')

@section('content')
<div class="card">
    <form action="{{ route('admin.aplikasi.update', $aplikasi) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <div class="form-group full">
                <label>Nama Aplikasi Lengkap *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $aplikasi->name) }}" required>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Nama Singkat *</label>
                <input type="text" name="short_name" class="form-control" value="{{ old('short_name', $aplikasi->short_name) }}" required>
            </div>
            <div class="form-group">
                <label>Kategori *</label>
                <select name="category" class="form-control" required>
                    <option value="layanan" {{ old('category', $aplikasi->category) == 'layanan' ? 'selected' : '' }}>Layanan</option>
                    <option value="aplikasi" {{ old('category', $aplikasi->category) == 'aplikasi' ? 'selected' : '' }}>Aplikasi</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi Lengkap *</label>
            <textarea name="description" class="form-control" rows="3" required>{{ old('description', $aplikasi->description) }}</textarea>
        </div>

        <div class="form-group">
            <label>Poin-Poin Fitur Aplikasi</label>
            <small style="color:var(--text-muted);display:block;margin-bottom:1rem">
                Tambahkan 2-5 poin keunggulan/fitur aplikasi (minimal 2, maksimal 5)
            </small>
            
            <div id="features-container">
                @php
                    $features = old('features', $aplikasi->features ?? [
                        'Terintegrasi dengan data PKK',
                        'Akses real-time 24/7',
                        'Keamanan data terjamin'
                    ]);
                @endphp
                
                @foreach($features as $index => $feature)
                <div class="feature-item" style="display:flex;gap:8px;margin-bottom:8px">
                    {{-- ✅ PENTING: name="features[]" agar jadi array --}}
                    <input type="text" 
                        name="features[]" 
                        class="form-control" 
                        value="{{ $feature }}" 
                        placeholder="Masukkan poin fitur" 
                        required>
                    <button type="button" 
                            class="btn btn-outline" 
                            onclick="removeFeature(this)" 
                            style="padding:0.5rem;min-width:36px;{{ count($features) <= 2 ? 'opacity:0.3' : '' }}" 
                            title="Hapus poin" 
                            {{ count($features) <= 2 ? 'disabled' : '' }}>🗑️</button>
                </div>
                @endforeach
            </div>
            
            <button type="button" 
                    id="add-feature-btn" 
                    class="btn btn-outline" 
                    onclick="addFeature()" 
                    style="margin-top:0.5rem;width:100%;{{ count($features) >= 5 ? 'display:none' : '' }}">
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
                    <option value="active" {{ old('status', $aplikasi->status) == 'active' ? 'selected' : '' }}>✅ Aktif</option>
                    <option value="maintenance" {{ old('status', $aplikasi->status) == 'maintenance' ? 'selected' : '' }}>🔧 Dalam Maintenance</option>
                    <option value="development" {{ old('status', $aplikasi->status) == 'development' ? 'selected' : '' }}>🚧 Dalam Pengembangan</option>
                </select>
            </div>
            <div class="form-group" id="urlField" style="{{ $aplikasi->status == 'development' ? 'opacity:0.5' : '' }}">
                <label>URL Aplikasi</label>
                <input type="url" name="url" class="form-control" value="{{ old('url', $aplikasi->url) }}" {{ $aplikasi->status == 'development' ? 'disabled' : '' }}>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Icon/Logo Aplikasi</label>
                <input type="file" name="icon" class="form-control" accept="image/*">
                @if($aplikasi->icon)
                <div style="margin-top:10px">
                    <img src="{{ asset('storage/'.$aplikasi->icon) }}" style="width:80px;height:80px;border-radius:12px;object-fit:cover;border:2px solid var(--border);box-shadow:0 2px 8px rgba(0,0,0,0.08)">
                    <span style="display:block;font-size:0.8rem;color:var(--text-muted);margin-top:4px">Icon saat ini (upload baru untuk mengganti)</span>
                </div>
                @endif
            </div>
            <div class="form-group">
                <label>Urutan Tampil</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $aplikasi->sort_order) }}" min="0">
            </div>
        </div>

        <div class="form-group">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $aplikasi->is_active) ? 'checked' : '' }} style="width:18px;height:18px;cursor:pointer">
                <span style="font-weight:600">Tampilkan di Website</span>
            </label>
        </div>

        <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem">
            <a href="{{ route('admin.aplikasi.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">💾 Update Aplikasi</button>
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
        urlInput.value = '#';
        urlInput.removeAttribute('required');
    } else {
        urlField.style.opacity = '1';
        urlInput.disabled = false;
        if (urlInput.value === '#') urlInput.value = '';
    }
}

// Init on load
document.addEventListener('DOMContentLoaded', () => {
    const statusSelect = document.querySelector('select[name="status"]');
    if (statusSelect) toggleUrlField(statusSelect.value);
});
</script>
@endsection