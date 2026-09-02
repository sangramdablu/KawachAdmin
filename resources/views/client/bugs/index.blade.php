@extends('layouts.master')
@section('title', 'Bug Reports — KawachTech Client Portal')
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

    #clientBugs { font-family: 'Open Sans', sans-serif; color: var(--text); background: var(--bg); min-height: 100vh; }
    #clientBugs .cp-wrap { max-width: 1100px; margin: 0 auto; padding: 28px 22px 70px; }
    #clientBugs .cp-breadcrumb { font-size: .76rem; color: var(--muted); display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
    #clientBugs .cp-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 600; }
    #clientBugs .cp-topbar { display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; margin-bottom: 22px; }
    #clientBugs .cp-title { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.4rem; }
    #clientBugs .btn-cp { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: 8px; font-size: .84rem; font-weight: 700; cursor: pointer; border: none; text-decoration: none; }
    #clientBugs .btn-primary { background: var(--primary); color: #fff; }

    #clientBugs .cp-card { background: var(--card); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; }
    #clientBugs .bug-row { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid var(--border); gap: 12px; flex-wrap: wrap; text-decoration: none; color: var(--text); }
    #clientBugs .bug-row:last-child { border-bottom: none; }
    #clientBugs .bug-row:hover { background: rgba(26,115,232,.03); }
    #clientBugs .bug-title { font-weight: 800; font-size: .88rem; margin-bottom: 3px; }
    #clientBugs .bug-sub { font-size: .74rem; color: var(--muted); }
    #clientBugs .badge-group { display: flex; gap: 6px; align-items: center; }
    #clientBugs .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: .64rem; font-weight: 800; text-transform: uppercase; letter-spacing: .3px; }
    #clientBugs .badge-onhold     { background: #ffe2e8; color: var(--danger); }
    #clientBugs .badge-review     { background: #fff4d6; color: #b8860b; }
    #clientBugs .badge-inprogress { background: #e8f1fd; color: var(--primary); }
    #clientBugs .badge-active     { background: #d4f5ec; color: #00a87c; }
    #clientBugs .badge-completed  { background: #f3e8ff; color: #9333ea; }
    #clientBugs .pri-critical { background: #ffe2e8; color: var(--danger); }
    #clientBugs .pri-high     { background: #fff4d6; color: #b8860b; }
    #clientBugs .pri-medium   { background: #e8f1fd; color: var(--primary); }
    #clientBugs .pri-low      { background: #d4f5ec; color: #00a87c; }
    #clientBugs .empty-state { text-align: center; padding: 60px 20px; color: var(--muted); font-size: .9rem; }
    #clientBugs .empty-state i { font-size: 2.4rem; display: block; margin-bottom: 12px; color: var(--border); }
</style>

<div id="clientBugs">
<div class="cp-wrap">

  <div class="cp-topbar">
    <div>
      <div class="cp-breadcrumb">
        <a href="{{ route('client.portal') }}"><i class="fas fa-home"></i> Dashboard</a>
        <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
        <span>Bug Reports</span>
      </div>
      <div class="cp-title"><i class="fas fa-bug" style="color:var(--primary);"></i> Bug Reports</div>
    </div>
    <a href="{{ route('client.bugs.create') }}" class="btn-cp btn-primary"><i class="fas fa-plus"></i> Report a Bug</a>
  </div>

  <div class="cp-card">
    @forelse($bugs as $bug)
      <a href="{{ route('client.bugs.show', $bug->id) }}" class="bug-row">
        <div>
          <div class="bug-title">{{ $bug->title }}</div>
          <div class="bug-sub">{{ $bug->project->project_name ?? '—' }} &middot; {{ $bug->created_at->format('M d, Y') }}</div>
        </div>
        <div class="badge-group">
          <span class="status-badge pri-{{ $bug->priority }}">{{ ucfirst($bug->priority) }}</span>
          <span class="status-badge {{ $bug->badge_class }}">{{ $bug->status_label }}</span>
        </div>
      </a>
    @empty
      <div class="empty-state"><i class="fas fa-bug"></i> No bugs reported yet.</div>
    @endforelse
  </div>

</div>
</div>
@endsection
