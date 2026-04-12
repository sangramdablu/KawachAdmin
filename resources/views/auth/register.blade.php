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
   LEFT PANEL
══════════════════════════════════ */
#authPage .auth-left {
  width: 50%;
  background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 55%, #1e4a8f 100%);
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 40px 50px 44px;
  overflow: hidden;
  flex-shrink: 0;
}
#authPage .auth-left::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse at 30% 60%, rgba(33,150,243,.2) 0%, transparent 65%);
  pointer-events: none;
}
#authPage .auth-left::after {
  content: '';
  position: absolute;
  inset: 0;
  background: repeating-linear-gradient(
    45deg,
    rgba(255,255,255,.018) 0px, rgba(255,255,255,.018) 1px,
    transparent 1px, transparent 26px
  );
  pointer-events: none;
}

#authPage .auth-brand { position: relative; z-index: 2; }
#authPage .brand-name {
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 1.6rem; color: #fff; letter-spacing: .5px;
}
#authPage .brand-name span { color: var(--accent); }
#authPage .brand-sub {
  font-size: .5rem; font-weight: 600; letter-spacing: 5px;
  color: #7a9cc4; display: block; margin-top: -3px;
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

/* illustration */
#authPage .auth-illustration {
  position: relative; z-index: 2;
  flex: 1; display: flex; flex-direction: column;
  align-items: center; justify-content: center; padding: 24px 0;
}

/* feature list */
#authPage .feature-list { width: 100%; max-width: 340px; }
#authPage .feature-item {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  padding: 14px 0;
  border-bottom: 1px solid rgba(255,255,255,.08);
}
#authPage .feature-item:last-child { border-bottom: none; }
#authPage .feature-icon {
  width: 40px; height: 40px;
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: .95rem;
  flex-shrink: 0;
}
#authPage .feature-body { flex: 1; }
#authPage .feature-title {
  font-family: 'Nunito', sans-serif; font-weight: 800;
  font-size: .9rem; color: #fff; margin-bottom: 2px;
}
#authPage .feature-desc { font-size: .75rem; color: #90c8f8; line-height: 1.5; }

/* headline above feature list */
#authPage .illus-headline {
  font-family: 'Nunito', sans-serif;
  font-weight: 900; font-size: 1.75rem; color: #fff;
  text-align: center; line-height: 1.25; margin-bottom: 28px;
}
#authPage .illus-sub {
  font-size: .84rem; color: #b8d4f0;
  text-align: center; line-height: 1.65;
  max-width: 320px; margin-bottom: 32px;
}

/* trust strip */
#authPage .trust-strip {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-top: 28px;
  padding: 12px 16px;
  background: rgba(255,255,255,.06);
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 10px;
  backdrop-filter: blur(4px);
  width: 100%; max-width: 340px;
}
#authPage .trust-strip i { color: var(--success); font-size: .9rem; }
#authPage .trust-text { font-size: .73rem; color: #90c8f8; line-height: 1.4; }
#authPage .trust-text strong { color: #fff; }

#authPage .auth-left-footer {
  position: relative; z-index: 2;
  font-size: .72rem; color: #5a7a99;
  display: flex; align-items: center; justify-content: space-between; gap: 10px;
}
#authPage .auth-left-footer a { color: #7a9cc4; text-decoration: none; }
#authPage .auth-left-footer a:hover { color: #fff; }

/* ══════════════════════════════════
   RIGHT PANEL
══════════════════════════════════ */
#authPage .auth-right {
  flex: 1; display: flex; align-items: flex-start;
  justify-content: center; padding: 36px 24px;
  background: var(--bg); overflow-y: auto;
  transition: background .3s;
}

#authPage .auth-box {
  width: 100%; max-width: 480px;
  padding: 4px 0 40px;
}

#authPage .auth-box-top {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 28px; flex-wrap: wrap; gap: 10px;
}
#authPage .auth-greeting {
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 1.6rem; color: var(--text); line-height: 1.2; margin-bottom: 4px;
}
#authPage .auth-sub { font-size: .82rem; color: var(--muted); }
#authPage .auth-sub a { color: var(--primary); font-weight: 700; text-decoration: none; }
#authPage .auth-sub a:hover { text-decoration: underline; }

