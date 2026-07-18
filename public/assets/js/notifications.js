/**
 * Generic real-time notification pipeline. Any backend notification that
 * extends App\Notifications\BaseNotification (comments today, anything else
 * tomorrow — e.g. a new contact-form message) broadcasts a payload shaped
 * like { title, message, url, icon, color } on the user's private channel.
 * This file is the one common place that turns that payload into UI —
 * nothing here is specific to comments or tasks.
 */
(function () {
  if (typeof window.__authUserId === 'undefined' || window.__authUserId === null) return;
  if (typeof Echo === 'undefined') return;

  const driver = window.__broadcastDriver;
  const cfg = window.__broadcastConfig || {};
  const authHeaders = { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content };

  if (driver === 'pusher') {
    window.Echo = new Echo({
      broadcaster: 'pusher',
      key: cfg.key,
      cluster: cfg.cluster,
      forceTLS: true,
      authEndpoint: '/broadcasting/auth',
      auth: { headers: authHeaders },
    });
  } else if (driver === 'reverb') {
    window.Echo = new Echo({
      broadcaster: 'reverb',
      key: cfg.key,
      wsHost: cfg.host,
      wsPort: cfg.port,
      wssPort: cfg.port,
      forceTLS: cfg.scheme === 'https',
      enabledTransports: cfg.scheme === 'https' ? ['wss'] : ['ws'],
      authEndpoint: '/broadcasting/auth',
      auth: { headers: authHeaders },
    });
  } else {
    return;
  }

  function renderNotification(n) {
    const list = document.getElementById('notifList');
    if (!list) return;

    const empty = document.getElementById('notifEmpty');
    if (empty) empty.remove();

    const color = n.color || '#1a73e8';
    const icon = n.icon || 'fas fa-bell';
    const item = document.createElement('div');
    item.className = 'notif-item unread';
    item.dataset.id = n.id || '';
    item.dataset.url = n.url || '#';
    item.innerHTML = `
      <div class="notif-icon" style="background:${color}1a; color:${color};"><i class="${icon}"></i></div>
      <div class="notif-body">
        <strong>${n.title || 'Notification'}</strong>
        <p>${n.message || ''}</p>
        <span class="notif-time"><i class="fas fa-clock" style="font-size:.65rem;"></i> just now</span>
      </div>`;
    list.prepend(item);

    if (typeof window.updateNotifBadge === 'function') window.updateNotifBadge();
    if (typeof window.showToast === 'function') window.showToast(n.title || 'New notification', color, icon);
  }

  // Laravel wraps every ->notify() broadcast in
  // Illuminate\Notifications\Events\BroadcastNotificationCreated and (since
  // that event defines its own broadcastAs()) sends it under that exact,
  // undotted class name — Echo's .notification() sugar listens for a dotted
  // variant that doesn't match here, so bind to the confirmed wire name directly.
  window.Echo.private('App.Models.User.' + window.__authUserId)
    .listen('.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', renderNotification);
})();
