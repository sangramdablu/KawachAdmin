@extends('layouts.master')
@section('title', 'My Profile — KawachTech')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap');

#profilePage {
  font-family: 'Open Sans', sans-serif;
  color: var(--text-dark);
  background: var(--bg-body);
  min-height: 100vh;
}
#profilePage * { box-sizing: border-box; }
.profile-wrap { max-width: 980px; margin: 0 auto; padding: 26px 20px 70px; }

.profile-breadcrumb { font-size:.73rem;color:var(--text-muted);display:flex;align-items:center;gap:6px;margin-bottom:4px; }
.profile-breadcrumb a { color:var(--primary);text-decoration:none;font-weight:600; }
.profile-breadcrumb a:hover { text-decoration:underline; }
.profile-page-title { font-family:'Nunito',sans-serif;font-weight:900;font-size:1.5rem;color:var(--text-dark);display:flex;align-items:center;gap:10px;margin-bottom:22px; }
.profile-page-title i { color:var(--primary);font-size:1.2rem; }

.profile-card { background:var(--card-bg);border:1px solid var(--border);border-radius:var(--card-radius);box-shadow:0 2px 14px rgba(26,115,232,.07);overflow:hidden;margin-bottom:20px; }
.profile-hero { display:flex;align-items:center;gap:22px;padding:26px;border-bottom:1px solid var(--border);background:var(--modal-header);flex-wrap:wrap; }