#authPage .theme-btn {
  width: 38px; height: 38px; border-radius: 50%;
  border: 1.5px solid var(--border); background: var(--card);
  color: var(--muted); font-size: .88rem; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all .2s; flex-shrink: 0;
}
#authPage .theme-btn:hover { border-color: var(--primary); color: var(--primary); }

/* ── AUTH CARD ── */
#authPage .auth-card {
  background: var(--card);
  border-radius: var(--radius);
  border: 1px solid var(--border);
  box-shadow: var(--shadow);
  padding: 30px 32px 34px;
  transition: background .3s, box-shadow .3s;
}
#authPage .auth-card:hover { box-shadow: var(--shadow-lg); }

/* step indicator */
#authPage .step-bar {
  display: flex; align-items: center; gap: 0;
  margin-bottom: 26px;
}
#authPage .step-dot {
  width: 28px; height: 28px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: .75rem; border: 2px solid var(--border);
  background: var(--white); color: var(--muted);
  flex-shrink: 0; transition: all .25s;
  position: relative; z-index: 1;
}
#authPage .step-dot.active { background: var(--primary); border-color: var(--primary); color: #fff; }
#authPage .step-dot.done { background: var(--success); border-color: var(--success); color: #fff; }
#authPage .step-line {
  flex: 1; height: 2px; background: var(--border); transition: background .25s;
}
#authPage .step-line.done { background: var(--success); }

/* ── FORM ELEMENTS ── */
#authPage .auth-form-group { margin-bottom: 16px; }
#authPage .auth-label {
  display: flex; align-items: center; gap: 6px;
  font-size: .8rem; font-weight: 700; color: var(--text); margin-bottom: 7px;
}
#authPage .auth-label i { font-size: .78rem; color: var(--primary); }

#authPage .auth-input-wrap { position: relative; }
#authPage .auth-input-icon {
  position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
  color: var(--muted); font-size: .85rem; pointer-events: none; transition: color .2s;
}
#authPage .auth-input {
  width: 100%; border: 1.5px solid var(--border);
  border-radius: 9px; padding: 11px 42px 11px 40px;
  font-size: .875rem; color: var(--text);
  background: var(--white); outline: none;
  font-family: 'Open Sans', sans-serif;
  transition: border-color .2s, box-shadow .2s, background .3s, color .3s;
}
#authPage .auth-input:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(26,115,232,.12);
}
#authPage .auth-input.is-valid { border-color: var(--success); }
#authPage .auth-input.is-invalid { border-color: var(--danger); }
#authPage .auth-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(255,77,109,.12); }

/* validation icon */
#authPage .valid-icon {
  position: absolute; right: 13px; top: 50%; transform: translateY(-50%);
  font-size: .82rem; display: none;
}
#authPage .valid-icon.show-valid { display: block; color: var(--success); }
#authPage .valid-icon.show-invalid { display: block; color: var(--danger); }

/* pw toggle */
#authPage .pw-toggle {
  position: absolute; right: 13px; top: 50%; transform: translateY(-50%);
  color: var(--muted); font-size: .85rem; cursor: pointer;
  background: none; border: none; padding: 4px; transition: color .2s;
}
#authPage .pw-toggle:hover { color: var(--primary); }

/* password strength */
#authPage .pw-strength-wrap { margin-top: 8px; }
#authPage .pw-strength-bars {
  display: flex; gap: 4px; margin-bottom: 5px;
}
#authPage .pw-bar {
  flex: 1; height: 4px; border-radius: 2px;
  background: var(--border); transition: background .3s;
}
#authPage .pw-strength-row {
  display: flex; align-items: center; justify-content: space-between;
}
#authPage .pw-strength-label { font-size: .68rem; font-weight: 700; color: var(--muted); }
#authPage .pw-rules {
  margin-top: 10px; display: flex; flex-wrap: wrap; gap: 6px;
}
#authPage .pw-rule {
  display: flex; align-items: center; gap: 4px;
  font-size: .68rem; color: var(--muted); font-weight: 600;
}
#authPage .pw-rule i { font-size: .6rem; color: var(--border); }
#authPage .pw-rule.ok { color: var(--success); }
#authPage .pw-rule.ok i { color: var(--success); }

