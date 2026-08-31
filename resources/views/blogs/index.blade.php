@extends('layouts.master')
@section('title', 'Blogs — KawachTech Software Solutions')
@section('content')

{{-- ================== STYLES ================== --}}
<style>
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap');

/* ── GLOBAL VARIABLES (matching create-blog page) ── */
:root {
  --primary:      #1a73e8;
  --primary-dark: #1558b0;
  --accent:       #2196f3;
  --success:      #00c896;
  --warning:      #ffb830;
  --danger:       #ff4d6d;
  --bg:           #f0f4fb;
  --white:        #ffffff;
  --card:         #ffffff;
  --border:       #e2e8f0;
  --text:         #1a1a2e;
  --muted:        #6b7a99;
  --radius:       12px;
  --shadow:       0 2px 20px rgba(26,115,232,.08);
  --shadow-md:    0 6px 32px rgba(26,115,232,.13);
}

html[data-theme="dark"] {
  --bg:     #0f172a;
  --white:  #1e293b;
  --card:   #1e293b;
  --text:   #e2e8f0;
  --border: #334155;
  --muted:  #94a3b8;
  --shadow:    0 2px 20px rgba(0,0,0,.3);
  --shadow-md: 0 6px 32px rgba(0,0,0,.4);
}

/* ── SCOPE ── */
#blogList {
  font-family: 'Open Sans', sans-serif;
  color: var(--text);
  background: var(--bg);
  min-height: 100vh;
  transition: background .3s, color .3s;
}

/* ── WRAP ── */
#blogList .bl-wrap {
  max-width: 1440px;
  margin: 0 auto;
  padding: 28px 22px 70px;
}

/* ── TOPBAR ── */
#blogList .bl-topbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
  flex-wrap: wrap;
  margin-bottom: 24px;
}
#blogList .bl-breadcrumb {
  font-size: .76rem;
  color: var(--muted);
  display: flex;
  align-items: center;
  gap: 6px;
  margin-bottom: 4px;
}
#blogList .bl-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 600; }
#blogList .bl-breadcrumb a:hover { text-decoration: underline; }
#blogList .bl-title {
  font-family: 'Nunito', sans-serif;
  font-weight: 900;
  font-size: 1.55rem;
  color: var(--text);
  line-height: 1.2;
}
#blogList .bl-topbar-actions {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  align-items: center;
}

/* ── BUTTONS ── */
#blogList .btn-bl {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 9px 18px;
  border-radius: 8px;
  font-size: .84rem;
  font-weight: 700;
  cursor: pointer;
  border: none;
  transition: all .2s;
  font-family: 'Open Sans', sans-serif;
  white-space: nowrap;
  text-decoration: none;
}
#blogList .btn-bl:hover { transform: translateY(-1px); }
#blogList .btn-primary { background: var(--primary); color: #fff; }
#blogList .btn-primary:hover { background: var(--primary-dark); box-shadow: 0 4px 14px rgba(26,115,232,.35); color: #fff; }
#blogList .btn-outline { background: var(--card); color: var(--text); border: 1.5px solid var(--border); }
#blogList .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
#blogList .btn-success { background: var(--success); color: #fff; }
#blogList .btn-success:hover { background: #00a87c; box-shadow: 0 4px 14px rgba(0,200,150,.3); color: #fff; }
#blogList .btn-danger { background: var(--danger); color: #fff; }
#blogList .btn-danger:hover { background: #e03050; box-shadow: 0 4px 14px rgba(255,77,109,.3); color: #fff; }
#blogList .btn-warning { background: var(--warning); color: #fff; }
#blogList .btn-sm { padding: 6px 12px; font-size: .77rem; }
#blogList .btn-xs { padding: 4px 10px; font-size: .72rem; border-radius: 6px; }
#blogList .btn-icon {
  width: 32px; height: 32px; padding: 0;
  display: inline-flex; align-items: center; justify-content: center;
  border-radius: 8px;
  border: 1.5px solid var(--border);
  background: var(--card);
  color: var(--muted);
  cursor: pointer;
  font-size: .8rem;
  transition: all .18s;
  text-decoration: none;
}
#blogList .btn-icon:hover { border-color: var(--primary); color: var(--primary); }
#blogList .btn-icon.danger:hover { border-color: var(--danger); color: var(--danger); }
#blogList .btn-icon.success-h:hover { border-color: var(--success); color: var(--success); }

/* ── STAT OVERVIEW CARDS ── */
#blogList .stat-row {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 14px;
  margin-bottom: 22px;
}
@media (max-width: 1200px) { #blogList .stat-row { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 640px)  { #blogList .stat-row { grid-template-columns: repeat(2, 1fr); } }

#blogList .stat-ov-card {
  background: var(--card);
  border-radius: var(--radius);
  padding: 16px 18px;
  border: 1px solid var(--border);
  box-shadow: var(--shadow);
  display: flex;
  align-items: center;
  gap: 14px;
  transition: transform .2s, box-shadow .2s, background .3s;
  cursor: default;
}
#blogList .stat-ov-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
#blogList .stat-ov-icon {
  width: 46px; height: 46px;
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.15rem;
  flex-shrink: 0;
}
#blogList .stat-ov-body { min-width: 0; }
#blogList .stat-ov-val {
  font-family: 'Nunito', sans-serif;
  font-weight: 900;
  font-size: 1.45rem;
  color: var(--text);
  line-height: 1;
  margin-bottom: 2px;
}
#blogList .stat-ov-lbl {
  font-size: .72rem;
  color: var(--muted);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: .4px;
}
#blogList .stat-ov-delta {
  font-size: .7rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 3px;
  margin-top: 2px;
}

/* ── FILTER / SEARCH BAR ── */
#blogList .filter-bar {
  background: var(--card);
  border-radius: var(--radius);
  border: 1px solid var(--border);
  box-shadow: var(--shadow);
  padding: 14px 18px;
  margin-bottom: 18px;
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
  transition: background .3s;
}
#blogList .search-wrap {
  position: relative;
  flex: 1;
  min-width: 200px;
  max-width: 360px;
}
#blogList .search-wrap i {
  position: absolute; left: 12px; top: 50%;
  transform: translateY(-50%);
  color: var(--muted); font-size: .82rem;
}
#blogList .search-wrap input {
  width: 100%;
  border: 1.5px solid var(--border);
  border-radius: 8px;
  padding: 8px 36px 8px 34px;
  font-size: .84rem;
  color: var(--text);
  background: var(--bg);
  outline: none;
  font-family: 'Open Sans', sans-serif;
  transition: border-color .2s, box-shadow .2s;
}
#blogList .search-wrap input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,115,232,.1); }
#blogList .search-wrap .search-clear {
  position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
  cursor: pointer; color: var(--muted); font-size: .72rem; display: none;
}
#blogList .filter-select {
  border: 1.5px solid var(--border);
  border-radius: 8px;
  padding: 8px 12px;
  font-size: .82rem;
  color: var(--text);
  background: var(--bg);
  outline: none;
  cursor: pointer;
  font-family: 'Open Sans', sans-serif;
  transition: border-color .2s;
  min-width: 130px;
}
#blogList .filter-select:focus { border-color: var(--primary); }

/* Status filter tabs */
#blogList .status-tabs {
  display: flex;
  gap: 4px;
  background: var(--bg);
  border-radius: 8px;
  padding: 3px;
  border: 1px solid var(--border);
}
#blogList .status-tab {
  padding: 6px 14px;
  border-radius: 6px;
  font-size: .78rem;
  font-weight: 700;
  cursor: pointer;
  color: var(--muted);
  border: none;
  background: transparent;
  transition: all .18s;
  white-space: nowrap;
  font-family: 'Open Sans', sans-serif;
}
#blogList .status-tab.active {
  background: var(--card);
  color: var(--primary);
  box-shadow: 0 1px 6px rgba(26,115,232,.12);
}
#blogList .status-tab .tab-count {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 18px; height: 18px;
  border-radius: 9px;
  font-size: .62rem;
  font-weight: 800;
  margin-left: 5px;
  padding: 0 5px;
}

