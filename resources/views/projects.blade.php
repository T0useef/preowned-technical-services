<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <x-favicon />
  <title>Our Projects | Preowned Technical Services</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
  <header class="projects-page-header">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top site-navbar">
      <div class="container">
        <x-site-logo />
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#projectsNav" aria-controls="projectsNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="projectsNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="{{ route('welcome') }}">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('services') }}">Services</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="projects-page-hero-bg"></div>
    <div class="projects-page-hero-overlay"></div>
    <div class="container projects-page-hero-content">
      <div class="row min-vh-70 align-items-center">
        <div class="col-lg-9 col-xl-8">
          <p class="hero-tagline mb-2">Our Projects</p>
          <h1 class="projects-page-title mb-3">A modern portfolio of technical projects delivered with precision.</h1>
          <p class="projects-page-subtitle mb-0">Browse our recent project deliveries across construction, maintenance, retrofit, and systems integration.</p>
        </div>
      </div>
    </div>
  </header>

  <main>
    <section class="section-pad projects-list-section" aria-labelledby="projects-list-heading">
      <div class="container">
        <div class="row justify-content-center text-center mb-4 mb-lg-5">
          <div class="col-lg-10 col-xl-9">
            <p class="section-kicker"><span class="section-kicker-inner">Portfolio</span></p>
            <h2 id="projects-list-heading" class="section-title">All listed projects we have delivered.</h2>
            <p class="section-lead mb-0">Each project reflects our focus on quality workmanship, safety compliance, and reliable outcomes.</p>
          </div>
        </div>

        <div class="row g-4 projects-grid projects-list-grid">
          <div class="col-12 col-sm-6 col-lg-4">
            <article class="project-card">
              <img src="{{asset('assets/images/vegetable-market.jpg')}}" alt="Vegetable and fruit market project in Dubai" class="project-card-img" loading="lazy">
              <div class="project-card-overlay">
                <h3>Vegetable & Fruit Market</h3>
                <p><i class="fa-solid fa-location-dot"></i> Dubai, UAE</p>
                <p><i class="fa-solid fa-layer-group"></i> Mixed-Use Build</p>
              </div>
            </article>
          </div>

          <div class="col-12 col-sm-6 col-lg-4">
            <article class="project-card">
              <img src="{{asset('assets/images/dubai-municipality-office.jpg')}}" alt="Dubai Municipality Office project in Dubai" class="project-card-img" loading="lazy">
              <div class="project-card-overlay">
                <h3>Dubai Municipality Office</h3>
                <p><i class="fa-solid fa-location-dot"></i> Dubai, UAE</p>
                <p><i class="fa-solid fa-building"></i> Maintenance of Offices</p>
              </div>
            </article>
          </div>

          <div class="col-12 col-sm-6 col-lg-4">
            <article class="project-card">
              <img src="{{asset('assets/images/office-interior-fitout.jpg')}}" alt="Office Interior Fit-Out & Maintenance project" class="project-card-img" loading="lazy">
              <div class="project-card-overlay">
                <h3>Office Interior Fit-Out & Maintenance</h3>
                <p><i class="fa-solid fa-location-dot"></i> Dubai, UAE</p>
                <p><i class="fa-solid fa-screwdriver-wrench"></i> Office Interior Fit-Out & Maintenance</p>
              </div>
            </article>
          </div>

          <div class="col-12 col-sm-6 col-lg-4">
            <article class="project-card">
              <img src="{{asset('assets/images/al-mamzar-park.jpg')}}" alt="Al Mamzar Park Renovation project" class="project-card-img" loading="lazy">
              <div class="project-card-overlay">
                <h3>Al Mamzar Park Renovation</h3>
                <p><i class="fa-solid fa-location-dot"></i> Sharjah, UAE</p>
                <p><i class="fa-solid fa-parking"></i> Al Mamzar Park Renovation</p>
              </div>
            </article>
          </div>

          <div class="col-12 col-sm-6 col-lg-4">
            <article class="project-card">
              <img src="{{asset('assets/images/al-lisaili-hall.jpg')}}" alt="Al Lisaili Hall project" class="project-card-img" loading="lazy">
              <div class="project-card-overlay">
                <h3>Al Lisaili Hall</h3>
                <p><i class="fa-solid fa-location-dot"></i> Dubai, UAE</p>
                <p><i class="fa-solid fa-shield-halved"></i> Al Lisaili Hall Maintenance</p>
              </div>
            </article>
          </div>

          <div class="col-12 col-sm-6 col-lg-4">
            <article class="project-card">
              <img src="{{asset('assets/images/jabel-ali-jogging.jpg')}}" alt="Jabel Ali Jogging Track project" class="project-card-img" loading="lazy">
              <div class="project-card-overlay">
                <h3>Jabel Ali Jogging Track</h3>
                <p><i class="fa-solid fa-location-dot"></i> Dubai, UAE</p>
                <p><i class="fa-solid fa-person-running"></i> Jabel Ali Jogging Track</p>
              </div>
            </article>
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
