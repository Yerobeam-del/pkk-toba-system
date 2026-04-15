/**
 * Navigation & SPA Router
 * Handles page switching, menu toggle, floating button
 */

function navigateTo(pageId) {
    // Hide all pages, show target
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    const targetPage = document.getElementById('page-' + pageId);
    if (targetPage) targetPage.classList.add('active');
    
    // Update nav active state
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active-link');
        if (link.getAttribute('data-page') === pageId) link.classList.add('active-link');
    });
    
    // Close mobile menu
    document.getElementById('navLinks')?.classList.remove('active');
    document.getElementById('hamburger')?.classList.remove('active');
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    // Load dynamic content if needed
    if (pageId === 'berita' && typeof populateNewsFull === 'function') populateNewsFull();
    if (pageId === 'desa' && typeof populateDesa === 'function') populateDesa();
    if (pageId === 'sk' && typeof populateSKTable === 'function') populateSKTable();
    if (pageId === 'template' && typeof populateTemplates === 'function') populateTemplates();
    
    closeFloatingMenu();
}

function toggleMenu() {
    document.getElementById('navLinks')?.classList.toggle('active');
    document.getElementById('hamburger')?.classList.toggle('active');
}

function toggleFloatingMenu() {
    const menu = document.getElementById('floatingMenu');
    const trigger = document.getElementById('floatingTrigger');
    menu?.classList.toggle('open');
    trigger?.classList.toggle('open');
}

function closeFloatingMenu() {
    document.getElementById('floatingMenu')?.classList.remove('open');
    document.getElementById('floatingTrigger')?.classList.remove('open');
}

// Close floating menu when clicking outside
document.addEventListener('click', function(e) {
    const floatingBtn = document.getElementById('floatingAppBtn');
    if (floatingBtn && !floatingBtn.contains(e.target)) closeFloatingMenu();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { navigateTo, toggleMenu, toggleFloatingMenu, closeFloatingMenu };
}