/* name row */
#authPage .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
@media (max-width: 480px) { #authPage .field-row { grid-template-columns: 1fr; } }

/* error / hint */
#authPage .field-error {
  font-size: .72rem; color: var(--danger); margin-top: 5px;
  display: flex; align-items: center; gap: 4px;
}
#authPage .field-hint {
  font-size: .72rem; color: var(--muted); margin-top: 5px;
  display: flex; align-items: center; gap: 4px;
}
#authPage .field-error i, #authPage .field-hint i { font-size: .65rem; }

/* terms checkbox */
#authPage .auth-check {
  display: flex; align-items: flex-start; gap: 10px;
  cursor: pointer; font-size: .8rem; color: var(--text); user-select: none;
  line-height: 1.5;
}
#authPage .auth-check input { display: none; }
#authPage .check-box {
  width: 18px; height: 18px; border-radius: 5px; margin-top: 1px;
  border: 1.5px solid var(--border); background: var(--white);
  display: flex; align-items: center; justify-content: center;
  font-size: .65rem; color: #fff; transition: all .18s; flex-shrink: 0;
}
#authPage .auth-check input:checked ~ .check-box {
  background: var(--primary); border-color: var(--primary);
}
#authPage .auth-check a { color: var(--primary); font-weight: 700; text-decoration: none; }
#authPage .auth-check a:hover { text-decoration: underline; }

/* ── SUBMIT BUTTON ── */
#authPage .btn-auth {
  width: 100%; padding: 13px; border-radius: 9px;
  font-size: .95rem; font-weight: 800;
  font-family: 'Nunito', sans-serif; border: none;
  cursor: pointer; transition: all .22s;
  display: flex; align-items: center; justify-content: center; gap: 9px;
  margin-top: 6px;
}
#authPage .btn-auth-primary { background: var(--primary); color: #fff; }
#authPage .btn-auth-primary:hover {
  background: var(--primary-dark);
  box-shadow: 0 6px 20px rgba(26,115,232,.38);
  transform: translateY(-1px);
}

/* ── DIVIDER ── */
#authPage .auth-divider {
  display: flex; align-items: center; gap: 12px;
  margin: 20px 0; font-size: .75rem; color: var(--muted); font-weight: 600;
}
#authPage .auth-divider::before, #authPage .auth-divider::after {
  content: ''; flex: 1; height: 1px; background: var(--border);
}

/* ── SOCIAL ── */
#authPage .social-auth-grid {
  display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 6px;
}
#authPage .btn-social {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  padding: 10px 14px; border-radius: 9px;
  border: 1.5px solid var(--border); background: var(--card);
  font-size: .82rem; font-weight: 700; color: var(--text);
  cursor: pointer; transition: all .18s; font-family: 'Open Sans', sans-serif;
  text-decoration: none;
}
#authPage .btn-social:hover {
  border-color: var(--primary); color: var(--primary);
  background: #e8f1fd; transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(26,115,232,.12);
}
html[data-theme="dark"] #authPage .btn-social:hover { background: rgba(26,115,232,.15); }
#authPage .btn-social i { font-size: 1rem; }
#authPage .g-icon { color: #ea4335; }
#authPage .gh-icon { color: var(--text); }

/* ── ALERTS ── */
#authPage .auth-alert {
  border-radius: 9px; padding: 12px 16px; margin-bottom: 16px;
  display: flex; align-items: flex-start; gap: 10px; font-size: .82rem;
  animation: alertSlide .2s ease;
}
@keyframes alertSlide { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:none; } }
#authPage .alert-danger  { background: #fff0f3; border: 1px solid #ffc0cc; color: #c0392b; }
html[data-theme="dark"] #authPage .alert-danger { background: rgba(255,77,109,.1); border-color: rgba(255,77,109,.25); color: #fca5a5; }
#authPage .auth-alert i { font-size: .9rem; margin-top: 1px; flex-shrink: 0; }