/* View toggle */
#blogList .view-toggle {
  display: flex;
  gap: 4px;
  border: 1.5px solid var(--border);
  border-radius: 8px;
  overflow: hidden;
  background: var(--bg);
}
#blogList .vt-btn {
  width: 34px; height: 34px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  color: var(--muted);
  font-size: .82rem;
  border: none;
  background: transparent;
  transition: all .15s;
}
#blogList .vt-btn.active { background: var(--primary); color: #fff; }
#blogList .vt-btn:not(.active):hover { color: var(--primary); }

/* ── BULK ACTION BAR ── */
#blogList .bulk-bar {
  background: linear-gradient(135deg, #e8f1fd, #dbeeff);
  border: 1.5px solid rgba(26,115,232,.2);
  border-radius: var(--radius);
  padding: 12px 18px;
  display: none;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
  margin-bottom: 14px;
}
#blogList .bulk-bar.show { display: flex; }
#blogList .bulk-count {
  font-family: 'Nunito', sans-serif;
  font-weight: 800;
  font-size: .88rem;
  color: var(--primary);
}

/* ── TABLE VIEW ── */
#blogList .bl-table-wrap {
  background: var(--card);
  border-radius: var(--radius);
  border: 1px solid var(--border);
  box-shadow: var(--shadow);
  overflow: hidden;
  transition: background .3s;
}
#blogList .bl-table {
  width: 100%;
  border-collapse: collapse;
  font-size: .83rem;
}
#blogList .bl-table thead th {
  background: #fafbfe;
  padding: 12px 16px;
  font-family: 'Nunito', sans-serif;
  font-weight: 800;
  font-size: .75rem;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: .5px;
  border-bottom: 1px solid var(--border);
  white-space: nowrap;
  cursor: pointer;
  user-select: none;
  transition: color .15s;
}
html[data-theme="dark"] #blogList .bl-table thead th { background: #0f172a; }
#blogList .bl-table thead th:hover { color: var(--primary); }
#blogList .bl-table thead th i.sort-icon { margin-left: 4px; font-size: .65rem; opacity: .5; }
#blogList .bl-table thead th.sorted i.sort-icon { opacity: 1; color: var(--primary); }
#blogList .bl-table tbody tr {
  border-bottom: 1px solid var(--border);
  transition: background .15s;
}
#blogList .bl-table tbody tr:last-child { border-bottom: none; }
#blogList .bl-table tbody tr:hover { background: rgba(26,115,232,.03); }
html[data-theme="dark"] #blogList .bl-table tbody tr:hover { background: rgba(255,255,255,.03); }
#blogList .bl-table td { padding: 14px 16px; vertical-align: middle; color: var(--text); }

/* post thumb cell */
#blogList .post-thumb-cell { display: flex; align-items: center; gap: 12px; }
#blogList .post-thumb {
  width: 60px; height: 46px;
  border-radius: 8px;
  object-fit: cover;
  flex-shrink: 0;
  background: linear-gradient(135deg, #1a3a6e, #2196f3);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.1rem; color: rgba(255,255,255,.35);
}
#blogList .post-thumb img { width:100%; height:100%; object-fit:cover; border-radius:8px; }
#blogList .post-title-link {
  font-family: 'Nunito', sans-serif;
  font-weight: 800;
  font-size: .9rem;
  color: var(--text);
  text-decoration: none;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-height: 1.35;
  transition: color .15s;
}
#blogList .post-title-link:hover { color: var(--primary); }
#blogList .post-cat-tag {
  display: inline-flex; align-items: center; gap: 4px;
  font-size: .68rem; font-weight: 700;
  color: var(--primary); background: #e8f1fd;
  padding: 2px 8px; border-radius: 10px; margin-top: 3px;
}
html[data-theme="dark"] #blogList .post-cat-tag { background: rgba(26,115,232,.2); }

/* stat cells */
#blogList .stat-cell {
  display: flex; align-items: center; gap: 5px;
  font-size: .8rem; font-weight: 600;
  color: var(--text);
  white-space: nowrap;
}
#blogList .stat-cell i { font-size: .72rem; color: var(--muted); }

/* mini sparkline bar */
#blogList .mini-bar {
  width: 60px; height: 5px;
  background: var(--border);
  border-radius: 3px; overflow: hidden;
}
#blogList .mini-bar-fill { height: 100%; border-radius: 3px; }

/* status badges */
#blogList .status-badge {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 4px 10px; border-radius: 20px;
  font-size: .7rem; font-weight: 800;
  text-transform: uppercase; letter-spacing: .4px;
}
#blogList .badge-published { background: #d4f5ec; color: #00a87c; }
#blogList .badge-draft      { background: #eef2f9; color: var(--muted); }
#blogList .badge-pending    { background: #fff4d6; color: #b8860b; }
#blogList .badge-scheduled  { background: #e8f1fd; color: var(--primary); }
#blogList .badge-trash      { background: #ffe2e8; color: var(--danger); }
html[data-theme="dark"] #blogList .badge-published { background: rgba(0,200,150,.15); }
html[data-theme="dark"] #blogList .badge-draft      { background: rgba(100,116,139,.15); }
html[data-theme="dark"] #blogList .badge-pending    { background: rgba(255,184,48,.15); }
html[data-theme="dark"] #blogList .badge-scheduled  { background: rgba(26,115,232,.15); }

/* SEO score badge */
#blogList .seo-badge {
  display: inline-flex; align-items: center; justify-content: center;
  width: 34px; height: 34px; border-radius: 50%;
  font-family: 'Nunito', sans-serif; font-weight: 900; font-size: .75rem; color: #fff;
}

/* actions cell */
#blogList .action-cell { display: flex; gap: 5px; align-items: center; }

/* ── GRID VIEW ── */
#blogList .bl-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 18px;
}
@media (max-width: 480px) { #blogList .bl-grid { grid-template-columns: 1fr; } }

#blogList .blog-card {
  background: var(--card);
  border-radius: var(--radius);
  border: 1px solid var(--border);
  box-shadow: var(--shadow);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition: transform .2s, box-shadow .2s, background .3s;
}
#blogList .blog-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }

#blogList .blog-card-thumb {
  height: 175px;
  background: linear-gradient(135deg, #1a3a6e, #2196f3);
  position: relative;
  overflow: hidden;
  flex-shrink: 0;
}
#blogList .blog-card-thumb img { width:100%; height:100%; object-fit:cover; }
#blogList .blog-card-thumb .thumb-pattern {
  position: absolute; inset: 0;
  background: repeating-linear-gradient(45deg, rgba(255,255,255,.025) 0px, rgba(255,255,255,.025) 1px, transparent 1px, transparent 20px);
}
#blogList .blog-card-thumb .thumb-icon {
  position: absolute; inset:0;
  display: flex; align-items: center; justify-content: center;
  font-size: 3rem; color: rgba(255,255,255,.18);
}
#blogList .card-thumb-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,.55) 0%, transparent 55%);
}
#blogList .card-thumb-status { position: absolute; top: 10px; left: 10px; }
#blogList .card-thumb-seo    { position: absolute; top: 10px; right: 10px; }
#blogList .card-thumb-select { position: absolute; top: 40px; left: 12px; }

#blogList .blog-card-body { padding: 18px; flex: 1; display: flex; flex-direction: column; }
#blogList .card-cat {
  font-size: .68rem; font-weight: 800;
  color: var(--primary); text-transform: uppercase; letter-spacing: .5px;
  margin-bottom: 6px; display: flex; align-items: center; gap: 5px;
}
#blogList .card-title {
  font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1rem;
  color: var(--text); text-decoration: none;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
  line-height: 1.35; margin-bottom: 10px; transition: color .15s;
}
#blogList .card-title:hover { color: var(--primary); }
#blogList .card-excerpt {
  font-size: .78rem; color: var(--muted); line-height: 1.6;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
  margin-bottom: 14px; flex: 1;
}

