@extends('layouts.master')
@section('title', 'Visitors — KawachTech')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap');

#visitorsPage {
  font-family: 'Open Sans', sans-serif;
  color: var(--text-dark);
  background: var(--bg-body);
  min-height: 100vh;
}
#visitorsPage * { box-sizing: border-box; }
.vis-wrap { max-width: 1400px; margin: 0 auto; padding: 26px 20px 70px; }

.vis-topbar { display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:22px; }
.vis-breadcrumb { font-size:.73rem;color:var(--text-muted);display:flex;align-items:center;gap:6px;margin-bottom:4px; }
.vis-breadcrumb a { color:var(--primary);text-decoration:none;font-weight:600; }
.vis-page-title { font-family:'Nunito',sans-serif;font-weight:900;font-size:1.5rem;color:var(--text-dark);display:flex;align-items:center;gap:10px; }
.vis-page-title i { color:var(--primary);font-size:1.2rem; }
.vis-page-sub { font-size:.8rem;color:var(--text-muted);margin-top:4px; }

.vis-bots-toggle { display:flex;align-items:center;gap:8px;font-size:.8rem;color:var(--text-muted);font-weight:600; }

.vis-stats { display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:22px; }
.vis-stat-card { background:var(--card-bg);border:1px solid var(--border);border-radius:var(--card-radius);padding:16px 18px;display:flex;align-items:center;gap:14px;box-shadow:0 2px 12px rgba(26,115,232,.06); }
.vis-stat-icon { width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;background:#e8f1fd;color:var(--primary); }
[data-theme="dark"] .vis-stat-icon { background:rgba(26,115,232,.18); }
.vis-stat-val { font-family:'Nunito',sans-serif;font-weight:900;font-size:1.5rem;color:var(--text-dark);line-height:1; }
.vis-stat-lbl { font-size:.72rem;color:var(--text-muted);margin-top:2px; }

.vis-row { display:grid;grid-template-columns:1.6fr 1fr;gap:16px;margin-bottom:16px; }
@media(max-width:991px){ .vis-row{ grid-template-columns:1fr; } }

.vis-card { background:var(--card-bg);border:1px solid var(--border);border-radius:var(--card-radius);box-shadow:0 2px 14px rgba(26,115,232,.07);overflow:hidden; }
.vis-card-header { display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--border);background:var(--modal-header); }
.vis-card-header h2 { font-family:'Nunito',sans-serif;font-weight:900;font-size:.95rem;color:var(--panel-title);display:flex;align-items:center;gap:8px;margin:0; }
.vis-card-header h2 i { color:var(--primary); }
.vis-card-body { padding:18px 20px; }

.vis-breakdowns { display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px; }
@media(max-width:900px){ .vis-breakdowns{ grid-template-columns:1fr; } }

.vis-list { list-style:none;margin:0;padding:0; }
.vis-list li { display:flex;justify-content:space-between;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--border);font-size:.82rem; }
.vis-list li:last-child { border-bottom:none; }
.vis-list .lbl { color:var(--text-dark);font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:220px; }
.vis-list .val { color:var(--primary);font-weight:800;flex-shrink:0; }
.vis-empty-mini { text-align:center;padding:20px 0;color:var(--text-muted);font-size:.82rem; }

.vis-table-wrap { overflow-x:auto; }
.vis-table { width:100%;border-collapse:collapse;font-size:.82rem; }
.vis-table thead tr { background:var(--modal-header);border-bottom:2px solid var(--border); }
.vis-table th { padding:11px 14px;text-align:left;font-family:'Nunito',sans-serif;font-weight:800;font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;white-space:nowrap; }
.vis-table td { padding:11px 14px;border-bottom:1px solid var(--border);color:var(--text-dark);vertical-align:middle;white-space:nowrap; }
.vis-table tbody tr:hover { background:var(--modal-header); }
.vis-table tbody tr:last-child td { border-bottom:none; }

