@extends('layouts.master')
@section('title', 'Roles & Access Management — KawachTech')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap');

#ramPage {
  font-family: 'Open Sans', sans-serif;
  color: var(--text-dark);
  background: var(--bg-body);
  min-height: 100vh;
  transition: background .3s, color .3s;
}
#ramPage * { box-sizing: border-box; }
.ram-wrap { max-width: 1400px; margin: 0 auto; padding: 26px 20px 70px; }

.ram-topbar { display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:22px; }
.ram-breadcrumb { font-size:.73rem;color:var(--text-muted);display:flex;align-items:center;gap:6px;margin-bottom:4px; }
.ram-breadcrumb a { color:var(--primary);text-decoration:none;font-weight:600; }
.ram-breadcrumb a:hover { text-decoration:underline; }
.ram-page-title { font-family:'Nunito',sans-serif;font-weight:900;font-size:1.5rem;color:var(--text-dark);display:flex;align-items:center;gap:10px; }
.ram-page-title i { color:var(--primary);font-size:1.2rem; }

.ram-stats { display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:22px; }
.ram-stat-card { background:var(--card-bg);border:1px solid var(--border);border-radius:var(--card-radius);padding:16px 18px;display:flex;align-items:center;gap:14px;box-shadow:0 2px 12px rgba(26,115,232,.06);transition:background .3s,transform .18s; }
.ram-stat-card:hover { transform:translateY(-2px); }
.ram-stat-icon { width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0; }
.ram-stat-icon.blue  { background:#e8f1fd;color:var(--primary); }
.ram-stat-icon.green { background:#e0faf3;color:var(--green); }
.ram-stat-icon.red   { background:#fff0f3;color:var(--red); }
.ram-stat-icon.gold  { background:#fff8e6;color:var(--yellow); }
[data-theme="dark"] .ram-stat-icon.blue  { background:rgba(26,115,232,.18); }
[data-theme="dark"] .ram-stat-icon.green { background:rgba(0,200,150,.15); }
[data-theme="dark"] .ram-stat-icon.red   { background:rgba(255,77,109,.15); }
[data-theme="dark"] .ram-stat-icon.gold  { background:rgba(255,184,48,.15); }
.ram-stat-val { font-family:'Nunito',sans-serif;font-weight:900;font-size:1.5rem;color:var(--text-dark);line-height:1; }
.ram-stat-lbl { font-size:.72rem;color:var(--text-muted);margin-top:2px; }

.ram-tabs { display:flex;gap:4px;background:var(--card-bg);border:1px solid var(--border);border-radius:10px;padding:5px;margin-bottom:20px;width:fit-content;box-shadow:0 2px 10px rgba(26,115,232,.05); }
.ram-tab { padding:8px 18px;border-radius:7px;border:none;font-size:.82rem;font-weight:700;cursor:pointer;color:var(--text-muted);background:transparent;transition:all .18s;font-family:'Open Sans',sans-serif;display:flex;align-items:center;gap:6px; }
.ram-tab:hover { color:var(--primary);background:var(--bg-body); }
.ram-tab.active { background:var(--primary);color:#fff;box-shadow:0 2px 10px rgba(26,115,232,.3); }
.ram-tab .tab-badge { font-size:.62rem;font-weight:800;background:rgba(255,255,255,.25);color:#fff;padding:1px 6px;border-radius:10px; }
.ram-tab:not(.active) .tab-badge { background:var(--border);color:var(--text-muted); }
.ram-panel { display:none; }
.ram-panel.active { display:block; }

.ram-card { background:var(--card-bg);border:1px solid var(--border);border-radius:var(--card-radius);box-shadow:0 2px 14px rgba(26,115,232,.07);overflow:hidden;transition:background .3s; }
.ram-card+.ram-card { margin-top:16px; }
.ram-card-header { display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--border);background:var(--modal-header); }
.ram-card-header h2 { font-family:'Nunito',sans-serif;font-weight:900;font-size:.95rem;color:var(--panel-title);display:flex;align-items:center;gap:8px;margin:0; }
.ram-card-header h2 i { color:var(--primary); }

.ram-toolbar { display:flex;gap:10px;flex-wrap:wrap;padding:14px 20px;border-bottom:1px solid var(--border);background:var(--modal-header);align-items:center; }
.ram-search { display:flex;align-items:center;gap:8px;border:1.5px solid var(--input-border);border-radius:8px;padding:7px 12px;background:var(--input-bg);flex:1;min-width:180px;transition:border-color .2s; }
.ram-search:focus-within { border-color:var(--primary);box-shadow:0 0 0 3px rgba(26,115,232,.1); }
.ram-search i { color:var(--text-muted);font-size:.8rem; }
.ram-search input { border:none;outline:none;font-size:.84rem;color:var(--input-color);background:transparent;flex:1;font-family:'Open Sans',sans-serif; }
.ram-filter-select { border:1.5px solid var(--input-border);border-radius:8px;padding:7px 12px;font-size:.82rem;color:var(--input-color);background:var(--input-bg);cursor:pointer;font-family:'Open Sans',sans-serif;outline:none;transition:border-color .2s; }
.ram-filter-select:focus { border-color:var(--primary); }

.btn-ram { display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:8px;font-size:.82rem;font-weight:700;cursor:pointer;border:none;transition:all .2s;font-family:'Open Sans',sans-serif;white-space:nowrap; }
.btn-ram:hover { transform:translateY(-1px); }
.btn-ram-primary { background:var(--primary);color:#fff; }
.btn-ram-primary:hover { background:#1558b0;box-shadow:0 4px 14px rgba(26,115,232,.35); }
.btn-ram-success { background:var(--green);color:#fff; }
.btn-ram-success:hover { background:#00a87c; }
.btn-ram-outline { background:var(--card-bg);color:var(--text-dark);border:1.5px solid var(--border); }
.btn-ram-outline:hover { border-color:var(--primary);color:var(--primary); }
.btn-ram-danger { background:#fff5f7;color:var(--red);border:1.5px solid #ffc0cc; }
.btn-ram-danger:hover { background:var(--red);color:#fff;border-color:var(--red); }
.btn-sm { padding:5px 11px;font-size:.75rem; }
.btn-icon { width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:7px;border:1.5px solid var(--border);background:var(--card-bg);color:var(--text-muted);cursor:pointer;font-size:.78rem;transition:all .18s; }
.btn-icon:hover { border-color:var(--primary);color:var(--primary); }
.btn-icon.del:hover { border-color:var(--red);color:var(--red); }

.ram-table-wrap { overflow-x:auto; }
.ram-table { width:100%;border-collapse:collapse;font-size:.83rem; }
.ram-table thead tr { background:var(--modal-header);border-bottom:2px solid var(--border); }
.ram-table th { padding:11px 16px;text-align:left;font-family:'Nunito',sans-serif;font-weight:800;font-size:.74rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;white-space:nowrap; }
.ram-table td { padding:12px 16px;border-bottom:1px solid var(--border);color:var(--text-dark);vertical-align:middle; }
.ram-table tbody tr { transition:background .15s; }
.ram-table tbody tr:hover { background:var(--modal-header); }
.ram-table tbody tr:last-child td { border-bottom:none; }

.ram-avatar { width:32px;height:32px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-family:'Nunito',sans-serif;font-weight:800;font-size:.72rem;color:#fff;flex-shrink:0; }
.ram-user-cell { display:flex;align-items:center;gap:10px; }
.ram-user-name { font-weight:600;font-size:.85rem;color:var(--text-dark); }
.ram-user-email { font-size:.73rem;color:var(--text-muted); }

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

.roles-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;padding:20px; }
.role-card { background:var(--modal-header);border:1.5px solid var(--border);border-radius:12px;padding:18px;transition:border-color .18s,transform .18s,background .3s;position:relative;overflow:hidden; }
.role-card::before { content:'';position:absolute;left:0;top:0;bottom:0;width:4px;border-radius:4px 0 0 4px; }
.role-card.superadmin::before { background:#6c2bd9; }
.role-card.admin::before      { background:var(--primary); }
.role-card.editor::before     { background:var(--green); }
.role-card.viewer::before     { background:var(--text-muted); }
.role-card.custom::before     { background:var(--yellow); }
.role-card:hover { border-color:var(--primary);transform:translateY(-2px); }
.role-card-top { display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px; }
.role-card-icon { width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem; }
.role-card-title { font-family:'Nunito',sans-serif;font-weight:900;font-size:.95rem;color:var(--text-dark);margin-bottom:2px; }
.role-card-desc { font-size:.74rem;color:var(--text-muted);line-height:1.5; }
.role-card-meta { display:flex;align-items:center;justify-content:space-between;margin-top:14px;padding-top:12px;border-top:1px solid var(--border); }
.role-card-users { font-size:.74rem;color:var(--text-muted);display:flex;align-items:center;gap:5px; }
.role-card-users i { color:var(--primary); }

.perm-matrix-wrap { overflow-x:auto; }
.perm-matrix { width:100%;border-collapse:collapse;font-size:.82rem;min-width:600px; }
.perm-matrix th { padding:10px 14px;font-family:'Nunito',sans-serif;font-weight:800;font-size:.73rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;background:var(--modal-header);border-bottom:2px solid var(--border);white-space:nowrap;text-align:center; }
.perm-matrix th:first-child { text-align:left;min-width:200px; }
.perm-matrix td { padding:10px 14px;border-bottom:1px solid var(--border);text-align:center;vertical-align:middle; }
.perm-matrix td:first-child { text-align:left; }
.perm-matrix tbody tr:last-child td { border-bottom:none; }
.perm-matrix tbody tr:hover { background:var(--modal-header); }
.perm-section-row td { padding:8px 14px;background:var(--bg-body)!important;font-family:'Nunito',sans-serif;font-weight:800;font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.6px;border-bottom:1px solid var(--border); }
.perm-name { font-weight:600;color:var(--text-dark);font-size:.83rem; }
.perm-desc { font-size:.71rem;color:var(--text-muted);margin-top:1px; }

.toggle { position:relative;width:36px;height:20px;display:inline-block; }
.toggle input { display:none; }
.toggle-slider { position:absolute;inset:0;border-radius:20px;background:var(--border);cursor:pointer;transition:background .2s; }
.toggle-slider::before { content:'';position:absolute;width:14px;height:14px;border-radius:50%;background:#fff;top:3px;left:3px;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.2); }
.toggle input:checked+.toggle-slider { background:var(--primary); }
.toggle input:checked+.toggle-slider::before { transform:translateX(16px); }
.perm-check { font-size:.9rem; }
.perm-check.yes { color:var(--green); }

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

.perm-group-title { font-family:'Nunito',sans-serif;font-weight:800;font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.6px;margin:14px 0 8px;padding-bottom:6px;border-bottom:1px solid var(--border); }
.perm-checkbox-grid { display:grid;grid-template-columns:1fr 1fr;gap:6px; }
.perm-checkbox-item { display:flex;align-items:center;gap:8px;padding:7px 10px;border-radius:7px;border:1.5px solid var(--border);cursor:pointer;transition:border-color .15s,background .15s;font-size:.78rem;color:var(--text-dark);background:var(--input-bg); }
.perm-checkbox-item:hover { border-color:var(--primary);background:#f0f6ff; }
[data-theme="dark"] .perm-checkbox-item:hover { background:rgba(26,115,232,.08); }
.perm-checkbox-item input[type="checkbox"] { accent-color:var(--primary);width:14px;height:14px; }
.perm-checkbox-item.checked { border-color:var(--primary);background:#e8f1fd; }
[data-theme="dark"] .perm-checkbox-item.checked { background:rgba(26,115,232,.18); }

.activity-list { list-style:none;padding:16px 20px;margin:0; }
.activity-item { display:flex;gap:12px;padding:10px 0;border-bottom:1px solid var(--border); }
.activity-item:last-child { border-bottom:none; }
.activity-dot-wrap { display:flex;flex-direction:column;align-items:center;flex-shrink:0;padding-top:2px; }
.activity-dot { width:8px;height:8px;border-radius:50%;flex-shrink:0; }
.activity-dot.blue   { background:var(--primary); }
.activity-dot.green  { background:var(--green); }
.activity-dot.red    { background:var(--red); }
.activity-dot.yellow { background:var(--yellow); }
.activity-line { width:1px;flex:1;background:var(--border);margin-top:4px;min-height:20px; }
.activity-item:last-child .activity-line { display:none; }
.activity-content { flex:1; }
.activity-text { font-size:.81rem;color:var(--text-dark);line-height:1.5; }
.activity-text strong { font-weight:700; }
.activity-time { font-size:.7rem;color:var(--text-muted);margin-top:2px; }

.ram-toast-stack { position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none; }
.ram-toast { background:var(--card-bg);border:1px solid var(--border);border-radius:10px;padding:11px 15px;display:flex;align-items:center;gap:9px;box-shadow:0 6px 24px rgba(0,0,0,.12);font-size:.81rem;color:var(--text-dark);pointer-events:all;animation:ramToastIn .2s ease;max-width:320px; }
@keyframes ramToastIn { from{opacity:0;transform:translateX(14px);}to{opacity:1;transform:none;} }
.ram-spinner { display:inline-block;width:13px;height:13px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite; }
@keyframes spin { to{transform:rotate(360deg);} }
.ram-empty { text-align:center;padding:48px 20px;color:var(--text-muted); }
.ram-empty i { font-size:2.5rem;opacity:.3;margin-bottom:12px;display:block; }
.ram-empty p { font-size:.85rem; }
@media(max-width:768px){.ram-topbar{flex-direction:column;}.ram-tabs{overflow-x:auto;}}
</style>

<div id="ramPage">
<div class="ram-wrap">

  {{-- TOP BAR --}}
  <div class="ram-topbar">
    <div>
      <div class="ram-breadcrumb">
        <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
        <i class="fas fa-chevron-right" style="font-size:.5rem;"></i>
        <span>Roles &amp; Access</span>
      </div>
      <div class="ram-page-title">
        <i class="fas fa-shield-alt"></i> Roles &amp; Access Management
      </div>
    </div>
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
      <button class="btn-ram btn-ram-outline" onclick="openModal('activityModal')">
        <i class="fas fa-history"></i> Activity Log
      </button>
      <button class="btn-ram btn-ram-primary" onclick="openCreateRoleModal()">
        <i class="fas fa-plus"></i> Create Role
      </button>
      <button class="btn-ram btn-ram-success" onclick="openModal('inviteUserModal')">
        <i class="fas fa-user-plus"></i> Invite User
      </button>
    </div>
  </div>

  {{-- STATS --}}
  <div class="ram-stats">
    <div class="ram-stat-card">
      <div class="ram-stat-icon blue"><i class="fas fa-users"></i></div>
      <div><div class="ram-stat-val">{{ $stats['total_users'] }}</div><div class="ram-stat-lbl">Total Users</div></div>
    </div>
    <div class="ram-stat-card">
      <div class="ram-stat-icon gold"><i class="fas fa-user-shield"></i></div>
      <div><div class="ram-stat-val">{{ $stats['active_roles'] }}</div><div class="ram-stat-lbl">Active Roles</div></div>
    </div>
    <div class="ram-stat-card">
      <div class="ram-stat-icon green"><i class="fas fa-check-circle"></i></div>
      <div><div class="ram-stat-val">{{ $stats['active_users'] }}</div><div class="ram-stat-lbl">Active Users</div></div>
    </div>
    <div class="ram-stat-card">
      <div class="ram-stat-icon red"><i class="fas fa-ban"></i></div>
      <div><div class="ram-stat-val">{{ $stats['inactive_users'] }}</div><div class="ram-stat-lbl">Inactive / Banned</div></div>
    </div>
    <div class="ram-stat-card">
      <div class="ram-stat-icon blue"><i class="fas fa-key"></i></div>
      <div><div class="ram-stat-val">{{ $stats['total_perms'] }}</div><div class="ram-stat-lbl">Permissions</div></div>
    </div>
  </div>

  {{-- TABS --}}
  <div class="ram-tabs">
    <button class="ram-tab active" data-tab="users">
      <i class="fas fa-users"></i> Users
      <span class="tab-badge" id="tabBadgeUsers">{{ $stats['total_users'] }}</span>
    </button>
    <button class="ram-tab" data-tab="roles">
      <i class="fas fa-user-shield"></i> Roles
      <span class="tab-badge">{{ $stats['active_roles'] }}</span>
    </button>
    <button class="ram-tab" data-tab="permissions">
      <i class="fas fa-key"></i> Permission Matrix
    </button>
  </div>

  {{-- PANEL: USERS --}}
  <div class="ram-panel active" id="panel-users">
    <div class="ram-card">
      <div class="ram-card-header">
        <h2><i class="fas fa-users"></i> User Management</h2>
        <a href="{{ route('roles-access.users.export') }}" class="btn-ram btn-ram-outline btn-sm">
          <i class="fas fa-download"></i> Export CSV
        </a>
      </div>
      <div class="ram-toolbar">
        <div class="ram-search" style="flex:1;min-width:200px;">
          <i class="fas fa-search"></i>
          <input type="text" id="userSearch" placeholder="Search by name or email…"/>
        </div>
        <select class="ram-filter-select" id="roleFilter">
          <option value="">All Roles</option>
          @foreach($roleNames as $rn)
            <option value="{{ $rn }}">{{ ucfirst(str_replace('-', ' ', $rn)) }}</option>
          @endforeach
        </select>
        <select class="ram-filter-select" id="statusFilter">
          <option value="">All Status</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
          <option value="pending">Pending</option>
        </select>
      </div>
      <div class="ram-table-wrap">
        <table class="ram-table">
          <thead>
            <tr>
              <th style="width:36px;"><input type="checkbox" id="selectAll" style="accent-color:var(--primary);"/></th>
              <th>User</th>
              <th>Role</th>
              <th>Status</th>
              <th>Last Active</th>
              <th>Joined</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="usersTableBody"></tbody>
        </table>
      </div>
      {{-- Bulk bar --}}
      <div id="bulkActionsBar" style="display:none;padding:10px 20px;background:var(--modal-header);border-top:1px solid var(--border);align-items:center;gap:10px;flex-wrap:wrap;">
        <span style="font-size:.8rem;color:var(--text-muted);"><strong id="selectedCount">0</strong> selected</span>
        <select class="ram-filter-select" id="bulkRoleSelect">
          <option value="">Change Role…</option>
          @foreach($roleNames as $rn)
            <option value="{{ $rn }}">{{ ucfirst(str_replace('-', ' ', $rn)) }}</option>
          @endforeach
        </select>
        <button class="btn-ram btn-ram-primary btn-sm" onclick="applyBulkRole()"><i class="fas fa-check"></i> Apply Role</button>
        <button class="btn-ram btn-ram-danger btn-sm" onclick="bulkDeactivate()"><i class="fas fa-ban"></i> Deactivate</button>
      </div>
    </div>
  </div>

  {{-- PANEL: ROLES --}}
  <div class="ram-panel" id="panel-roles">
    <div class="ram-card">
      <div class="ram-card-header">
        <h2><i class="fas fa-user-shield"></i> Role Definitions</h2>
        <button class="btn-ram btn-ram-primary btn-sm" onclick="openCreateRoleModal()">
          <i class="fas fa-plus"></i> New Role
        </button>
      </div>
      <div class="roles-grid" id="rolesGrid"></div>
    </div>
  </div>

  {{-- PANEL: PERMISSIONS --}}
  <div class="ram-panel" id="panel-permissions">
    <div class="ram-card">
      <div class="ram-card-header">
        <h2><i class="fas fa-key"></i> Permission Matrix</h2>
        <div style="display:flex;gap:8px;align-items:center;">
          <span style="font-size:.75rem;color:var(--text-muted);">
            <i class="fas fa-circle" style="color:var(--green);font-size:.5rem;"></i> Enabled &nbsp;
            <i class="fas fa-circle" style="color:var(--border);font-size:.5rem;"></i> Disabled
          </span>
          <button class="btn-ram btn-ram-outline btn-sm" id="saveMatrixBtn" onclick="saveMatrix()">
            <i class="fas fa-save"></i> Save Changes
          </button>
        </div>
      </div>
      <div class="perm-matrix-wrap">
        <table class="perm-matrix" id="permMatrix"></table>
      </div>
    </div>
  </div>

</div>{{-- /ram-wrap --}}
</div>{{-- /ramPage --}}

{{-- ═══════ MODALS ═══════ --}}

{{-- Create / Edit Role --}}
<div class="ram-modal-overlay" id="createRoleModal">
  <div class="ram-modal">
    <div class="ram-modal-header">
      <h3><i class="fas fa-user-shield"></i> <span id="roleModalTitle">Create New Role</span></h3>
      <button class="ram-modal-close" onclick="closeModal('createRoleModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="ram-modal-body">
      <input type="hidden" id="editingRoleId"/>
      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-tag"></i> Role Name</label>
        <input type="text" class="ram-input" id="roleName" placeholder="e.g. content-manager (lowercase, hyphens)"/>
        <div style="font-size:.7rem;color:var(--text-muted);margin-top:4px;"><i class="fas fa-info-circle" style="color:var(--primary);"></i> Lowercase with hyphens only — this becomes the Spatie role name.</div>
      </div>
      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-align-left"></i> Description</label>
        <textarea class="ram-textarea" id="roleDesc" rows="2" placeholder="What can this role do?"></textarea>
      </div>
      <div class="ram-grid-2">
        <div class="ram-form-group">
          <label class="ram-label"><i class="fas fa-palette"></i> Colour</label>
          <select class="ram-select" id="roleColor">
            <option value="admin">Blue</option>
            <option value="editor">Green</option>
            <option value="viewer">Grey</option>
            <option value="custom">Gold</option>
            <option value="superadmin">Purple</option>
          </select>
        </div>
        <div class="ram-form-group">
          <label class="ram-label"><i class="fas fa-icons"></i> Icon</label>
          <select class="ram-select" id="roleIcon">
            <option value="fas fa-user-shield">Shield</option>
            <option value="fas fa-user-tie">Tie</option>
            <option value="fas fa-pen-nib">Pen</option>
            <option value="fas fa-eye">Eye</option>
            <option value="fas fa-cog">Cog</option>
            <option value="fas fa-star">Star</option>
            <option value="fas fa-folder">Folder</option>
            <option value="fas fa-crown">Crown</option>
          </select>
        </div>
      </div>
      <div class="perm-group-title">Assign Permissions</div>
      <div id="rolePermCheckboxes"></div>
    </div>
    <div class="ram-modal-footer">
      <button class="btn-ram btn-ram-outline" onclick="closeModal('createRoleModal')">Cancel</button>
      <button class="btn-ram btn-ram-primary" id="saveRoleBtn" onclick="saveRole()">
        <i class="fas fa-save"></i> Save Role
      </button>
    </div>
  </div>
</div>

{{-- Invite User --}}
<div class="ram-modal-overlay" id="inviteUserModal">
  <div class="ram-modal">
    <div class="ram-modal-header">
      <h3><i class="fas fa-user-plus"></i> Invite User</h3>
      <button class="ram-modal-close" onclick="closeModal('inviteUserModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="ram-modal-body">
      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-envelope"></i> Email Address</label>
        <input type="email" class="ram-input" id="inviteEmail" placeholder="colleague@company.com"/>
      </div>
      <div class="ram-grid-2">
        <div class="ram-form-group">
          <label class="ram-label"><i class="fas fa-user"></i> First Name <span style="font-size:.65rem;color:var(--text-muted);font-weight:400;">(optional)</span></label>
          <input type="text" class="ram-input" id="inviteFirst" placeholder="Jane"/>
        </div>
        <div class="ram-form-group">
          <label class="ram-label"><i class="fas fa-user"></i> Last Name</label>
          <input type="text" class="ram-input" id="inviteLast" placeholder="Smith"/>
        </div>
      </div>
      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-user-shield"></i> Assign Role</label>
        <select class="ram-select" id="inviteRole">
          @foreach($roleNames as $rn)
            <option value="{{ $rn }}">{{ ucfirst(str_replace('-', ' ', $rn)) }}</option>
          @endforeach
        </select>
      </div>
      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-comment-alt"></i> Personal Message <span style="font-size:.65rem;color:var(--text-muted);font-weight:400;">(optional)</span></label>
        <textarea class="ram-textarea" id="inviteMsg" rows="2" placeholder="Add a note to the invitation email…"></textarea>
      </div>
    </div>
    <div class="ram-modal-footer">
      <button class="btn-ram btn-ram-outline" onclick="closeModal('inviteUserModal')">Cancel</button>
      <button class="btn-ram btn-ram-success" id="sendInviteBtn" onclick="sendInvite()">
        <i class="fas fa-paper-plane"></i> Send Invite
      </button>
    </div>
  </div>
</div>

{{-- Edit User --}}
<div class="ram-modal-overlay" id="editUserModal">
  <div class="ram-modal">
    <div class="ram-modal-header">
      <h3><i class="fas fa-user-edit"></i> Edit User</h3>
      <button class="ram-modal-close" onclick="closeModal('editUserModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="ram-modal-body">
      <input type="hidden" id="editingUserId"/>
      {{-- User card --}}
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:18px;padding:14px;background:var(--modal-header);border-radius:10px;border:1px solid var(--border);">
        <div class="ram-avatar" id="editUserAvatar" style="width:46px;height:46px;font-size:.88rem;"></div>
        <div>
          <div style="font-weight:700;font-size:.95rem;color:var(--text-dark);" id="editUserName"></div>
          <div style="font-size:.76rem;color:var(--text-muted);" id="editUserEmail"></div>
          <div id="editUserCurrentRole" style="margin-top:4px;"></div>
        </div>
      </div>
      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-user-shield"></i> Assign Role</label>
        <select class="ram-select" id="editUserRole">
          @foreach($roleNames as $rn)
            <option value="{{ $rn }}">{{ ucfirst(str_replace('-', ' ', $rn)) }}</option>
          @endforeach
        </select>
      </div>
      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-toggle-on"></i> Status</label>
        <select class="ram-select" id="editUserStatus">
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
          <option value="pending">Pending</option>
        </select>
      </div>

      {{-- ── Team page profile fields ── --}}
      <div style="margin:16px 0 4px;padding-top:14px;border-top:1px solid var(--border);">
        <label class="ram-label" style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;">
          <span><i class="fas fa-users"></i> Show on Team page</span>
          <input type="checkbox" id="editUserIsTeamMember" style="width:18px;height:18px;accent-color:var(--primary);cursor:pointer;"/>
        </label>
        <p style="font-size:.72rem;color:var(--text-muted);margin:4px 0 0;">When enabled, this user appears on the public Team page with the details below.</p>
      </div>
      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-id-badge"></i> Designation</label>
        <input type="text" class="ram-input" id="editUserDesignation" placeholder="e.g. Senior Content Manager"/>
      </div>
      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-layer-group"></i> Team</label>
        <input type="text" class="ram-input" id="editUserTeamRole" placeholder="e.g. Content Team, Engineering"/>
      </div>
      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-list-check"></i> Responsibilities</label>
        <textarea class="ram-textarea" id="editUserResponsibilities" rows="2" placeholder="Comma-separated or free text"></textarea>
      </div>
      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-quote-left"></i> Bio</label>
        <textarea class="ram-textarea" id="editUserBio" rows="3" placeholder="Short professional bio shown on the public team page"></textarea>
      </div>
      <div class="ram-form-group">
        <label class="ram-label"><i class="fab fa-linkedin"></i> LinkedIn URL</label>
        <input type="url" class="ram-input" id="editUserLinkedin" placeholder="https://www.linkedin.com/in/username"/>
      </div>
      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-clock"></i> Years of Experience</label>
        <input type="number" class="ram-input" id="editUserYearsExperience" min="0" max="80" placeholder="e.g. 8"/>
      </div>
      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-camera"></i> Avatar</label>
        <div style="display:flex;align-items:center;gap:12px;">
          <div class="ram-avatar" id="editUserAvatarPreview" style="width:46px;height:46px;font-size:.88rem;background-size:cover;background-position:center;"></div>
          <input type="file" id="editUserAvatarFile" accept="image/png,image/jpeg,image/webp,image/gif" style="font-size:.78rem;"/>
        </div>
        <p style="font-size:.72rem;color:var(--text-muted);margin:4px 0 0;">Uploads immediately when a file is chosen.</p>
      </div>
      {{-- Inherited permissions from role --}}
      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-key"></i> Permissions from role</label>
        <div id="editUserPerms" style="display:flex;flex-wrap:wrap;gap:5px;margin-top:4px;"></div>
      </div>
    </div>
    <div class="ram-modal-footer">
      <button class="btn-ram btn-ram-danger btn-sm" id="editBanBtn" onclick="toggleBan()"></button>
      <button class="btn-ram btn-ram-outline" onclick="closeModal('editUserModal')">Cancel</button>
      <button class="btn-ram btn-ram-primary" id="saveUserBtn" onclick="saveUserEdit()">
        <i class="fas fa-save"></i> Save Changes
      </button>
    </div>
  </div>
</div>

{{-- Activity Log --}}
<div class="ram-modal-overlay" id="activityModal">
  <div class="ram-modal" style="max-width:640px;">
    <div class="ram-modal-header">
      <h3><i class="fas fa-history"></i> Access Activity Log</h3>
      <button class="ram-modal-close" onclick="closeModal('activityModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="ram-modal-body" style="padding:0;">
      <div style="padding:12px 20px;background:var(--modal-header);border-bottom:1px solid var(--border);">
        <div class="ram-search">
          <i class="fas fa-search"></i>
          <input type="text" id="activitySearch" placeholder="Search activity…"/>
        </div>
      </div>
      <ul class="activity-list" id="activityList"></ul>
    </div>
    <div class="ram-modal-footer">
      <button class="btn-ram btn-ram-outline" onclick="closeModal('activityModal')">Close</button>
    </div>
  </div>
</div>

<div class="ram-toast-stack" id="ramToastStack"></div>

@push('scripts')
<script>
(function () {
'use strict';

const CSRF = '{{ csrf_token() }}';

const ROUTES = {
  userUpdate:   '{{ url("admin/roles-access/users") }}',       // PATCH /{id}
  userBan:      '{{ url("admin/roles-access/users") }}',       // PATCH /{id}/toggle-ban
  userDelete:   '{{ url("admin/roles-access/users") }}',       // DELETE /{id}
  userResetPwd: '{{ url("admin/roles-access/users") }}',       // POST /{id}/reset-pwd
  userBulk:     '{{ route("roles-access.users.bulk") }}',
  roleStore:    '{{ route("roles-access.roles.store") }}',
  roleUpdate:   '{{ url("admin/roles-access/roles") }}',       // PATCH /{id}
  roleDelete:   '{{ url("admin/roles-access/roles") }}',       // DELETE /{id}
  saveMatrix:   '{{ route("roles-access.permissions.matrix") }}',
  sendInvite:   '{{ route("roles-access.invitations.send") }}',
  activity:     '{{ route("roles-access.activity") }}',
};

// PHP controller data → JS variables
let USERS        = @json($users);          // array of formatted user objects
let ROLES        = @json($roles);          // array of role objects with meta
let ROLE_PERMS   = @json($rolePerms);      // { role_name: [perm_key, ...] }
const PERM_SECTIONS = @json($permSections); // [{ section, perms: [{key,name,desc}] }]

const COLORS = {
  superadmin: ['#6c2bd9','#f0e8ff'],
  admin:      ['#1a73e8','#e8f1fd'],
  editor:     ['#00c896','#e0faf3'],
  viewer:     ['#8a9bb5','#eef2f9'],
  custom:     ['#ffb830','#fff8e6'],
};

/* ═══════════════════════════════════════════════
   UTILITIES
═══════════════════════════════════════════════ */
function toast(msg, color, icon) {
  color = color || 'var(--primary)';
  icon  = icon  || 'fas fa-info-circle';
  const el = document.createElement('div');
  el.className = 'ram-toast';
  el.innerHTML = `<i class="${icon}" style="color:${color};font-size:.88rem;flex-shrink:0;"></i><span>${msg}</span>`;
  document.getElementById('ramToastStack').appendChild(el);
  setTimeout(() => {
    el.style.opacity = '0';
    el.style.transition = 'opacity .3s';
    setTimeout(() => el.remove(), 300);
  }, 3500);
}

// Generic fetch wrapper — returns parsed JSON or throws
async function api(url, method, body) {
  const opts = {
    method,
    headers: {
      'X-CSRF-TOKEN':  CSRF,
      'Accept':        'application/json',
      'Content-Type':  'application/json',
    },
  };
  if (body) opts.body = JSON.stringify(body);
  const res  = await fetch(url, opts);
  const data = await res.json();
  if (!res.ok) throw new Error(data.message || `Request failed (${res.status})`);
  return data;
}

// Set a button into loading state and restore it after
function setLoading(id, on) {
  const btn = document.getElementById(id);
  if (!btn) return;
  if (on) {
    btn.dataset.origHtml = btn.innerHTML;
    btn.innerHTML = '<span class="ram-spinner"></span> Saving…';
    btn.disabled  = true;
  } else {
    btn.innerHTML = btn.dataset.origHtml || btn.innerHTML;
    btn.disabled  = false;
  }
}

function roleLabel(name) {
  return (name || '').replace(/-/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
}

/* ═══════════════════════════════════════════════
   MODAL HELPERS
═══════════════════════════════════════════════ */
function openModal(id)  { document.getElementById(id).classList.add('show');    }
function closeModal(id) { document.getElementById(id).classList.remove('show'); }
window.openModal  = openModal;
window.closeModal = closeModal;

document.querySelectorAll('.ram-modal-overlay').forEach(m => {
  m.addEventListener('click', e => { if (e.target === m) m.classList.remove('show'); });
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape')
    document.querySelectorAll('.ram-modal-overlay.show').forEach(m => m.classList.remove('show'));
});

/* ═══════════════════════════════════════════════
   TABS
═══════════════════════════════════════════════ */
document.querySelectorAll('.ram-tab').forEach(tab => {
  tab.addEventListener('click', function () {
    document.querySelectorAll('.ram-tab').forEach(t   => t.classList.remove('active'));
    document.querySelectorAll('.ram-panel').forEach(p  => p.classList.remove('active'));
    this.classList.add('active');
    document.getElementById('panel-' + this.dataset.tab).classList.add('active');
    if (this.dataset.tab === 'permissions') buildPermMatrix();
  });
});

/* ═══════════════════════════════════════════════
   USERS TABLE
═══════════════════════════════════════════════ */
let filteredUsers = [...USERS];

function renderUsersTable(users) {
  const tbody = document.getElementById('usersTableBody');
  if (!users.length) {
    tbody.innerHTML = `<tr><td colspan="7">
      <div class="ram-empty">
        <i class="fas fa-users-slash"></i>
        <p>No users match your filters.</p>
      </div>
    </td></tr>`;
    return;
  }

  tbody.innerHTML = users.map(u => {
    const color = u.roleColor || 'viewer';
    return `
      <tr>
        <td><input type="checkbox" class="user-check" data-id="${u.id}"
              onchange="onCheckChange()" style="accent-color:var(--primary);"/></td>
        <td>
          <div class="ram-user-cell">
            <div class="ram-avatar" style="${u.avatarUrl ? `background:center/cover no-repeat url('${u.avatarUrl}');` : `background:${u.avatarBg};`}">${u.avatarUrl ? '' : u.avatar}</div>
            <div>
              <div class="ram-user-name">${u.name}</div>
              <div class="ram-user-email">${u.email}</div>
            </div>
          </div>
        </td>
        <td>
          <span class="role-badge ${color}">
            <i class="fas fa-circle" style="font-size:.42rem;"></i>
            ${roleLabel(u.role)}
          </span>
        </td>
        <td>
          <span style="font-size:.8rem;">
            <span class="status-dot ${u.status}"></span>
            ${u.status.charAt(0).toUpperCase() + u.status.slice(1)}
          </span>
        </td>
        <td style="font-size:.8rem;color:var(--text-muted);">${u.last}</td>
        <td style="font-size:.8rem;color:var(--text-muted);">${u.joined}</td>
        <td>
          <div style="display:flex;gap:4px;">
            <button class="btn-icon" onclick="editUser(${u.id})" title="Edit user">
              <i class="fas fa-edit"></i>
            </button>
            <button class="btn-icon" onclick="resetPassword(${u.id})" title="Send password reset">
              <i class="fas fa-key"></i>
            </button>
            <button class="btn-icon del" onclick="removeUser(${u.id})" title="Remove user">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </td>
      </tr>`;
  }).join('');
}

// Search + filter (client-side on already-loaded data)
function filterUsers() {
  const q       = document.getElementById('userSearch').value.toLowerCase();
  const roleF   = document.getElementById('roleFilter').value;
  const statusF = document.getElementById('statusFilter').value;

  filteredUsers = USERS.filter(u =>
    (!q      || u.name.toLowerCase().includes(q)  || u.email.toLowerCase().includes(q)) &&
    (!roleF  || u.role === roleF) &&
    (!statusF|| u.status === statusF)
  );
  renderUsersTable(filteredUsers);
}

let searchTimer;
document.getElementById('userSearch').addEventListener('input', () => {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(filterUsers, 200);
});
document.getElementById('roleFilter').addEventListener('change',   filterUsers);
document.getElementById('statusFilter').addEventListener('change', filterUsers);

// Select all checkbox
document.getElementById('selectAll').addEventListener('change', function () {
  document.querySelectorAll('.user-check').forEach(c => c.checked = this.checked);
  onCheckChange();
});

window.onCheckChange = function () {
  const n   = document.querySelectorAll('.user-check:checked').length;
  const bar = document.getElementById('bulkActionsBar');
  document.getElementById('selectedCount').textContent = n;
  bar.style.display = n > 0 ? 'flex' : 'none';
};

/* ── Edit User ── */
window.editUser = function (id) {
  const u = USERS.find(x => x.id === id);
  if (!u) return;

  document.getElementById('editingUserId').value         = id;
  document.getElementById('editUserAvatar').textContent  = u.avatar;
  document.getElementById('editUserAvatar').style.background = u.avatarBg;
  document.getElementById('editUserName').textContent    = u.name;
  document.getElementById('editUserEmail').textContent   = u.email;
  document.getElementById('editUserRole').value          = u.role;
  document.getElementById('editUserStatus').value        = u.status;

  // Team page profile fields
  document.getElementById('editUserIsTeamMember').checked   = !!u.isTeamMember;
  document.getElementById('editUserDesignation').value      = u.designation || '';
  document.getElementById('editUserTeamRole').value         = u.teamRole || '';
  document.getElementById('editUserResponsibilities').value = u.responsibilities || '';
  document.getElementById('editUserBio').value               = u.bio || '';
  document.getElementById('editUserLinkedin').value          = u.linkedinUrl || '';
  document.getElementById('editUserYearsExperience').value   = u.yearsExperience ?? '';
  document.getElementById('editUserAvatarFile').value       = '';
  const avatarPreview = document.getElementById('editUserAvatarPreview');
  if (u.avatarUrl) {
    avatarPreview.style.backgroundImage = `url('${u.avatarUrl}')`;
    avatarPreview.textContent = '';
    avatarPreview.style.background = `center/cover no-repeat url('${u.avatarUrl}')`;
  } else {
    avatarPreview.style.backgroundImage = '';
    avatarPreview.textContent = u.avatar;
    avatarPreview.style.background = u.avatarBg;
  }

  // Current role badge
  const col = u.roleColor || 'viewer';
  document.getElementById('editUserCurrentRole').innerHTML =
    `<span class="role-badge ${col}" style="font-size:.68rem;">${roleLabel(u.role)}</span>`;

  // Show permissions inherited from role
  const perms = ROLE_PERMS[u.role] || [];
  const permsEl = document.getElementById('editUserPerms');
  if (perms.length === 0) {
    permsEl.innerHTML = `<span style="font-size:.74rem;color:var(--text-muted);">No permissions assigned to this role.</span>`;
  } else {
    permsEl.innerHTML = perms.map(p =>
      `<span style="font-size:.7rem;padding:3px 8px;border-radius:20px;background:var(--modal-header);border:1px solid var(--border);color:var(--text-muted);">${p}</span>`
    ).join('');
  }

  // Ban/unban button label
  const banBtn = document.getElementById('editBanBtn');
  if (u.status === 'inactive') {
    banBtn.innerHTML = '<i class="fas fa-check-circle"></i> Unban User';
    banBtn.className = 'btn-ram btn-ram-success btn-sm';
  } else {
    banBtn.innerHTML = '<i class="fas fa-ban"></i> Ban User';
    banBtn.className = 'btn-ram btn-ram-danger btn-sm';
  }

  // Update inherited permissions when role dropdown changes
  document.getElementById('editUserRole').onchange = function () {
    const newPerms = ROLE_PERMS[this.value] || [];
    permsEl.innerHTML = newPerms.length
      ? newPerms.map(p => `<span style="font-size:.7rem;padding:3px 8px;border-radius:20px;background:var(--modal-header);border:1px solid var(--border);color:var(--text-muted);">${p}</span>`).join('')
      : `<span style="font-size:.74rem;color:var(--text-muted);">No permissions assigned to this role.</span>`;
  };

  openModal('editUserModal');
};

/* ── Avatar upload (uploads immediately on file select, via
   ImageUploadService on the backend — same as Blog/News images) ── */
document.getElementById('editUserAvatarFile').addEventListener('change', async function () {
  const id   = document.getElementById('editingUserId').value;
  const file = this.files[0];
  if (!id || !file) return;

  const fd = new FormData();
  fd.append('avatar', file);

  try {
    const res  = await fetch(`${ROUTES.userUpdate}/${id}/avatar`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: fd,
    });
    const data = await res.json();
    if (!res.ok || !data.success) throw new Error(data.message || 'Avatar upload failed');

    const avatarPreview = document.getElementById('editUserAvatarPreview');
    avatarPreview.textContent = '';
    avatarPreview.style.background = `center/cover no-repeat url('${data.avatarUrl}')`;

    const idx = USERS.findIndex(x => x.id == id);
    if (idx !== -1) USERS[idx] = data.user;
    filteredUsers = filteredUsers.map(x => x.id == id ? data.user : x);
    renderUsersTable(filteredUsers);

    toast('Avatar updated.', 'var(--green)', 'fas fa-check-circle');
  } catch (e) {
    toast(e.message, 'var(--red)', 'fas fa-exclamation-circle');
  }
});

/* ── Save user edits (role + status) ── */
window.saveUserEdit = async function () {
  const id     = document.getElementById('editingUserId').value;
  const role   = document.getElementById('editUserRole').value;
  const status = document.getElementById('editUserStatus').value;

  const is_team_member   = document.getElementById('editUserIsTeamMember').checked;
  const designation      = document.getElementById('editUserDesignation').value.trim();
  const team_role        = document.getElementById('editUserTeamRole').value.trim();
  const responsibilities = document.getElementById('editUserResponsibilities').value.trim();
  const bio              = document.getElementById('editUserBio').value.trim();
  const linkedin_url     = document.getElementById('editUserLinkedin').value.trim();
  const yearsRaw         = document.getElementById('editUserYearsExperience').value.trim();
  const years_experience = yearsRaw === '' ? null : parseInt(yearsRaw, 10);

  if (!role) { toast('Please select a role.', 'var(--red)', 'fas fa-exclamation-circle'); return; }

  setLoading('saveUserBtn', true);
  try {
    const data = await api(`${ROUTES.userUpdate}/${id}`, 'PATCH', {
      role, status, is_team_member, designation, team_role, responsibilities,
      bio, linkedin_url, years_experience,
    });

    // Update local array
    const idx = USERS.findIndex(u => u.id == id);
    if (idx !== -1) {
      USERS[idx] = data.user;
      // Sync roleColor from ROLES meta
      const roleMeta = ROLES.find(r => r.name === data.user.role);
      USERS[idx].roleColor = roleMeta?.color || 'viewer';
    }
    filteredUsers = filteredUsers.map(u => u.id == id ? USERS[idx] : u);

    renderUsersTable(filteredUsers);
    closeModal('editUserModal');
    toast('User updated successfully.', 'var(--green)', 'fas fa-check-circle');
  } catch (e) {
    toast(e.message, 'var(--red)', 'fas fa-exclamation-circle');
  } finally {
    setLoading('saveUserBtn', false);
  }
};

/* ── Toggle ban / unban ── */
window.toggleBan = async function () {
  const id = document.getElementById('editingUserId').value;
  try {
    const data = await api(`${ROUTES.userBan}/${id}/toggle-ban`, 'PATCH');
    const idx  = USERS.findIndex(u => u.id == id);
    if (idx !== -1) USERS[idx].status = data.status;
    filteredUsers = filteredUsers.map(u => u.id == id ? {...u, status: data.status} : u);
    renderUsersTable(filteredUsers);
    closeModal('editUserModal');
    const banned = data.status === 'inactive';
    toast(
      banned ? 'User banned.'    : 'User unbanned.',
      banned ? 'var(--red)'      : 'var(--green)',
      banned ? 'fas fa-ban'      : 'fas fa-check-circle'
    );
  } catch (e) {
    toast(e.message, 'var(--red)', 'fas fa-exclamation-circle');
  }
};

/* ── Reset password ── */
window.resetPassword = async function (id) {
  const u = USERS.find(x => x.id === id);
  try {
    await api(`${ROUTES.userResetPwd}/${id}/reset-pwd`, 'POST');
    toast(`Password reset link sent to ${u?.email}`, 'var(--primary)', 'fas fa-key');
  } catch (e) {
    toast(e.message, 'var(--red)', 'fas fa-exclamation-circle');
  }
};

/* ── Remove user ── */
window.removeUser = async function (id) {
  const u = USERS.find(x => x.id === id);
  if (!confirm(`Remove ${u?.name} from the system? This cannot be undone.`)) return;
  try {
    await api(`${ROUTES.userDelete}/${id}`, 'DELETE');
    USERS         = USERS.filter(x => x.id !== id);
    filteredUsers = filteredUsers.filter(x => x.id !== id);
    renderUsersTable(filteredUsers);
    document.getElementById('tabBadgeUsers').textContent = USERS.length;
    toast(`${u?.name} removed.`, 'var(--red)', 'fas fa-trash');
  } catch (e) {
    toast(e.message, 'var(--red)', 'fas fa-exclamation-circle');
  }
};

/* ── Bulk actions ── */
window.applyBulkRole = async function () {
  const role = document.getElementById('bulkRoleSelect').value;
  if (!role) { toast('Please select a role.', 'var(--yellow)', 'fas fa-exclamation-triangle'); return; }

  const ids = [...document.querySelectorAll('.user-check:checked')].map(c => +c.dataset.id);
  try {
    const data = await api(ROUTES.userBulk, 'POST', { action: 'change_role', ids, role });

    // Update local array
    const roleMeta = ROLES.find(r => r.name === role);
    ids.forEach(id => {
      const u = USERS.find(x => x.id === id);
      if (u) { u.role = role; u.roleColor = roleMeta?.color || 'viewer'; }
    });
    filterUsers();
    toast(data.message, 'var(--green)', 'fas fa-check-circle');
  } catch (e) {
    toast(e.message, 'var(--red)', 'fas fa-exclamation-circle');
  }
};

window.bulkDeactivate = async function () {
  const ids = [...document.querySelectorAll('.user-check:checked')].map(c => +c.dataset.id);
  try {
    const data = await api(ROUTES.userBulk, 'POST', { action: 'deactivate', ids });
    ids.forEach(id => { const u = USERS.find(x => x.id === id); if (u) u.status = 'inactive'; });
    filterUsers();
    toast(data.message, 'var(--red)', 'fas fa-ban');
  } catch (e) {
    toast(e.message, 'var(--red)', 'fas fa-exclamation-circle');
  }
};

/* ═══════════════════════════════════════════════
   ROLES
═══════════════════════════════════════════════ */

// Build permission checkboxes inside role modal
function buildRolePermCheckboxes(currentPerms) {
  currentPerms = currentPerms || [];
  document.getElementById('rolePermCheckboxes').innerHTML =
    PERM_SECTIONS.map(s => `
      <div class="perm-group-title">${s.section}</div>
      <div class="perm-checkbox-grid">
        ${s.perms.map(p => {
          const checked = currentPerms.includes(p.key);
          return `<label class="perm-checkbox-item${checked ? ' checked' : ''}">
            <input type="checkbox" value="${p.key}" ${checked ? 'checked' : ''}
              onchange="this.closest('.perm-checkbox-item').classList.toggle('checked', this.checked)"/>
            ${p.name}
          </label>`;
        }).join('')}
      </div>`).join('');
}

function renderRoles() {
  const grid = document.getElementById('rolesGrid');
  if (!ROLES.length) {
    grid.innerHTML = `<div class="ram-empty" style="grid-column:1/-1;"><i class="fas fa-user-shield"></i><p>No roles defined yet.</p></div>`;
    return;
  }

  grid.innerHTML = ROLES.map(r => {
    const [c1, c2] = COLORS[r.color] || COLORS.viewer;
    return `
      <div class="role-card ${r.color}">
        <div class="role-card-top">
          <div class="role-card-icon" style="background:${c2};color:${c1};">
            <i class="${r.icon}"></i>
          </div>
          <div style="display:flex;gap:4px;align-items:flex-start;">
            ${r.is_system
              ? `<span style="font-size:.6rem;font-weight:800;padding:2px 8px;border-radius:10px;background:${c2};color:${c1};">SYSTEM</span>`
              : `<button class="btn-icon" onclick="openEditRoleModal(${r.id})" title="Edit"><i class="fas fa-edit"></i></button>
                 <button class="btn-icon del" onclick="deleteRole(${r.id})" title="Delete"><i class="fas fa-trash"></i></button>`
            }
          </div>
        </div>
        <div class="role-card-title">${roleLabel(r.name)}</div>
        <div class="role-card-desc">${r.desc || ''}</div>
        <div class="role-card-meta">
          <div class="role-card-users">
            <i class="fas fa-users"></i>
            ${r.users} user${r.users !== 1 ? 's' : ''}
          </div>
          <span class="role-badge ${r.color}">${r.name}</span>
        </div>
      </div>`;
  }).join('');
}

window.openCreateRoleModal = function () {
  document.getElementById('roleModalTitle').textContent = 'Create New Role';
  document.getElementById('editingRoleId').value        = '';
  document.getElementById('roleName').value             = '';
  document.getElementById('roleDesc').value             = '';
  document.getElementById('roleColor').value            = 'admin';
  document.getElementById('roleIcon').value             = 'fas fa-user-shield';
  buildRolePermCheckboxes([]);
  openModal('createRoleModal');
};

window.openEditRoleModal = function (id) {
  const r = ROLES.find(x => x.id === id);
  if (!r) return;
  document.getElementById('roleModalTitle').textContent = 'Edit Role';
  document.getElementById('editingRoleId').value        = id;
  document.getElementById('roleName').value             = r.name;
  document.getElementById('roleDesc').value             = r.desc || '';
  document.getElementById('roleColor').value            = r.color;
  document.getElementById('roleIcon').value             = r.icon;
  buildRolePermCheckboxes(ROLE_PERMS[r.name] || []);
  openModal('createRoleModal');
};

window.saveRole = async function () {
  const id    = document.getElementById('editingRoleId').value;
  const name  = document.getElementById('roleName').value.trim();
  const desc  = document.getElementById('roleDesc').value.trim();
  const color = document.getElementById('roleColor').value;
  const icon  = document.getElementById('roleIcon').value;
  const perms = [...document.querySelectorAll('#rolePermCheckboxes input:checked')].map(c => c.value);

  if (!name) { toast('Role name is required.', 'var(--red)', 'fas fa-exclamation-circle'); return; }

  setLoading('saveRoleBtn', true);
  try {
    let data;
    if (id) {
      // Update existing
      data = await api(`${ROUTES.roleUpdate}/${id}`, 'PATCH', { name, description: desc, color, icon, permissions: perms });
      const idx = ROLES.findIndex(r => r.id == id);
      if (idx !== -1) ROLES[idx] = data.role;
      ROLE_PERMS[data.role.name] = perms;
    } else {
      // Create new
      data = await api(ROUTES.roleStore, 'POST', { name, description: desc, color, icon, permissions: perms });
      ROLES.push(data.role);
      ROLE_PERMS[data.role.name] = perms;
    }

    renderRoles();
    closeModal('createRoleModal');
    toast(`Role "${name}" ${id ? 'updated' : 'created'}.`, 'var(--green)', 'fas fa-check-circle');
  } catch (e) {
    toast(e.message, 'var(--red)', 'fas fa-exclamation-circle');
  } finally {
    setLoading('saveRoleBtn', false);
  }
};

window.deleteRole = async function (id) {
  const r = ROLES.find(x => x.id === id);
  if (!r) return;
  if (!confirm(`Delete role "${r.name}"? This cannot be undone.`)) return;
  try {
    await api(`${ROUTES.roleDelete}/${id}`, 'DELETE');
    ROLES = ROLES.filter(x => x.id !== id);
    delete ROLE_PERMS[r.name];
    renderRoles();
    toast(`Role "${r.name}" deleted.`, 'var(--yellow)', 'fas fa-trash');
  } catch (e) {
    toast(e.message, 'var(--red)', 'fas fa-exclamation-circle');
  }
};

/* ═══════════════════════════════════════════════
   PERMISSION MATRIX
═══════════════════════════════════════════════ */
function buildPermMatrix() {
  const roleNames = ROLES.map(r => r.name);

  let html = `<thead><tr>
    <th>Permission</th>
    ${roleNames.map(n => {
      const r = ROLES.find(x => x.name === n);
      return `<th><span class="role-badge ${r?.color || 'viewer'}">${roleLabel(n)}</span></th>`;
    }).join('')}
  </tr></thead><tbody>`;

  PERM_SECTIONS.forEach(s => {
    html += `<tr class="perm-section-row"><td colspan="${roleNames.length + 1}">${s.section}</td></tr>`;

    s.perms.forEach(p => {
      html += `<tr>
        <td>
          <div class="perm-name">${p.name}</div>
          <div class="perm-desc">${p.desc || ''}</div>
        </td>
        ${roleNames.map(n => {
          const isSA = n === 'super-admin';
          const has  = (ROLE_PERMS[n] || []).includes(p.key);
          if (isSA) {
            return `<td><span class="perm-check yes"><i class="fas fa-check-circle"></i></span></td>`;
          }
          return `<td>
            <label class="toggle">
              <input type="checkbox" ${has ? 'checked' : ''}
                data-role="${n}" data-perm="${p.key}"
                onchange="onPermToggle(this)"/>
              <span class="toggle-slider"></span>
            </label>
          </td>`;
        }).join('')}
      </tr>`;
    });
  });

  html += '</tbody>';
  document.getElementById('permMatrix').innerHTML = html;
}

window.onPermToggle = function (cb) {
  const role = cb.dataset.role;
  const perm = cb.dataset.perm;
  if (!ROLE_PERMS[role]) ROLE_PERMS[role] = [];
  if (cb.checked) {
    if (!ROLE_PERMS[role].includes(perm)) ROLE_PERMS[role].push(perm);
  } else {
    ROLE_PERMS[role] = ROLE_PERMS[role].filter(p => p !== perm);
  }
};

window.saveMatrix = async function () {
  // Build matrix payload: { role_name: { perm_key: bool } }
  const matrix = {};
  ROLES.filter(r => r.name !== 'super-admin').forEach(r => {
    matrix[r.name] = {};
    PERM_SECTIONS.forEach(s =>
      s.perms.forEach(p => {
        matrix[r.name][p.key] = (ROLE_PERMS[r.name] || []).includes(p.key);
      })
    );
  });

  setLoading('saveMatrixBtn', true);
  try {
    const data = await api(ROUTES.saveMatrix, 'POST', { matrix });
    toast(data.message, 'var(--green)', 'fas fa-check-circle');
  } catch (e) {
    toast(e.message, 'var(--red)', 'fas fa-exclamation-circle');
  } finally {
    setLoading('saveMatrixBtn', false);
  }
};

/* ═══════════════════════════════════════════════
   INVITE USER
═══════════════════════════════════════════════ */
window.sendInvite = async function () {
  const email = document.getElementById('inviteEmail').value.trim();
  if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    toast('Please enter a valid email address.', 'var(--red)', 'fas fa-exclamation-circle');
    return;
  }

  setLoading('sendInviteBtn', true);
  try {
    const data = await api(ROUTES.sendInvite, 'POST', {
      email,
      first_name: document.getElementById('inviteFirst').value.trim() || null,
      last_name:  document.getElementById('inviteLast').value.trim()  || null,
      role:       document.getElementById('inviteRole').value,
      message:    document.getElementById('inviteMsg').value.trim()   || null,
    });

    closeModal('inviteUserModal');
    ['inviteEmail','inviteFirst','inviteLast','inviteMsg'].forEach(id => {
      document.getElementById(id).value = '';
    });
    toast(data.message, 'var(--green)', 'fas fa-paper-plane');
  } catch (e) {
    toast(e.message, 'var(--red)', 'fas fa-exclamation-circle');
  } finally {
    setLoading('sendInviteBtn', false);
  }
};

/* ═══════════════════════════════════════════════
   ACTIVITY LOG
═══════════════════════════════════════════════ */
let ACTIVITY = @json($activity);

function renderActivity(items) {
  const list = document.getElementById('activityList');
  if (!items.length) {
    list.innerHTML = `<li style="padding:24px;text-align:center;color:var(--text-muted);font-size:.82rem;">No activity found.</li>`;
    return;
  }
  list.innerHTML = items.map(a => `
    <li class="activity-item">
      <div class="activity-dot-wrap">
        <div class="activity-dot ${a.type}"></div>
        <div class="activity-line"></div>
      </div>
      <div class="activity-content">
        <div class="activity-text">${a.description}</div>
        <div class="activity-time">
          <i class="fas fa-clock" style="font-size:.6rem;margin-right:3px;"></i>${a.time}
        </div>
      </div>
    </li>`).join('');
}

let activityTimer;
document.getElementById('activitySearch').addEventListener('input', function () {
  clearTimeout(activityTimer);
  activityTimer = setTimeout(async () => {
    try {
      const res  = await fetch(`${ROUTES.activity}?search=${encodeURIComponent(this.value)}`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
      });
      const data = await res.json();
      ACTIVITY   = data.data;
      renderActivity(ACTIVITY);
    } catch (_) {}
  }, 300);
});

/* ═══════════════════════════════════════════════
   INIT  —  render everything on page load
═══════════════════════════════════════════════ */
renderUsersTable(USERS);
renderRoles();
buildRolePermCheckboxes([]);
renderActivity(ACTIVITY);

})();
</script>
@endpush

@endsection