<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Welcome to KawachTech Portal</title>
<style>
  body { margin:0; padding:0; background:#f0f4fb; font-family:'Segoe UI',Arial,sans-serif; color:#1a1a2e; }
  .wrap { max-width:600px; margin:40px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(26,115,232,.12); }

  /* Header */
  .header { background:linear-gradient(135deg,#0d2a57 0%,#1a73e8 55%,#2196f3 100%); padding:40px 40px 32px; text-align:center; position:relative; overflow:hidden; }
  .header::after { content:''; position:absolute; right:-40px; top:-40px; width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,.07); }
  .confetti { font-size:2rem; margin-bottom:10px; display:block; }
  .header h1 { margin:0 0 6px; color:#fff; font-size:1.5rem; font-weight:800; position:relative; z-index:1; }
  .header p  { margin:0; color:rgba(255,255,255,.75); font-size:.88rem; position:relative; z-index:1; }

  /* Body */
  .body { padding:32px 40px; }
  .greeting { font-size:1.05rem; font-weight:700; margin-bottom:10px; }
  .intro { font-size:.88rem; color:#4a5568; line-height:1.7; margin-bottom:22px; }

  /* Partnership banner */
  .partner-banner { background:linear-gradient(135deg,#e8f4fd,#f0eaff); border-radius:12px; padding:18px 22px; margin-bottom:22px; text-align:center; }
  .partner-banner .icon { font-size:2rem; display:block; margin-bottom:6px; }
  .partner-banner h3 { margin:0 0 4px; font-size:1rem; font-weight:800; color:#1a1a2e; }
  .partner-banner p { margin:0; font-size:.82rem; color:#4a5568; }

  /* Project summary */
  .info-box { background:#f0f4fb; border:1.5px solid #e2e8f0; border-radius:12px; padding:18px 22px; margin-bottom:22px; }
  .info-row { display:flex; justify-content:space-between; padding:7px 0; border-bottom:1px solid #e2e8f0; font-size:.85rem; }
  .info-row:last-child { border-bottom:none; }
  .info-lbl { color:#6b7a99; font-weight:600; }
  .info-val { font-weight:800; color:#1a1a2e; }

  /* Credentials */
  .cred-section { margin-bottom:22px; }
  .cred-title { font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.5px; color:#6b7a99; margin-bottom:10px; }
  .cred-box { background:#0f172a; border-radius:12px; padding:20px 24px; }
  .cred-row { display:flex; align-items:center; justify-content:space-between; padding:8px 0; border-bottom:1px solid rgba(255,255,255,.08); }
  .cred-row:last-child { border-bottom:none; }
  .cred-lbl { font-size:.75rem; font-weight:700; color:rgba(255,255,255,.5); text-transform:uppercase; letter-spacing:.4px; }
  .cred-val { font-family:'Courier New',monospace; font-size:.92rem; font-weight:800; color:#60d0a0; letter-spacing:.5px; }

  /* CTA */
  .cta { text-align:center; margin:26px 0; }
  .btn { display:inline-block; background:linear-gradient(135deg,#1a73e8,#2196f3); color:#fff !important; text-decoration:none; padding:16px 40px; border-radius:10px; font-weight:800; font-size:1rem; box-shadow:0 4px 16px rgba(26,115,232,.35); }

  /* Security note */
  .security-box { background:#fff8e1; border-left:4px solid #ffb830; border-radius:6px; padding:14px 18px; font-size:.82rem; color:#5a4000; line-height:1.6; margin-bottom:20px; }

  /* What's next */
  .next-steps { margin-bottom:22px; }
  .next-steps h4 { font-size:.82rem; font-weight:800; text-transform:uppercase; letter-spacing:.5px; color:#6b7a99; margin:0 0 12px; }
  .step { display:flex; gap:12px; margin-bottom:12px; align-items:flex-start; }
  .step-num { width:26px; height:26px; border-radius:50%; background:#1a73e8; color:#fff; font-size:.72rem; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px; }
  .step-body { flex:1; }
  .step-title { font-size:.84rem; font-weight:700; color:#1a1a2e; margin-bottom:2px; }
  .step-sub { font-size:.76rem; color:#6b7a99; line-height:1.4; }

  /* Footer */
  .footer { padding:20px 40px 28px; text-align:center; font-size:.75rem; color:#6b7a99; border-top:1px solid #e2e8f0; }
  .footer a { color:#1a73e8; }
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <span class="confetti">🎉</span>
    <h1>Welcome to KawachTech!</h1>
    <p>Your agreement is signed &amp; your portal is ready.</p>
  </div>

  <div class="body">
    <div class="greeting">Dear {{ $clientUser->name }},</div>
    <p class="intro">
      Thank you for choosing Kawach Technology as your development partner! Your agreement has been
      successfully signed and we're thrilled to begin this journey together. Your dedicated client
      portal is now active — use it to track progress, communicate with your team, and manage invoices.
    </p>

    <div class="partner-banner">
      <span class="icon">🤝</span>
      <h3>Partnership Confirmed</h3>
      <p>You're now a valued KawachTech partner. We're committed to delivering excellence on every milestone.</p>
    </div>

    <div class="info-box">
      <div class="info-row">
        <span class="info-lbl">Agreement #</span>
        <span class="info-val">{{ $agreement->invoice_no }}</span>
      </div>
      <div class="info-row">
        <span class="info-lbl">Project</span>
        <span class="info-val">{{ $agreement->project_name }}</span>
      </div>
      <div class="info-row">
        <span class="info-lbl">Start Date</span>
        <span class="info-val">{{ $agreement->contract_start?->format('M d, Y') }}</span>
      </div>
      <div class="info-row">
        <span class="info-lbl">Duration</span>
        <span class="info-val">{{ $agreement->duration_months }} {{ Str::plural('Month', $agreement->duration_months) }}</span>
      </div>
      <div class="info-row">
        <span class="info-lbl">Your Email</span>
        <span class="info-val">{{ $clientUser->email }}</span>
      </div>
      <div class="info-row">
        <span class="info-lbl">Agreement Signed</span>
        <span class="info-val">{{ $agreement->signed_at?->format('M d, Y h:i A') }}</span>
      </div>
    </div>

    <div class="cred-section">
      <div class="cred-title">🔐 Your Client Portal Login</div>
      <div class="cred-box">
        <div class="cred-row">
          <span class="cred-lbl">Portal URL</span>
          <span class="cred-val">{{ url('/client/portal') }}</span>
        </div>
        <div class="cred-row">
          <span class="cred-lbl">Email</span>
          <span class="cred-val">{{ $clientUser->email }}</span>
        </div>
        <div class="cred-row">
          <span class="cred-lbl">Password</span>
          <span class="cred-val">{{ $plainPassword }}</span>
        </div>
      </div>
    </div>

    <div class="cta">
      <a href="{{ url('/client/portal') }}" class="btn">Login to Your Portal →</a>
    </div>

    <div class="security-box">
      🔒 <strong>Please change your password</strong> after your first login.
      Never share these credentials with anyone. KawachTech staff will never ask for your password.
    </div>

    <div class="next-steps">
      <h4>What happens next?</h4>
      <div class="step">
        <div class="step-num">1</div>
        <div class="step-body">
          <div class="step-title">Onboarding Call</div>
          <div class="step-sub">Your project manager will reach out within 24 hours to schedule your kickoff call.</div>
        </div>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <div class="step-body">
          <div class="step-title">Advance Payment</div>
          <div class="step-sub">The advance invoice is now visible in your portal. Kindly process it to officially begin development.</div>
        </div>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <div class="step-body">
          <div class="step-title">Development Begins</div>
          <div class="step-sub">Once payment is confirmed, your dedicated team is assigned and work begins immediately.</div>
        </div>
      </div>
      <div class="step">
        <div class="step-num">4</div>
        <div class="step-body">
          <div class="step-title">Track Progress in Portal</div>
          <div class="step-sub">View real-time project phases, approve tasks, download files, and message your team anytime.</div>
        </div>
      </div>
    </div>

    <p style="font-size:.84rem;color:#4a5568;line-height:1.7;">
      If you have any questions, reply to this email or reach us at
      <a href="mailto:support@kawachtech.com" style="color:#1a73e8;font-weight:700;">support@kawachtech.com</a>.
      We look forward to building something great together! 🚀
    </p>
  </div>

  <div class="footer">
    © {{ date('Y') }} Kawach Technology Private Limited ·
    <a href="{{ url('/') }}">kawachtech.com</a><br>
    This email was sent because you signed a service agreement with KawachTech.
  </div>

</div>
</body>
</html>