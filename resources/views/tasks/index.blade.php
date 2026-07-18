@extends('layouts.master')
@section('title', 'Tasks — KawachTech Software Solutions')
@section('content')

@php
    $avatarPalette = ['#1a73e8','#e91e63','#388e3c','#7c4dff','#ff7043','#00c896','#ffb830','#ff4d6d'];
    $avatarColor = fn ($name) => $avatarPalette[array_sum(array_map('ord', str_split($name ?: '?'))) % count($avatarPalette)];
    $initials = fn ($name) => strtoupper(collect(preg_split('/\s+/', trim($name ?: '?')))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode(''));
@endphp

<div id="taskBoard">

{{-- ================== STYLES ================== --}}
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

#taskBoard {
  font-family: 'Open Sans', sans-serif;
  color: var(--text);
  background: var(--bg);
  min-height: 100vh;
  transition: background .3s, color .3s;
}

#taskBoard .bl-wrap { max-width: 1600px; margin: 0 auto; padding: 28px 22px 70px; }

#taskBoard .bl-breadcrumb {
  font-size: .76rem; color: var(--muted);
  display: flex; align-items: center; gap: 6px; margin-bottom: 4px;
}
#taskBoard .bl-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 600; }
#taskBoard .bl-breadcrumb a:hover { text-decoration: underline; }

#taskBoard .bl-topbar {
  display: flex; align-items: center; justify-content: space-between;
  gap: 14px; flex-wrap: wrap; margin-bottom: 22px;
}
#taskBoard .bl-title {
  font-family: 'Nunito', sans-serif;
  font-weight: 900; font-size: 1.55rem; color: var(--text); line-height: 1.2;
  display: flex; align-items: center; gap: 10px;
}
#taskBoard .bl-title .bl-title-icon {
  width: 38px; height: 38px; border-radius: 10px;
  background: linear-gradient(135deg, var(--primary), var(--accent));
  color: #fff; display: inline-flex; align-items: center; justify-content: center;
  font-size: 1rem; box-shadow: 0 4px 14px rgba(26,115,232,.35);
}

#taskBoard .btn-bl {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 9px 18px; border-radius: 8px;
  font-size: .84rem; font-weight: 700; cursor: pointer; border: none;
  transition: all .2s; font-family: 'Open Sans', sans-serif;
  white-space: nowrap; text-decoration: none;
}
#taskBoard .btn-bl:hover { transform: translateY(-1px); }
#taskBoard .btn-bl.btn-sm { padding: 6px 14px; font-size: .78rem; }
#taskBoard .btn-primary { background: var(--primary); color: #fff; }
#taskBoard .btn-primary:hover { background: var(--primary-dark); box-shadow: 0 4px 14px rgba(26,115,232,.35); color: #fff; }
#taskBoard .btn-outline { background: var(--card); color: var(--text); border: 1.5px solid var(--border); }
#taskBoard .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
#taskBoard .btn-danger { background: var(--danger); color: #fff; }
#taskBoard .btn-danger:hover { background: #e03050; box-shadow: 0 4px 14px rgba(255,77,109,.3); color: #fff; }

/* ── BOARD / COLUMNS ── */
#taskBoard .board-outer { display: flex; align-items: flex-start; gap: 16px; overflow-x: auto; padding-bottom: 18px; }
#taskBoard .board-scroll { display: flex; gap: 16px; }

#taskBoard .task-list {
  flex: 0 0 288px;
  width: 288px;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 14px;
  box-shadow: var(--shadow);
  transition: background .3s, border-color .3s, box-shadow .2s, transform .2s;
}
#taskBoard .task-list-header {
  display: flex; align-items: center; gap: 8px;
  margin-bottom: 12px; cursor: grab; user-select: none;
}
#taskBoard .list-name {
  font-family: 'Nunito', sans-serif; font-weight: 800; font-size: .95rem;
  color: var(--text); flex: 1; word-break: break-word; min-width: 0;
}
#taskBoard .list-count {
  background: var(--bg); color: var(--muted);
  font-size: .72rem; font-weight: 800; border-radius: 20px;
  padding: 1px 9px; flex-shrink: 0; border: 1px solid var(--border);
}
#taskBoard .list-name-input {
  font-family: 'Nunito', sans-serif; font-weight: 800; font-size: .95rem;
  width: 100%; border: 1.5px solid var(--primary); border-radius: 6px;
  padding: 3px 6px; color: var(--text); background: var(--white);
}
#taskBoard .list-actions { display: flex; gap: 2px; flex-shrink: 0; }
#taskBoard .list-actions button {
  border: none; background: transparent; color: var(--muted);
  cursor: pointer; font-size: .78rem; padding: 3px 5px; border-radius: 5px;
}
#taskBoard .list-actions button:hover { color: var(--primary); background: var(--bg); }
#taskBoard .list-actions button.del-list-btn:hover { color: var(--danger); }
#taskBoard .task-list.list-chosen { opacity: .9; transform: rotate(1deg) scale(1.01); box-shadow: var(--shadow-md); }

