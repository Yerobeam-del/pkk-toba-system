/**
 * News Handler Module
 * Renders news cards, handles modal, manages news data
 */

// News data (static - replace with API call later)
const newsData = [
    { 
        image: '/assets/landing/images/berita/berita-1.jpg', 
        date: '02 April 2026', 
        category: 'UP2K', 
        title: 'Pembinaan Pokja II TP PKK kab.Toba ke Desa binaan UP2K PKK Desa Sigaol Timur', 
        excerpt: 'Kegiatan pembinaan ke Desa binaan UP2K TP PKK Sigaol Timur dilaksanakan untuk meningkatkan kemampuan TP PKK Desa Sigaol Timur dalam memodifikasi tenun ulos menjadi berbagai produk fashion seperti outer, tas dan dompet dari bahan ulos sebagai produk unggulan Desa Sigaol Timur.', 
        content: 'Kegiatan pembinaan ke Desa binaan UP2K TP PKK Sigaol Timur dilaksanakan untuk meningkatkan kemampuan TP PKK Desa Sigaol Timur dalam memodifikasi tenun ulos menjadi berbagai produk fashion seperti outer, tas dan dompet dari bahan ulos sebagai produk unggulan Desa Sigaol Timur.' 
    },
    { 
        image: '/assets/landing/images/berita/berita-2.jpg', 
        date: '26 Maret 2026', 
        category: 'Aku Hatinya PKK', 
        title: 'Pembinaan Desa Binaan Kabupaten Toba Tahun 2026', 
        excerpt: 'Melaksanakan kegiatan Pembinaan kategori Aku Hatinya PKK TP PKK Kabupaten Toba ke Desa Tangga Batu Barat, Kecamatan Tampahan', 
        content: 'Melaksanakan kegiatan Pembinaan kategori Aku Hatinya PKK TP PKK Kabupaten Toba ke Desa Tangga Batu Barat, Kecamatan Tampahan' 
    },
    { 
        image: '/assets/landing/images/berita/berita-3.jpg', 
        date: '12 Maret 2026', 
        category: 'Rapat Konsultasi', 
        title: 'Rapat Konsultasi Pokja III', 
        excerpt: 'Mengikuti Pelaksanaan Rapat Konsultasi Pokja III yang membahsa ketahanan pangan dan seluruh program pokja III', 
        content: 'Mengikuti Pelaksanaan Rapat Konsultasi Pokja III yang membahsa ketahanan pangan dan seluruh program pokja III' 
    },
    { 
        image: '/assets/landing/images/berita/berita-4.jpg', 
        date: '06 Maret 2026', 
        category: 'Kunjungan', 
        title: 'Kunjungan TP. PKK Kab. Toba Ke Tempat Pemilahan Sampah', 
        excerpt: 'Anggota TP. PKK Kab. Toba melaksanakan kunjungan ke tempat pemilahan sampah d Desa Pintu Pohan Kecamatan Pintu Pohan Meranti', 
        content: 'Anggota TP. PKK Kab. Toba melaksanakan kunjungan ke tempat pemilahan sampah d Desa Pintu Pohan Kecamatan Pintu Pohan Meranti' 
    },
    { 
        image: '/assets/landing/images/berita/berita-5.jpg', 
        date: '05 Maret 2026', 
        category: 'Pola Asuh Anak & Remaja (PAAR)', 
        title: 'Pembinaan Awal Desa Binaan Kategori Pola Asuh Anak dan Remaja (PAAR) tahun 2026', 
        excerpt: 'Melaksanakan pembinaan awal Desa Binaan kategori Pola Asuh Anak dan Remaja (PAAR) tahun 2026 di desa Jonggi Manulus, Kec. Parmaksian, Kab. Toba.', 
        content: 'Melaksanakan pembinaan awal Desa Binaan kategori Pola Asuh Anak dan Remaja (PAAR) tahun 2026 di desa Jonggi Manulus, Kec. Parmaksian, Kab. Toba. Kegiatan ini dihadiri oleh Ibu Staf Ahli TP PKK Toba, Ketua I dan Ketua Pokja I TP PKK Toba, dan Camat Parmaksian serta Ketua TP PKK Kecamatan dan juga Ketua TP PKK Desa. Fokus Pembinaan tahun 2026 secara khusus dititikberatkan pada program PAAREDI (Pola Asuh Anak dan Remaja di Era Digital), yang bertujuan membentengi keluarga dari dampak negatif teknologi seperti judi online, perundungan siber, dan konten tidak layak. Selain literasi digital, pencegahan stunting melalui gerakan pencegahan perkawinan anak (CEPAK) menjadi pilar utama dalam arahan yang disampaikan oleh TP PKK Toba.' 
    }
];

function renderNewsCard(news, index) {
    return `<div class="news-card" onclick="openNewsModal(${index})">
        <img src="${news.image}" alt="${news.title}" class="news-card-image">
        <div class="news-card-body">
            <div class="news-card-date">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                ${news.date}
            </div>
            <span class="news-card-category">${news.category}</span>
            <h3 class="news-card-title">${news.title}</h3>
            <p class="news-card-excerpt">${news.excerpt}</p>
            <span class="news-card-link">Baca Selengkapnya
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </span>
        </div>
    </div>`;
}

function populateNewsHome() {
    const grid = document.getElementById('newsHomeGrid');
    if (!grid) return;
    grid.innerHTML = newsData.slice(0, 3).map((n, i) => renderNewsCard(n, i)).join('');
}

function populateNewsFull() {
    const grid = document.getElementById('newsFullGrid');
    if (!grid) return;
    grid.innerHTML = newsData.map((n, i) => renderNewsCard(n, i)).join('');
}

function openNewsModal(index) {
    const news = newsData[index];
    if (!news) return;
    
    document.getElementById('newsModalImage').src = news.image;
    document.getElementById('newsModalDate').innerHTML = `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
        </svg> ${news.date}`;
    document.getElementById('newsModalCategory').textContent = news.category;
    document.getElementById('newsModalTitle').textContent = news.title;
    document.getElementById('newsModalContent').textContent = news.content;
    document.getElementById('newsModal')?.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeNewsModal() {
    document.getElementById('newsModal')?.classList.remove('active');
    document.body.style.overflow = '';
}

// Event listeners for modal
document.getElementById('newsModal')?.addEventListener('click', function(e) { 
    if (e.target === this) closeNewsModal(); 
});
document.addEventListener('keydown', (e) => { 
    if (e.key === 'Escape') closeNewsModal(); 
});

// Initialize on load
document.addEventListener('DOMContentLoaded', populateNewsHome);

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { newsData, renderNewsCard, populateNewsHome, populateNewsFull, openNewsModal, closeNewsModal };
}