/* card stats row */
#blogList .card-stats {
  display: flex; gap: 14px; flex-wrap: wrap;
  padding: 10px 0;
  border-top: 1px solid var(--border);
  border-bottom: 1px solid var(--border);
  margin-bottom: 14px;
}
#blogList .card-stat {
  display: flex; align-items: center; gap: 5px;
  font-size: .75rem; font-weight: 600; color: var(--muted);
}
#blogList .card-stat i { font-size: .7rem; }
#blogList .card-stat strong { color: var(--text); }

/* card author row */
#blogList .card-meta {
  display: flex; align-items: center; gap: 8px; margin-bottom: 14px;
}
#blogList .author-avatar-sm {
  width: 28px; height: 28px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-family: 'Nunito', sans-serif; font-weight: 900; font-size: .65rem; color: #fff;
  flex-shrink: 0;
}
#blogList .card-meta-text { flex: 1; min-width: 0; }
#blogList .card-meta-text .author-name { font-size: .78rem; font-weight: 700; color: var(--text); }
#blogList .card-meta-text .post-date  { font-size: .7rem; color: var(--muted); }

/* card actions */
#blogList .card-actions {
  display: flex; gap: 6px; flex-wrap: wrap;
}

/* ── MINI CHART SPARKLINES ── */
#blogList .sparkline-cell canvas { display: block; }

/* ── PAGINATION ── */
#blogList .pagination-wrap {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: 22px;
}
#blogList .page-info { font-size: .8rem; color: var(--muted); }
#blogList .page-btns { display: flex; gap: 5px; }
#blogList .page-btn {
  width: 36px; height: 36px;
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: .82rem; font-weight: 700;
  border: 1.5px solid var(--border);
  background: var(--card); color: var(--muted);
  cursor: pointer; transition: all .18s; text-decoration: none;
}
#blogList .page-btn:hover, #blogList .page-btn.active {
  background: var(--primary); border-color: var(--primary); color: #fff;
}

/* ── MODALS ── */
#blogList .bl-modal-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.5); z-index: 9000;
  align-items: center; justify-content: center;
  backdrop-filter: blur(3px);
}
#blogList .bl-modal-overlay.show { 
    display: flex; 
}
#blogList .bl-modal {
  background: var(--card);
  border-radius: 16px;
  padding: 0;
  max-width: 600px; width: 95%;
  box-shadow: 0 24px 64px rgba(0,0,0,.25);
  animation: blModalPop .2s ease;
  max-height: 92vh; 
  overflow-y: auto;
  position: relative;
  scrollbar-width: none;
  -ms-overflow-style: none;
}
#blogList .bl-modal::-webkit-scrollbar {
  display: none;
}
#blogList .bl-modal.modal-sm { 
    max-width: 420px; 
}
@keyframes blModalPop {
  from { 
    opacity:0; 
    transform: scale(.93); 
}
  to   { 
    opacity:1; 
    transform: scale(1); 
}
}
#blogList .modal-header {
  padding: 20px 24px 16px;
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
}
#blogList .modal-header h3 {
  font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.1rem; color: var(--text); margin: 0;
  display: flex; align-items: center; gap: 8px;
}
#blogList .modal-header h3 i { color: var(--primary); }
#blogList .modal-close {
  width: 30px; height: 30px; border-radius: 50%;
  border: none; background: var(--bg); color: var(--muted);
  font-size: .8rem; cursor: pointer; display: flex; align-items: center; justify-content: center;
  transition: background .15s;
}
#blogList .modal-close:hover { background: var(--border); color: var(--text); }
#blogList .modal-body { padding: 22px 24px; }
#blogList .modal-footer {
  padding: 16px 24px;
  border-top: 1px solid var(--border);
  display: flex; gap: 8px; justify-content: flex-end;
}

/* Share modal */
#blogList .share-platform-grid {
  display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 18px;
}
#blogList .share-btn-plat {
  display: flex; flex-direction: column; align-items: center; gap: 6px;
  padding: 14px 8px; border-radius: 10px;
  border: 1.5px solid var(--border); background: var(--bg);
  cursor: pointer; font-size: .72rem; font-weight: 700; color: var(--text);
  transition: all .18s;
}
#blogList .share-btn-plat:hover { transform: translateY(-2px); box-shadow: 0 4px 14px rgba(0,0,0,.1); }
#blogList .share-btn-plat i { font-size: 1.4rem; }
#blogList .share-btn-plat.fb:hover { border-color: #1877f2; color: #1877f2; }
#blogList .share-btn-plat.tw:hover { border-color: #1da1f2; color: #1da1f2; }
#blogList .share-btn-plat.li:hover { border-color: #0077b5; color: #0077b5; }
#blogList .share-btn-plat.wa:hover { border-color: #25d366; color: #25d366; }
#blogList .share-btn-plat.tg:hover { border-color: #0088cc; color: #0088cc; }
#blogList .share-btn-plat.em:hover { border-color: var(--primary); color: var(--primary); }
#blogList .share-btn-plat.rd:hover { border-color: #ff4500; color: #ff4500; }
#blogList .share-btn-plat.cp:hover { border-color: var(--success); color: var(--success); }

#blogList .share-url-row {
  display: flex; gap: 8px; align-items: center;
  background: var(--bg); border: 1.5px solid var(--border); border-radius: 8px; padding: 8px 12px;
}
#blogList .share-url-row input {
  flex: 1; border: none; background: transparent;
  font-size: .82rem; color: var(--muted); outline: none;
  font-family: 'Open Sans', sans-serif;
}

/* Stats modal */
#blogList .stats-chart-wrap { height: 200px; margin-bottom: 18px; }
#blogList .stats-grid {
  display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; margin-bottom: 18px;
}
#blogList .stats-mini-card {
  background: var(--bg); border: 1px solid var(--border); border-radius: 10px;
  padding: 14px 12px; text-align: center;
}
#blogList .stats-mini-val {
  font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.35rem; color: var(--text); line-height: 1;
}
#blogList .stats-mini-lbl { font-size: .7rem; color: var(--muted); margin-top: 3px; font-weight: 600; }

/* Delete modal */
#blogList .delete-warn-icon {
  width: 64px; height: 64px; border-radius: 50%;
  background: #ffe2e8; display: flex; align-items: center; justify-content: center;
  font-size: 1.6rem; color: var(--danger); margin: 0 auto 16px;
}

/* ── TOAST ── */
#blogList .bl-toast-stack {
  position: fixed; bottom: 24px; right: 24px; z-index: 9999;
  display: flex; flex-direction: column; gap: 8px; pointer-events: none;
}
#blogList .bl-toast {
  background: var(--card); border: 1px solid var(--border);
  border-radius: 10px; padding: 12px 16px;
  display: flex; align-items: center; gap: 10px;
  box-shadow: 0 6px 24px rgba(0,0,0,.15);
  font-size: .82rem; color: var(--text);
  pointer-events: all;
  animation: toastSlide .22s ease;
  max-width: 300px;
}
@keyframes toastSlide { from { opacity:0; transform:translateX(20px); } to { opacity:1; transform:none; } }
#blogList .bl-toast i { font-size: .95rem; flex-shrink: 0; }

/* ── Dark theme input/select overrides ── */
html[data-theme="dark"] #blogList .filter-select,
html[data-theme="dark"] #blogList .search-wrap input,
html[data-theme="dark"] #blogList .share-url-row input {
  color: var(--text);
}

/* ── Checkbox style ── */
#blogList input[type="checkbox"] {
  width: 16px; height: 16px; cursor: pointer; accent-color: var(--primary);
}

/* ── Responsive table ── */
@media (max-width: 900px) {
  #blogList .hide-mobile { display: none !important; }
}
@media (max-width: 640px) {
  #blogList .bl-topbar { flex-direction: column; align-items: flex-start; }
  #blogList .filter-bar { gap: 8px; }
  #blogList .share-platform-grid { grid-template-columns: repeat(3,1fr); }
  #blogList .stats-grid { grid-template-columns: repeat(2,1fr); }
}
</style>