#taskBoard .card-container { min-height: 10px; }

/* ── CARDS ── */
#taskBoard .task-card {
  position: relative;
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 11px 12px 11px 16px;
  margin-bottom: 9px;
  cursor: pointer;
  overflow: hidden;
  user-select: none;
  transition: box-shadow .15s, transform .15s, border-color .3s, background .3s;
}
#taskBoard .task-card::before {
  content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
}
#taskBoard .task-card.priority-low::before    { background: var(--success); }
#taskBoard .task-card.priority-medium::before { background: var(--warning); }
#taskBoard .task-card.priority-high::before   { background: var(--danger); }
#taskBoard .task-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); border-color: var(--primary); }

#taskBoard .card-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px; }
#taskBoard .card-id { font-size: .68rem; color: var(--muted); font-weight: 700; }
#taskBoard .card-title { font-size: .87rem; font-weight: 700; color: var(--text); margin-bottom: 8px; word-break: break-word; line-height: 1.35; }

#taskBoard .card-footer { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
#taskBoard .card-footer-left { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; min-width: 0; }

#taskBoard .priority-badge {
  display: inline-block; font-size: .65rem; font-weight: 800;
  padding: 2px 8px; border-radius: 20px; text-transform: uppercase; letter-spacing: .3px;
}
#taskBoard .priority-badge.low    { background: #e0faf3; color: #00915e; }
#taskBoard .priority-badge.medium { background: #fff5df; color: #a06a00; }
#taskBoard .priority-badge.high   { background: #ffe0e6; color: #d1264a; }
html[data-theme="dark"] #taskBoard .priority-badge.low    { background: rgba(0,200,150,.15); color: #4ce0b3; }
html[data-theme="dark"] #taskBoard .priority-badge.medium { background: rgba(255,184,48,.15); color: #ffcf70; }
html[data-theme="dark"] #taskBoard .priority-badge.high   { background: rgba(255,77,109,.15); color: #ff8ca0; }

#taskBoard .due-date { font-size: .72rem; color: var(--muted); display: inline-flex; align-items: center; gap: 4px; }
#taskBoard .due-date.overdue { color: var(--danger); font-weight: 700; }
#taskBoard .comment-badge { font-size: .72rem; color: var(--muted); display: inline-flex; align-items: center; gap: 4px; }

#taskBoard .avatar-chip {
  width: 26px; height: 26px; border-radius: 50%; flex-shrink: 0;
  display: inline-flex; align-items: center; justify-content: center;
  font-size: .66rem; font-weight: 800;
}

#taskBoard .add-card-btn, #taskBoard .add-list-toggle {
  display: flex; align-items: center; gap: 6px;
  width: 100%; text-align: left; background: transparent; border: none;
  color: var(--muted); font-size: .82rem; font-weight: 600;
  padding: 8px 6px; border-radius: 6px; cursor: pointer;
}
#taskBoard .add-card-btn:hover, #taskBoard .add-list-toggle:hover { background: var(--bg); color: var(--primary); }

#taskBoard .add-list-col {
  flex: 0 0 288px; width: 288px;
  border: 1.5px dashed var(--border);
  border-radius: var(--radius);
  padding: 6px;
}
#taskBoard .add-list-form { display: none; gap: 8px; flex-direction: column; padding: 6px; }
#taskBoard .add-list-form.show { display: flex; }
#taskBoard .add-list-form input {
  border: 1.5px solid var(--border); border-radius: 8px; padding: 8px 10px;
  font-size: .84rem; background: var(--bg); color: var(--text); outline: none;
}
#taskBoard .add-list-form input:focus { border-color: var(--primary); }
#taskBoard .add-list-form .form-actions { display: flex; gap: 8px; }

#taskBoard .empty-board {
  color: var(--muted); font-size: .9rem; padding: 40px 0; text-align: center; width: 100%;
}

