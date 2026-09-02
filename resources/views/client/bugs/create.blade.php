@extends('layouts.master')
@section('title', 'Report a Bug — KawachTech Client Portal')
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

    #clientBugForm { font-family: 'Open Sans', sans-serif; color: var(--text); background: var(--bg); min-height: 100vh; }
    #clientBugForm .cp-wrap { max-width: 720px; margin: 0 auto; padding: 28px 22px 70px; }
    #clientBugForm .cp-breadcrumb { font-size: .76rem; color: var(--muted); display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
    #clientBugForm .cp-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 600; }
    #clientBugForm .cp-title { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.4rem; margin-bottom: 20px; }
    #clientBugForm .cp-card { background: var(--card); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); padding: 24px; }
    #clientBugForm .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    @media (max-width: 560px) { #clientBugForm .form-row { grid-template-columns: 1fr; } }
    #clientBugForm .form-group { margin-bottom: 16px; }
    #clientBugForm label { display: block; font-size: .78rem; font-weight: 700; color: var(--text); margin-bottom: 6px; }
    #clientBugForm input[type="text"], #clientBugForm select, #clientBugForm textarea, #clientBugForm input[type="file"] {
        width: 100%; padding: 10px 12px; border: 1.5px solid var(--border); border-radius: 8px; font-size: .85rem;
        font-family: 'Open Sans', sans-serif; color: var(--text); background: var(--bg); box-sizing: border-box;
    }
    #clientBugForm textarea { min-height: 80px; resize: vertical; }
    #clientBugForm .btn-cp { display: inline-flex; align-items: center; gap: 7px; padding: 10px 22px; border-radius: 8px; font-size: .85rem; font-weight: 700; cursor: pointer; border: none; text-decoration: none; }
    #clientBugForm .btn-primary { background: var(--primary); color: #fff; }
    #clientBugForm .btn-outline { background: var(--card); color: var(--text); border: 1.5px solid var(--border); }
    #clientBugForm .notice-error { background: rgba(255,77,109,.1); color: var(--danger); border: 1.5px solid rgba(255,77,109,.3); padding: 12px 16px; border-radius: 10px; font-size: .82rem; margin-bottom: 16px; }
</style>

<div id="clientBugForm">
<div class="cp-wrap">

  <div class="cp-breadcrumb">
    <a href="{{ route('client.portal') }}"><i class="fas fa-home"></i> Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
    <a href="{{ route('client.bugs.index') }}">Bug Reports</a>
    <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
    <span>Report a Bug</span>
  </div>
  <div class="cp-title"><i class="fas fa-bug" style="color:var(--primary);"></i> Report a Bug</div>

  @if($errors->any())
    <div class="notice-error">
      <ul style="margin:0;padding-left:18px;">
        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
      </ul>
    </div>
  @endif

  <div class="cp-card">
    <form action="{{ route('client.bugs.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="form-group">
        <label>Project</label>
        <select name="client_portal_project_id" required>
          <option value="">Select a project…</option>
          @foreach($projects as $project)
            <option value="{{ $project->id }}" {{ old('client_portal_project_id') == $project->id ? 'selected' : '' }}>{{ $project->project_name }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" value="{{ old('title') }}" required maxlength="255" placeholder="Short summary of the bug">
      </div>

      <div class="form-group">
        <label>Description</label>
        <textarea name="description" required placeholder="Describe the issue">{{ old('description') }}</textarea>
      </div>

      <div class="form-group">
        <label>Steps to Reproduce <span style="font-weight:400;color:var(--muted);">(optional)</span></label>
        <textarea name="steps_to_reproduce" placeholder="1. Go to...&#10;2. Click...&#10;3. See error">{{ old('steps_to_reproduce') }}</textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Expected Result <span style="font-weight:400;color:var(--muted);">(optional)</span></label>
          <textarea name="expected_result" style="min-height:60px;">{{ old('expected_result') }}</textarea>
        </div>
        <div class="form-group">
          <label>Actual Result <span style="font-weight:400;color:var(--muted);">(optional)</span></label>
          <textarea name="actual_result" style="min-height:60px;">{{ old('actual_result') }}</textarea>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Device <span style="font-weight:400;color:var(--muted);">(optional)</span></label>
          <input type="text" name="device" value="{{ old('device') }}" placeholder="e.g. iPhone 14, Windows PC">
        </div>
        <div class="form-group">
          <label>Browser <span style="font-weight:400;color:var(--muted);">(optional)</span></label>
          <input type="text" name="browser" value="{{ old('browser') }}" placeholder="e.g. Chrome 128">
        </div>
      </div>

      <div class="form-group">
        <label>Priority</label>
        <select name="priority" required>
          <option value="low"      {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
          <option value="medium"   {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
          <option value="high"     {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
          <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
        </select>
      </div>

      <div class="form-group">
        <label>Attachment <span style="font-weight:400;color:var(--muted);">(image or short video, optional)</span></label>
        <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.webp,.mp4,.mov">
      </div>

      <div style="display:flex;gap:10px;justify-content:flex-end;">
        <a href="{{ route('client.bugs.index') }}" class="btn-cp btn-outline">Cancel</a>
        <button type="submit" class="btn-cp btn-primary"><i class="fas fa-paper-plane"></i> Submit Bug Report</button>
      </div>
    </form>
  </div>

</div>
</div>
@endsection
