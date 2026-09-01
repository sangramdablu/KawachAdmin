@extends('layouts.master')
@section('title', 'Team — KawachTech')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap');

#teamPage {
  font-family: 'Open Sans', sans-serif;
  color: var(--text-dark);
  background: var(--bg-body);
  min-height: 100vh;
}
#teamPage * { box-sizing: border-box; }
.team-wrap { max-width: 1400px; margin: 0 auto; padding: 26px 20px 70px; }

.team-topbar { display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:22px; }
.team-breadcrumb { font-size:.73rem;color:var(--text-muted);display:flex;align-items:center;gap:6px;margin-bottom:4px; }
.team-breadcrumb a { color:var(--primary);text-decoration:none;font-weight:600; }
.team-breadcrumb a:hover { text-decoration:underline; }
.team-page-title { font-family:'Nunito',sans-serif;font-weight:900;font-size:1.5rem;color:var(--text-dark);display:flex;align-items:center;gap:10px; }
.team-page-title i { color:var(--primary);font-size:1.2rem; }
.team-page-sub { font-size:.8rem;color:var(--text-muted);margin-top:4px; }

.team-stats { display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:22px; }
.team-stat-card { background:var(--card-bg);border:1px solid var(--border);border-radius:var(--card-radius);padding:16px 18px;display:flex;align-items:center;gap:14px;box-shadow:0 2px 12px rgba(26,115,232,.06); }
.team-stat-icon { width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;background:#e8f1fd;color:var(--primary); }
[data-theme="dark"] .team-stat-icon { background:rgba(26,115,232,.18); }
.team-stat-val { font-family:'Nunito',sans-serif;font-weight:900;font-size:1.5rem;color:var(--text-dark);line-height:1; }
.team-stat-lbl { font-size:.72rem;color:var(--text-muted);margin-top:2px; }

.team-card { background:var(--card-bg);border:1px solid var(--border);border-radius:var(--card-radius);box-shadow:0 2px 14px rgba(26,115,232,.07);overflow:hidden; }
.team-card-header { display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--border);background:var(--modal-header); }
.team-card-header h2 { font-family:'Nunito',sans-serif;font-weight:900;font-size:.95rem;color:var(--panel-title);display:flex;align-items:center;gap:8px;margin:0; }
.team-card-header h2 i { color:var(--primary); }
.team-card-header .hint { font-size:.74rem;color:var(--text-muted); }
.team-card-header .hint a { color:var(--primary);font-weight:700;text-decoration:none; }
.team-card-header .hint a:hover { text-decoration:underline; }

.team-table-wrap { overflow-x:auto; }
.team-table { width:100%;border-collapse:collapse;font-size:.83rem; }
.team-table thead tr { background:var(--modal-header);border-bottom:2px solid var(--border); }
.team-table th { padding:11px 16px;text-align:left;font-family:'Nunito',sans-serif;font-weight:800;font-size:.74rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;white-space:nowrap; }
.team-table td { padding:12px 16px;border-bottom:1px solid var(--border);color:var(--text-dark);vertical-align:middle; }
.team-table tbody tr:hover { background:var(--modal-header); }
.team-table tbody tr:last-child td { border-bottom:none; }