.vis-pill { display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;background:#eef2f9;color:var(--text-muted);white-space:nowrap; }
[data-theme="dark"] .vis-pill { background:rgba(138,155,181,.15); }
.vis-pill.new { background:rgba(0,168,124,.12);color:#00a87c; }
.vis-pill.bot { background:rgba(229,57,53,.1);color:#e53935; }

.btn-icon { width:30px;height:30px;border-radius:8px;border:1px solid var(--border);background:var(--card-bg);color:var(--text-muted);display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:all .15s;font-size:.78rem; }
.btn-icon:hover { border-color:var(--primary);color:var(--primary); }

.vis-empty { text-align:center;padding:60px 20px;color:var(--text-muted); }
.vis-empty i { font-size:2.6rem;opacity:.3;margin-bottom:14px;display:block; }

/* ── world map ── */
.vis-map { width:100%;height:340px; }
.vis-map svg { width:100%;height:100%; }
.vis-map-legend { display:flex;align-items:center;gap:10px;margin-top:10px;font-size:.72rem;color:var(--text-muted);font-weight:600;justify-content:center; }
.vis-map-scale { width:120px;height:8px;border-radius:4px;background:linear-gradient(90deg,#cfe2ff,#1a73e8); }
[data-theme="dark"] .vis-map-scale { background:linear-gradient(90deg,#24405f,#5b9dff); }
.jvm-tooltip { background:var(--modal-bg) !important;color:var(--text-dark) !important;border:1px solid var(--border) !important;border-radius:8px !important;padding:6px 10px !important;font-family:'Open Sans',sans-serif !important;font-size:.78rem !important;font-weight:600 !important;box-shadow:0 6px 20px rgba(0,0,0,.15) !important; }

/* ── ram-modal tokens (reused) ── */
.ram-modal-overlay { display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:900;align-items:center;justify-content:center;backdrop-filter:blur(3px);padding:20px; }
.ram-modal-overlay.show { display:flex; }
.ram-modal { background:var(--modal-bg);border-radius:14px;max-width:640px;width:100%;max-height:85vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.22); }
.ram-modal-header { padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;background:var(--modal-header);position:sticky;top:0; }
.ram-modal-header h3 { font-family:'Nunito',sans-serif;font-weight:900;font-size:1.05rem;color:var(--text-dark);margin:0; }
.ram-modal-close { width:28px;height:28px;border-radius:50%;border:none;background:var(--border);color:var(--text-muted);cursor:pointer;font-size:.78rem; }
.ram-modal-body { padding:20px; }

.pv-row { display:flex;justify-content:space-between;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid var(--border);font-size:.82rem; }
.pv-row:last-child { border-bottom:none; }
.pv-path { font-weight:700;color:var(--text-dark); }
.pv-time { color:var(--text-muted);font-size:.74rem; }
.pv-duration { font-weight:800;color:var(--primary);flex-shrink:0; }
</style>

<div id="visitorsPage">
<div class="vis-wrap">

  <div class="vis-topbar">
    <div>
      <div class="vis-breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a> <i class="fas fa-chevron-right" style="font-size:.6rem;"></i> <span>Visitors</span>
      </div>
      <div class="vis-page-title"><i class="fas fa-chart-line"></i> Visitor Analytics</div>
      <div class="vis-page-sub">Self-hosted traffic tracking — captures every visit directly, first-party only, independent of the cookie-consent banner.</div>
    </div>
    <label class="vis-bots-toggle">
      <input type="checkbox" id="includeBotsToggle" {{ $includeBots ? 'checked' : '' }} onchange="toggleBots(this.checked)">
      Include bots/crawlers ({{ $stats['bot_count'] }})
    </label>
  </div>

  <div class="vis-stats">
    <div class="vis-stat-card">
      <div class="vis-stat-icon"><i class="fas fa-users"></i></div>
      <div><div class="vis-stat-val">{{ number_format($stats['total_visitors']) }}</div><div class="vis-stat-lbl">Unique Visitors</div></div>
    </div>
    <div class="vis-stat-card">
      <div class="vis-stat-icon"><i class="fas fa-calendar-day"></i></div>
      <div><div class="vis-stat-val">{{ number_format($stats['today']) }}</div><div class="vis-stat-lbl">Today</div></div>
    </div>
    <div class="vis-stat-card">
      <div class="vis-stat-icon"><i class="fas fa-calendar-week"></i></div>
      <div><div class="vis-stat-val">{{ number_format($stats['this_week']) }}</div><div class="vis-stat-lbl">This Week</div></div>
    </div>
    <div class="vis-stat-card">
      <div class="vis-stat-icon"><i class="fas fa-calendar"></i></div>
      <div><div class="vis-stat-val">{{ number_format($stats['this_month']) }}</div><div class="vis-stat-lbl">This Month</div></div>
    </div>
    <div class="vis-stat-card">
      <div class="vis-stat-icon"><i class="fas fa-clock"></i></div>
      <div><div class="vis-stat-val">{{ gmdate('i:s', $stats['avg_time_seconds']) }}</div><div class="vis-stat-lbl">Avg. Time on Site</div></div>
    </div>
    <div class="vis-stat-card">
      <div class="vis-stat-icon"><i class="fas fa-user-check"></i></div>
      <div><div class="vis-stat-val">{{ number_format($stats['returning']) }}</div><div class="vis-stat-lbl">Returning Visitors</div></div>
    </div>
  </div>

  <div class="vis-row">
    <div class="vis-card">
      <div class="vis-card-header">
        <h2><i class="fas fa-earth-americas"></i> Traffic by Country</h2>
        <span class="vis-pill">{{ count($countryTraffic) }} {{ \Illuminate\Support\Str::plural('country', count($countryTraffic)) }}</span>
      </div>
      <div class="vis-card-body">
        @if(empty($countryTraffic))
          <div class="vis-empty" style="padding:40px 20px;">
            <i class="fas fa-map-location-dot"></i>
            <p>No location data yet. Country is resolved from each visitor's IP on the live site — local/private IPs aren't geolocated.</p>
          </div>
        @else
          <div id="worldMap" class="vis-map"></div>
          <div class="vis-map-legend">
            <span>Fewer</span>
            <span class="vis-map-scale"></span>
            <span>More visitors</span>
          </div>
        @endif
      </div>
    </div>
    <div class="vis-card">
      <div class="vis-card-header"><h2><i class="fas fa-mobile-screen"></i> Devices</h2></div>
      <div class="vis-card-body">
        @if($deviceBreakdown->isEmpty())
          <div class="vis-empty-mini">No data yet.</div>
        @else
          <canvas id="deviceChart" height="180"></canvas>
        @endif
      </div>
    </div>
  </div>

  <div class="vis-breakdowns">
    <div class="vis-card">
      <div class="vis-card-header"><h2><i class="fas fa-file-lines"></i> Top Pages</h2></div>
      <div class="vis-card-body">
        <ul class="vis-list">
          @forelse($topPages as $page)
            <li><span class="lbl" title="/{{ $page->path }}">/{{ $page->path }}</span><span class="val">{{ number_format($page->hits) }}</span></li>
          @empty
            <li class="vis-empty-mini" style="display:block;border:none;">No pageviews yet.</li>
          @endforelse
        </ul>
      </div>
    </div>
    <div class="vis-card">
      <div class="vis-card-header"><h2><i class="fas fa-arrow-turn-down"></i> Top Sources</h2></div>
      <div class="vis-card-body">
        <ul class="vis-list">
          @forelse($topSources as $source)
            <li><span class="lbl">{{ $source['label'] }}</span><span class="val">{{ number_format($source['total']) }}</span></li>
          @empty
            <li class="vis-empty-mini" style="display:block;border:none;">No data yet.</li>
          @endforelse
        </ul>
      </div>
    </div>
    <div class="vis-card">
      <div class="vis-card-header"><h2><i class="fas fa-earth-americas"></i> Top Countries</h2></div>
      <div class="vis-card-body">
        <ul class="vis-list">
          @forelse($topCountries as $c)
            <li><span class="lbl">{{ strtoupper($c->country) }}</span><span class="val">{{ number_format($c->total) }}</span></li>
          @empty
            <li class="vis-empty-mini" style="display:block;border:none;">No data yet.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>

  <div class="vis-card">
    <div class="vis-card-header"><h2><i class="fas fa-list"></i> Recent Visitors</h2></div>

    @if($visitors->isEmpty())
      <div class="vis-empty">
        <i class="fas fa-user-slash"></i>
        <p>No visitors recorded yet — check back once your site starts getting traffic.</p>
      </div>
    @else
      <div class="vis-table-wrap">
        <table class="vis-table">
          <thead>
            <tr>
              <th>Visitor</th>
              <th>First Seen</th>
              <th>Last Seen</th>
              <th>Visits</th>
              <th>Pageviews</th>
              <th>Total Time</th>
              <th>Entry Page</th>
              <th>Source</th>
              <th>Country</th>
              <th>Device</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($visitors as $v)
              <tr>
                <td>
                  #{{ $v->id }}
                  @if($v->visit_count == 1)<span class="vis-pill new">New</span>@endif
                  @if($v->is_bot)<span class="vis-pill bot">Bot</span>@endif
                </td>
                <td>{{ $v->first_seen_at?->format('d M Y, h:i A') }}</td>
                <td>{{ $v->last_seen_at?->format('d M Y, h:i A') }}</td>
                <td>{{ $v->visit_count }}</td>
                <td>{{ $v->total_pageviews }}</td>
                <td>{{ gmdate('i:s', $v->total_time_seconds) }}</td>
                <td>/{{ $v->entry_route }}</td>
                <td><span class="vis-pill">{{ $v->utm_source ?: (parse_url($v->referrer ?? '', PHP_URL_HOST) ?: 'Direct') }}</span></td>
                <td>{{ $v->country ? strtoupper($v->country) : '—' }}</td>
                <td><span class="vis-pill"><i class="fas fa-{{ $v->device_type === 'mobile' ? 'mobile-screen' : ($v->device_type === 'tablet' ? 'tablet-screen-button' : 'desktop') }}"></i> {{ ucfirst($v->device_type ?? 'Unknown') }}</span></td>
                <td>
                  <button class="btn-icon" onclick="viewPageviews({{ $v->id }})" title="View page history"><i class="fas fa-route"></i></button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @if($visitors->hasPages())
      <div style="padding:16px 20px;">
        {{ $visitors->links() }}
      </div>
      @endif
    @endif
  </div>

</div>
</div>

<div class="ram-modal-overlay" id="pageviewsModal">
  <div class="ram-modal">
    <div class="ram-modal-header">
      <h3><i class="fas fa-route"></i> Page History — Visitor #<span id="pvVisitorId"></span></h3>
      <button class="ram-modal-close" onclick="document.getElementById('pageviewsModal').classList.remove('show')"><i class="fas fa-times"></i></button>
    </div>
    <div class="ram-modal-body" id="pageviewsBody"></div>
  </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
(function () {
'use strict';

/* ── World map: traffic by country (GA-style choropleth) ── */
const mapValues = @json((object) $countryTraffic);
const mapEl = document.getElementById('worldMap');
if (mapEl && typeof jsVectorMap !== 'undefined') {
  const isDark = document.documentElement.getAttribute('data-theme') === 'dark'
              || document.body.getAttribute('data-theme') === 'dark';

  // Interpolate #cfe2ff → #1a73e8 ourselves so equal values / single country
  // don't break the library's built-in normalizer (which divides by range).
  const nums = Object.values(mapValues);
  const maxV = nums.length ? Math.max(...nums) : 1;
  const minV = nums.length ? Math.min(...nums) : 0;
  const lo = [207, 226, 255], hi = [26, 115, 232];
  function colorFor(n) {
    const t = maxV === minV ? 1 : (n - minV) / (maxV - minV);
    const c = lo.map((x, i) => Math.round(x + (hi[i] - x) * (0.25 + 0.75 * t)));
    return `rgb(${c[0]},${c[1]},${c[2]})`;
  }
  const regionColors = {};
  Object.keys(mapValues).forEach(code => { regionColors[code] = colorFor(mapValues[code]); });

  try {
    const map = new jsVectorMap({
      selector: '#worldMap',
      map: 'world',
      zoomButtons: true,
      zoomOnScroll: false,
      backgroundColor: 'transparent',
      regionStyle: {
        initial: { fill: isDark ? '#2b3644' : '#e4e9f0', stroke: isDark ? '#1c232d' : '#ffffff', strokeWidth: 0.4 },
        hover: { fillOpacity: 1, fill: '#0f5bd1' },
      },
      onLoaded(mapInstance) {
        const inst = mapInstance || map;
        Object.keys(regionColors).forEach(code => {
          const region = inst.regions && inst.regions[code];
          const shape = region && (region.element ? (region.element.shape || region.element) : null);
          if (shape && typeof shape.setStyle === 'function') shape.setStyle('fill', regionColors[code]);
        });
      },
      onRegionTooltipShow(event, tooltip, code) {
        try {
          const n = mapValues[code] || 0;
          const name = (typeof tooltip.text === 'function') ? tooltip.text() : code;
          const label = `${name}: ${Number(n).toLocaleString()} visitor${n === 1 ? '' : 's'}`;
          if (typeof tooltip.text === 'function') tooltip.text(label);
          else if (tooltip._tooltip) tooltip._tooltip.innerHTML = label;
        } catch (e) {}
      },
    });

    // Fallback in case onLoaded fires before regions are attached
    setTimeout(() => {
      Object.keys(regionColors).forEach(code => {
        const region = map.regions && map.regions[code];
        const shape = region && (region.element ? (region.element.shape || region.element) : null);
        if (shape && typeof shape.setStyle === 'function') shape.setStyle('fill', regionColors[code]);
      });
    }, 200);
  } catch (e) {
    mapEl.innerHTML = '<div class="vis-empty-mini">Map failed to load.</div>';
  }
}

@if($deviceBreakdown->isNotEmpty())
new Chart(document.getElementById('deviceChart'), {
  type: 'doughnut',
  data: {
    labels: @json($deviceBreakdown->pluck('device_type')->map(fn($d) => ucfirst($d ?? 'Unknown'))),
    datasets: [{
      data: @json($deviceBreakdown->pluck('total')),
      backgroundColor: ['#1a73e8', '#00a87c', '#ffb830', '#6c2bd9'],
    }],
  },
  options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } } },
});
@endif

window.toggleBots = function (checked) {
  const url = new URL(window.location.href);
  if (checked) url.searchParams.set('include_bots', '1');
  else url.searchParams.delete('include_bots');
  window.location.href = url.toString();
};

window.viewPageviews = async function (visitorId) {
  document.getElementById('pvVisitorId').textContent = visitorId;
  const body = document.getElementById('pageviewsBody');
  body.innerHTML = '<div style="text-align:center;padding:30px 0;color:var(--text-muted);">Loading…</div>';
  document.getElementById('pageviewsModal').classList.add('show');

  try {
    const res = await fetch(`/visitors/${visitorId}/pageviews`, { headers: { 'Accept': 'application/json' } });
    const data = await res.json();
    if (!data.pageviews.length) {
      body.innerHTML = '<div style="text-align:center;padding:30px 0;color:var(--text-muted);">No pageviews recorded.</div>';
      return;
    }
    body.innerHTML = data.pageviews.map(pv => `
      <div class="pv-row">
        <div>
          <div class="pv-path">/${pv.path}</div>
          <div class="pv-time">${pv.visited_at}</div>
        </div>
        <div class="pv-duration">${pv.time_spent_seconds != null ? pv.time_spent_seconds + 's' : '—'}</div>
      </div>
    `).join('');
  } catch (e) {
    body.innerHTML = `<div style="text-align:center;padding:30px 0;color:var(--red);">Failed to load: ${e.message}</div>`;
  }
};

document.getElementById('pageviewsModal').addEventListener('click', (e) => {
  if (e.target.id === 'pageviewsModal') e.target.classList.remove('show');
});

})();
</script>
@endpush

@endsection
