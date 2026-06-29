<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <x-favicon />
  <title>About Us | Preowned Technical Services</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
  <header class="about-page-header">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top site-navbar">
      <div class="container">
        <x-site-logo />
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#aboutNav" aria-controls="aboutNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="aboutNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="{{ route('welcome') }}">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('services') }}">Services</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('projects') }}">Projects</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="about-page-hero-bg"></div>
    <div class="about-page-hero-overlay"></div>
    <div class="container about-page-hero-content">
      <div class="row min-vh-70 align-items-center">
        <div class="col-lg-9 col-xl-8">
          <p class="hero-tagline mb-2">About TechServicePro</p>
          <h1 class="about-page-title mb-3">A modern technical services team focused on quality, speed, and long-term trust.</h1>
          <p class="about-page-subtitle mb-0">We deliver construction, maintenance, plumbing, electrical, and interior services through clear communication and premium workmanship.</p>
        </div>
      </div>
    </div>
  </header>

  <main>
    <section class="section-pad ceo-message-section" aria-labelledby="ceo-message-heading">
      <div class="container">
        <div class="row g-4 g-lg-5 align-items-center">
          <div class="col-lg-5">
            <div class="ceo-frame-wrap">
              <div class="ceo-frame-glow" aria-hidden="true"></div>
              <figure class="ceo-frame">
                <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=900&q=80" alt="CEO portrait" class="ceo-image" loading="lazy">
              </figure>
            </div>
          </div>
          <div class="col-lg-7">
            <article class="ceo-message-card">
              <p class="section-kicker mb-2"><span class="section-kicker-inner">Message From Our CEO</span></p>
              <h2 id="ceo-message-heading">Building trust through quality technical execution.</h2>
              <p>At TechServicePro, our mission is simple: deliver high-standard technical services with clear communication, dependable timelines, and workmanship our clients can rely on.</p>
              <p>Every project we accept reflects our commitment to professionalism, safety, and long-term value. We are proud to serve homes, businesses, and institutions that demand modern service excellence.</p>
              <p class="mb-0 ceo-signature">— Muhammad Touseef, Chief Executive Officer</p>
            </article>
          </div>
        </div>
      </div>
    </section>

    <section class="section-pad about-page-intro-section">
      <div class="container">
        <div class="row g-4 g-lg-5 align-items-stretch">
          <div class="col-lg-6">
            <article class="about-page-panel h-100">
              <h2>Who We Are</h2>
              <p>TechServicePro is a user-focused technical services provider helping residential and commercial clients complete projects efficiently and safely.</p>
              <p class="mb-0">Our multidisciplinary teams manage planning, execution, and ongoing support so clients get one reliable partner for their technical needs.</p>
            </article>
          </div>
          <div class="col-lg-6">
            <article class="about-page-panel h-100">
              <h2>How We Work</h2>
              <ul class="about-page-list">
                <li><i class="fa-solid fa-circle-check"></i> Fast inspection and clear scope definition</li>
                <li><i class="fa-solid fa-circle-check"></i> Transparent pricing and timeline planning</li>
                <li><i class="fa-solid fa-circle-check"></i> Quality-controlled execution by expert teams</li>
                <li><i class="fa-solid fa-circle-check"></i> Post-completion support and maintenance</li>
              </ul>
            </article>
          </div>
        </div>
      </div>
    </section>

    <section class="section-pad about-page-metrics-section">
      <span class="metrics-orb" aria-hidden="true"></span>
      <span class="metrics-ring" aria-hidden="true"></span>
      <span class="metrics-ring metrics-ring--alt" aria-hidden="true"></span>
      <div class="container position-relative">
        <div class="row g-4">
          <div class="col-6 col-lg-3">
            <div class="about-metric-card">
              <span class="about-metric-value">250+</span>
              <span class="about-metric-label">Projects Delivered</span>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="about-metric-card">
              <span class="about-metric-value">15+</span>
              <span class="about-metric-label">Years Experience</span>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="about-metric-card">
              <span class="about-metric-value">98%</span>
              <span class="about-metric-label">Client Satisfaction</span>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="about-metric-card">
              <span class="about-metric-value">24/7</span>
              <span class="about-metric-label">Support Response</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section-pad testimonials-section" aria-labelledby="testimonials-heading">
      <div class="container">
        <div class="row g-4 g-lg-5 align-items-start">
          <div class="col-lg-5">
            <article class="testimonial-intro-card ux-reveal">
              <p class="section-kicker mb-2"><span class="section-kicker-inner">Client Reviews</span></p>
              <h2 id="testimonials-heading">Trusted by clients who value professional technical delivery.</h2>
              <p class="mb-3">Our company story is built on repeat business and long-term relationships. Clients choose us for transparent communication, consistent quality, and a process that feels smooth from planning to handover.</p>

              <div class="testimonial-values">
                <span><i class="fa-solid fa-shield-heart"></i> Reliability</span>
                <span><i class="fa-solid fa-eye"></i> Transparency</span>
                <span><i class="fa-solid fa-gem"></i> Quality</span>
                <span><i class="fa-solid fa-bolt"></i> Fast Response</span>
              </div>
            </article>
          </div>
          

          <div class="col-lg-7">
            <div class="feedback-grid ux-reveal ux-reveal-delay-1" aria-label="Client feedback cards">
              <article class="feedback-card">
                <i class="fa-solid fa-quote-left feedback-quote" aria-hidden="true"></i>
                <p>“Their team handled our renovation and technical installations with impressive discipline. Every stage was clearly communicated, and final quality exceeded expectations.”</p>
                <div class="feedback-meta">
                  <strong>Ahmad Raza</strong>
                  <span>Operations Manager, UrbanAxis</span>
                </div>
              </article>
              <article class="feedback-card">
                <i class="fa-solid fa-quote-left feedback-quote" aria-hidden="true"></i>
                <p>“Fast response and professional maintenance support. We now rely on them for all recurring technical works.”</p>
                <div class="feedback-meta">
                  <strong>Maria Khan</strong>
                  <span>Facility Lead, Nova Residences</span>
                </div>
              </article>
              <article class="feedback-card">
                <i class="fa-solid fa-quote-left feedback-quote" aria-hidden="true"></i>
                <p>“The team stayed transparent from quotation to handover. Timeline and budget discipline were excellent throughout.”</p>
                <div class="feedback-meta">
                  <strong>Hassan Ali</strong>
                  <span>Project Coordinator, PrimeView Towers</span>
                </div>
              </article>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section-pad about-page-cta-section">
      <div class="container">
        <div class="about-page-cta-box text-center">
          <h2>Ready to work with a modern technical team?</h2>
          <p class="mb-4">Let us turn your requirements into reliable execution with a clear and professional process.</p>
          <a href="{{ route('contact') }}" class="btn btn-hero">Let's Connect</a>
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
