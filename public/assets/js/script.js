$(function () {
  const chartInstances = {};
  // Load saved theme
  let isDark = localStorage.getItem('theme') === 'dark';

  // Apply on page load
  applyTheme(isDark);
  /* ─── Live date ─── */
  function updateDate() {
    const now = new Date();
    $('#liveDate').text(' ' + now.toLocaleDateString('en-US', { weekday:'long', year:'numeric', month:'long', day:'numeric' }));
  }
  updateDate();
  setInterval(updateDate, 60000);

  /* ─── Toast helper ─── */
  function showToast(msg, color, icon) {
    color = color || '#1a73e8';
    icon  = icon  || 'fas fa-info-circle';
    const $t = $(`<div class="toast-msg"><i class="${icon}" style="color:${color};"></i> ${msg}</div>`);
    $('#toastStack').append($t);
    setTimeout(() => $t.fadeOut(300, () => $t.remove()), 3200);
  }

  /* ─── SIDEBAR TOGGLE (mobile) ─── */
  $('#toggleBtn').on('click', function (e) {
    e.stopPropagation();
    $('#sidebar').toggleClass('open');
    $('#sidebarOverlay').toggleClass('show');
  });
  $('#sidebarOverlay').on('click', function () {
    $('#sidebar').removeClass('open');
    $(this).removeClass('show');
  });

  /* ─── NAV active state ─── */
  $(document).on('click', '.nav-link-item:not(.has-sub)', function () {
    const page = $(this).data('page') || 'Dashboard';
    $('.nav-link-item').not('.has-sub').removeClass('active');
    $(this).addClass('active');
    $('#pageTitle').text(page);
    showToast('Navigated to <strong>' + page + '</strong>', '#1a73e8', 'fas fa-th-large');
    // close sidebar on mobile
    if ($(window).width() < 992) {
      $('#sidebar').removeClass('open');
      $('#sidebarOverlay').removeClass('show');
    }
  });

  /* ─── Sub-menu expand ─── */
  $(document).on('click', '.nav-link-item.has-sub', function () {
    const subId  = $(this).data('sub');
    const $sub   = $('#' + subId);
    const $arrow = $(this).find('.nav-arrow');
    const isOpen = $sub.is(':visible');
    // close all others
    $('.nav-submenu').not($sub).slideUp(200);
    $('.nav-link-item.has-sub').find('.nav-arrow').css('transform', 'rotate(0deg)');
    // toggle this one
    $sub.slideToggle(220);
    $arrow.css('transform', isOpen ? 'rotate(0deg)' : 'rotate(90deg)');
  });

  /* ─── Close all dropdowns helper ─── */
  function closeAllDropdowns() {
    $('.topbar-dropdown').removeClass('show');
  }

  /* ─── NOTIFICATION DROPDOWN ─── */
  $('#btnNotif').on('click', function (e) {
    e.stopPropagation();
    const isOpen = $('#notifDropdown').hasClass('show');
    closeAllDropdowns();
    if (!isOpen) $('#notifDropdown').addClass('show');
  });

  /* Mark individual notification read */
  $(document).on('click', '.notif-item', function () {
    $(this).removeClass('unread');
    updateNotifBadge();
  });

  /* Mark all read */
  $('#markAllRead').on('click', function (e) {
    e.stopPropagation();
    $('.notif-item').removeClass('unread');
    updateNotifBadge();
    showToast('All notifications marked as read', '#00c896', 'fas fa-check-circle');
  });

  function updateNotifBadge() {
    const count = $('.notif-item.unread').length;
    if (count > 0) { $('#notifBadge').text(count).show(); }
    else { $('#notifBadge').hide(); }
  }

  /* ─── MESSAGES DROPDOWN ─── */
  $('#btnMessages').on('click', function (e) {
    e.stopPropagation();
    const isOpen = $('#msgDropdown').hasClass('show');
    closeAllDropdowns();
    if (!isOpen) $('#msgDropdown').addClass('show');
  });

  $(document).on('click', '.msg-item', function () {
    $(this).find('.msg-badge').fadeOut(200, function () { $(this).remove(); });
    updateMsgBadge();
    showToast('Message opened', '#2196f3', 'fas fa-comment-dots');
  });

  $('#markAllMsgs').on('click', function (e) {
    e.stopPropagation();
    $('.msg-badge').fadeOut(200, function () { $(this).remove(); });
    updateMsgBadge();
    showToast('All messages marked as read', '#00c896', 'fas fa-check-circle');
  });

  function updateMsgBadge() {
    const count = $('.msg-badge').length;
    if (count > 0) { $('#msgBadge').text(count).show(); }
    else { $('#msgBadge').hide(); }
  }

  /* ─── USER DROPDOWN ─── */
  $('#btnUser').on('click', function (e) {
    e.stopPropagation();
    const isOpen = $('#userDropdown').hasClass('show');
    closeAllDropdowns();
    if (!isOpen) $('#userDropdown').addClass('show');
  });

  /* ─── SETTINGS shortcut ─── */
  $('#btnSettings').on('click', function () {
    closeAllDropdowns();
    $('.nav-link-item').not('.has-sub').removeClass('active');
    $('.nav-link-item[data-page="Settings"]').addClass('active');
    $('#pageTitle').text('Settings');
    showToast('Opened Settings', '#1a73e8', 'fas fa-cog');
  });

  /* ─── Close dropdowns on outside click ─── */
  $(document).on('click', function (e) {
    if (!$(e.target).closest('.topbar-dropdown, #btnNotif, #btnMessages, #btnUser').length) {
      closeAllDropdowns();
    }
  });

  /* ─── DARK / LIGHT MODE ─── */
  function applyTheme(dark) {
    if (dark) {
      $('html').attr('data-theme', 'dark');
      $('#themeIcon').removeClass('fa-moon').addClass('fa-sun');
      $('#btnTheme').css('background', '#334155');
    } else {
      $('html').attr('data-theme', 'light');
      $('#themeIcon').removeClass('fa-sun').addClass('fa-moon');
      $('#btnTheme').css('background', '#ffb830');
    }
    // update chart colors
    updateChartsForTheme(dark);
  }

  $('#btnTheme').on('click', function () {
    isDark = !isDark;

    // Save to localStorage
    localStorage.setItem('theme', isDark ? 'dark' : 'light');

    applyTheme(isDark);

    showToast(
      isDark ? 'Dark mode enabled' : 'Light mode enabled',
      isDark ? '#334155' : '#ffb830',
      isDark ? 'fas fa-moon' : 'fas fa-sun'
    );
  });

  /* ─── LOGOUT MODAL ─── */
  function openLogoutModal() {
    closeAllDropdowns();
    $('#logoutModal').addClass('show');
  }
  $('#btnLogout, #userLogoutBtn').on('click', openLogoutModal);
  $('#cancelLogout, #closeLogoutModal').on('click', function () {
    $('#logoutModal').removeClass('show');
  });
  $('#confirmLogout').on('click', function () {
    $('#logoutModal').removeClass('show');
    showToast('Logging out…', '#ff4d6d', 'fas fa-sign-out-alt');
    setTimeout(() => {
      $('#pageTitle').text('Logged Out');
      showToast('You have been logged out.', '#ff4d6d', 'fas fa-check');
    }, 1200);
  });
  $('#logoutModal').on('click', function (e) {
    if ($(e.target).is('#logoutModal')) $(this).removeClass('show');
  });

  /* ─── Analytics toolbar toggle ─── */
  $('.analytics-toolbar button').on('click', function () {
    $('.analytics-toolbar button').removeClass('active');
    $(this).addClass('active');
    showToast('View changed', '#1a73e8', 'fas fa-chart-bar');
  });

  /* ─── Panel dots (more options) ─── */
  $('.panel-dots').on('click', function () {
    showToast('More options coming soon', '#8a9bb5', 'fas fa-ellipsis-h');
  });

  /* ─── Activity items ─── */
  $('.activity-item').on('click', function () {
    const name = $(this).find('.activity-name').text();
    showToast('Viewing activity for ' + name, '#1a73e8', 'fas fa-user');
  });

  /* ─── Stat footer selects ─── */
  $('.stat-footer select').on('change', function () {
    showToast('Period updated to: ' + $(this).val(), '#00c896', 'fas fa-calendar-alt');
  });

  /* ─── Search input ─── */
  let searchTimer;
  $('#searchInput').on('input', function () {
    clearTimeout(searchTimer);
    const val = $(this).val().trim();
    if (val.length > 2) {
      searchTimer = setTimeout(() => {
        showToast('Searching for "<strong>' + val + '</strong>"…', '#1a73e8', 'fas fa-search');
      }, 500);
    }
  });

  /* ══════════════════════════
     CHARTS
  ══════════════════════════ */
  const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

  function mkLine(id, data, color) {
    const el = document.getElementById(id);
    if (!el) return;
    const ctx = el.getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, 90);
    grad.addColorStop(0, color + '55');
    grad.addColorStop(1, color + '00');
    chartInstances[id] = new Chart(ctx, {
      type: 'line',
      data: {
        labels: months,
        datasets: [{ 
          data, borderColor: 
          color, borderWidth: 
          2, pointRadius: 0, 
          tension: .45, fill: 
          true, backgroundColor: 
          grad 
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { enabled: false } },
        scales: { x: { display: false }, y: { display: false } },
        animation: { duration: 900 }
      }
    });
  }

  mkLine('chartRevenue', [9000,11000,8500,13000,10500,14000,12000,16000,13500,18000,15000,19500], '#00c896');
  mkLine('chartUsers',   [3200,4100,3800,4600,4200,5000,4700,5200,4900,5600,5100,5800], '#2196f3');
  mkLine('chartGrowth',  [60,80,70,90,85,100,95,110,105,120,115,130], '#26c6da');

  const chartTasksEl = document.getElementById('chartTasks');

  if (chartTasksEl) {
    chartInstances['chartTasks'] = new Chart(chartTasksEl, {
      type: 'doughnut',
      data: {
        datasets: [{
          data: [88,12],
          backgroundColor: ['#4a90d9','#e8edf5'],
          borderWidth: 0,
          borderRadius: 4
        }]
      },
      options: {
        cutout: '72%',
        responsive: false,
        plugins: { legend: { display: false }, tooltip: { enabled: false } }
      }
    });
  }

  /* Sales & Revenue */
  const chartSalesEl = document.getElementById('chartSales');

  if (chartSalesEl) {
    const ctx = chartSalesEl.getContext('2d');

    const g1 = ctx.createLinearGradient(0,0,0,220);
    g1.addColorStop(0,'rgba(74,144,217,.6)');
    g1.addColorStop(1,'rgba(74,144,217,.05)');

    const g2 = ctx.createLinearGradient(0,0,0,220);
    g2.addColorStop(0,'rgba(144,200,248,.5)');
    g2.addColorStop(1,'rgba(144,200,248,.03)');

    chartInstances['chartSales'] = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov'],
        datasets: [
          { 
            label:'Sales', 
            data:[10, 20, 30, 25, 40, 35, 50, 60, 55, 70, 65], 
            borderColor:'#4a90d9', 
            backgroundColor:g1, 
            fill:true 
          },
          { 
            label:'Revenue', 
            data:[5, 15, 25, 20, 30, 28, 45, 50, 48, 60, 58], 
            borderColor:'#90c8f8', 
            backgroundColor:g2, 
            fill:true 
          }
        ]
      }
    });
  }

  /* Traffic donut */
  const chartTrafficEl = document.getElementById('chartTraffic');

  if (chartTrafficEl) {
    chartInstances['chartTraffic'] = new Chart(chartTrafficEl, {
      type: 'doughnut',
      data: {
        labels: ['Paid Search','Direct','Social Media','Other','Email'],
        datasets: [{
          data:[43,30,14,8,5],
          backgroundColor:['#4a90d9','#ef5350','#ffb830','#26c6da','#ab47bc'],
          borderWidth:3,
          borderColor:'#fff'
        }]
      }
    });
  }

  /* ─── Update charts for dark theme ─── */
  function updateChartsForTheme(dark) {
    const gridColor  = dark ? '#334155' : '#f0f4fa';
    const tickColor  = dark ? '#64748b' : '#8a9bb5';
    const taskBg     = dark ? '#334155' : '#e8edf5';
    const trafficBorder = dark ? '#1e293b' : '#fff';

    const s = chartInstances['chartSales'];
    if (s) {
      s.options.scales.x.ticks.color = tickColor;
      s.options.scales.y.ticks.color = tickColor;
      s.options.scales.y.grid.color  = gridColor;
      s.update();
    }
    const t = chartInstances['chartTasks'];
    if (t) { t.data.datasets[0].backgroundColor[1] = taskBg; t.update(); }
    const tr = chartInstances['chartTraffic'];
    if (tr) { tr.data.datasets[0].borderColor = trafficBorder; tr.update(); }
  }

});