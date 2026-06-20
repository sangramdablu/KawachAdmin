<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Please Sign Your Agreement</title>
<style>
  body { margin:0; padding:0; background:#f0f4fb; font-family:'Segoe UI',Arial,sans-serif; color:#1a1a2e; }
  .wrap { max-width:600px; margin:40px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(26,115,232,.12); }
  .header { background:linear-gradient(135deg,#1a3a6e 0%,#1a73e8 100%); padding:36px 40px 28px; text-align:center; }
  .header h1 { margin:0 0 6px; color:#fff; font-size:1.4rem; font-weight:800; }
  .header p  { margin:0; color:rgba(255,255,255,.75); font-size:.88rem; }
  .body { padding:32px 40px; }
  .greeting { font-size:1.05rem; font-weight:700; margin-bottom:10px; }
  .intro { font-size:.88rem; color:#4a5568; line-height:1.7; margin-bottom:24px; }
  .info-box { background:#f0f4fb; border:1.5px solid #e2e8f0; border-radius:12px; padding:18px 22px; margin-bottom:24px; }
  .info-row { display:flex; justify-content:space-between; padding:7px 0; border-bottom:1px solid #e2e8f0; font-size:.85rem; }
  .info-row:last-child { border-bottom:none; }
  .info-lbl { color:#6b7a99; font-weight:600; }
  .info-val { font-weight:800; color:#1a1a2e; }
  .cta { text-align:center; margin:28px 0; }
  .btn { display:inline-block; background:#1a73e8; color:#fff !important; text-decoration:none; padding:16px 40px; border-radius:10px; font-weight:800; font-size:1rem; letter-spacing:.3px; }
  .btn:hover { background:#1558b0; }
  .security-box { background:#fff8e1; border-left:4px solid #ffb830; border-radius:6px; padding:14px 18px; font-size:.82rem; color:#5a4000; line-height:1.6; margin-bottom:20px; }
  .expiry { text-align:center; font-size:.78rem; color:#6b7a99; margin-bottom:20px; }
  .expiry strong { color:#ff4d6d; }
  .link-fallback { background:#f0f4fb; border-radius:8px; padding:12px 16px; font-size:.75rem; color:#6b7a99; word-break:break-all; margin-bottom:20px; }
  .footer { padding:20px 40px 28px; text-align:center; font-size:.75rem; color:#6b7a99; border-top:1px solid #e2e8f0; }
  .footer a { color:#1a73e8; }
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <h1>✍️ Agreement Ready for Your Signature</h1>
    <p>KawachTech · Secure Digital Signing</p>
  </div>

  <div class="body">
    <div class="greeting">Hello, {{ $agreement->client_name }}!</div>
    <p class="intro">
      Kawach Technology has prepared a service agreement for your project. Please review the full
      document and sign it digitally using the secure link below. Your signature confirms acceptance
      of the terms and kicks off the onboarding process.
    </p>

    <div class="info-box">
      <div class="info-row">
        <span class="info-lbl">Invoice / Agreement #</span>
        <span class="info-val">{{ $agreement->invoice_no }}</span>
      </div>
      <div class="info-row">
        <span class="info-lbl">Project</span>
        <span class="info-val">{{ $agreement->project_name }}</span>
      </div>
      <div class="info-row">
        <span class="info-lbl">Contract Start</span>
        <span class="info-val">{{ $agreement->contract_start?->format('M d, Y') }}</span>
      </div>
      <div class="info-row">
        <span class="info-lbl">Duration</span>
        <span class="info-val">{{ $agreement->duration_months }} {{ Str::plural('Month', $agreement->duration_months) }}</span>
      </div>
      <div class="info-row">
        <span class="info-lbl">Contract Value</span>
        <span class="info-val">{{ $agreement->grand_total_formatted }}</span>
      </div>
    </div>

    <div class="cta">
      <a href="{{ route('billing.sign', $agreement->signing_token) }}" class="btn">
        Review &amp; Sign Agreement →
      </a>
    </div>

    <div class="expiry">
      ⏰ This signing link expires on <strong>{{ $agreement->expires_at?->format('M d, Y \a\t h:i A T') }}</strong>.<br>
      Contact us immediately if you need an extension.
    </div>

    <div class="security-box">
      🔒 <strong>Security Notice:</strong> This link is unique to you and is single-use. Do not
      forward this email. KawachTech will never ask for your password or payment details over email.
      If you did not expect this agreement, please contact us at
      <a href="mailto:contracts@kawachtech.com">contracts@kawachtech.com</a>.
    </div>

    <div style="font-size:.78rem;color:#6b7a99;margin-bottom:8px;">If the button doesn't work, copy and paste this link:</div>
    <div class="link-fallback">{{ route('billing.sign', $agreement->signing_token) }}</div>
  </div>

  <div class="footer">
    © {{ date('Y') }} Kawach Technology Private Limited ·
    <a href="{{ url('/') }}">kawachtech.com</a><br>
    This email was sent because a billing agreement was created on your behalf.
  </div>

</div>
</body>
</html>