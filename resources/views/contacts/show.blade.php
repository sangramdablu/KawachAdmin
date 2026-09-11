@extends('layouts.master')
@section('title', 'Contact — ' . $contact->full_name)

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap');

#contactShow { font-family:'Open Sans',sans-serif; color:var(--text-dark); background:var(--bg-body); min-height:100vh; }
#contactShow * { box-sizing:border-box; }
.cs-wrap { max-width:860px; margin:0 auto; padding:26px 20px 70px; }

.cs-breadcrumb { font-size:.73rem; color:var(--text-muted); display:flex; align-items:center; gap:6px; margin-bottom:14px; }
.cs-breadcrumb a { color:var(--primary); text-decoration:none; font-weight:600; }

.cs-card { background:var(--card-bg); border:1px solid var(--border); border-radius:var(--card-radius); box-shadow:0 2px 14px rgba(26,115,232,.07); overflow:hidden; margin-bottom:18px; }
.cs-card-head { padding:20px 24px; border-bottom:1px solid var(--border); background:var(--modal-header); display:flex; align-items:flex-start; justify-content:space-between; gap:14px; flex-wrap:wrap; }
.cs-name { font-family:'Nunito',sans-serif; font-weight:900; font-size:1.3rem; color:var(--text-dark); }
.cs-meta { font-size:.78rem; color:var(--text-muted); margin-top:4px; }
.cs-pill { display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:20px; font-size:.72rem; font-weight:800; text-transform:capitalize; background:#eef2f9; color:var(--text-muted); }
[data-theme="dark"] .cs-pill { background:rgba(138,155,181,.15); }
.cs-pill.new { background:rgba(26,115,232,.12); color:var(--primary); }
.cs-pill.replied { background:rgba(0,168,124,.12); color:#00a87c; }
.cs-pill.archived { background:rgba(229,57,53,.1); color:#e53935; }

.cs-body { padding:22px 24px; }
.cs-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px 24px; margin-bottom:22px; }
@media(max-width:600px){ .cs-grid { grid-template-columns:1fr; } }
.cs-field-label { font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.6px; color:var(--text-muted); margin-bottom:4px; }
.cs-field-val { font-size:.92rem; color:var(--text-dark); font-weight:600; word-break:break-word; }
.cs-field-val a { color:var(--primary); }

.cs-tags { display:flex; flex-wrap:wrap; gap:7px; }
.cs-tag { background:rgba(26,115,232,.1); color:var(--primary); font-size:.74rem; font-weight:700; padding:4px 12px; border-radius:20px; }

.cs-message { background:var(--modal-header); border:1px solid var(--border); border-radius:12px; padding:18px 20px; font-size:.92rem; line-height:1.75; color:var(--text-dark); white-space:pre-wrap; word-break:break-word; }

.cs-actions { padding:18px 24px; border-top:1px solid var(--border); display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
.cs-actions form { display:flex; align-items:center; gap:8px; margin:0; }
.cs-actions select { padding:8px 12px; border:1px solid var(--border); border-radius:8px; background:var(--card-bg); color:var(--text-dark); font-size:.82rem; }
.cs-btn { padding:8px 16px; border-radius:8px; font-size:.82rem; font-weight:700; cursor:pointer; border:1px solid var(--border); background:var(--card-bg); color:var(--text-dark); text-decoration:none; display:inline-flex; align-items:center; gap:7px; }
.cs-btn-primary { background:var(--primary); color:#fff; border-color:var(--primary); }
.cs-btn-danger { background:#fff0f0; color:#e53935; border-color:#f3c9c9; }
[data-theme="dark"] .cs-btn-danger { background:rgba(229,57,53,.12); border-color:rgba(229,57,53,.3); }
.cs-reply-link { margin-left:auto; }
</style>

<div id="contactShow">
<div class="cs-wrap">

  <div class="cs-breadcrumb">
    <a href="{{ route('dashboard') }}">Dashboard</a> <i class="fas fa-chevron-right" style="font-size:.6rem;"></i>
    <a href="{{ route('contacts.index') }}">Contacts</a> <i class="fas fa-chevron-right" style="font-size:.6rem;"></i>
    <span>{{ $contact->full_name }}</span>
  </div>

  <div class="cs-card">
    <div class="cs-card-head">
      <div>
        <div class="cs-name">{{ $contact->full_name }}</div>
        <div class="cs-meta">
          Submission #{{ $contact->id }} &middot; {{ $contact->created_at->format('l, d M Y \a\t h:i A') }}
          &middot; {{ $contact->created_at->diffForHumans() }}
        </div>
      </div>
      <span class="cs-pill {{ $contact->status }}">{{ $contact->status }}</span>
    </div>

    <div class="cs-body">
      @php $emailReadable = ! str_contains($contact->safe('email'), 'APP_KEY mismatch'); @endphp
      @if(! $emailReadable)
      <div style="background:#fff4e5;border:1px solid #f0c98a;color:#8a5a00;border-radius:10px;padding:12px 16px;font-size:.82rem;margin-bottom:18px;">
        <i class="fas fa-triangle-exclamation"></i>
        Email, phone, message and services are encrypted with the public site's <code>APP_KEY</code>, which doesn't match this admin app's. Align the two <code>APP_KEY</code> values so these fields become readable.
      </div>
      @endif

      <div class="cs-grid">
        <div>
          <div class="cs-field-label">Email</div>
          <div class="cs-field-val">
            @if($emailReadable)<a href="mailto:{{ $contact->safe('email') }}">{{ $contact->safe('email') }}</a>@else{{ $contact->safe('email') }}@endif
          </div>
        </div>
        <div>
          <div class="cs-field-label">Phone</div>
          <div class="cs-field-val">{{ $contact->safe('phone') }}</div>
        </div>
        <div>
          <div class="cs-field-label">Company</div>
          <div class="cs-field-val">{{ $contact->company ?: '—' }}</div>
        </div>
        <div>
          <div class="cs-field-label">Subject</div>
          <div class="cs-field-val">{{ $contact->subject ?: '—' }}</div>
        </div>
        <div>
          <div class="cs-field-label">Estimated Budget</div>
          <div class="cs-field-val">{{ $contact->budget ?: 'Not specified' }}</div>
        </div>
        <div>
          <div class="cs-field-label">Services of Interest</div>
          <div class="cs-field-val">
            @if($contact->servicesReadable() && !empty($contact->safeServices()))
              <div class="cs-tags">
                @foreach($contact->safeServices() as $service)
                  <span class="cs-tag">{{ $service }}</span>
                @endforeach
              </div>
            @elseif(! $contact->servicesReadable())
              <span style="color:var(--text-muted);">🔒 encrypted — APP_KEY mismatch</span>
            @else
              &mdash;
            @endif
          </div>
        </div>
      </div>

      <div class="cs-field-label">Message</div>
      <div class="cs-message">{{ $contact->safe('message', 'No message.') }}</div>
    </div>

    <div class="cs-actions">
      @can('contacts.manage')
      <form method="POST" action="{{ route('contacts.status', $contact) }}">
        @csrf
        @method('PATCH')
        <label class="cs-field-label" style="margin:0;">Status</label>
        <select name="status" onchange="this.form.submit()">
          @foreach(['new' => 'Unread', 'read' => 'Read', 'replied' => 'Replied', 'archived' => 'Archived'] as $val => $label)
            <option value="{{ $val }}" {{ $contact->status === $val ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </form>

      <form method="POST" action="{{ route('contacts.destroy', $contact) }}"
            onsubmit="return confirm('Permanently delete this submission? This cannot be undone.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="cs-btn cs-btn-danger"><i class="fas fa-trash"></i> Delete</button>
      </form>
      @endcan

      @if($emailReadable)
      <a class="cs-btn cs-btn-primary cs-reply-link"
         href="mailto:{{ $contact->safe('email') }}?subject={{ rawurlencode('Re: ' . ($contact->subject ?: 'Your enquiry')) }}">
        <i class="fas fa-reply"></i> Reply by Email
      </a>
      @endif
    </div>
  </div>

  <a href="{{ route('contacts.index') }}" class="cs-btn"><i class="fas fa-arrow-left"></i> Back to all submissions</a>

</div>
</div>

@if(session('success'))
<script>document.addEventListener('DOMContentLoaded', function(){ if(window.showToast) showToast(@json(session('success')), '#00a87c', 'fas fa-check-circle'); });</script>
@endif

@endsection
