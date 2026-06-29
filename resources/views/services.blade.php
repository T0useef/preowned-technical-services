<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <x-favicon />
  <title>Our Services | Preowned Technical Services</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
  <header class="services-page-header">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top site-navbar">
      <div class="container">
        <x-site-logo />
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#servicesNav" aria-controls="servicesNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="servicesNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="{{ route('welcome') }}">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('projects') }}">Projects</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="services-page-hero-bg"></div>
    <div class="services-page-hero-overlay"></div>
    <div class="container services-page-hero-content">
      <div class="row min-vh-70 align-items-center">
        <div class="col-lg-9 col-xl-8">
          <p class="hero-tagline mb-2">Our Services</p>
          <h1 class="services-page-title mb-3">Complete technical services for residential, commercial, and industrial projects.</h1>
          <p class="services-page-subtitle mb-0">From new construction to ongoing maintenance, our teams deliver reliable service with professional execution and clear communication.</p>
        </div>
      </div>
    </div>
  </header>

  <main>
    <section class="section-pad services-list-section" aria-labelledby="services-list-heading">
      <div class="container">
        <div class="row justify-content-center text-center mb-4 mb-lg-5">
          <div class="col-lg-10 col-xl-9">
            <p class="section-kicker"><span class="section-kicker-inner">What We Offer</span></p>
            <h2 id="services-list-heading" class="section-title">All technical services we provide.</h2>
            <p class="section-lead mb-0">One partner for planning, execution, repairs, upgrades, and long-term support.</p>
          </div>
        </div>

        <div class="row g-4">
          <div class="col-sm-6 col-lg-4">
            <article class="service-list-card h-100">
              <span class="service-list-icon"><i class="fa-solid fa-building"></i></span>
              <h3>Construction</h3>
              <p class="mb-0">Structural and civil works delivered with quality and safety-first processes.</p>
            </article>
          </div>
          <div class="col-sm-6 col-lg-4">
            <article class="service-list-card h-100">
              <span class="service-list-icon"><i class="fa-solid fa-screwdriver-wrench"></i></span>
              <h3>Maintenance</h3>
              <p class="mb-0">Preventive and corrective maintenance to keep systems dependable year-round.</p>
            </article>
          </div>
          <div class="col-sm-6 col-lg-4">
            <article class="service-list-card h-100">
              <span class="service-list-icon"><i class="fa-solid fa-faucet-drip"></i></span>
              <h3>Plumbing</h3>
              <p class="mb-0">Installations, leak repairs, pressure optimization, and complete pipeline upgrades.</p>
            </article>
          </div>
          <div class="col-sm-6 col-lg-4">
            <article class="service-list-card h-100">
              <span class="service-list-icon"><i class="fa-solid fa-bolt"></i></span>
              <h3>Electrical</h3>
              <p class="mb-0">Wiring, panels, lighting, load balancing, and safe power distribution services.</p>
            </article>
          </div>
          <div class="col-sm-6 col-lg-4">
            <article class="service-list-card h-100">
              <span class="service-list-icon"><i class="fa-solid fa-fan"></i></span>
              <h3>Air Conditioning (HVAC)</h3>
              <p class="mb-0">AC installation, duct servicing, diagnostics, and performance tuning.</p>
            </article>
          </div>
          <div class="col-sm-6 col-lg-4">
            <article class="service-list-card h-100">
              <span class="service-list-icon"><i class="fa-solid fa-ruler-combined"></i></span>
              <h3>Interior Fit-Out</h3>
              <p class="mb-0">Partitions, ceilings, finishes, and full interior technical coordination.</p>
            </article>
          </div>
          <div class="col-sm-6 col-lg-4">
            <article class="service-list-card h-100">
              <span class="service-list-icon"><i class="fa-solid fa-paint-roller"></i></span>
              <h3>Painting</h3>
              <p class="mb-0">Interior and exterior painting with premium preparation and finishing.</p>
            </article>
          </div>
          <div class="col-sm-6 col-lg-4">
            <article class="service-list-card h-100">
              <span class="service-list-icon"><i class="fa-solid fa-shield-halved"></i></span>
              <h3>Facility Care</h3>
              <p class="mb-0">Routine inspections, upkeep plans, and emergency support for properties.</p>
            </article>
          </div>
          <div class="col-sm-6 col-lg-4">
            <article class="service-list-card h-100">
              <span class="service-list-icon"><i class="fa-solid fa-toolbox"></i></span>
              <h3>Other Technical Works</h3>
              <p class="mb-0">Carpentry, tiling, waterproofing, and multi-trade technical services.</p>
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
