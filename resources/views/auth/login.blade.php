<!DOCTYPE html>
<html lang="en" data-theme="light">
    @include('layouts.head')
<body>

<style>
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap');

/* ── ROOT TOKENS ── */
:root {
  --primary:      #1a73e8;
  --primary-dark: #1558b0;
  --accent:       #2196f3;
  --success:      #00c896;
  --warning:      #ffb830;
  --danger:       #ff4d6d;
  --bg:           #f0f4fb;
  --white:        #ffffff;
  --card:         #ffffff;
  --border:       #e2e8f0;
  --text:         #1a1a2e;
  --muted:        #6b7a99;
  --navy:         #0d1b3e;
  --navy-mid:     #162447;
  --navy-light:   #1f3a6e;
  --radius:       14px;
  --shadow:       0 4px 32px rgba(26,115,232,.10);
  --shadow-lg:    0 16px 64px rgba(26,115,232,.16);
}

html[data-theme="dark"] {
  --bg:     #0f172a;
  --white:  #1e293b;
  --card:   #1e293b;
  --text:   #e2e8f0;
  --border: #334155;
  --muted:  #94a3b8;
  --shadow:    0 4px 32px rgba(0,0,0,.35);
  --shadow-lg: 0 16px 64px rgba(0,0,0,.45);
}

/* ── RESET ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

/* ── PAGE SHELL ── */
#authPage {
  font-family: 'Open Sans', sans-serif;
  color: var(--text);
  min-height: 100vh;
  display: flex;
  background: var(--bg);
  transition: background .3s, color .3s;
  overflow: hidden;
}

/* ══════════════════════════════════
   LEFT PANEL — branding / visual
══════════════════════════════════ */
#authPage .auth-left {
  width: 50%;
  background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 55%, var(--navy-light) 100%);
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 40px 50px 44px;
  overflow: hidden;
  flex-shrink: 0;
}

/* radial glow */
#authPage .auth-left::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse at 70% 40%, rgba(33,150,243,.18) 0%, transparent 65%);
  pointer-events: none;
}

/* diagonal grid texture */
#authPage .auth-left::after {
  content: '';
  position: absolute;
  inset: 0;
  background: repeating-linear-gradient(
    45deg,
    rgba(255,255,255,.018) 0px,
    rgba(255,255,255,.018) 1px,
    transparent 1px,
    transparent 26px
  );
  pointer-events: none;
}

/* brand */
#authPage .auth-brand {
  position: relative;
  z-index: 2;
}
#authPage .brand-name {
  font-family: 'Nunito', sans-serif;
  font-weight: 900;
  font-size: 1.6rem;
  color: #fff;
  letter-spacing: .5px;
}
#authPage .brand-name span { color: var(--accent); }
#authPage .brand-sub {
  font-size: .5rem;
  font-weight: 600;
  letter-spacing: 5px;
  color: #7a9cc4;
  display: block;
  margin-top: -3px;
}

/* illustration area */
#authPage .auth-illustration {
  position: relative;
  z-index: 2;
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 30px 0;
}

/* big icon ring */
#authPage .illus-ring {
  width: 200px; height: 200px;
  border-radius: 50%;
  border: 2px solid rgba(255,255,255,.1);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  margin-bottom: 36px;
  animation: ringFloat 2s ease-in-out infinite;
}
@keyframes ringFloat {
  0%,100% { transform: translateY(0); }
  50%      { transform: translateY(-10px); }
}
#authPage .illus-ring::before {
  content: '';
  position: absolute;
  inset: 14px;
  border-radius: 50%;
  border: 1.5px solid rgba(33,150,243,.35);
}
#authPage .illus-ring-inner {
  width: 120px; height: 120px;
  border-radius: 50%;
  background: rgba(33,150,243,.18);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 3rem;
  color: rgba(255,255,255,.75);
  backdrop-filter: blur(4px);
  border: 1px solid rgba(255,255,255,.12);
  transition: all .5s cubic-bezier(.34,1.56,.64,1);
}


/* orbiting dots */
#authPage .orbit-dot {
  position: absolute;
  width: 10px; height: 10px;
  border-radius: 50%;
  border: 2px solid var(--accent);
  background: rgba(33,150,243,.3);
}
#authPage .orbit-dot:nth-child(1) { top: 12px; left: 50%; transform: translateX(-50%); }
#authPage .orbit-dot:nth-child(2) { right: 12px; top: 50%; transform: translateY(-50%); }
#authPage .orbit-dot:nth-child(3) { bottom: 12px; left: 50%; transform: translateX(-50%); }
#authPage .orbit-dot:nth-child(4) { left: 12px; top: 50%; transform: translateY(-50%); }

