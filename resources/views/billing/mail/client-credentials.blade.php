<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Client Portal Access</title>
<style>
  body { margin:0; padding:0; background:#f0f4fb; font-family:'Segoe UI',Arial,sans-serif; color:#1a1a2e; }
  .wrap { max-width:580px; margin:40px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(26,115,232,.12); }
  .header { background:linear-gradient(135deg,#1a3a6e 0%,#1a73e8 100%); padding:36px 40px 28px; text-align:center; }
  .header img { height:40px; margin-bottom:16px; }
  .header h1 { margin:0; color:#fff; font-size:1.4rem; font-weight:800; }
  .header p { margin:8px 0 0; color:rgba(255,255,255,.75); font-size:.88rem; }
  .body { padding:32px 40px; }
  .greeting { font-size:1rem; font-weight:700; margin-bottom:8px; }
  .intro { font-size:.88rem; color:#4a5568; line-height:1.6; margin-bottom:24px; }
  .cred-box { background:#f0f4fb; border:1.5px solid #e2e8f0; border-radius:12px; padding:20px 24px; margin-bottom:24px; }
  .cred-row { display:flex; align-items:center; justify-content:space-between; padding:8px 0; border-bottom:1px solid #e2e8f0; }
  .cred-row:last-child { border-bottom:none; }
  .cred-label { font-size:.78rem; font-weight:700; color:#6b7a99; text-transform:uppercase; letter-spacing:.4px; }
  .cred-value { font-size:.9rem; font-weight:800; color:#1a1a2e; font-family:monospace; }
  .btn-wrap { text-align:center; margin-bottom:24px; }
  .btn { display:inline-block; background:#1a73e8; color:#fff; text-decoration:none; padding:14px 32px; border-radius:10px; font-weight:800; font-size:.92rem; }
  .note { background:#fff4d6; border-left:4px solid #ffb830; border-radius:6px; padding:12px 16px; font-size:.82rem; color:#5a4000; line-height:1.5; margin-bottom:24px; }
  .footer { padding:20px 40px 28px; text-align:center; font-size:.75rem; color:#6b7a99; border-top:1px solid #e2e8f0; }
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <h1>🚀 Welcome to Your Client Portal</h1>
    <p>KawachTech — Project Collaboration Platform</p>
  </div>

  <div class="body">
    <div class="greeting">Hello, {{ $clientUser->name }}!</div>
    <p class="intro">
      Your billing agreement for <strong>{{ $agreement->project_name }}</strong> has been confirmed.
      We've created a dedicated Client Portal account for you where you can track your project's
      real-time progress, review tasks, download files, and stay in touch with your project team.
    </p>

    <div class="cred-box">
      <div class="cred-row">
        <span class="cred-label">Portal URL</span>
        <span class="cred-value">{{ url('/client/portal') }}</span>
      </div>
      <div class="cred-row">
        <span class="cred-label">Email</span>
        <span class="cred-value">{{ $clientUser->email }}</span>
      </div>
      <div class="cred-row">
        <span class="cred-label">Password</span>
        <span class="cred-value">{{ $plainPassword }}</span>
      </div>
    </div>

    <div class="btn-wrap">
      <a href="{{ url('/client/portal') }}" class="btn">Login to Client Portal →</a>
    </div>

    <div class="note">
      ⚠️ <strong>Security Notice:</strong> Please change your password immediately after your first login.
      Keep your credentials confidential and do not share them with anyone.
    </div>

    <p style="font-size:.82rem;color:#4a5568;line-height:1.6;">
      Your project manager <strong>will reach out shortly</strong> to schedule your onboarding call.
      If you have any questions before then, reply to this email or use the Message Team feature
      inside your portal.
    </p>
  </div>

  <div class="footer">
    © {{ date('Y') }} Kawach Technology Private Limited · <a href="{{ url('/') }}" style="color:#1a73e8;">kawachtech.com</a><br>
    This email was sent because a billing agreement was created for your project.
  </div>

</div>
</body>
</html>