/* ── DRAG STATES (Sortable, forceFallback) ── */
#taskBoard .card-ghost {
  background: var(--bg) !important;
  border: 2px dashed var(--primary) !important;
  box-shadow: none !important;
}
#taskBoard .card-ghost > * { visibility: hidden; }
#taskBoard .card-chosen { box-shadow: var(--shadow-md); cursor: grabbing; }
#taskBoard .card-drag-fallback {
  /* Sortable repositions this clone via an inline transform on every mouse
     move; the base .task-card transition on "transform" would ease toward
     each new position instead of snapping, making it visibly lag behind the
     cursor. Killing the transition here lets it track the pointer 1:1. */
  transition: none !important;
  opacity: .95;
  box-shadow: 0 14px 30px rgba(0,0,0,.28) !important;
  cursor: grabbing;
}
#taskBoard .list-ghost { opacity: .35; }
#taskBoard .task-list.list-chosen { transition: none !important; }

body.dragging-active,
body.dragging-active * {
  user-select: none !important;
  -webkit-user-select: none !important;
}

/* ── MODAL ── */
#cardModal .modal-content {
  border-radius: 16px; border: none; overflow: hidden;
  background: var(--card); color: var(--text);
}
#cardModal .modal-header { border-bottom: 1px solid var(--border); padding: 18px 24px; }
#cardModal .modal-title { font-family: 'Nunito', sans-serif; font-weight: 800; }
#cardModal .modal-body { padding: 22px 24px; background: var(--bg); }
#cardModal .modal-footer { border-top: 1px solid var(--border); padding: 14px 24px; }
#cardModal label.form-label { font-size: .78rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .3px; }
#cardModal .form-control, #cardModal .form-select {
  border-radius: 8px; border: 1.5px solid var(--border);
  background: var(--card); color: var(--text); font-size: .9rem;
}
#cardModal .form-control:focus, #cardModal .form-select:focus {
  border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,115,232,.12);
}
#cardModal #cardTitle { font-size: 1.05rem; font-weight: 700; }
#cardModal .ticket-sidebar {
  background: var(--card); border: 1px solid var(--border); border-radius: 12px;
  padding: 16px; height: 100%;
}
#cardModal .ticket-section-title {
  font-family: 'Nunito', sans-serif; font-weight: 800; font-size: .85rem;
  color: var(--text); margin: 18px 0 10px; display: flex; align-items: center; gap: 6px;
}
#cardModal .comment-composer { display: flex; gap: 8px; align-items: flex-start; margin-bottom: 14px; }
#cardModal .comment-composer textarea {
  flex: 1; border-radius: 8px; border: 1.5px solid var(--border);
  background: var(--card); color: var(--text); font-size: .85rem; padding: 8px 10px; resize: vertical;
}
#cardModal .comment-composer textarea:focus { outline: none; border-color: var(--primary); }
#cardModal .comments-thread { display: flex; flex-direction: column; gap: 12px; max-height: 260px; overflow-y: auto; padding-right: 2px; }
#cardModal .comment-item { display: flex; gap: 10px; align-items: flex-start; }
#cardModal .comment-avatar {
  width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
  display: inline-flex; align-items: center; justify-content: center; font-size: .68rem; font-weight: 800;
}
#cardModal .comment-body { flex: 1; background: var(--card); border: 1px solid var(--border); border-radius: 10px; padding: 8px 12px; position: relative; }
#cardModal .comment-meta { display: flex; align-items: baseline; gap: 8px; margin-bottom: 3px; }
#cardModal .comment-meta strong { font-size: .82rem; }
#cardModal .comment-time { font-size: .68rem; color: var(--muted); }
#cardModal .comment-body p { font-size: .84rem; margin: 0; color: var(--text); white-space: pre-wrap; word-break: break-word; }
#cardModal .comment-delete-btn {
  position: absolute; top: 6px; right: 8px; border: none; background: transparent;
  color: var(--muted); font-size: .72rem; cursor: pointer; padding: 2px 4px;
}
#cardModal .comment-delete-btn:hover { color: var(--danger); }
#cardModal .comments-empty { color: var(--muted); font-size: .82rem; padding: 8px 0; }
</style>

