
<!-- ══════════ TOPBAR ══════════ -->
<header class="topbar">
  <button class="toggle-btn" id="toggleBtn"><i class="fas fa-bars"></i></button>

  <div class="topbar-search">
    <i class="fas fa-search"></i>
    <input type="text" id="searchInput" placeholder="Search…" autocomplete="off"/>
  </div>

  <div class="topbar-spacer"></div>

  <div class="topbar-icons">
    <!-- Notifications -->
    <button class="icon-btn" id="btnNotif" title="Notifications">
      <i class="fas fa-bell"></i>
      <span class="badge-dot badge-red" id="notifBadge" style="{{ ($unreadNotifCount ?? 0) ? '' : 'display:none;' }}">{{ $unreadNotifCount ?? 0 }}</span>
    </button>

    <!-- Messages -->
    <button class="icon-btn" id="btnMessages" title="Messages">
      <i class="fas fa-comment-dots"></i>
      <span class="badge-dot badge-blue" id="msgBadge">5</span>
    </button>

    <!-- Theme toggle -->
    <button class="icon-btn-plain ib-yellow" id="btnTheme" title="Toggle Dark/Light Mode">
      <i class="fas fa-moon" id="themeIcon"></i>
    </button>

    <!-- User profile -->
    <button class="icon-btn-plain ib-teal" id="btnUser" title="Profile">
      <i class="fas fa-user"></i>
    </button>

    <!-- Settings shortcut -->
    <button class="icon-btn-plain ib-blue" title="Settings" id="btnSettings">
      <i class="fas fa-cog"></i>
    </button>
  </div>
</header>