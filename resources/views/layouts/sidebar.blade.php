
<!-- ══════════ SIDEBAR OVERLAY (mobile) ══════════ -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ══════════ SIDEBAR ══════════ -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="brand-main">Kawach<span>TECH</span></div>
    <span class="brand-sub">A D M I N</span>
  </div>

  <nav class="sidebar-nav">

    @hasrole('client')
    <!-- Dashboard (client's own projects/tasks/team — scoped to client_user_id) -->
    <div class="nav-item-wrap">
      <a href="{{ route('client.portal') }}"
          class="nav-link-item {{ request()->routeIs('client.portal') ? 'active' : '' }}">
          <i class="fas fa-th-large nav-icon"></i> Dashboard
      </a>
    </div>

    <!-- Approvals (client — pending designs / responded change requests) -->
    <div class="nav-item-wrap">
      <a href="{{ route('client.approvals.index') }}"
          class="nav-link-item {{ request()->routeIs('client.approvals.*') ? 'active' : '' }}">
          <i class="fa-solid fa-check-double"></i> Approvals
      </a>
    </div>

    <!-- Designs (client) -->
    <div class="nav-item-wrap">
      <a href="{{ route('client.designs.index') }}"
          class="nav-link-item {{ request()->routeIs('client.designs.*') ? 'active' : '' }}">
          <i class="fa-solid fa-palette"></i> Designs
      </a>
    </div>

    <!-- Change Requests (client) -->
    <div class="nav-item-wrap">
      <a href="{{ route('client.change-requests.index') }}"
          class="nav-link-item {{ request()->routeIs('client.change-requests.*') ? 'active' : '' }}">
          <i class="fa-solid fa-code-pull-request"></i> Change Requests
      </a>
    </div>

    <!-- Bug Reports (client) -->
    <div class="nav-item-wrap">
      <a href="{{ route('client.bugs.index') }}"
          class="nav-link-item {{ request()->routeIs('client.bugs.*') ? 'active' : '' }}">
          <i class="fa-solid fa-bug"></i> Bug Reports
      </a>
    </div>

    <!-- Billing & Agreement (client — view only) -->
    <div class="nav-item-wrap">
      <a href="{{ route('client.billing.index') }}"
          class="nav-link-item {{ request()->routeIs('client.billing.*') ? 'active' : '' }}">
          <i class="fa-solid fa-handshake"></i> Billing & Agreement
      </a>
    </div>
    @else

    <!-- Dashboard -->
    <div class="nav-item-wrap">
    <a href="{{ route('dashboard') }}"
        class="nav-link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fas fa-th-large nav-icon"></i> Dashboard
    </a>
    </div>

    @can('blog.view')
    <!-- Blog -->
    <div class="nav-item-wrap">
      <a href="{{ route('blogs.index') }}"
          class="nav-link-item {{ request()->routeIs('blogs.*') ? 'active' : '' }}">
          <i class="fa-solid fa-blog"></i> Blog
      </a>
    </div>
    @endcan

    @can('blog.edit')
    @php
      // Live COUNT() so the sidebar badge never drifts — no cached number.
      $pendingCommentsNavCount = \App\Models\BlogComment::where('status', 'pending')->count();
    @endphp
    <!-- Blog Comments -->
    <div class="nav-item-wrap">
      <a href="{{ route('blog-comments.index') }}"
          class="nav-link-item {{ request()->routeIs('blog-comments.*') ? 'active' : '' }}">
          <i class="fa-solid fa-comment-dots"></i> Comments
          @if($pendingCommentsNavCount > 0)
            <span style="margin-left:auto;background:#ffb830;color:#1a1a2e;font-size:.65rem;font-weight:800;padding:2px 7px;border-radius:10px;">{{ $pendingCommentsNavCount }}</span>
          @endif
      </a>
    </div>
    @endcan

    @can('news.view')
    <!-- Newsroom -->
    <div class="nav-item-wrap">
      <a href="{{ route('news.index') }}"
          class="nav-link-item {{ request()->routeIs('news.*') ? 'active' : '' }}">
          <i class="fa-solid fa-newspaper"></i> Newsroom
      </a>
    </div>
    @endcan

    @can('settings.billing')
    <!-- Billing -->
    <div class="nav-item-wrap">
      <a href="{{ route('billing.index') }}"
          class="nav-link-item {{ request()->routeIs('billing.*') ? 'active' : '' }}">
          <i class="fa-solid fa-handshake"></i> Billing & Agreement
      </a>
    </div>
    @endcan

    @can('pages.view')
    <!-- Page -->
    <div class="nav-item-wrap">
    <a href="{{ route('pages.index') }}"
        class="nav-link-item {{ request()->routeIs('pages.*') ? 'active' : '' }}">
        <i class="fa-solid fa-file-lines"></i> Page
    </a>
    </div>
    @endcan

    @hasanyrole('super-admin|admin')
    <!-- Roles & Permissions -->
    <div class="nav-item-wrap">
    <a href="{{ route('roles-access.roles.index') }}"
        class="nav-link-item {{ request()->routeIs('roles-access.roles.*') ? 'active' : '' }}">
        <i class="fa-solid fa-unlock"></i> Role & Permission
    </a>
    </div>
    @endhasanyrole

    @can('clients.view')
    <!-- Clients -->
    <div class="nav-item-wrap">
      <a href="{{ route('clients.index') }}"
        class="nav-link-item {{ request()->routeIs('clients.*') ? 'active' : '' }}">
          <i class="fas fa-user-tie nav-icon"></i> Client
      </a>
    </div>
    @endcan

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
    @can('tasks.view')
    <!-- Tasks -->
    <div class="nav-item-wrap">
      <a href="{{ route('tasks.index') }}"
          class="nav-link-item {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
          <i class="fas fa-tasks nav-icon"></i> Tasks
      </a>
    </div>
    @endcan
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
    @can('users.view')
    <!-- Team -->
    <div class="nav-item-wrap">
      <a href="{{ route('team.index') }}"
          class="nav-link-item {{ request()->routeIs('team.*') ? 'active' : '' }}">
          <i class="fas fa-users nav-icon"></i> Team
      </a>
    </div>
    @endcan

    @can('jobs.view')
    <!-- Jobs -->
    <div class="nav-item-wrap">
      <a href="{{ route('jobs.index') }}"
          class="nav-link-item {{ request()->routeIs('jobs.*') ? 'active' : '' }}">
          <i class="fas fa-briefcase nav-icon"></i> Jobs
      </a>
    </div>
    @endcan

    @can('analytics.view')
    <!-- Visitors -->
    <div class="nav-item-wrap">
      <a href="{{ route('visitors.index') }}"
          class="nav-link-item {{ request()->routeIs('visitors.*') ? 'active' : '' }}">
          <i class="fas fa-chart-line nav-icon"></i> Visitors
      </a>
    </div>
    @endcan

    @can('contacts.view')
    <!-- Contacts -->
    <div class="nav-item-wrap">
      <a href="{{ route('contacts.index') }}"
          class="nav-link-item {{ request()->routeIs('contacts.*') ? 'active' : '' }}">
          <i class="fas fa-envelope-open-text nav-icon"></i> Contacts
      </a>
    </div>
    @endcan

    @endhasrole

  </nav>

  <div class="sidebar-logout">
      <button class="btn-logout" id="btnLogout">
        <i class="fas fa-sign-out-alt"></i> Logout
      </button>
  </div>
</aside>
