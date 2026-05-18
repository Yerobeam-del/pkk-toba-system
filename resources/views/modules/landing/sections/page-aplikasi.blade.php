<div class="page" id="page-aplikasi" style="display: none;">
    <div class="page-header" style="background: linear-gradient(135deg, #2b6cb0, #3182ce);">
        <div class="page-header-content">
            <h1>Aplikasi & Sistem</h1>
            <p>Sistem informasi digital PKK Kabupaten Toba</p>
            <div class="breadcrumb">
                <a onclick="navigateTo('beranda')">Beranda</a><span>/</span><span class="current">Aplikasi</span>
            </div>
        </div>
    </div>
    
    <div id="aplikasi-loading" style="text-align: center; padding: 4rem 2rem;">
        <div style="font-size: 1.2rem; color: var(--text-muted);">⏳ Memuat data aplikasi...</div>
    </div>
    
    <section class="apps-full-section" id="aplikasi-content" style="display: none;">
        <div class="section-header">
            <div class="section-label">Aplikasi Aktif</div>
            <h2 class="section-title">Sistem yang Tersedia</h2>
        </div>
        <div class="apps-full-grid" id="active-apps-grid">
            <div style="grid-column: 1/-1; text-align: center; padding: 2rem; color: var(--text-muted);">Memuat aplikasi aktif...</div>
        </div>

        <div class="section-header" style="margin-top: 4rem;" id="development-section-header">
            <div class="section-label">Akan Datang</div>
            <h2 class="section-title">Aplikasi dalam Pengembangan</h2>
        </div>
        <div class="coming-full-grid" id="development-apps-grid">
            <div style="grid-column: 1/-1; text-align: center; padding: 2rem; color: var(--text-muted);">Memuat aplikasi yang sedang dikembangkan...</div>
        </div>
    </section>
</div>

<script>
let aplikasiDataLoaded = false;

function getIconHtml(app, size = 80) {
    return `
    <div style="width:${size}px;height:${size}px;border-radius:16px;
                background:#e2e8f0;
                border:1px solid #cbd5e1;
                display:flex;align-items:center;justify-content:center;">
    </div>
    `;
}

function buildImageUrl(iconPath) {
    if (!iconPath) return null;
    return '/storage/' + iconPath.replace(/^(storage\/|public\/|app\/public\/)/i, '');
}

async function loadAplikasiData() {
    if (aplikasiDataLoaded) return;
    const loadingEl = document.getElementById('aplikasi-loading');
    const contentEl = document.getElementById('aplikasi-content');
    try {
        const response = await fetch('/api/v1/applications');
        const result = await response.json();
        if (!result.success) throw new Error(result.message);
        const { active, development } = result.data;
        renderActiveApps(active || []);
        renderDevelopmentApps(development || []);
        if (!active || active.length === 0) document.querySelector('.apps-full-grid').innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--text-muted)">Belum ada aplikasi aktif.</div>';
        if (!development || development.length === 0) { document.getElementById('development-section-header').style.display = 'none'; document.getElementById('development-apps-grid').innerHTML = ''; }
        loadingEl.style.display = 'none';
        contentEl.style.display = 'block';
        aplikasiDataLoaded = true;
    } catch (error) {
        console.error('Error:', error);
        loadingEl.innerHTML = '<p style="color:var(--danger)">Gagal memuat data aplikasi</p>';
    }
}

