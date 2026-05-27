{{-- resources/views/roles/mail/invitation.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>You're Invited | Kawach Technology</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>

    /* ── Reset ──────────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body, html { margin: 0; padding: 0; width: 100% !important; -webkit-text-size-adjust: 100%; }
    body {
      font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
      background-color: #0a1628;
      color: #1e293b;
    }
    img { border: 0; outline: none; text-decoration: none; display: block; }
    a { text-decoration: none; }

    /* ── Outer wrapper ─────────────────────────────────────── */
    .email-bg {
      width: 100%;
      background: linear-gradient(160deg, #0a1628 0%, #0d1b3e 50%, #0a1628 100%);
      padding: 48px 16px 56px;
    }

    /* ── Card ──────────────────────────────────────────────── */
    .card {
      max-width: 620px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45);
    }

    /* ── Header ────────────────────────────────────────────── */
    .header {
      background: linear-gradient(135deg, #0d1b3e 0%, #112d5e 55%, #163a78 100%);
      padding: 0;
      position: relative;
      overflow: hidden;
    }

    /* Circuit grid overlay */
    .header::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(rgba(26,115,232,.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(26,115,232,.06) 1px, transparent 1px);
      background-size: 36px 36px;
      pointer-events: none;
    }

    /* Glow orb */
    .header::after {
      content: '';
      position: absolute;
      top: -60px; right: -60px;
      width: 260px; height: 260px;
      background: radial-gradient(circle, rgba(26,115,232,.22) 0%, transparent 70%);
      pointer-events: none;
    }

    .header-inner {
      position: relative;
      z-index: 1;
      padding: 44px 44px 36px;
      text-align: center;
    }

    /* Logo row */
    .logo-row {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-bottom: 36px;
    }
    .logo-shield {
      width: 42px; height: 42px;
      background: linear-gradient(135deg, #1a73e8, #0d5cbf);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      box-shadow: 0 4px 14px rgba(26,115,232,.45);
      font-size: 20px;
      flex-shrink: 0;
    }
    .logo-text-wrap { text-align: left; }
    .logo-name {
      font-size: 17px;
      font-weight: 800;
      color: #ffffff;
      letter-spacing: 2.5px;
      text-transform: uppercase;
      line-height: 1.1;
    }
    .logo-sub {
      font-size: 9px;
      font-weight: 500;
      color: #1a73e8;
      letter-spacing: 3px;
      text-transform: uppercase;
    }

    /* Badge */
    .invite-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(26,115,232,.18);
      border: 1px solid rgba(26,115,232,.4);
      border-radius: 30px;
      padding: 6px 18px;
      font-size: 11px;
      font-weight: 700;
      color: #7ec8f7;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      margin-bottom: 22px;
    }
    .badge-dot {
      width: 7px; height: 7px;
      background: #1a73e8;
      border-radius: 50%;
      box-shadow: 0 0 6px #1a73e8;
    }

    /* Shield icon */
    .shield-wrap {
      width: 88px; height: 88px;
      background: linear-gradient(135deg, rgba(26,115,232,.2), rgba(26,115,232,.08));
      border: 2px solid rgba(26,115,232,.35);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 22px;
      font-size: 36px;
    }

    .header-title {
      font-size: 28px;
      font-weight: 800;
      color: #ffffff;
      line-height: 1.25;
      margin-bottom: 10px;
      letter-spacing: -0.3px;
    }
    .header-title span { color: #1a73e8; }

    .header-sub {
      font-size: 14px;
      font-weight: 400;
      color: #aac4e0;
      line-height: 1.65;
      max-width: 420px;
      margin: 0 auto;
    }
    .header-sub strong { color: #7ec8f7; font-weight: 600; }

    /* Role pill */
    .role-pill {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: rgba(26,115,232,.18);
      border: 1px solid rgba(26,115,232,.4);
      border-radius: 30px;
      padding: 7px 20px;
      font-size: 12px;
      font-weight: 700;
      color: #7ec8f7;
      margin-top: 20px;
      letter-spacing: 0.5px;
    }

    /* ── Stats strip ────────────────────────────────────────── */
    .stats-strip {
      background: linear-gradient(90deg, #112048, #0f1c42, #112048);
      border-top: 1px solid rgba(26,115,232,.2);
      border-bottom: 1px solid rgba(26,115,232,.2);
      padding: 18px 40px;
      display: flex;
      justify-content: space-around;
      align-items: center;
      gap: 8px;
    }
    .stat-item { text-align: center; }
    .stat-value {
      font-size: 17px;
      font-weight: 800;
      color: #1a73e8;
      line-height: 1;
      margin-bottom: 3px;
    }
    .stat-label {
      font-size: 9px;
      font-weight: 600;
      color: #6b8aaa;
      text-transform: uppercase;
      letter-spacing: 0.8px;
    }
    .stat-divider {
      width: 1px;
      height: 30px;
      background: rgba(26,115,232,.2);
    }

    /* ── Body ───────────────────────────────────────────────── */
    .body { padding: 40px 44px; }

    /* Greeting */
    .greeting {
      font-size: 15px;
      color: #334155;
      line-height: 1.75;
      margin-bottom: 28px;
    }
    .greeting strong { color: #0d1b3e; font-weight: 700; }

    /* Personal message card */
    .msg-card {
      background: #f0f6ff;
      border-left: 4px solid #1a73e8;
      border-radius: 0 10px 10px 0;
      padding: 16px 20px;
      margin-bottom: 28px;
    }
    .msg-card-label {
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.2px;
      color: #1a73e8;
      margin-bottom: 7px;
    }
    .msg-card-text {
      font-size: 14px;
      color: #334155;
      line-height: 1.65;
      font-style: italic;
    }

    /* What you'll get section */
    .section-label {
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: #1a73e8;
      padding-bottom: 10px;
      border-bottom: 2px solid #e8f1fd;
      margin-bottom: 18px;
    }

    .perks-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      margin-bottom: 32px;
    }
    .perk-item {
      background: #f8fafd;
      border: 1px solid #e2ecf8;
      border-radius: 12px;
      padding: 16px 16px;
      display: flex;
      align-items: flex-start;
      gap: 12px;
    }
    .perk-icon {
      width: 36px; height: 36px;
      background: linear-gradient(135deg, #1a73e8, #0d5cbf);
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      font-size: 16px;
      flex-shrink: 0;
    }
    .perk-title {
      font-size: 12px;
      font-weight: 700;
      color: #0d1b3e;
      margin-bottom: 3px;
    }
    .perk-sub {
      font-size: 11px;
      color: #64748b;
      line-height: 1.45;
    }

    /* Expiry notice */
    .expiry-notice {
      background: #fff8e8;
      border: 1px solid #fde58a;
      border-radius: 10px;
      padding: 13px 16px;
      display: flex;
      align-items: center;
      gap: 11px;
      margin-bottom: 30px;
      font-size: 13px;
      color: #7a5c00;
    }
    .expiry-icon {
      font-size: 18px;
      flex-shrink: 0;
    }
    .expiry-notice strong { color: #5a4200; }

    /* ── CTA button ─────────────────────────────────────────── */
    .cta-wrap {
      text-align: center;
      margin-bottom: 28px;
    }
    .cta-btn {
      display: inline-block;
      background: linear-gradient(135deg, #1a73e8 0%, #0d5cbf 100%);
      color: #ffffff !important;
      font-family: 'Poppins', Arial, sans-serif;
      font-size: 15px;
      font-weight: 700;
      letter-spacing: 0.4px;
      padding: 16px 48px;
      border-radius: 12px;
      text-decoration: none;
      box-shadow: 0 8px 24px rgba(26,115,232,.38);
      transition: all .2s;
    }
    .cta-sub {
      font-size: 12px;
      color: #94a3b8;
      margin-top: 10px;
      text-align: center;
    }

    /* Fallback URL */
    .fallback-wrap {
      background: #f8fafd;
      border: 1px solid #e2ecf8;
      border-radius: 10px;
      padding: 14px 18px;
      margin-bottom: 28px;
    }
    .fallback-label {
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: #64748b;
      margin-bottom: 7px;
    }
    .fallback-url {
      font-size: 11px;
      color: #1a73e8;
      word-break: break-all;
      line-height: 1.5;
    }

    /* Closing */
    .closing {
      font-size: 14px;
      color: #475569;
      line-height: 1.7;
      margin-bottom: 20px;
    }
    .sign-name {
      font-size: 14px;
      font-weight: 700;
      color: #0d1b3e;
    }
    .sign-title { font-size: 12px; color: #64748b; margin-top: 2px; }

    .divider { border: none; border-top: 1px solid #e8edf5; margin: 28px 0; }

    /* Security note */
    .security-note {
      background: #f0f6ff;
      border: 1px solid #cce0fb;
      border-radius: 10px;
      padding: 13px 16px;
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 12px;
      color: #334155;
      line-height: 1.55;
    }
    .security-note-icon { font-size: 16px; flex-shrink: 0; margin-top: 1px; }

    /* ── Footer ─────────────────────────────────────────────── */
    .footer {
      background: linear-gradient(135deg, #0d1b3e 0%, #0a1628 100%);
      padding: 32px 44px;
      text-align: center;
      border-top: 1px solid rgba(26,115,232,.15);
    }
    .footer-logo-row {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 9px;
      margin-bottom: 12px;
    }
    .footer-shield {
      width: 30px; height: 30px;
      background: linear-gradient(135deg, #1a73e8, #0d5cbf);
      border-radius: 7px;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px;
    }
    .footer-brand {
      font-size: 14px;
      font-weight: 800;
      color: #ffffff;
      letter-spacing: 2px;
      text-transform: uppercase;
    }
    .footer-brand span { color: #1a73e8; }
    .footer-tagline {
      font-size: 11px;
      color: #4a6080;
      line-height: 1.6;
      margin-bottom: 16px;
      max-width: 360px;
      margin-left: auto;
      margin-right: auto;
    }
    .footer-links {
      margin-bottom: 16px;
    }
    .footer-links a {
      color: #4a6080;
      font-size: 11px;
      text-decoration: none;
      margin: 0 8px;
      transition: color .2s;
    }
    .footer-links a:hover { color: #1a73e8; }
    .footer-copyright {
      font-size: 10px;
      color: #2d4060;
      padding-top: 14px;
      border-top: 1px solid rgba(255,255,255,.05);
    }

    /* ── Responsive ─────────────────────────────────────────── */
    @media (max-width: 600px) {
      .email-bg { padding: 24px 12px 36px; }
      .header-inner { padding: 32px 24px 28px; }
      .body { padding: 28px 24px; }
      .footer { padding: 24px 20px; }
      .stats-strip { padding: 16px 20px; }
      .header-title { font-size: 22px; }
      .perks-grid { grid-template-columns: 1fr; }
      .cta-btn { padding: 14px 28px; font-size: 14px; }
      .logo-name { font-size: 14px; }
    }
  </style>
</head>
<body>
<div class="email-bg">
  <div class="card">

    {{-- ══ HEADER ══════════════════════════════════════════════ --}}
    <div class="header">
      <div class="header-inner">

        {{-- Logo --}}
        <div class="logo-row">
          <div class="logo-shield">&#x1F6E1;</div>
          <div class="logo-text-wrap">
            <div class="logo-name">Kawach</div>
            <div class="logo-sub">Technology</div>
          </div>
        </div>

        {{-- Badge --}}
        <div class="invite-badge">
          <span class="badge-dot"></span>
          You've Been Invited
        </div>

        {{-- Shield icon --}}
        <div class="shield-wrap">&#x1F6E1;</div>

        {{-- Title --}}
        <div class="header-title">
          Welcome to<br><span>Kawach Technology</span>
        </div>

        <div class="header-sub">
          @if($invitation->first_name)
            Hi <strong>{{ $invitation->first_name }}</strong>, you've been personally invited to join our
            secure digital infrastructure team.
          @else
            You've been personally invited to join our secure digital infrastructure team.
          @endif
        </div>

        {{-- Role pill --}}
        <div>
          <span class="role-pill">
            &#x1F464;&nbsp; Joining as: <strong style="color:#fff;">{{ ucfirst($invitation->role_name) }}</strong>
          </span>
        </div>

      </div>
    </div>{{-- /header --}}

    {{-- ══ STATS STRIP ════════════════════════════════════════ --}}
    <div class="stats-strip">
      <div class="stat-item">
        <div class="stat-value">200+</div>
        <div class="stat-label">Enterprise Clients</div>
      </div>
      <div class="stat-divider"></div>
      <div class="stat-item">
        <div class="stat-value">99.9%</div>
        <div class="stat-label">Uptime SLA</div>
      </div>
      <div class="stat-divider"></div>
      <div class="stat-item">
        <div class="stat-value">50+</div>
        <div class="stat-label">Expert Engineers</div>
      </div>
      <div class="stat-divider"></div>
      <div class="stat-item">
        <div class="stat-value">10+</div>
        <div class="stat-label">Countries Served</div>
      </div>
    </div>

    {{-- ══ BODY ════════════════════════════════════════════════ --}}
    <div class="body">

      {{-- Greeting --}}
      <p class="greeting">
        Hi <strong>{{ $invitation->first_name ?? 'there' }}</strong>,<br><br>
        You have been selected to join <strong>Kawach Technology Private Limited</strong> — a
        next-generation software development company building secure, scalable, and intelligent
        digital infrastructure for enterprises across the globe.<br><br>
        Click the button below to accept your invitation and set up your account. It only takes
        a minute.
      </p>

      {{-- Personal message (if provided) --}}
      @if($invitation->message)
        <div class="msg-card">
          <div class="msg-card-label">&#x1F4AC; Personal message from your team</div>
          <div class="msg-card-text">"{{ $invitation->message }}"</div>
        </div>
      @endif

      {{-- What you'll get --}}
      <div class="section-label">What awaits you at Kawach</div>
      <div class="perks-grid">
        <div class="perk-item">
          <div class="perk-icon">&#x1F6E1;</div>
          <div>
            <div class="perk-title">Security-First Projects</div>
            <div class="perk-sub">Work on enterprise-grade secure systems with top-tier compliance standards.</div>
          </div>
        </div>
        <div class="perk-item">
          <div class="perk-icon">&#x1F310;</div>
          <div>
            <div class="perk-title">Global Reach</div>
            <div class="perk-sub">Deliver solutions for clients across the US, UK, Australia and beyond.</div>
          </div>
        </div>
        <div class="perk-item">
          <div class="perk-icon">&#x2601;</div>
          <div>
            <div class="perk-title">Cutting-Edge Stack</div>
            <div class="perk-sub">Build with AWS, Azure, GCP and modern microservice architectures.</div>
          </div>
        </div>
        <div class="perk-item">
          <div class="perk-icon">&#x1F9E0;</div>
          <div>
            <div class="perk-title">AI & Innovation</div>
            <div class="perk-sub">Craft intelligent automation and AI-driven solutions for real-world impact.</div>
          </div>
        </div>
      </div>

      {{-- Expiry notice --}}
      <div class="expiry-notice">
        <span class="expiry-icon">&#x23F0;</span>
        <div>
          This invitation expires on
          <strong>{{ $invitation->expires_at->format('d M Y \a\t H:i') }} UTC</strong>.
          Please accept it before it expires.
        </div>
      </div>

      {{-- ── CTA BUTTON ── --}}
      <div class="cta-wrap">
        <a href="{{ $registerUrl }}" class="cta-btn">
          &#x2714;&nbsp;&nbsp;Accept Invitation &amp; Register
        </a>
        <div class="cta-sub">No existing account required &mdash; you'll set your password on the next screen.</div>
      </div>

      {{-- Fallback URL --}}
      <div class="fallback-wrap">
        <div class="fallback-label">&#x1F517; Or copy this link into your browser</div>
        <div class="fallback-url">{{ $registerUrl }}</div>
      </div>

      {{-- Closing --}}
      <p class="closing">
        We're excited to have you on board. If you have any questions before accepting,
        reply to this email or reach out to us at
        <a href="mailto:{{ config('mail.admin_email', 'hr@kawachtech.com') }}"
           style="color:#1a73e8;font-weight:600;">
          {{ config('mail.admin_email', 'hr@kawachtech.com') }}
        </a>.
      </p>
      <div class="sign-name">The Kawach Technology Team</div>
      <div class="sign-title">Kawach Technology Private Limited &bull; <a href="https://www.kawachtech.com" style="color:#1a73e8;">kawachtech.com</a></div>

      <hr class="divider">

      {{-- Security note --}}
      <div class="security-note">
        <span class="security-note-icon">&#x1F512;</span>
        <div>
          <strong style="color:#0d1b3e;">Security notice:</strong>
          This invitation link is unique to you and valid for one use only. If you did not expect
          this invitation, please ignore this email — your account will not be created. Never share
          this link with anyone.
        </div>
      </div>

    </div>{{-- /body --}}

    {{-- ══ FOOTER ═════════════════════════════════════════════ --}}
    <div class="footer">
      <div class="footer-logo-row">
        <div class="footer-shield">&#x1F6E1;</div>
        <div class="footer-brand">Kawach<span>.</span>Tech</div>
      </div>
      <div class="footer-tagline">
        Empowering Businesses with Custom Software Solutions<br>and Trusted IT Security Services.
      </div>
      <div class="footer-links">
        <a href="https://www.kawachtech.com">Website</a>
        <a href="https://www.kawachtech.com/services">Services</a>
        <a href="https://www.kawachtech.com/careers">Careers</a>
        <a href="mailto:{{ config('mail.admin_email', 'hr@kawachtech.com') }}">Contact</a>
      </div>
      <div class="footer-copyright">
        &copy; {{ date('Y') }} Kawach Technology Private Limited. All rights reserved.<br>
        Uttar Pradesh, India &bull; Global Operations
      </div>
    </div>

  </div>{{-- /card --}}
</div>{{-- /email-bg --}}
</body>
</html>