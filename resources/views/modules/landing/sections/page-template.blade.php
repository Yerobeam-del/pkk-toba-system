<div class="page" id="page-template" style="display: none;">
    <div class="page-header" style="background: linear-gradient(135deg, #2563eb, #3b82f6);">
        <div class="page-header-content">
            <h1>Template PKK</h1>
            <p>Template surat dan formulir yang dapat dicetak untuk keperluan PKK</p>
            <div class="breadcrumb">
                <a onclick="navigateTo('beranda')">Beranda</a><span>/</span><span class="current">Template</span>
            </div>
        </div>
    </div>

    <section style="padding: 4rem 2rem; background: var(--bg-light);">
        <div style="max-width: 1200px; margin: 0 auto;">
            
            {{-- Search Bar --}}
            <div style="margin-bottom: 3rem;">
                <div style="position: relative; max-width: 600px; margin: 0 auto;">
                    <input type="text" 
                        id="searchInput" 
                        placeholder="🔍 Cari template..." 
                        style="width: 100%; padding: 1rem 1.5rem 1rem 3rem; border: 2px solid #e2e8f0; border-radius: 50px; font-size: 1rem; outline: none;"
                        oninput="if(typeof handleTemplateSearch==='function'){handleTemplateSearch(this.value)}">
                    <svg style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; color: var(--text-muted);" 
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                </div>
            </div>

            {{-- Template Grid --}}
            <div id="templateGrid" style="display: none; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2rem;">
            </div>

            {{-- Empty State --}}
            <div id="emptyState" style="display: none; text-align: center; padding: 5rem 2rem; background: #fff; border-radius: 16px;">
                <div style="font-size: 5rem; margin-bottom: 1rem;">📄</div>
                <h3 style="color: var(--text-muted); margin-bottom: 0.5rem;">Belum Ada Template</h3>
            </div>
        </div>
    </section>
</div>

<script>
// Global variable
window.allTemplates = [];

