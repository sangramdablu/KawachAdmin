<!DOCTYPE html>
<html lang="en" data-theme="light">
    @include('layouts.head')
<body>

    @include('layouts.navbar');

    @include('layouts.sidebar');

<!-- ══════════ NOTIFICATION DROPDOWN ══════════ -->
<div class="topbar-dropdown" id="notifDropdown">
  <div class="dropdown-head">
    <h6><i class="fas fa-bell" style="color:var(--primary);margin-right:6px;"></i> Notifications <span style="font-size:.75rem;color:var(--text-muted);font-weight:600;">(3 new)</span></h6>
    <button class="dropdown-mark-all" id="markAllRead">Mark all read</button>
  </div>
  <div class="dropdown-body">
    <div class="notif-item unread" data-id="1">
      <div class="notif-icon ni-blue"><i class="fas fa-chart-line"></i></div>
      <div class="notif-body">
        <strong>Revenue milestone reached</strong>
        <p>Monthly revenue crossed $24,000 target</p>
        <span class="notif-time"><i class="fas fa-clock" style="font-size:.65rem;"></i> 2 mins ago</span>
      </div>
    </div>
    <div class="notif-item unread" data-id="2">
      <div class="notif-icon ni-green"><i class="fas fa-user-plus"></i></div>
      <div class="notif-body">
        <strong>New team member joined</strong>
        <p>Sarah Johnson accepted the invitation</p>
        <span class="notif-time"><i class="fas fa-clock" style="font-size:.65rem;"></i> 18 mins ago</span>
      </div>
    </div>
    <div class="notif-item unread" data-id="3">
      <div class="notif-icon ni-red"><i class="fas fa-exclamation-triangle"></i></div>
      <div class="notif-body">
        <strong>Server alert</strong>
        <p>CPU usage exceeded 85% on Node-03</p>
        <span class="notif-time"><i class="fas fa-clock" style="font-size:.65rem;"></i> 42 mins ago</span>
      </div>
    </div>
    <div class="notif-item" data-id="4">
      <div class="notif-icon ni-yellow"><i class="fas fa-star"></i></div>
      <div class="notif-body">
        <strong>New 5-star review</strong>
        <p>A client left an excellent review on your project</p>
        <span class="notif-time"><i class="fas fa-clock" style="font-size:.65rem;"></i> 2 hours ago</span>
      </div>
    </div>
    <div class="notif-item" data-id="5">
      <div class="notif-icon ni-purple"><i class="fas fa-robot"></i></div>
      <div class="notif-body">
        <strong>AI Analytics report ready</strong>
        <p>Weekly AI insights report has been generated</p>
        <span class="notif-time"><i class="fas fa-clock" style="font-size:.65rem;"></i> Yesterday</span>
      </div>
    </div>
  </div>
  <div class="dropdown-footer"><a href="#">View all notifications →</a></div>
</div>