<div class="bl-wrap">
  <div class="bl-breadcrumb"><a href="{{ route('dashboard') }}">Dashboard</a> <span>/</span> <span>Tasks</span></div>
  <div class="bl-topbar">
    <div class="bl-title"><span class="bl-title-icon"><i class="fas fa-columns"></i></span> Tasks</div>
  </div>

  <div class="board-outer">
    <div class="board-scroll" id="boardScroll">
      @foreach($lists as $list)
        <div class="task-list" data-list-id="{{ $list->id }}">
          <div class="task-list-header">
            <span class="list-name">{{ $list->name }}</span>
            <span class="list-count">{{ $list->cards->count() }}</span>
            @can('tasks.manage-columns')
              <span class="list-actions">
                <button type="button" class="rename-list-btn" title="Rename"><i class="fas fa-pen"></i></button>
                <button type="button" class="del-list-btn" title="Delete"><i class="fas fa-trash"></i></button>
              </span>
            @endcan
          </div>

          <div class="card-container" data-list-id="{{ $list->id }}">
            @foreach($list->cards as $card)
              @php
                $cardData = [
                    'id' => $card->id,
                    'task_list_id' => $card->task_list_id,
                    'title' => $card->title,
                    'description' => $card->description,
                    'assignee_id' => $card->assignee_id,
                    'due_date' => optional($card->due_date)->format('Y-m-d'),
                    'priority' => $card->priority,
                    'assignee' => $card->assignee ? ['id' => $card->assignee->id, 'name' => $card->assignee->name] : null,
                    'comments_count' => $card->comments_count ?? 0,
                ];
              @endphp
              <div class="task-card priority-{{ $card->priority }}"
                   data-card-id="{{ $card->id }}"
                   data-card='@json($cardData)'>
                <div class="card-top">
                  <span class="card-id">#{{ $card->id }}</span>
                  <span class="priority-badge {{ $card->priority }}">{{ ucfirst($card->priority) }}</span>
                </div>
                <div class="card-title">{{ $card->title }}</div>
                <div class="card-footer">
                  <div class="card-footer-left">
                    @if($card->due_date)
                      <span class="due-date {{ $card->due_date->isPast() ? 'overdue' : '' }}">
                        <i class="fas fa-calendar"></i> {{ $card->due_date->format('M d') }}
                      </span>
                    @endif
                    @if($card->comments_count)
                      <span class="comment-badge"><i class="fas fa-comment-dots"></i> {{ $card->comments_count }}</span>
                    @endif
                  </div>
                  @if($card->assignee)
                    <div class="avatar-chip" title="{{ $card->assignee->name }}"
                         style="background:{{ $avatarColor($card->assignee->name) }}1a; color:{{ $avatarColor($card->assignee->name) }};">
                      {{ $initials($card->assignee->name) }}
                    </div>
                  @endif
                </div>
              </div>
            @endforeach
          </div>

          @can('tasks.create')
            <button type="button" class="add-card-btn" data-list-id="{{ $list->id }}">
              <i class="fas fa-plus"></i> Add a card
            </button>
          @endcan
        </div>
      @endforeach

      @if($lists->isEmpty() && !auth()->user()->can('tasks.manage-columns'))
        <div class="empty-board">No task columns yet. Ask an admin to set up the board.</div>
      @endif
    </div>

    @can('tasks.manage-columns')
      <div class="add-list-col">
        <button type="button" class="add-list-toggle" id="addListToggle"><i class="fas fa-plus"></i> Add column</button>
        <form class="add-list-form" id="addListForm">
          <input type="text" id="newListName" placeholder="Column name" maxlength="100" autocomplete="off">
          <div class="form-actions">
            <button type="submit" class="btn-bl btn-primary btn-sm">Add</button>
            <button type="button" class="btn-bl btn-outline btn-sm" id="cancelAddList">Cancel</button>
          </div>
        </form>
      </div>
    @endcan
  </div>
</div>

{{-- ================== CARD MODAL ================== --}}
<div class="modal fade" id="cardModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cardModalTitle">New Ticket</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="cardForm" onsubmit="return false;">
          <input type="hidden" id="cardId">
          <input type="hidden" id="cardListId">
          <div class="row">
            <div class="col-md-8">
              <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" id="cardTitle" maxlength="255" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" id="cardDescription" rows="3"></textarea>
              </div>

              <div id="ticketCommentsSection">
                <h6 class="ticket-section-title"><i class="fas fa-comment-dots"></i> Comments</h6>
                <div class="comment-composer">
                  <textarea id="newCommentBody" rows="2" placeholder="Write a comment…" maxlength="2000"></textarea>
                  <button type="button" class="btn-bl btn-primary btn-sm" id="postCommentBtn">Comment</button>
                </div>
                <div class="comments-thread" id="commentsThread"></div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="ticket-sidebar">
                <div class="mb-3">
                  <label class="form-label">Assignee</label>
                  <select class="form-select" id="cardAssignee">
                    <option value="">Unassigned</option>
                    @foreach($users as $u)
                      <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Due date</label>
                  <input type="date" class="form-control" id="cardDueDate">
                </div>
                <div class="mb-2">
                  <label class="form-label">Priority</label>
                  <select class="form-select" id="cardPriority">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-bl btn-danger me-auto d-none" id="deleteCardBtn"><i class="fas fa-trash"></i> Delete</button>
        <button type="button" class="btn-bl btn-outline" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn-bl btn-primary" id="saveCardBtn">Save</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
