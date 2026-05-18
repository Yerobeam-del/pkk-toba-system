@extends('admin.layouts.app')
@section('title', 'Manajemen Desa')
@section('page-title', 'Manajemen Desa - Kabupaten Toba')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div style="margin-bottom:2rem">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap">
        <div>
            <h1 style="font-size:1.75rem;font-weight:700;color:var(--primary);margin:0 0 0.25rem 0">Manajemen Desa</h1>
            <p style="color:var(--text-muted);margin:0">Kabupaten Toba • Data dari API wilayah.id</p>
        </div>
        
        <div style="display:flex;align-items:center;gap:0.75rem">
            <select id="filterKecamatan" class="form-control" style="width:220px">
                <option value="">🔍 Filter Kecamatan</option>
            </select>
            <a id="btnTambahDesa" href="{{ route('admin.desa.create') }}" class="btn btn-primary" style="white-space:nowrap">
                + Tambah Desa
            </a>
        </div>
    </div>

    @if(session('success'))
    <div style="background:#f0fff4;border-left:4px solid var(--success);padding:1rem;margin-bottom:1.5rem;border-radius:8px;color:#276749">
        {{ session('success') }}
    </div>
    @endif

    {{-- Loading State --}}
    <div id="loading-state" style="text-align:center;padding:3rem;color:var(--text-muted)">
        <div style="font-size:1.2rem;margin-bottom:0.5rem">⏳ Memuat data...</div>
        <div style="font-size:0.85rem">Mengambil data desa per kecamatan</div>
    </div>

    {{-- Error State --}}
    <div id="error-state" style="display:none;text-align:center;padding:3rem">
        <div style="font-size:3rem;margin-bottom:1rem">⚠️</div>
        <h3 style="color:var(--danger);margin-bottom:0.5rem">Gagal Memuat Data</h3>
        <p id="error-message" style="color:var(--text-muted);margin-bottom:1.5rem"></p>
        <button onclick="location.reload()" class="btn btn-primary">🔄 Refresh Halaman</button>
    </div>

    {{-- Empty State - Belum Ada Desa --}}
    <div id="empty-state" style="display:none;text-align:center;padding:3rem">
        <div style="font-size:3rem;margin-bottom:1rem">📋</div>
        <h3 style="margin-bottom:0.5rem">Belum Ada Data Desa</h3>
        <p style="color:var(--text-muted);margin-bottom:1.5rem;max-width:500px;margin-left:auto;margin-right:auto">
            Belum ada desa yang diinput. Silakan tambah desa pertama Anda untuk mulai mengelola data.
        </p>
        <a href="{{ route('admin.desa.create') }}" class="btn btn-primary">+ Tambah Desa Pertama</a>
    </div>

    {{-- Content State --}}
    <div id="content-state" style="display:none">
        <div id="no-data-message" style="display:none;text-align:center;padding:2rem;background:#f8fafc;border-radius:8px;margin-bottom:1.5rem">
            <p style="color:var(--text-muted)">Tidak ada kecamatan dengan data desa. Silakan tambah desa untuk memulai.</p>
        </div>
        
        <div class="card">
            <div id="accordion-container"></div>
        </div>
    </div>
</div>

<script>
let allKecamatans = [];
let desasData = {};
let currentFilter = '';

// Debug: Log API responses
async function testAPI(url, name) {
    console.log(`🔍 Testing ${name}: ${url}`);
    try {
        const response = await fetch(url);
        console.log(`📡 Status ${name}:`, response.status, response.statusText);
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error(`❌ ${name} bukan JSON! Response:`, text.substring(0, 200));
            throw new Error(`${name} mengembalikan HTML, bukan JSON. Cek route!`);
        }
        
        const data = await response.json();
        console.log(`✅ ${name} Response:`, data);
        return { success: true, data };
    } catch (error) {
        console.error(`❌ ${name} Error:`, error.message);
        return { success: false, error: error.message };
    }
}

