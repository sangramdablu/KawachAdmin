@extends('layouts.master')
@section('title', 'Jobs — KawachTech')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap');

#jobsPage {
  font-family: 'Open Sans', sans-serif;
  color: var(--text-dark);
  background: var(--bg-body);
  min-height: 100vh;
}
#jobsPage * { box-sizing: border-box; }
.jobs-wrap { max-width: 1400px; margin: 0 auto; padding: 26px 20px 70px; }

.jobs-topbar { display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:22px; }
.jobs-breadcrumb { font-size:.73rem;color:var(--text-muted);display:flex;align-items:center;gap:6px;margin-bottom:4px; }
.jobs-breadcrumb a { color:var(--primary);text-decoration:none;font-weight:600; }
.jobs-breadcrumb a:hover { text-decoration:underline; }
.jobs-page-title { font-family:'Nunito',sans-serif;font-weight:900;font-size:1.5rem;color:var(--text-dark);display:flex;align-items:center;gap:10px; }
.jobs-page-title i { color:var(--primary);font-size:1.2rem; }
.jobs-page-sub { font-size:.8rem;color:var(--text-muted);margin-top:4px; }

.jobs-stats { display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:22px; }
.jobs-stat-card { background:var(--card-bg);border:1px solid var(--border);border-radius:var(--card-radius);padding:16px 18px;display:flex;align-items:center;gap:14px;box-shadow:0 2px 12px rgba(26,115,232,.06); }
.jobs-stat-icon { width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;background:#e8f1fd;color:var(--primary); }
[data-theme="dark"] .jobs-stat-icon { background:rgba(26,115,232,.18); }
.jobs-stat-val { font-family:'Nunito',sans-serif;font-weight:900;font-size:1.5rem;color:var(--text-dark);line-height:1; }
.jobs-stat-lbl { font-size:.72rem;color:var(--text-muted);margin-top:2px; }

.jobs-card { background:var(--card-bg);border:1px solid var(--border);border-radius:var(--card-radius);box-shadow:0 2px 14px rgba(26,115,232,.07);overflow:hidden; }
.jobs-card-header { display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--border);background:var(--modal-header);flex-wrap:wrap;gap:10px; }
.jobs-card-header h2 { font-family:'Nunito',sans-serif;font-weight:900;font-size:.95rem;color:var(--panel-title);display:flex;align-items:center;gap:8px;margin:0; }
.jobs-card-header h2 i { color:var(--primary); }

.jobs-table-wrap { overflow-x:auto; }
.jobs-table { width:100%;border-collapse:collapse;font-size:.83rem; }
.jobs-table thead tr { background:var(--modal-header);border-bottom:2px solid var(--border); }
.jobs-table th { padding:11px 16px;text-align:left;font-family:'Nunito',sans-serif;font-weight:800;font-size:.74rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;white-space:nowrap; }
.jobs-table td { padding:12px 16px;border-bottom:1px solid var(--border);color:var(--text-dark);vertical-align:middle; }
.jobs-table tbody tr:hover { background:var(--modal-header); }
.jobs-table tbody tr:last-child td { border-bottom:none; }