function renderActiveApps(apps) {
    const container = document.getElementById('active-apps-grid');
    if (!container) return;
    if (apps.length === 0) { container.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--text-muted)">Belum ada aplikasi aktif.</div>'; return; }
    
    const activeCardTemplate = (app, index) => {
        const colors = ['sieda', 'SIDONGAN', 'app3', 'app4'];
        const cardClass = colors[index % colors.length] || 'sieda';
        
        let iconContent = '';
        if (app.icon) {
            const imgUrl = buildImageUrl(app.icon);
            // 🔴 DEBUG: Border merah dashed + !important untuk paksa tampil
            iconContent += `<img src="${imgUrl}" alt="${app.short_name}" 
                style="width:100%;height:100%;object-fit:contain;padding:10px;display:block;
                       background:transparent;
                       border-radius:12px;z-index:10;position:relative;
                       filter:none !important; mix-blend-mode:normal !important;"
                onerror="this.style.display='none';document.getElementById('ph-'+app.id).style.display='flex'">`;
            iconContent += `<div id="ph-${app.id}" style="display:none;width:100%;height:100%;">${getIconHtml(app, 80)}</div>`;
        } else {
            iconContent = getIconHtml(app, 80);
        }
        
        const features = Array.isArray(app.features) ? app.features.slice(0, 5) : [];
        const featuresHtml = features.length > 0 ? features.map(f => `<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>${f}</li>`).join('') : `<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>Fitur unggulan aplikasi</li>`;
        
        return `<a href="${app.url && app.url !== '#' ? app.url : '#'}" target="_blank" class="app-card-home ${cardClass}" ${!app.url || app.url === '#' ? 'style="pointer-events:none;opacity:0.7"' : ''}>
            <div class="app-card-header">
                <div class="app-icon-wrapper" style="width:80px;height:80px;border-radius:16px;overflow:hidden;background:#f1f5f9;border:1px solid #cbd5e1;box-shadow:0 4px 12px rgba(0,0,0,0.1);display:flex;align-items:center;justify-content:center">
                    ${iconContent}
                </div>
            </div>
            <div class="app-card-body">
                <h3 class="app-name">${app.short_name || app.name}</h3>
                <p class="app-fullname">${app.name}</p>
                <p class="app-description">${app.description || 'Sistem informasi digital terpadu PKK Kabupaten Toba.'}</p>
                <ul class="app-features">${featuresHtml}</ul>
            </div>
            <div class="app-card-footer">
                <span class="app-btn">${app.url && app.url !== '#' ? `Akses ${app.short_name || 'Aplikasi'}` : 'Akses Terbatas'}<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                <div class="app-status"><div class="app-status-dot"></div>Aktif</div>
            </div>
        </a>`;
    };
    container.innerHTML = apps.map((app, i) => activeCardTemplate(app, i)).join('');
}

function renderDevelopmentApps(apps) {
    const container = document.getElementById('development-apps-grid');
    const header = document.getElementById('development-section-header');
    if (!container) return;
    if (!apps || apps.length === 0) { header.style.display = 'none'; container.innerHTML = ''; return; }
    
    const devCardTemplate = (app, index) => {
        const colors = ['coming1', 'coming2', 'coming3', 'coming4'];
        const cardClass = colors[index % colors.length] || 'coming1';
        
        let iconContent = '';
        if (app.icon) {
            const imgUrl = buildImageUrl(app.icon);
            iconContent += `<img src="${imgUrl}" alt="${app.name}" 
                style="width:100%;height:100%;object-fit:contain;padding:10px;display:block;
                       background:transparent;
                       border-radius:12px;z-index:10;position:relative;
                       filter:none !important; mix-blend-mode:normal !important;"
                onerror="this.style.display='none';document.getElementById('ph-dev-'+app.id).style.display='flex'">`;
            iconContent += `<div id="ph-dev-${app.id}" style="display:none;width:100%;height:100%;">${getIconHtml(app, 80)}</div>`;
        } else {
            iconContent = getIconHtml(app, 80);
        }
        
        return `<div class="coming-home-card ${cardClass}">
            <div class="coming-home-card-header">
                <div class="coming-icon-wrapper" style="width:80px;height:80px;border-radius:16px;overflow:hidden;background:#f1f5f9;border:1px solid #cbd5e1;box-shadow:0 4px 12px rgba(0,0,0,0.1);display:flex;align-items:center;justify-content:center">
                    ${iconContent}
                </div>
            </div>
            <div class="coming-home-card-body">
                <h3 class="coming-name">${app.short_name || app.name}</h3>
                <p class="coming-fullname">${app.name}</p>
                <p class="coming-description">${app.description || 'Tim pengembangan PKK Kabupaten Toba sedang mempersiapkan aplikasi digital terbaru.'}</p>
                <div class="coming-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg><span>Segera Hadir</span></div>
                <ul class="coming-features">
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>Sedang dalam tahap pengembangan</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>Fitur akan diumumkan segera</li>
                </ul>
            </div>
            <div class="coming-home-card-footer">
                <span class="coming-btn-home">Coming Soon<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>
                <div class="coming-status"><div class="coming-status-dot"></div>Dalam Pengembangan</div>
            </div>
        </div>`;
    };
    container.innerHTML = apps.map((app, i) => devCardTemplate(app, i)).join('');
}

document.addEventListener('DOMContentLoaded', () => {
    const observer = new MutationObserver(() => {
        const page = document.getElementById('page-aplikasi');
        if (page && page.classList.contains('active') && !aplikasiDataLoaded) {
            setTimeout(() => loadAplikasiData(), 100);
            observer.disconnect();
        }
    });
    observer.observe(document.body, { childList: true, subtree: true });
});
</script>