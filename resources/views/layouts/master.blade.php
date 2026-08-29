<!DOCTYPE html>
<html lang="en" data-theme="light">
    @include('layouts.head')
<body>

    @include('layouts.navbar');
    @include('layouts.sidebar');

<!-- ══════════ NOTIFICATION DROPDOWN ══════════ -->
<div class="topbar-dropdown" id="notifDropdown">
  <div class="dropdown-head">
    <h6><i class="fas fa-bell" style="color:var(--primary);margin-right:6px;"></i> Notifications <span id="notifHeadCount" style="font-size:.75rem;color:var(--text-muted);font-weight:600;">({{ $unreadNotifCount ?? 0 }} new)</span></h6>
    <button class="dropdown-mark-all" id="markAllRead">Mark all read</button>
  </div>
  <div class="dropdown-body" id="notifList">
    @forelse(($notifications ?? collect()) as $n)
      <div class="notif-item {{ $n->read_at ? '' : 'unread' }}" data-id="{{ $n->id }}" data-url="{{ $n->data['url'] ?? '#' }}">
        <div class="notif-icon" style="background:{{ ($n->data['color'] ?? '#1a73e8') }}1a; color:{{ $n->data['color'] ?? '#1a73e8' }};"><i class="{{ $n->data['icon'] ?? 'fas fa-bell' }}"></i></div>
        <div class="notif-body">
          <strong>{{ $n->data['title'] ?? 'Notification' }}</strong>
          <p>{{ $n->data['message'] ?? '' }}</p>
          <span class="notif-time"><i class="fas fa-clock" style="font-size:.65rem;"></i> {{ $n->created_at->diffForHumans() }}</span>
        </div>
      </div>
    @empty
      <div class="notif-empty" id="notifEmpty">No notifications yet</div>
    @endforelse
  </div>
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
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn-modal-confirm" style="background:#ff4d6d;" id="confirmLogout">Yes, Logout</button>
      </form>
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
    // 'current_draft_key' was never actually used by the blog editor — the
    // DraftManager module in blog.js persists unsaved "New Post" content
    // under the localStorage key 'blog_draft_new' (see DraftManager._resolveLocalKey
    // in public/assets/js/blog.js). Clearing the old unused key was a no-op,
    // so leftover content from an abandoned draft would silently reappear
    // the next time "New Post" was opened. Remove the key that's actually used.
    sessionStorage.removeItem('current_draft_key');
    try {
      localStorage.removeItem('blog_draft_new');
    } catch (e) {
      // localStorage unavailable (private mode, etc.) — nothing to clear anyway
    }
  }
</script>
{{-- CDN libraries --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@auth
  <script src="{{ asset('assets/js/notifications.js') }}"></script>
@endauth
@stack('scripts')

</body>
</html>
