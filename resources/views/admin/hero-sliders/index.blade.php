@extends('admin.layouts.app')
@section('title', 'Manajemen Hero Slider')
@section('page-title', 'Kelola Slider Beranda')

@section('content')
<div style="margin-bottom:2rem">
    @if(session('success'))
    <div style="background:#f0fdf4;border-left:4px solid #22c55e;padding:1rem;margin-bottom:1.5rem;border-radius:8px;color:#166534;display:flex;align-items:center;gap:0.75rem">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Settings Card --}}
    <div class="card" style="margin-bottom:2rem">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:0.75rem">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="flex-shrink:0">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            </svg>
            <h3 style="font-size:1.1rem;font-weight:700;color:var(--primary);margin:0">Pengaturan Slider</h3>
        </div>
        <form action="{{ route('admin.hero-sliders.settings') }}" method="POST" style="padding:1.5rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem">
            @csrf
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.75rem;font-size:0.9rem">Auto Play</label>
                <div style="display:flex;align-items:flex-start;gap:0.75rem">
                    <input type="checkbox" name="auto_play" value="1" id="autoPlay" {{ $settings['auto_play'] ? 'checked' : '' }} style="width:18px;height:18px;cursor:pointer;margin-top:2px;flex-shrink:0">
                    <label for="autoPlay" style="color:var(--text-muted);font-size:0.9rem;cursor:pointer;line-height:1.5;margin:0">Aktifkan rotasi otomatis</label>
                </div>
            </div>
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Durasi Transisi (ms)</label>
                <input type="number" name="transition_duration" value="{{ $settings['transition_duration'] ?? 500 }}" min="300" max="2000" class="form-control" style="max-width:150px">
            </div>
            <div style="grid-column:1/-1;display:flex;justify-content:flex-end">
                <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.5rem">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

    {{-- Add New Slider --}}
    <div class="card" style="margin-bottom:2rem">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:0.75rem">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2" style="flex-shrink:0">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            <h3 style="font-size:1.1rem;font-weight:700;color:#8b5cf6;margin:0">Tambah Slide Baru</h3>
        </div>
        <div style="padding:0 1.5rem 1.5rem">
            <p style="color:var(--text-muted);margin:1rem 0 0 0;font-size:0.9rem;line-height:1.6">Upload gambar background untuk slider beranda. Teks konten tetap menggunakan desain yang sudah ada.</p>
        </div>
        <form action="{{ route('admin.hero-sliders.store') }}" method="POST" enctype="multipart/form-data" style="display:grid; gap:1.5rem; padding: 0 1.5rem 1.5rem 1.5rem;">
            @csrf

            {{-- Upload Gambar (Full Width) --}}
            <div style="grid-column: 1 / -1;">
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Gambar Background <span style="color:var(--danger)">*</span></label>
                <input type="file" name="image" class="form-control" accept="image/*" required style="width:100%">
                
                <div style="display:flex;gap:1rem;margin-top:0.5rem;font-size:0.8rem;color:var(--text-muted);flex-wrap:wrap">
                    <span style="display:flex;align-items:center;gap:0.25rem">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        Format: JPG, PNG, WebP
                    </span>
                    <span style="display:flex;align-items:center;gap:0.25rem">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        Maksimal: 5MB
                    </span>
                    <span style="display:flex;align-items:center;gap:0.25rem">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                        Rekomendasi: 1920x1080px (16:9)
                    </span>
                </div>
            </div>

            {{-- Baris 2: Durasi, Urutan, & Aktif (Grid 3 Kolom Presisi) --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1.5rem; align-items: end;">
                
                {{-- 1. Durasi --}}
                <div>
                    <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Durasi Tampil (detik)</label>
                    <input type="number" name="display_duration" class="form-control" value="5" min="3" max="30" placeholder="5">
                    <small style="color:var(--text-muted);display:block;margin-top:0.25rem;font-size:0.8rem">Berapa lama gambar ditampilkan</small>
                </div>

                {{-- 2. Urutan --}}
                <div>
                    <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Urutan</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ $sliders->count() + 1 }}" min="0" placeholder="1">
                    <small style="color:var(--text-muted);display:block;margin-top:0.25rem;font-size:0.8rem">Urutan tampilan slide</small>
                </div>

                {{-- 3. Checkbox Aktif (Diposisikan di Bawah) --}}
                <div style="display:flex; align-items:center; gap: 0.5rem; height: 42px; padding-bottom: 4px;"> 
                    <!-- height & padding disesuaikan agar sejajar dengan base input -->
                    <input type="checkbox" name="is_active" value="1" id="isActive" checked style="width:18px;height:18px;cursor:pointer;flex-shrink:0; margin-top: 2px;">
                    <label for="isActive" style="font-weight:600;color:var(--text-dark);font-size:0.9rem;cursor:pointer;margin:0;">Aktif</label>
                </div>
            </div>

            {{-- Baris 3: Tombol Submit --}}
            <div style="display:flex;justify-content:flex-end;grid-column: 1 / -1; margin-top: 0.5rem;">
                <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.5rem">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Tambah Slide
                </button>
            </div>
        </form>
    </div>

    {{-- Sliders List --}}
    <div class="card">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
            <div style="display:flex;align-items:center;gap:0.75rem">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#14b8a6" stroke-width="2" style="flex-shrink:0">
                    <line x1="8" y1="6" x2="21" y2="6"/>
                    <line x1="8" y1="12" x2="21" y2="12"/>
                    <line x1="8" y1="18" x2="21" y2="18"/>
                    <line x1="3" y1="6" x2="3.01" y2="6"/>
                    <line x1="3" y1="12" x2="3.01" y2="12"/>
                    <line x1="3" y1="18" x2="3.01" y2="18"/>
                </svg>
                <h3 style="font-size:1.1rem;font-weight:700;color:#14b8a6;margin:0">Daftar Slide</h3>
            </div>
            <small style="color:var(--text-muted);font-size:0.85rem">Drag & drop untuk mengurutkan</small>
        </div>
        <div id="slidersList" style="padding:1rem">
            @forelse($sliders as $slider)
            <div class="slider-item" data-id="{{ $slider->id }}" style="display:flex;gap:1rem;padding:1rem;border:1px solid var(--border);border-radius:12px;margin-bottom:1rem;background:#fff;cursor:grab;transition:all 0.2s" onmouseover="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'" draggable="true">
                <div style="display:flex;align-items:center;color:var(--text-muted);padding:0 0.5rem">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="8" y1="6" x2="21" y2="6"/>
                        <line x1="8" y1="12" x2="21" y2="12"/>
                        <line x1="8" y1="18" x2="21" y2="18"/>
                    </svg>
                </div>
                <img src="{{ $slider->image_url }}" alt="Slide {{ $slider->id }}" style="width:120px;height:80px;object-fit:cover;border-radius:8px;border:1px solid var(--border)">
                <div style="flex:1;min-width:0">
                    <div style="font-weight:600;color:var(--text-dark);margin-bottom:0.25rem">Slide #{{ $slider->id }}</div>
                    <div style="font-size:0.85rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $slider->image_path }}</div>
                    <div style="display:flex;align-items:center;gap:1rem;margin-top:0.5rem;font-size:0.8rem;color:var(--text-muted);flex-wrap:wrap">
                        <span style="display:flex;align-items:center;gap:0.35rem">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            {{ $slider->display_duration }}s
                        </span>
                        <span style="display:flex;align-items:center;gap:0.35rem">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="10" y1="6" x2="21" y2="6"/>
                                <line x1="10" y1="12" x2="21" y2="12"/>
                                <line x1="10" y1="18" x2="21" y2="18"/>
                                <polyline points="4 6 4 12 4 18"/>
                            </svg>
                            Urutan: {{ $slider->sort_order }}
                        </span>
                        @if($slider->is_active)
                        <span style="display:flex;align-items:center;gap:0.35rem;color:#22c55e;font-weight:500">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            Aktif
                        </span>
                        @else
                        <span style="display:flex;align-items:center;gap:0.35rem;color:#ef4444;font-weight:500">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                            </svg>
                            Nonaktif
                        </span>
                        @endif
                    </div>
                </div>
                <div style="display:flex;gap:0.5rem;align-items:center">
                    <a href="{{ $slider->image_url }}" target="_blank" class="btn-edit" title="Preview" style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#eff6ff;color:#2b6cb0;border-radius:6px;transition:all 0.2s" onmouseover="this.style.background='#2b6cb0';this.style.color='#fff'" onmouseout="this.style.background='#eff6ff';this.style.color='#2b6cb0'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </a>
                    <button onclick="editSlider({{ $slider->id }})" class="btn-edit" title="Edit" style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#eff6ff;color:#2b6cb0;border-radius:6px;transition:all 0.2s;border:none;cursor:pointer" onmouseover="this.style.background='#2b6cb0';this.style.color='#fff'" onmouseout="this.style.background='#eff6ff';this.style.color='#2b6cb0'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                    <form action="{{ route('admin.hero-sliders.destroy', $slider) }}" method="POST" onsubmit="return confirm('Hapus slide ini?')" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-del" title="Hapus" style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#fff5f5;color:#ef4444;border-radius:6px;transition:all 0.2s;border:none;cursor:pointer" onmouseover="this.style.background='#ef4444';this.style.color='#fff'" onmouseout="this.style.background='#fff5f5';this.style.color='#ef4444'">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                <line x1="10" y1="11" x2="10" y2="17"/>
                                <line x1="14" y1="11" x2="14" y2="17"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:3rem 1rem;color:var(--text-muted)">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin:0 auto 1rem;opacity:0.3">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                    <line x1="8" y1="21" x2="16" y2="21"/>
                    <line x1="12" y1="17" x2="12" y2="21"/>
                </svg>
                <p style="margin:0;font-size:0.95rem">Belum ada slide. Tambahkan slide pertama di atas.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;padding:1rem">
    <div style="background:#fff;border-radius:16px;max-width:500px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,0.3)">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
            <h3 style="margin:0;font-size:1.2rem;font-weight:700;color:var(--text-dark);display:flex;align-items:center;gap:0.75rem">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit Slide
            </h3>
            <button onclick="closeEditModal()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:var(--text-muted);width:32px;height:32px;display:flex;align-items:center;justify-content:center;border-radius:6px;transition:all 0.2s" onmouseover="this.style.background='#f1f5f9';this.style.color='var(--text-dark)'" onmouseout="this.style.background='none';this.style.color='var(--text-muted)'">&times;</button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data" style="padding:1.5rem;display:grid;gap:1rem">
            @csrf @method('PUT')
            <input type="hidden" id="editId" name="id">
            
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Gambar (kosongkan jika tidak diubah)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <img id="editImagePreview" src="" style="max-width:100%;max-height:200px;margin-top:0.75rem;border-radius:8px;display:none;border:1px solid var(--border)">
            </div>
            
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Durasi Tampil (detik)</label>
                <input type="number" name="display_duration" id="editDuration" class="form-control" min="3" max="30">
            </div>
            
            <div>
                <label style="font-weight:600;display:block;margin-bottom:0.5rem;font-size:0.9rem">Urutan</label>
                <input type="number" name="sort_order" id="editSortOrder" class="form-control" min="0">
            </div>
            
            <div>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                    <input type="checkbox" name="is_active" id="editActive" value="1" style="width:18px;height:18px;cursor:pointer">
                    <span style="font-weight:500;color:var(--text-dark)">Aktif</span>
                </label>
            </div>
            
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:1rem">
                <button type="button" onclick="closeEditModal()" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:0.5rem">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Batal
                </button>
                <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.5rem">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Edit slider
