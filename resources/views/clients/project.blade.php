@extends('layouts.master')
@section('title', $project->project_name . ' — Kawach Technology')

@section('content')
<div style="font-family:'Sora',sans-serif;padding:28px 24px;max-width:1100px;margin:0 auto;">

  <div style="font-size:.72rem;color:#7a82a8;margin-bottom:5px;">
    <a href="{{ route('dashboard') }}" style="color:#2563eb;text-decoration:none;font-weight:600;">Dashboard</a>
    ›
    <a href="{{ route('clients.index') }}" style="color:#2563eb;text-decoration:none;font-weight:600;">Clients</a>
    › {{ $project->project_name }}
  </div>

  <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <div>
      <h1 style="font-size:1.4rem;font-weight:800;color:#0c0f1a;letter-spacing:-.3px;margin:0 0 4px;">
        <i class="fas fa-layer-group" style="color:#2563eb;font-size:1.1rem;margin-right:8px;"></i>
        {{ $project->project_name }}
      </h1>
      <div style="font-size:.8rem;color:#7a82a8;">
        Client: <strong style="color:#3a3f5c;">{{ $project->clientUser->name ?? '—' }}</strong>
        ({{ $project->clientUser->email ?? '—' }})
      </div>
    </div>
    <span style="font-size:.7rem;font-weight:700;color:#2563eb;text-transform:uppercase;background:#eef4ff;padding:5px 14px;border-radius:20px;">{{ $project->status_label }}</span>
  </div>

  @if(session('success'))
  <div style="background:#ecfdf5;border:1px solid #6ee7b7;border-radius:8px;padding:11px 16px;margin-bottom:16px;font-size:.82rem;color:#059669;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
  </div>
  @endif
  @if($errors->any())
  <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:11px 16px;margin-bottom:16px;font-size:.82rem;color:#dc2626;">
    <ul style="margin:0;padding-left:18px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
  @endif

  {{-- ── TABS ── --}}
  <div style="display:flex;gap:4px;border-bottom:2px solid #e2e6f3;margin-bottom:22px;overflow-x:auto;">
    @foreach(['overview'=>'Overview','tasks'=>'Tasks','team'=>'Team','invoices'=>'Invoices','designs'=>'Designs','change-requests'=>'Change Requests','bugs'=>'Bugs'] as $tabKey => $tabLabel)
    <button type="button" class="proj-tab-btn" data-tab="{{ $tabKey }}"
            style="padding:10px 16px;border:none;background:none;font-size:.82rem;font-weight:700;color:#7a82a8;cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;white-space:nowrap;">
      {{ $tabLabel }}
    </button>
    @endforeach
  </div>

  {{-- ── OVERVIEW TAB ── --}}
  <div class="proj-tab-panel" data-panel="overview">
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e6f3;padding:24px;box-shadow:0 2px 12px rgba(37,99,235,.07);">
      <h2 style="font-size:.95rem;font-weight:800;color:#0c0f1a;margin:0 0 16px;">Edit Project Progress</h2>
      <form action="{{ route('client-projects.update', $project->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
          <div>
            <label style="display:block;font-size:.76rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Status</label>
            <select name="status" required style="width:100%;padding:10px 12px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.85rem;">
              @foreach(['inprogress','active','review','onhold','completed'] as $s)
                <option value="{{ $s }}" {{ $project->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label style="display:block;font-size:.76rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Progress (%)</label>
            <input type="number" name="progress" min="0" max="100" value="{{ $project->progress }}" required
                   style="width:100%;padding:10px 12px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.85rem;box-sizing:border-box;">
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:20px;">
          <div>
            <label style="display:block;font-size:.76rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Done Tasks</label>
            <input type="number" name="done_tasks" min="0" value="{{ $project->done_tasks }}" required
                   style="width:100%;padding:10px 12px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.85rem;box-sizing:border-box;">
          </div>
          <div>
            <label style="display:block;font-size:.76rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Total Tasks</label>
            <input type="number" name="total_tasks" min="0" value="{{ $project->total_tasks }}" required
                   style="width:100%;padding:10px 12px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.85rem;box-sizing:border-box;">
          </div>
          <div>
            <label style="display:block;font-size:.76rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Deadline</label>
            <input type="date" name="deadline" value="{{ $project->deadline?->format('Y-m-d') }}"
                   style="width:100%;padding:10px 12px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.85rem;box-sizing:border-box;">
          </div>
        </div>
        {{-- Preserve existing phases untouched (no phase editor UI yet — out of scope) --}}
        @foreach(($project->phases ?? []) as $i => $phase)
          <input type="hidden" name="phases[{{ $i }}][name]" value="{{ $phase['name'] ?? '' }}">
          <input type="hidden" name="phases[{{ $i }}][state]" value="{{ $phase['state'] ?? 'pending' }}">
        @endforeach
        <button type="submit" style="padding:9px 20px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-size:.83rem;font-weight:700;cursor:pointer;">Save Changes</button>
      </form>
    </div>
  </div>

  {{-- ── TASKS TAB ── --}}
  <div class="proj-tab-panel" data-panel="tasks" style="display:none;">
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e6f3;padding:24px;box-shadow:0 2px 12px rgba(37,99,235,.07);margin-bottom:18px;">
      <h2 style="font-size:.95rem;font-weight:800;color:#0c0f1a;margin:0 0 14px;">Add Task</h2>
      <form action="{{ route('client-projects.tasks.store', $project->id) }}" method="POST" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
        @csrf
        <div style="flex:2;min-width:200px;">
          <label style="display:block;font-size:.74rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Task Name</label>
          <input type="text" name="name" required style="width:100%;padding:9px 11px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.83rem;box-sizing:border-box;">
        </div>
        <div style="flex:1;min-width:120px;">
          <label style="display:block;font-size:.74rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Priority</label>
          <select name="priority" required style="width:100%;padding:9px 11px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.83rem;">
            <option value="high">High</option><option value="medium" selected>Medium</option><option value="low">Low</option>
          </select>
        </div>
        <div style="flex:1;min-width:150px;">
          <label style="display:block;font-size:.74rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Due Date</label>
          <input type="date" name="due" required style="width:100%;padding:9px 11px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.83rem;box-sizing:border-box;">
        </div>
        <button type="submit" style="padding:9px 18px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-size:.82rem;font-weight:700;cursor:pointer;">Add</button>
      </form>
    </div>
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e6f3;overflow:hidden;box-shadow:0 2px 12px rgba(37,99,235,.07);">
      @forelse($project->tasks as $task)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-bottom:1px solid #f0f2f8;">
          <div>
            <div style="font-weight:700;font-size:.85rem;color:#0c0f1a;">{{ $task->name }}</div>
            <div style="font-size:.72rem;color:#7a82a8;">Due {{ $task->due_formatted }} @if($task->overdue)<span style="color:#dc2626;">(Overdue)</span>@endif</div>
          </div>
          <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:#3a3f5c;">{{ $task->state }} &middot; {{ $task->priority }}</span>
        </div>
      @empty
        <div style="padding:30px;text-align:center;color:#7a82a8;font-size:.82rem;">No tasks yet.</div>
      @endforelse
    </div>
  </div>

  {{-- ── TEAM TAB ── --}}
  <div class="proj-tab-panel" data-panel="team" style="display:none;">
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e6f3;padding:24px;box-shadow:0 2px 12px rgba(37,99,235,.07);margin-bottom:18px;">
      <h2 style="font-size:.95rem;font-weight:800;color:#0c0f1a;margin:0 0 14px;">Add Team Member</h2>
      <form action="{{ route('client-projects.team.store', $project->id) }}" method="POST" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
        @csrf
        <div style="flex:1;min-width:150px;">
          <label style="display:block;font-size:.74rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Name</label>
          <input type="text" name="name" required style="width:100%;padding:9px 11px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.83rem;box-sizing:border-box;">
        </div>
        <div style="flex:1;min-width:130px;">
          <label style="display:block;font-size:.74rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Role</label>
          <input type="text" name="role" required style="width:100%;padding:9px 11px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.83rem;box-sizing:border-box;">
        </div>
        <div style="width:80px;">
          <label style="display:block;font-size:.74rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Initials</label>
          <input type="text" name="initials" maxlength="4" required style="width:100%;padding:9px 11px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.83rem;box-sizing:border-box;">
        </div>
        <div style="width:110px;">
          <label style="display:block;font-size:.74rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Color</label>
          <input type="text" name="color" value="#1a73e8" required style="width:100%;padding:9px 11px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.83rem;box-sizing:border-box;">
        </div>
        <div style="width:120px;">
          <label style="display:block;font-size:.74rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Status</label>
          <select name="status" required style="width:100%;padding:9px 11px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.83rem;">
            <option value="online">Online</option><option value="away">Away</option><option value="offline" selected>Offline</option>
          </select>
        </div>
        <button type="submit" style="padding:9px 18px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-size:.82rem;font-weight:700;cursor:pointer;">Add</button>
      </form>
    </div>
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e6f3;overflow:hidden;box-shadow:0 2px 12px rgba(37,99,235,.07);">
      @forelse($project->team as $member)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-bottom:1px solid #f0f2f8;">
          <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:32px;height:32px;border-radius:50%;background:{{ $member->color }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:800;">{{ $member->initials }}</div>
            <div>
              <div style="font-weight:700;font-size:.85rem;color:#0c0f1a;">{{ $member->name }}</div>
              <div style="font-size:.72rem;color:#7a82a8;">{{ $member->role }}</div>
            </div>
          </div>
          <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:#3a3f5c;">{{ $member->status }}</span>
        </div>
      @empty
        <div style="padding:30px;text-align:center;color:#7a82a8;font-size:.82rem;">No team members yet.</div>
      @endforelse
    </div>
  </div>

  {{-- ── INVOICES TAB ── --}}
  <div class="proj-tab-panel" data-panel="invoices" style="display:none;">
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e6f3;padding:24px;box-shadow:0 2px 12px rgba(37,99,235,.07);margin-bottom:18px;">
      <h2 style="font-size:.95rem;font-weight:800;color:#0c0f1a;margin:0 0 14px;">Add Invoice</h2>
      <form action="{{ route('clients.invoices.store', $project->client_user_id) }}" method="POST" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
        @csrf
        <div style="flex:1;min-width:130px;">
          <label style="display:block;font-size:.74rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Invoice ID</label>
          <input type="text" name="invoice_id" required style="width:100%;padding:9px 11px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.83rem;box-sizing:border-box;">
        </div>
        <div style="flex:1;min-width:150px;">
          <label style="display:block;font-size:.74rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Date</label>
          <input type="date" name="invoice_date" required style="width:100%;padding:9px 11px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.83rem;box-sizing:border-box;">
        </div>
        <div style="width:90px;">
          <label style="display:block;font-size:.74rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Symbol</label>
          <input type="text" name="currency_symbol" value="₹" required style="width:100%;padding:9px 11px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.83rem;box-sizing:border-box;">
        </div>
        <div style="width:140px;">
          <label style="display:block;font-size:.74rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Amount (in cents/paise)</label>
          <input type="number" name="amount_cents" min="1" required style="width:100%;padding:9px 11px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.83rem;box-sizing:border-box;">
        </div>
        <div style="width:120px;">
          <label style="display:block;font-size:.74rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Status</label>
          <select name="status" required style="width:100%;padding:9px 11px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.83rem;">
            <option value="due" selected>Due</option><option value="paid">Paid</option><option value="overdue">Overdue</option>
          </select>
        </div>
        <button type="submit" style="padding:9px 18px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-size:.82rem;font-weight:700;cursor:pointer;">Add</button>
      </form>
    </div>
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e6f3;overflow:hidden;box-shadow:0 2px 12px rgba(37,99,235,.07);">
      @forelse($invoices as $inv)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-bottom:1px solid #f0f2f8;">
          <div>
            <div style="font-weight:700;font-size:.85rem;color:#0c0f1a;">{{ $inv->invoice_id }}</div>
            <div style="font-size:.72rem;color:#7a82a8;">{{ $inv->date_formatted }}</div>
          </div>
          <div style="text-align:right;">
            <div style="font-weight:800;color:#0c0f1a;">{{ $inv->amount_formatted }}</div>
            <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:#3a3f5c;">{{ $inv->status }}</span>
          </div>
        </div>
      @empty
        <div style="padding:30px;text-align:center;color:#7a82a8;font-size:.82rem;">No invoices yet.</div>
      @endforelse
    </div>
  </div>

  {{-- ── DESIGNS TAB ── --}}
  <div class="proj-tab-panel" data-panel="designs" style="display:none;">
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e6f3;padding:24px;box-shadow:0 2px 12px rgba(37,99,235,.07);margin-bottom:18px;">
      <h2 style="font-size:.95rem;font-weight:800;color:#0c0f1a;margin:0 0 14px;">Upload Design</h2>
      <form action="{{ route('client-projects.designs.store', $project->id) }}" method="POST" enctype="multipart/form-data" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
        @csrf
        <div style="flex:1;min-width:160px;">
          <label style="display:block;font-size:.74rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Title</label>
          <input type="text" name="title" required style="width:100%;padding:9px 11px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.83rem;box-sizing:border-box;">
        </div>
        <div style="width:100px;">
          <label style="display:block;font-size:.74rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Version</label>
          <input type="text" name="version" value="v1" style="width:100%;padding:9px 11px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.83rem;box-sizing:border-box;">
        </div>
        <div style="flex:1;min-width:200px;">
          <label style="display:block;font-size:.74rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Image</label>
          <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" required style="width:100%;padding:7px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.8rem;box-sizing:border-box;">
        </div>
        <div style="flex:1;min-width:180px;">
          <label style="display:block;font-size:.74rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Figma URL (optional)</label>
          <input type="text" name="figma_url" style="width:100%;padding:9px 11px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.83rem;box-sizing:border-box;">
        </div>
        <button type="submit" style="padding:9px 18px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-size:.82rem;font-weight:700;cursor:pointer;">Upload</button>
      </form>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;">
      @forelse($project->designs as $design)
        <div style="background:#fff;border-radius:12px;border:1px solid #e2e6f3;overflow:hidden;box-shadow:0 2px 12px rgba(37,99,235,.07);">
          <img src="{{ asset($design->image_path) }}" style="width:100%;height:130px;object-fit:cover;">
          <div style="padding:12px 14px;">
            <div style="font-weight:700;font-size:.85rem;color:#0c0f1a;">{{ $design->title }} <span style="color:#7a82a8;font-weight:600;">({{ $design->version }})</span></div>
            <div style="font-size:.7rem;color:#7a82a8;margin:4px 0 8px;">{{ $design->status_label }} &middot; {{ $design->comments->count() }} comments</div>
            @foreach($design->comments as $c)
              <div style="font-size:.72rem;color:#3a3f5c;padding:4px 0;border-top:1px solid #f0f2f8;">
                <strong>{{ $c->user->name ?? 'Client' }}:</strong> {{ $c->body }}
              </div>
            @endforeach
          </div>
        </div>
      @empty
        <div style="grid-column:1/-1;padding:30px;text-align:center;color:#7a82a8;font-size:.82rem;background:#fff;border-radius:12px;border:1px solid #e2e6f3;">No designs uploaded yet.</div>
      @endforelse
    </div>
  </div>

  {{-- ── CHANGE REQUESTS TAB ── --}}
  <div class="proj-tab-panel" data-panel="change-requests" style="display:none;">
    @forelse($project->changeRequests as $cr)
      <div style="background:#fff;border-radius:12px;border:1px solid #e2e6f3;padding:18px 20px;box-shadow:0 2px 12px rgba(37,99,235,.07);margin-bottom:14px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;flex-wrap:wrap;">
          <div>
            <div style="font-weight:800;font-size:.9rem;color:#0c0f1a;">{{ $cr->title }}</div>
            <div style="font-size:.76rem;color:#3a3f5c;margin:6px 0;">{{ $cr->description }}</div>
          </div>
          <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:#2563eb;background:#eef4ff;padding:4px 10px;border-radius:14px;white-space:nowrap;">{{ $cr->status_label }}</span>
        </div>
        @if($cr->status === 'submitted' || $cr->status === 'clarification_requested')
          @if($cr->client_note)
            <div style="font-size:.76rem;color:#7a82a8;margin-bottom:10px;"><strong>Client note:</strong> {{ $cr->client_note }}</div>
          @endif
          <form action="{{ route('client-projects.change-requests.respond', [$project->id, $cr->id]) }}" method="POST" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;margin-top:10px;">
            @csrf
            @method('PUT')
            <div style="width:110px;">
              <label style="display:block;font-size:.72rem;font-weight:700;color:#3a3f5c;margin-bottom:5px;">Est. Hours</label>
              <input type="number" step="0.5" name="estimated_hours" style="width:100%;padding:8px 10px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.8rem;box-sizing:border-box;">
            </div>
            <div style="width:130px;">
              <label style="display:block;font-size:.72rem;font-weight:700;color:#3a3f5c;margin-bottom:5px;">Additional Cost</label>
              <input type="number" step="0.01" name="additional_cost" style="width:100%;padding:8px 10px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.8rem;box-sizing:border-box;">
            </div>
            <div style="width:120px;">
              <label style="display:block;font-size:.72rem;font-weight:700;color:#3a3f5c;margin-bottom:5px;">Deadline Impact</label>
              <input type="text" name="deadline_impact" placeholder="+3 days" style="width:100%;padding:8px 10px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.8rem;box-sizing:border-box;">
            </div>
            <div style="flex:1;min-width:180px;">
              <label style="display:block;font-size:.72rem;font-weight:700;color:#3a3f5c;margin-bottom:5px;">Response Note</label>
              <input type="text" name="response_note" style="width:100%;padding:8px 10px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.8rem;box-sizing:border-box;">
            </div>
            <button type="submit" style="padding:8px 16px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-size:.78rem;font-weight:700;cursor:pointer;">Respond</button>
          </form>
        @else
          <div style="font-size:.76rem;color:#3a3f5c;">
            @if($cr->estimated_hours) <strong>{{ $cr->estimated_hours }}</strong> hrs &middot; @endif
            @if($cr->additional_cost) ₹{{ number_format($cr->additional_cost, 2) }} &middot; @endif
            @if($cr->deadline_impact) {{ $cr->deadline_impact }} @endif
          </div>
        @endif
      </div>
    @empty
      <div style="padding:30px;text-align:center;color:#7a82a8;font-size:.82rem;background:#fff;border-radius:12px;border:1px solid #e2e6f3;">No change requests submitted yet.</div>
    @endforelse
  </div>

  {{-- ── BUGS TAB ── --}}
  <div class="proj-tab-panel" data-panel="bugs" style="display:none;">
    @forelse($project->bugs as $bug)
      <div style="background:#fff;border-radius:12px;border:1px solid #e2e6f3;padding:18px 20px;box-shadow:0 2px 12px rgba(37,99,235,.07);margin-bottom:14px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;flex-wrap:wrap;">
          <div>
            <div style="font-weight:800;font-size:.9rem;color:#0c0f1a;">{{ $bug->title }} <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:#dc2626;">{{ $bug->priority }}</span></div>
            <div style="font-size:.76rem;color:#3a3f5c;margin:6px 0;">{{ $bug->description }}</div>
          </div>
        </div>
        <form action="{{ route('client-projects.bugs.update-status', [$project->id, $bug->id]) }}" method="POST" style="display:flex;gap:8px;align-items:center;margin-top:8px;">
          @csrf
          @method('PUT')
          <select name="status" style="padding:7px 10px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.78rem;">
            @foreach(['reported','under_review','in_progress','fixed','ready_for_testing','closed'] as $s)
              <option value="{{ $s }}" {{ $bug->status == $s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
            @endforeach
          </select>
          <button type="submit" style="padding:7px 14px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-size:.76rem;font-weight:700;cursor:pointer;">Update</button>
        </form>
      </div>
    @empty
      <div style="padding:30px;text-align:center;color:#7a82a8;font-size:.82rem;background:#fff;border-radius:12px;border:1px solid #e2e6f3;">No bugs reported yet.</div>
    @endforelse
  </div>

</div>

<script>
(function () {
    var buttons = document.querySelectorAll('.proj-tab-btn');
    var panels = document.querySelectorAll('.proj-tab-panel');

    function activate(tab) {
        panels.forEach(function (p) { p.style.display = (p.getAttribute('data-panel') === tab) ? 'block' : 'none'; });
        buttons.forEach(function (b) {
            var active = b.getAttribute('data-tab') === tab;
            b.style.color = active ? '#2563eb' : '#7a82a8';
            b.style.borderBottomColor = active ? '#2563eb' : 'transparent';
        });
    }

    buttons.forEach(function (b) {
        b.addEventListener('click', function () { activate(b.getAttribute('data-tab')); });
    });

    activate('overview');
})();
</script>
@endsection
