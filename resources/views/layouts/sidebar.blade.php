
<!-- ══════════ SIDEBAR OVERLAY (mobile) ══════════ -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ══════════ SIDEBAR ══════════ -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="brand-main">Kawach<span>TECH</span></div>
    <span class="brand-sub">A D M I N</span>
  </div>

  <nav class="sidebar-nav">

    <!-- Dashboard -->
    <div class="nav-item-wrap">
    <a href="{{ route('dashboard') }}"
        class="nav-link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fas fa-th-large nav-icon"></i> Dashboard
    </a>
    </div>

    @hasanyrole('super-admin|admin')
    <!-- Blog -->
    <div class="nav-item-wrap">
      <a href="{{ route('blogs.index') }}"
          class="nav-link-item {{ request()->routeIs('blogs.*') ? 'active' : '' }}">
          <i class="fa-solid fa-blog"></i> Blog
      </a>
    </div>
    <!-- Billing -->
    <div class="nav-item-wrap">
      <a href="{{ route('billing.index') }}"
          class="nav-link-item {{ request()->routeIs('billing.*') ? 'active' : '' }}">
          <i class="fa-solid fa-handshake"></i> Billing & Agreement
      </a>
    </div>
    @endhasanyrole

    <!-- Page -->
    <div class="nav-item-wrap">
    <a href="{{ route('pages.index') }}"
        class="nav-link-item {{ request()->routeIs('pages.*') ? 'active' : '' }}">
        <i class="fa-solid fa-file-lines"></i> Page
    </a>
    </div>

    <!-- Roles & Permissions -->
    <div class="nav-item-wrap">
    <a href="{{ route('roles-access.roles.index') }}"
        class="nav-link-item {{ request()->routeIs('roles-access.roles.*') ? 'active' : '' }}">
        <i class="fa-solid fa-unlock"></i> Role & Permission
    </a>
    </div>

    <!-- Reports -->
    <div class="nav-item-wrap">
      <a class="nav-link-item" data-page="Reports">
        <i class="far fa-file-alt nav-icon"></i> Reports
      </a>
    </div>
    <!-- Messages -->
    <div class="nav-item-wrap">
      <a class="nav-link-item" data-page="Messages">
        <i class="far fa-comment-dots nav-icon"></i> Messages
      </a>
    </div>
    <!-- Tasks -->
    <div class="nav-item-wrap">
      <a class="nav-link-item" data-page="Tasks">
        <i class="fas fa-tasks nav-icon"></i> Tasks
      </a>
    </div>
    <!-- Calendar -->
    <div class="nav-item-wrap">
      <a class="nav-link-item" data-page="Calendar">
        <i class="fas fa-calendar-check nav-icon"></i> Calendar
      </a>
    </div>
    <!-- Settings -->
    <div class="nav-item-wrap">
      <a class="nav-link-item" data-page="Settings">
        <i class="fas fa-cog nav-icon"></i> Settings
      </a>
    </div>
    <!-- Projects (expandable) -->
    <div class="nav-item-wrap">
      <a class="nav-link-item has-sub" data-sub="sub-projects">
        <i class="fas fa-chart-bar nav-icon"></i> Projects
        <i class="fas fa-chevron-right nav-arrow"></i>
      </a>
      <div class="nav-submenu" id="sub-projects">
        <a class="nav-link-item" data-page="All Projects"><i class="fas fa-circle nav-icon" style="font-size:.4rem;"></i> All Projects</a>
        <a class="nav-link-item" data-page="Active Projects"><i class="fas fa-circle nav-icon" style="font-size:.4rem;"></i> Active</a>
        <a class="nav-link-item" data-page="Archived"><i class="fas fa-circle nav-icon" style="font-size:.4rem;"></i> Archived</a>
      </div>
    </div>
    <!-- Team (expandable) -->
    <div class="nav-item-wrap">
      <a class="nav-link-item has-sub" data-sub="sub-team">
        <i class="fas fa-users nav-icon"></i> Team
        <i class="fas fa-chevron-right nav-arrow"></i>
      </a>
      <div class="nav-submenu" id="sub-team">
        <a class="nav-link-item" data-page="Members"><i class="fas fa-circle nav-icon" style="font-size:.4rem;"></i> Members</a>
        <a class="nav-link-item" data-page="Roles"><i class="fas fa-circle nav-icon" style="font-size:.4rem;"></i> Roles</a>
      </div>
    </div>

  </nav>

  <div class="sidebar-logout">
    <button class="btn-logout" id="btnLogout">
      <i class="fas fa-sign-out-alt"></i> Logout
    </button>
  </div>
</aside>
