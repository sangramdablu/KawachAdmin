@extends('layouts.master')
@section('title', 'My Projects — KawachTech Client Portal')
@section('content')

{{-- ================== STYLES ================== --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap');

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

    #clientPortal {
        font-family: 'Open Sans', sans-serif;
        color: var(--text);
        background: var(--bg);
        min-height: 100vh;
        transition: background .3s, color .3s;
    }

    #clientPortal .cp-wrap {
        max-width: 1440px;
        margin: 0 auto;
        padding: 28px 22px 70px;
    }

    /* ── TOPBAR ── */
    #clientPortal .cp-topbar {
        display: flex; align-items: center; justify-content: space-between;
        gap: 14px; flex-wrap: wrap; margin-bottom: 24px;
    }
    #clientPortal .cp-breadcrumb {
        font-size: .76rem; color: var(--muted);
        display: flex; align-items: center; gap: 6px; margin-bottom: 4px;
    }
    #clientPortal .cp-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 600; }
    #clientPortal .cp-breadcrumb a:hover { text-decoration: underline; }
    #clientPortal .cp-title {
        font-family: 'Nunito', sans-serif; font-weight: 900;
        font-size: 1.55rem; color: var(--text); line-height: 1.2;
    }
    #clientPortal .cp-topbar-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }

    /* ── BUTTONS ── */
    #clientPortal .btn-cp {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px; border-radius: 8px; font-size: .84rem; font-weight: 700;
        cursor: pointer; border: none; transition: all .2s;
        font-family: 'Open Sans', sans-serif; white-space: nowrap; text-decoration: none;
    }
    #clientPortal .btn-cp:hover { transform: translateY(-1px); }
    #clientPortal .btn-primary  { background: var(--primary); color: #fff; }
    #clientPortal .btn-primary:hover { background: var(--primary-dark); box-shadow: 0 4px 14px rgba(26,115,232,.35); color: #fff; }
    #clientPortal .btn-outline  { background: var(--card); color: var(--text); border: 1.5px solid var(--border); }
    #clientPortal .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
    #clientPortal .btn-success  { background: var(--success); color: #fff; }
    #clientPortal .btn-success:hover { background: #00a87c; box-shadow: 0 4px 14px rgba(0,200,150,.3); color: #fff; }
    #clientPortal .btn-sm  { padding: 6px 14px; font-size: .78rem; }
    #clientPortal .btn-xs  { padding: 4px 10px; font-size: .72rem; border-radius: 6px; }
    #clientPortal .btn-icon {
        width: 32px; height: 32px; padding: 0;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px; border: 1.5px solid var(--border);
        background: var(--card); color: var(--muted); cursor: pointer;
        font-size: .8rem; transition: all .18s; text-decoration: none;
    }
    #clientPortal .btn-icon:hover { border-color: var(--primary); color: var(--primary); }

    /* ── WELCOME BANNER ── */
    #clientPortal .welcome-banner {
        background: linear-gradient(135deg, #1a3a6e 0%, #1a73e8 60%, #2196f3 100%);
        border-radius: 16px; padding: 28px 32px; margin-bottom: 22px;
        display: flex; align-items: center; justify-content: space-between;
        gap: 20px; flex-wrap: wrap; overflow: hidden; position: relative;
    }
    #clientPortal .welcome-banner::before {
        content: ''; position: absolute; inset: 0;
        background: repeating-linear-gradient(45deg, rgba(255,255,255,.025) 0px, rgba(255,255,255,.025) 1px, transparent 1px, transparent 28px);
        pointer-events: none;
    }
    #clientPortal .welcome-banner::after {
        content: ''; position: absolute; right: -60px; top: -60px;
        width: 240px; height: 240px; border-radius: 50%;
        background: rgba(255,255,255,.06); pointer-events: none;
    }
    #clientPortal .wb-text { position: relative; z-index: 1; }
    #clientPortal .wb-greeting { font-size: .82rem; font-weight: 700; color: rgba(255,255,255,.75); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 4px; }
    #clientPortal .wb-name { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.7rem; color: #fff; line-height: 1.1; margin-bottom: 8px; }
    #clientPortal .wb-sub { font-size: .82rem; color: rgba(255,255,255,.72); line-height: 1.5; }
    #clientPortal .wb-actions { position: relative; z-index: 1; display: flex; gap: 10px; flex-wrap: wrap; }
    #clientPortal .wb-actions .btn-cp { background: rgba(255,255,255,.15); border: 1.5px solid rgba(255,255,255,.3); color: #fff; backdrop-filter: blur(4px); }
    #clientPortal .wb-actions .btn-cp:hover { background: rgba(255,255,255,.28); border-color: rgba(255,255,255,.5); transform: translateY(-1px); color: #fff; }
    #clientPortal .wb-actions .btn-cp.solid { background: #fff; color: var(--primary); border-color: #fff; }
    #clientPortal .wb-actions .btn-cp.solid:hover { background: rgba(255,255,255,.92); color: var(--primary-dark); }

    /* ── STAT ROW ── */
    #clientPortal .stat-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 22px; }
    @media (max-width: 900px) { #clientPortal .stat-row { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px)  { #clientPortal .stat-row { grid-template-columns: 1fr 1fr; } }
    #clientPortal .stat-card {
        background: var(--card); border-radius: var(--radius); padding: 18px 20px;
        border: 1px solid var(--border); box-shadow: var(--shadow);
        display: flex; align-items: center; gap: 14px;
        transition: transform .2s, box-shadow .2s, background .3s;
    }
    #clientPortal .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    #clientPortal .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.18rem; flex-shrink: 0; }
    #clientPortal .stat-body { min-width: 0; }
    #clientPortal .stat-val { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.6rem; color: var(--text); line-height: 1; margin-bottom: 2px; }
    #clientPortal .stat-lbl { font-size: .72rem; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }
    #clientPortal .stat-sub { font-size: .7rem; font-weight: 700; display: flex; align-items: center; gap: 3px; margin-top: 3px; }

    /* ── MAIN GRID ── */
    #clientPortal .main-grid { display: grid; grid-template-columns: 1fr 360px; gap: 18px; align-items: start; }
    @media (max-width: 1100px) { #clientPortal .main-grid { grid-template-columns: 1fr; } }

    /* ── SECTION HEADER ── */
    #clientPortal .section-hdr { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; gap: 10px; }
    #clientPortal .section-hdr-left { display: flex; align-items: center; gap: 10px; }
    #clientPortal .section-hdr-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .82rem; }
    #clientPortal .section-title { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.02rem; color: var(--text); }
    #clientPortal .section-sub { font-size: .74rem; color: var(--muted); margin-top: 1px; }

    /* ── PROJECT CARD ── */
    #clientPortal .project-list { display: flex; flex-direction: column; gap: 14px; }
    #clientPortal .project-card { background: var(--card); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; transition: transform .2s, box-shadow .2s, background .3s; }
    #clientPortal .project-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    #clientPortal .project-card-top { display: flex; align-items: flex-start; gap: 14px; padding: 18px 20px 14px; }
    #clientPortal .project-logo { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; border: 1.5px solid var(--border); }
    #clientPortal .project-info { flex: 1; min-width: 0; }
    #clientPortal .project-name { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.05rem; color: var(--text); margin-bottom: 3px; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
    #clientPortal .project-type { font-size: .72rem; font-weight: 700; color: var(--muted); display: flex; align-items: center; gap: 5px; margin-bottom: 8px; }
    #clientPortal .project-tags { display: flex; flex-wrap: wrap; gap: 5px; }
    #clientPortal .p-tag { display: inline-flex; align-items: center; gap: 3px; font-size: .66rem; font-weight: 700; padding: 2px 8px; border-radius: 10px; background: #e8f1fd; color: var(--primary); }
    html[data-theme="dark"] #clientPortal .p-tag { background: rgba(26,115,232,.18); }
    #clientPortal .p-tag.green  { background: #d4f5ec; color: #00a87c; }
    #clientPortal .p-tag.orange { background: #fff4d6; color: #b8860b; }

    /* ── PROGRESS ── */
    #clientPortal .project-progress { padding: 0 20px 14px; }
    #clientPortal .progress-meta { display: flex; align-items: center; justify-content: space-between; font-size: .74rem; font-weight: 700; margin-bottom: 7px; }
    #clientPortal .progress-label { color: var(--muted); }
    #clientPortal .progress-pct { color: var(--primary); font-size: .82rem; }
    #clientPortal .progress-bar-wrap { width: 100%; height: 8px; background: var(--border); border-radius: 4px; overflow: hidden; }
    #clientPortal .progress-bar-fill { height: 100%; border-radius: 4px; transition: width .6s cubic-bezier(.4,0,.2,1); }

    /* ── PHASE STEPS ── */
    #clientPortal .phase-steps { display: flex; gap: 0; padding: 0 20px 14px; overflow-x: auto; scrollbar-width: none; }
    #clientPortal .phase-steps::-webkit-scrollbar { display: none; }
    #clientPortal .phase-step { display: flex; flex-direction: column; align-items: center; flex: 1; min-width: 70px; position: relative; }
    #clientPortal .phase-step::before { content: ''; position: absolute; top: 13px; left: 50%; right: -50%; height: 2px; background: var(--border); z-index: 0; }
    #clientPortal .phase-step:last-child::before { display: none; }
    #clientPortal .phase-step.done::before { background: var(--success); }
    #clientPortal .phase-dot { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .7rem; font-weight: 800; z-index: 1; border: 2px solid var(--border); background: var(--card); color: var(--muted); margin-bottom: 5px; transition: all .2s; }
    #clientPortal .phase-step.done   .phase-dot { background: var(--success); border-color: var(--success); color: #fff; }
    #clientPortal .phase-step.active .phase-dot { background: var(--primary); border-color: var(--primary); color: #fff; box-shadow: 0 0 0 4px rgba(26,115,232,.2); }
    #clientPortal .phase-name { font-size: .62rem; font-weight: 700; color: var(--muted); text-align: center; line-height: 1.3; text-transform: uppercase; letter-spacing: .3px; }
    #clientPortal .phase-step.done  .phase-name { color: var(--success); }
    #clientPortal .phase-step.active .phase-name { color: var(--primary); }

    /* ── PROJECT FOOTER ── */
    #clientPortal .project-card-footer { padding: 12px 20px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; background: rgba(26,115,232,.02); }
    #clientPortal .pf-meta { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
    #clientPortal .pf-item { display: flex; align-items: center; gap: 5px; font-size: .75rem; font-weight: 600; color: var(--muted); }
    #clientPortal .pf-item i { font-size: .68rem; }
    #clientPortal .pf-item strong { color: var(--text); }
    #clientPortal .pf-actions { display: flex; gap: 6px; }

    /* ── STATUS BADGE ── */
    #clientPortal .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .4px; }
    #clientPortal .badge-active     { background: #d4f5ec; color: #00a87c; }
    #clientPortal .badge-inprogress { background: #e8f1fd; color: var(--primary); }
    #clientPortal .badge-review     { background: #fff4d6; color: #b8860b; }
    #clientPortal .badge-onhold     { background: #ffe2e8; color: var(--danger); }
    #clientPortal .badge-completed  { background: #f3e8ff; color: #9333ea; }
    html[data-theme="dark"] #clientPortal .badge-active     { background: rgba(0,200,150,.15); }
    html[data-theme="dark"] #clientPortal .badge-inprogress { background: rgba(26,115,232,.15); }
    html[data-theme="dark"] #clientPortal .badge-review     { background: rgba(255,184,48,.15); }
    html[data-theme="dark"] #clientPortal .badge-onhold     { background: rgba(255,77,109,.15); }
    html[data-theme="dark"] #clientPortal .badge-completed  { background: rgba(147,51,234,.15); }

    /* ── SIDE PANEL ── */
    #clientPortal .side-panel { display: flex; flex-direction: column; gap: 18px; }
    #clientPortal .cp-card { background: var(--card); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; transition: background .3s; }

    /* ── TASK LIST ── */
    #clientPortal .task-list { display: flex; flex-direction: column; }
    #clientPortal .task-item { display: flex; align-items: flex-start; gap: 12px; padding: 12px 18px; border-bottom: 1px solid var(--border); transition: background .15s; }
    #clientPortal .task-item:last-child { border-bottom: none; }
    #clientPortal .task-item:hover { background: rgba(26,115,232,.03); }
    #clientPortal .task-check { width: 18px; height: 18px; border-radius: 50%; border: 2px solid var(--border); flex-shrink: 0; margin-top: 1px; display: flex; align-items: center; justify-content: center; font-size: .65rem; transition: all .18s; }
    #clientPortal .task-check.done   { background: var(--success); border-color: var(--success); color: #fff; }
    #clientPortal .task-check.active { border-color: var(--primary); background: rgba(26,115,232,.1); color: var(--primary); }
    #clientPortal .task-body { flex: 1; min-width: 0; }
    #clientPortal .task-name { font-size: .82rem; font-weight: 700; color: var(--text); margin-bottom: 3px; line-height: 1.35; }
    #clientPortal .task-name.done { text-decoration: line-through; color: var(--muted); }
    #clientPortal .task-meta-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    #clientPortal .task-due { font-size: .68rem; font-weight: 600; color: var(--muted); display: flex; align-items: center; gap: 3px; }
    #clientPortal .task-due.overdue { color: var(--danger); }
    #clientPortal .task-priority { font-size: .62rem; font-weight: 800; padding: 1px 6px; border-radius: 6px; text-transform: uppercase; letter-spacing: .3px; }
    #clientPortal .pri-high   { background: #ffe2e8; color: var(--danger); }
    #clientPortal .pri-medium { background: #fff4d6; color: #b8860b; }
    #clientPortal .pri-low    { background: #d4f5ec; color: #00a87c; }

    /* ── ACTIVITY FEED ── */
    #clientPortal .activity-feed { display: flex; flex-direction: column; }
    #clientPortal .activity-item { display: flex; gap: 12px; padding: 12px 18px; border-bottom: 1px solid var(--border); }
    #clientPortal .activity-item:last-child { border-bottom: none; }
    #clientPortal .activity-dot { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .7rem; flex-shrink: 0; margin-top: 1px; }
    #clientPortal .activity-content { flex: 1; min-width: 0; }
    #clientPortal .activity-msg { font-size: .8rem; font-weight: 600; color: var(--text); line-height: 1.4; }
    #clientPortal .activity-time { font-size: .68rem; color: var(--muted); margin-top: 2px; display: flex; align-items: center; gap: 3px; }

    /* ── TEAM CARD ── */
    #clientPortal .team-list { padding: 14px 18px; display: flex; flex-direction: column; gap: 12px; }
    #clientPortal .team-member { display: flex; align-items: center; gap: 11px; }
    #clientPortal .member-avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: 'Nunito', sans-serif; font-weight: 900; font-size: .75rem; color: #fff; flex-shrink: 0; }
    #clientPortal .member-info { flex: 1; min-width: 0; }
    #clientPortal .member-name { font-size: .82rem; font-weight: 700; color: var(--text); }
    #clientPortal .member-role { font-size: .7rem; color: var(--muted); }
    #clientPortal .member-status { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    #clientPortal .online  { background: var(--success); }
    #clientPortal .offline { background: var(--border); }
    #clientPortal .away    { background: var(--warning); }

    /* ── INVOICE ── */
    #clientPortal .invoice-list { display: flex; flex-direction: column; }
    #clientPortal .invoice-item { display: flex; align-items: center; gap: 12px; padding: 11px 18px; border-bottom: 1px solid var(--border); transition: background .15s; }
    #clientPortal .invoice-item:last-child { border-bottom: none; }
    #clientPortal .invoice-item:hover { background: rgba(26,115,232,.03); }
    #clientPortal .invoice-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .8rem; flex-shrink: 0; }
    #clientPortal .invoice-info { flex: 1; min-width: 0; }
    #clientPortal .invoice-id   { font-size: .8rem; font-weight: 700; color: var(--text); }
    #clientPortal .invoice-date { font-size: .68rem; color: var(--muted); }
    #clientPortal .invoice-amount { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: .9rem; color: var(--text); }
    #clientPortal .inv-badge { font-size: .62rem; font-weight: 800; padding: 2px 7px; border-radius: 8px; text-transform: uppercase; letter-spacing: .3px; }
    #clientPortal .inv-paid    { background: #d4f5ec; color: #00a87c; }
    #clientPortal .inv-due     { background: #fff4d6; color: #b8860b; }
    #clientPortal .inv-overdue { background: #ffe2e8; color: var(--danger); }

    /* ── QUICK CONTACT ── */
    #clientPortal .contact-card-body { padding: 16px 18px; }
    #clientPortal .pm-profile { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
    #clientPortal .pm-avatar { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: 'Nunito', sans-serif; font-weight: 900; font-size: .95rem; color: #fff; flex-shrink: 0; }
    #clientPortal .pm-info { flex: 1; min-width: 0; }
    #clientPortal .pm-name { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: .95rem; color: var(--text); }
    #clientPortal .pm-role { font-size: .72rem; color: var(--muted); }
    #clientPortal .pm-avail { font-size: .68rem; font-weight: 700; color: var(--success); display: flex; align-items: center; gap: 4px; margin-top: 2px; }
    #clientPortal .pm-avail::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--success); flex-shrink: 0; }
    #clientPortal .contact-btns { display: flex; gap: 8px; }
    #clientPortal .contact-btns .btn-cp { flex: 1; justify-content: center; }

    /* ── MODAL ── */
    #clientPortal .cp-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 9000; align-items: center; justify-content: center; backdrop-filter: blur(3px); }
    #clientPortal .cp-modal-overlay.show { display: flex; }
    #clientPortal .cp-modal { background: var(--card); border-radius: 16px; padding: 0; max-width: 560px; width: 95%; box-shadow: 0 24px 64px rgba(0,0,0,.25); animation: cpModalPop .2s ease; max-height: 92vh; overflow-y: auto; position: relative; scrollbar-width: none; }
    #clientPortal .cp-modal::-webkit-scrollbar { display: none; }
    @keyframes cpModalPop { from { opacity:0; transform: scale(.93); } to { opacity:1; transform: scale(1); } }
    #clientPortal .modal-header { padding: 20px 24px 16px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
    #clientPortal .modal-header h3 { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.1rem; color: var(--text); margin: 0; display: flex; align-items: center; gap: 8px; }
    #clientPortal .modal-header h3 i { color: var(--primary); }
    #clientPortal .modal-close { width: 30px; height: 30px; border-radius: 50%; border: none; background: var(--bg); color: var(--muted); font-size: .8rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .15s; }
    #clientPortal .modal-close:hover { background: var(--border); color: var(--text); }
    #clientPortal .modal-body { padding: 22px 24px; }
    #clientPortal .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; gap: 8px; justify-content: flex-end; }

    /* ── FILES ── */
    #clientPortal .file-list { display: flex; flex-direction: column; gap: 8px; }
    #clientPortal .file-row { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; border: 1px solid var(--border); background: var(--bg); transition: border-color .15s; }
    #clientPortal .file-row:hover { border-color: var(--primary); }
    #clientPortal .file-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .82rem; flex-shrink: 0; }
    #clientPortal .file-meta { flex: 1; min-width: 0; }
    #clientPortal .file-name { font-size: .8rem; font-weight: 700; color: var(--text); }
    #clientPortal .file-size { font-size: .68rem; color: var(--muted); }

    /* ── TOAST ── */
    #clientPortal .cp-toast-stack { position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; pointer-events: none; }
    #clientPortal .cp-toast { background: var(--card); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; display: flex; align-items: center; gap: 10px; box-shadow: 0 6px 24px rgba(0,0,0,.15); font-size: .82rem; color: var(--text); pointer-events: all; animation: toastSlide .22s ease; max-width: 300px; }
    @keyframes toastSlide { from { opacity:0; transform:translateX(20px); } to { opacity:1; transform:none; } }
    #clientPortal .cp-toast i { font-size: .95rem; flex-shrink: 0; }

    /* ── MILESTONE ── */
    #clientPortal .milestone-list { padding: 8px 18px 14px; display: flex; flex-direction: column; gap: 0; }
    #clientPortal .milestone-item { display: flex; gap: 14px; position: relative; padding-bottom: 16px; }
    #clientPortal .milestone-item:last-child { padding-bottom: 0; }
    #clientPortal .milestone-item::before { content: ''; position: absolute; left: 14px; top: 28px; bottom: 0; width: 2px; background: var(--border); }
    #clientPortal .milestone-item:last-child::before { display: none; }
    #clientPortal .milestone-item.done::before { background: var(--success); }
    #clientPortal .ms-dot { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .68rem; flex-shrink: 0; z-index: 1; border: 2px solid var(--border); background: var(--card); color: var(--muted); }
    #clientPortal .milestone-item.done   .ms-dot { background: var(--success); border-color: var(--success); color: #fff; }
    #clientPortal .milestone-item.active .ms-dot { background: var(--primary); border-color: var(--primary); color: #fff; box-shadow: 0 0 0 4px rgba(26,115,232,.15); }
    #clientPortal .ms-body { flex: 1; padding-top: 3px; }
    #clientPortal .ms-title { font-size: .82rem; font-weight: 700; color: var(--text); margin-bottom: 2px; }
    #clientPortal .ms-date  { font-size: .7rem; color: var(--muted); }

    /* ── FEEDBACK ── */
    #clientPortal .feedback-body { padding: 16px 18px; }
    #clientPortal .rating-stars { display: flex; gap: 5px; margin-bottom: 12px; }
    #clientPortal .star { font-size: 1.3rem; cursor: pointer; color: var(--border); transition: color .15s; }
    #clientPortal .star.active { color: var(--warning); }
    #clientPortal .feedback-textarea { width: 100%; min-height: 80px; border: 1.5px solid var(--border); border-radius: 8px; padding: 10px 12px; font-size: .82rem; font-family: 'Open Sans', sans-serif; color: var(--text); background: var(--bg); resize: vertical; outline: none; transition: border-color .2s; box-sizing: border-box; }
    #clientPortal .feedback-textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,115,232,.1); }

    /* ── MISC ── */
    #clientPortal .notice-bar { display: flex; align-items: flex-start; gap: 12px; padding: 12px 16px; border-radius: 10px; border: 1.5px solid rgba(26,115,232,.25); background: rgba(26,115,232,.05); font-size: .8rem; color: var(--text); line-height: 1.5; margin-bottom: 14px; }
    #clientPortal .notice-bar i { color: var(--primary); margin-top: 2px; flex-shrink: 0; }
    #clientPortal .compose-wrap { border: 1.5px solid var(--border); border-radius: 10px; overflow: hidden; }
    #clientPortal .compose-textarea { width: 100%; min-height: 100px; border: none; padding: 12px 14px; font-size: .82rem; font-family: 'Open Sans', sans-serif; color: var(--text); background: var(--bg); resize: none; outline: none; box-sizing: border-box; }
    #clientPortal .compose-footer { display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; border-top: 1px solid var(--border); background: var(--bg); gap: 8px; }
    #clientPortal .cp-divider { height: 1px; background: var(--border); margin: 4px 0; }

    @media (max-width: 640px) {
        #clientPortal .cp-topbar { flex-direction: column; align-items: flex-start; }
        #clientPortal .welcome-banner { padding: 22px 20px; }
        #clientPortal .wb-name { font-size: 1.3rem; }
        #clientPortal .stat-val { font-size: 1.3rem; }
        #clientPortal .phase-step { min-width: 55px; }
        #clientPortal .phase-name { font-size: .55rem; }
    }
</style>

{{-- ============== MARKUP ============== --}}
<div id="clientPortal">
<div class="cp-wrap">

  {{-- ── TOPBAR ── --}}
  <div class="cp-topbar">
    <div>
      <div class="cp-breadcrumb">
        <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
        <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
        <span>My Projects</span>
      </div>
      <div class="cp-title">🚀 Client Portal</div>
    </div>
    <div class="cp-topbar-actions">
      <button class="btn-cp btn-outline" onclick="openMessageModal()">
        <i class="fas fa-comment-dots"></i> Message Team
      </button>
      <button class="btn-cp btn-primary" onclick="openFilesModal()">
        <i class="fas fa-folder-open"></i> Project Files
      </button>
    </div>
  </div>

  {{-- ── WELCOME BANNER ── --}}
  <div class="welcome-banner">
    <div class="wb-text">
      <div class="wb-greeting">👋 Welcome back</div>
      <div class="wb-name">{{ auth()->user()->name }}</div>
      <div class="wb-sub">
        You have <strong style="color:#fff;">{{ $activeTasks }} active tasks</strong> across
        <strong style="color:#fff;">{{ $totalProjects }} {{ Str::plural('project', $totalProjects) }}</strong> this week.
        @if($nextMilestoneDays !== null)
          Your next milestone is due in <strong style="color:#ffb830;">{{ $nextMilestoneDays }} {{ Str::plural('day', $nextMilestoneDays) }}</strong>.
        @else
          All milestones are on track.
        @endif
      </div>
    </div>
    <div class="wb-actions">
      <button class="btn-cp solid" onclick="openMessageModal()">
        <i class="fas fa-paper-plane"></i> Send Message
      </button>
      <button class="btn-cp" onclick="openFilesModal()">
        <i class="fas fa-download"></i> Downloads
      </button>
    </div>
  </div>

  {{-- ── STAT OVERVIEW ── --}}
  <div class="stat-row">
    <div class="stat-card">
      <div class="stat-icon" style="background:#e8f1fd;color:var(--primary);"><i class="fas fa-layer-group"></i></div>
      <div class="stat-body">
        <div class="stat-val">{{ $totalProjects ?? 0 }}</div>
        <div class="stat-lbl">Active Projects</div>
        <div class="stat-sub" style="color:var(--success);"><i class="fas fa-check-circle"></i> On schedule</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:#d4f5ec;color:var(--success);"><i class="fas fa-tasks"></i></div>
      <div class="stat-body">
        <div class="stat-val">{{ $completedTasks }}</div>
        <div class="stat-lbl">Tasks Completed</div>
        <div class="stat-sub" style="color:var(--success);"><i class="fas fa-check"></i> All time</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:#fff4d6;color:var(--warning);"><i class="fas fa-hourglass-half"></i></div>
      <div class="stat-body">
        <div class="stat-val">{{ $pendingApprovals }}</div>
        <div class="stat-lbl">Pending Approvals</div>
        <div class="stat-sub" style="color:var(--warning);"><i class="fas fa-clock"></i> Needs your review</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:#f3e8ff;color:#9333ea;"><i class="fas fa-file-invoice-dollar"></i></div>
      <div class="stat-body">
        <div class="stat-val">{{ $pendingInvoices }}</div>
        <div class="stat-lbl">Pending Invoices</div>
        <div class="stat-sub" style="{{ $pendingInvoices > 0 ? 'color:var(--danger)' : 'color:var(--success)' }};">
          <i class="fas fa-{{ $pendingInvoices > 0 ? 'exclamation-circle' : 'check-circle' }}"></i>
          {{ $pendingInvoices > 0 ? 'Due soon' : 'All clear' }}
        </div>
      </div>
    </div>
  </div>

  {{-- ── MAIN GRID ── --}}
  <div class="main-grid">

    {{-- LEFT COL ── Projects + Tasks --}}
    <div>

      {{-- PROJECTS ── --}}
      <div class="section-hdr">
        <div class="section-hdr-left">
          <div class="section-hdr-icon" style="background:#e8f1fd;color:var(--primary);"><i class="fas fa-layer-group"></i></div>
          <div>
            <div class="section-title">My Projects</div>
            <div class="section-sub">Live status &amp; progress</div>
          </div>
        </div>
      </div>

      <div class="project-list">
        @forelse($projects as $project)
        <div class="project-card">
          <div class="project-card-top">
            <div class="project-logo" style="background:{{ $project['color_bg'] }};color:{{ $project['color'] }};">
              <i class="{{ $project['icon'] }}"></i>
            </div>
            <div class="project-info">
              <div class="project-name">{{ $project['name'] }}</div>
              <div class="project-type"><i class="fas fa-tag"></i> {{ $project['type'] }}</div>
              <div class="project-tags">
                <span class="status-badge {{ $project['badge_class'] }}" style="font-size:.62rem;padding:2px 8px;">
                  <span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                  {{ $project['status_label'] }}
                </span>
                @foreach($project['tags'] as $tag)
                  <span class="p-tag {{ $tag['class'] ?? '' }}">
                    <i class="{{ $tag['icon'] ?? 'fas fa-tag' }}"></i> {{ $tag['label'] }}
                  </span>
                @endforeach
              </div>
            </div>
          </div>

          {{-- Progress Bar ── --}}
          <div class="project-progress">
            <div class="progress-meta">
              <span class="progress-label">Overall Progress</span>
              <span class="progress-pct">{{ $project['progress'] }}%</span>
            </div>
            <div class="progress-bar-wrap">
              <div class="progress-bar-fill"
                   style="width:{{ $project['progress'] }}%;
                          background:{{ $project['progress'] >= 80
                              ? 'var(--success)'
                              : ($project['progress'] >= 50 ? 'var(--primary)' : 'var(--warning)') }};"></div>
            </div>
          </div>

          {{-- Phase Steps ── --}}
          @if(count($project['phases']))
          <div class="phase-steps">
            @foreach($project['phases'] as $i => $phase)
            <div class="phase-step {{ $phase['state'] }}">
              <div class="phase-dot">
                @if($phase['state'] === 'done')
                  <i class="fas fa-check"></i>
                @elseif($phase['state'] === 'active')
                  <i class="fas fa-spinner fa-spin" style="font-size:.6rem;"></i>
                @else
                  <span style="font-size:.62rem;">{{ $i + 1 }}</span>
                @endif
              </div>
              <div class="phase-name">{{ $phase['name'] }}</div>
            </div>
            @endforeach
          </div>
          @endif

          {{-- Footer ── --}}
          <div class="project-card-footer">
            <div class="pf-meta">
              <div class="pf-item"><i class="fas fa-calendar-alt"></i> Started <strong>{{ $project['start_date'] }}</strong></div>
              <div class="pf-item"><i class="fas fa-flag-checkered"></i> Deadline <strong>{{ $project['deadline'] }}</strong></div>
              <div class="pf-item"><i class="fas fa-tasks"></i> <strong>{{ $project['done_tasks'] }}/{{ $project['total_tasks'] }}</strong> tasks</div>
            </div>
            <div class="pf-actions">
              <button class="btn-cp btn-outline btn-xs" onclick="openFilesModal()">
                <i class="fas fa-folder-open"></i> Files
              </button>
              <button class="btn-cp btn-primary btn-xs" onclick="openMilestoneModal('{{ addslashes($project['name']) }}')">
                <i class="fas fa-map-signs"></i> Milestones
              </button>
            </div>
          </div>
        </div>
        @empty
        <div class="cp-card" style="padding:40px;text-align:center;color:var(--muted);">
          <i class="fas fa-layer-group" style="font-size:2rem;margin-bottom:12px;display:block;opacity:.4;"></i>
          <div style="font-weight:700;">No projects yet</div>
          <div style="font-size:.8rem;margin-top:4px;">Your project details will appear here once the team sets up your workspace.</div>
        </div>
        @endforelse
      </div>

      {{-- TASKS ── --}}
      <div class="section-hdr" style="margin-top:24px;">
        <div class="section-hdr-left">
          <div class="section-hdr-icon" style="background:#d4f5ec;color:var(--success);"><i class="fas fa-check-square"></i></div>
          <div>
            <div class="section-title">Current Tasks Requiring Your Input</div>
            <div class="section-sub">Approvals, feedback &amp; sign-offs pending from you</div>
          </div>
        </div>
      </div>

      @if(count($pendingTasksList))
      <div class="notice-bar">
        <i class="fas fa-info-circle"></i>
        <span>
          <strong>{{ collect($pendingTasksList)->where('state', '!=', 'done')->count() }} {{ Str::plural('item', collect($pendingTasksList)->where('state', '!=', 'done')->count()) }}</strong>
          {{ collect($pendingTasksList)->where('state', '!=', 'done')->count() === 1 ? 'is' : 'are' }}
          waiting for your review or approval.
        </span>
      </div>

      <div class="cp-card">
        <div class="task-list">
          @foreach($pendingTasksList as $task)
          <div class="task-item">
            <div class="task-check {{ $task['state'] }}">
              @if($task['state'] === 'done') <i class="fas fa-check"></i>
              @elseif($task['state'] === 'active') <i class="fas fa-clock"></i>
              @endif
            </div>
            <div class="task-body">
              <div class="task-name {{ $task['state'] === 'done' ? 'done' : '' }}">{{ $task['name'] }}</div>
              <div class="task-meta-row">
                <div class="task-due {{ $task['overdue'] ? 'overdue' : '' }}">
                  <i class="fas fa-calendar"></i> {{ $task['due'] }}
                </div>
                <span class="task-priority pri-{{ $task['priority'] }}">{{ $task['priority'] }}</span>
                <span class="p-tag" style="font-size:.62rem;">{{ $task['project'] }}</span>
              </div>
            </div>
            <div>
              @if($task['state'] !== 'done')
                <button class="btn-cp btn-success btn-xs"
                        onclick="cpToast('Approval noted — the team has been informed.', 'var(--success)', 'fas fa-check-circle')">
                  Approve
                </button>
              @else
                <span style="font-size:.7rem;color:var(--success);font-weight:700;"><i class="fas fa-check-circle"></i> Done</span>
              @endif
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @else
      <div class="cp-card" style="padding:32px;text-align:center;color:var(--muted);">
        <i class="fas fa-check-circle" style="font-size:1.8rem;color:var(--success);margin-bottom:10px;display:block;"></i>
        <div style="font-weight:700;">You're all caught up!</div>
        <div style="font-size:.8rem;margin-top:4px;">No tasks require your input right now.</div>
      </div>
      @endif

    </div>{{-- /left col --}}

    {{-- RIGHT COL ── Side Panel --}}
    <div class="side-panel">

      {{-- QUICK CONTACT ── --}}
      <div class="cp-card">
        <div class="modal-header" style="padding:14px 18px 12px;">
          <h3 style="font-size:.88rem;"><i class="fas fa-headset"></i> Your Project Manager</h3>
        </div>
        <div class="contact-card-body">
          <div class="pm-profile">
            <div class="pm-avatar" style="background:linear-gradient(135deg,#1a73e8,#9333ea);">RK</div>
            <div class="pm-info">
              <div class="pm-name">Rohit Khanna</div>
              <div class="pm-role">Senior Project Manager</div>
              <div class="pm-avail">Available now</div>
            </div>
          </div>
          <div class="contact-btns">
            <button class="btn-cp btn-outline btn-sm" onclick="openMessageModal()">
              <i class="fas fa-comment-alt"></i> Message
            </button>
            <button class="btn-cp btn-primary btn-sm"
                    onclick="cpToast('Scheduling a call…', 'var(--primary)', 'fas fa-phone')">
              <i class="fas fa-phone"></i> Schedule Call
            </button>
          </div>
        </div>
      </div>

      {{-- ACTIVITY FEED ── --}}
      <div class="cp-card">
        <div class="modal-header" style="padding:14px 18px 12px;">
          <h3 style="font-size:.88rem;"><i class="fas fa-bolt"></i> Recent Activity</h3>
          <button class="btn-icon" style="font-size:.7rem;"
                  onclick="cpToast('Activity refreshed', 'var(--success)', 'fas fa-sync')">
            <i class="fas fa-sync"></i>
          </button>
        </div>
        <div class="activity-feed">
          <div class="activity-item">
            <div class="activity-dot" style="background:#e8f1fd;color:var(--primary);"><i class="fas fa-check"></i></div>
            <div class="activity-content">
              <div class="activity-msg">Portal access <strong>activated</strong> for your account</div>
              <div class="activity-time"><i class="fas fa-clock" style="font-size:.6rem;"></i> Recently</div>
            </div>
          </div>
          <div class="activity-item">
            <div class="activity-dot" style="background:#d4f5ec;color:var(--success);"><i class="fas fa-file-contract"></i></div>
            <div class="activity-content">
              <div class="activity-msg">Billing agreement <strong>confirmed</strong></div>
              <div class="activity-time"><i class="fas fa-clock" style="font-size:.6rem;"></i> On project start</div>
            </div>
          </div>
          <div class="activity-item">
            <div class="activity-dot" style="background:#fff4d6;color:var(--warning);"><i class="fas fa-envelope"></i></div>
            <div class="activity-content">
              <div class="activity-msg">Credentials email <strong>sent</strong> to your inbox</div>
              <div class="activity-time"><i class="fas fa-clock" style="font-size:.6rem;"></i> On account creation</div>
            </div>
          </div>
        </div>
      </div>

      {{-- TEAM ── --}}
      @if(count($team))
      <div class="cp-card">
        <div class="modal-header" style="padding:14px 18px 12px;">
          <h3 style="font-size:.88rem;"><i class="fas fa-users"></i> Project Team</h3>
          <span style="font-size:.72rem;color:var(--muted);font-weight:700;">{{ count($team) }} {{ Str::plural('member', count($team)) }}</span>
        </div>
        <div class="team-list">
          @foreach($team as $member)
          <div class="team-member">
            <div class="member-avatar" style="background:{{ $member['color'] }};">{{ $member['initials'] }}</div>
            <div class="member-info">
              <div class="member-name">{{ $member['name'] }}</div>
              <div class="member-role">{{ $member['role'] }}</div>
            </div>
            <div class="member-status {{ $member['status'] }}" title="{{ ucfirst($member['status']) }}"></div>
          </div>
          @endforeach
        </div>
      </div>
      @endif

      {{-- INVOICES ── --}}
      <div class="cp-card">
        <div class="modal-header" style="padding:14px 18px 12px;">
          <h3 style="font-size:.88rem;"><i class="fas fa-file-invoice-dollar"></i> Invoices</h3>
        </div>
        @if(count($invoices))
        <div class="invoice-list">
          @foreach($invoices as $inv)
          <div class="invoice-item">
            <div class="invoice-icon" style="background:{{ $inv['icon_bg'] }};color:{{ $inv['icon_color'] }};"><i class="fas fa-file-invoice"></i></div>
            <div class="invoice-info">
              <div class="invoice-id">{{ $inv['id'] }}</div>
              <div class="invoice-date">{{ $inv['date'] }}</div>
            </div>
            <div style="text-align:right;">
              <div class="invoice-amount">{{ $inv['amount'] }}</div>
              <span class="inv-badge inv-{{ $inv['status'] }}">{{ ucfirst($inv['status']) }}</span>
            </div>
          </div>
          @endforeach
        </div>
        @else
        <div style="padding:24px;text-align:center;color:var(--muted);font-size:.82rem;">
          No invoices yet.
        </div>
        @endif
      </div>

      {{-- FEEDBACK ── --}}
      <div class="cp-card">
        <div class="modal-header" style="padding:14px 18px 12px;">
          <h3 style="font-size:.88rem;"><i class="fas fa-star"></i> Leave Feedback</h3>
        </div>
        <div class="feedback-body">
          <div style="font-size:.75rem;color:var(--muted);font-weight:700;margin-bottom:6px;text-transform:uppercase;letter-spacing:.4px;">How's the project going?</div>
          <div class="rating-stars" id="ratingStars">
            <span class="star active" data-val="1">★</span>
            <span class="star active" data-val="2">★</span>
            <span class="star active" data-val="3">★</span>
            <span class="star active" data-val="4">★</span>
            <span class="star" data-val="5">★</span>
          </div>
          <textarea class="feedback-textarea"
                    placeholder="Share your thoughts on the project progress, team communication, or anything else…"
                    id="feedbackText"></textarea>
          <button class="btn-cp btn-primary btn-sm"
                  style="width:100%;justify-content:center;margin-top:10px;"
                  onclick="submitFeedback()">
            <i class="fas fa-paper-plane"></i> Submit Feedback
          </button>
        </div>
      </div>

    </div>{{-- /side-panel --}}

  </div>{{-- /main-grid --}}

</div>{{-- /cp-wrap --}}


{{-- ══════════ MODALS ══════════ --}}

{{-- MESSAGE MODAL --}}
<div class="cp-modal-overlay" id="messageModal">
  <div class="cp-modal">
    <div class="modal-header">
      <h3><i class="fas fa-comment-dots"></i> Message Project Team</h3>
      <button class="modal-close" onclick="closeModal('messageModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <div style="margin-bottom:12px;">
        <label style="font-size:.75rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.4px;display:block;margin-bottom:6px;">To</label>
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
          <span class="p-tag"><i class="fas fa-user"></i> Rohit Khanna (PM)</span>
          <span class="p-tag green"><i class="fas fa-users"></i> Full Team</span>
        </div>
      </div>
      <div style="margin-bottom:12px;">
        <label style="font-size:.75rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.4px;display:block;margin-bottom:6px;">Message</label>
        <div class="compose-wrap">
          <textarea class="compose-textarea" placeholder="Write your message here…" id="msgText"></textarea>
          <div class="compose-footer">
            <div style="display:flex;gap:8px;">
              <button class="btn-icon" title="Attach file"
                      onclick="cpToast('File picker opening…', 'var(--muted)', 'fas fa-paperclip')"><i class="fas fa-paperclip"></i></button>
              <button class="btn-icon" title="Add emoji"
                      onclick="cpToast('Emoji picker…', 'var(--warning)', 'fas fa-smile')"><i class="fas fa-smile"></i></button>
            </div>
            <span style="font-size:.7rem;color:var(--muted);">Typical reply: &lt; 2 hours</span>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-cp btn-outline" onclick="closeModal('messageModal')">Cancel</button>
      <button class="btn-cp btn-primary" onclick="sendMessage()">
        <i class="fas fa-paper-plane"></i> Send Message
      </button>
    </div>
  </div>
</div>

{{-- FILES MODAL --}}
<div class="cp-modal-overlay" id="filesModal">
  <div class="cp-modal">
    <div class="modal-header">
      <h3><i class="fas fa-folder-open"></i> Project Files</h3>
      <button class="modal-close" onclick="closeModal('filesModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <div style="font-size:.75rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.4px;margin-bottom:12px;">Shared With You</div>
      <div class="file-list">
        <div class="file-row">
          <div class="file-icon" style="background:#ffe2e8;color:var(--danger);"><i class="fas fa-file-pdf"></i></div>
          <div class="file-meta">
            <div class="file-name">Project_Brief_v2.pdf</div>
            <div class="file-size">Shared by your team</div>
          </div>
          <button class="btn-cp btn-outline btn-xs"
                  onclick="cpToast('Downloading…', 'var(--success)', 'fas fa-download')"><i class="fas fa-download"></i></button>
        </div>
        <div class="file-row">
          <div class="file-icon" style="background:#fff4d6;color:var(--warning);"><i class="fas fa-file-contract"></i></div>
          <div class="file-meta">
            <div class="file-name">Contract_KawachTech.pdf</div>
            <div class="file-size">Billing agreement document</div>
          </div>
          <button class="btn-cp btn-outline btn-xs"
                  onclick="cpToast('Downloading…', 'var(--success)', 'fas fa-download')"><i class="fas fa-download"></i></button>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-cp btn-outline" onclick="closeModal('filesModal')">Close</button>
    </div>
  </div>
</div>

{{-- MILESTONES MODAL --}}
<div class="cp-modal-overlay" id="milestoneModal">
  <div class="cp-modal">
    <div class="modal-header">
      <h3><i class="fas fa-map-signs"></i> Project Milestones</h3>
      <button class="modal-close" onclick="closeModal('milestoneModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body" style="padding:16px 24px;">
      <div id="milestoneProjectName"
           style="font-family:'Nunito',sans-serif;font-weight:900;font-size:.88rem;color:var(--muted);margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--border);"></div>

      {{-- Milestones are built from the active project's phases --}}
      @foreach($projects as $project)
      <div class="milestone-list project-milestone-list" data-project="{{ $project['name'] }}"
           style="{{ $loop->first ? '' : 'display:none;' }}">
        @foreach($project['phases'] as $i => $phase)
        <div class="milestone-item {{ $phase['state'] }}">
          <div class="ms-dot">
            @if($phase['state'] === 'done')      <i class="fas fa-check"></i>
            @elseif($phase['state'] === 'active') <i class="fas fa-spinner fa-spin" style="font-size:.6rem;"></i>
            @else                                  <span style="font-size:.62rem;">{{ $i + 1 }}</span>
            @endif
          </div>
          <div class="ms-body">
            <div class="ms-title">{{ $phase['name'] }}</div>
            <div class="ms-date" style="{{ $phase['state'] === 'active' ? 'color:var(--primary);' : '' }}">
              @if($phase['state'] === 'done')      <i class="fas fa-calendar" style="font-size:.62rem;"></i> Completed
              @elseif($phase['state'] === 'active') <i class="fas fa-hourglass-half" style="font-size:.62rem;"></i> In Progress
              @else                                  <i class="fas fa-calendar" style="font-size:.62rem;"></i> Planned
              @endif
            </div>
          </div>
        </div>
        @endforeach
      </div>
      @endforeach

    </div>
    <div class="modal-footer">
      <button class="btn-cp btn-outline" onclick="closeModal('milestoneModal')">Close</button>
    </div>
  </div>
</div>

{{-- TOAST STACK --}}
<div class="cp-toast-stack" id="cpToastStack"></div>

</div>{{-- /clientPortal --}}

@endsection


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(function () {

  /* ─── HELPERS ─── */
  window.cpToast = function (msg, color, icon) {
    color = color || 'var(--primary)';
    icon  = icon  || 'fas fa-info-circle';
    var $t = $('<div class="cp-toast"><i class="' + icon + '" style="color:' + color + ';"></i><span>' + msg + '</span></div>');
    $('#cpToastStack').append($t);
    setTimeout(function () { $t.fadeOut(300, function () { $t.remove(); }); }, 3200);
  };

  window.closeModal = function (id) {
    $('#' + id).removeClass('show');
  };

  $('.cp-modal-overlay').on('click', function (e) {
    if ($(e.target).hasClass('cp-modal-overlay')) $(this).removeClass('show');
  });

  $(document).on('keydown', function (e) {
    if (e.key === 'Escape') $('.cp-modal-overlay.show').removeClass('show');
  });

  /* ─── MODALS ─── */
  window.openMessageModal = function () {
    $('#msgText').val('');
    $('#messageModal').addClass('show');
  };

  window.openFilesModal = function () {
    $('#filesModal').addClass('show');
  };

  window.openMilestoneModal = function (projectName) {
    $('#milestoneProjectName').text(projectName);
    // Show the matching project milestone list
    $('.project-milestone-list').hide();
    var $match = $('.project-milestone-list[data-project="' + projectName + '"]');
    if ($match.length) { $match.show(); } else { $('.project-milestone-list').first().show(); }
    $('#milestoneModal').addClass('show');
  };

  window.sendMessage = function () {
    var msg = $('#msgText').val().trim();
    if (!msg) {
      cpToast('Please write a message first.', 'var(--warning)', 'fas fa-exclamation-triangle');
      return;
    }
    closeModal('messageModal');
    cpToast('Message sent to your project team!', 'var(--success)', 'fas fa-check-circle');
  };

  /* ─── STAR RATING ─── */
  var currentRating = 4;

  $('#ratingStars .star').on('click', function () {
    currentRating = parseInt($(this).data('val'));
    updateStars(currentRating);
  });

  $('#ratingStars .star')
    .on('mouseenter', function () { updateStars(parseInt($(this).data('val'))); })
    .on('mouseleave', function () { updateStars(currentRating); });

  function updateStars(val) {
    $('#ratingStars .star').each(function () {
      $(this).toggleClass('active', parseInt($(this).data('val')) <= val);
    });
  }

  window.submitFeedback = function () {
    var text = $('#feedbackText').val().trim();
    if (!text) {
      cpToast('Please write your feedback first.', 'var(--warning)', 'fas fa-exclamation-triangle');
      return;
    }
    $('#feedbackText').val('');
    cpToast('Thank you! Feedback submitted (' + currentRating + '★)', 'var(--success)', 'fas fa-star');
  };

  /* ─── ANIMATE PROGRESS BARS on load ─── */
  $('.progress-bar-fill').each(function () {
    var target = $(this).css('width');
    $(this).css('width', 0).animate({ width: target }, 900);
  });

});
</script>
@endpush