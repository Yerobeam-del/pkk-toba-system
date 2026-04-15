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
        <div class="desa-filter">
            <button class="filter-btn active" onclick="filterDesa('all', this)">Semua Desa</button>
            <button class="filter-btn" onclick="filterDesa('balige', this)">Balige</button>
            <button class="filter-btn" onclick="filterDesa('laguboti', this)">Laguboti</button>
            <button class="filter-btn" onclick="filterDesa('habinsaran', this)">Habinsaran</button>
            <button class="filter-btn" onclick="filterDesa('borbor', this)">Borbor</button>
            <button class="filter-btn" onclick="filterDesa('nassau', this)">Nassau</button>
            <button class="filter-btn" onclick="filterDesa('silaen', this)">Silaen</button>
            <button class="filter-btn" onclick="filterDesa('siantar', this)">Siantar Narumonda</button>
        </div>
        <div class="desa-grid" id="desaGrid"></div>
    </section>
</div>