async function initData() {
    document.getElementById('loading-state').style.display = 'block';
    document.getElementById('error-state').style.display = 'none';
    document.getElementById('content-state').style.display = 'none';
    document.getElementById('empty-state').style.display = 'none';

    try {
        // 1. Test & Load Kecamatan
        const kecResult = await testAPI('/api/v1/kecamatans', 'Kecamatan API');
        
        if (!kecResult.success) {
            throw new Error(kecResult.error);
        }
        
        if (!kecResult.data.data || !Array.isArray(kecResult.data.data)) {
            throw new Error('Response kecamatan tidak valid');
        }
        
        allKecamatans = kecResult.data.data;
        console.log(`✅ Loaded ${allKecamatans.length} kecamatan(s)`);

        // 2. Test & Load Desa
        const desaResult = await testAPI('/api/v1/desas', 'Desa API');
        
        if (!desaResult.success) {
            console.warn('⚠️ Failed to load desa:', desaResult.error);
            desasData = {};
        } else {
            // Group desas by kecamatan_id
            desasData = {};
            let totalDesa = 0;
            
            desaResult.data.data.forEach(k => {
                desasData[k.id] = k.desas || [];
                totalDesa += (k.desas || []).length;
            });
            
            console.log(`✅ Loaded ${totalDesa} desa(s) in ${Object.keys(desasData).length} kecamatan(s)`);
        }

        // 3. Populate Filter Dropdown
        const filterSelect = document.getElementById('filterKecamatan');
        filterSelect.innerHTML = '<option value="">🔍 Filter Kecamatan</option>';
        
        const kecamatansWithDesa = allKecamatans.filter(k => (desasData[k.id] || []).length > 0);
        
        kecamatansWithDesa.forEach(k => {
            filterSelect.innerHTML += `<option value="${k.id}">${k.name} (${(desasData[k.id] || []).length} desa)</option>`;
        });

        // 4. Check if there's any data
        const totalDesa = Object.values(desasData).reduce((sum, arr) => sum + arr.length, 0);
        
        if (totalDesa === 0) {
            document.getElementById('loading-state').style.display = 'none';
            document.getElementById('empty-state').style.display = 'block';
            return;
        }

        // 5. Render & Show
        renderAccordion();
        document.getElementById('loading-state').style.display = 'none';
        document.getElementById('content-state').style.display = 'block';

    } catch (err) {
        console.error('💥 Fatal error:', err);
        document.getElementById('loading-state').style.display = 'none';
        document.getElementById('error-state').style.display = 'block';
        document.getElementById('error-message').textContent = err.message;
    }
}

// ... (kode selanjutnya tetap sama: filter event, renderAccordion, toggleKecamatan, deleteDesa)

document.getElementById('filterKecamatan').addEventListener('change', function(e) {
    currentFilter = e.target.value;
    const btn = document.getElementById('btnTambahDesa');
    const baseUrl = "{{ route('admin.desa.create') }}";
    btn.href = currentFilter ? `${baseUrl}?kecamatan=${currentFilter}` : baseUrl;
    renderAccordion();
});

