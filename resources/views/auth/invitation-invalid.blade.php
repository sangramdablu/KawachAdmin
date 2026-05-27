{{-- resources/views/auth/invitation-invalid.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invalid Invitation | Kawach Technology</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Poppins', Arial, sans-serif;
      background: linear-gradient(160deg, #0a1628 0%, #0d1b3e 50%, #0a1628 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px 16px;
    }
    body::before {
      content: '';
      position: fixed; inset: 0;
      background-image:
        linear-gradient(rgba(26,115,232,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(26,115,232,.04) 1px, transparent 1px);
      background-size: 40px 40px;
      pointer-events: none;
    }
    .card {
      max-width: 480px;
      width: 100%;
      background: #fff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 28px 64px rgba(0,0,0,.45);
      position: relative;
      z-index: 1;
      text-align: center;
    }
    .card-top {
      background: linear-gradient(135deg, #0d1b3e, #112d5e);
      padding: 44px 36px 36px;
      position: relative;
      overflow: hidden;
    }
    .card-top::before {
      content: '';
      position: absolute; inset: 0;
      background-image:
        linear-gradient(rgba(26,115,232,.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(26,115,232,.06) 1px, transparent 1px);
      background-size: 28px 28px;
    }
    .icon-wrap {
      width: 80px; height: 80px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 20px;
      font-size: 34px;
      position: relative; z-index: 1;
    }
    .icon-expired  { background: rgba(234,179,8,.15);  border: 2px solid rgba(234,179,8,.4); }
    .icon-used     { background: rgba(26,115,232,.15); border: 2px solid rgba(26,115,232,.4); }
    .icon-invalid  { background: rgba(239,68,68,.15);  border: 2px solid rgba(239,68,68,.4); }
    .card-title {
      font-size: 22px; font-weight: 800;
      color: #fff; margin-bottom: 8px;
      position: relative; z-index: 1;
    }
    .card-sub {
      font-size: 13px; color: #aac4e0;
      line-height: 1.65; position: relative; z-index: 1;
    }
    .card-body { padding: 32px 36px; }
    .info-box {
      background: #f8fafd;
      border: 1px solid #e2ecf8;
      border-radius: 12px;
      padding: 18px 20px;
      margin-bottom: 24px;
      font-size: 13px;
      color: #334155;
      line-height: 1.7;
    }
    .info-box strong { color: #0d1b3e; }
    .btn-login {
      display: inline-block;
      background: linear-gradient(135deg, #1a73e8, #0d5cbf);
      color: #fff !important;
      font-family: 'Poppins', Arial, sans-serif;
      font-size: 14px;
      font-weight: 700;
      padding: 13px 36px;
      border-radius: 11px;
      text-decoration: none;
      box-shadow: 0 6px 18px rgba(26,115,232,.35);
      margin-bottom: 16px;
    }
    .help-text {
      font-size: 12px; color: #94a3b8; line-height: 1.6;
    }
    .help-text a { color: #1a73e8; text-decoration: none; }
    .logo-row {
      display: flex; align-items: center; justify-content: center;
      gap: 8px; margin-top: 24px;
    }
    .logo-shield {
      width: 32px; height: 32px;
      background: linear-gradient(135deg, #1a73e8, #0d5cbf);
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-size: 15px;
    }
    .logo-name { font-size: 13px; font-weight: 800; color: #fff; letter-spacing: 2px; }
  </style>
</head>
<body>
  <div class="card">
    <div class="card-top">

      @if($reason === 'expired')
        <div class="icon-wrap icon-expired">&#x23F0;</div>
        <div class="card-title">Invitation Expired</div>
        <div class="card-sub">This invitation link has passed its expiry date and is no longer valid.</div>

      @elseif($reason === 'already_accepted')
        <div class="icon-wrap icon-used">&#x2714;</div>
        <div class="card-title">Already Accepted</div>
        <div class="card-sub">This invitation has already been used to create an account. Please log in.</div>

      @else
        <div class="icon-wrap icon-invalid">&#x274C;</div>
        <div class="card-title">Invalid Invitation</div>
        <div class="card-sub">This invitation link is not valid. It may have been revoked or the URL is incorrect.</div>
      @endif

    </div>

    <div class="card-body">
      <div class="info-box">
        @if($reason === 'expired')
          Your invitation has expired. Please contact your administrator and ask them to
          <strong>send a new invitation</strong> to your email address.
        @elseif($reason === 'already_accepted')
          Your account was already created with this invitation. You can
          <strong>log in directly</strong> using your email and the password you set.
        @else
          If you believe this is an error, please <strong>contact your administrator</strong>
          or check that you copied the full invitation URL from your email.
        @endif
      </div>

      @if($reason === 'already_accepted')
        <a href="{{ route('login') }}" class="btn-login">&#x1F511; Go to Login</a>
      @else
        <a href="{{ route('login') }}" class="btn-login">&#x2190; Back to Login</a>
      @endif

      <div class="help-text">
        Need help? Contact us at
        <a href="mailto:{{ config('mail.admin_email', 'hr@kawachtech.com') }}">
          {{ config('mail.admin_email', 'hr@kawachtech.com') }}
        </a>
      </div>

      <div class="logo-row">
        <div class="logo-shield">&#x1F6E1;</div>
        <div class="logo-name">KAWACH TECH</div>
      </div>
    </div>
  </div>
</body>
</html>