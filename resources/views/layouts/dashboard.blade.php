<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <x-favicon />
  <title>@yield('title')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('dashboard_assets/dashboard.css') }}">
  @yield('style')
</head>
<body>
  <div class="sidebar-overlay" id="sidebarOverlay"></div>
  <div class="dashboard-shell">
    @include('layouts.components.sidebar')

    <main class="main-content">
      <header class="topbar">
        <div class="d-flex align-items-center gap-2 flex-grow-1">
          <button class="hamburger-btn" id="toggleSidebar" aria-label="Toggle sidebar">
            <i class="fa-solid fa-bars"></i>
          </button>
          <div class="d-flex align-items-center gap-2 px-3 py-2">
            <div class="avatar" style="width:38px;height:38px;">
              <i class="fa-solid fa-user"></i>
            </div>
            <div>
              <p class="mb-0 small text-secondary">Welcome back</p>
              <h6 class="mb-0 fw-bold" style="color:#080059;">{{ auth()->user()->name }} <span style="color:#eabc73;">&#10024;</span></h6>
            </div>
          </div>
        </div>
        <div class="topbar-actions">
          <button class="btn btn-danger text-white" id="logoutBtn"><i class="fa-solid fa-right-from-bracket me-1"></i>Logout</button>
        </div>
      </header>

      @yield('content')
    </main>
  </div>

  @yield('modals')

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(function () {
      const $sidebar = $("#sidebar");
      const $overlay = $("#sidebarOverlay");
      const isMobile = () => window.matchMedia("(max-width: 991.98px)").matches;

      function closeSidebar() {
        $sidebar.removeClass("show");
        $overlay.removeClass("show");
        $("body").removeClass("overflow-hidden");
      }

      $("#toggleSidebar").on("click", function () {
        $sidebar.toggleClass("show");
        $overlay.toggleClass("show");
        $("body").toggleClass("overflow-hidden");
      });

      $overlay.on("click", closeSidebar);

      $(window).on("resize", function () {
        if (!isMobile()) {
          closeSidebar();
        }
      });
    });
  </script>
  @yield('scripts')
</body>
</html>
