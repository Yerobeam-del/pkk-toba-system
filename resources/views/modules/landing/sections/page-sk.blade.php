<div class="page" id="page-sk">
    <div class="page-header" style="background: linear-gradient(135deg, #553c9a, #805ad5);">
        <div class="page-header-content">
            <h1>SK & Dokumen</h1>
            <p>Surat Keputusan dan dokumen resmi PKK Kabupaten Toba</p>
            <div class="breadcrumb">
                <a onclick="navigateTo('beranda')">Beranda</a><span>/</span><span class="current">SK & Dokumen</span>
            </div>
        </div>
    </div>
    <section class="sk-section">
        <div class="sk-container">
            <div class="sk-list-header">
                <h3>Daftar Dokumen</h3>
                <div class="sk-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" placeholder="Cari dokumen..." oninput="searchSK(this.value)">
                </div>
            </div>
            <div class="sk-table">
                <div class="sk-table-header">
                    <div></div><div>Nama Dokumen</div><div>Tanggal</div><div>Ukuran</div><div>Aksi</div>
                </div>
                <div id="skTableBody"></div>
            </div>
        </div>
    </section>
</div>