{{-- ================= MARKUP ================== --}}
<div id="blogList">
<div class="bl-wrap">

  {{-- ── TOPBAR ── --}}
  <div class="bl-topbar">
    <div>
      <div class="bl-breadcrumb">
        <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
        <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
        <span>Blog Posts</span>
      </div>
      <div class="bl-title">📝 Blog Posts</div>
    </div>
    <div class="bl-topbar-actions">
      @can('blog.create')
        <button class="btn-bl btn-outline" id="btnExport">
          <i class="fas fa-download"></i> Export
        </button>
        <a href="{{ route('blogs.create') }}" onclick="clearDraftSession()" class="btn-bl btn-primary">
          <i class="fas fa-plus"></i> New Post
        </a>
      @endcan
    </div>
  </div>

  {{-- ── OVERVIEW STAT CARDS ── --}}
  <div class="stat-row">
    <div class="stat-ov-card">
      <div class="stat-ov-icon" style="background:#e8f1fd;color:var(--primary);"><i class="fas fa-newspaper"></i></div>
      <div class="stat-ov-body">
        <div class="stat-ov-val">{{ $totalPosts }}</div>
        <div class="stat-ov-lbl">Total Posts</div>
        <div class="stat-ov-delta" style="color:var(--success);"><i class="fas fa-arrow-up"></i> 8 this week</div>
      </div>
    </div>
    <div class="stat-ov-card">
      <div class="stat-ov-icon" style="background:#d4f5ec;color:var(--success);"><i class="fas fa-check-circle"></i></div>
      <div class="stat-ov-body">
        <div class="stat-ov-val"> {{ $counts['published'] ?? $counts->published ?? 0 }} </div>
        <div class="stat-ov-lbl">Published</div>
        <div class="stat-ov-delta" style="color:var(--success);"><i class="fas fa-arrow-up"></i> 79%</div>
      </div>
    </div>
    <div class="stat-ov-card">
      <div class="stat-ov-icon" style="background:#eef2f9;color:var(--muted);"><i class="fas fa-file-alt"></i></div>
      <div class="stat-ov-body">
        <div class="stat-ov-val">{{ $counts['draft'] ?? $counts->draft ?? 0 }}</div>
        <div class="stat-ov-lbl">Drafts</div>
        <div class="stat-ov-delta" style="color:var(--muted);"><i class="fas fa-minus"></i> No change</div>
      </div>
    </div>
    <div class="stat-ov-card">
      <div class="stat-ov-icon" style="background:#fff0f3;color:var(--danger);"><i class="fas fa-eye"></i></div>
      <div class="stat-ov-body">
        <div class="stat-ov-val"> {{ $counts['views'] ?? $counts->views ?? 0 }} </div>
        <div class="stat-ov-lbl">Total Views</div>
        <div class="stat-ov-delta" style="color:var(--success);"><i class="fas fa-arrow-up"></i> +12.4%</div>
      </div>
    </div>
    <div class="stat-ov-card">
      <div class="stat-ov-icon" style="background:#fff4d6;color:var(--warning);"><i class="fas fa-heart"></i></div>
      <div class="stat-ov-body">
        <div class="stat-ov-val">{{ number_format($counts['likes'] ?? 0) }}</div>
        <div class="stat-ov-lbl">Total Likes</div>
      </div>
    </div>
    <div class="stat-ov-card">
      <div class="stat-ov-icon" style="background:#f3e8ff;color:#9333ea;"><i class="fas fa-comment-dots"></i></div>
      <div class="stat-ov-body">
        <div class="stat-ov-val">{{ number_format($counts['comments'] ?? 0) }}</div>
        <div class="stat-ov-lbl">Comments</div>
        @if(($counts['pendingComments'] ?? 0) > 0)
          <div class="stat-ov-delta" style="color:var(--warning);"><i class="fas fa-hourglass-half"></i> {{ $counts['pendingComments'] }} pending</div>
        @endif
      </div>
    </div>
  </div>

  {{-- ── FILTER BAR ── --}}
  <div class="filter-bar">
    {{-- Status tabs --}}
    <div class="status-tabs">
      <button class="status-tab active" data-status="all">
        All <span class="tab-count" style="background:#e8f1fd;color:var(--primary);">{{ $counts['all'] ?? $counts->all_count ?? 0 }}</span>
      </button>
      <button class="status-tab" data-status="published">
        Published <span class="tab-count" style="background:#d4f5ec;color:var(--success);">{{ $counts['published'] ?? $counts->published ?? 0 }}</span>
      </button>
      <button class="status-tab" data-status="draft">
        Draft <span class="tab-count" style="background:#eef2f9;color:var(--muted);">{{ $counts['draft'] ?? $counts->draft ?? 0 }}</span>
      </button>
      <button class="status-tab" data-status="scheduled">
        Scheduled <span class="tab-count" style="background:#e8f1fd;color:var(--primary);">{{ $counts['scheduled'] ?? $counts->scheduled ?? 0 }}</span>
      </button>
    </div>

    {{-- Search --}}
    <div class="search-wrap">
      <i class="fas fa-search"></i>
      <input type="text" id="blSearch" placeholder="Search posts, authors, tags…" autocomplete="off"/>
      <i class="fas fa-times search-clear" id="searchClear"></i>
    </div>

    {{-- Filters --}}
    <select class="filter-select" id="filterCategory">
      <option value="">All Categories</option>
      <option value="tech">Technology</option>
      <option value="ai">AI & ML</option>
      <option value="dev">Web Dev</option>
      <option value="cloud">Cloud & DevOps</option>
      <option value="biz">Business</option>
    </select>

    <select class="filter-select" id="filterSort">
      <option value="newest">Newest First</option>
      <option value="oldest">Oldest First</option>
      <option value="views">Most Views</option>
      <option value="likes">Most Likes</option>
      <option value="comments">Most Comments</option>
      <option value="seo">SEO Score</option>
    </select>

    <select class="filter-select" id="filterDate">
      <option value="">Any Date</option>
      <option value="today">Today</option>
      <option value="week">This Week</option>
      <option value="month">This Month</option>
      <option value="year">This Year</option>
    </select>

    <div class="view-toggle ms-auto">
      <button class="vt-btn active" id="viewTable" title="Table view"><i class="fas fa-list"></i></button>
      <button class="vt-btn" id="viewGrid" title="Grid view"><i class="fas fa-th-large"></i></button>
    </div>
  </div>

  {{-- ── BULK ACTION BAR ── --}}
  @hasanyrole('super-admin|admin')
    <div class="bulk-bar" id="bulkBar">
      <span class="bulk-count" id="bulkCount">0 posts selected</span>
      <button class="btn-bl btn-outline btn-sm" id="bulkPublish"><i class="fas fa-check-circle"></i> Publish</button>
      <button class="btn-bl btn-outline btn-sm" id="bulkDraft"><i class="fas fa-file-alt"></i> Set Draft</button>
      <button class="btn-bl btn-outline btn-sm" id="bulkExport"><i class="fas fa-download"></i> Export</button>
      <button class="btn-bl btn-danger btn-sm" id="bulkDelete"><i class="fas fa-trash"></i> Delete</button>
      <button class="btn-bl btn-outline btn-sm ms-auto" id="bulkClear"><i class="fas fa-times"></i> Clear</button>
    </div>
  @endhasanyrole

  {{-- ════════════════════   TABLE VIEW   ════════════════════ --}}
  <div id="tableView">
    <div class="bl-table-wrap">
      <table class="bl-table">
        <thead>
          <tr>
            <th style="width:36px;"><input type="checkbox" id="selectAll"/></th>
            <th class="sorted" data-col="title">Post <i class="fas fa-sort sort-icon"></i></th>
            <th class="hide-mobile" data-col="status">Status <i class="fas fa-sort sort-icon"></i></th>
            <th class="hide-mobile" data-col="views"><i class="fas fa-eye"></i> Views <i class="fas fa-sort sort-icon"></i></th>
            <th class="hide-mobile" data-col="likes"><i class="fas fa-heart"></i> Likes <i class="fas fa-sort sort-icon"></i></th>
            <th class="hide-mobile" data-col="comments"><i class="fas fa-comment"></i> Cmts <i class="fas fa-sort sort-icon"></i></th>
            <th class="hide-mobile" data-col="seo">SEO <i class="fas fa-sort sort-icon"></i></th>
            <th class="hide-mobile" data-col="date">Date <i class="fas fa-sort sort-icon"></i></th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="blogTableBody">

          @foreach($posts ?? $demoPosts as $post)
          <tr data-status="{{ $post['status'] }}" data-id="{{ $post['id'] }}">
            <td><input type="checkbox" class="row-check" value="{{ $post['id'] }}"/></td>
            <td>
              <div class="post-thumb-cell">
                <div class="post-thumb">
                    @if(!empty($post->featured_image))
                        <img src="{{ asset($post->featured_image) }}" alt="Blog Image">
                    @else
                        <i class="fas fa-newspaper" style="position:relative;z-index:1;"></i>
                    @endif
                </div>
                <div>
                    <a href="#" class="post-title-link">{{ $post->title }}</a>
                    <div>
                        <span class="post-cat-tag">
                            <i class="fas fa-folder"></i>
                            {{ $post->category->name ?? 'Uncategorized' }}
                        </span>
                    </div>
                </div>
              </div>
            </td>
            <td class="hide-mobile">
               @php
                    $badgeClass = match($post->status) {
                        'published' => 'badge-published',
                        'draft'     => 'badge-draft',
                        'scheduled' => 'badge-scheduled',
                        default     => 'badge-draft'
                    };
                @endphp
                <span class="status-badge {{ $badgeClass }}">{{ ucfirst($post->status) }}</span>
            </td>
            <td class="hide-mobile">
              <div class="stat-cell">
                <i class="fas fa-eye"></i>
                {{ number_format($post->views ?? 0) }}
              </div>
            </td>
            <td class="hide-mobile">
              <div class="stat-cell">
                <i class="fas fa-heart" style="color:#ff4d6d;"></i>
                {{ number_format($post->likes_count ?? 0) }}
              </div>
            </td>
            <td class="hide-mobile">
              <div class="stat-cell">
                <i class="fas fa-comment-dots" style="color:#9333ea;"></i>
                {{ number_format($post->approved_comments_count ?? 0) }}
                @if(($post->pending_comments_count ?? 0) > 0)
                  <span class="status-badge badge-pending" style="font-size:.6rem;padding:2px 6px;margin-left:4px;" title="Pending moderation">{{ $post->pending_comments_count }} pending</span>
                @endif
              </div>
            </td>
            <td class="hide-mobile">
              @php
                $seo = rand(60, 95);
                $seoColor = $seo >= 80 ? '#00c896' : ($seo >= 60 ? '#ffb830' : '#ff4d6d');
              @endphp
              <div style="display:flex;align-items:center;gap:8px;">
                <div class="seo-badge" style="background:{{ $seoColor }};">{{ $seo }}</div>
                <div class="mini-bar" style="width:50px;">
                  <div class="mini-bar-fill" style="width:{{ $seo }}%;background:{{ $seoColor }};"></div>
                </div>
              </div>
            </td>
            <td class="hide-mobile" style="font-size:.78rem;color:var(--muted);white-space:nowrap;">
              {{ $post->created_at->format('M d, Y') }}
            </td>
            <td>
              <div class="action-cell">
                <a href="/blog/{{ $post['slug'] }}" target="_blank" class="btn-icon success-h" title="View post">
                  <i class="fas fa-eye"></i>
                </a>
                @can('blog.edit')
                  <a href="{{ route('blogs.edit', $post->id) }}" class="btn-icon" title="Edit post">
                      <i class="fas fa-pen"></i>
                  </a>
                @endcan
                <button class="btn-icon" title="Share post" onclick="openShareModal('{{ $post->title }}', '/blog/{{ $post['slug'] }}')">
                  <i class="fas fa-share-alt"></i>
                </button>
                <button class="btn-icon" title="View stats" onclick="openStatsModal( '{{ addslashes($post->title) }}', {{ $post->views ?? 0 }}, {{ $post->likes_count ?? 0 }}, {{ $post->approved_comments_count ?? 0 }} )">
                  <i class="fas fa-chart-line"></i>
                </button>
                @can('blog.delete')
                  <button class="btn-icon danger" title="Delete post" onclick="openDeleteModal('{{ encrypt($post->id) }}', '{{ addslashes($post->title) }}')">
                    <i class="fas fa-trash"></i>
                  </button>
                @endcan
              </div>
            </td>
          </tr>
          @endforeach

        </tbody>
      </table>
    </div>
  </div>

  {{-- ════════════════════   GRID VIEW   ════════════════════ --}}
  <div id="gridView" style="display:none;">
    <div class="bl-grid">

      @foreach($posts as $post)
      @php
        $seo = rand(60, 95);
        $seoColor = $seo >= 80 ? '#00c896' : ($seo >= 60 ? '#ffb830' : '#ff4d6d');
        $badgeClass = match($post['status']) {
          'published' => 'badge-published',
          'draft'     => 'badge-draft',
          'pending'   => 'badge-pending',
          'scheduled' => 'badge-scheduled',
          default     => 'badge-draft'
        };
      @endphp
      <div class="blog-card" data-status="{{ $post['status'] }}" data-id="{{ $post['id'] }}">
        <div class="blog-card-thumb" style="background:linear-gradient({{ $post['img_gradient'] }});">
          <div class="thumb-pattern"></div>
          <div class="thumb-icon"><i class="fas fa-newspaper"></i></div>
          <div class="card-thumb-overlay">
            @if(!empty($post->featured_image))
                <img src="{{ asset($post->featured_image) }}" alt="Blog Image">
            @else
                <i class="fas fa-newspaper" style="position:relative;z-index:1;"></i>
            @endif
          </div>
          <div class="card-thumb-status">
            <span class="status-badge {{ $badgeClass }}" style="font-size:.62rem;padding:3px 8px;">{{ ucfirst($post['status']) }}</span>
          </div>
          <div class="card-thumb-seo">
            <div class="seo-badge" style="background:{{ $seoColor }};width:30px;height:30px;font-size:.7rem;">{{ $seo }}</div>
          </div>
          <div class="card-thumb-select">
            <input type="checkbox" class="row-check" value="{{ $post['id'] }}" style="width:16px;height:16px;"/>
          </div>
        </div>

        <div class="blog-card-body">
          <div class="card-cat"><i class="fas fa-folder" style="font-size:.6rem;"></i> {{ $post->category->name ?? 'General' }}</div>
          <a href="#" class="card-title">{{ $post->title }}</a>
          <div class="card-excerpt">
            A comprehensive guide covering all aspects of this topic with expert insights, real-world examples, and actionable takeaways for practitioners.
          </div>

          <div class="card-stats">
            <div class="card-stat">
              <i class="fas fa-eye" style="color:var(--primary);"></i>
              <strong>{{ number_format($post->views ?? 0) }}</strong> views
            </div>
            <div class="card-stat">
              <i class="fas fa-heart" style="color:var(--danger);"></i>
              <strong>{{ number_format($post->likes_count ?? 0) }}</strong> likes
            </div>
            <div class="card-stat">
              <i class="fas fa-comment-dots" style="color:#9333ea;"></i>
              <strong>{{ number_format($post->approved_comments_count ?? 0) }}</strong> cmts
              @if(($post->pending_comments_count ?? 0) > 0)
                <span style="color:var(--warning);font-weight:700;">({{ $post->pending_comments_count }} pending)</span>
              @endif
            </div>
          </div>

          <div class="card-meta">
            <div class="author-avatar-sm" style="background:{{ $post['author_color'] }};">{{ $post['author'] }}</div>
            <div class="card-meta-text">
              <div class="author-name">{{ $post['author'] === 'AK' ? 'Arjun Kumar' : ($post['author'] === 'SR' ? 'Sara R.' : ($post['author'] === 'MP' ? 'Mike P.' : 'Lena N.')) }}</div>
              <div class="post-date"><i class="fas fa-calendar-alt" style="font-size:.62rem;"></i> {{ $post['date'] }}</div>
            </div>
          </div>

          <div class="card-actions">
            <a href="/blog/{{ $post['slug'] }}" target="_blank" class="btn-bl btn-outline btn-xs">
              <i class="fas fa-eye"></i> View
            </a>
            <a href="{{ route('blogs.edit', $post->id) }}" class="btn-bl btn-outline btn-xs">
              <i class="fas fa-pen"></i> Edit
            </a>
            <button class="btn-bl btn-outline btn-xs" onclick="openShareModal('{{ $post->title }}', '/blog/{{ $post['slug'] }}')">
              <i class="fas fa-share-alt"></i> Share
            </button>   
            <button class="btn-bl btn-outline btn-xs" onclick="openStatsModal('{{ $post->title }}', {{ number_format($post->views ?? 0) }}, {{ $post->likes_count ?? 0 }}, {{ $post->approved_comments_count ?? 0 }})">
              <i class="fas fa-chart-line"></i> Stats
            </button>
            <button class="btn-bl btn-danger btn-xs ms-auto" onclick="openDeleteModal('{{ encrypt($post->id) }}', '{{ addslashes($post->title) }}')">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>
      </div>
      @endforeach

    </div>
  </div>

  {{-- ── PAGINATION ── --}}
  <div class="pagination-wrap">
    <div class="page-info">Showing <strong>1–8</strong> of <strong>{{ $totalPosts }}</strong> posts</div>
    <div class="page-btns">
      <a href="#" class="page-btn"><i class="fas fa-chevron-left" style="font-size:.65rem;"></i></a>
      <a href="#" class="page-btn active">1</a>
      <a href="#" class="page-btn">2</a>
      <a href="#" class="page-btn">3</a>
      <span class="page-btn" style="cursor:default;opacity:.4;">…</span>
      <a href="#" class="page-btn">16</a>
      <a href="#" class="page-btn"><i class="fas fa-chevron-right" style="font-size:.65rem;"></i></a>
    </div>
  </div>

