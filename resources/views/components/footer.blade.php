<footer id="contact" class="site-footer">
    <div class="container">
      <div class="row g-4 g-lg-5 align-items-start">
        <div class="col-lg-4">
          <x-site-logo variant="footer" />
          <p class="footer-text mt-3 mb-3">Modern technical services company delivering premium construction, maintenance, and plumbing execution with reliability and speed.</p>
          <div class="footer-socials" aria-label="Social links">
            <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
            <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
          </div>
        </div>

        <div class="col-sm-6 col-lg-2">
          <h3 class="footer-title">Quick Links</h3>
          <ul class="footer-links">
            <li><a href="{{ route('welcome') }}">Home</a></li>
            <li><a href="{{ route('about') }}">About</a></li>
            <li><a href="{{ route('services') }}">What We Can Do</a></li>
            <li><a href="{{ route('projects') }}">Projects</a></li>
            <li><a href="{{ route('contact') }}">Contact</a></li>
          </ul>
        </div>

        <div class="col-sm-6 col-lg-3">
          <h3 class="footer-title">Core Services</h3>
          <ul class="footer-links">
            <li><a href="{{ route('services') }}">Construction</a></li>
            <li><a href="{{ route('services') }}">Maintenance</a></li>
            <li><a href="{{ route('services') }}">Plumbing</a></li>
            <li><a href="{{ route('services') }}">Facility Care</a></li>
          </ul>
        </div>

        <div class="col-lg-3">
          <h3 class="footer-title">Contact</h3>
          <ul class="footer-contact">
            <li><i class="fa-solid fa-location-dot"></i> Business Bay, Dubai, UAE</li>
            <li><i class="fa-solid fa-phone"></i> +92 300 0000000</li>
            <li><i class="fa-solid fa-envelope"></i> info@techservicepro.com</li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <p class="mb-0">© 2026 TechServicePro. All rights reserved.</p>
        <p class="mb-0">Built with premium technical standards.</p>
      </div>
    </div>
  </footer>