function renderAccordion() {
    const container = document.getElementById('accordion-container');
    const noDataMsg = document.getElementById('no-data-message');
    
    let filteredKec = allKecamatans.filter(k => (desasData[k.id] || []).length > 0);
    
    if (currentFilter) {
        filteredKec = filteredKec.filter(k => k.id == currentFilter);
    }

    if (filteredKec.length === 0) {
        noDataMsg.style.display = 'block';
        container.innerHTML = '';
        return;
    } else {
        noDataMsg.style.display = 'none';
    }

    container.innerHTML = filteredKec.map((kec) => {
        const desas = desasData[kec.id] || [];
        const count = desas.length;
        const totalPenduduk = desas.reduce((s, d) => s + (parseInt(d.population) || 0), 0);
        const totalKK = desas.reduce((s, d) => s + (parseInt(d.households) || 0), 0);

        return `
        <div style="border-bottom:1px solid var(--border)">
            <button onclick="toggleKecamatan('${kec.id}')" 
                    style="width:100%;padding:1rem 1.5rem;display:flex;justify-content:space-between;align-items:center;background:#f8fafc;border:none;cursor:pointer;text-align:left;font-weight:600;color:var(--primary);font-size:1.05rem">
                <span>📍 ${kec.name}</span>
                <span style="display:flex;align-items:center;gap:1rem">
                    <span style="background:var(--primary);color:#fff;padding:0.25rem 0.75rem;border-radius:20px;font-size:0.8rem;font-weight:600">
                        ${count} Desa
                    </span>
                    <span id="icon-${kec.id}" style="transition:transform 0.2s">▼</span>
                </span>
            </button>
            
            <div id="content-${kec.id}" style="display:none;padding:1.5rem;background:#fff">
                <div style="display:flex;gap:2rem;margin-bottom:1.5rem;padding:1rem;background:#f8fafc;border-radius:8px;font-size:0.9rem;flex-wrap:wrap">
                    <span>👥 Total Penduduk: <strong style="color:var(--primary)">${totalPenduduk.toLocaleString('id-ID')}</strong></span>
                    <span>🏠 Total KK: <strong style="color:var(--primary)">${totalKK.toLocaleString('id-ID')}</strong></span>
                </div>
                <div style="overflow-x:auto">
                    <table style="width:100%;border-collapse:collapse;font-size:0.9rem">
                        <thead style="background:#f1f5f9;text-align:left">
                            <tr>
                                <th style="padding:0.75rem 1rem">Foto</th>
                                <th style="padding:0.75rem 1rem">Nama Desa</th>
                                <th style="padding:0.75rem 1rem">Penduduk</th>
                                <th style="padding:0.75rem 1rem">KK</th>
                                <th style="padding:0.75rem 1rem">Status</th>
                                <th style="padding:0.75rem 1rem;text-align:right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${desas.map(d => `
                            <tr style="border-top:1px solid #f1f5f9">
                                <td style="padding:0.75rem 1rem">
                                    ${d.image 
                                        ? `<img src="${d.image}" style="width:40px;height:40px;border-radius:6px;object-fit:cover">`
                                        : `<div style="width:40px;height:40px;border-radius:6px;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-size:1.1rem">🏘️</div>`
                                    }
                                </td>
                                <td style="padding:0.75rem 1rem;font-weight:500">${d.name}</td>
                                <td style="padding:0.75rem 1rem">${(d.population||0).toLocaleString('id-ID')}</td>
                                <td style="padding:0.75rem 1rem">${(d.households||0).toLocaleString('id-ID')}</td>
                                <td style="padding:0.75rem 1rem">
                                    ${d.is_active 
                                        ? `<span style="padding:0.15rem 0.5rem;background:#dcfce7;color:#166534;border-radius:20px;font-size:0.75rem;font-weight:600">Aktif</span>`
                                        : `<span style="padding:0.15rem 0.5rem;background:#f1f5f9;color:#475569;border-radius:20px;font-size:0.75rem;font-weight:600">Nonaktif</span>`
                                    }
                                </td>
                                <td style="padding:0.75rem 1rem;text-align:right">
                                    <a href="/admin/desa/${d.id}/edit" style="color:var(--primary);margin-right:0.5rem" title="Edit">✏️</a>
                                    <button onclick="deleteDesa('${d.id}', '${d.name.replace(/'/g, "\\'")}')" style="background:none;border:none;color:#ef4444;cursor:pointer" title="Hapus">🗑️</button>
                                </td>
                            </tr>`).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>`;
    }).join('');
}

function toggleKecamatan(id) {
    const content = document.getElementById(`content-${id}`);
    const icon = document.getElementById(`icon-${id}`);
    if (!content || !icon) return;
    const isOpen = content.style.display === 'block';
    content.style.display = isOpen ? 'none' : 'block';
    icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
}

function deleteDesa(id, name) {
    if (!confirm(`Hapus desa "${name}"?`)) return;
    fetch(`/admin/desa/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    }).then(res => {
        if (res.ok) { alert('✅ Desa berhasil dihapus'); location.reload(); }
        else { alert('❌ Gagal menghapus desa'); }
    }).catch(err => { console.error(err); alert('❌ Error'); });
}

document.addEventListener('DOMContentLoaded', initData);
</script>
@endsection