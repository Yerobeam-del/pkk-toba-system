<div class="page" id="page-desa">
    <div class="page-header" style="background: linear-gradient(135deg, #9b2c2c, #c53030);">
        <div class="page-header-content">
            <h1>Data Desa</h1>
            <p>Daftar desa dan kelurahan di Kabupaten Toba, Sumatera Utara</p>
            <div class="breadcrumb">
                <a onclick="navigateTo('beranda')">Beranda</a><span>/</span><span class="current">Desa</span>
            </div>
        </div>
    </div>
    
    <section class="desa-section">
        {{-- Filter Container (Akan diisi otomatis oleh JS berdasarkan data database) --}}
        <div class="desa-filter" id="desaFilter">
            <div style="text-align:center; padding:1rem; color:var(--text-muted)">Memuat filter...</div>
        </div>
        
        {{-- Grid Container (Kartu desa akan muncul di sini) --}}
        <div class="desa-grid" id="desaGrid">
            <div style="grid-column: 1/-1; text-align:center; padding:2rem;">
                ⏳ Memuat data desa...
            </div>
        </div>
    </section>
</div>

<script>
// Variabel untuk menyimpan data desa
let allDesaData = [];

// Fungsi utama untuk mengambil data dari API
async function loadDesaData() {
    try {
        const response = await fetch('/api/v1/desas');
        const result = await response.json();
        
        if (!result.success) throw new Error('Gagal memuat data');
        
        // Proses data: Gabungkan semua desa dari semua kecamatan
        allDesaData = [];
        const kecamatanList = new Set();
        
        result.data.forEach(kec => {
            if (kec.desas && kec.desas.length > 0) {
                kec.desas.forEach(desa => {
                    // Tambahkan info kecamatan ke setiap desa
                    allDesaData.push({
                        ...desa,
                        kecamatan_name: kec.name,
                        kecamatan_slug: kec.name.toLowerCase().replace(/\s+/g, '-') // Buat slug unik untuk filter
                    });
                    kecamatanList.add(kec.name);
                });
            }
        });
        
        // 1. Render Filter Buttons (Dinamis sesuai data)
        renderFilters(kecamatanList);
        
        // 2. Render Semua Kartu Desa (Awalnya tampilkan semua)
        renderCards(allDesaData);
        
    } catch (error) {
        console.error(error);
        document.getElementById('desaGrid').innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:2rem;color:var(--danger)">Gagal memuat data desa.</div>';
    }
}

// Fungsi untuk membuat tombol filter
function renderFilters(kecamatanSet) {
    const container = document.getElementById('desaFilter');
    const kecamatanArray = Array.from(kecamatanSet).sort();
    
    let html = `<button class="filter-btn active" onclick="filterDesa('all', this)">Semua Desa</button>`;
    
    kecamatanArray.forEach(namaKec => {
        // Buat ID unik untuk setiap kecamatan (misal: "Siantar Narumonda" jadi "siantar-narumonda")
        const slug = namaKec.toLowerCase().replace(/\s+/g, '-');
        html += `<button class="filter-btn" onclick="filterDesa('${slug}', this)">${namaKec}</button>`;
    });
    
    container.innerHTML = html;
}

// Fungsi untuk menampilkan kartu desa
function renderCards(data) {
    const container = document.getElementById('desaGrid');
    
    if (data.length === 0) {
        container.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--text-muted)">Tidak ada data desa untuk filter ini.</div>';
        return;
    }
    
    // Template kartu sesuai desain Anda (Gambar, Judul, Info, Statistik)
    container.innerHTML = data.map(desa => `
        <div class="desa-card" data-kecamatan="${desa.kecamatan_slug}">
            <div class="desa-image" style="width:100%;height:200px;overflow:hidden;border-radius:8px 8px 0 0;background:#f0f0f0">
                ${desa.image 
                    ? `<img src="${desa.image}" alt="${desa.name}" style="width:100%;height:100%;object-fit:cover">` 
                    : `<div style="display:flex;align-items:center;justify-content:center;height:100%;font-size:3rem;color:#ccc">🏘️</div>`
                }
            </div>
            <div class="desa-info" style="padding:1rem">
                <h3 class="desa-name" style="font-size:1.1rem;font-weight:bold;margin-bottom:0.25rem">${desa.name}</h3>
                <p class="desa-kecamatan" style="font-size:0.85rem;color:var(--text-muted);margin-bottom:0.75rem">📍 ${desa.kecamatan_name}</p>
                
                <div class="desa-stats" style="display:flex;justify-content:space-between;border-top:1px solid #eee;padding-top:0.75rem">
                    <div class="stat-item">
                        <div class="stat-value" style="font-weight:bold;color:#9b2c2c">${desa.population ? Number(desa.population).toLocaleString('id-ID') : '0'}</div>
                        <div class="stat-label" style="font-size:0.75rem;color:var(--text-muted)">Penduduk</div>
                    </div>
                    <div class="stat-item" style="text-align:right">
                        <div class="stat-value" style="font-weight:bold;color:#9b2c2c">${desa.households ? Number(desa.households).toLocaleString('id-ID') : '0'}</div>
                        <div class="stat-label" style="font-size:0.75rem;color:var(--text-muted)">KK</div>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

// Fungsi Filter
function filterDesa(slug, btnElement) {
    // Update tampilan tombol aktif
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btnElement.classList.add('active');
    
    // Filter data
    if (slug === 'all') {
        renderCards(allDesaData);
    } else {
        const filtered = allDesaData.filter(d => d.kecamatan_slug === slug);
        renderCards(filtered);
    }
}

// Jalankan saat halaman dibuka
document.addEventListener('DOMContentLoaded', loadDesaData);
</script>