#authPage .illus-headline {
  font-family: 'Nunito', sans-serif;
  font-weight: 900;
  font-size: 1.8rem;
  color: #fff;
  text-align: center;
  line-height: 1.25;
  margin-bottom: 14px;
}
#authPage .illus-sub {
  font-size: .88rem;
  color: #b8d4f0;
  text-align: center;
  line-height: 1.65;
  max-width: 340px;
}

/* floating stat cards */
#authPage .float-cards {
  display: flex;
  gap: 12px;
  margin-top: 32px;
  flex-wrap: wrap;
  justify-content: center;
}
#authPage .float-card {
  display: flex;
  align-items: center;
  gap: 10px;
  background: rgba(255,255,255,.07);
  border: 1px solid rgba(255,255,255,.13);
  border-radius: 10px;
  padding: 10px 16px;
  backdrop-filter: blur(6px);
}
#authPage .float-card-icon {
  width: 34px; height: 34px;
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: .9rem;
  flex-shrink: 0;
}
#authPage .float-card-text { line-height: 1.3; }
#authPage .float-card-text strong {
  display: block;
  font-family: 'Nunito', sans-serif;
  font-weight: 900;
  font-size: .95rem;
  color: #fff;
}
#authPage .float-card-text span { font-size: .7rem; color: #90c8f8; }

/* left footer */
#authPage .auth-left-footer {
  position: relative;
  z-index: 2;
  font-size: .72rem;
  color: #5a7a99;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
}
#authPage .auth-left-footer a { color: #7a9cc4; text-decoration: none; }
#authPage .auth-left-footer a:hover { color: #fff; }

/* ══════════════════════════════════
   RIGHT PANEL — form
══════════════════════════════════ */
#authPage .auth-right {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 24px;
  background: var(--bg);
  overflow-y: auto;
  transition: background .3s;
}

#authPage .auth-box {
  width: 100%;
  max-width: 440px;
}

/* top row of auth-box */
#authPage .auth-box-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 32px;
  flex-wrap: wrap;
  gap: 10px;
}

#authPage .auth-greeting {
  font-family: 'Nunito', sans-serif;
  font-weight: 900;
  font-size: 1.65rem;
  color: var(--text);
  line-height: 1.2;
  margin-bottom: 4px;
}
#authPage .auth-sub {
  font-size: .82rem;
  color: var(--muted);
}
#authPage .auth-sub a {
  color: var(--primary);
  font-weight: 700;
  text-decoration: none;
}
#authPage .auth-sub a:hover { text-decoration: underline; }

/* theme toggle */
#authPage .theme-btn {
  width: 38px; height: 38px;
  border-radius: 50%;
  border: 1.5px solid var(--border);
  background: var(--card);
  color: var(--muted);
  font-size: .88rem;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all .2s;
  flex-shrink: 0;
}
#authPage .theme-btn:hover { border-color: var(--primary); color: var(--primary); }

/* ── AUTH CARD ── */
#authPage .auth-card {
  background: var(--card);
  border-radius: var(--radius);
  border: 1px solid var(--border);
  box-shadow: var(--shadow);
  padding: 32px 36px 36px;
  transition: background .3s, box-shadow .3s;
}
#authPage .auth-card:hover { box-shadow: var(--shadow-lg); }

/* ── FORM ELEMENTS ── */
#authPage .auth-form-group {
  margin-bottom: 18px;
}
#authPage .auth-label {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: .8rem;
  font-weight: 700;
  color: var(--text);
  margin-bottom: 7px;
}
#authPage .auth-label i { font-size: .78rem; color: var(--primary); }

#authPage .auth-input-wrap { position: relative; }
#authPage .auth-input-icon {
  position: absolute;
  left: 13px; top: 50%; transform: translateY(-50%);
  color: var(--muted); font-size: .85rem;
  pointer-events: none;
  transition: color .2s;
}
#authPage .auth-input {
  width: 100%;
  border: 1.5px solid var(--border);
  border-radius: 9px;
  padding: 11px 42px 11px 40px;
  font-size: .875rem;
  color: var(--text);
  background: var(--white);
  outline: none;
  font-family: 'Open Sans', sans-serif;
  transition: border-color .2s, box-shadow .2s, background .3s, color .3s;
}
#authPage .auth-input:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(26,115,232,.12);
}
#authPage .auth-input:focus ~ .auth-input-icon { color: var(--primary); }
#authPage .auth-input.is-invalid { border-color: var(--danger); }
#authPage .auth-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(255,77,109,.12); }

