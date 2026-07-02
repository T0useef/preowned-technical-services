<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <x-favicon />
  <title>Login | Preowned Technical Services</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --brand-gold: #eabc73;
      --brand-navy: #080059;
      --surface-2: rgba(255, 255, 255, 0.92);
      --text-main: #22274a;
      --text-muted: #71749a;
      --radius-xl: 24px;
      --radius-md: 12px;
      --shadow-soft: 0 16px 40px rgba(8, 0, 89, 0.13);
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      font-family: "Inter", sans-serif;
      color: var(--text-main);
      background:
        radial-gradient(circle at 0% 0%, rgba(234, 188, 115, 0.3), transparent 34%),
        radial-gradient(circle at 100% 100%, rgba(8, 0, 89, 0.25), transparent 38%),
        linear-gradient(130deg, #f9f9ff 0%, #f5f4ff 52%, #fffef8 100%);
      display: grid;
      place-items: center;
      padding: 1rem;
    }

    .login-shell {
      width: min(430px, 100%);
      border-radius: var(--radius-xl);
      background: var(--surface-2);
      backdrop-filter: blur(14px);
      border: 1px solid rgba(255, 255, 255, 0.7);
      box-shadow: var(--shadow-soft);
      padding: clamp(1.5rem, 4vw, 2.25rem);
    }

    .login-logo {
      display: flex;
      justify-content: center;
      margin-bottom: 1.35rem;
    }

    .login-logo a {
      display: inline-block;
      line-height: 0;
    }

    .login-logo img {
      height: 56px;
      width: auto;
      display: block;
      object-fit: contain;
    }

    .login-eyebrow {
      display: inline-block;
      border-radius: 999px;
      background: rgba(8, 0, 89, 0.08);
      color: var(--brand-navy);
      padding: 0.38rem 0.72rem;
      font-size: 0.76rem;
      font-weight: 600;
      margin-bottom: 0.75rem;
    }

    .login-title {
      margin: 0 0 0.35rem;
      font-size: 1.58rem;
      color: var(--brand-navy);
      font-weight: 800;
      letter-spacing: -0.3px;
    }

    .login-subtitle {
      margin: 0 0 1.2rem;
      color: var(--text-muted);
      font-size: 0.9rem;
    }

    .form-label {
      font-size: 0.82rem;
      font-weight: 600;
      margin-bottom: 0.34rem;
      color: #2f3461;
    }

    .form-control {
      min-height: 48px;
      border-radius: var(--radius-md);
      border: 1px solid rgba(8, 0, 89, 0.15);
      background: #fff;
    }

    .form-control:focus {
      border-color: rgba(8, 0, 89, 0.35);
      box-shadow: 0 0 0 0.15rem rgba(8, 0, 89, 0.08);
    }

    .password-wrap {
      position: relative;
    }

    .password-wrap .form-control {
      padding-right: 2.8rem;
    }

    .password-toggle-btn {
      position: absolute;
      top: 50%;
      right: 0.7rem;
      transform: translateY(-50%);
      border: none;
      background: transparent;
      color: #8a8ead;
      font-size: 1rem;
      line-height: 1;
      padding: 0.2rem;
      cursor: pointer;
    }

    .password-toggle-btn:hover {
      color: #4f5382;
    }

    .btn-gold {
      min-height: 50px;
      border-radius: 12px;
      border: none;
      width: 100%;
      font-weight: 700;
      color: var(--brand-navy);
      background: linear-gradient(120deg, var(--brand-gold), #f3d7a8);
      transition: all 0.3s ease;
      box-shadow: 0 12px 28px rgba(234, 188, 115, 0.35);
    }

    .btn-gold:hover {
      transform: translateY(-2px);
      color: var(--brand-navy);
      box-shadow: 0 16px 32px rgba(234, 188, 115, 0.45);
    }

    .foot-note {
      margin-top: 1rem;
      color: #868ab2;
      text-align: center;
      font-size: 0.78rem;
    }

    @media (max-width: 575.98px) {
      body {
        padding: 0.55rem;
      }

      .login-shell {
        padding: 1.25rem;
      }

      .login-logo img {
        height: 48px;
      }
    }
  </style>
</head>
<body>
  <section class="login-shell">
    <div class="login-logo">
      <a href="{{ route('welcome') }}">
        <img src="{{ asset('assets/images/pts-logo.png') }}" alt="Preowned Technical Services">
      </a>
    </div>

    <span class="login-eyebrow">Secure Access</span>
    <h1 class="login-title">Sign In</h1>
    <p class="login-subtitle">Use your account credentials to access the dashboard.</p>

    <form id="loginForm" action="index.html" method="get" novalidate>
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email" placeholder="admin@techservicepro.com" required>
        <div class="text-danger" id="email-error"></div>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="password-wrap">
          <input type="password" class="form-control" id="password" placeholder="Enter password" required minlength="6">
          <button class="password-toggle-btn" type="button" id="togglePassword" aria-label="Toggle password visibility">
            <i class="fa-regular fa-eye"></i>
          </button>
        </div>
        <div class="text-danger" id="password-error"></div>
      </div>

      <button type="submit" class="btn btn-gold" id="loginBtn">
        <span class="btn-text">Login to Dashboard</span>
      </button>
    </form>

    <p class="foot-note">Protected access for authorized team members only.</p>
  </section>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(function () {
      $("#togglePassword").on("click", function () {
        const $password = $("#password");
        const isPassword = $password.attr("type") === "password";
        $password.attr("type", isPassword ? "text" : "password");
        $(this).find("i")
          .toggleClass("fa-eye", !isPassword)
          .toggleClass("fa-eye-slash", isPassword);
      });

      $("#loginForm").on("submit", function (e) {
        $("#email-error").html('');
        $("#password-error").html('');

        var btn = $("#loginBtn");
        btn.prop("disabled", true);
        btn.html('<i class="fa-solid fa-spinner fa-spin"></i> Logging in...');

        e.preventDefault();
        $.ajax({
          url: '{{ route('login') }}',
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            email: $("#email").val(),
            password: $("#password").val(),
          },
          success: function(response) {
            window.location.href = '{{ route('dashboard') }}';
          },
          error: function(xhr, status, error) {
            if(xhr.responseJSON.errors.email) {
              $("#email-error").html('<i class="fa-solid fa-circle-exclamation"></i> ' + xhr.responseJSON.errors.email[0]);
            }
            if(xhr.responseJSON.errors.password) {
              $("#password-error").html('<i class="fa-solid fa-circle-exclamation"></i> ' + xhr.responseJSON.errors.password[0]);
            }
            btn.prop("disabled", false);
            btn.html('Login to Dashboard');
          }
        });
      });
    });
  </script>
</body>
</html>
