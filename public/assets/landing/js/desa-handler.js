/**
 * Desa Handler Module
 * Filters and renders village data cards
 */

const desaData = [
    { name: 'Balige', kecamatan: 'Balige', image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/114d932fb-bc55-449b-8780-188260bc6a0f.png', population: '8.500', households: '2.100', filter: 'balige' },
    { name: 'Laguboti', kecamatan: 'Laguboti', image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/1cf949f20-5c06-4589-960d-4e1ec32e4d59.png', population: '7.200', households: '1.800', filter: 'laguboti' },
    { name: 'Habinsaran', kecamatan: 'Habinsaran', image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/102798b95-e8ef-4f1b-bddb-47eadf2932f8.png', population: '6.800', households: '1.650', filter: 'habinsaran' },
    { name: 'Borbor', kecamatan: 'Borbor', image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/1bd00f053-8fcf-4293-a50c-d9e039713cef.png', population: '5.900', households: '1.450', filter: 'borbor' },
    { name: 'Nassau', kecamatan: 'Nassau', image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/1fab1705d-d822-416d-a047-2e0ac4ff4321.png', population: '4.500', households: '1.100', filter: 'nassau' },
    { name: 'Silaen', kecamatan: 'Silaen', image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/12e40dab9-68a1-4cd2-a51a-70b7107811f2.png', population: '7.800', households: '1.950', filter: 'silaen' },
    { name: 'Siantar Narumonda', kecamatan: 'Siantar Narumonda', image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/1a53cf493-07d5-4124-af11-5067b8b29262.png', population: '6.200', households: '1.550', filter: 'siantar' },
    { name: 'Pintu Pohan Meranti', kecamatan: 'Balige', image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/114d932fb-bc55-449b-8780-188260bc6a0f.png', population: '5.400', households: '1.300', filter: 'balige' },
    { name: 'Porsea', kecamatan: 'Laguboti', image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/1cf949f20-5c06-4589-960d-4e1ec32e4d59.png', population: '8.100', households: '2.000', filter: 'laguboti' },
    { name: 'Ajibata', kecamatan: 'Habinsaran', image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/102798b95-e8ef-4f1b-bddb-47eadf2932f8.png', population: '4.800', households: '1.200', filter: 'habinsaran' },
    { name: 'Tampahan', kecamatan: 'Borbor', image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/1bd00f053-8fcf-4293-a50c-d9e039713cef.png', population: '5.600', households: '1.380', filter: 'borbor' },
    { name: 'Uluan', kecamatan: 'Nassau', image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/1fab1705d-d822-416d-a047-2e0ac4ff4321.png', population: '3.900', households: '950', filter: 'nassau' },
    { name: 'Sigumpar', kecamatan: 'Silaen', image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/12e40dab9-68a1-4cd2-a51a-70b7107811f2.png', population: '6.500', households: '1.600', filter: 'silaen' },
    { name: 'Lumban Julu', kecamatan: 'Siantar Narumonda', image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/1a53cf493-07d5-4124-af11-5067b8b29262.png', population: '5.100', households: '1.250', filter: 'siantar' },
    { name: 'Habinsaran Baru', kecamatan: 'Habinsaran', image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/102798b95-e8ef-4f1b-bddb-47eadf2932f8.png', population: '4.200', households: '1.050', filter: 'habinsaran' },
    { name: 'Bonatua Lunasi', kecamatan: 'Balige', image: 'https://image.qwenlm.ai/public_source/16a5de01-2ebd-49a2-adc6-75a9b8b3e1d7/114d932fb-bc55-449b-8780-188260bc6a0f.png', population: '7.000', households: '1.750', filter: 'balige' },
];

function renderDesaCard(desa) {
    return `<div class="desa-card">
        <img src="${desa.image}" alt="${desa.name}" class="desa-card-image">
        <div class="desa-card-body">
            <h3>${desa.name}</h3>
            <p>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                Kec. ${desa.kecamatan}
            </p>
        </div>
        <div class="desa-card-stats">
            <div class="desa-stat"><div class="desa-stat-number">${desa.population}</div><div class="desa-stat-label">Penduduk</div></div>
            <div class="desa-stat"><div class="desa-stat-number">${desa.households}</div><div class="desa-stat-label">KK</div></div>
        </div>
    </div>`;
}

function populateDesa(filter = 'all') {
    const grid = document.getElementById('desaGrid');
    if (!grid) return;
    
    const filtered = filter === 'all' ? desaData : desaData.filter(d => d.filter === filter);
    grid.innerHTML = filtered.map(d => renderDesaCard(d)).join('');
}

function filterDesa(filter, btn) {
    // Update active button state
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn?.classList.add('active');
    
    // Render filtered data
    populateDesa(filter);
}

// Initialize on load
document.addEventListener('DOMContentLoaded', () => populateDesa());

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { desaData, renderDesaCard, populateDesa, filterDesa };
}