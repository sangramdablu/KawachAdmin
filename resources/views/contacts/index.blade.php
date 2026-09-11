@extends('layouts.master')
@section('title', 'Contacts — KawachTech')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap');

#contactsPage { font-family:'Open Sans',sans-serif; color:var(--text-dark); background:var(--bg-body); min-height:100vh; }
#contactsPage * { box-sizing:border-box; }
.ct-wrap { max-width:1400px; margin:0 auto; padding:26px 20px 70px; }

.ct-topbar { margin-bottom:22px; }
.ct-breadcrumb { font-size:.73rem; color:var(--text-muted); display:flex; align-items:center; gap:6px; margin-bottom:4px; }
.ct-breadcrumb a { color:var(--primary); text-decoration:none; font-weight:600; }
.ct-page-title { font-family:'Nunito',sans-serif; font-weight:900; font-size:1.5rem; color:var(--text-dark); display:flex; align-items:center; gap:10px; }
.ct-page-title i { color:var(--primary); font-size:1.2rem; }
.ct-page-sub { font-size:.8rem; color:var(--text-muted); margin-top:4px; }

.ct-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:14px; margin-bottom:22px; }
.ct-stat-card { background:var(--card-bg); border:1px solid var(--border); border-radius:var(--card-radius); padding:16px 18px; display:flex; align-items:center; gap:14px; box-shadow:0 2px 12px rgba(26,115,232,.06); }
.ct-stat-icon { width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; background:#e8f1fd; color:var(--primary); }
[data-theme="dark"] .ct-stat-icon { background:rgba(26,115,232,.18); }
.ct-stat-val { font-family:'Nunito',sans-serif; font-weight:900; font-size:1.5rem; color:var(--text-dark); line-height:1; }
.ct-stat-lbl { font-size:.72rem; color:var(--text-muted); margin-top:2px; }

.ct-toolbar { display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:16px; }
.ct-filters { display:flex; gap:6px; flex-wrap:wrap; }
.ct-filter { padding:7px 14px; border-radius:20px; font-size:.76rem; font-weight:700; text-decoration:none; border:1px solid var(--border); color:var(--text-muted); background:var(--card-bg); }
.ct-filter.active { background:var(--primary); color:#fff; border-color:var(--primary); }
.ct-search { display:flex; gap:8px; }
.ct-search input { padding:8px 14px; border:1px solid var(--border); border-radius:9px; background:var(--card-bg); color:var(--text-dark); font-size:.82rem; min-width:220px; }
.ct-search button { padding:8px 16px; border:none; border-radius:9px; background:var(--primary); color:#fff; font-size:.82rem; font-weight:700; cursor:pointer; }

.ct-card { background:var(--card-bg); border:1px solid var(--border); border-radius:var(--card-radius); box-shadow:0 2px 14px rgba(26,115,232,.07); overflow:hidden; }
.ct-table-wrap { overflow-x:auto; }
.ct-table { width:100%; border-collapse:collapse; font-size:.82rem; }
.ct-table thead tr { background:var(--modal-header); border-bottom:2px solid var(--border); }
.ct-table th { padding:11px 14px; text-align:left; font-family:'Nunito',sans-serif; font-weight:800; font-size:.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; }
.ct-table td { padding:11px 14px; border-bottom:1px solid var(--border); color:var(--text-dark); vertical-align:middle; }
.ct-table tbody tr:hover { background:var(--modal-header); cursor:pointer; }
.ct-table tbody tr:last-child td { border-bottom:none; }
.ct-name { font-weight:700; }
.ct-sub { color:var(--text-muted); font-size:.75rem; }

.ct-pill { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:.7rem; font-weight:800; text-transform:capitalize; white-space:nowrap; background:#eef2f9; color:var(--text-muted); }
[data-theme="dark"] .ct-pill { background:rgba(138,155,181,.15); }
.ct-pill.new { background:rgba(26,115,232,.12); color:var(--primary); }
.ct-pill.read { background:rgba(138,155,181,.18); color:var(--text-muted); }
.ct-pill.replied { background:rgba(0,168,124,.12); color:#00a87c; }
.ct-pill.archived { background:rgba(229,57,53,.1); color:#e53935; }

.ct-empty { text-align:center; padding:60px 20px; color:var(--text-muted); }
.ct-empty i { font-size:2.6rem; opacity:.3; margin-bottom:14px; display:block; }

@media(max-width:768px){ .ct-search input { min-width:0; flex:1; } }
</style>

<div id="contactsPage">
<div class="ct-wrap">

  <div class="ct-topbar">
    <div class="ct-breadcrumb">
      <a href="{{ route('dashboard') }}">Dashboard</a> <i class="fas fa-chevron-right" style="font-size:.6rem;"></i> <span>Contacts</span>
    </div>
    <div class="ct-page-title"><i class="fas fa-envelope-open-text"></i> Contact Submissions</div>
    <div class="ct-page-sub">Every message submitted through the contact form on the public website.</div>
  </div>

  <div class="ct-stats">
    <div class="ct-stat-card">
      <div class="ct-stat-icon"><i class="fas fa-inbox"></i></div>
      <div><div class="ct-stat-val">{{ number_format($stats['total']) }}</div><div class="ct-stat-lbl">Total Submissions</div></div>
    </div>
    <div class="ct-stat-card">
      <div class="ct-stat-icon"><i class="fas fa-circle-dot"></i></div>
      <div><div class="ct-stat-val">{{ number_format($stats['new']) }}</div><div class="ct-stat-lbl">Unread</div></div>
    </div>
    <div class="ct-stat-card">
      <div class="ct-stat-icon"><i class="fas fa-reply"></i></div>
      <div><div class="ct-stat-val">{{ number_format($stats['replied']) }}</div><div class="ct-stat-lbl">Replied</div></div>
    </div>
    <div class="ct-stat-card">
      <div class="ct-stat-icon"><i class="fas fa-calendar-week"></i></div>
      <div><div class="ct-stat-val">{{ number_format($stats['this_week']) }}</div><div class="ct-stat-lbl">This Week</div></div>
    </div>
  </div>

  <div class="ct-toolbar">
    <div class="ct-filters">
      @php $tabs = ['' => 'All', 'new' => 'Unread', 'read' => 'Read', 'replied' => 'Replied', 'archived' => 'Archived']; @endphp
      @foreach($tabs as $key => $label)
        <a href="{{ route('contacts.index', array_filter(['status' => $key, 'search' => $search])) }}"
           class="ct-filter {{ (string) $status === (string) $key ? 'active' : '' }}">{{ $label }}</a>
      @endforeach
    </div>
    <form method="GET" action="{{ route('contacts.index') }}" class="ct-search">
      @if($status)<input type="hidden" name="status" value="{{ $status }}">@endif
      <input type="text" name="search" value="{{ $search }}" placeholder="Search name, company, subject…">
      <button type="submit"><i class="fas fa-search"></i></button>
    </form>
  </div>

  <div class="ct-card">
    @if($contacts->isEmpty())
      <div class="ct-empty">
        <i class="fas fa-envelope"></i>
        <p>No contact submissions{{ $status || $search ? ' match this filter' : ' yet' }}.</p>
      </div>
    @else
      <div class="ct-table-wrap">
        <table class="ct-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Subject</th>
              <th>Budget</th>
              <th>Status</th>
              <th>Received</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($contacts as $c)
              <tr onclick="window.location='{{ route('contacts.show', $c) }}'">
                <td>
                  <div class="ct-name">{{ $c->full_name }}</div>
                  @if($c->company)<div class="ct-sub">{{ $c->company }}</div>@endif
                </td>
                <td>{{ $c->safe('email') }}</td>
                <td>{{ $c->subject ?: '—' }}</td>
                <td>{{ $c->budget ?: '—' }}</td>
                <td><span class="ct-pill {{ $c->status }}">{{ $c->status }}</span></td>
                <td class="ct-sub">{{ $c->created_at->format('d M Y, h:i A') }}</td>
                <td><a href="{{ route('contacts.show', $c) }}" onclick="event.stopPropagation()" style="color:var(--primary);font-weight:700;">View</a></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @if($contacts->hasPages())
      <div style="padding:16px 20px;">
        {{ $contacts->links() }}
      </div>
      @endif
    @endif
  </div>

</div>
</div>

@if(session('success'))
<script>document.addEventListener('DOMContentLoaded', function(){ if(window.showToast) showToast(@json(session('success')), '#00a87c', 'fas fa-check-circle'); });</script>
@endif

@endsection