function editSlider(id) {
    const item = document.querySelector(`.slider-item[data-id="${id}"]`);
    if (!item) return;
    
    document.getElementById('editId').value = id;
    
    // Get duration and order from the info text
    const infoText = item.querySelector('div[style*="font-size:0.8rem"]').textContent;
    const durationMatch = infoText.match(/(\d+)s/);
    const orderMatch = infoText.match(/Urutan:\s*(\d+)/);
    
    document.getElementById('editDuration').value = durationMatch ? durationMatch[1] : '5';
    document.getElementById('editSortOrder').value = orderMatch ? orderMatch[1] : '0';
    
    // Check active status
    document.getElementById('editActive').checked = infoText.includes('Aktif') && !infoText.includes('Nonaktif');
    
    // Preview image
    const img = item.querySelector('img');
    const preview = document.getElementById('editImagePreview');
    preview.src = img.src;
    preview.style.display = 'block';
    
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
    document.getElementById('editImagePreview').style.display = 'none';
}

// Drag & drop reorder
let draggedItem = null;
document.getElementById('slidersList').addEventListener('dragstart', (e) => {
    if (e.target.classList.contains('slider-item')) {
        draggedItem = e.target;
        setTimeout(() => e.target.style.opacity = '0.5', 0);
    }
});
document.getElementById('slidersList').addEventListener('dragend', (e) => {
    if (draggedItem) {
        draggedItem.style.opacity = '1';
        updateOrder();
        draggedItem = null;
    }
});
document.getElementById('slidersList').addEventListener('dragover', (e) => {
    e.preventDefault();
    const afterElement = getDragAfterElement(document.getElementById('slidersList'), e.clientY);
    if (afterElement == null) {
        document.getElementById('slidersList').appendChild(draggedItem);
    } else {
        document.getElementById('slidersList').insertBefore(draggedItem, afterElement);
    }
});
function getDragAfterElement(container, y) {
    const draggableElements = [...container.querySelectorAll('.slider-item:not(.dragging)')];
    return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect();
        const offset = y - box.top - box.height / 2;
        return offset < 0 && offset > closest.offset ? { offset, element: child } : closest;
    }, { offset: Number.NEGATIVE_INFINITY }).element;
}
async function updateOrder() {
    const order = [...document.querySelectorAll('.slider-item')].map(el => el.dataset.id);
    await fetch('{{ route('admin.hero-sliders.reorder') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ order })
    });
}
</script>
@endsection