</div>{{-- /bl-wrap --}}

{{-- ═════════════════ MODALS ════════════════ --}}

{{-- SHARE MODAL --}}
<div class="bl-modal-overlay" id="shareModal">
  <div class="bl-modal">
    <div class="modal-header">
      <h3><i class="fas fa-share-alt"></i> Share Post</h3>
      <button class="modal-close" onclick="closeModal('shareModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <div style="margin-bottom:14px;">
        <div style="font-size:.78rem;font-weight:700;color:var(--muted);margin-bottom:4px;text-transform:uppercase;letter-spacing:.5px;">Sharing</div>
        <div id="sharePostTitle" style="font-family:'Nunito',sans-serif;font-weight:900;font-size:1rem;color:var(--text);"></div>
      </div>
      <div class="share-platform-grid">
        <button class="share-btn-plat fb" onclick="shareTo('facebook')">
          <i class="fab fa-facebook-f" style="color:#1877f2;"></i> Facebook
        </button>
        <button class="share-btn-plat tw" onclick="shareTo('twitter')">
          <i class="fab fa-twitter" style="color:#1da1f2;"></i> Twitter
        </button>
        <button class="share-btn-plat li" onclick="shareTo('linkedin')">
          <i class="fab fa-linkedin-in" style="color:#0077b5;"></i> LinkedIn
        </button>
        <button class="share-btn-plat wa" onclick="shareTo('whatsapp')">
          <i class="fab fa-whatsapp" style="color:#25d366;"></i> WhatsApp
        </button>
        <button class="share-btn-plat tg" onclick="shareTo('telegram')">
          <i class="fab fa-telegram-plane" style="color:#0088cc;"></i> Telegram
        </button>
        <button class="share-btn-plat em" onclick="shareTo('email')">
          <i class="fas fa-envelope" style="color:var(--primary);"></i> Email
        </button>
        <button class="share-btn-plat rd" onclick="shareTo('reddit')">
          <i class="fab fa-reddit-alien" style="color:#ff4500;"></i> Reddit
        </button>
        <button class="share-btn-plat cp" onclick="copyPostUrl()">
          <i class="fas fa-link" style="color:var(--success);"></i> Copy Link
        </button>
      </div>
      <div style="font-size:.78rem;font-weight:700;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;">Post URL</div>
      <div class="share-url-row">
        <input type="text" id="shareUrlInput" readonly/>
        <button class="btn-bl btn-primary btn-sm" onclick="copyPostUrl()">
          <i class="fas fa-copy"></i> Copy
        </button>
      </div>
      <div style="margin-top:16px;">
        <div style="font-size:.78rem;font-weight:700;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;">UTM Campaign Link</div>
        <div class="share-url-row">
          <input type="text" id="shareUtmInput" readonly/>
          <button class="btn-bl btn-outline btn-sm" onclick="copyUtmUrl()">
            <i class="fas fa-copy"></i>
          </button>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-bl btn-outline" onclick="closeModal('shareModal')">Close</button>
    </div>
  </div>
