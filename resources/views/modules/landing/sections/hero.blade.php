<section class="hero">
    <div class="hero-bg-slider" id="heroBgSlider">
        <div class="hero-bg-slide active" style="background-image: url('{{ asset('assets/landing/images/Background/Background_1.jpg') }}')"></div>
        <div class="hero-bg-slide" style="background-image: url('{{ asset('assets/landing/images/Background/Background_2.jpg') }}')"></div>
        <div class="hero-bg-slide" style="background-image: url('{{ asset('assets/landing/images/Background/Background_3.jpg') }}')"></div>
        <div class="hero-bg-slide" style="background-image: url('{{ asset('assets/landing/images/Background/Background_5.jpg') }}')"></div>
    </div>
    <div class="hero-bg-overlay"></div>
    <div class="hero-particles" id="particles"></div>
    <div class="hero-content">
        <div class="hero-badge">
            <div class="hero-badge-dot"></div>
            <span>Portal Resmi Digital</span>
        </div>
        <div class="hero-logo-container">
            <img src="{{ asset('assets/landing/images/PKK-Logo.png') }}" alt="PKK Logo" class="hero-logo">
        </div>
        <h1>Selamat Datang di Portal<br><span class="highlight">PKK Kabupaten Toba</span></h1>
        <p class="hero-subtitle">Melayani masyarakat Kabupaten Toba melalui transformasi digital untuk pemberdayaan keluarga dan kesejahteraan masyarakat yang lebih baik.</p>
        <a onclick="document.getElementById('quickAccess').scrollIntoView({behavior:'smooth'})" class="hero-cta">
            Jelajahi Layanan
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17l9.2-9.2M17 17V7.8H7.8"/></svg>
        </a>
    </div>
    <div class="hero-slider-indicators" id="sliderIndicators">
        <div class="slider-dot active" data-index="0"></div>
        <div class="slider-dot" data-index="1"></div>
        <div class="slider-dot" data-index="2"></div>
        <div class="slider-dot" data-index="3"></div>
    </div>
</section>