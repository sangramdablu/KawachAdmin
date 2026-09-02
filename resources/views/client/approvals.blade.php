@extends('layouts.master')
@section('title', 'Approval Center — KawachTech Client Portal')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap');

    :root {
        --primary:      #1a73e8;
        --primary-dark: #1558b0;
        --accent:       #2196f3;
        --success:      #00c896;
        --warning:      #ffb830;
        --danger:       #ff4d6d;
        --bg:           #f0f4fb;
        --white:        #ffffff;
        --card:         #ffffff;
        --border:       #e2e8f0;
        --text:         #1a1a2e;
        --muted:        #6b7a99;
        --radius:       12px;
        --shadow:       0 2px 20px rgba(26,115,232,.08);
        --shadow-md:    0 6px 32px rgba(26,115,232,.13);
    }
    html[data-theme="dark"] {
        --bg:     #0f172a;
        --white:  #1e293b;
        --card:   #1e293b;
        --text:   #e2e8f0;
        --border: #334155;
        --muted:  #94a3b8;
        --shadow:    0 2px 20px rgba(0,0,0,.3);
        --shadow-md: 0 6px 32px rgba(0,0,0,.4);
    }

    #clientApprovals { font-family: 'Open Sans', sans-serif; color: var(--text); background: var(--bg); min-height: 100vh; }
    #clientApprovals .cp-wrap { max-width: 1100px; margin: 0 auto; padding: 28px 22px 70px; }
    #clientApprovals .cp-breadcrumb { font-size: .76rem; color: var(--muted); display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
    #clientApprovals .cp-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 600; }
    #clientApprovals .cp-title { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.55rem; color: var(--text); margin-bottom: 22px; }

    #clientApprovals .btn-cp { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: 8px; font-size: .84rem; font-weight: 700; cursor: pointer; border: none; transition: all .2s; font-family: 'Open Sans', sans-serif; white-space: nowrap; text-decoration: none; }
    #clientApprovals .btn-cp:hover { transform: translateY(-1px); }
    #clientApprovals .btn-primary { background: var(--primary); color: #fff; }
    #clientApprovals .btn-primary:hover { background: var(--primary-dark); color: #fff; }
    #clientApprovals .btn-outline { background: var(--card); color: var(--text); border: 1.5px solid var(--border); }
    #clientApprovals .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
    #clientApprovals .btn-success { background: var(--success); color: #fff; }
    #clientApprovals .btn-success:hover { background: #00a87c; color: #fff; }
    #clientApprovals .btn-danger { background: var(--danger); color: #fff; }
    #clientApprovals .btn-danger:hover { background: #e0435f; color: #fff; }
    #clientApprovals .btn-sm { padding: 6px 14px; font-size: .78rem; }

    #clientApprovals .section-title { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.02rem; color: var(--text); margin: 26px 0 12px; display: flex; align-items: center; gap: 8px; }
    #clientApprovals .section-title i { color: var(--primary); }
    #clientApprovals .cp-card { background: var(--card); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; margin-bottom: 14px; }
    #clientApprovals .cp-row { display: flex; align-items: flex-start; gap: 16px; padding: 18px 20px; flex-wrap: wrap; }
    #clientApprovals .thumb { width: 76px; height: 56px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border); flex-shrink: 0; }
    #clientApprovals .row-body { flex: 1; min-width: 220px; }
    #clientApprovals .row-title { font-weight: 800; font-size: .92rem; color: var(--text); margin-bottom: 3px; }
    #clientApprovals .row-sub { font-size: .74rem; color: var(--muted); margin-bottom: 6px; }
    #clientApprovals .row-meta { display: flex; flex-wrap: wrap; gap: 12px; font-size: .74rem; color: var(--muted); margin-top: 6px; }
    #clientApprovals .row-meta strong { color: var(--text); }
    #clientApprovals .row-actions { display: flex; gap: 8px; flex-wrap: wrap; align-self: center; }
    #clientApprovals .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .4px; background: #fff4d6; color: #b8860b; }

    #clientApprovals details.inline-form { width: 100%; margin-top: 10px; }
    #clientApprovals details.inline-form summary { cursor: pointer; font-size: .78rem; font-weight: 700; color: var(--primary); list-style: none; }
    #clientApprovals details.inline-form summary::-webkit-details-marker { display: none; }
    #clientApprovals .inline-form-body { margin-top: 8px; display: flex; gap: 8px; flex-wrap: wrap; }
    #clientApprovals textarea.cp-textarea { flex: 1; min-width: 220px; min-height: 60px; border: 1.5px solid var(--border); border-radius: 8px; padding: 8px 10px; font-size: .8rem; font-family: 'Open Sans', sans-serif; color: var(--text); background: var(--bg); resize: vertical; }
    #clientApprovals .empty-state { text-align: center; padding: 40px 20px; color: var(--muted); font-size: .85rem; }
    #clientApprovals .empty-state i { font-size: 2rem; display: block; margin-bottom: 10px; color: var(--border); }
    #clientApprovals .notice-bar { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 10px; font-size: .82rem; margin-bottom: 16px; }
    #clientApprovals .notice-success { background: rgba(0,200,150,.1); color: #00a87c; border: 1.5px solid rgba(0,200,150,.3); }
    #clientApprovals .notice-error { background: rgba(255,77,109,.1); color: var(--danger); border: 1.5px solid rgba(255,77,109,.3); }
