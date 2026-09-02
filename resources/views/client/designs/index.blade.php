@extends('layouts.master')
@section('title', 'Designs — KawachTech Client Portal')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap');

    :root {
        --primary: #1a73e8; --primary-dark: #1558b0; --success: #00c896; --warning: #ffb830; --danger: #ff4d6d;
        --bg: #f0f4fb; --card: #ffffff; --border: #e2e8f0; --text: #1a1a2e; --muted: #6b7a99;
        --radius: 12px; --shadow: 0 2px 20px rgba(26,115,232,.08); --shadow-md: 0 6px 32px rgba(26,115,232,.13);
    }
    html[data-theme="dark"] {
        --bg: #0f172a; --card: #1e293b; --text: #e2e8f0; --border: #334155; --muted: #94a3b8;
        --shadow: 0 2px 20px rgba(0,0,0,.3); --shadow-md: 0 6px 32px rgba(0,0,0,.4);
    }

    #clientDesigns { font-family: 'Open Sans', sans-serif; color: var(--text); background: var(--bg); min-height: 100vh; }
    #clientDesigns .cp-wrap { max-width: 1200px; margin: 0 auto; padding: 28px 22px 70px; }
    #clientDesigns .cp-breadcrumb { font-size: .76rem; color: var(--muted); display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
    #clientDesigns .cp-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 600; }
    #clientDesigns .cp-title { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.55rem; margin-bottom: 22px; }
    #clientDesigns .project-group { margin-bottom: 30px; }
    #clientDesigns .project-group-title { font-family: 'Nunito', sans-serif; font-weight: 800; font-size: .95rem; color: var(--text); margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
    #clientDesigns .project-group-title i { color: var(--primary); }
    #clientDesigns .design-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; }
    #clientDesigns .design-card { background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; text-decoration: none; color: var(--text); transition: transform .2s, box-shadow .2s; display: block; }
    #clientDesigns .design-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    #clientDesigns .design-thumb { width: 100%; height: 140px; object-fit: cover; background: var(--bg); }
    #clientDesigns .design-body { padding: 12px 14px; }
    #clientDesigns .design-title { font-weight: 800; font-size: .85rem; margin-bottom: 4px; }
    #clientDesigns .design-version { font-size: .7rem; color: var(--muted); margin-bottom: 8px; }
    #clientDesigns .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px; border-radius: 20px; font-size: .64rem; font-weight: 800; text-transform: uppercase; letter-spacing: .3px; }
    #clientDesigns .badge-review     { background: #fff4d6; color: #b8860b; }
    #clientDesigns .badge-active     { background: #d4f5ec; color: #00a87c; }
    #clientDesigns .badge-onhold     { background: #ffe2e8; color: var(--danger); }
    #clientDesigns .empty-state { text-align: center; padding: 60px 20px; color: var(--muted); font-size: .9rem; }
    #clientDesigns .empty-state i { font-size: 2.4rem; display: block; margin-bottom: 12px; color: var(--border); }
</style>

<div id="clientDesigns">
<div class="cp-wrap">

  <div class="cp-breadcrumb">
    <a href="{{ route('client.portal') }}"><i class="fas fa-home"></i> Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
    <span>Designs</span>
  </div>
  <div class="cp-title"><i class="fas fa-palette" style="color:var(--primary);"></i> Designs</div>

  @forelse($designs as $projectId => $group)
    <div class="project-group">
      <div class="project-group-title"><i class="fas fa-folder-open"></i> {{ $group->first()->project->project_name ?? '—' }}</div>
      <div class="design-grid">
        @foreach($group as $design)
          <a href="{{ route('client.designs.show', $design->id) }}" class="design-card">
            <img class="design-thumb" src="{{ asset($design->image_path) }}" alt="{{ $design->title }}">
            <div class="design-body">
              <div class="design-title">{{ $design->title }}</div>
              <div class="design-version">{{ $design->version }} &middot; {{ $design->created_at->format('M d, Y') }}</div>
              <span class="status-badge {{ $design->badge_class }}">{{ $design->status_label }}</span>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  @empty
    <div class="empty-state"><i class="fas fa-palette"></i> No designs have been uploaded yet.</div>
  @endforelse

</div>
</div>
@endsection
