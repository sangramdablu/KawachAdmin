{{-- resources/views/billing/pdf/sign-success.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agreement Signed — KawachTech</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body { background: linear-gradient(135deg, #f0f4fb 0%, #e8f1fd 100%); font-family: 'Segoe UI', Arial, sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
  .card { background: #fff; border-radius: 20px; box-shadow: 0 8px 48px rgba(26,115,232,.14); padding: 52px 44px; max-width: 520px; width: 100%; text-align: center; }
  .icon-wrap { width: 88px; height: 88px; border-radius: 50%; background: linear-gradient(135deg, #d4f5ec, #b0eedd); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; box-shadow: 0 4px 20px rgba(0,200,150,.2); }
  .icon-wrap i { font-size: 2.4rem; color: #00a87c; }
  h1 { font-size: 1.55rem; font-weight: 800; color: #1a1a2e; margin-bottom: 12px; }
  .sub { font-size: .92rem; color: #6b7a99; line-height: 1.7; margin-bottom: 28px; }
  .sub strong { color: #1a1a2e; }
  .steps { text-align: left; background: #f0f4fb; border-radius: 12px; padding: 20px 22px; margin-bottom: 28px; }
  .steps h4 { font-size: .75rem; font-weight: 800; text-transform: uppercase; letter-spacing: .5px; color: #6b7a99; margin-bottom: 14px; }
  .step { display: flex; gap: 12px; margin-bottom: 12px; align-items: flex-start; }
  .step:last-child { margin-bottom: 0; }
  .step-num { width: 24px; height: 24px; border-radius: 50%; background: #1a73e8; color: #fff; font-size: .7rem; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
  .step-text { font-size: .84rem; color: #4a5568; line-height: 1.5; }
  .step-text strong { color: #1a1a2e; }
  .btn { display: inline-flex; align-items: center; gap: 8px; padding: 14px 32px; border-radius: 10px; background: linear-gradient(135deg, #1a73e8, #2196f3); color: #fff; text-decoration: none; font-weight: 800; font-size: .92rem; box-shadow: 0 4px 16px rgba(26,115,232,.3); transition: all .2s; }
  .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(26,115,232,.4); }
  .footer-note { font-size: .75rem; color: #94a3b8; margin-top: 20px; }
</style>
</head>
<body>
<div class="card">
  <div class="icon-wrap">
    <i class="fas fa-check"></i>
  </div>
  <h1>Agreement Signed! 🎉</h1>
  <p class="sub">
    Thank you for partnering with <strong>Kawach Technology</strong>.<br>
    Your agreement has been signed and a confirmation email with your
    <strong>client portal login credentials</strong> has been sent to your inbox.
  </p>

  <div class="steps">
    <h4>What Happens Next</h4>
    <div class="step">
      <div class="step-num">1</div>
      <div class="step-text"><strong>Check your email</strong> — credentials for your project dashboard are on the way.</div>
    </div>
    <div class="step">
      <div class="step-num">2</div>
      <div class="step-text"><strong>Your project manager</strong> will contact you within 24 hours to schedule an onboarding call.</div>
    </div>
    <div class="step">
      <div class="step-num">3</div>
      <div class="step-text"><strong>Process the advance payment</strong> visible in your portal to officially kick off development.</div>
    </div>
    <div class="step">
      <div class="step-num">4</div>
      <div class="step-text"><strong>Track everything</strong> — project phases, tasks, team, and invoices all in one place.</div>
    </div>
  </div>

  <a href="{{ url('/login') }}" class="btn">
    <i class="fas fa-sign-in-alt"></i> Go to Portal Login
  </a>

  <p class="footer-note">
    Questions? Contact us at
    <a href="mailto:support@kawachtech.com" style="color:#1a73e8;">support@kawachtech.com</a>
  </p>
</div>
</body>
</html>