</div>

{{-- STATS MODAL --}}
<div class="bl-modal-overlay" id="statsModal">
  <div class="bl-modal">
    <div class="modal-header">
      <h3><i class="fas fa-chart-line"></i> Post Analytics</h3>
      <button class="modal-close" onclick="closeModal('statsModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <div id="statsPostTitle" style="font-family:'Nunito',sans-serif;font-weight:900;font-size:.95rem;color:var(--text);margin-bottom:16px;padding-bottom:14px;border-bottom:1px solid var(--border);"></div>

      <div class="stats-grid">
        <div class="stats-mini-card">
          <div class="stats-mini-val" id="statViews" style="color:var(--primary);">0</div>
          <div class="stats-mini-lbl"><i class="fas fa-eye" style="font-size:.68rem;"></i> Total Views</div>
        </div>
        <div class="stats-mini-card">
          <div class="stats-mini-val" id="statLikes" style="color:var(--danger);">0</div>
          <div class="stats-mini-lbl"><i class="fas fa-heart" style="font-size:.68rem;"></i> Total Likes</div>
        </div>
        <div class="stats-mini-card">
          <div class="stats-mini-val" id="statComments" style="color:#9333ea;">0</div>
          <div class="stats-mini-lbl"><i class="fas fa-comment" style="font-size:.68rem;"></i> Comments</div>
        </div>
        <div class="stats-mini-card">
          <div class="stats-mini-val" style="color:var(--success);">4.2%</div>
          <div class="stats-mini-lbl"><i class="fas fa-mouse-pointer" style="font-size:.68rem;"></i> CTR</div>
        </div>
        <div class="stats-mini-card">
          <div class="stats-mini-val" style="color:var(--warning);">3:24</div>
          <div class="stats-mini-lbl"><i class="fas fa-clock" style="font-size:.68rem;"></i> Avg. Time</div>
        </div>
        <div class="stats-mini-card">
          <div class="stats-mini-val" style="color:var(--muted);">62%</div>
          <div class="stats-mini-lbl"><i class="fas fa-sign-out-alt" style="font-size:.68rem;"></i> Bounce Rate</div>
        </div>
      </div>

      <div style="font-size:.8rem;font-weight:700;color:var(--muted);margin-bottom:10px;text-transform:uppercase;letter-spacing:.5px;">Views — Last 30 Days</div>
      <div class="stats-chart-wrap"><canvas id="statsChartCanvas"></canvas></div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:8px;">
        <div>
          <div style="font-size:.78rem;font-weight:700;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;">Traffic Sources</div>
          <div style="display:flex;flex-direction:column;gap:6px;" id="trafficSources">
            <div style="display:flex;align-items:center;gap:8px;font-size:.78rem;">
              <div style="width:8px;height:8px;border-radius:50%;background:var(--primary);flex-shrink:0;"></div>
              <span style="flex:1;color:var(--text);">Organic Search</span>
              <strong style="color:var(--text);">48%</strong>
            </div>
            <div style="display:flex;align-items:center;gap:8px;font-size:.78rem;">
              <div style="width:8px;height:8px;border-radius:50%;background:var(--success);flex-shrink:0;"></div>
              <span style="flex:1;color:var(--text);">Direct</span>
              <strong style="color:var(--text);">27%</strong>
            </div>
            <div style="display:flex;align-items:center;gap:8px;font-size:.78rem;">
              <div style="width:8px;height:8px;border-radius:50%;background:#1877f2;flex-shrink:0;"></div>
              <span style="flex:1;color:var(--text);">Social</span>
              <strong style="color:var(--text);">16%</strong>
            </div>
            <div style="display:flex;align-items:center;gap:8px;font-size:.78rem;">
              <div style="width:8px;height:8px;border-radius:50%;background:var(--warning);flex-shrink:0;"></div>
              <span style="flex:1;color:var(--text);">Referral</span>
              <strong style="color:var(--text);">9%</strong>
            </div>
          </div>
        </div>
        <div>
          <div style="font-size:.78rem;font-weight:700;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;">Top Devices</div>
          <div style="display:flex;flex-direction:column;gap:6px;">
            <div style="display:flex;align-items:center;gap:8px;font-size:.78rem;">
              <i class="fas fa-mobile-alt" style="color:var(--primary);width:12px;"></i>
              <span style="flex:1;color:var(--text);">Mobile</span><strong style="color:var(--text);">54%</strong>
            </div>
            <div style="display:flex;align-items:center;gap:8px;font-size:.78rem;">
              <i class="fas fa-desktop" style="color:var(--success);width:12px;"></i>
              <span style="flex:1;color:var(--text);">Desktop</span><strong style="color:var(--text);">38%</strong>
            </div>
            <div style="display:flex;align-items:center;gap:8px;font-size:.78rem;">
              <i class="fas fa-tablet-alt" style="color:var(--warning);width:12px;"></i>
              <span style="flex:1;color:var(--text);">Tablet</span><strong style="color:var(--text);">8%</strong>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-bl btn-primary btn-sm">
        <i class="fas fa-external-link-alt"></i> Full Report
      </button>
      <button class="btn-bl btn-outline" onclick="closeModal('statsModal')">Close</button>
    </div>
  </div>