.profile-avatar-wrap { position:relative;flex-shrink:0; }
.profile-avatar { width:92px;height:92px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Nunito',sans-serif;font-weight:900;font-size:1.9rem;color:#fff;border:3px solid var(--border); }
.profile-avatar-edit { position:absolute;bottom:0;right:0;width:32px;height:32px;border-radius:50%;background:var(--primary);color:#fff;border:2.5px solid var(--card-bg);display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:.8rem;transition:background .15s; }
.profile-avatar-edit:hover { background:#1558b0; }
.profile-avatar-edit input[type="file"] { position:absolute;inset:0;opacity:0;cursor:pointer; }

.profile-hero-info { flex:1;min-width:200px; }
.profile-hero-name { font-family:'Nunito',sans-serif;font-weight:900;font-size:1.3rem;color:var(--text-dark); }
.profile-hero-email { font-size:.85rem;color:var(--text-muted);margin-top:2px; }
.profile-hero-badges { display:flex;gap:8px;margin-top:10px;flex-wrap:wrap; }
.profile-upload-hint { font-size:.72rem;color:var(--text-muted);margin-top:8px; }
.profile-upload-hint.uploading { color:var(--primary); }
.profile-upload-hint.error { color:var(--red); }
.profile-upload-hint.success { color:var(--green); }

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
.status-pill { display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;background:var(--modal-header);color:var(--text-dark);border:1px solid var(--border); }

.profile-card-header { padding:14px 20px;border-bottom:1px solid var(--border);background:var(--modal-header); }
.profile-card-header h2 { font-family:'Nunito',sans-serif;font-weight:900;font-size:.9rem;color:var(--panel-title);display:flex;align-items:center;gap:8px;margin:0; }
.profile-card-header h2 i { color:var(--primary); }

.profile-fields { display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:0; }
.profile-field { padding:16px 20px;border-bottom:1px solid var(--border);border-right:1px solid var(--border); }
.profile-field:nth-last-child(-n+2) { border-bottom:none; }
.profile-field-label { font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:5px; }
.profile-field-value { font-size:.9rem;font-weight:600;color:var(--text-dark); }
.profile-field-value.muted { color:var(--text-muted);font-weight:400; }

.profile-note { font-size:.76rem;color:var(--text-muted);padding:14px 20px;background:var(--modal-header);border-top:1px solid var(--border); }
.profile-note a { color:var(--primary);font-weight:700;text-decoration:none; }
.profile-note a:hover { text-decoration:underline; }

@media(max-width:768px){ .profile-field:nth-last-child(-n+2){border-bottom:1px solid var(--border);} .profile-field:last-child{border-bottom:none;} }
</style>

<div id="profilePage">
<div class="profile-wrap">

  <div class="profile-breadcrumb">
    <a href="{{ route('dashboard') }}">Dashboard</a> <i class="fas fa-chevron-right" style="font-size:.6rem;"></i> <span>My Profile</span>
  </div>
  <div class="profile-page-title"><i class="fas fa-id-badge"></i> My Profile</div>

  <div class="profile-card">
    <div class="profile-hero">
      <div class="profile-avatar-wrap">
        @if($user->avatar)
          <div class="profile-avatar" id="profileAvatar" style="background:center/cover no-repeat url('{{ $user->avatar_url }}');"></div>
        @else
          <div class="profile-avatar" id="profileAvatar" style="background:{{ $user->avatar_color }};">{{ $user->initials }}</div>
        @endif
        <label class="profile-avatar-edit" title="Change photo">
          <i class="fas fa-camera"></i>
          <input type="file" id="avatarInput" accept="image/png,image/jpeg,image/webp,image/gif"/>
        </label>
      </div>
      <div class="profile-hero-info">
        <div class="profile-hero-name">{{ $user->name }}</div>
        <div class="profile-hero-email">{{ $user->email }}</div>
        <div class="profile-hero-badges">
          @php $__role = $user->roles->first(); @endphp
          @if($__role)
            <span class="role-badge {{ \App\Models\RoleMeta::where('role_name', $__role->name)->first()?->color ?? 'viewer' }}">
              <i class="fas fa-circle" style="font-size:.42rem;"></i> {{ ucfirst(str_replace('-', ' ', $__role->name)) }}
            </span>
          @endif
          <span class="status-pill"><span class="status-dot {{ $user->status ?? 'active' }}"></span>{{ ucfirst($user->status ?? 'active') }}</span>
        </div>
        <div class="profile-upload-hint" id="avatarUploadHint">JPG, PNG, WEBP or GIF — up to 5MB.</div>
      </div>
    </div>
  </div>

  <div class="profile-card">
    <div class="profile-card-header"><h2><i class="fas fa-address-card"></i> My Details</h2></div>
    <div class="profile-fields">
      <div class="profile-field">
        <div class="profile-field-label">Designation</div>
        <div class="profile-field-value {{ $user->designation ? '' : 'muted' }}">{{ $user->designation ?? 'Not set' }}</div>
      </div>
      <div class="profile-field">
        <div class="profile-field-label">Team</div>
        <div class="profile-field-value {{ $user->team_role ? '' : 'muted' }}">{{ $user->team_role ?? 'Not set' }}</div>
      </div>
      <div class="profile-field">
        <div class="profile-field-label">Permission Role</div>
        <div class="profile-field-value">{{ $__role ? ucfirst(str_replace('-', ' ', $__role->name)) : 'Viewer' }}</div>
      </div>
      <div class="profile-field">
        <div class="profile-field-label">Status</div>
        <div class="profile-field-value">{{ ucfirst($user->status ?? 'active') }}</div>
      </div>
      <div class="profile-field">
        <div class="profile-field-label">Last Login</div>
        <div class="profile-field-value">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Never' }}</div>
      </div>
      <div class="profile-field">
        <div class="profile-field-label">Joined</div>
        <div class="profile-field-value">{{ $user->created_at->format('d M Y') }}</div>
      </div>
      <div class="profile-field" style="grid-column:1/-1;">
        <div class="profile-field-label">Responsibilities</div>
        <div class="profile-field-value {{ $user->responsibilities ? '' : 'muted' }}" style="font-weight:400;">{{ $user->responsibilities ?? 'Not set' }}</div>
      </div>
    </div>
    <div class="profile-note">
      <i class="fas fa-circle-info"></i>
      Name, email, designation, team and responsibilities are managed by an administrator via
      <a href="{{ route('roles-access.roles.index') }}">Roles &amp; Access → Users → Edit User</a>. From here you can only update your own photo.
    </div>
  </div>

</div>
</div>

@push('scripts')
<script>
(function () {
  'use strict';

  const CSRF = '{{ csrf_token() }}';
  const AVATAR_UPDATE_URL = '{{ route("profile.avatar.update") }}';

  const input = document.getElementById('avatarInput');
  const hint  = document.getElementById('avatarUploadHint');
  const avatarEl = document.getElementById('profileAvatar');

  function setHint(msg, cls) {
    hint.textContent = msg;
    hint.className = 'profile-upload-hint' + (cls ? ' ' + cls : '');
  }

  input.addEventListener('change', async function () {
    const file = input.files[0];
    if (!file) return;

    setHint('Uploading…', 'uploading');

    const fd = new FormData();
    fd.append('avatar', file);

    try {
      const res = await fetch(AVATAR_UPDATE_URL, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: fd,
      });
      const data = await res.json();
      if (!res.ok || !data.success) {
        throw new Error(data.message || 'Upload failed.');
      }

      // Reflect immediately on this page…
      avatarEl.style.background = `center/cover no-repeat url('${data.avatarUrl}')`;
      avatarEl.textContent = '';

      // …and in the topbar dropdown avatar, without a full page reload.
      const topbarDp = document.querySelector('#userDropdown .user-dp');
      if (topbarDp) {
        topbarDp.style.background = `center/cover no-repeat url('${data.avatarUrl}')`;
        topbarDp.textContent = '';
      }

      setHint('Photo updated.', 'success');
    } catch (e) {
      setHint(e.message || 'Upload failed.', 'error');
    } finally {
      input.value = '';
    }
  });
})();
</script>
@endpush

@endsection
