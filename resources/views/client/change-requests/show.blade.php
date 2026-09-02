@extends('layouts.master')
@section('title', $cr->title . ' — KawachTech Client Portal')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap');

    :root {
        --primary: #1a73e8; --primary-dark: #1558b0; --success: #00c896; --warning: #ffb830; --danger: #ff4d6d;
        --bg: #f0f4fb; --card: #ffffff; --border: #e2e8f0; --text: #1a1a2e; --muted: #6b7a99;
        --radius: 12px; --shadow: 0 2px 20px rgba(26,115,232,.08);
    }
    html[data-theme="dark"] {
        --bg: #0f172a; --card: #1e293b; --text: #e2e8f0; --border: #334155; --muted: #94a3b8;
        --shadow: 0 2px 20px rgba(0,0,0,.3);
    }

    #clientChangeRequestShow { font-family: 'Open Sans', sans-serif; color: var(--text); background: var(--bg); min-height: 100vh; }
    #clientChangeRequestShow .cp-wrap { max-width: 780px; margin: 0 auto; padding: 28px 22px 70px; }
    #clientChangeRequestShow .cp-breadcrumb { font-size: .76rem; color: var(--muted); display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
    #clientChangeRequestShow .cp-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 600; }
    #clientChangeRequestShow .cp-title { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.4rem; margin-bottom: 4px; }
    #clientChangeRequestShow .cp-sub { font-size: .8rem; color: var(--muted); margin-bottom: 18px; }
    #clientChangeRequestShow .cp-card { background: var(--card); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); padding: 22px 24px; margin-bottom: 16px; }
    #clientChangeRequestShow .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .4px; }
    #clientChangeRequestShow .badge-inprogress { background: #e8f1fd; color: var(--primary); }
    #clientChangeRequestShow .badge-review     { background: #fff4d6; color: #b8860b; }
    #clientChangeRequestShow .badge-active     { background: #d4f5ec; color: #00a87c; }
    #clientChangeRequestShow .badge-onhold     { background: #ffe2e8; color: var(--danger); }
    #clientChangeRequestShow .field-row { margin-bottom: 14px; }
    #clientChangeRequestShow .field-label { font-size: .68rem; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; margin-bottom: 4px; }
    #clientChangeRequestShow .field-value { font-size: .88rem; color: var(--text); line-height: 1.5; }
    #clientChangeRequestShow .response-box { background: rgba(26,115,232,.05); border: 1.5px solid rgba(26,115,232,.2); border-radius: 10px; padding: 16px; }
    #clientChangeRequestShow .btn-cp { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: 8px; font-size: .84rem; font-weight: 700; cursor: pointer; border: none; text-decoration: none; }
    #clientChangeRequestShow .btn-success { background: var(--success); color: #fff; }
    #clientChangeRequestShow .btn-danger  { background: var(--danger); color: #fff; }
    #clientChangeRequestShow .btn-outline { background: var(--card); color: var(--text); border: 1.5px solid var(--border); }
    #clientChangeRequestShow textarea.cp-textarea { width: 100%; min-height: 60px; border: 1.5px solid var(--border); border-radius: 8px; padding: 8px 10px; font-size: .8rem; background: var(--bg); color: var(--text); box-sizing: border-box; }
    #clientChangeRequestShow .notice-bar { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 10px; font-size: .82rem; margin-bottom: 16px; }
    #clientChangeRequestShow .notice-success { background: rgba(0,200,150,.1); color: #00a87c; border: 1.5px solid rgba(0,200,150,.3); }
    #clientChangeRequestShow .notice-error { background: rgba(255,77,109,.1); color: var(--danger); border: 1.5px solid rgba(255,77,109,.3); }
</style>

