<div class="page" id="page-sk" style="display: none;">
    <div class="page-header" style="background: linear-gradient(135deg, #553c9a, #6b46c1);">
        <div class="page-header-content">
            <h1>SK & Dokumen</h1>
            <p>Surat Keputusan dan dokumen resmi PKK Kabupaten Toba</p>
            <div class="breadcrumb">
                <a onclick="navigateTo('beranda')">Beranda</a><span>/</span><span class="current">SK & Dokumen</span>
            </div>
        </div>
    </div>

    <section class="sk-section" style="padding: 4rem 2rem; background: var(--bg-light);">
        <div class="sk-container" style="max-width: 1000px; margin: 0 auto;">
            <div style="margin-bottom: 2rem;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-dark); margin-bottom: 1rem;">Daftar Dokumen</h2>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div style="flex: 1; position: relative;">
                        <input type="text" id="searchInput" placeholder="🔍 Cari dokumen..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 0.95rem;">
                        <svg style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--text-muted);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    </div>
                </div>
            </div>

            <div id="loadingState" style="text-align: center; padding: 3rem;">
                <div style="font-size: 1.2rem; color: var(--text-muted);">⏳ Memuat dokumen...</div>
            </div>

            <div id="documentsTable" style="display: none; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                        <thead style="background: linear-gradient(135deg, #553c9a, #6b46c1); color: #fff;">
                            <tr>
                                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 600; width: 60px;">No</th>
                                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 600; width: 40%;">Nama Dokumen</th>
                                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 600; width: 120px;">Tanggal</th>
                                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 600; width: 100px;">Ukuran</th>
                                <th style="padding: 1rem 1.5rem; text-align: center; font-size: 0.85rem; font-weight: 600; position: sticky; right: 0; background: linear-gradient(135deg, #553c9a, #6b46c1); z-index: 10; width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="documentsBody"></tbody>
                    </table>
                </div>
            </div>

            <div id="emptyState" style="display: none; text-align: center; padding: 4rem 2rem; background: #fff; border-radius: 16px;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📄</div>
                <h3 style="color: var(--text-muted); margin-bottom: 0.5rem;">Belum Ada Dokumen</h3>
                <p style="color: var(--text-muted);">Dokumen akan segera ditambahkan</p>
            </div>
        </div>
    </section>
</div>

<script>
// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase().trim();
            const rows = document.querySelectorAll('#documentsBody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }
});
</script>