</div>

{{-- DELETE MODAL --}}
<div class="bl-modal-overlay" id="deleteModal">
  <div class="bl-modal modal-sm">
    <div class="modal-body" style="text-align:center;padding:32px 28px 24px;">
      <div class="delete-warn-icon"><i class="fas fa-trash-alt"></i></div>
      <div style="font-family:'Nunito',sans-serif;font-weight:900;font-size:1.15rem;color:var(--text);margin-bottom:8px;">Delete Post?</div>
      <div style="font-size:.85rem;color:var(--muted);line-height:1.6;margin-bottom:4px;">You are about to permanently delete:</div>
      <div id="deletePostTitle" style="font-size:.88rem;font-weight:700;color:var(--danger);margin-bottom:20px;padding:8px 14px;background:#fff5f7;border-radius:8px;"></div>
      <div style="font-size:.8rem;color:var(--muted);margin-bottom:24px;">This action <strong>cannot be undone</strong>. All views, likes, and comments will be lost.</div>
      <div style="display:flex;gap:10px;">
        <button class="btn-bl btn-outline" style="flex:1;" onclick="closeModal('deleteModal')">Cancel</button>
        <button class="btn-bl btn-danger" style="flex:1;" id="confirmDeleteBtn">
          <i class="fas fa-trash"></i> Yes, Delete
        </button>
      </div>
    </div>
  </div>
</div>

{{-- TOAST STACK --}}
<div class="bl-toast-stack" id="blToastStack"></div>

</div>{{-- /blogList --}}

@endsection


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