.team-avatar { width:38px;height:38px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-family:'Nunito',sans-serif;font-weight:800;font-size:.78rem;color:#fff;flex-shrink:0; }
.team-user-cell { display:flex;align-items:center;gap:10px; }
.team-user-name { font-weight:700;font-size:.86rem;color:var(--text-dark); }
.team-user-email { font-size:.73rem;color:var(--text-muted); }
.team-designation { font-weight:600; }
.team-role-pill { display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;background:#eef2f9;color:var(--text-muted); }
[data-theme="dark"] .team-role-pill { background:rgba(138,155,181,.15); }
.team-responsibilities { font-size:.78rem;color:var(--text-muted);max-width:260px;white-space:normal; }

.role-badge { display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;white-space:nowrap; }
.role-badge.superadmin { background:#f0e8ff;color:#6c2bd9; }
.role-badge.admin      { background:#e8f1fd;color:var(--primary); }
.role-badge.editor     { background:#e0faf3;color:#00a87c; }
.role-badge.viewer     { background:#eef2f9;color:var(--text-muted); }
.role-badge.custom     { background:#fff8e6;color:#b87a00; }
[data-theme="dark"] .role-badge.superadmin { background:rgba(108,43,217,.2); }
[data-theme="dark"] .role-badge.admin      { background:rgba(26,115,232,.2); }
[data-theme="dark"] .role-badge.editor     { background:rgba(0,200,150,.15); }
[data-theme="dark"] .role-badge.viewer     { background:rgba(138,155,181,.15); }
[data-theme="dark"] .role-badge.custom     { background:rgba(255,184,48,.15); }

.status-dot { width:7px;height:7px;border-radius:50%;display:inline-block;margin-right:4px; }
.status-dot.active   { background:var(--green); }
.status-dot.inactive { background:var(--red); }
.status-dot.pending  { background:var(--yellow); }
.status-dot.banned   { background:var(--red); }

.team-empty { text-align:center;padding:60px 20px;color:var(--text-muted); }
.team-empty i { font-size:2.6rem;opacity:.3;margin-bottom:14px;display:block; }
.team-empty p { font-size:.88rem;margin:0 0 6px; }
.team-empty .team-empty-sub { font-size:.78rem; }
.team-empty a { color:var(--primary);font-weight:700;text-decoration:none; }
.team-empty a:hover { text-decoration:underline; }

@media(max-width:768px){.team-topbar{flex-direction:column;}}

/* ── Invite Team Member — button/modal/toast, same tokens & class
   names as roles/index.blade.php's Invite User modal, since this reuses
   the exact same sendInvitation endpoint. ── */
.btn-ram { display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:8px;font-size:.82rem;font-weight:700;cursor:pointer;border:none;transition:all .2s;font-family:'Open Sans',sans-serif;white-space:nowrap; }
.btn-ram:hover { transform:translateY(-1px); }
.btn-ram-success { background:var(--green);color:#fff; }
.btn-ram-success:hover { background:#00a87c; }
.btn-ram-outline { background:var(--card-bg);color:var(--text-dark);border:1.5px solid var(--border); }
.btn-ram-outline:hover { border-color:var(--primary);color:var(--primary); }

.ram-modal-overlay { display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:900;align-items:center;justify-content:center;backdrop-filter:blur(3px); }
.ram-modal-overlay.show { display:flex; }
.ram-modal { background:var(--modal-bg);border-radius:14px;max-width:560px;width:95%;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.22);animation:ramPop .2s ease; }
@keyframes ramPop { from{opacity:0;transform:scale(.94);}to{opacity:1;transform:scale(1);} }
.ram-modal-header { padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;background:var(--modal-header); }
.ram-modal-header h3 { font-family:'Nunito',sans-serif;font-weight:900;font-size:1.05rem;color:var(--text-dark);margin:0;display:flex;align-items:center;gap:8px; }
.ram-modal-header h3 i { color:var(--primary); }
.ram-modal-close { width:28px;height:28px;border-radius:50%;border:none;background:var(--border);color:var(--text-muted);cursor:pointer;font-size:.78rem;display:flex;align-items:center;justify-content:center;transition:background .15s; }
.ram-modal-close:hover { background:var(--red);color:#fff; }
.ram-modal-body { padding:20px; }
.ram-modal-footer { padding:14px 20px;border-top:1px solid var(--border);display:flex;gap:8px;justify-content:flex-end;flex-wrap:wrap; }

.ram-form-group { margin-bottom:15px; }
.ram-form-group:last-child { margin-bottom:0; }
.ram-label { display:flex;align-items:center;gap:6px;font-size:.79rem;font-weight:700;color:var(--text-dark);margin-bottom:6px; }
.ram-label i { color:var(--primary);font-size:.76rem; }
.ram-input,.ram-select,.ram-textarea { width:100%;border:1.5px solid var(--input-border);border-radius:8px;padding:9px 13px;font-size:.875rem;color:var(--input-color);background:var(--input-bg);outline:none;transition:border-color .2s,box-shadow .2s;font-family:'Open Sans',sans-serif; }
.ram-input:focus,.ram-select:focus,.ram-textarea:focus { border-color:var(--primary);box-shadow:0 0 0 3px rgba(26,115,232,.11); }
.ram-grid-2 { display:grid;grid-template-columns:1fr 1fr;gap:12px; }
@media(max-width:500px){.ram-grid-2{grid-template-columns:1fr;}}

.ram-toast-stack { position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none; }
.ram-toast { background:var(--card-bg);border:1px solid var(--border);border-radius:10px;padding:11px 15px;display:flex;align-items:center;gap:9px;box-shadow:0 6px 24px rgba(0,0,0,.12);font-size:.81rem;color:var(--text-dark);pointer-events:all;animation:ramToastIn .2s ease;max-width:320px; }
@keyframes ramToastIn { from{opacity:0;transform:translateX(14px);}to{opacity:1;transform:none;} }
.ram-spinner { display:inline-block;width:13px;height:13px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite; }
@keyframes spin { to{transform:rotate(360deg);} }
</style>

<div id="teamPage">
<div class="team-wrap">

  {{-- TOP BAR --}}
  <div class="team-topbar">
    <div>
      <div class="team-breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a> <i class="fas fa-chevron-right" style="font-size:.6rem;"></i> <span>Team</span>
      </div>
      <div class="team-page-title"><i class="fas fa-users"></i> Team</div>
      <div class="team-page-sub">Everyone currently shown on the public Team page — driven live from the Users system.</div>
    </div>
    @hasanyrole('super-admin|admin')
    <div>
      <button class="btn-ram btn-ram-success" onclick="openModal('inviteTeamModal')">
        <i class="fas fa-user-plus"></i> Invite Team Member
      </button>
    </div>
    @endhasanyrole
  </div>

  {{-- STATS --}}
  <div class="team-stats">
    <div class="team-stat-card">
      <div class="team-stat-icon"><i class="fas fa-user-check"></i></div>
      <div>
        <div class="team-stat-val">{{ $members->count() }}</div>
        <div class="team-stat-lbl">Team Members</div>
      </div>
    </div>
    <div class="team-stat-card">
      <div class="team-stat-icon"><i class="fas fa-layer-group"></i></div>
      <div>
        <div class="team-stat-val">{{ $members->pluck('teamRole')->filter()->unique()->count() }}</div>
        <div class="team-stat-lbl">Teams Represented</div>
      </div>
    </div>
    <div class="team-stat-card">
      <div class="team-stat-icon"><i class="fas fa-circle-check"></i></div>
      <div>
        <div class="team-stat-val">{{ $members->where('status', 'active')->count() }}</div>
        <div class="team-stat-lbl">Active</div>
      </div>
    </div>
  </div>

  {{-- TABLE CARD --}}
  <div class="team-card">
    <div class="team-card-header">
      <h2><i class="fas fa-id-badge"></i> Team Members</h2>
      <div class="hint">Add or edit members via <a href="{{ route('roles-access.roles.index') }}">Roles & Access → Users → Edit</a></div>
    </div>

    @if($members->isEmpty())
      <div class="team-empty">
        <i class="fas fa-users-slash"></i>
        <p>No team members yet.</p>
        <div class="team-empty-sub">
          Mark a user as a team member from
          <a href="{{ route('roles-access.roles.index') }}">Roles & Access → Users → Edit User</a>
          to have them appear here and on the public Team page.
        </div>
      </div>
    @else
      <div class="team-table-wrap">
        <table class="team-table">
          <thead>
            <tr>
              <th>Member</th>
              <th>Designation</th>
              <th>Team</th>
              <th>Responsibilities</th>
              <th>Permission Role</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($members as $m)
              <tr>
                <td>
                  <div class="team-user-cell">
                    @if($m['avatarUrl'])
                      <div class="team-avatar" style="background:center/cover no-repeat url('{{ $m['avatarUrl'] }}');"></div>
                    @else
                      <div class="team-avatar" style="background:{{ $m['avatarBg'] }};">{{ $m['avatarInitials'] }}</div>
                    @endif
                    <div>
                      <div class="team-user-name">{{ $m['name'] }}</div>
                      <div class="team-user-email">{{ $m['email'] }}</div>
                    </div>
                  </div>
                </td>
                <td class="team-designation">{{ $m['designation'] ?? '—' }}</td>
                <td>
                  @if($m['teamRole'])
                    <span class="team-role-pill"><i class="fas fa-circle" style="font-size:.4rem;"></i> {{ $m['teamRole'] }}</span>
                  @else
                    <span style="color:var(--text-muted);">—</span>
                  @endif
                </td>
                <td class="team-responsibilities">{{ $m['responsibilities'] ?? '—' }}</td>
                <td>
                  <span class="role-badge {{ $m['roleColor'] }}">
                    <i class="fas fa-circle" style="font-size:.42rem;"></i> {{ ucfirst(str_replace('-', ' ', $m['permissionRole'])) }}
                  </span>
                </td>
                <td>
                  <span style="font-size:.8rem;">
                    <span class="status-dot {{ $m['status'] }}"></span>
                    {{ ucfirst($m['status']) }}
                  </span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

</div>
</div>

@hasanyrole('super-admin|admin')
{{-- Invite Team Member — reuses RoleAccessController::sendInvitation() exactly
     (route roles-access.invitations.send), same as the Invite User modal on
     the Roles & Access page. This only sends the invitation; the invited
     person still has to accept + set a password, then be marked as a team
     member (is_team_member/designation/team_role) via the existing Edit
     User modal on Roles & Access — that step is intentionally not
     duplicated here. --}}
<div class="ram-modal-overlay" id="inviteTeamModal">
  <div class="ram-modal">
    <div class="ram-modal-header">
      <h3><i class="fas fa-user-plus"></i> Invite Team Member</h3>
      <button class="ram-modal-close" onclick="closeModal('inviteTeamModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="ram-modal-body">
      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-envelope"></i> Email Address</label>
        <input type="email" class="ram-input" id="teamInviteEmail" placeholder="colleague@company.com"/>
      </div>
      <div class="ram-grid-2">
        <div class="ram-form-group">
          <label class="ram-label"><i class="fas fa-user"></i> First Name <span style="font-size:.65rem;color:var(--text-muted);font-weight:400;">(optional)</span></label>
          <input type="text" class="ram-input" id="teamInviteFirst" placeholder="Jane"/>
        </div>
        <div class="ram-form-group">
          <label class="ram-label"><i class="fas fa-user"></i> Last Name</label>
          <input type="text" class="ram-input" id="teamInviteLast" placeholder="Smith"/>
        </div>
      </div>
      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-user-shield"></i> Assign Role</label>
        <select class="ram-select" id="teamInviteRole">
          @foreach($roleNames as $rn)
            <option value="{{ $rn }}">{{ ucfirst(str_replace('-', ' ', $rn)) }}</option>
          @endforeach
        </select>
      </div>
      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-comment-alt"></i> Personal Message <span style="font-size:.65rem;color:var(--text-muted);font-weight:400;">(optional)</span></label>
        <textarea class="ram-textarea" id="teamInviteMsg" rows="2" placeholder="Add a note to the invitation email…"></textarea>
      </div>
      <div style="font-size:.74rem;color:var(--text-muted);display:flex;gap:6px;align-items:flex-start;">
        <i class="fas fa-circle-info" style="margin-top:2px;"></i>
        <span>This sends an account invitation. To have them appear on this Team page afterwards, mark them as a team member from <a href="{{ route('roles-access.roles.index') }}" style="color:var(--primary);font-weight:700;">Roles &amp; Access → Users → Edit User</a>.</span>
      </div>
    </div>
    <div class="ram-modal-footer">
      <button class="btn-ram btn-ram-outline" onclick="closeModal('inviteTeamModal')">Cancel</button>
      <button class="btn-ram btn-ram-success" id="teamSendInviteBtn" onclick="sendTeamInvite()">
        <i class="fas fa-paper-plane"></i> Send Invite
      </button>
    </div>
  </div>
</div>
@endhasanyrole

<div class="ram-toast-stack" id="ramToastStack"></div>

@push('scripts')
<script>
(function () {
'use strict';

const CSRF = '{{ csrf_token() }}';
const SEND_INVITE_URL = '{{ route("roles-access.invitations.send") }}';

function toast(msg, color, icon) {
  color = color || 'var(--primary)';
  icon  = icon  || 'fas fa-info-circle';
  const el = document.createElement('div');
  el.className = 'ram-toast';
  el.innerHTML = `<i class="${icon}" style="color:${color};font-size:.88rem;flex-shrink:0;"></i><span>${msg}</span>`;
  const stack = document.getElementById('ramToastStack');
  if (!stack) return;
  stack.appendChild(el);
  setTimeout(() => {
    el.style.opacity = '0';
    el.style.transition = 'opacity .3s';
    setTimeout(() => el.remove(), 300);
  }, 3500);
}

async function api(url, method, body) {
  const opts = {
    method,
    headers: {
      'X-CSRF-TOKEN': CSRF,
      'Accept':       'application/json',
      'Content-Type': 'application/json',
    },
  };
  if (body) opts.body = JSON.stringify(body);
  const res  = await fetch(url, opts);
  const data = await res.json();
  if (!res.ok) throw new Error(data.message || `Request failed (${res.status})`);
  return data;
}

function setLoading(id, on) {
  const btn = document.getElementById(id);
  if (!btn) return;
  if (on) {
    btn.dataset.origHtml = btn.innerHTML;
    btn.innerHTML = '<span class="ram-spinner"></span> Sending…';
    btn.disabled  = true;
  } else {
    btn.innerHTML = btn.dataset.origHtml || btn.innerHTML;
    btn.disabled  = false;
  }
}

window.openModal  = function (id) { document.getElementById(id).classList.add('show'); };
window.closeModal = function (id) { document.getElementById(id).classList.remove('show'); };

document.querySelectorAll('.ram-modal-overlay').forEach(m => {
  m.addEventListener('click', e => { if (e.target === m) m.classList.remove('show'); });
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape')
    document.querySelectorAll('.ram-modal-overlay.show').forEach(m => m.classList.remove('show'));
});

window.sendTeamInvite = async function () {
  const email = document.getElementById('teamInviteEmail').value.trim();
  if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    toast('Please enter a valid email address.', 'var(--red)', 'fas fa-exclamation-circle');
    return;
  }

  setLoading('teamSendInviteBtn', true);
  try {
    const data = await api(SEND_INVITE_URL, 'POST', {
      email,
      first_name: document.getElementById('teamInviteFirst').value.trim() || null,
      last_name:  document.getElementById('teamInviteLast').value.trim()  || null,
      role:       document.getElementById('teamInviteRole').value,
      message:    document.getElementById('teamInviteMsg').value.trim()  || null,
    });

    closeModal('inviteTeamModal');
    ['teamInviteEmail','teamInviteFirst','teamInviteLast','teamInviteMsg'].forEach(id => {
      document.getElementById(id).value = '';
    });
    toast(data.message, 'var(--green)', 'fas fa-paper-plane');
  } catch (e) {
    toast(e.message, 'var(--red)', 'fas fa-exclamation-circle');
  } finally {
    setLoading('teamSendInviteBtn', false);
  }
};

})();
</script>
@endpush

@endsection
