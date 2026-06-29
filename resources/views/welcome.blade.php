<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <x-favicon />
  <title>Preowned Technical Services</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
  <header class="hero-section">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top site-navbar">
      <div class="container">
        <x-site-logo href="{{ route('welcome') }}" />
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('services') }}">Services</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('projects') }}">Projects</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="hero-bg hero-bg-1 is-active"></div>
    <div class="hero-bg hero-bg-2"></div>
    <div class="hero-bg hero-bg-3"></div>
    <div class="hero-overlay"></div>

    <div class="container hero-content">
      <div class="row min-vh-100 align-items-center">
        <div class="col-lg-8">
          <p class="hero-tagline mb-2">Premium Technical Services</p>
          <h1 class="hero-title mb-3">
            We are expert in
            <span class="rotating-line">
              <span id="rotating-word" class="rotating-word is-visible">Construction</span><span class="typing-cursor" aria-hidden="true"></span>
            </span>
          </h1>
          <p class="hero-subtitle mb-4">
            We deliver high-end technical execution with quality, speed, and reliability. Explore our work and discover how we transform ideas into dependable systems.
          </p>
          <div class="hero-highlights mb-4" aria-label="Company highlights">
            <div class="hero-highlight-item">
              <span class="hero-highlight-value">250+</span>
              <span class="hero-highlight-label">Projects Done</span>
            </div>
            <div class="hero-highlight-item">
              <span class="hero-highlight-value">15+</span>
              <span class="hero-highlight-label">Years of Experience</span>
            </div>
            <div class="hero-highlight-item">
              <span class="hero-highlight-value">98%</span>
              <span class="hero-highlight-label">Client Satisfaction</span>
            </div>
          </div>
          <a href="services.html" class="btn btn-hero">Explore More</a>
        </div>
      </div>
    </div>
  </header>

  <main>
    <section class="services-ticker-section" aria-label="Moving list of services">
      <div class="services-ticker-track">
        <div class="services-ticker-group">
          <span class="services-ticker-item"><i class="fa-solid fa-building"></i> Construction</span>
          <span class="services-ticker-item"><i class="fa-solid fa-screwdriver-wrench"></i> Maintenance</span>
          <span class="services-ticker-item"><i class="fa-solid fa-faucet-drip"></i> Plumbing</span>
          <span class="services-ticker-item"><i class="fa-solid fa-bolt"></i> Electrical</span>
          <span class="services-ticker-item"><i class="fa-solid fa-fan"></i> HVAC</span>
          <span class="services-ticker-item"><i class="fa-solid fa-ruler-combined"></i> Interior Fit-Out</span>
          <span class="services-ticker-item"><i class="fa-solid fa-shield-halved"></i> Facility Care</span>
        </div>
      </div>
    </section>

    <section id="about" class="section-pad about-us-section" aria-labelledby="about-us-heading">
      <span class="about-us-decor about-us-decor--1" aria-hidden="true"></span>
      <span class="about-us-decor about-us-decor--2" aria-hidden="true"></span>
      <div class="container position-relative about-us-inner">
        <div class="row justify-content-center text-center mb-4 mb-lg-5">
          <div class="col-lg-10 col-xl-9">
            <p class="section-kicker about-reveal"><span class="section-kicker-inner">Why Clients Choose Us</span></p>
            <h2 id="about-us-heading" class="section-title about-reveal about-reveal-delay-1">
              Modern technical service with a <span class="about-title-accent">simple process</span> and <span class="about-title-accent">reliable results.</span>
            </h2>
            <p class="section-lead mb-0 about-reveal about-reveal-delay-2">From first call to final handover, we keep things clear and user-friendly with fast response, transparent updates, and quality work you can trust.</p>
          </div>
        </div>
        <div class="row g-4 align-items-stretch">
          <div class="col-lg-6">
            <article class="about-us-card about-reveal about-reveal-delay-2 h-100">
              <h3 class="about-us-card-title">Who we are</h3>
              <p class="mb-3">TechServicePro is a technical services company focused on construction support, maintenance programs, and plumbing solutions for homes and businesses.</p>
              <p class="mb-0">Our crews work with precision and respect for your space, whether it is a new install, a retrofit, or ongoing care.</p>
            </article>
          </div>
          <div class="col-lg-6">
            <div class="about-us-visual about-reveal about-reveal-delay-3 h-100">
              <img src="assets/images/header.png" alt="Technical services and construction" class="about-us-img" width="800" height="520" loading="lazy">
              <div class="about-us-visual-badge">
                <span class="about-us-badge-value">15+</span>
                <span class="about-us-badge-label">Years combined experience</span>
              </div>
            </div>
          </div>
        </div>
        <div class="row g-4 mt-1 mt-lg-2">
          <div class="col-md-4">
            <article class="about-stat-card about-reveal about-reveal-delay-4 h-100">
              <span class="about-stat-num" aria-hidden="true">01</span>
              <h3 class="about-stat-title">Quality first</h3>
              <p class="mb-0">Rigorous checks at every stage so workmanship meets our standards and yours.</p>
            </article>
          </div>
          <div class="col-md-4">
            <article class="about-stat-card about-reveal about-reveal-delay-5 h-100">
              <span class="about-stat-num" aria-hidden="true">02</span>
              <h3 class="about-stat-title">Clear updates</h3>
              <p class="mb-0">You always know what is happening next, from scope to timeline to completion.</p>
            </article>
          </div>
          <div class="col-md-4">
            <article class="about-stat-card about-reveal about-reveal-delay-6 h-100">
              <span class="about-stat-num" aria-hidden="true">03</span>
              <h3 class="about-stat-title">Long-term support</h3>
              <p class="mb-0">We stay available for maintenance and follow-up so systems keep performing.</p>
            </article>
          </div>
        </div>
      </div>
    </section>

    <section id="work" class="section-pad our-work-section" aria-labelledby="our-work-heading">
      <div class="container">
        <div class="row justify-content-center text-center mb-4 mb-lg-5">
          <div class="col-lg-10 col-xl-9">
            <p class="section-kicker"><span class="section-kicker-inner">What We Can Do</span></p>
            <h2 id="our-work-heading" class="section-title">What We Can Do with modern technical execution.</h2>
            <p class="section-lead mb-0">Explore selected work where design precision, technical quality, and dependable timelines come together.</p>
          </div>
        </div>

        <div class="work-carousel" id="workCarousel">
          <article class="work-card" aria-label="Construction service">
            <img src="https://img.freepik.com/free-photo/construction-high-rise-building-sunset_23-2152006091.jpg" alt="Construction service" class="work-card-img" loading="lazy">
            <div class="work-card-overlay">
              <h3>Construction</h3>
              <p>Civil and structural works delivered with strict quality and safety standards.</p>
            </div>
          </article>

          <article class="work-card" aria-label="Maintenance service">
            <img src="https://www.victorinsurance.com/content/dam/victor/victor2/imagery/article-card-deck-500x500/US-builder-risk-card-deck-renovation-500x500px.png" alt="Maintenance service" class="work-card-img" loading="lazy">
            <div class="work-card-overlay">
              <h3>Maintenance</h3>
              <p>Preventive and corrective maintenance to keep facilities reliable year-round.</p>
            </div>
          </article>

          <article class="work-card" aria-label="Plumbing service">
            <img src="https://plus.unsplash.com/premium_photo-1663047170515-66632d2a374d?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Plumbing service" class="work-card-img" loading="lazy">
            <div class="work-card-overlay">
              <h3>Plumbing</h3>
              <p>Leak repairs, line upgrades, and complete plumbing system installations.</p>
            </div>
          </article>

          <article class="work-card" aria-label="Electrical service">
            <img src="https://images.unsplash.com/photo-1544724569-5f546fd6f2b5?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8ZWxlY3RyaWNhbCUyMHdvcmt8ZW58MHwxfDB8fHww" alt="Electrical service" class="work-card-img" loading="lazy">
            <div class="work-card-overlay">
              <h3>Electrical</h3>
              <p>Wiring, panels, lighting systems, and safe power distribution solutions.</p>
            </div>
          </article>

          <article class="work-card" aria-label="Interior fit-out service">
            <img src="https://images.unsplash.com/photo-1617103996702-96ff29b1c467?auto=format&fit=crop&w=1600&q=80" alt="Interior fit-out service" class="work-card-img" loading="lazy">
            <div class="work-card-overlay">
              <h3>Interior Fit-Out</h3>
              <p>Modern interior execution including gypsum, partition, and finishing works.</p>
            </div>
          </article>

          <article class="work-card" aria-label="Air conditioning service">
            <img src="https://image.made-in-china.com/202f0j00zKjMlbfICiqu/OEM-Manufacture-T3-T1-12000-18000-BTU-220-110V-60-50Hz-on-off-Inverter-Cool-Only-Heat-and-Cool-Cheap-Wall-Mounted-Split-AC-Air-Conditioning.webp" alt="Air conditioning service" class="work-card-img" loading="lazy">
            <div class="work-card-overlay">
              <h3>Air Conditioning</h3>
              <p>HVAC installation, servicing, and performance optimization for all spaces.</p>
            </div>
          </article>

          <article class="work-card" aria-label="Painting service">
            <img src="https://images.unsplash.com/photo-1562259949-e8e7689d7828?auto=format&fit=crop&w=1600&q=80" alt="Painting service" class="work-card-img" loading="lazy">
            <div class="work-card-overlay">
              <h3>Painting</h3>
              <p>Interior and exterior painting with premium surface preparation and finishes.</p>
            </div>
          </article>
        </div>
      </div>
    </section>

    <section id="projects" class="section-pad projects-section" aria-labelledby="projects-heading">
      <div class="container">
        <div class="row justify-content-center text-center mb-4 mb-lg-5">
          <div class="col-lg-10 col-xl-9">
            <p class="section-kicker"><span class="section-kicker-inner">Our Projects</span></p>
            <h2 id="projects-heading" class="section-title">Landmark technical projects delivered with precision.</h2>
            <p class="section-lead mb-0">A curated portfolio of premium projects where our teams executed complex technical work with modern standards and dependable outcomes.</p>
          </div>
        </div>

        <div class="row g-4 projects-grid">
          <div class="col-6 col-md-3">
            <article class="project-card">
              <img src="https://thumbs.dreamstime.com/b/fruit-vegetables-market-dubai-196483346.jpg" alt="Downtown mixed-use development project" class="project-card-img" loading="lazy">
              <div class="project-card-overlay">
                <h3>Vegetable & Fruit Market</h3>
                <p><i class="fa-solid fa-location-dot"></i> Dubai, UAE</p>
                <p><i class="fa-solid fa-layer-group"></i> Mixed-Use Build</p>
              </div>
            </article>
          </div>

          <div class="col-6 col-md-3">
            <article class="project-card">
              <img src="https://mediaoffice.ae/-/media/2021/jan/17-01/05/1017012021-hisham-1.jpg?h=3712&w=5568&hash=68360692C0A5ACEDA2D4575E2953C2D4" alt="Industrial service hub project" class="project-card-img" loading="lazy">
              <div class="project-card-overlay">
                <h3>Al lisali Marriage </h3>
                <p><i class="fa-solid fa-location-dot"></i> Abu Dhabi, UAE</p>
                <p><i class="fa-solid fa-screwdriver-wrench"></i> Marina Build</p>
              </div>
            </article>
          </div>

          <div class="col-6 col-md-3">
            <article class="project-card">
              <img src="https://images.unsplash.com/photo-1621905252439-4e8f4a16f5f6?auto=format&fit=crop&w=1400&q=80" alt="Hospital utility retrofit project" class="project-card-img" loading="lazy">
              <div class="project-card-overlay">
                <h3>CityCare Retrofit</h3>
                <p><i class="fa-solid fa-location-dot"></i> Sharjah, UAE</p>
                <p><i class="fa-solid fa-faucet-drip"></i> Utility Upgrade</p>
              </div>
            </article>
          </div>

          <div class="col-6 col-md-3">
            <article class="project-card">
              <img src="https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=1400&q=80" alt="Residential premium villas project" class="project-card-img" loading="lazy">
              <div class="project-card-overlay">
                <h3>Premium Villas</h3>
                <p><i class="fa-solid fa-location-dot"></i> Jumeirah, UAE</p>
                <p><i class="fa-solid fa-house"></i> Residential Delivery</p>
              </div>
            </article>
          </div>

          <div class="col-12 col-md-6">
            <article class="project-card project-card-wide">
              <img src="https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?auto=format&fit=crop&w=1800&q=80" alt="Logistics terminal systems project" class="project-card-img" loading="lazy">
              <div class="project-card-overlay">
                <h3>Logistics Terminal</h3>
                <p><i class="fa-solid fa-location-dot"></i> Jebel Ali, UAE</p>
                <p><i class="fa-solid fa-truck-ramp-box"></i> Systems Integration & Operations</p>
              </div>
            </article>
          </div>

          <div class="col-12 col-md-6">
            <article class="project-card project-card-wide">
              <img src="https://images.unsplash.com/photo-1581094271901-8022df4466f9?auto=format&fit=crop&w=1800&q=80" alt="Smart campus infrastructure project" class="project-card-img" loading="lazy">
              <div class="project-card-overlay">
                <h3>Smart Campus</h3>
                <p><i class="fa-solid fa-location-dot"></i> Al Ain, UAE</p>
                <p><i class="fa-solid fa-microchip"></i> Intelligent Infrastructure</p>
              </div>
            </article>
          </div>
        </div>
        <div class="text-center mt-4 mt-lg-5">
          <a href="projects.html" class="btn btn-hero projects-cta-btn">Explore All Projects <i class="fa-solid fa-arrow-right-long ms-2"></i></a>
        </div>
      </div>
    </section>

    <section id="partners" class="section-pad partners-section d-none" aria-labelledby="partners-heading">
      <div class="container">
        <div class="row justify-content-center text-center mb-4 mb-lg-5">
          <div class="col-lg-10 col-xl-9">
            <p class="section-kicker"><span class="section-kicker-inner">Companies We Work With</span></p>
            <h2 id="partners-heading" class="section-title">Trusted by leading brands and enterprise teams.</h2>
            <p class="section-lead mb-0">A modern partnership network built on quality execution, transparency, and long-term technical support.</p>
          </div>
        </div>

        <div class="partners-slider-viewport" id="partnersViewport" aria-label="Company logos moving left one by one">
          <div class="partners-track" id="partnersTrack">
            <article class="partner-logo-card"><img src="https://cdn.simpleicons.org/microsoft/080059" alt="Microsoft logo" loading="lazy"></article>
            <article class="partner-logo-card"><img src="https://cdn.simpleicons.org/samsung/080059" alt="Samsung logo" loading="lazy"></article>
            <article class="partner-logo-card"><img src="https://cdn.simpleicons.org/bosch/080059" alt="Bosch logo" loading="lazy"></article>
            <article class="partner-logo-card"><img src="https://cdn.simpleicons.org/siemens/080059" alt="Siemens logo" loading="lazy"></article>
            <article class="partner-logo-card"><img src="https://cdn.simpleicons.org/toyota/080059" alt="Toyota logo" loading="lazy"></article>
            <article class="partner-logo-card"><img src="https://cdn.simpleicons.org/shell/080059" alt="Shell logo" loading="lazy"></article>
            <article class="partner-logo-card"><img src="https://cdn.simpleicons.org/pepsi/080059" alt="Pepsi logo" loading="lazy"></article>
            <article class="partner-logo-card"><img src="https://cdn.simpleicons.org/adidas/080059" alt="Adidas logo" loading="lazy"></article>
          </div>
        </div>
      </div>
    </section>
  </main>

  @include('components.footer')

  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
