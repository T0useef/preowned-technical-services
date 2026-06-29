<aside class="sidebar" id="sidebar">
    <div class="logo-area">
      <div class="logo-icon"><i class="fa-solid fa-screwdriver-wrench"></i></div>
      <div class="logo-title">
        <h6>Preowned Technical Services</h6>
        <span>Admin Dashboard</span>
      </div>
    </div>

    <div class="menu-title">Main Navigation</div>
    <nav class="nav flex-column">
      <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="fa-solid fa-dashboard"></i>Dashboard</a>
      <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}"><i class="fa-solid fa-users"></i>Users</a>
      <a class="nav-link {{ request()->is('dashboard/projects*') ? 'active' : '' }}" href="{{ route('dashboard.projects.index') }}"><i class="fa-solid fa-diagram-project"></i>Projects</a>
      <a class="nav-link {{ request()->is('dashboard/quotations*') ? 'active' : '' }}" href="{{ route('dashboard.quotations.index') }}"><i class="fa-solid fa-file-invoice"></i>Quotations</a>
      <a
        class="nav-link d-flex justify-content-between align-items-center {{ request()->is('dashboard/payments*') ? 'active' : '' }}"
        data-bs-toggle="collapse"
        href="#paymentsMenu"
        role="button"
        aria-expanded="{{ request()->is('dashboard/payments*') ? 'true' : 'false' }}"
        aria-controls="paymentsMenu"
      >
        <span><i class="fa-solid fa-money-check-dollar"></i>Payments</span>
        <i class="fa-solid fa-chevron-down small"></i>
      </a>
      <div class="collapse {{ request()->is('dashboard/payments*') ? 'show' : '' }}" id="paymentsMenu">
        <a class="nav-link ps-4 {{ request()->is('dashboard/payments') ? 'active' : '' }}" href="{{ route('dashboard.payments.index') }}">
          <i class="fa-solid fa-circle-dot small me-1"></i>Advance
        </a>
        <a class="nav-link ps-4 {{ request()->is('dashboard/payments/salaries') ? 'active' : '' }}" href="{{ route('dashboard.payments.salaries') }}">
          <i class="fa-solid fa-circle-dot small me-1"></i>Salaries
        </a>
      </div>
    </nav>
  </aside>