// Main function - EXPOSED GLOBALLY
window.populateTemplates = async function() {
    console.log('📋 [TEMPLATE] populateTemplates() STARTED');
    
    const loadingEl = document.getElementById('loadingState');
    const gridEl = document.getElementById('templateGrid');
    const emptyEl = document.getElementById('emptyState');
    
    // Check elements exist
    if (!loadingEl) { console.error('❌ loadingState not found'); return; }
    if (!gridEl) { console.error('❌ templateGrid not found'); return; }
    if (!emptyEl) { console.error('❌ emptyState not found'); return; }
    
    // Show loading
    loadingEl.style.display = 'block';
    gridEl.style.display = 'none';
    emptyEl.style.display = 'none';
    
    try {
        console.log('🔄 [TEMPLATE] Fetching from /api/v1/templates...');
        const response = await fetch('/api/v1/templates');
        console.log('📡 [TEMPLATE] Response status:', response.status);
        
        if (!response.ok) {
            throw new Error('HTTP ' + response.status);
        }
        
        const result = await response.json();
        console.log('📦 [TEMPLATE] API Result:', result);
        
        if (!result.success) {
            throw new Error(result.message || 'API error');
        }
        
        // Filter published only
        const templates = (result.data || []).filter(t => t.status === 'published');
        console.log('✅ [TEMPLATE] Found', templates.length, 'published templates');
        
        // Hide loading
        loadingEl.style.display = 'none';
        
        if (templates.length === 0) {
            console.log('⚠️ [TEMPLATE] No templates found');
            gridEl.style.display = 'none';
            emptyEl.style.display = 'block';
            return;
        }
        
        // Show grid
        gridEl.style.display = 'grid';
        emptyEl.style.display = 'none';
        
        // Store globally
        window.allTemplates = templates;
        
        // Render cards
        const imageUrl = 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=600&h=400&fit=crop';
        
        gridEl.innerHTML = templates.map((tpl, i) => {
            const ext = tpl.file_name ? tpl.file_name.split('.').pop().toUpperCase() : 'FILE';
            const date = tpl.formatted_date || new Date(tpl.upload_date).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });
            const size = tpl.file_size || '-';
            
            return `
            <div style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.08);transition:all 0.3s ease;display:flex;flex-direction:column"
                 onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,0.12)'"
                 onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'">
                
                <div style="position:relative;height:200px;overflow:hidden;background:#f1f5f9">
                    <img src="${imageUrl}" alt="${tpl.name}" style="width:100%;height:100%;object-fit:cover">
                    <div style="position:absolute;top:1rem;right:1rem;padding:0.3rem 0.8rem;background:#dcfce7;border-radius:20px;font-size:0.75rem;font-weight:700;color:#166534">Template</div>
                </div>
                
                <div style="padding:1.5rem;flex:1;display:flex;flex-direction:column">
                    <h3 style="font-size:1.1rem;font-weight:700;color:#1e293b;margin-bottom:0.5rem;line-height:1.4">${tpl.name}</h3>
                    
                    <div style="display:flex;align-items:center;gap:0.5rem;color:#64748b;font-size:0.85rem;margin-bottom:1rem">
                        <span style="background:#f1f5f9;padding:0.1rem 0.4rem;border-radius:4px;font-weight:600;font-size:0.75rem">${ext}</span>
                        <span>•</span>
                        <span>${size}</span>
                        <span>•</span>
                        <span>${date}</span>
                    </div>
                    
                    <div style="margin-top:auto">
                        <a href="${tpl.file_url}" download="${tpl.file_name}"
                           style="display:flex;align-items:center;justify-content:center;gap:0.5rem;padding:0.75rem;background:#3b82f6;color:#fff;border-radius:8px;font-weight:600;text-decoration:none;transition:background 0.3s"
                           onmouseover="this.style.background='#2563eb'"
                           onmouseout="this.style.background='#3b82f6'">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Unduh Template
                        </a>
                    </div>
                </div>
            </div>
            `;
        }).join('');
        
        console.log('✅ [TEMPLATE] Rendered successfully');
        
    } catch (error) {
        console.error('❌ [TEMPLATE] Error:', error);
        loadingEl.innerHTML = `
            <div style="color:var(--danger);font-size:1.1rem">
                ❌ Gagal memuat template<br>
                <small style="color:var(--text-muted)">${error.message}</small><br>
                <button onclick="populateTemplates()" style="margin-top:1rem;padding:0.5rem 1.5rem;background:var(--primary);color:#fff;border:none;border-radius:8px;cursor:pointer">🔄 Coba Lagi</button>
            </div>
        `;
    }
};

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase().trim();
            const gridEl = document.getElementById('templateGrid');
            
            if (!gridEl || !window.allTemplates) return;
            
            const filtered = term === '' ? window.allTemplates : window.allTemplates.filter(t => 
                (t.name && t.name.toLowerCase().includes(term)) || 
                (t.file_name && t.file_name.toLowerCase().includes(term))
            );
            
            console.log('🔍 Search:', term, '- Found', filtered.length, 'templates');
            
            // Re-render with filtered data (same render logic as above)
            const imageUrl = 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=600&h=400&fit=crop';
            
            gridEl.innerHTML = filtered.map((tpl, i) => {
                const ext = tpl.file_name ? tpl.file_name.split('.').pop().toUpperCase() : 'FILE';
                const date = tpl.formatted_date || new Date(tpl.upload_date).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });
                const size = tpl.file_size || '-';
                
                return `
                <div style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.08);transition:all 0.3s ease;display:flex;flex-direction:column"
                     onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,0.12)'"
                     onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'">
                    
                    <div style="position:relative;height:200px;overflow:hidden;background:#f1f5f9">
                        <img src="${imageUrl}" alt="${tpl.name}" style="width:100%;height:100%;object-fit:cover">
                        <div style="position:absolute;top:1rem;right:1rem;padding:0.3rem 0.8rem;background:#dcfce7;border-radius:20px;font-size:0.75rem;font-weight:700;color:#166534">Template</div>
                    </div>
                    
                    <div style="padding:1.5rem;flex:1;display:flex;flex-direction:column">
                        <h3 style="font-size:1.1rem;font-weight:700;color:#1e293b;margin-bottom:0.5rem;line-height:1.4">${tpl.name}</h3>
                        
                        <div style="display:flex;align-items:center;gap:0.5rem;color:#64748b;font-size:0.85rem;margin-bottom:1rem">
                            <span style="background:#f1f5f9;padding:0.1rem 0.4rem;border-radius:4px;font-weight:600;font-size:0.75rem">${ext}</span>
                            <span>•</span>
                            <span>${size}</span>
                            <span>•</span>
                            <span>${date}</span>
                        </div>
                        
                        <div style="margin-top:auto">
                            <a href="${tpl.file_url}" download="${tpl.file_name}"
                               style="display:flex;align-items:center;justify-content:center;gap:0.5rem;padding:0.75rem;background:#3b82f6;color:#fff;border-radius:8px;font-weight:600;text-decoration:none;transition:background 0.3s"
                               onmouseover="this.style.background='#2563eb'"
                               onmouseout="this.style.background='#3b82f6'">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                Unduh Template
                            </a>
                        </div>
                    </div>
                </div>
                `;
            }).join('');
        });
    }
    
    console.log('✅ [TEMPLATE] Script loaded and search initialized');
});
</script>