/* password toggle eye */
#authPage .pw-toggle {
  position: absolute;
  right: 13px; top: 50%; transform: translateY(-50%);
  color: var(--muted); font-size: .85rem; cursor: pointer;
  background: none; border: none; padding: 4px;
  transition: color .2s;
}
#authPage .pw-toggle:hover { color: var(--primary); }

/* strength bar */
#authPage .pw-strength-wrap {
  margin-top: 8px;
  display: none;
}
#authPage .pw-strength-bar {
  height: 4px;
  border-radius: 2px;
  background: var(--border);
  overflow: hidden;
  margin-bottom: 4px;
}
#authPage .pw-strength-fill {
  height: 100%;
  border-radius: 2px;
  transition: width .3s, background .3s;
  width: 0%;
}
#authPage .pw-strength-label {
  font-size: .68rem;
  font-weight: 700;
  color: var(--muted);
}

/* error / hint text */
#authPage .field-error {
  font-size: .72rem;
  color: var(--danger);
  margin-top: 5px;
  display: flex;
  align-items: center;
  gap: 4px;
}
#authPage .field-hint {
  font-size: .72rem;
  color: var(--muted);
  margin-top: 5px;
  display: flex;
  align-items: center;
  gap: 4px;
}
#authPage .field-hint i, #authPage .field-error i { font-size: .65rem; }

/* row utility */
#authPage .auth-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  flex-wrap: wrap;
  margin-bottom: 18px;
}

/* custom checkbox */
#authPage .auth-check {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  font-size: .8rem;
  color: var(--text);
  user-select: none;
}
#authPage .auth-check input { display: none; }
#authPage .check-box {
  width: 18px; height: 18px;
  border-radius: 5px;
  border: 1.5px solid var(--border);
  background: var(--white);
  display: flex; align-items: center; justify-content: center;
  font-size: .65rem;
  color: #fff;
  transition: all .18s;
  flex-shrink: 0;
}
#authPage .auth-check input:checked ~ .check-box {
  background: var(--primary);
  border-color: var(--primary);
}
#authPage .forgot-link {
  font-size: .8rem;
  color: var(--primary);
  font-weight: 700;
  text-decoration: none;
}
#authPage .forgot-link:hover { text-decoration: underline; }

/* ── SUBMIT BUTTON ── */
#authPage .btn-auth {
  width: 100%;
  padding: 13px;
  border-radius: 9px;
  font-size: .95rem;
  font-weight: 800;
  font-family: 'Nunito', sans-serif;
  border: none;
  cursor: pointer;
  transition: all .22s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 9px;
}
#authPage .btn-auth-primary {
  background: var(--primary);
  color: #fff;
}
#authPage .btn-auth-primary:hover {
  background: var(--primary-dark);
  box-shadow: 0 6px 20px rgba(26,115,232,.38);
  transform: translateY(-1px);
}
#authPage .btn-auth-primary:active { transform: none; }

/* ── DIVIDER ── */
#authPage .auth-divider {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 22px 0;
  font-size: .75rem;
  color: var(--muted);
  font-weight: 600;
}
#authPage .auth-divider::before,
#authPage .auth-divider::after {
  content: '';
  flex: 1;
  height: 1px;
  background: var(--border);
}

/* ── SOCIAL AUTH BUTTONS ── */
#authPage .social-auth-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-bottom: 6px;
}
#authPage .btn-social {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 10px 14px;
  border-radius: 9px;
  border: 1.5px solid var(--border);
  background: var(--card);
  font-size: .82rem;
  font-weight: 700;
  color: var(--text);
  cursor: pointer;
  transition: all .18s;
  font-family: 'Open Sans', sans-serif;
  text-decoration: none;
}
#authPage .btn-social:hover {
  border-color: var(--primary);
  color: var(--primary);
  background: #e8f1fd;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(26,115,232,.12);
}
html[data-theme="dark"] #authPage .btn-social:hover { background: rgba(26,115,232,.15); }
#authPage .btn-social i { font-size: 1rem; }
#authPage .btn-social .g-icon { color: #ea4335; }
#authPage .btn-social .gh-icon { color: var(--text); }