/* ── RESPONSIVE ── */
@media (max-width: 900px) {
  #authPage .auth-left { display: none; }
  #authPage .auth-right { width: 100%; padding: 28px 20px; }
}
@media (max-width: 480px) {
  #authPage .auth-card { padding: 22px 18px 26px; }
  #authPage .social-auth-grid { grid-template-columns: 1fr; }
}
</style>
<div id="authPage">

  {{-- ══════════ LEFT PANEL ══════════ --}}
  <div class="auth-left">
    <div class="auth-brand">
      <div class="brand-name">Kawach<span>TECH</span></div>
      <span class="brand-sub">S O L U T I O N S</span>
    </div>

    <div class="auth-illustration">
      <div class="illus-ring">
        <div class="orbit-dot"></div>
        <div class="orbit-dot"></div>
        <div class="orbit-dot"></div>
        <div class="orbit-dot"></div>
        <div class="illus-ring-inner"><i class="fas fa-lock"></i></div>
      </div>
      <div class="illus-headline">
        Start Your Journey<br>with KawachTech
      </div>
      <div class="illus-sub">
        Join thousands of content creators and developers building powerful digital experiences.
      </div>

      <div class="feature-list">
        <div class="feature-item">
          <div class="feature-icon" style="background:rgba(0,200,150,.18);color:#00c896;">
            <i class="fas fa-pen-nib"></i>
          </div>
          <div class="feature-body">
            <div class="feature-title">WordPress-style Blog Editor</div>
            <div class="feature-desc">Rich text editing with SEO scoring, meta fields, schemas and social previews.</div>
          </div>
        </div>
        <div class="feature-item">
          <div class="feature-icon" style="background:rgba(33,150,243,.18);color:#2196f3;">
            <i class="fas fa-chart-line"></i>
          </div>
          <div class="feature-body">
            <div class="feature-title">Full Analytics Dashboard</div>
            <div class="feature-desc">Track views, likes, comments, traffic sources and device breakdowns.</div>
          </div>
        </div>
        <div class="feature-item">
          <div class="feature-icon" style="background:rgba(255,184,48,.18);color:#ffb830;">
            <i class="fas fa-search"></i>
          </div>
          <div class="feature-body">
            <div class="feature-title">Built-in SEO Tools</div>
            <div class="feature-desc">Live keyword density, SERP preview, structured data and Open Graph management.</div>
          </div>
        </div>
        <div class="feature-item">
          <div class="feature-icon" style="background:rgba(255,77,109,.15);color:#ff4d6d;">
            <i class="fas fa-moon"></i>
          </div>
          <div class="feature-body">
            <div class="feature-title">Dark Mode + Responsive</div>
            <div class="feature-desc">Beautiful on every device, day or night, with persistent theme preferences.</div>
          </div>
        </div>
      </div>

      <div class="trust-strip">
        <i class="fas fa-shield-alt"></i>
        <div class="trust-text">
          <strong>Your data is safe.</strong> We use 256-bit SSL encryption and never share your information with third parties.
        </div>
      </div>
    </div>

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
          <div class="auth-greeting">Create Account 🚀</div>
          <div class="auth-sub">
            Already have an account? <a href="{{ route('login') }}">Sign in instead</a>
          </div>
        </div>
        <button class="theme-btn" id="themeToggle" title="Toggle dark mode">
          <i class="fas fa-moon" id="themeIcon"></i>
        </button>
      </div>

      {{-- Error alerts --}}
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
        <a href="#}" class="btn-social">
          <i class="fab fa-github gh-icon"></i> GitHub
        </a>
      </div>

      <div class="auth-divider">or register with email</div>

      {{-- REGISTER FORM --}}
      <div class="auth-card">

        {{-- Step indicator --}}
        <div class="step-bar">
          <div class="step-dot active" id="step1dot">1</div>
          <div class="step-line" id="line1"></div>
          <div class="step-dot" id="step2dot">2</div>
          <div class="step-line" id="line2"></div>
          <div class="step-dot" id="step3dot">3</div>
        </div>

        <form method="POST" action="#" id="registerForm">
          @csrf

          {{-- Name Row --}}
          <div class="field-row">
            <div class="auth-form-group">
              <label class="auth-label" for="first_name">
                <i class="fas fa-user"></i> First Name
              </label>
              <div class="auth-input-wrap">
                <i class="auth-input-icon fas fa-user"></i>
                <input
                  type="text"
                  name="first_name"
                  id="first_name"
                  class="auth-input {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                  placeholder="John"
                  value="{{ old('first_name') }}"
                  autocomplete="given-name"
                  required
                />
                <i class="fas fa-check valid-icon" id="fnIcon"></i>
              </div>
              @error('first_name')
                <div class="field-error"><i class="fas fa-times-circle"></i> {{ $message }}</div>
              @enderror
            </div>

            <div class="auth-form-group">
              <label class="auth-label" for="last_name">
                <i class="fas fa-user"></i> Last Name
              </label>
              <div class="auth-input-wrap">
                <i class="auth-input-icon fas fa-user"></i>
                <input
                  type="text"
                  name="last_name"
                  id="last_name"
                  class="auth-input {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                  placeholder="Doe"
                  value="{{ old('last_name') }}"
                  autocomplete="family-name"
                  required
                />
                <i class="fas fa-check valid-icon" id="lnIcon"></i>
              </div>
              @error('last_name')
                <div class="field-error"><i class="fas fa-times-circle"></i> {{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- Username --}}
          <div class="auth-form-group">
            <label class="auth-label" for="username">
              <i class="fas fa-at"></i> Username
            </label>
            <div class="auth-input-wrap">
              <i class="auth-input-icon fas fa-at"></i>
              <input
                type="text"
                name="username"
                id="username"
                class="auth-input {{ $errors->has('username') ? 'is-invalid' : '' }}"
                placeholder="johndoe"
                value="{{ old('username') }}"
                autocomplete="username"
                required
              />
              <i class="fas fa-check valid-icon" id="unIcon"></i>
            </div>
            @error('username')
              <div class="field-error"><i class="fas fa-times-circle"></i> {{ $message }}</div>
            @enderror
            <div class="field-hint"><i class="fas fa-info-circle"></i> Only letters, numbers, underscores. Min 3 chars.</div>
          </div>

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
              />
              <i class="fas fa-check valid-icon" id="emailIcon"></i>
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
                placeholder="Create a strong password"
                autocomplete="new-password"
                required
              />
              <button type="button" class="pw-toggle" id="togglePw">
                <i class="fas fa-eye-slash" id="pwEyeIcon"></i>
              </button>
            </div>
            @error('password')
              <div class="field-error"><i class="fas fa-times-circle"></i> {{ $message }}</div>
            @enderror

            {{-- Strength meter --}}
            <div class="pw-strength-wrap" id="pwStrengthWrap">
              <div class="pw-strength-bars">
                <div class="pw-bar" id="bar1"></div>
                <div class="pw-bar" id="bar2"></div>
                <div class="pw-bar" id="bar3"></div>
                <div class="pw-bar" id="bar4"></div>
              </div>
              <div class="pw-strength-row">
                <span class="pw-strength-label" id="strengthLabel">Enter password</span>
              </div>
              <div class="pw-rules">
                <span class="pw-rule" id="rule-len"><i class="fas fa-circle"></i> Min 8 chars</span>
                <span class="pw-rule" id="rule-upper"><i class="fas fa-circle"></i> Uppercase</span>
                <span class="pw-rule" id="rule-num"><i class="fas fa-circle"></i> Number</span>
                <span class="pw-rule" id="rule-special"><i class="fas fa-circle"></i> Special char</span>
              </div>
            </div>
          </div>

          {{-- Confirm Password --}}
          <div class="auth-form-group">
            <label class="auth-label" for="password_confirmation">
              <i class="fas fa-lock"></i> Confirm Password
            </label>
            <div class="auth-input-wrap">
              <i class="auth-input-icon fas fa-lock"></i>
              <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                class="auth-input"
                placeholder="Re-enter your password"
                autocomplete="new-password"
                required
              />
              <button type="button" class="pw-toggle" id="toggleConfirmPw">
                <i class="fas fa-eye-slash" id="confirmEyeIcon"></i>
              </button>
              <i class="fas fa-check valid-icon" id="confirmPwIcon"></i>
            </div>
            <div class="field-hint" id="confirmHint" style="display:none;">
              <i class="fas fa-check-circle" style="color:var(--success);"></i>
              Passwords match
            </div>
          </div>

          {{-- Role select --}}
          <div class="auth-form-group">
            <label class="auth-label" for="role">
              <i class="fas fa-user-tag"></i> Account Type
            </label>
            <div class="auth-input-wrap">
              <i class="auth-input-icon fas fa-user-tag"></i>
              <select name="role" id="role" class="auth-input" style="padding-left:40px;cursor:pointer;">
                <option value="author" {{ old('role') == 'author' ? 'selected':'' }}>✍️ Author — Write &amp; publish blog posts</option>
                <option value="editor" {{ old('role') == 'editor' ? 'selected':'' }}>📝 Editor — Review and manage content</option>
                <option value="admin"  {{ old('role') == 'admin'  ? 'selected':'' }}>⚙️ Admin — Full system access</option>
              </select>
            </div>
          </div>

          {{-- Terms --}}
          <div class="auth-form-group">
            <label class="auth-check" id="termsLabel">
              <input type="checkbox" name="terms" id="terms" required {{ old('terms') ? 'checked':'' }}/>
              <span class="check-box"><i class="fas fa-check"></i></span>
              I agree to the <a href="/terms" target="_blank">Terms of Service</a> and <a href="/privacy" target="_blank">Privacy Policy</a>
            </label>
            @error('terms')
              <div class="field-error" style="margin-top:6px;"><i class="fas fa-times-circle"></i> {{ $message }}</div>
            @enderror
          </div>

          {{-- Newsletter opt-in --}}
          <div class="auth-form-group" style="margin-bottom:22px;">
            <label class="auth-check">
              <input type="checkbox" name="newsletter" id="newsletter" {{ old('newsletter') ? 'checked':'' }}/>
              <span class="check-box"><i class="fas fa-check"></i></span>
              Send me tips, updates and product news (optional)
            </label>
          </div>

          {{-- Submit --}}
          <button type="submit" class="btn-auth btn-auth-primary" id="registerBtn">
            <i class="fas fa-user-plus"></i>
            <span id="registerBtnText">Create My Account</span>
          </button>

        </form>
      </div>

      <div style="text-align:center;margin-top:20px;font-size:.8rem;color:var(--muted);">
        Already have an account? <a href="{{ route('login') }}" style="color:var(--primary);font-weight:700;text-decoration:none;">Sign in →</a>
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
  var saved = localStorage.getItem('theme');
  if (saved) { isDark = saved === 'dark'; applyTheme(isDark); }

  $('#themeToggle').on('click', function () {
    isDark = !isDark;
    applyTheme(isDark);
  });

  /* ── Step indicator ── */
  function updateSteps(step) {
    for (var i = 1; i <= 3; i++) {
      var $dot = $('#step' + i + 'dot');
      $dot.removeClass('active done');
      if (i < step) $dot.addClass('done').html('<i class="fas fa-check" style="font-size:.65rem;"></i>');
      else if (i === step) $dot.addClass('active').text(i);
      else $dot.text(i);
    }
    $('#line1').toggleClass('done', step > 1);
    $('#line2').toggleClass('done', step > 2);
  }

  // track which fields are filled to advance steps
  function recalcStep() {
    var fn = $('#first_name').val().trim().length > 0;
    var ln = $('#last_name').val().trim().length > 0;
    var un = $('#username').val().trim().length >= 3;
    var em = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test($('#email').val().trim());
    var pw = $('#password').val().length >= 8;
    var cf = $('#password_confirmation').val() === $('#password').val() && pw;

    if (fn && ln && un && em && pw && cf) updateSteps(3);
    else if (fn && ln && un) updateSteps(2);
    else updateSteps(1);
  }

  /* ── Password toggle ── */
  $('#togglePw').on('click', function () {
    var $inp = $('#password');
    var isText = $inp.attr('type') === 'text';
    $inp.attr('type', isText ? 'password' : 'text');
    $('#pwEyeIcon').toggleClass('fa-eye-slash', !isText).toggleClass('fa-eye', isText);
  });
  $('#toggleConfirmPw').on('click', function () {
    var $inp = $('#password_confirmation');
    var isText = $inp.attr('type') === 'text';
    $inp.attr('type', isText ? 'password' : 'text');
    $('#confirmEyeIcon').toggleClass('fa-eye-slash', !isText).toggleClass('fa-eye', isText);
  });

  /* ── Password strength ── */
  $('#password').on('input', function () {
    var val = $(this).val();
    $('#pwStrengthWrap').show();

    var hasLen     = val.length >= 8;
    var hasUpper   = /[A-Z]/.test(val);
    var hasNum     = /[0-9]/.test(val);
    var hasSpecial = /[^A-Za-z0-9]/.test(val);

    var score = [hasLen, hasUpper, hasNum, hasSpecial].filter(Boolean).length;

    var colors = ['', '#ff4d6d', '#ff4d6d', '#ffb830', '#00c896'];
    var labels = ['', 'Very Weak', 'Weak', 'Good', 'Strong'];

    // Reset bars
    ['bar1','bar2','bar3','bar4'].forEach(function (id, idx) {
      $('#' + id).css('background', idx < score ? colors[score] : 'var(--border)');
    });
    $('#strengthLabel').text(score > 0 ? labels[score] : 'Enter password')
                       .css('color', score > 0 ? colors[score] : 'var(--muted)');

    // Rules
    setRule('rule-len',     hasLen);
    setRule('rule-upper',   hasUpper);
    setRule('rule-num',     hasNum);
    setRule('rule-special', hasSpecial);

    recalcStep();
    checkConfirm();
  });

  function setRule(id, ok) {
    var $el = $('#' + id);
    $el.toggleClass('ok', ok);
    $el.find('i').removeClass('fa-circle fa-check-circle')
                 .addClass(ok ? 'fa-check-circle' : 'fa-circle');
  }

  /* ── Confirm password match ── */
  function checkConfirm() {
    var pw = $('#password').val();
    var cf = $('#password_confirmation').val();
    if (!cf) { $('#confirmHint').hide(); $('#confirmPwIcon').removeClass('show-valid show-invalid'); return; }
    var match = pw === cf && pw.length >= 8;
    $('#confirmHint').toggle(match);
    $('#confirmPwIcon').removeClass('show-valid show-invalid').addClass(match ? 'show-valid' : 'show-invalid');
    $('#password_confirmation').removeClass('is-valid is-invalid').addClass(match ? 'is-valid' : 'is-invalid');
    recalcStep();
  }
  $('#password_confirmation').on('input', checkConfirm);

  /* ── Live field validation ── */
  function validateField($inp, $icon, isValidFn) {
    $inp.on('blur input', function () {
      var ok = isValidFn($(this).val());
      $(this).removeClass('is-valid is-invalid').addClass(ok ? 'is-valid' : ($(this).val() ? 'is-invalid' : ''));
      $icon.removeClass('show-valid show-invalid').addClass(ok ? 'show-valid' : ($(this).val() ? 'show-invalid' : ''));
    });
  }

  validateField($('#first_name'), $('#fnIcon'), function (v) { return v.trim().length >= 2; });
  validateField($('#last_name'), $('#lnIcon'), function (v) { return v.trim().length >= 2; });
  validateField($('#username'), $('#unIcon'), function (v) { return /^[a-zA-Z0-9_]{3,}$/.test(v); });
  validateField($('#email'), $('#emailIcon'), function (v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); });

  $('#first_name, #last_name, #username, #email').on('input', recalcStep);

  /* ── Custom checkbox ── */
  $(document).on('click', '.auth-check', function (e) {
    if ($(e.target).is('a')) return;
    var $inp = $(this).find('input[type="checkbox"]');
    $inp.prop('checked', !$inp.prop('checked'));
    $(this).find('.check-box i').toggle($inp.prop('checked'));
  });

  /* ── Submit loading state ── */
  $('#registerForm').on('submit', function () {
    var $btn = $('#registerBtn');
    $('#registerBtnText').text('Creating account…');
    $btn.prop('disabled', true).css('opacity', '.75');
    $btn.find('i').removeClass('fa-user-plus').addClass('fa-spinner fa-spin');
  });

  /* ── Focus icon colour ── */
  $('.auth-input').on('focus', function () {
    $(this).siblings('.auth-input-icon').css('color', 'var(--primary)');
  }).on('blur', function () {
    $(this).siblings('.auth-input-icon').css('color', 'var(--muted)');
  });

  // Init step
  updateSteps(1);
});
</script>

</body>
</html>