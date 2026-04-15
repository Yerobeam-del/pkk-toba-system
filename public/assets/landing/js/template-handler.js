/**
 * Template Handler Module
 * Renders template cards for download
 */

const templateData = [
    { 
        image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/14e0450b6-54db-4544-a34c-f9be7c92d02b.png', 
        category: 'Surat Resmi', 
        title: 'Template Surat Resmi PKK', 
        desc: 'Template surat resmi untuk kebutuhan administrasi PKK termasuk surat undangan, surat pemberitahuan, dan surat edaran.', 
        format: 'DOCX', 
        size: '245 KB' 
    },
    { 
        image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/14e0450b6-54db-4544-a34c-f9be7c92d02b.png', 
        category: 'Formulir', 
        title: 'Formulir Pendataan Dasawisma', 
        desc: 'Formulir standar untuk pendataan keluarga dalam kelompok dasawisma sesuai format yang ditetapkan.', 
        format: 'PDF', 
        size: '180 KB' 
    },
    { 
        image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/14e0450b6-54db-4544-a34c-f9be7c92d02b.png', 
        category: 'Laporan', 
        title: 'Template Laporan Kegiatan Bulanan', 
        desc: 'Template laporan kegiatan bulanan PKK untuk dicetak dan diserahkan ke kecamatan.', 
        format: 'DOCX', 
        size: '320 KB' 
    },
    { 
        image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/14e0450b6-54db-4544-a34c-f9be7c92d02b.png', 
        category: 'Administrasi', 
        title: 'Notulen Rapat PKK', 
        desc: 'Template notulen rapat untuk mendokumentasikan setiap kegiatan rapat PKK di tingkat desa.', 
        format: 'DOCX', 
        size: '156 KB' 
    },
    { 
        image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/14e0450b6-54db-4544-a34c-f9be7c92d02b.png', 
        category: 'Program', 
        title: 'Template Proposal Kegiatan', 
        desc: 'Template proposal untuk pengajuan kegiatan PKK ke sumber dana atau sponsor.', 
        format: 'DOCX', 
        size: '280 KB' 
    },
    { 
        image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/14e0450b6-54db-4544-a34c-f9be7c92d02b.png', 
        category: 'Kesehatan', 
        title: 'Formulir Posyandu', 
        desc: 'Formulir pencatatan data posyandu termasuk kartu kesehatan ibu dan anak.', 
        format: 'PDF', 
        size: '195 KB' 
    },
    { 
        image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/14e0450b6-54db-4544-a34c-f9be7c92d02b.png', 
        category: 'Surat Resmi', 
        title: 'Template Surat Undangan Rapat', 
        desc: 'Template surat undangan rapat PKK yang dapat disesuaikan dengan kebutuhan.', 
        format: 'DOCX', 
        size: '168 KB' 
    },
    { 
        image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/14e0450b6-54db-4544-a34c-f9be7c92d02b.png', 
        category: 'Keuangan', 
        title: 'Template Laporan Keuangan', 
        desc: 'Template laporan keuangan sederhana untuk pencatatan kas PKK tingkat desa.', 
        format: 'XLSX', 
        size: '210 KB' 
    },
    { 
        image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/14e0450b6-54db-4544-a34c-f9be7c92d02b.png', 
        category: 'Formulir', 
        title: 'Formulir Pendaftaran Anggota PKK', 
        desc: 'Formulir pendaftaran anggota PKK baru untuk tingkat desa dan kelurahan.', 
        format: 'PDF', 
        size: '142 KB' 
    },
];

function renderTemplateCard(template) {
    return `<div class="template-card">
        <img src="${template.image}" alt="${template.title}" class="template-card-image">
        <div class="template-card-body">
            <span class="template-card-category">${template.category}</span>
            <h3 class="template-card-title">${template.title}</h3>
            <p class="template-card-desc">${template.desc}</p>
            <div class="template-card-meta">
                <div class="template-card-format">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    ${template.format} • ${template.size}
                </div>
                <button class="template-download-btn" onclick="alert('Mengunduh template: ${template.title}')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Unduh
                </button>
            </div>
        </div>
    </div>`;
}

function populateTemplates() {
    const grid = document.getElementById('templateGrid');
    if (!grid) return;
    grid.innerHTML = templateData.map(t => renderTemplateCard(t)).join('');
}

// Initialize on load
document.addEventListener('DOMContentLoaded', populateTemplates);

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { templateData, renderTemplateCard, populateTemplates };
}