<script>
(function () {
  const CSRF = document.querySelector('meta[name="csrf-token"]').content;
  const CAN = @json($can);
  const AVATAR_PALETTE = ['#1a73e8','#e91e63','#388e3c','#7c4dff','#ff7043','#00c896','#ffb830','#ff4d6d'];

  const ROUTES = {
    listsStore:    '{{ route('tasks.lists.store') }}',
    listsBase:     '{{ url('tasks/lists') }}',
    listsReorder:  '{{ route('tasks.lists.reorder') }}',
    cardsStore:    '{{ route('tasks.cards.store') }}',
    cardsBase:     '{{ url('tasks/cards') }}',
    commentsBase:  '{{ url('tasks/comments') }}',
  };

  function toast(msg, color, icon) {
    if (typeof window.showToast === 'function') { window.showToast(msg, color, icon); return; }
    const stack = document.getElementById('toastStack');
    if (!stack) return;
    const el = document.createElement('div');
    el.className = 'toast-msg';
    el.innerHTML = `<i class="${icon || 'fas fa-info-circle'}" style="color:${color || '#1a73e8'};"></i> ${msg}`;
    stack.appendChild(el);
    setTimeout(() => { el.style.opacity = '0'; setTimeout(() => el.remove(), 300); }, 3200);
  }

  async function api(url, method, body) {
    const res = await fetch(url, {
      method,
      headers: {
        'X-CSRF-TOKEN': CSRF,
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: body ? JSON.stringify(body) : undefined,
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(data.message || 'Something went wrong');
    return data;
  }

  function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str ?? '';
    return div.innerHTML;
  }

  function isPast(dateStr) {
    if (!dateStr) return false;
    const today = new Date(); today.setHours(0, 0, 0, 0);
    return new Date(dateStr) < today;
  }

  function formatDate(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('en-US', { month: 'short', day: '2-digit' });
  }

  function avatarColor(name) {
    const sum = (name || '?').split('').reduce((a, c) => a + c.charCodeAt(0), 0);
    return AVATAR_PALETTE[sum % AVATAR_PALETTE.length];
  }

  function initials(name) {
    return (name || '?').trim().split(/\s+/).slice(0, 2).map(p => p[0]).join('').toUpperCase();
  }

  function timeAgo(iso) {
    const diff = (Date.now() - new Date(iso).getTime()) / 1000;
    if (diff < 60) return 'just now';
    if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
    return Math.floor(diff / 86400) + 'd ago';
  }

  function cardEl(card) {
    const div = document.createElement('div');
    div.className = 'task-card priority-' + card.priority;
    div.dataset.cardId = card.id;
    div.dataset.card = JSON.stringify(card);
    const commentsCount = card.comments_count || 0;
    div.innerHTML = `
      <div class="card-top">
        <span class="card-id">#${card.id}</span>
        <span class="priority-badge ${card.priority}">${card.priority.charAt(0).toUpperCase() + card.priority.slice(1)}</span>
      </div>
      <div class="card-title">${escapeHtml(card.title)}</div>
      <div class="card-footer">
        <div class="card-footer-left">
          ${card.due_date ? `<span class="due-date ${isPast(card.due_date) ? 'overdue' : ''}"><i class="fas fa-calendar"></i> ${formatDate(card.due_date)}</span>` : ''}
          ${commentsCount ? `<span class="comment-badge"><i class="fas fa-comment-dots"></i> ${commentsCount}</span>` : ''}
        </div>
        ${card.assignee ? `<div class="avatar-chip" title="${escapeHtml(card.assignee.name)}" style="background:${avatarColor(card.assignee.name)}1a;color:${avatarColor(card.assignee.name)};">${initials(card.assignee.name)}</div>` : ''}
      </div>`;
    div.addEventListener('click', () => openCardModal(card));
    return div;
  }

  function updateListCount(listEl) {
    const countEl = listEl.querySelector('.list-count');
    if (countEl) countEl.textContent = listEl.querySelectorAll('.task-card').length;
  }

  function initCardSortable(container) {
    if (typeof Sortable === 'undefined') return;
    new Sortable(container, {
      group: 'cards',
      animation: 200,
      easing: 'cubic-bezier(0.2, 0, 0, 1)',
      forceFallback: true,
      fallbackTolerance: 0,
      swapThreshold: 0.65,
      disabled: !CAN.edit,
      ghostClass: 'card-ghost',
      chosenClass: 'card-chosen',
      dragClass: 'card-drag-fallback',
      onStart: () => document.body.classList.add('dragging-active'),
      onEnd: async (evt) => {
        document.body.classList.remove('dragging-active');
        const listEl = evt.to;
        const listId = listEl.dataset.listId;
        const cardIds = Array.from(listEl.querySelectorAll('.task-card')).map(el => el.dataset.cardId);
        updateListCount(evt.from);
        updateListCount(evt.to);
        try {
          await api(`${ROUTES.cardsBase}/${evt.item.dataset.cardId}/move`, 'PATCH', {
            task_list_id: listId,
            card_ids: cardIds,
          });
        } catch (e) {
          toast(e.message, '#ff4d6d', 'fas fa-exclamation-circle');
        }
      },
    });
  }

  document.querySelectorAll('.card-container').forEach(initCardSortable);

  if (CAN.manageColumns && typeof Sortable !== 'undefined') {
    new Sortable(document.getElementById('boardScroll'), {
      handle: '.task-list-header',
      animation: 200,
      easing: 'cubic-bezier(0.2, 0, 0, 1)',
      forceFallback: true,
      fallbackTolerance: 0,
      ghostClass: 'list-ghost',
      chosenClass: 'list-chosen',
      onStart: () => document.body.classList.add('dragging-active'),
      onEnd: async () => {
        document.body.classList.remove('dragging-active');
        const order = Array.from(document.querySelectorAll('.task-list')).map(el => el.dataset.listId);
        try {
          await api(ROUTES.listsReorder, 'PATCH', { order });
        } catch (e) {
          toast(e.message, '#ff4d6d', 'fas fa-exclamation-circle');
        }
      },
    });
  }

  // ── Card modal (create / edit) ──────────────────────────────
  const cardModalEl = document.getElementById('cardModal');
  const cardModal = new bootstrap.Modal(cardModalEl);
  let currentCard = null;

  function renderComments(comments) {
    const thread = document.getElementById('commentsThread');
    thread.innerHTML = '';
    if (!comments.length) {
      thread.innerHTML = '<div class="comments-empty">No comments yet — be the first to say something.</div>';
      return;
    }
    comments.forEach(c => thread.appendChild(commentEl(c)));
    thread.scrollTop = thread.scrollHeight;
  }

  function commentEl(c) {
    const div = document.createElement('div');
    div.className = 'comment-item';
    div.dataset.commentId = c.id;
    div.innerHTML = `
      <div class="comment-avatar" style="background:${avatarColor(c.user.name)}1a;color:${avatarColor(c.user.name)};">${initials(c.user.name)}</div>
      <div class="comment-body">
        <div class="comment-meta"><strong>${escapeHtml(c.user.name)}</strong><span class="comment-time">${timeAgo(c.created_at)}</span></div>
        <p>${escapeHtml(c.body)}</p>
        ${c.can_delete ? `<button type="button" class="comment-delete-btn" data-id="${c.id}" title="Delete"><i class="fas fa-times"></i></button>` : ''}
      </div>`;
    return div;
  }

  function updateCardFace(card) {
    const el = document.querySelector(`.task-card[data-card-id="${card.id}"]`);
    if (el) el.replaceWith(cardEl(card));
  }

  async function loadComments(cardId) {
    const thread = document.getElementById('commentsThread');
    thread.innerHTML = '<div class="comments-empty">Loading…</div>';
    try {
      const comments = await api(`${ROUTES.cardsBase}/${cardId}/comments`, 'GET');
      renderComments(comments);
    } catch (e) {
      thread.innerHTML = '<div class="comments-empty">Failed to load comments.</div>';
    }
  }

  function openCardModal(card) {
    const isEdit = !!(card && card.id);
    const editable = isEdit ? CAN.edit : CAN.create;
    currentCard = card ? { ...card } : null;

    document.getElementById('cardModalTitle').textContent = isEdit ? 'Edit Ticket' : 'New Ticket';
    document.getElementById('cardId').value = isEdit ? card.id : '';
    document.getElementById('cardListId').value = card ? card.task_list_id : '';
    document.getElementById('cardTitle').value = card ? card.title : '';
    document.getElementById('cardDescription').value = card ? (card.description || '') : '';
    document.getElementById('cardAssignee').value = card && card.assignee_id ? card.assignee_id : '';
    document.getElementById('cardDueDate').value = card ? (card.due_date || '') : '';
    document.getElementById('cardPriority').value = card ? card.priority : 'medium';

    ['cardTitle', 'cardDescription', 'cardAssignee', 'cardDueDate', 'cardPriority'].forEach(id => {
      document.getElementById(id).disabled = !editable;
    });

    document.getElementById('deleteCardBtn').classList.toggle('d-none', !(isEdit && CAN.delete));
    document.getElementById('saveCardBtn').classList.toggle('d-none', !editable);

    document.getElementById('ticketCommentsSection').style.display = isEdit ? '' : 'none';
    if (isEdit) {
      loadComments(card.id);
    }

    cardModal.show();
  }

  document.querySelectorAll('.task-card').forEach(el => {
    el.addEventListener('click', () => openCardModal(JSON.parse(el.dataset.card)));
  });

  document.querySelectorAll('.add-card-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      openCardModal({ task_list_id: btn.dataset.listId, priority: 'medium' });
    });
  });

  document.getElementById('saveCardBtn').addEventListener('click', async () => {
    const id = document.getElementById('cardId').value;
    const title = document.getElementById('cardTitle').value.trim();
    if (!title) { toast('Title is required', '#ff4d6d', 'fas fa-exclamation-circle'); return; }

    const payload = {
      title,
      description: document.getElementById('cardDescription').value,
      assignee_id: document.getElementById('cardAssignee').value || null,
      due_date: document.getElementById('cardDueDate').value || null,
      priority: document.getElementById('cardPriority').value,
    };

    try {
      if (id) {
        const updated = await api(`${ROUTES.cardsBase}/${id}`, 'PUT', payload);
        updateCardFace(updated);
        toast('Ticket updated', '#00c896', 'fas fa-check-circle');
      } else {
        payload.task_list_id = document.getElementById('cardListId').value;
        const created = await api(ROUTES.cardsStore, 'POST', payload);
        const container = document.querySelector(`.card-container[data-list-id="${created.task_list_id}"]`);
        if (container) container.appendChild(cardEl(created));
        const listEl = container ? container.closest('.task-list') : null;
        if (listEl) updateListCount(listEl);
        toast('Ticket created', '#00c896', 'fas fa-check-circle');
      }
      cardModal.hide();
    } catch (e) {
      toast(e.message, '#ff4d6d', 'fas fa-exclamation-circle');
    }
  });

  document.getElementById('deleteCardBtn').addEventListener('click', async () => {
    const id = document.getElementById('cardId').value;
    if (!id) return;
    if (!confirm('Delete this ticket? This cannot be undone.')) return;
    try {
      await api(`${ROUTES.cardsBase}/${id}`, 'DELETE');
      const el = document.querySelector(`.task-card[data-card-id="${id}"]`);
      const listEl = el ? el.closest('.task-list') : null;
      if (el) el.remove();
      if (listEl) updateListCount(listEl);
      cardModal.hide();
      toast('Ticket deleted', '#ff4d6d', 'fas fa-trash');
    } catch (e) {
      toast(e.message, '#ff4d6d', 'fas fa-exclamation-circle');
    }
  });

  // ── Comments ─────────────────────────────────────────────────
  document.getElementById('postCommentBtn').addEventListener('click', async () => {
    const cardId = document.getElementById('cardId').value;
    const textarea = document.getElementById('newCommentBody');
    const body = textarea.value.trim();
    if (!cardId || !body) return;

    try {
      const comment = await api(`${ROUTES.cardsBase}/${cardId}/comments`, 'POST', { body });
      const thread = document.getElementById('commentsThread');
      const empty = thread.querySelector('.comments-empty');
      if (empty) empty.remove();
      thread.appendChild(commentEl(comment));
      thread.scrollTop = thread.scrollHeight;
      textarea.value = '';

      if (currentCard) {
        currentCard.comments_count = (currentCard.comments_count || 0) + 1;
        updateCardFace(currentCard);
      }
    } catch (e) {
      toast(e.message, '#ff4d6d', 'fas fa-exclamation-circle');
    }
  });

  document.getElementById('commentsThread').addEventListener('click', async (e) => {
    const btn = e.target.closest('.comment-delete-btn');
    if (!btn) return;
    if (!confirm('Delete this comment?')) return;
    try {
      await api(`${ROUTES.commentsBase}/${btn.dataset.id}`, 'DELETE');
      btn.closest('.comment-item').remove();
      if (currentCard) {
        currentCard.comments_count = Math.max(0, (currentCard.comments_count || 1) - 1);
        updateCardFace(currentCard);
      }
    } catch (e) {
      toast(e.message, '#ff4d6d', 'fas fa-exclamation-circle');
    }
  });

  // ── Column management ───────────────────────────────────────
  const addListToggle = document.getElementById('addListToggle');
  const addListForm = document.getElementById('addListForm');
  if (addListToggle) {
    addListToggle.addEventListener('click', () => {
      addListForm.classList.add('show');
      addListToggle.style.display = 'none';
      document.getElementById('newListName').focus();
    });
    document.getElementById('cancelAddList').addEventListener('click', () => {
      addListForm.classList.remove('show');
      addListToggle.style.display = '';
      document.getElementById('newListName').value = '';
    });
    addListForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const name = document.getElementById('newListName').value.trim();
      if (!name) return;
      try {
        const list = await api(ROUTES.listsStore, 'POST', { name });
        const col = document.createElement('div');
        col.className = 'task-list';
        col.dataset.listId = list.id;
        col.innerHTML = `
          <div class="task-list-header">
            <span class="list-name">${escapeHtml(list.name)}</span>
            <span class="list-count">0</span>
            <span class="list-actions">
              <button type="button" class="rename-list-btn" title="Rename"><i class="fas fa-pen"></i></button>
              <button type="button" class="del-list-btn" title="Delete"><i class="fas fa-trash"></i></button>
            </span>
          </div>
          <div class="card-container" data-list-id="${list.id}"></div>
          ${CAN.create ? `<button type="button" class="add-card-btn" data-list-id="${list.id}"><i class="fas fa-plus"></i> Add a card</button>` : ''}
        `;
        document.getElementById('boardScroll').appendChild(col);
        initCardSortable(col.querySelector('.card-container'));
        bindColumnActions(col);
        if (CAN.create) {
          col.querySelector('.add-card-btn').addEventListener('click', () => {
            openCardModal({ task_list_id: list.id, priority: 'medium' });
          });
        }
        addListForm.classList.remove('show');
        addListToggle.style.display = '';
        document.getElementById('newListName').value = '';
        toast('Column added', '#00c896', 'fas fa-check-circle');
      } catch (err) {
        toast(err.message, '#ff4d6d', 'fas fa-exclamation-circle');
      }
    });
  }

  function bindColumnActions(col) {
    const listId = col.dataset.listId;
    const renameBtn = col.querySelector('.rename-list-btn');
    const delBtn = col.querySelector('.del-list-btn');

    if (renameBtn) {
      renameBtn.addEventListener('click', () => {
        const nameEl = col.querySelector('.list-name');
        const current = nameEl.textContent;
        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'list-name-input';
        input.value = current;
        input.maxLength = 100;
        nameEl.replaceWith(input);
        input.focus();
        input.select();

        const save = async () => {
          const newName = input.value.trim() || current;
          try {
            await api(`${ROUTES.listsBase}/${listId}`, 'PUT', { name: newName });
            const span = document.createElement('span');
            span.className = 'list-name';
            span.textContent = newName;
            input.replaceWith(span);
          } catch (e) {
            toast(e.message, '#ff4d6d', 'fas fa-exclamation-circle');
            const span = document.createElement('span');
            span.className = 'list-name';
            span.textContent = current;
            input.replaceWith(span);
          }
        };

        input.addEventListener('keydown', (e) => {
          if (e.key === 'Enter') { e.preventDefault(); input.blur(); }
          if (e.key === 'Escape') { input.value = current; input.blur(); }
        });
        input.addEventListener('blur', save, { once: true });
      });
    }

    if (delBtn) {
      delBtn.addEventListener('click', async () => {
        const cardCount = col.querySelectorAll('.task-card').length;
        const name = col.querySelector('.list-name').textContent;
        const msg = cardCount > 0
          ? `Delete "${name}"? This will also delete ${cardCount} card(s).`
          : `Delete "${name}"?`;
        if (!confirm(msg)) return;
        try {
          await api(`${ROUTES.listsBase}/${listId}`, 'DELETE');
          col.remove();
          toast('Column deleted', '#ff4d6d', 'fas fa-trash');
        } catch (e) {
          toast(e.message, '#ff4d6d', 'fas fa-exclamation-circle');
        }
      });
    }
  }

  document.querySelectorAll('.task-list').forEach(bindColumnActions);

  // ── Deep link from a notification (?card=ID) ────────────────
  const params = new URLSearchParams(window.location.search);
  const wantedCardId = params.get('card');
  if (wantedCardId) {
    const el = document.querySelector(`.task-card[data-card-id="${wantedCardId}"]`);
    if (el) openCardModal(JSON.parse(el.dataset.card));
    const url = new URL(window.location.href);
    url.searchParams.delete('card');
    window.history.replaceState({}, '', url);
  }
})();
</script>
@endpush