/* ── ALERT MESSAGES ── */
#authPage .auth-alert {
  border-radius: 9px;
  padding: 12px 16px;
  margin-bottom: 18px;
  display: flex;
  align-items: flex-start;
  gap: 10px;
  font-size: .82rem;
  animation: alertSlide .2s ease;
}
@keyframes alertSlide { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:none; } }
#authPage .alert-danger  { background: #fff0f3; border: 1px solid #ffc0cc; color: #c0392b; }
#authPage .alert-success { background: #eafaf5; border: 1px solid #a8dfc8; color: #1a7e45; }
html[data-theme="dark"] #authPage .alert-danger  { background: rgba(255,77,109,.1); border-color: rgba(255,77,109,.25); color: #fca5a5; }
html[data-theme="dark"] #authPage .alert-success { background: rgba(0,200,150,.1); border-color: rgba(0,200,150,.25); color: #6ee7b7; }
#authPage .auth-alert i { font-size: .9rem; margin-top: 1px; flex-shrink: 0; }

/* ── RESPONSIVE ── */
@media (max-width: 900px) {
  #authPage .auth-left { display: none; }
  #authPage .auth-right { width: 100%; padding: 28px 20px; }
}
@media (max-width: 480px) {
  #authPage .auth-card { padding: 24px 20px 28px; }
  #authPage .social-auth-grid { grid-template-columns: 1fr; }
}
</style>
@if(session('session_expired_error'))
    <div class="alert alert-danger">
        {{ session('session_expired_error') }}
    </div>
@endif
<div id="authPage">

  {{-- ══════════ LEFT PANEL ══════════ --}}
  <div class="auth-left">
    {{-- Brand --}}
    <div class="auth-brand">
      <div class="brand-name">Kawach<span>TECH</span></div>
      <span class="brand-sub">S O L U T I O N S</span>
    </div>

    {{-- Illustration --}}
    <div class="auth-illustration">
        
      <div class="illus-ring">
        <div class="orbit-dot"></div>
        <div class="orbit-dot"></div>
        <div class="orbit-dot"></div>
        <div class="orbit-dot"></div>
        <div class="illus-ring-inner"><i class="fas fa-lock"></i></div>
      </div>

      <div class="illus-headline">
        Welcome Back<br>to Your Dashboard
      </div>
      <div class="illus-sub">
        Sign in to manage your blog posts, track analytics, and grow your digital presence with our powerful content tools.
      </div>

      <div class="float-cards">
        <div class="float-card">
          <div class="float-card-icon" style="background:rgba(0,200,150,.2);color:#00c896;">
            <i class="fas fa-chart-line"></i>
          </div>
          <div class="float-card-text">
            <strong>124 Posts</strong>
            <span>Published this year</span>
          </div>
        </div>
        <div class="float-card">
          <div class="float-card-icon" style="background:rgba(33,150,243,.2);color:#2196f3;">
            <i class="fas fa-eye"></i>
          </div>
          <div class="float-card-text">
            <strong>482K Views</strong>
            <span>Total blog traffic</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Footer --}}
    <div class="auth-left-footer">
      <span>© 2024 KawachTech Solutions</span>
      <div style="display:flex;gap:14px;">
        <a href="#">Privacy</a>
        <a href="#">Terms</a>
        <a href="#">Help</a>
      </div>
    </div>
  </div>

  {{-- ══════════ RIGHT PANEL ══════════ --}}
  <div class="auth-right">
    <div class="auth-box">

      {{-- top row --}}
      <div class="auth-box-top">
        <div>
          <div class="auth-greeting">Sign In 👋</div>
          <div class="auth-sub">
            Don't have an account? <a href="#">Create one free</a>
          </div>
        </div>
        <button class="theme-btn" id="themeToggle" title="Toggle dark mode">
          <i class="fas fa-moon" id="themeIcon"></i>
        </button>
      </div>

      {{-- Error/Success alerts --}}
      @if(session('status'))
        <div class="auth-alert alert-success">
          <i class="fas fa-check-circle"></i>
          {{ session('status') }}
        </div>
      @endif

      @if($errors->any())
        <div class="auth-alert alert-danger">
          <i class="fas fa-exclamation-circle"></i>
          <div>
            @foreach($errors->all() as $error)
              <div>{{ $error }}</div>
            @endforeach
          </div>
        </div>
      @endif

      {{-- Social auth --}}
      <div class="social-auth-grid">
        <a href="#" class="btn-social">
          <i class="fab fa-google g-icon"></i> Google
        </a>
        <a href="#" class="btn-social">
          <i class="fab fa-github gh-icon"></i> GitHub
        </a>
      </div>

      <div class="auth-divider">or sign in with email</div>

      {{-- LOGIN FORM --}}
      <div class="auth-card">
        <form method="POST" action="{{ route('login') }}" id="loginForm">
          @csrf

          {{-- Email --}}
          <div class="auth-form-group">
            <label class="auth-label" for="email">
              <i class="fas fa-envelope"></i> Email Address
            </label>
            <div class="auth-input-wrap">
              <i class="auth-input-icon fas fa-envelope"></i>
              <input
                type="email"
                name="email"
                id="email"
                class="auth-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                placeholder="you@example.com"
                value="{{ old('email') }}"
                autocomplete="email"
                required
                autofocus
              />
            </div>
            @error('email')
              <div class="field-error"><i class="fas fa-times-circle"></i> {{ $message }}</div>
            @enderror
          </div>

          {{-- Password --}}
          <div class="auth-form-group">
            <label class="auth-label" for="password">
              <i class="fas fa-lock"></i> Password
            </label>
            <div class="auth-input-wrap">
              <i class="auth-input-icon fas fa-lock"></i>
              <input
                type="password"
                name="password"
                id="password"
                class="auth-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                placeholder="Enter your password"
                autocomplete="current-password"
                required
              />
              <button type="button" class="pw-toggle" id="togglePw" title="Show/hide password">
                <i class="fas fa-eye-slash" id="pwEyeIcon"></i>
              </button>
            </div>
            @error('password')
              <div class="field-error"><i class="fas fa-times-circle"></i> {{ $message }}</div>
            @enderror
          </div>

          {{-- Remember me + Forgot password --}}
          <div class="auth-row">
            <label class="auth-check">
              <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
              <span class="check-box"><i class="fas fa-check"></i></span>
              Remember me for 30 days
            </label>
          </div>

          {{-- Submit --}}
          <button type="submit" class="btn-auth btn-auth-primary" id="loginBtn">
            <i class="fas fa-sign-in-alt"></i>
            <span id="loginBtnText">Sign In to Dashboard</span>
          </button>

        </form>
      </div>

      {{-- Mobile-only register link --}}
      <div style="text-align:center;margin-top:20px;font-size:.8rem;color:var(--muted);">
        New to KawachTech? 
        <a href="{{ route('register-form') }}" style="color:var(--primary);font-weight:700;text-decoration:none;">Create an account →</a>
      </div>

    </div>{{-- /auth-box --}}
  </div>{{-- /auth-right --}}

