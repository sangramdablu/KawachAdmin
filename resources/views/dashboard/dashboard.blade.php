@extends('layouts.master')
@section('content')

  <div class="content-inner">

    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
      <div class="page-title mb-0" id="pageTitle">Dashboard</div>
      <div style="font-size:.8rem;color:var(--text-muted);">
        <i class="fas fa-calendar-alt" style="color:var(--primary);"></i>
        <span id="liveDate"></span>
      </div>
    </div>

    <!-- STAT CARDS -->
    <div class="row g-3 mb-3">
      <div class="col-lg-3 col-md-6">
        <div class="stat-card">
          <div class="stat-card-top">
            <span class="stat-label">Revenue</span>
            <span class="stat-badge badge-green">+15.8%</span>
          </div>
          <div class="stat-value">$24,560</div>
          <div class="stat-chart-area"><canvas id="chartRevenue"></canvas></div>
          <div class="stat-footer"><span>Last 12 months</span><select><option>Monthly ▾</option><option>Weekly</option><option>Yearly</option></select></div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="stat-card">
          <div class="stat-card-top">
            <span class="stat-label">Active Users</span>
            <span class="stat-badge badge-blue-l">+5.4%</span>
          </div>
          <div class="stat-value">5,430</div>
          <div class="stat-chart-area"><canvas id="chartUsers"></canvas></div>
          <div class="stat-footer"><span>Last 12 months</span><select><option>Monthly ▾</option><option>Weekly</option></select></div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="stat-card">
          <div class="stat-card-top">
            <span class="stat-label">Growth</span>
            <span class="stat-badge badge-teal">+12.4%</span>
          </div>
          <div class="stat-value">150%</div>
          <div class="stat-chart-area"><canvas id="chartGrowth"></canvas></div>
          <div class="stat-footer"><span>vs last quarter</span><select><option>Quarterly ▾</option><option>Monthly</option></select></div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="stat-card">
          <div class="stat-card-top">
            <span class="stat-label">Tasks</span>
            <span class="stat-badge badge-red-s">88%</span>
          </div>
          <div class="stat-value">43<span> /50</span></div>
          <div class="tasks-right">
            <div class="task-progress-wrap">
              <div class="task-completed-label">Completed</div>
              <div class="task-bar"><div class="task-bar-fill" style="width:88%"></div></div>
              <div style="font-size:.7rem;color:var(--text-muted);">7 tasks remaining</div>
            </div>
            <div style="position:relative;width:72px;height:72px;flex-shrink:0;">
              <canvas id="chartTasks" width="72" height="72"></canvas>
              <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-family:'Nunito',sans-serif;font-weight:900;font-size:.9rem;color:var(--text-dark);">88%</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- MIDDLE ROW -->
    <div class="row g-3 mb-3">
      <!-- Recent Activity -->
      <div class="col-lg-5">
        <div class="panel-card">
          <div class="panel-header">
            <div class="panel-title">Recent Activity</div>
            <div class="panel-dots" title="More options"><i class="fas fa-ellipsis-h"></i></div>
          </div>
          <div class="activity-item">
            <div class="activity-avatar" style="background:#dceeff;color:#1a73e8;">AM</div>
            <div class="activity-body">
              <div class="activity-name">Alex Mercer</div>
              <div class="activity-text">Pushed new feature branch to repository</div>
            </div>
            <div class="activity-time">27 min ago</div>
          </div>
          <div class="activity-item">
            <div class="activity-avatar" style="background:#fce4ec;color:#e91e63;">SJ</div>
            <div class="activity-body">
              <div class="activity-name">Sarah Johnson</div>
              <div class="activity-text">Completed UI design for the new dashboard</div>
            </div>
            <div class="activity-time">1 hour ago</div>
          </div>
          <div class="activity-item">
            <div class="activity-avatar" style="background:#e8f5e9;color:#388e3c;">JB</div>
            <div class="activity-body">
              <div class="activity-name">John Brown</div>
              <div class="activity-text">Deployed backend API to production server</div>
            </div>
            <div class="activity-time">3 hours ago</div>
          </div>
        </div>
      </div>

      <!-- Sales & Revenue -->
      <div class="col-lg-7">
        <div class="panel-card">
          <div class="panel-header">
            <div class="panel-title">Sales &amp; Revenue</div>
            <div class="chart-legend">
              <div class="legend-label"><div class="legend-dot" style="background:#4a90d9;"></div> Sales</div>
              <div class="legend-label"><div class="legend-dot" style="background:#90c8f8;"></div> Revenue</div>
            </div>
          </div>
          <div style="height:220px;"><canvas id="chartSales"></canvas></div>
        </div>
      </div>
    </div>

    <!-- BOTTOM ROW -->
    <div class="row g-3">
      <!-- AI Analytics -->
      <div class="col-lg-5">
        <div class="panel-card">
          <div class="panel-header">
            <div class="panel-title">AI Analytics</div>
            <div class="analytics-toolbar">
              <button class="active" title="List view"><i class="fas fa-bars"></i></button>
              <button title="Grid view"><i class="fas fa-th"></i></button>
              <button title="Chart view"><i class="fas fa-chart-bar"></i></button>
            </div>
          </div>
          <div class="analytics-row">
            <div class="analytics-dot" style="background:#4a90d9;"></div>
            <div class="analytics-label">Sustained words</div>
            <div class="analytics-bar-wrap"><div class="analytics-bar" style="width:72%;background:#4a90d9;"></div></div>
            <div class="analytics-val">72%</div>
          </div>
          <div class="analytics-row">
            <div class="analytics-dot" style="background:#ef5350;"></div>
            <div class="analytics-label">Net mote Vods</div>
            <div class="analytics-bar-wrap"><div class="analytics-bar" style="width:48%;background:#ef5350;"></div></div>
            <div class="analytics-val">48%</div>
          </div>
          <div class="analytics-row">
            <div class="analytics-dot" style="border:2px solid #26c6da;background:transparent;"></div>
            <div class="analytics-label">Got news</div>
            <div class="analytics-bar-wrap"><div class="analytics-bar" style="width:33%;background:#26c6da;"></div></div>
            <div class="analytics-val">33%</div>
          </div>
          <div class="analytics-row">
            <div class="analytics-dot" style="border:2px solid #ffb830;background:transparent;"></div>
            <div class="analytics-label">Outeerditions</div>
            <div class="analytics-bar-wrap"><div class="analytics-bar" style="width:25%;background:#ffb830;"></div></div>
            <div class="analytics-val">25%</div>
          </div>
        </div>
      </div>

      <!-- Traffic Sources -->
      <div class="col-lg-7">
        <div class="panel-card">
          <div class="panel-header">
            <div class="panel-title">Traffic Sources</div>
          </div>
          <div class="row align-items-center">
            <div class="col-7">
              <div class="traffic-item">
                <div class="traffic-rank">1</div>
                <div class="traffic-name">Paid Search</div>
                <div class="traffic-pct">43.0%</div>
              </div>
              <div class="traffic-item">
                <div class="traffic-rank" style="background:#b3d4f8;color:#1a73e8;">8</div>
                <div class="traffic-name">Direct</div>
                <div class="traffic-pct">30.0%</div>
              </div>
              <div class="traffic-item">
                <div class="traffic-rank rank-dark">9</div>
                <div class="traffic-name">Social Media</div>
                <div class="traffic-pct">14.0%</div>
              </div>
            </div>
            <div class="col-5 d-flex justify-content-center">
              <div style="width:160px;height:160px;position:relative;">
                <canvas id="chartTraffic"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /bottom row -->

  </div><!-- /content-inner -->

@endsection

  @push('scripts')


  @endpush