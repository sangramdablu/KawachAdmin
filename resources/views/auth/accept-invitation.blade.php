{{-- resources/views/auth/accept-invitation.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accept Invitation | Kawach Technology</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --navy:        #0d1b3e;
      --navy-light:  #112d5e;
      --blue:        #1a73e8;
      --blue-dark:   #0d5cbf;
      --blue-soft:   #e8f1fd;
      --text-dark:   #1e293b;
      --text-muted:  #64748b;
      --border:      #e2ecf8;
      --success:     #4caf50;
      --danger:      #ef4444;
      --white:       #ffffff;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
      background: linear-gradient(160deg, #0a1628 0%, #0d1b3e 50%, #0a1628 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 32px 16px;
    }

    /* ── Circuit grid background ── */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(rgba(26,115,232,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(26,115,232,.04) 1px, transparent 1px);
      background-size: 40px 40px;
      pointer-events: none;
      z-index: 0;
    }

    /* ── Logo ── */
    .logo-wrap {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 28px;
      position: relative;
      z-index: 1;
    }
    .logo-shield {
      width: 44px; height: 44px;
      background: linear-gradient(135deg, var(--blue), var(--blue-dark));
      border-radius: 11px;
      display: flex; align-items: center; justify-content: center;
      box-shadow: 0 4px 16px rgba(26,115,232,.4);
      font-size: 20px;
    }
    .logo-name {
      font-size: 18px;
      font-weight: 800;
      color: var(--white);
      letter-spacing: 2.5px;
      text-transform: uppercase;
      line-height: 1.1;
    }
    .logo-sub {
      font-size: 9px;
      font-weight: 500;
      color: var(--blue);
      letter-spacing: 3px;
      text-transform: uppercase;
    }

    /* ── Card ── */
    .card {
      width: 100%;
      max-width: 520px;
      background: var(--white);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 28px 64px rgba(0,0,0,.45);
      position: relative;
      z-index: 1;
    }

    /* ── Card header ── */
    .card-header-custom {
      background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
      padding: 30px 36px 26px;
      position: relative;
      overflow: hidden;
    }
    .card-header-custom::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(rgba(26,115,232,.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(26,115,232,.06) 1px, transparent 1px);
      background-size: 28px 28px;
    }
    .card-header-custom::after {
      content: '';
      position: absolute;
      top: -50px; right: -50px;
      width: 180px; height: 180px;
      background: radial-gradient(circle, rgba(26,115,232,.18) 0%, transparent 70%);
    }
    .ch-badge {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: rgba(76,175,80,.18);
      border: 1px solid rgba(76,175,80,.4);
      border-radius: 20px;
      padding: 4px 14px;
      font-size: 10px;
      font-weight: 700;
      color: #86efac;
      letter-spacing: 1px;
      text-transform: uppercase;
      margin-bottom: 12px;
      position: relative;
      z-index: 1;
    }
    .ch-badge-dot {
      width: 6px; height: 6px;
      background: var(--success);
      border-radius: 50%;
      box-shadow: 0 0 5px var(--success);
    }
    .ch-title {
      font-size: 20px;
      font-weight: 800;
      color: var(--white);
      margin-bottom: 6px;
      position: relative;
      z-index: 1;
    }
    .ch-sub {
      font-size: 13px;
      color: #aac4e0;
      position: relative;
      z-index: 1;
    }
    .ch-sub strong { color: #7ec8f7; }

    /* ── Invitation info strip ── */
    .inv-strip {
      background: var(--blue-soft);
      border-bottom: 1px solid var(--border);
      padding: 14px 36px;
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
      align-items: center;
    }
    .inv-item {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      color: var(--text-dark);
    }
    .inv-item-icon {
      width: 28px; height: 28px;
      background: linear-gradient(135deg, var(--blue), var(--blue-dark));
      border-radius: 7px;
      display: flex; align-items: center; justify-content: center;
      font-size: 12px;
      color: var(--white);
      flex-shrink: 0;
    }
    .inv-item-label { font-size: 10px; color: var(--text-muted); font-weight: 600; }
    .inv-item-val { font-size: 13px; font-weight: 700; color: var(--text-dark); }

    /* ── Form ── */
    .form-wrap { padding: 30px 36px 34px; }

    .form-label-custom {
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      color: var(--text-dark);
      margin-bottom: 7px;
      display: block;
    }

    .input-group-custom {
      position: relative;
      margin-bottom: 18px;
    }
    .input-icon {
      position: absolute;
      left: 14px; top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 14px;
      pointer-events: none;
    }
    .form-input {
      width: 100%;
      padding: 11px 14px 11px 40px;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      font-family: 'Poppins', Arial, sans-serif;
      font-size: 14px;
      color: var(--text-dark);
      background: #fafbfd;
      transition: border-color .2s, box-shadow .2s;
      outline: none;
    }
    .form-input:focus {
      border-color: var(--blue);
      box-shadow: 0 0 0 3px rgba(26,115,232,.12);
      background: var(--white);
    }
    .form-input.is-invalid { border-color: var(--danger); }
    .form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.12); }

    /* Read-only email */
    .form-input[readonly] {
      background: #f0f6ff;
      color: var(--text-muted);
      cursor: not-allowed;
      border-color: var(--border);
    }
    .readonly-note {
      font-size: 11px;
      color: var(--text-muted);
      margin-top: 5px;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    /* Password toggle */
    .pwd-toggle {
      position: absolute;
      right: 14px; top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      color: #94a3b8;
      font-size: 14px;
      padding: 0;
    }
    .pwd-toggle:hover { color: var(--blue); }

    /* Password strength bar */
    .strength-wrap { margin-top: 8px; }
    .strength-bar-bg {
      height: 4px;
      background: var(--border);
      border-radius: 4px;
      overflow: hidden;
      margin-bottom: 5px;
    }
    .strength-bar {
      height: 100%;
      border-radius: 4px;
      width: 0%;
      transition: width .3s, background .3s;
    }
    .strength-label {
      font-size: 11px;
      font-weight: 600;
      color: var(--text-muted);
    }

    /* Password requirements */
    .pwd-reqs {
      background: #f8fafd;
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 12px 14px;
      margin-bottom: 18px;
    }
    .pwd-reqs-title {
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: var(--text-muted);
      margin-bottom: 8px;
    }
    .req-list {
      list-style: none;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 5px 10px;
    }
    .req-item {
      font-size: 11px;
      color: #94a3b8;
      display: flex;
      align-items: center;
      gap: 6px;
      transition: color .2s;
    }
    .req-item.met { color: var(--success); }
    .req-item .req-icon { font-size: 10px; }

    /* Error messages */
    .field-error {
      font-size: 12px;
      color: var(--danger);
      margin-top: 5px;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    .alert-error {
      background: #fef2f2;
      border: 1px solid #fecaca;
      border-radius: 10px;
      padding: 12px 16px;
      font-size: 13px;
      color: #991b1b;
      margin-bottom: 18px;
      display: flex;
      align-items: flex-start;
      gap: 10px;
    }

    /* Submit button */
    .btn-submit {
      width: 100%;
      background: linear-gradient(135deg, var(--blue) 0%, var(--blue-dark) 100%);
      color: var(--white);
      border: none;
      border-radius: 11px;
      padding: 14px;
      font-family: 'Poppins', Arial, sans-serif;
      font-size: 15px;
      font-weight: 700;
      letter-spacing: 0.3px;
      cursor: pointer;
      box-shadow: 0 6px 20px rgba(26,115,232,.35);
      transition: opacity .2s, transform .15s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 9px;
      margin-top: 8px;
    }
    .btn-submit:hover  { opacity: .9; transform: translateY(-1px); }
    .btn-submit:active { transform: translateY(0); }
    .btn-submit:disabled { opacity: .6; cursor: not-allowed; transform: none; }

    /* Security note */
    .security-note {
      display: flex;
      align-items: flex-start;
      gap: 8px;
      margin-top: 18px;
      font-size: 11px;
      color: var(--text-muted);
      line-height: 1.55;
    }
    .security-note i { color: var(--success); margin-top: 2px; flex-shrink: 0; }

    /* Footer note */
    .footer-note {
      text-align: center;
      font-size: 11px;
      color: rgba(255,255,255,.35);
      margin-top: 20px;
      position: relative;
      z-index: 1;
    }

    /* Responsive */
    @media (max-width: 576px) {
      .card-header-custom, .form-wrap { padding-left: 22px; padding-right: 22px; }
      .inv-strip { padding: 12px 22px; }
      .req-list { grid-template-columns: 1fr; }
      .ch-title { font-size: 18px; }
    }
  </style>
</head>
<body>

  {{-- Logo --}}
  <div class="logo-wrap">
    <div class="logo-shield">&#x1F6E1;</div>
    <div>
      <div class="logo-name">Kawach</div>
      <div class="logo-sub">Technology</div>
    </div>
  </div>

  <div class="card">

    {{-- Card header --}}
    <div class="card-header-custom">
      <div class="ch-badge">
        <span class="ch-badge-dot"></span>
        Invitation Valid
      </div>
      <div class="ch-title">Accept Your Invitation</div>
      <div class="ch-sub">
        Set your password to activate your
        <strong>{{ ucfirst($invitation->role_name) }}</strong> account.
      </div>
    </div>

    {{-- Invitation info strip --}}
    <div class="inv-strip">
      <div class="inv-item">
        <div class="inv-item-icon">&#x2709;</div>
        <div>
          <div class="inv-item-label">Invited Email</div>
          <div class="inv-item-val">{{ $invitation->email }}</div>
        </div>
      </div>
      <div class="inv-item">
        <div class="inv-item-icon">&#x1F464;</div>
        <div>
          <div class="inv-item-label">Assigned Role</div>
          <div class="inv-item-val">{{ ucfirst($invitation->role_name) }}</div>
        </div>
      </div>
      <div class="inv-item">
        <div class="inv-item-icon">&#x23F0;</div>
        <div>
          <div class="inv-item-label">Expires</div>
          <div class="inv-item-val">{{ $invitation->expires_at->format('d M Y') }}</div>
        </div>
      </div>
    </div>

    {{-- Form --}}
    <div class="form-wrap">

      {{-- General errors --}}
      @if($errors->has('general'))
        <div class="alert-error">
          <i class="fas fa-exclamation-circle"></i>
          {{ $errors->first('general') }}
        </div>
      @endif

        <form method="POST" action="{{ route('invitation.register', $invitation->token) }}" id="acceptForm" novalidate>
          @csrf

        {{-- Token (hidden) --}}
        <input type="hidden" name="token" value="{{ $invitation->token }}">

        {{-- Full Name --}}
        <div>
          <label class="form-label-custom" for="name">Full Name *</label>
          <div class="input-group-custom">
            <i class="fas fa-user input-icon"></i>
            <input type="text"
                   id="name"
                   name="name"
                   class="form-input @error('name') is-invalid @enderror"
                   placeholder="John Smith"
                   value="{{ old('name', $invitation->full_name ?? '') }}"
                   maxlength="100"
                   required>
          </div>
          @error('name')
            <div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
          @enderror
        </div>

        {{-- Email — pre-filled, read-only, NOT a form input so it can't be tampered with --}}
        <div>
          <label class="form-label-custom" for="email_display">Email Address</label>
          <div class="input-group-custom">
            <i class="fas fa-envelope input-icon"></i>
            {{-- Display-only: value comes from the DB via the controller, not this input --}}
            <input type="email"
                   id="email_display"
                   class="form-input"
                   value="{{ $invitation->email }}"
                   readonly
                   tabindex="-1">
          </div>
          <div class="readonly-note">
            <i class="fas fa-lock" style="color:#1a73e8;font-size:10px;"></i>
            This email was set by your invitation and cannot be changed.
          </div>
        </div>

        {{-- Password --}}
        <div style="margin-top:18px;">
          <label class="form-label-custom" for="password">Set Password *</label>
          <div class="input-group-custom" style="margin-bottom:6px;">
            <i class="fas fa-lock input-icon"></i>
            <input type="password"
                   id="password"
                   name="password"
                   class="form-input @error('password') is-invalid @enderror"
                   placeholder="Minimum 8 characters"
                   maxlength="100"
                   required
                   oninput="checkStrength(this.value)">
            <button type="button" class="pwd-toggle" onclick="togglePwd('password', this)" tabindex="-1">
              <i class="fas fa-eye" id="pwd-eye"></i>
            </button>
          </div>

          {{-- Strength bar --}}
          <div class="strength-wrap">
            <div class="strength-bar-bg">
              <div class="strength-bar" id="strengthBar"></div>
            </div>
            <span class="strength-label" id="strengthLabel"></span>
          </div>

          @error('password')
            <div class="field-error" style="margin-top:6px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
          @enderror
        </div>

        {{-- Confirm Password --}}
        <div style="margin-top:18px;">
          <label class="form-label-custom" for="password_confirmation">Confirm Password *</label>
          <div class="input-group-custom">
            <i class="fas fa-lock input-icon"></i>
            <input type="password"
                   id="password_confirmation"
                   name="password_confirmation"
                   class="form-input"
                   placeholder="Re-enter your password"
                   maxlength="100"
                   required>
            <button type="button" class="pwd-toggle" onclick="togglePwd('password_confirmation', this)" tabindex="-1">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>

        {{-- Password requirements --}}
        <div class="pwd-reqs" style="margin-top:14px;">
          <div class="pwd-reqs-title">Password must include</div>
          <ul class="req-list">
            <li class="req-item" id="req-len"><span class="req-icon">&#x25CF;</span> At least 8 characters</li>
            <li class="req-item" id="req-upper"><span class="req-icon">&#x25CF;</span> One uppercase letter</li>
            <li class="req-item" id="req-lower"><span class="req-icon">&#x25CF;</span> One lowercase letter</li>
            <li class="req-item" id="req-num"><span class="req-icon">&#x25CF;</span> One number</li>
            <li class="req-item" id="req-special"><span class="req-icon">&#x25CF;</span> One special character</li>
          </ul>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-submit" id="submitBtn">
          <span id="btnText"><i class="fas fa-check-circle"></i> Accept Invitation &amp; Create Account</span>
          <span id="btnLoading" style="display:none;">
            <i class="fas fa-spinner fa-spin"></i> Creating Account…
          </span>
        </button>

        <div class="security-note">
          <i class="fas fa-shield-alt"></i>
          Your password is encrypted and never stored in plain text. This invitation link is
          single-use and will expire after you register.
        </div>

      </form>
    </div>{{-- /form-wrap --}}
  </div>{{-- /card --}}

  <div class="footer-note">
    &copy; {{ date('Y') }} Kawach Technology Private Limited &bull; Secure Invitation System
  </div>

</body>
<script>
  // ── Password visibility toggle ──────────────────────────────
  function togglePwd(fieldId, btn) {
    const field = document.getElementById(fieldId);
    const icon  = btn.querySelector('i');
    if (field.type === 'password') {
      field.type = 'text';
      icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      field.type = 'password';
      icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
  }

  // ── Password strength + requirements ───────────────────────
  function checkStrength(val) {
    const bar   = document.getElementById('strengthBar');
    const label = document.getElementById('strengthLabel');

    const checks = {
      len:     val.length >= 8,
      upper:   /[A-Z]/.test(val),
      lower:   /[a-z]/.test(val),
      num:     /\d/.test(val),
      special: /[\W_]/.test(val),
    };

    // Update requirement items
    Object.keys(checks).forEach(k => {
      const el = document.getElementById('req-' + k);
      if (el) el.classList.toggle('met', checks[k]);
    });

    const score = Object.values(checks).filter(Boolean).length;

    const levels = [
      { w: '0%',   bg: 'transparent', text: '' },
      { w: '20%',  bg: '#ef4444',     text: 'Very Weak' },
      { w: '40%',  bg: '#f97316',     text: 'Weak' },
      { w: '60%',  bg: '#eab308',     text: 'Fair' },
      { w: '80%',  bg: '#22c55e',     text: 'Strong' },
      { w: '100%', bg: '#16a34a',     text: 'Very Strong' },
    ];

    bar.style.width      = levels[score].w;
    bar.style.background = levels[score].bg;
    label.style.color    = levels[score].bg;
    label.textContent    = levels[score].text;
  }

  // ── Loading state on submit ─────────────────────────────────
  document.getElementById('acceptForm').addEventListener('submit', function () {
    document.getElementById('btnText').style.display    = 'none';
    document.getElementById('btnLoading').style.display = 'inline-flex';
    document.getElementById('submitBtn').disabled       = true;
  });
</script>
</html>