</div>{{-- /authPage --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(function () {

  /* ── Dark mode ── */
  var isDark = $('html').attr('data-theme') === 'dark';
  function applyTheme(dark) {
    $('html').attr('data-theme', dark ? 'dark' : 'light');
    $('#themeIcon').toggleClass('fa-moon', !dark).toggleClass('fa-sun', dark);
    localStorage.setItem('theme', dark ? 'dark' : 'light');
  }
  // Load saved preference
  var saved = localStorage.getItem('theme');
  if (saved) { isDark = saved === 'dark'; applyTheme(isDark); }

  $('#themeToggle').on('click', function () {
    isDark = !isDark;
    applyTheme(isDark);
  });

  /* ── Password toggle ── */
  $('#togglePw').on('click', function () {
    var $inp = $('#password');
    var isText = $inp.attr('type') === 'text';
    $inp.attr('type', isText ? 'password' : 'text');
    $('#pwEyeIcon').toggleClass('fa-eye-slash', !isText).toggleClass('fa-eye', isText);
  });

  /* ── Custom checkbox ── */
  $(document).on('click', '.auth-check', function () {
    var $inp = $(this).find('input[type="checkbox"]');
    $inp.prop('checked', !$inp.prop('checked'));
    $(this).find('.check-box').toggleClass('checked', $inp.prop('checked'));
  });

  /* ── Submit loading state ── */
  $('#loginForm').on('submit', function () {
    var $btn = $('#loginBtn');
    $('#loginBtnText').text('Signing in…');
    $btn.prop('disabled', true).css('opacity', '.75');
    $btn.find('i').removeClass('fa-sign-in-alt').addClass('fa-spinner fa-spin');
  });

  /* ── Input focus: icon colour ── */
  $('.auth-input').on('focus', function () {
    $(this).siblings('.auth-input-icon').css('color', 'var(--primary)');
  }).on('blur', function () {
    $(this).siblings('.auth-input-icon').css('color', 'var(--muted)');
  });

});
</script>

</body>
</html>