<div id="clientChangeRequestShow">
<div class="cp-wrap">

  <div class="cp-breadcrumb">
    <a href="{{ route('client.portal') }}"><i class="fas fa-home"></i> Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
    <a href="{{ route('client.change-requests.index') }}">Change Requests</a>
    <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
    <span>{{ $cr->title }}</span>
  </div>
  <div class="cp-title">{{ $cr->title }}</div>
  <div class="cp-sub">{{ $cr->project->project_name ?? '—' }} &middot; Submitted {{ $cr->created_at->format('M d, Y') }}</div>

  @if(session('success'))
    <div class="notice-bar notice-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="notice-bar notice-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
  @endif

  <div class="cp-card">
    <span class="status-badge {{ $cr->badge_class }}">{{ $cr->status_label }}</span>

    <div class="field-row" style="margin-top:16px;">
      <div class="field-label">Description</div>
      <div class="field-value">{{ $cr->description }}</div>
    </div>

    @if($cr->expected_result)
    <div class="field-row">
      <div class="field-label">Expected Result</div>
      <div class="field-value">{{ $cr->expected_result }}</div>
    </div>
    @endif

    @if($cr->screenshot_path)
    <div class="field-row">
      <div class="field-label">Screenshot</div>
      <img src="{{ asset($cr->screenshot_path) }}" style="max-width:100%;border-radius:8px;border:1px solid var(--border);">
    </div>
    @endif

    <div class="field-row">
      <div class="field-label">Priority</div>
      <div class="field-value" style="text-transform:capitalize;">{{ $cr->priority }}</div>
    </div>
  </div>

  @if($cr->responded_at)
  <div class="cp-card response-box">
    <div class="field-label" style="margin-bottom:8px;"><i class="fas fa-reply"></i> Team Response</div>
    <div style="display:flex;flex-wrap:wrap;gap:20px;margin-bottom:10px;">
      @if($cr->estimated_hours)
        <div><div class="field-label">Estimated Hours</div><div class="field-value">{{ $cr->estimated_hours }} hrs</div></div>
      @endif
      @if($cr->additional_cost)
        <div><div class="field-label">Additional Cost</div><div class="field-value">₹{{ number_format($cr->additional_cost, 2) }}</div></div>
      @endif
      @if($cr->deadline_impact)
        <div><div class="field-label">Deadline Impact</div><div class="field-value">{{ $cr->deadline_impact }}</div></div>
      @endif
    </div>
    @if($cr->response_note)
      <div class="field-value">{{ $cr->response_note }}</div>
    @endif
  </div>
  @endif

  @if($cr->client_note)
  <div class="cp-card">
    <div class="field-label" style="margin-bottom:6px;">Your Note</div>
    <div class="field-value">{{ $cr->client_note }}</div>
  </div>
  @endif

  @if($cr->status === 'responded')
  <div class="cp-card">
    <div class="field-label" style="margin-bottom:10px;">Your Decision</div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <form action="{{ route('client.change-requests.approve', $cr->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn-cp btn-success"><i class="fas fa-check"></i> Approve</button>
      </form>
      <details>
        <summary class="btn-cp btn-danger" style="display:inline-flex;"><i class="fas fa-times"></i> Reject</summary>
        <form action="{{ route('client.change-requests.reject', $cr->id) }}" method="POST" style="margin-top:8px;display:flex;gap:8px;">
          @csrf
          <textarea name="client_note" class="cp-textarea" placeholder="Reason (optional)"></textarea>
          <button type="submit" class="btn-cp btn-outline">Confirm</button>
        </form>
      </details>
      <details>
        <summary class="btn-cp btn-outline" style="display:inline-flex;"><i class="fas fa-question"></i> Ask a Question</summary>
        <form action="{{ route('client.change-requests.clarify', $cr->id) }}" method="POST" style="margin-top:8px;display:flex;gap:8px;">
          @csrf
          <textarea name="client_note" class="cp-textarea" placeholder="Your question…" required></textarea>
          <button type="submit" class="btn-cp btn-outline">Send</button>
        </form>
      </details>
    </div>
  </div>
  @endif

</div>
</div>
@endsection