.job-title-cell { font-weight:700;font-size:.86rem;color:var(--text-dark); }
.job-slug-cell { font-size:.72rem;color:var(--text-muted); }
.job-meta-pill { display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;background:#eef2f9;color:var(--text-muted);white-space:nowrap; }
[data-theme="dark"] .job-meta-pill { background:rgba(138,155,181,.15); }

.status-dot { width:7px;height:7px;border-radius:50%;display:inline-block;margin-right:4px; }
.status-dot.active   { background:var(--green); }
.status-dot.inactive { background:var(--red); }

.job-status-toggle { cursor:pointer;border:none;background:none;padding:0;font-size:.8rem;display:inline-flex;align-items:center;color:var(--text-dark); }
.job-status-toggle:hover { text-decoration:underline; }

.job-actions { display:flex;gap:6px; }
.btn-icon { width:30px;height:30px;border-radius:8px;border:1px solid var(--border);background:var(--card-bg);color:var(--text-muted);display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:all .15s;font-size:.78rem; }
.btn-icon:hover { border-color:var(--primary);color:var(--primary); }
.btn-icon.del:hover { border-color:var(--red);color:var(--red);background:rgba(229,57,53,.08); }

.jobs-empty { text-align:center;padding:60px 20px;color:var(--text-muted); }
.jobs-empty i { font-size:2.6rem;opacity:.3;margin-bottom:14px;display:block; }
.jobs-empty p { font-size:.88rem;margin:0 0 6px; }

@media(max-width:768px){.jobs-topbar{flex-direction:column;}}

/* ── Reused ram-modal tokens (same as Team/Roles) ── */
.btn-ram { display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:8px;font-size:.82rem;font-weight:700;cursor:pointer;border:none;transition:all .2s;font-family:'Open Sans',sans-serif;white-space:nowrap; }
.btn-ram:hover { transform:translateY(-1px); }
.btn-ram-success { background:var(--green);color:#fff; }
.btn-ram-success:hover { background:#00a87c; }
.btn-ram-outline { background:var(--card-bg);color:var(--text-dark);border:1.5px solid var(--border); }
.btn-ram-outline:hover { border-color:var(--primary);color:var(--primary); }

.ram-modal-overlay { display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:900;align-items:center;justify-content:center;backdrop-filter:blur(3px);padding:20px; }
.ram-modal-overlay.show { display:flex; }
.ram-modal { background:var(--modal-bg);border-radius:14px;max-width:680px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.22);animation:ramPop .2s ease; }
@keyframes ramPop { from{opacity:0;transform:scale(.94);}to{opacity:1;transform:scale(1);} }
.ram-modal-header { padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;background:var(--modal-header);position:sticky;top:0;z-index:2; }
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
.ram-label .opt { font-size:.65rem;color:var(--text-muted);font-weight:400; }
.ram-input,.ram-select,.ram-textarea { width:100%;border:1.5px solid var(--input-border);border-radius:8px;padding:9px 13px;font-size:.875rem;color:var(--input-color);background:var(--input-bg);outline:none;transition:border-color .2s,box-shadow .2s;font-family:'Open Sans',sans-serif; }
.ram-input:focus,.ram-select:focus,.ram-textarea:focus { border-color:var(--primary);box-shadow:0 0 0 3px rgba(26,115,232,.11); }
.ram-textarea { resize:vertical;min-height:78px; }
.ram-hint { font-size:.72rem;color:var(--text-muted);margin-top:4px; }
.ram-grid-2 { display:grid;grid-template-columns:1fr 1fr;gap:12px; }
.ram-grid-3 { display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px; }
@media(max-width:560px){.ram-grid-2,.ram-grid-3{grid-template-columns:1fr;}}

.ram-toast-stack { position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none; }
.ram-toast { background:var(--card-bg);border:1px solid var(--border);border-radius:10px;padding:11px 15px;display:flex;align-items:center;gap:9px;box-shadow:0 6px 24px rgba(0,0,0,.12);font-size:.81rem;color:var(--text-dark);pointer-events:all;animation:ramToastIn .2s ease;max-width:320px; }
@keyframes ramToastIn { from{opacity:0;transform:translateX(14px);}to{opacity:1;transform:none;} }
.ram-spinner { display:inline-block;width:13px;height:13px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite; }
@keyframes spin { to{transform:rotate(360deg);} }

.app-card{ border:1px solid var(--border);border-radius:10px;padding:14px 16px;margin-bottom:12px; }
.app-card:last-child{ margin-bottom:0; }
.app-card-top{ display:flex;justify-content:space-between;align-items:flex-start;gap:10px;flex-wrap:wrap; }
.app-name{ font-weight:800;font-size:.92rem;color:var(--text-dark); }
.app-email{ font-size:.78rem;color:var(--text-muted); }
.app-meta{ display:flex;flex-wrap:wrap;gap:8px;margin-top:8px; }
.app-meta span{ font-size:.74rem;color:var(--text-muted);background:var(--modal-header);border:1px solid var(--border);padding:3px 9px;border-radius:14px; }
.app-cover{ font-size:.8rem;color:var(--text-dark);margin-top:10px;background:var(--modal-header);border-radius:8px;padding:10px 12px;white-space:pre-wrap; }
.app-actions{ display:flex;align-items:center;gap:10px;margin-top:10px;flex-wrap:wrap; }
.app-resume-btn{ font-size:.78rem;font-weight:700;color:var(--primary);text-decoration:none;display:inline-flex;align-items:center;gap:6px; }
.app-resume-btn:hover{ text-decoration:underline; }
.app-status-select{ font-size:.76rem;border:1px solid var(--border);border-radius:8px;padding:5px 9px;background:var(--input-bg);color:var(--input-color); }
.app-status-badge{ font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:14px;background:var(--modal-header);color:var(--text-muted);white-space:nowrap; }
.app-empty{ text-align:center;padding:40px 0;color:var(--text-muted); }
</style>

<div id="jobsPage">
<div class="jobs-wrap">

  {{-- TOP BAR --}}
  <div class="jobs-topbar">
    <div>
      <div class="jobs-breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a> <i class="fas fa-chevron-right" style="font-size:.6rem;"></i> <span>Jobs</span>
      </div>
      <div class="jobs-page-title"><i class="fas fa-briefcase"></i> Job Postings</div>
      <div class="jobs-page-sub">Manage the openings shown on the public Careers page — add, edit, activate/deactivate, or remove roles.</div>
    </div>
    @can('jobs.edit')
    <div>
      <button class="btn-ram btn-ram-success" onclick="openCreateModal()">
        <i class="fas fa-plus"></i> Add Job Posting
      </button>
    </div>
    @endcan
  </div>

  {{-- STATS --}}
  <div class="jobs-stats">
    <div class="jobs-stat-card">
      <div class="jobs-stat-icon"><i class="fas fa-briefcase"></i></div>
      <div>
        <div class="jobs-stat-val">{{ $jobs->count() }}</div>
        <div class="jobs-stat-lbl">Total Postings</div>
      </div>
    </div>
    <div class="jobs-stat-card">
      <div class="jobs-stat-icon"><i class="fas fa-circle-check"></i></div>
      <div>
        <div class="jobs-stat-val">{{ $jobs->where('status', 'active')->count() }}</div>
        <div class="jobs-stat-lbl">Active</div>
      </div>
    </div>
    <div class="jobs-stat-card">
      <div class="jobs-stat-icon"><i class="fas fa-circle-pause"></i></div>
      <div>
        <div class="jobs-stat-val">{{ $jobs->where('status', 'inactive')->count() }}</div>
        <div class="jobs-stat-lbl">Inactive</div>
      </div>
    </div>
    <div class="jobs-stat-card">
      <div class="jobs-stat-icon"><i class="fas fa-file-lines"></i></div>
      <div>
        <div class="jobs-stat-val">{{ $jobs->sum('applications_count') }}</div>
        <div class="jobs-stat-lbl">Applications Received</div>
      </div>
    </div>
  </div>

  {{-- TABLE CARD --}}
  <div class="jobs-card">
    <div class="jobs-card-header">
      <h2><i class="fas fa-list"></i> All Job Postings</h2>
      <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <div class="hint" style="font-size:.74rem;color:var(--text-muted);">Only <strong>Active</strong> postings appear on the public Careers page</div>
        <select class="ram-select" id="jobCountryFilter" style="width:auto;padding:6px 10px;font-size:.78rem;" onchange="filterJobsByCountry(this.value)">
          <option value="">Filter: All Countries</option>
          @foreach(\App\Models\JobPosting::COUNTRIES as $code => $name)
            <option value="{{ $code }}">Filter: {{ $name }}</option>
          @endforeach
          <option value="global">Filter: Global only</option>
        </select>
      </div>
    </div>

    @if($jobs->isEmpty())
      <div class="jobs-empty">
        <i class="fas fa-briefcase"></i>
        <p>No job postings yet.</p>
        @can('jobs.edit')
        <div><button class="btn-ram btn-ram-success" onclick="openCreateModal()"><i class="fas fa-plus"></i> Add Your First Job Posting</button></div>
        @endcan
      </div>
    @else
      <div class="jobs-table-wrap">
        <table class="jobs-table">
          <thead>
            <tr>
              <th>Position</th>
              <th>Department</th>
              <th>Location</th>
              <th>Countries</th>
              <th>Type</th>
              <th>Openings</th>
              <th>Applications</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="jobsTbody">
            @foreach($jobs as $job)
              <tr id="job-row-{{ $job->id }}" data-countries="{{ implode(',', $job->countries ?? []) }}">
                <td>
                  <div class="job-title-cell">{{ $job->title }}</div>
                  <div class="job-slug-cell">/{{ $job->slug }}</div>
                </td>
                <td><span class="job-meta-pill">{{ $job->department }}</span></td>
                <td>{{ $job->location }}</td>
                <td>
                  @forelse($job->countries ?? [] as $code)
                    <span class="job-meta-pill">{{ \App\Models\JobPosting::COUNTRIES[$code] ?? strtoupper($code) }}</span>
                  @empty
                    <span class="job-meta-pill" style="background:rgba(0,168,124,.12);color:#00a87c;">🌍 Global</span>
                  @endforelse
                </td>
                <td><span class="job-meta-pill">{{ $job->type }}</span></td>
                <td>{{ $job->openings }}</td>
                <td>
                  <button class="job-status-toggle" onclick="viewApplications({{ $job->id }}, {{ Illuminate\Support\Js::from($job->title) }})">
                    <i class="fas fa-file-lines"></i> {{ $job->applications_count }}
                  </button>
                </td>
                <td>
                  @can('jobs.edit')
                  <button class="job-status-toggle" id="job-status-{{ $job->id }}" onclick="toggleJobStatus({{ $job->id }})">
                    <span class="status-dot {{ $job->status }}"></span> {{ ucfirst($job->status) }}
                  </button>
                  @else
                  <span style="font-size:.8rem;"><span class="status-dot {{ $job->status }}"></span> {{ ucfirst($job->status) }}</span>
                  @endcan
                </td>
                <td>
                  <div class="job-actions">
                    @can('jobs.edit')
                    <button class="btn-icon" onclick="editJob({{ $job->id }})" title="Edit"><i class="fas fa-pen"></i></button>
                    <button class="btn-icon del" onclick="deleteJob({{ $job->id }}, {{ Illuminate\Support\Js::from($job->title) }})" title="Delete"><i class="fas fa-trash"></i></button>
                    @endcan
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

</div>
</div>

@can('jobs.edit')
{{-- Create / Edit Job modal — one form, mode switches between POST (create) and PATCH (update) --}}
<div class="ram-modal-overlay" id="jobModal">
  <div class="ram-modal">
    <div class="ram-modal-header">
      <h3><i class="fas fa-briefcase"></i> <span id="jobModalTitle">Add Job Posting</span></h3>
      <button class="ram-modal-close" onclick="closeModal('jobModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="ram-modal-body">
      <input type="hidden" id="jobId" value="">

      <div class="ram-grid-2">
        <div class="ram-form-group">
          <label class="ram-label"><i class="fas fa-heading"></i> Job Title *</label>
          <input type="text" class="ram-input" id="jobTitle" placeholder="e.g. Node.js Developer" maxlength="150">
        </div>
        <div class="ram-form-group">
          <label class="ram-label"><i class="fas fa-building"></i> Department *</label>
          <input type="text" class="ram-input" id="jobDepartment" placeholder="e.g. Engineering" maxlength="100">
        </div>
      </div>

      <div class="ram-grid-2">
        <div class="ram-form-group">
          <label class="ram-label"><i class="fas fa-location-dot"></i> Location *</label>
          <input type="text" class="ram-input" id="jobLocation" placeholder="e.g. Remote / New Delhi, India" maxlength="150">
        </div>
        <div class="ram-form-group">
          <label class="ram-label"><i class="fas fa-clock"></i> Job Type *</label>
          <select class="ram-select" id="jobType">
            <option value="Full-time">Full-time</option>
            <option value="Part-time">Part-time</option>
            <option value="Contract">Contract</option>
            <option value="Internship">Internship</option>
            <option value="Freelance">Freelance</option>
          </select>
        </div>
      </div>

      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-globe"></i> Target Countries <span class="opt">(leave all unchecked = Global, visible to every visitor)</span></label>
        <div id="jobCountriesGroup" style="display:flex;flex-wrap:wrap;gap:8px;">
          @foreach(\App\Models\JobPosting::COUNTRIES as $code => $name)
            <label style="display:inline-flex;align-items:center;gap:5px;font-size:.8rem;font-weight:600;color:var(--text-dark);background:var(--modal-header);border:1px solid var(--border);border-radius:20px;padding:5px 12px;cursor:pointer;">
              <input type="checkbox" class="job-country-cb" value="{{ $code }}" style="accent-color:var(--primary);">
              {{ $name }}
            </label>
          @endforeach
        </div>
        <div class="ram-hint">A job ranks higher for visitors from — or who select — a checked country, but still shows to everyone.</div>
      </div>

      <div class="ram-grid-3">
        <div class="ram-form-group">
          <label class="ram-label"><i class="fas fa-layer-group"></i> Experience Level *</label>
          <input type="text" class="ram-input" id="jobExperience" placeholder="e.g. 2-5 years" maxlength="50">
        </div>
        <div class="ram-form-group">
          <label class="ram-label"><i class="fas fa-users"></i> Openings</label>
          <input type="number" class="ram-input" id="jobOpenings" min="1" max="999" value="1">
        </div>
        <div class="ram-form-group">
          <label class="ram-label"><i class="fas fa-toggle-on"></i> Status</label>
          <select class="ram-select" id="jobStatus">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
      </div>

      <div class="ram-grid-2">
        <div class="ram-form-group">
          <label class="ram-label"><i class="fas fa-money-bill-wave"></i> Salary Range <span class="opt">(optional)</span></label>
          <input type="text" class="ram-input" id="jobSalary" placeholder="e.g. Competitive, based on experience" maxlength="150">
        </div>
        <div class="ram-form-group">
          <label class="ram-label"><i class="fas fa-calendar-xmark"></i> Application Deadline <span class="opt">(optional)</span></label>
          <input type="date" class="ram-input" id="jobDeadline">
        </div>
      </div>

      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-align-left"></i> Summary *</label>
        <textarea class="ram-textarea" id="jobSummary" maxlength="2000" placeholder="A short paragraph describing the role."></textarea>
      </div>

      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-list-check"></i> Responsibilities * <span class="opt">(one per line)</span></label>
        <textarea class="ram-textarea" id="jobResponsibilities" placeholder="Design, build, and maintain scalable REST APIs...&#10;Collaborate with frontend and DevOps engineers..."></textarea>
      </div>

      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-clipboard-check"></i> Requirements * <span class="opt">(one per line)</span></label>
        <textarea class="ram-textarea" id="jobRequirements" placeholder="2+ years of professional experience...&#10;Strong understanding of REST API design..."></textarea>
      </div>

      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-star"></i> Nice to Have <span class="opt">(optional, one per line)</span></label>
        <textarea class="ram-textarea" id="jobNiceToHave" placeholder="Experience with TypeScript or GraphQL...&#10;Exposure to AWS or other cloud infrastructure..."></textarea>
      </div>

      <div class="ram-form-group">
        <label class="ram-label"><i class="fas fa-sort"></i> Display Order <span class="opt">(optional — lower shows first)</span></label>
        <input type="number" class="ram-input" id="jobSortOrder" min="0" value="0">
      </div>
    </div>
    <div class="ram-modal-footer">
      <button class="btn-ram btn-ram-outline" onclick="closeModal('jobModal')">Cancel</button>
      <button class="btn-ram btn-ram-success" id="jobSaveBtn" onclick="saveJob()">
        <i class="fas fa-check"></i> <span id="jobSaveBtnText">Post Job</span>
      </button>
    </div>
  </div>
</div>
@endcan

{{-- Applications modal — resumes live on the public site's own disk, so
     the download link is a signed cross-app URL (ResumeLinkService); this
     panel never touches the file itself. --}}
<div class="ram-modal-overlay" id="applicationsModal">
  <div class="ram-modal" style="max-width:820px;">
    <div class="ram-modal-header">
      <h3><i class="fas fa-file-lines"></i> Applications — <span id="applicationsJobTitle"></span></h3>
      <button class="ram-modal-close" onclick="closeModal('applicationsModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="ram-modal-body" id="applicationsBody">
      <div style="text-align:center;padding:40px 0;color:var(--text-muted);"><span class="ram-spinner" style="border-top-color:var(--primary);border-color:rgba(26,115,232,.25);width:20px;height:20px;"></span></div>
    </div>
  </div>
</div>

<div class="ram-toast-stack" id="ramToastStack"></div>

@push('scripts')
<script>
(function () {
'use strict';

const CSRF = '{{ csrf_token() }}';
const STORE_URL = '{{ route('jobs.store') }}';
const JOBS = @json($jobsJson);
const CAN_EDIT_JOBS = @json(auth()->user()->can('jobs.edit'));

function toast(msg, color, icon) {
  color = color || 'var(--primary)';
  icon  = icon  || 'fas fa-info-circle';
  const el = document.createElement('div');
  el.className = 'ram-toast';
  el.innerHTML = `<i class="${icon}" style="color:${color};font-size:.88rem;flex-shrink:0;"></i><span>${msg}</span>`;
  const stack = document.getElementById('ramToastStack');
  if (!stack) return;
  stack.appendChild(el);
  setTimeout(() => {
    el.style.opacity = '0';
    el.style.transition = 'opacity .3s';
    setTimeout(() => el.remove(), 300);
  }, 3500);
}

async function api(url, method, body) {
  const opts = {
    method,
    headers: {
      'X-CSRF-TOKEN': CSRF,
      'Accept':       'application/json',
      'Content-Type': 'application/json',
    },
  };
  if (body) opts.body = JSON.stringify(body);
  const res  = await fetch(url, opts);
  const data = await res.json();
  if (!res.ok) throw new Error(data.message || (data.errors ? Object.values(data.errors)[0][0] : `Request failed (${res.status})`));
  return data;
}

function setLoading(id, on, label) {
  const btn = document.getElementById(id);
  if (!btn) return;
  if (on) {
    btn.dataset.origHtml = btn.innerHTML;
    btn.innerHTML = `<span class="ram-spinner"></span> ${label || 'Saving…'}`;
    btn.disabled  = true;
  } else {
    btn.innerHTML = btn.dataset.origHtml || btn.innerHTML;
    btn.disabled  = false;
  }
}

window.openModal  = function (id) { document.getElementById(id).classList.add('show'); };
window.closeModal = function (id) { document.getElementById(id).classList.remove('show'); };

document.querySelectorAll('.ram-modal-overlay').forEach(m => {
  m.addEventListener('click', e => { if (e.target === m) m.classList.remove('show'); });
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape')
    document.querySelectorAll('.ram-modal-overlay.show').forEach(m => m.classList.remove('show'));
});

function resetJobForm() {
  document.getElementById('jobId').value = '';
  document.getElementById('jobTitle').value = '';
  document.getElementById('jobDepartment').value = '';
  document.getElementById('jobLocation').value = '';
  document.querySelectorAll('.job-country-cb').forEach(cb => cb.checked = false);
  document.getElementById('jobType').value = 'Full-time';
  document.getElementById('jobExperience').value = '';
  document.getElementById('jobOpenings').value = 1;
  document.getElementById('jobStatus').value = 'active';
  document.getElementById('jobSalary').value = '';
  document.getElementById('jobDeadline').value = '';
  document.getElementById('jobSummary').value = '';
  document.getElementById('jobResponsibilities').value = '';
  document.getElementById('jobRequirements').value = '';
  document.getElementById('jobNiceToHave').value = '';
  document.getElementById('jobSortOrder').value = 0;
}

window.openCreateModal = function () {
  resetJobForm();
  document.getElementById('jobModalTitle').textContent = 'Add Job Posting';
  document.getElementById('jobSaveBtnText').textContent = 'Post Job';
  openModal('jobModal');
};

window.editJob = function (id) {
  const job = JOBS.find(j => j.id === id);
  if (!job) return;
  resetJobForm();
  document.getElementById('jobId').value = job.id;
  document.getElementById('jobTitle').value = job.title;
  document.getElementById('jobDepartment').value = job.department;
  document.getElementById('jobLocation').value = job.location;
  const jobCountries = job.countries || [];
  document.querySelectorAll('.job-country-cb').forEach(cb => cb.checked = jobCountries.includes(cb.value));
  document.getElementById('jobType').value = job.type;
  document.getElementById('jobExperience').value = job.experience_level;
  document.getElementById('jobOpenings').value = job.openings;
  document.getElementById('jobStatus').value = job.status;
  document.getElementById('jobSalary').value = job.salary_range || '';
  document.getElementById('jobDeadline').value = job.application_deadline || '';
  document.getElementById('jobSummary').value = job.summary;
  document.getElementById('jobResponsibilities').value = job.responsibilities;
  document.getElementById('jobRequirements').value = job.requirements;
  document.getElementById('jobNiceToHave').value = job.nice_to_have;
  document.getElementById('jobSortOrder').value = job.sort_order;

  document.getElementById('jobModalTitle').textContent = `Edit — ${job.title}`;
  document.getElementById('jobSaveBtnText').textContent = 'Save Changes';
  openModal('jobModal');
};

window.saveJob = async function () {
  const id = document.getElementById('jobId').value;
  const title = document.getElementById('jobTitle').value.trim();
  const department = document.getElementById('jobDepartment').value.trim();
  const location = document.getElementById('jobLocation').value.trim();
  const experience = document.getElementById('jobExperience').value.trim();
  const summary = document.getElementById('jobSummary').value.trim();
  const responsibilities = document.getElementById('jobResponsibilities').value.trim();
  const requirements = document.getElementById('jobRequirements').value.trim();

  if (!title || !department || !location || !experience || !summary || !responsibilities || !requirements) {
    toast('Please fill in all required fields (marked *).', 'var(--red)', 'fas fa-exclamation-circle');
    return;
  }

  const countries = Array.from(document.querySelectorAll('.job-country-cb:checked')).map(cb => cb.value);

  const payload = {
    title, department, location, countries, experience_level: experience, summary, responsibilities, requirements,
    type: document.getElementById('jobType').value,
    openings: document.getElementById('jobOpenings').value || 1,
    status: document.getElementById('jobStatus').value,
    salary_range: document.getElementById('jobSalary').value.trim() || null,
    application_deadline: document.getElementById('jobDeadline').value || null,
    nice_to_have: document.getElementById('jobNiceToHave').value.trim() || null,
    sort_order: document.getElementById('jobSortOrder').value || 0,
  };

  setLoading('jobSaveBtn', true);
  try {
    if (id) {
      await api(`/jobs/${id}`, 'PATCH', payload);
    } else {
      await api(STORE_URL, 'POST', payload);
    }
    toast(id ? 'Job posting updated.' : 'Job posting created.', 'var(--green)', 'fas fa-check');
    setTimeout(() => window.location.reload(), 600);
  } catch (e) {
    toast(e.message, 'var(--red)', 'fas fa-exclamation-circle');
    setLoading('jobSaveBtn', false);
  }
};

window.toggleJobStatus = async function (id) {
  try {
    const data = await api(`/jobs/${id}/toggle`, 'PATCH');
    const btn = document.getElementById(`job-status-${id}`);
    if (btn) {
      btn.innerHTML = `<span class="status-dot ${data.status}"></span> ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}`;
    }
    toast(`Job marked as ${data.status}.`, 'var(--green)', 'fas fa-check');
  } catch (e) {
    toast(e.message, 'var(--red)', 'fas fa-exclamation-circle');
  }
};

window.filterJobsByCountry = function (value) {
  document.querySelectorAll('#jobsTbody tr[id^="job-row-"]').forEach(row => {
    const rowCountries = (row.dataset.countries || '').split(',').filter(Boolean);
    let show = true;
    if (value === 'global') {
      show = rowCountries.length === 0;
    } else if (value) {
      show = rowCountries.includes(value);
    }
    row.style.display = show ? '' : 'none';
  });
};

function escapeHtml(str) {
  const div = document.createElement('div');
  div.textContent = str == null ? '' : str;
  return div.innerHTML;
}

function renderApplicationCard(app) {
  const resumeBtn = app.resume_url
    ? `<a href="${app.resume_url}" target="_blank" rel="noopener" class="app-resume-btn"><i class="fas fa-download"></i> ${escapeHtml(app.resume_original_name) || 'Resume'}</a>`
    : `<span style="font-size:.78rem;color:var(--text-muted);">No resume on file</span>`;

  const meta = [];
  if (app.phone) meta.push(`<span><i class="fas fa-phone"></i> ${escapeHtml(app.phone)}</span>`);
  if (app.experience) meta.push(`<span><i class="fas fa-layer-group"></i> ${escapeHtml(app.experience)}</span>`);
  if (app.linkedin_url) meta.push(`<span><a href="${app.linkedin_url}" target="_blank" rel="noopener" style="color:inherit;text-decoration:none;"><i class="fab fa-linkedin"></i> LinkedIn</a></span>`);
  if (app.portfolio_url) meta.push(`<span><a href="${app.portfolio_url}" target="_blank" rel="noopener" style="color:inherit;text-decoration:none;"><i class="fas fa-globe"></i> Portfolio</a></span>`);

  const cover = app.cover_letter ? `<div class="app-cover">${escapeHtml(app.cover_letter)}</div>` : '';

  const statuses = ['new', 'reviewed', 'shortlisted', 'rejected', 'hired'];
  const statusControl = CAN_EDIT_JOBS
    ? `<select class="app-status-select" onchange="updateApplicationStatus(${app.id}, this.value)">
        ${statuses.map(s => `<option value="${s}" ${s === app.status ? 'selected' : ''}>${s.charAt(0).toUpperCase() + s.slice(1)}</option>`).join('')}
      </select>`
    : `<span class="app-status-badge">${app.status.charAt(0).toUpperCase() + app.status.slice(1)}</span>`;

  return `
    <div class="app-card">
      <div class="app-card-top">
        <div>
          <div class="app-name">${escapeHtml(app.full_name)}</div>
          <div class="app-email">${escapeHtml(app.email)} · applied ${app.applied_at}</div>
        </div>
        ${statusControl}
      </div>
      <div class="app-meta">${meta.join('')}</div>
      ${cover}
      <div class="app-actions">${resumeBtn}</div>
    </div>
  `;
}

window.viewApplications = async function (jobId, jobTitle) {
  document.getElementById('applicationsJobTitle').textContent = jobTitle;
  const body = document.getElementById('applicationsBody');
  body.innerHTML = '<div style="text-align:center;padding:40px 0;"><span class="ram-spinner" style="border-top-color:var(--primary);border-color:rgba(26,115,232,.25);width:20px;height:20px;"></span></div>';
  openModal('applicationsModal');

  try {
    const res = await fetch(`/jobs/${jobId}/applications`, { headers: { 'Accept': 'application/json' } });
    const data = await res.json();
    if (!data.applications.length) {
      body.innerHTML = '<div class="app-empty"><i class="fas fa-inbox" style="font-size:2rem;opacity:.3;display:block;margin-bottom:10px;"></i>No applications yet for this role.</div>';
      return;
    }
    body.innerHTML = data.applications.map(renderApplicationCard).join('');
  } catch (e) {
    body.innerHTML = `<div class="app-empty">Failed to load applications: ${e.message}</div>`;
  }
};

window.updateApplicationStatus = async function (id, status) {
  try {
    await api(`/applications/${id}/status`, 'PATCH', { status });
    toast('Status updated.', 'var(--green)', 'fas fa-check');
  } catch (e) {
    toast(e.message, 'var(--red)', 'fas fa-exclamation-circle');
  }
};

window.deleteJob = async function (id, title) {
  if (!confirm(`Delete "${title}"? This cannot be undone.`)) return;
  try {
    const data = await api(`/jobs/${id}`, 'DELETE');
    const row = document.getElementById(`job-row-${id}`);
    if (row) row.remove();
    toast(data.message, 'var(--green)', 'fas fa-trash');
  } catch (e) {
    toast(e.message, 'var(--red)', 'fas fa-exclamation-circle');
  }
};

})();
</script>
@endpush

@endsection
