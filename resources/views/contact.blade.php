<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <x-favicon />
  <title>Contact Us | Preowned Technical Services</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
  <header class="contact-page-header">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top site-navbar">
      <div class="container">
        <x-site-logo />
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#contactNav" aria-controls="contactNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="contactNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="{{ route('welcome') }}">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('services') }}">Services</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('projects') }}">Projects</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="contact-page-hero-bg"></div>
    <div class="contact-page-hero-overlay"></div>
    <div class="container contact-page-hero-content">
      <div class="row min-vh-70 align-items-center">
        <div class="col-lg-9 col-xl-8">
          <p class="hero-tagline mb-2">Contact Us</p>
          <h1 class="contact-page-title mb-3">Let’s discuss your next technical project.</h1>
          <p class="contact-page-subtitle mb-0">Share your requirements and our team will respond with a clear scope, timeline, and execution plan.</p>
        </div>
      </div>
    </div>
  </header>

  <main>
    <section class="section-pad contact-page-section" aria-labelledby="contact-page-heading">
      <div class="container">
        <div class="row g-4 g-lg-5 align-items-stretch">
          <div class="col-lg-8">
            <article class="contact-form-card mb-4">
              <h3 class="contact-form-title">Send us a message</h3>
              <form class="contact-form" action="#" method="post">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="contactName" class="form-label">Full Name</label>
                    <input type="text" id="contactName" name="name" class="form-control contact-input" placeholder="Enter your name" required>
                  </div>
                  <div class="col-md-6">
                    <label for="contactEmail" class="form-label">Email Address</label>
                    <input type="email" id="contactEmail" name="email" class="form-control contact-input" placeholder="you@example.com" required>
                  </div>
                  <div class="col-md-6">
                    <label for="contactPhone" class="form-label">Phone Number</label>
                    <input type="tel" id="contactPhone" name="phone" class="form-control contact-input" placeholder="+971 50 000 0000">
                  </div>
                  <div class="col-md-6">
                    <label for="contactService" class="form-label">Service Needed</label>
                    <select id="contactService" name="service" class="form-select contact-input" required>
                      <option value="">Select a service</option>
                      <option>Construction</option>
                      <option>Maintenance</option>
                      <option>Plumbing</option>
                      <option>Electrical</option>
                      <option>Air Conditioning (HVAC)</option>
                      <option>Interior Fit-Out</option>
                      <option>Painting</option>
                      <option>Facility Care</option>
                      <option>Other Technical Works</option>
                    </select>
                  </div>
                  <div class="col-12">
                    <label for="contactMessage" class="form-label">Project Details</label>
                    <textarea id="contactMessage" name="message" rows="4" class="form-control contact-input contact-textarea" placeholder="Share your project scope, location, and expected timeline..." required></textarea>
                  </div>
                  <div class="col-12">
                    <button type="submit" class="btn btn-hero contact-submit-btn">Send Inquiry <i class="fa-solid fa-paper-plane ms-2"></i></button>
                  </div>
                </div>
              </form>
            </article>
          </div>
          <div class="col-lg-4">
            <article class="contact-info-card h-100">
              <p class="section-kicker mb-2"><span class="section-kicker-inner">Get in Touch</span></p>
              <h2 id="contact-page-heading">Contact information</h2>
              <p class="mb-4">We are available for site visits, technical consultations, and project planning support.</p>
              <ul class="contact-info-list">
                <li><i class="fa-solid fa-location-dot"></i> Office # 2403, Damac Smart Height Tecom - Dubai - United Arab Emirates</li>
                <li><i class="fa-solid fa-phone"></i> +971 56 814 4848</li>
                <li><i class="fa-solid fa-envelope"></i> info@preownedtechnicalservices.com</li>
                <li><i class="fa-solid fa-clock"></i> Mon - Sat: 10:00 AM to 7:00 PM</li>
              </ul>
            </article>
          </div>
          <div class="col-12">
            
            <article class="contact-map-card">
              <h3 class="contact-map-title">Our Location</h3>
              <div class="contact-map-wrap">
                <iframe
                  title="TechServicePro location map"
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3613.154593831793!2d55.176651099999994!3d25.0966277!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f6ba177f231db%3A0xe7c71f71c1334acb!2sPreowned%20Properties!5e0!3m2!1sen!2sae!4v1777037043483!5m2!1sen!2sae"
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"
                  allowfullscreen></iframe>
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