</style>

<div id="clientApprovals">
<div class="cp-wrap">

  <div class="cp-breadcrumb">
    <a href="{{ route('client.portal') }}"><i class="fas fa-home"></i> Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
    <span>Approvals</span>
  </div>
  <div class="cp-title"><i class="fas fa-check-double" style="color:var(--primary);"></i> Approval Center</div>

  @if(session('success'))
    <div class="notice-bar notice-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="notice-bar notice-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
  @endif

  {{-- ── DESIGNS PENDING REVIEW ── --}}
  <div class="section-title"><i class="fas fa-palette"></i> Designs Pending Your Review</div>
  @forelse($pendingDesigns as $design)
    <div class="cp-card">
      <div class="cp-row">
        <img class="thumb" src="{{ asset($design->image_path) }}" alt="{{ $design->title }}">
        <div class="row-body">
          <div class="row-title">{{ $design->title }} <span style="color:var(--muted);font-weight:600;">({{ $design->version }})</span></div>
          <div class="row-sub">{{ $design->project->project_name ?? '—' }}</div>
          <span class="status-badge">{{ $design->status_label }}</span>
          <div style="margin-top:10px;">
            <a href="{{ route('client.designs.show', $design->id) }}" class="btn-cp btn-outline btn-sm"><i class="fas fa-eye"></i> View Full</a>
          </div>
        </div>
        <div class="row-actions">
          <form action="{{ route('client.designs.approve', $design->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-cp btn-success btn-sm"><i class="fas fa-check"></i> Approve</button>
          </form>
        </div>
      </div>
      <div style="padding: 0 20px 16px;">
        <details class="inline-form">
          <summary><i class="fas fa-comment-dots"></i> Request Changes</summary>
          <form action="{{ route('client.designs.request-changes', $design->id) }}" method="POST" class="inline-form-body">
            @csrf
            <textarea name="body" class="cp-textarea" placeholder="What needs to change?" required></textarea>
            <button type="submit" class="btn-cp btn-outline btn-sm"><i class="fas fa-paper-plane"></i> Submit</button>
          </form>
        </details>
      </div>
    </div>
  @empty
    <div class="cp-card"><div class="empty-state"><i class="fas fa-palette"></i> No designs are waiting on your review right now.</div></div>
  @endforelse

  {{-- ── CHANGE REQUESTS AWAITING DECISION ── --}}
  <div class="section-title"><i class="fas fa-code-pull-request"></i> Change Requests Awaiting Your Decision</div>
  @forelse($respondedChangeRequests as $cr)
    <div class="cp-card">
      <div class="cp-row">
        <div class="row-body">
          <div class="row-title">{{ $cr->title }}</div>
          <div class="row-sub">{{ $cr->project->project_name ?? '—' }}</div>
          <span class="status-badge">{{ $cr->status_label }}</span>
          <div class="row-meta">
            @if($cr->estimated_hours) <span><strong>{{ $cr->estimated_hours }}</strong> hrs</span> @endif
            @if($cr->additional_cost) <span><strong>₹{{ number_format($cr->additional_cost, 2) }}</strong> cost</span> @endif
            @if($cr->deadline_impact) <span>Impact: <strong>{{ $cr->deadline_impact }}</strong></span> @endif
          </div>
          @if($cr->response_note)
            <div style="margin-top:8px;font-size:.8rem;color:var(--text);background:var(--bg);border-radius:8px;padding:8px 12px;">{{ $cr->response_note }}</div>
          @endif
          <div style="margin-top:10px;">
            <a href="{{ route('client.change-requests.show', $cr->id) }}" class="btn-cp btn-outline btn-sm"><i class="fas fa-eye"></i> View Full</a>
          </div>
        </div>
        <div class="row-actions">
          <form action="{{ route('client.change-requests.approve', $cr->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-cp btn-success btn-sm"><i class="fas fa-check"></i> Approve</button>
          </form>
          <details class="inline-form" style="width:auto;">
            <summary class="btn-cp btn-danger btn-sm" style="display:inline-flex;"><i class="fas fa-times"></i> Reject</summary>
            <form action="{{ route('client.change-requests.reject', $cr->id) }}" method="POST" class="inline-form-body">
              @csrf
              <textarea name="client_note" class="cp-textarea" placeholder="Reason (optional)"></textarea>
              <button type="submit" class="btn-cp btn-outline btn-sm">Confirm Reject</button>
            </form>
          </details>
          <details class="inline-form" style="width:auto;">
            <summary class="btn-cp btn-outline btn-sm" style="display:inline-flex;"><i class="fas fa-question"></i> Ask a Question</summary>
            <form action="{{ route('client.change-requests.clarify', $cr->id) }}" method="POST" class="inline-form-body">
              @csrf
              <textarea name="client_note" class="cp-textarea" placeholder="Your question…" required></textarea>
              <button type="submit" class="btn-cp btn-outline btn-sm">Send</button>
            </form>
          </details>
        </div>
      </div>
    </div>
  @empty
    <div class="cp-card"><div class="empty-state"><i class="fas fa-code-pull-request"></i> No change requests are waiting on your decision right now.</div></div>
  @endforelse

</div>
</div>
@endsection