<script>
$(function () {

  /* ══════════════════════════ HELPER  ══════════════════════════ */
  function toast(msg, color, icon) {
    color = color || 'var(--primary)';
    icon  = icon  || 'fas fa-info-circle';
    var $t = $('<div class="bl-toast"><i class="' + icon + '" style="color:' + color + ';"></i> <span>' + msg + '</span></div>');
    $('#blToastStack').append($t);
    setTimeout(function(){ $t.fadeOut(300, function(){ $t.remove(); }); }, 3000);
  }

  function closeModal(id) {
    $('#' + id).removeClass('show');
  }

  // close on overlay click
  $('.bl-modal-overlay').on('click', function(e){
    if ($(e.target).hasClass('bl-modal-overlay')) {
      $(this).removeClass('show');
    }
  });

  /* ══════════════════════════ VIEW TOGGLE (table/grid)  ══════════════════════════ */
  $('#viewTable').on('click', function () {
    $(this).addClass('active');
    $('#viewGrid').removeClass('active');
    $('#tableView').show();
    $('#gridView').hide();
    toast('Table view', 'var(--primary)', 'fas fa-list');
  });

  $('#viewGrid').on('click', function () {
    $(this).addClass('active');
    $('#viewTable').removeClass('active');
    $('#gridView').show();
    $('#tableView').hide();
    toast('Grid view', 'var(--primary)', 'fas fa-th-large');
  });

  /* ══════════════════════════ STATUS TABS FILTER  ══════════════════════════ */
  $('.status-tab').on('click', function () {
    $('.status-tab').removeClass('active');
    $(this).addClass('active');
    var status = $(this).data('status');

    if (status === 'all') {
      $('[data-status]').show();
    } else {
      $('[data-status]').hide();
      $('[data-status="' + status + '"]').show();
    }
    toast('Filtered: ' + (status === 'all' ? 'All posts' : status), 'var(--primary)', 'fas fa-filter');
  });

  /* ══════════════════════════ SEARCH  ══════════════════════════ */
  var searchTimer;
  $('#blSearch').on('input', function () {
    var val = $(this).val().toLowerCase();
    $('#searchClear').toggle(val.length > 0);
    clearTimeout(searchTimer);
    searchTimer = setTimeout(function () {
      filterRows(val);
    }, 250);
  });

  $('#searchClear').on('click', function () {
    $('#blSearch').val('');
    $(this).hide();
    filterRows('');
  });

  function filterRows(query) {
    var $rows = $('#blogTableBody tr');
    var $cards = $('.blog-card');
    if (!query) {
      $rows.show(); $cards.show(); return;
    }
    $rows.each(function () {
      var text = $(this).text().toLowerCase();
      $(this).toggle(text.includes(query));
    });
    $cards.each(function () {
      var text = $(this).text().toLowerCase();
      $(this).toggle(text.includes(query));
    });
  }

  /* ══════════════════════════ SORT COLUMNS  ══════════════════════════ */
  var sortDir = {};
  $('.bl-table thead th[data-col]').on('click', function () {
    var col = $(this).data('col');
    sortDir[col] = !sortDir[col];
    $('.bl-table thead th').removeClass('sorted');
    $(this).addClass('sorted');
    var $tbody = $('#blogTableBody');
    var $rows = $tbody.find('tr').toArray();
    var colIndex = $(this).index();

    $rows.sort(function(a, b) {
      var aVal = $(a).find('td').eq(colIndex).text().trim().replace(/,/g,'');
      var bVal = $(b).find('td').eq(colIndex).text().trim().replace(/,/g,'');
      var aNum = parseFloat(aVal);
      var bNum = parseFloat(bVal);
      if (!isNaN(aNum) && !isNaN(bNum)) {
        return sortDir[col] ? aNum - bNum : bNum - aNum;
      }
      return sortDir[col] ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
    });

    $.each($rows, function(i, row){ $tbody.append(row); });
    toast('Sorted by ' + col, 'var(--primary)', 'fas fa-sort');
  });

  /* ══════════════════════════ BULK SELECT  ══════════════════════════ */
  $('#selectAll').on('change', function () {
    var checked = $(this).is(':checked');
    $('.row-check').prop('checked', checked);
    updateBulkBar();
  });

  $(document).on('change', '.row-check', function () {
    var total = $('.row-check').length;
    var checked = $('.row-check:checked').length;
    $('#selectAll').prop('indeterminate', checked > 0 && checked < total);
    $('#selectAll').prop('checked', checked === total);
    updateBulkBar();
  });

  function updateBulkBar() {
    var count = $('.row-check:checked').length;
    if (count > 0) {
      $('#bulkBar').addClass('show');
      $('#bulkCount').text(count + ' post' + (count > 1 ? 's' : '') + ' selected');
    } else {
      $('#bulkBar').removeClass('show');
    }
  }

  $('#bulkClear').on('click', function () {
    $('.row-check, #selectAll').prop('checked', false);
    $('#selectAll').prop('indeterminate', false);
    updateBulkBar();
  });

  $('#bulkPublish').on('click', function () {
    var count = $('.row-check:checked').length;
    toast('<strong>' + count + ' posts</strong> published successfully', 'var(--success)', 'fas fa-check-circle');
    $('.row-check:checked').closest('tr, .blog-card').find('.status-badge')
      .removeClass('badge-draft badge-pending badge-scheduled')
      .addClass('badge-published').text('Published');
    $('#bulkBar').removeClass('show');
    $('.row-check, #selectAll').prop('checked', false);
  });

  $('#bulkDraft').on('click', function () {
    var count = $('.row-check:checked').length;
    toast('<strong>' + count + ' posts</strong> set to draft', 'var(--muted)', 'fas fa-file-alt');
    $('#bulkBar').removeClass('show');
    $('.row-check, #selectAll').prop('checked', false);
  });

  $('#bulkExport').on('click', function () {
    toast('Exporting selected posts…', 'var(--success)', 'fas fa-download');
  });

  $('#bulkDelete').on('click', function () {
    var count = $('.row-check:checked').length;
    if (confirm('Delete ' + count + ' selected posts? This cannot be undone.')) {
      $('.row-check:checked').closest('tr').fadeOut(300, function(){ $(this).remove(); });
      $('.row-check:checked').closest('.blog-card').fadeOut(300, function(){ $(this).remove(); });
      toast('<strong>' + count + ' posts</strong> deleted', 'var(--danger)', 'fas fa-trash');
      $('#bulkBar').removeClass('show');
    }
  });

  /* ══════════════════════════ EXPORT  ══════════════════════════ */
  $('#btnExport').on('click', function () {
    toast('Exporting all posts as CSV…', 'var(--success)', 'fas fa-download');
  });

  /* ══════════════════════════ FILTER SELECT CHANGES  ══════════════════════════ */
  $('#filterCategory, #filterSort, #filterDate').on('change', function () {
    toast('Filter applied', 'var(--primary)', 'fas fa-filter');
  });

  /* ══════════════════════════ PAGINATION  ══════════════════════════ */
  $('.page-btn').on('click', function (e) {
    e.preventDefault();
    if ($(this).hasClass('active')) return;
    $('.page-btn').removeClass('active');
    $(this).addClass('active');
    toast('Loading page ' + $(this).text(), 'var(--primary)', 'fas fa-file-alt');
  });

  /* ══════════════════════════ SHARE MODAL  ══════════════════════════ */
  window.openShareModal = function (title, url) {
    var fullUrl = window.location.origin + url;
    $('#sharePostTitle').text(title);
    $('#shareUrlInput').val(fullUrl);
    $('#shareUtmInput').val(fullUrl + '?utm_source=admin&utm_medium=share&utm_campaign=blog');
    $('#shareModal').addClass('show');
  };

  window.shareTo = function (platform) {
    var url = encodeURIComponent($('#shareUrlInput').val());
    var title = encodeURIComponent($('#sharePostTitle').text());
    var links = {
      facebook: 'https://www.facebook.com/sharer/sharer.php?u=' + url,
      twitter:  'https://twitter.com/intent/tweet?url=' + url + '&text=' + title,
      linkedin: 'https://www.linkedin.com/shareArticle?mini=true&url=' + url + '&title=' + title,
      whatsapp: 'https://wa.me/?text=' + title + ' ' + url,
      telegram: 'https://t.me/share/url?url=' + url + '&text=' + title,
      reddit:   'https://www.reddit.com/submit?url=' + url + '&title=' + title,
      email:    'mailto:?subject=' + title + '&body=Check this out: ' + url,
    };
    if (links[platform]) {
      window.open(links[platform], '_blank', 'width=600,height=500');
      toast('Opening ' + platform + ' share…', '#1877f2', 'fas fa-share-alt');
    }
  };

  window.copyPostUrl = function () {
    var url = $('#shareUrlInput').val();
    navigator.clipboard.writeText(url).catch(function(){});
    toast('Post URL copied to clipboard!', 'var(--success)', 'fas fa-check-circle');
  };

  window.copyUtmUrl = function () {
    var url = $('#shareUtmInput').val();
    navigator.clipboard.writeText(url).catch(function(){});
    toast('UTM URL copied!', 'var(--success)', 'fas fa-check-circle');
  };

  /* ══════════════════════════ STATS MODAL + CHART  ══════════════════════════ */
  var statsChart = null;

  window.openStatsModal = function (title, views, likes, comments) {
    $('#statsPostTitle').text(title);
    $('#statViews').text(Number(views).toLocaleString());
    $('#statLikes').text(Number(likes).toLocaleString());
    $('#statComments').text(Number(comments).toLocaleString());
    $('#statsModal').addClass('show');

    // Generate random 30-day data
    var labels = [];
    var data = [];
    for (var i = 29; i >= 0; i--) {
      var d = new Date();
      d.setDate(d.getDate() - i);
      labels.push(d.toLocaleDateString('en-US', { month:'short', day:'numeric' }));
      data.push(Math.floor(Math.random() * (views / 10)) + Math.floor(views / 30));
    }

    setTimeout(function () {
      var ctx = document.getElementById('statsChartCanvas').getContext('2d');
      var grad = ctx.createLinearGradient(0, 0, 0, 200);
      grad.addColorStop(0, 'rgba(26,115,232,.45)');
      grad.addColorStop(1, 'rgba(26,115,232,.02)');

      if (statsChart) { statsChart.destroy(); }
      statsChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Views',
            data: data,
            borderColor: '#1a73e8',
            borderWidth: 2.5,
            backgroundColor: grad,
            fill: true,
            tension: .42,
            pointRadius: 0,
            pointHoverRadius: 5
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
          scales: {
            x: {
              grid: { display: false },
              ticks: { font: { size: 9 }, color: '#8a9bb5', maxTicksLimit: 8 }
            },
            y: {
              grid: { color: 'rgba(0,0,0,.05)' },
              ticks: { font: { size: 9 }, color: '#8a9bb5' }
            }
          }
        }
      });
    }, 100);
  };

  /* ══════════════════════════ DELETE MODAL  ══════════════════════════ */
  var deletePostId = null;

  window.openDeleteModal = function (id, title) {
    deletePostId = id;
    $('#deletePostTitle').text(title);
    $('#deleteModal').addClass('show');
  };

  $('#confirmDeleteBtn').on('click', function () {
    if (!deletePostId) return;
    // AJAX delete (or form submit)
    $.ajax({
        url: '/blogs/' + deletePostId,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            if (res.success) {
                $('tr[data-id="' + deletePostId + '"], .blog-card[data-id="' + deletePostId + '"]').fadeOut(300, function(){ $(this).remove(); });
                toast(res.message, 'var(--danger)', 'fas fa-trash');
                setTimeout(function () {
                    location.reload();
                }, 800);
            } else {
                toast(res.message, '#ff4d6d', 'fas fa-exclamation-circle');
            }
        },
        error: function (xhr) {
            let msg = 'Delete failed';

            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }

            toast(msg, '#ff4d6d', 'fas fa-times-circle');
        }
    });

    closeModal('deleteModal');
    deletePostId = null;
  });

  window.closeModal = closeModal;

  /* ══════════════════════════ ESC to close modals  ══════════════════════════ */
  $(document).on('keydown', function (e) {
    if (e.key === 'Escape') {
      $('.bl-modal-overlay.show').removeClass('show');
    }
  });

});
</script>
@endpush