<!-- ══════════ MESSAGES DROPDOWN ══════════ -->
<div class="topbar-dropdown" id="msgDropdown">
  <div class="dropdown-head">
    <h6><i class="fas fa-comment-dots" style="color:var(--accent);margin-right:6px;"></i> Messages <span style="font-size:.75rem;color:var(--text-muted);font-weight:600;">(5 unread)</span></h6>
    <button class="dropdown-mark-all" id="markAllMsgs">Mark all read</button>
  </div>
  <div class="dropdown-body">
    <div class="msg-item">
      <div class="msg-avatar" style="background:#1a73e8;">AM<span class="online-dot"></span></div>
      <div class="msg-body">
        <div class="msg-name">Alex Mercer</div>
        <div class="msg-preview">Hey, can you review the Q3 report?</div>
      </div>
      <div class="msg-meta">
        <span class="msg-time">2m</span>
        <span class="msg-badge">2</span>
      </div>
    </div>
    <div class="msg-item">
      <div class="msg-avatar" style="background:#e91e63;">SJ</div>
      <div class="msg-body">
        <div class="msg-name">Sarah Johnson</div>
        <div class="msg-preview">The design mockups are ready for review</div>
      </div>
      <div class="msg-meta">
        <span class="msg-time">15m</span>
        <span class="msg-badge">1</span>
      </div>
    </div>
    <div class="msg-item">
      <div class="msg-avatar" style="background:#388e3c;">JB<span class="online-dot"></span></div>
      <div class="msg-body">
        <div class="msg-name">John Brown</div>
        <div class="msg-preview">Updated the deployment pipeline docs</div>
      </div>
      <div class="msg-meta">
        <span class="msg-time">1h</span>
        <span class="msg-badge">1</span>
      </div>
    </div>
    <div class="msg-item">
      <div class="msg-avatar" style="background:#7c4dff;">MK</div>
      <div class="msg-body">
        <div class="msg-name">Maya Kim</div>
        <div class="msg-preview">Client meeting scheduled for Friday 3pm</div>
      </div>
      <div class="msg-meta">
        <span class="msg-time">3h</span>
        <span class="msg-badge">1</span>
      </div>
    </div>
    <div class="msg-item">
      <div class="msg-avatar" style="background:#ff7043;">RP</div>
      <div class="msg-body">
        <div class="msg-name">Ryan Patel</div>
        <div class="msg-preview">Can we push the deadline by 2 days?</div>
      </div>
      <div class="msg-meta">
        <span class="msg-time">5h</span>
        <span class="msg-badge">1</span>
      </div>
    </div>
  </div>
  <div class="dropdown-footer"><a href="#">Open inbox →</a></div>
</div>

<!-- ══════════ USER DROPDOWN ══════════ -->
<div class="topbar-dropdown user-dropdown" id="userDropdown">
  <div class="user-info">
    <div class="user-dp">AK</div>
    <div class="user-name">Arjun Kumar</div>
    <div class="user-role">Lead Developer</div>
  </div>
  <a class="user-menu-item"><i class="fas fa-user-circle"></i> My Profile</a>
  <a class="user-menu-item"><i class="fas fa-cog"></i> Account Settings</a>
  <a class="user-menu-item"><i class="fas fa-shield-alt"></i> Privacy</a>
  <a class="user-menu-item"><i class="fas fa-question-circle"></i> Help & Support</a>
  <div class="user-menu-sep"></div>
  <a class="user-menu-item" id="userLogoutBtn" style="color:#ff4d6d;"><i class="fas fa-sign-out-alt" style="color:#ff4d6d;"></i> Logout</a>
</div>

<!-- ══════════ LOGOUT MODAL ══════════ -->
<div class="custom-modal-overlay" id="logoutModal">
  <div class="custom-modal">
    <button class="modal-close-x" id="closeLogoutModal"><i class="fas fa-times"></i></button>
    <div class="modal-icon" style="background:#ffe2e8;color:#ff4d6d;"><i class="fas fa-sign-out-alt"></i></div>
    <div class="modal-title">Confirm Logout</div>
    <div class="modal-body-text">Are you sure you want to log out? Any unsaved changes will be lost.</div>
    <div class="modal-actions">
      <button class="btn-modal-cancel" id="cancelLogout">Cancel</button>
      <button class="btn-modal-confirm" style="background:#ff4d6d;" id="confirmLogout">Yes, Logout</button>
    </div>
  </div>
</div>

<!-- ══════════ TOAST STACK ══════════ -->
<div class="toast-stack" id="toastStack"></div>

<!-- ══════════ MAIN ══════════ -->
<main class="main-content">
    @yield('content')
</main>

<!-- ══════════ JQUERY + SCRIPTS ══════════ -->
<script src="{{ asset('assets/js/script.js') }}"></script>
<script>
  function clearDraftSession() {
    sessionStorage.removeItem('current_draft_key');
  }
</script>
{{-- CDN libraries --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@stack('scripts')

</body>
</html>
