@extends('layouts.master')
@section('title', $bug->title . ' — KawachTech Client Portal')
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

    #clientBugShow { font-family: 'Open Sans', sans-serif; color: var(--text); background: var(--bg); min-height: 100vh; }
    #clientBugShow .cp-wrap { max-width: 780px; margin: 0 auto; padding: 28px 22px 70px; }
    #clientBugShow .cp-breadcrumb { font-size: .76rem; color: var(--muted); display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
    #clientBugShow .cp-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 600; }
    #clientBugShow .cp-title { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.4rem; margin-bottom: 4px; }
    #clientBugShow .cp-sub { font-size: .8rem; color: var(--muted); margin-bottom: 18px; }
    #clientBugShow .cp-card { background: var(--card); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); padding: 22px 24px; margin-bottom: 16px; }
    #clientBugShow .badge-group { display: flex; gap: 8px; margin-bottom: 16px; }
    #clientBugShow .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .4px; }
    #clientBugShow .badge-onhold     { background: #ffe2e8; color: var(--danger); }
    #clientBugShow .badge-review     { background: #fff4d6; color: #b8860b; }
    #clientBugShow .badge-inprogress { background: #e8f1fd; color: var(--primary); }
    #clientBugShow .badge-active     { background: #d4f5ec; color: #00a87c; }
    #clientBugShow .badge-completed  { background: #f3e8ff; color: #9333ea; }
    #clientBugShow .pri-critical { background: #ffe2e8; color: var(--danger); }
    #clientBugShow .pri-high     { background: #fff4d6; color: #b8860b; }
    #clientBugShow .pri-medium   { background: #e8f1fd; color: var(--primary); }
    #clientBugShow .pri-low      { background: #d4f5ec; color: #00a87c; }
    #clientBugShow .field-row { margin-bottom: 14px; }
    #clientBugShow .field-label { font-size: .68rem; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; margin-bottom: 4px; }
    #clientBugShow .field-value { font-size: .88rem; color: var(--text); line-height: 1.5; white-space: pre-line; }
</style>

<div id="clientBugShow">
<div class="cp-wrap">

  <div class="cp-breadcrumb">
    <a href="{{ route('client.portal') }}"><i class="fas fa-home"></i> Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
    <a href="{{ route('client.bugs.index') }}">Bug Reports</a>
    <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
    <span>{{ $bug->title }}</span>
  </div>
  <div class="cp-title">{{ $bug->title }}</div>
  <div class="cp-sub">{{ $bug->project->project_name ?? '—' }} &middot; Reported {{ $bug->created_at->format('M d, Y') }}</div>

  <div class="cp-card">
    <div class="badge-group">
      <span class="status-badge pri-{{ $bug->priority }}">{{ ucfirst($bug->priority) }} Priority</span>
      <span class="status-badge {{ $bug->badge_class }}">{{ $bug->status_label }}</span>
    </div>

    <div class="field-row">
      <div class="field-label">Description</div>
      <div class="field-value">{{ $bug->description }}</div>
    </div>

    @if($bug->steps_to_reproduce)
    <div class="field-row">
      <div class="field-label">Steps to Reproduce</div>
      <div class="field-value">{{ $bug->steps_to_reproduce }}</div>
    </div>
    @endif

    @if($bug->expected_result)
    <div class="field-row">
      <div class="field-label">Expected Result</div>
      <div class="field-value">{{ $bug->expected_result }}</div>
    </div>
    @endif

    @if($bug->actual_result)
    <div class="field-row">
      <div class="field-label">Actual Result</div>
      <div class="field-value">{{ $bug->actual_result }}</div>
    </div>
    @endif

    @if($bug->device || $bug->browser)
    <div class="field-row" style="display:flex;gap:30px;">
      @if($bug->device)
        <div><div class="field-label">Device</div><div class="field-value">{{ $bug->device }}</div></div>
      @endif
      @if($bug->browser)
        <div><div class="field-label">Browser</div><div class="field-value">{{ $bug->browser }}</div></div>
      @endif
    </div>
    @endif

    @if($bug->attachment_path)
    <div class="field-row">
      <div class="field-label">Attachment</div>
      @if(str_ends_with($bug->attachment_path, '.mp4') || str_ends_with($bug->attachment_path, '.mov'))
        <video src="{{ asset($bug->attachment_path) }}" controls style="max-width:100%;border-radius:8px;border:1px solid var(--border);"></video>
      @else
        <img src="{{ asset($bug->attachment_path) }}" style="max-width:100%;border-radius:8px;border:1px solid var(--border);">
      @endif
    </div>
    @endif
  </div>

</div>
</div>
@endsection
