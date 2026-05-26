@extends('layouts.master')
@section('title', 'Create Agreement — Kawach Technology')

@section('content')
<style>
        @import url('https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');
 
    /* ── ROOT TOKENS ── */
    :root {
        --sidebar-bg:    #0d1b3e;
        --sidebar-width: 220px;
        --navbar-h:      70px;
        --primary:       #1a73e8;
        --primary-d:     #1558b0;
        --primary-l:     #e8f0fd;
        --accent:        #2196f3;
        --bg-body:       #eef2f9;
        --white:         #ffffff;
        --card-bg:       #ffffff;
        --card-radius:   14px;
        --text-dark:     #1a1a2e;
        --text-muted:    #8a9bb5;
        --border:        #e2e8f0;
        --green:         #00c896;
        --red:           #ff4d6d;
        --red-l:         #fff0f3;
        --yellow:        #ffb830;
        --amber:         #d97706;
        --panel-title:   #1a1a2e;
        --input-bg:      #ffffff;
        --input-border:  #e2e8f0;
        --input-color:   #1a1a2e;
        --modal-bg:      #ffffff;
        --modal-header:  #f4f6fb;
        --topbar-bg:     #0d1b3e;
        --shadow-sm:     0 1px 6px rgba(0,0,0,.06);
        --shadow:        0 4px 20px rgba(0,0,0,.09);
        --radius:        10px;
        /* semantic aliases */
        --paper:         var(--bg-body);
        --ink:           var(--text-dark);
        --ink-2:         #3a4a6b;
        --ink-3:         var(--text-muted);
    }
 
    [data-theme="dark"] {
        --bg-body:     #0f172a;
        --white:       #1e293b;
        --card-bg:     #1e293b;
        --text-dark:   #e2e8f0;
        --text-muted:  #64748b;
        --border:      #334155;
        --panel-title: #e2e8f0;
        --input-bg:    #0f172a;
        --input-border:#334155;
        --input-color: #e2e8f0;
        --modal-bg:    #1e293b;
        --modal-header:#0f172a;
        --primary-l:   #1e3a5f;
        --red-l:       #2d1520;
        --shadow-sm:   0 1px 6px rgba(0,0,0,.25);
        --shadow:      0 4px 20px rgba(0,0,0,.4);
        --ink-2:       #94a3b8;
        --ink-3:       #64748b;
        --paper:       #0f172a;
        --ink:         #e2e8f0;
    }
 
    /* ── BASE ── */
    #ba-page {
        font-family: 'Sora', sans-serif;
        background: var(--bg-body);
        color: var(--text-dark);
        min-height: 100vh;
    }
    .ba-wrap { max-width: 1440px; margin: 0 auto; padding: 28px 22px 70px; }
 
    /* ── TOPBAR ── */
    .ba-topbar {
        display: flex; align-items: flex-start; justify-content: space-between;
        gap: 16px; flex-wrap: wrap; margin-bottom: 24px;
    }
    .ba-breadcrumb {
        font-size: .72rem; color: var(--ink-3);
        display: flex; align-items: center; gap: 5px; margin-bottom: 6px;
    }
    .ba-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 600; }
    .ba-page-title {
        font-size: 1.5rem; font-weight: 800; color: var(--ink);
        display: flex; align-items: center; gap: 10px; letter-spacing: -.4px;
    }
    .ba-page-title .title-badge {
        font-size: .65rem; font-weight: 700; letter-spacing: .6px;
        text-transform: uppercase; padding: 4px 10px;
        background: var(--primary-l); color: var(--primary);
        border-radius: 30px; border: 1px solid rgba(26,115,232,.2);
    }
    .ba-actions { display: flex; gap: 8px; flex-wrap: wrap; }
 
    /* ── BUTTONS ── */
    .btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 20px; border-radius: var(--radius);
        font-family: 'Sora', sans-serif; font-size: .82rem; font-weight: 600;
        cursor: pointer; border: none; transition: all .18s; white-space: nowrap;
        text-decoration: none;
    }
    .btn:hover { transform: translateY(-1px); }
    .btn-primary { background: var(--primary); color: #fff; box-shadow: 0 2px 12px rgba(26,115,232,.3); }
    .btn-primary:hover { background: var(--primary-d); box-shadow: 0 4px 18px rgba(26,115,232,.4); color: #fff; }
    .btn-success { background: var(--green); color: #fff; box-shadow: 0 2px 12px rgba(0,200,150,.25); }
    .btn-success:hover { background: #00a87e; color: #fff; }
    .btn-ghost {
        background: var(--white); color: var(--ink-2);
        border: 1.5px solid var(--border);
    }
    .btn-ghost:hover { border-color: var(--primary); color: var(--primary); background: var(--white); }
    .btn-danger {
        background: var(--red-l); color: var(--red);
        border: 1.5px solid rgba(255,77,109,.2);
    }
    .btn-danger:hover { background: var(--red); color: #fff; border-color: var(--red); }
    .btn-sm { padding: 6px 14px; font-size: .76rem; }
    .btn-icon {
        width: 34px; height: 34px; padding: 0;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px;
    }
 
    /* ── LAYOUT GRID ── */
    .ba-grid {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 20px; align-items: start;
    }
    @media (max-width: 1060px) { .ba-grid { grid-template-columns: 1fr; } }
 
    /* ── CARDS ── */
    .ba-card {
        background: var(--white);
        border-radius: var(--card-radius);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .ba-card + .ba-card { margin-top: 16px; }
    .ba-card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 20px; border-bottom: 1px solid var(--border);
        background: var(--sidebar-bg);
    }
    .ba-card-header h2 {
        font-size: .9rem; font-weight: 700; color: #fff;
        display: flex; align-items: center; gap: 8px;
    }
    .ba-card-header h2 i { color: var(--accent); font-size: .85rem; }
    .ba-card-header span { color: #6b8cc4; }
    .ba-card-body { padding: 20px; }
 
    /* ── FORMS ── */
    .fg { margin-bottom: 14px; }
    .fg:last-child { margin-bottom: 0; }
    .fg label {
        display: block; font-size: .74rem; font-weight: 600;
        color: var(--ink-2); margin-bottom: 5px;
    }
    .fg label .req { color: var(--red); margin-left: 2px; }
    .fg label .opt { font-size: .63rem; color: var(--ink-3); font-weight: 400; margin-left: 4px; }
    .fg-input, .fg-select, .fg-textarea {
        width: 100%;
        border: 1.5px solid var(--input-border);
        border-radius: 8px; padding: 9px 12px;
        font-family: 'Sora', sans-serif; font-size: .85rem;
        color: var(--input-color);
        background: var(--input-bg);
        outline: none;
        transition: border-color .18s, box-shadow .18s;
    }
    .fg-input::placeholder,
    .fg-textarea::placeholder { color: var(--ink-3); }
    .fg-input:focus, .fg-select:focus, .fg-textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(26,115,232,.12);
    }
    .fg-select {
        appearance: none; cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%237a82a8' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 11px center;
        padding-right: 30px;
    }
    .fg-textarea { resize: vertical; min-height: 76px; line-height: 1.6; }
    .fg-hint {
        font-size: .68rem; color: var(--ink-3);
        margin-top: 4px; display: flex; align-items: center; gap: 4px;
    }
    .fg-hint i { font-size: .62rem; color: var(--primary); }
 
    .g2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .g3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; }
    @media (max-width: 600px) { .g2, .g3 { grid-template-columns: 1fr; } }
 
    /* ── PROVIDER HEADER ── */
    .provider-bar {
        background: linear-gradient(135deg, var(--sidebar-bg) 0%, #1e2d5a 100%);
        border-radius: 10px; padding: 16px 18px; margin-bottom: 18px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px;
    }
    .provider-name { font-size: 1rem; font-weight: 800; color: #fff; letter-spacing: .3px; }
    .provider-name span { color: var(--accent); }
    .provider-sub  { font-size: .55rem; letter-spacing: 3px; color: #6b8cc4; font-weight: 500; margin-top: 1px; }
    .provider-meta { text-align: right; font-size: .7rem; color: #90b4e4; line-height: 1.75; }
 
    /* section divider inside cards */
    .section-divider {
        font-size: .68rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .5px; color: var(--ink-3);
        margin-bottom: 10px; padding-bottom: 5px;
        border-bottom: 1px dashed var(--border);
    }
 
    /* ── TECH RATE CARD ── */
    .tech-grid {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;
    }
    @media (max-width: 680px) { .tech-grid { grid-template-columns: repeat(2, 1fr); } }
    .tech-chip {
        border: 1.5px solid var(--border);
        border-radius: 9px; padding: 10px 11px;
        cursor: pointer; transition: all .16s;
        position: relative; overflow: hidden;
        background: var(--white);
    }
    .tech-chip::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
        background: var(--border); border-radius: 0 2px 2px 0; transition: background .16s;
    }
    .tech-chip:hover {
        border-color: var(--primary); transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }
    .tech-chip:hover::before { background: var(--primary); }
    .tech-chip.active { border-color: var(--primary); background: var(--primary-l); }
    .tech-chip.active::before { background: var(--primary); }
    .tech-chip-icon { font-size: .9rem; margin-bottom: 3px; }
    .tech-chip-name { font-size: .74rem; font-weight: 700; color: var(--ink); }
    .tech-chip-rate { font-size: .65rem; color: var(--primary); font-weight: 600; margin-top: 1px; }
 
    /* ── DEVELOPER ROWS ── */
    .dev-list { display: flex; flex-direction: column; gap: 10px; }
    .dev-row {
        background: var(--bg-body);
        border: 1.5px solid var(--border);
        border-radius: 10px; padding: 14px 16px;
        transition: border-color .18s;
    }
    .dev-row:hover { border-color: rgba(26,115,232,.35); }
    .dev-row-head {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 12px;
    }
    .dev-row-title {
        display: flex; align-items: center; gap: 8px;
        font-size: .84rem; font-weight: 700; color: var(--ink);
    }
    .dev-num {
        width: 22px; height: 22px; border-radius: 50%;
        background: var(--primary); color: #fff;
        font-size: .62rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .dev-row-cost {
        font-size: .9rem; font-weight: 800; color: var(--primary);
        font-variant-numeric: tabular-nums;
    }
    .dev-fields { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 10px; align-items: end; }
    @media (max-width: 640px) { .dev-fields { grid-template-columns: 1fr 1fr; } }
    .dev-field-lbl {
        font-size: .68rem; font-weight: 600;
        color: var(--ink-3); margin-bottom: 4px;
    }
    .rate-badge {
        padding: 9px 12px; border-radius: 8px;
        border: 1.5px solid var(--border);
        background: var(--primary-l);
        font-size: .84rem; font-weight: 700;
        color: var(--primary); font-family: 'DM Mono', monospace;
        display: flex; align-items: center; gap: 5px; white-space: nowrap;
    }
    .rate-badge .rate-lock { font-size: .62rem; color: var(--ink-3); }
    .add-dev-row {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        padding: 12px; border-radius: 9px;
        border: 2px dashed var(--border);
        background: transparent; color: var(--ink-3);
        cursor: pointer; font-family: 'Sora', sans-serif;
        font-size: .8rem; font-weight: 600;
        width: 100%; margin-top: 10px; transition: all .18s;
    }
    .add-dev-row:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-l); }
    .dev-empty {
        text-align: center; padding: 28px; color: var(--ink-3); font-size: .82rem;
    }
    .dev-empty i { font-size: 2rem; color: var(--border); display: block; margin-bottom: 8px; }
 
    /* ── SIGNATURE ── */
    .sig-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media (max-width: 600px) { .sig-grid { grid-template-columns: 1fr; } }
    .sig-box {
        border: 2px dashed var(--border);
        border-radius: 10px; padding: 16px;
        text-align: center; transition: border-color .18s;
        background: var(--white);
    }
    .sig-box:hover { border-color: var(--primary); }
    .sig-box-label {
        font-size: .68rem; font-weight: 700; color: var(--ink-3);
        text-transform: uppercase; letter-spacing: .4px; margin-bottom: 10px;
    }
    .sig-canvas {
        width: 100%; height: 60px; cursor: crosshair;
        border-radius: 5px; touch-action: none;
        background: var(--input-bg);
        border: 1px solid var(--border);
    }
    .sig-name { font-size: .82rem; font-weight: 700; color: var(--ink); margin-top: 8px; }
    .sig-sub  { font-size: .7rem; color: var(--ink-3); }
 
    /* ── TERMS PREVIEW ── */
    .terms-preview {
        background: var(--bg-body);
        border-radius: 8px; border: 1px solid var(--border);
        padding: 14px 16px; max-height: 260px; overflow-y: auto;
        font-size: .76rem; line-height: 1.75; color: var(--ink-3);
    }
    .terms-preview h4 {
        font-size: .78rem; font-weight: 700; color: var(--ink-2);
        margin-bottom: 4px; margin-top: 10px;
    }
    .terms-preview h4:first-child { margin-top: 0; }
    .terms-preview p { margin-bottom: 5px; }
    .terms-preview::-webkit-scrollbar { width: 4px; }
    .terms-preview::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }
 
    /* ── STATUS STRIP ── */
    .status-strip {
        display: flex; gap: 12px; flex-wrap: wrap;
        padding: 10px 14px;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 10px; margin-bottom: 20px;
        box-shadow: var(--shadow-sm);
    }
    .si { display: flex; align-items: center; gap: 5px; font-size: .72rem; color: var(--ink-3); }
    .si i { font-size: .68rem; color: var(--primary); }
    .si strong { color: var(--ink); font-weight: 700; }
 
    /* ── BILLING SIDEBAR ── */
    .ba-sidebar { position: sticky; top: 84px; }
 
    .bill-card {
        background: var(--white);
        border-radius: var(--card-radius);
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    .bill-header {
        background: var(--sidebar-bg);
        padding: 16px 20px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .bill-header h3 {
        font-size: .9rem; font-weight: 700; color: #fff;
        display: flex; align-items: center; gap: 8px; margin: 0;
    }
    .bill-header h3 i { color: var(--accent); }
    .bill-inv { font-size: .68rem; color: #6b8cc4; font-weight: 600; }
 
    .bill-body { padding: 16px; }
 
    .bill-pills { display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; margin-bottom: 14px; }
    .bill-pill {
        background: var(--bg-body);
        border: 1px solid var(--border);
        border-radius: 8px; padding: 8px 6px; text-align: center;
    }
    .bill-pill-val { font-size: .92rem; font-weight: 800; color: var(--ink); }
    .bill-pill-lbl { font-size: .58rem; color: var(--ink-3); font-weight: 500; margin-top: 1px; }
 
    .bill-section-lbl {
        font-size: .63rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .5px; color: var(--ink-3);
        margin-bottom: 7px; padding-bottom: 4px;
        border-bottom: 1px dashed var(--border);
    }
    .bill-line {
        display: flex; align-items: flex-start; justify-content: space-between;
        padding: 3.5px 0; font-size: .78rem; gap: 8px;
    }
    .bill-line-lbl {
        color: var(--ink-3); display: flex; align-items: center;
        gap: 5px; flex: 1;
    }
    .bill-line-lbl i { font-size: .65rem; color: var(--primary); flex-shrink: 0; }
    .bill-line-val {
        font-weight: 700; color: var(--ink); white-space: nowrap;
        font-family: 'DM Mono', monospace; font-size: .76rem;
    }
    .bill-div { height: 1px; background: var(--border); margin: 10px 0; }
 
    .bill-dev-items { display: flex; flex-direction: column; gap: 6px; margin-bottom: 10px; }
    .bill-dev-item {
        background: var(--bg-body);
        border-radius: 8px; padding: 8px 11px;
        border: 1px solid var(--border);
    }
    .bill-dev-item-name { font-size: .76rem; font-weight: 700; color: var(--ink); }
    .bill-dev-item-meta { font-size: .65rem; color: var(--ink-3); margin-top: 2px; }
    .bill-dev-item-cost {
        font-size: .82rem; font-weight: 800; color: var(--primary);
        margin-top: 3px; font-family: 'DM Mono', monospace;
    }
 
    .bill-total-box {
        background: var(--primary-l);
        border: 1px solid rgba(26,115,232,.18);
        border-radius: 10px; padding: 14px 16px; margin-top: 10px;
    }
    .bill-total-lbl {
        font-size: .65rem; font-weight: 700; color: var(--ink-3);
        margin-bottom: 3px; text-transform: uppercase; letter-spacing: .4px;
    }
    .bill-total-val {
        font-size: 1.85rem; font-weight: 800; color: var(--primary);
        font-family: 'DM Mono', monospace; letter-spacing: -.5px;
    }
    .bill-total-sub { font-size: .65rem; color: var(--ink-3); margin-top: 3px; }
 
    .meter-track {
        height: 5px; background: var(--border);
        border-radius: 3px; margin-top: 12px; overflow: hidden;
    }
    .meter-fill { height: 100%; border-radius: 3px; transition: width .4s, background .4s; }
    .meter-labels {
        display: flex; justify-content: space-between;
        font-size: .63rem; color: var(--ink-3); margin-top: 4px;
    }
 
    .bill-actions {
        display: flex; flex-direction: column; gap: 7px;
        padding: 14px 16px; border-top: 1px solid var(--border);
    }
 
    /* ── QUICK RATE REF ── */
    .rate-ref-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 4px 0; border-bottom: 1px solid var(--border); font-size: .73rem;
    }
    .rate-ref-row:last-child { border-bottom: none; }
    .rate-ref-name { font-weight: 600; color: var(--ink); }
    .rate-ref-val  { color: var(--primary); font-weight: 700; font-family: 'DM Mono', monospace; }
    .rate-ref-hint { font-size: .68rem; color: var(--ink-3); margin-bottom: 8px; }
 
    /* ── LOADING / SPINNER ── */
    .calc-loading { opacity: .5; pointer-events: none; }
    .spinner { animation: spin .8s linear infinite; display: inline-block; }
    @keyframes spin { to { transform: rotate(360deg); } }
 
    /* ── TOAST ── */
    .toast-stack { position: fixed; bottom: 22px; right: 22px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; }
    .toast {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 10px; padding: 11px 15px;
        display: flex; align-items: center; gap: 8px;
        box-shadow: var(--shadow); font-size: .8rem;
        color: var(--ink);
        animation: toastIn .18s ease; max-width: 300px;
        transition: opacity .4s;
    }
    @keyframes toastIn { from { opacity:0; transform:translateX(10px); } to { opacity:1; transform:none; } }
    .toast i { font-size: .88rem; flex-shrink: 0; }
</style>
<div id="ba-page">
<div class="ba-wrap">

{{-- TOP BAR --}}
<div class="ba-topbar">
  <div>
    <div class="ba-breadcrumb">
      <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
      <i class="fas fa-chevron-right" style="font-size:.5rem;"></i>
      <a href="{{ route('billing.index') }}">Agreements</a>
      <i class="fas fa-chevron-right" style="font-size:.5rem;"></i>
      <span>New Agreement</span>
    </div>
    <div class="ba-page-title">
      <i class="fas fa-file-signature" style="color:var(--primary);font-size:1.2rem;"></i>
      Create Agreement
      <span class="title-badge">New</span>
    </div>
  </div>
  <div class="ba-actions">
    <button class="btn btn-ghost" id="btnSaveDraft" type="button">
      <i class="fas fa-save"></i> Save Draft
    </button>
    <button class="btn btn-success" id="btnSave" type="button">
      <i class="fas fa-check"></i> Save Agreement
    </button>
  </div>
</div>

{{-- STATUS STRIP --}}
<div class="status-strip">
  <div class="si"><i class="fas fa-file-invoice"></i> Invoice: <strong id="si-inv">{{ $nextNo }}</strong></div>
  <div class="si"><i class="fas fa-users"></i> Developers: <strong id="si-devs">0</strong></div>
  <div class="si"><i class="fas fa-dollar-sign"></i> Monthly Total: <strong id="si-monthly">—</strong></div>
  <div class="si"><i class="fas fa-calendar-alt"></i> Working Days: <strong>22/mo</strong></div>
  <div class="si"><i class="fas fa-clock"></i> Hours/Month: <strong id="si-hours">—</strong></div>
  <div class="si"><i class="fas fa-hourglass-half"></i> Duration: <strong id="si-dur">—</strong></div>
</div>

{{-- MAIN FORM --}}
<form id="agreementForm" novalidate>
@csrf
<div class="ba-grid">

  {{-- ════ LEFT ════ --}}
  <div>

    {{-- AGREEMENT PARTIES --}}
    <div class="ba-card">
      <div class="ba-card-header">
        <h2><i class="fas fa-handshake"></i> Agreement Parties</h2>
        <span style="font-size:.7rem;color:var(--ink-3);font-weight:600;" id="inv-display">#{{ $nextNo }}</span>
      </div>
      <div class="ba-card-body">
        <div class="provider-bar">
          <div>
            <div class="provider-name">KAWACH TECHNOLOGY</div>
            <div class="provider-sub">PRIVATE LIMITED</div>
          </div>
          <div class="provider-meta">
            <div>📧 contracts@kawachtech.com</div>
            <div>🌐 www.kawachtech.com</div>
            <div>📍 India</div>
          </div>
        </div>

        <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--ink-3);margin-bottom:10px;padding-bottom:5px;border-bottom:1px dashed var(--border);">Client Information</div>

        <div class="g2">
          <div class="fg">
            <label>Company / Client Name <span class="req">*</span></label>
            <input type="text" class="fg-input" name="client_name" id="clientName" placeholder="Acme Corp Pvt Ltd" required autocomplete="off"/>
          </div>
          <div class="fg">
            <label>Contact Person <span class="req">*</span></label>
            <input type="text" class="fg-input" name="client_contact" id="clientContact" placeholder="John Smith" required autocomplete="off"/>
          </div>
          <div class="fg">
            <label>Email Address <span class="req">*</span></label>
            <input type="email" class="fg-input" name="client_email" placeholder="john@acmecorp.com" required/>
          </div>
          <div class="fg">
            <label>Phone Number <span class="opt">(optional)</span></label>
            <input type="text" class="fg-input" name="client_phone" placeholder="+1 234 567 8900"/>
          </div>
        </div>
        <div class="fg">
          <label>Client Address <span class="opt">(optional)</span></label>
          <textarea class="fg-textarea" name="client_address" rows="2" placeholder="Street, City, State, Country, ZIP"></textarea>
        </div>
        <div class="g3">
          <div class="fg">
            <label>Invoice Number</label>
            <input type="text" class="fg-input" name="invoice_no" id="invoiceNo" value="{{ $nextNo }}" required/>
          </div>
          <div class="fg">
            <label>Agreement Date</label>
            <input type="date" class="fg-input" name="agreement_date" id="agreementDate" value="{{ date('Y-m-d') }}" required/>
          </div>
          <div class="fg">
            <label>Contract Start</label>
            <input type="date" class="fg-input" name="contract_start" id="contractStart" value="{{ date('Y-m-d') }}" required/>
          </div>
        </div>
      </div>
    </div>

    {{-- PROJECT DETAILS --}}
    <div class="ba-card">
      <div class="ba-card-header"><h2><i class="fas fa-project-diagram"></i> Project Details</h2></div>
      <div class="ba-card-body">
        <div class="g2">
          <div class="fg">
            <label>Project Name <span class="req">*</span></label>
            <input type="text" class="fg-input" name="project_name" placeholder="e.g. E-Commerce Platform" required/>
          </div>
          <div class="fg">
            <label>Project Type</label>
            <select class="fg-select" name="project_type">
              <option value="">— Select —</option>
              @foreach(['Web Application','Mobile App','API / Backend','E-Commerce Platform','SaaS Product','Enterprise Software','Custom Software','UI/UX Design','Cloud Infrastructure','AI / ML Solution'] as $pt)
              <option>{{ $pt }}</option>
              @endforeach
            </select>
          </div>
          <div class="fg">
            <label>Contract Duration</label>
            <select class="fg-select calc-trigger" name="duration_months" id="durationMonths">
              @foreach([1=>'1 Month', 2=>'2 Months', 3=>'3 Months', 6=>'6 Months', 12=>'12 Months (1 Year)', 24=>'24 Months (2 Years)'] as $v => $l)
              <option value="{{ $v }}" @if($v==3) selected @endif>{{ $l }}</option>
              @endforeach
            </select>
          </div>
          <div class="fg">
            <label>Daily Working Hours</label>
            <select class="fg-select calc-trigger" name="daily_hours" id="dailyHours">
              <option value="4">4 hrs/day (Part-time)</option>
              <option value="6">6 hrs/day</option>
              <option value="8" selected>8 hrs/day (Full-time)</option>
              <option value="10">10 hrs/day</option>
            </select>
          </div>
        </div>
        <div class="fg">
          <label>Project Scope / Description <span class="opt">(optional)</span></label>
          <textarea class="fg-textarea" name="project_scope" rows="3" placeholder="Key deliverables, modules, and objectives…"></textarea>
        </div>
      </div>
    </div>

    {{-- TECHNOLOGY RATE CARD --}}
    <div class="ba-card">
      <div class="ba-card-header">
        <h2><i class="fas fa-tags"></i> Technology Rate Card</h2>
        <span style="font-size:.7rem;color:var(--ink-3);">Click to add developer</span>
      </div>
      <div class="ba-card-body">
        <div class="tech-grid" id="techGrid">
          @foreach($techRates as $tech => $rateCents)
          @php $icon = $techIcons[$tech] ?? '💻'; $rate = $rateCents / 100; @endphp
          <div class="tech-chip" data-tech="{{ $tech }}" data-rate="{{ $rate }}" data-icon="{{ $icon }}">
            <div class="tech-chip-icon">{{ $icon }}</div>
            <div class="tech-chip-name">{{ $tech }}</div>
            <div class="tech-chip-rate">${{ number_format($rate, 0) }}/hr</div>
          </div>
          @endforeach
        </div>
        <div class="fg-hint" style="margin-top:12px;">
          <i class="fas fa-info-circle"></i>
          Rates are whitelisted server-side. All totals are calculated by the backend — they cannot be altered in-browser.
        </div>
      </div>
    </div>

    {{-- DEVELOPER ROSTER --}}
    <div class="ba-card">
      <div class="ba-card-header">
        <h2><i class="fas fa-users-cog"></i> Developer Roster</h2>
        <button class="btn btn-ghost btn-sm" type="button" id="addDevBtn">
          <i class="fas fa-plus"></i> Add Developer
        </button>
      </div>
      <div class="ba-card-body">
        <div class="dev-list" id="devList"></div>
        <div class="dev-empty" id="devEmpty">
          <i class="fas fa-user-plus"></i>
          No developers added. Click a tech card above or use the button.
        </div>
        <button class="add-dev-row" type="button" id="addDevBottom">
          <i class="fas fa-plus-circle"></i> Add Another Developer
        </button>
      </div>
    </div>

    {{-- PAYMENT TERMS --}}
    <div class="ba-card">
      <div class="ba-card-header"><h2><i class="fas fa-credit-card"></i> Payment & Billing Terms</h2></div>
      <div class="ba-card-body">
        <div class="g3">
          <div class="fg">
            <label>Payment Cycle</label>
            <select class="fg-select" name="payment_cycle">
              <option value="monthly">Monthly</option>
              <option value="biweekly">Bi-weekly</option>
              <option value="weekly">Weekly</option>
              <option value="milestone">On Milestone</option>
            </select>
          </div>
          <div class="fg">
            <label>Payment Due</label>
            <select class="fg-select" name="payment_due_days">
              <option value="7">Net 7 days</option>
              <option value="15">Net 15 days</option>
              <option value="30" selected>Net 30 days</option>
              <option value="45">Net 45 days</option>
            </select>
          </div>
          <div class="fg">
            <label>Late Fee (%/month)</label>
            <input type="number" class="fg-input calc-trigger" name="late_fee_pct" value="1.5" step="0.1" min="0" max="10"/>
          </div>
        </div>
        <div class="g2">
          <div class="fg">
            <label>Advance / Deposit (%)</label>
            <input type="number" class="fg-input calc-trigger" name="advance_pct" id="advancePct" value="25" min="0" max="100"/>
            <div class="fg-hint"><i class="fas fa-info-circle"></i> % of Month 1 billed upfront</div>
          </div>
          <div class="fg">
            <label>Tax / GST (%)</label>
            <input type="number" class="fg-input calc-trigger" name="tax_pct" id="taxPct" value="18" min="0" max="50"/>
          </div>
        </div>
        <div class="fg">
          <label>Payment Methods Accepted</label>
          <input type="text" class="fg-input" name="payment_methods" value="Bank Transfer (SWIFT/NEFT), PayPal, Wise, Stripe"/>
        </div>
        <div class="fg">
          <label>Currency</label>
          <select class="fg-select calc-trigger" name="currency" id="currency">
            <option value="USD" selected>USD — US Dollar ($)</option>
            <option value="EUR">EUR — Euro (€)</option>
            <option value="GBP">GBP — British Pound (£)</option>
            <option value="INR">INR — Indian Rupee (₹)</option>
            <option value="CAD">CAD — Canadian Dollar (CA$)</option>
            <option value="AUD">AUD — Australian Dollar (A$)</option>
          </select>
        </div>
      </div>
    </div>

    {{-- TERMS --}}
    <div class="ba-card">
      <div class="ba-card-header"><h2><i class="fas fa-file-contract"></i> Agreement Terms & Conditions</h2></div>
      <div class="ba-card-body">
        <div class="terms-preview">
          <h4>1. ENGAGEMENT & SCOPE OF WORK</h4>
          <p>Kawach Technology Private Limited ("Company") agrees to provide dedicated software development resources ("Developers") to the Client as specified in this Agreement.</p>
          <h4>2. DEVELOPER RESOURCES</h4>
          <p>The Client shall have access to the agreed number of dedicated developers during standard business hours (Monday–Friday). Developers work 5 days per week (22 working days/month).</p>
          <h4>3. BILLING & PAYMENT</h4>
          <p>Billing is calculated on an hourly basis per developer at the rates in the Rate Schedule. Invoices are issued at the beginning of each billing cycle. A late payment fee applies on overdue amounts. An advance payment is required before work commences.</p>
          <h4>4. INTELLECTUAL PROPERTY</h4>
          <p>Upon full payment, all work product, code, designs, and deliverables shall be assigned exclusively to the Client. The Company retains no rights after full payment.</p>
          <h4>5. CONFIDENTIALITY & NON-DISCLOSURE</h4>
          <p>Both parties agree to maintain strict confidentiality regarding all proprietary information for 3 years after termination.</p>
          <h4>6. NON-SOLICITATION</h4>
          <p>During the term and for 12 months thereafter, the Client agrees not to directly recruit any developer of the Company who worked on the Client's project. A finder's fee of 3 months' billing applies if violated.</p>
          <h4>7. TERMINATION</h4>
          <p>Either party may terminate with 30 days' written notice. The Client pays for all work completed to the termination date.</p>
          <h4>8. LIABILITY LIMITATION</h4>
          <p>The Company's total liability shall not exceed the total fees paid in the 3 months preceding the claim.</p>
          <h4>9. DISPUTE RESOLUTION</h4>
          <p>Disputes shall be resolved through good-faith negotiation, then binding arbitration under Indian Arbitration & Conciliation Act, 1996.</p>
          <h4>10. GOVERNING LAW</h4>
          <p>This Agreement is governed by the laws of India.</p>
          <h4>11. FORCE MAJEURE</h4>
          <p>Neither party shall be liable for delays caused by circumstances beyond reasonable control.</p>
          <h4>12. ENTIRE AGREEMENT</h4>
          <p>This Agreement constitutes the entire agreement between the parties and supersedes all prior negotiations.</p>
        </div>
      </div>
    </div>

    {{-- CUSTOM CLAUSES --}}
    <div class="ba-card">
      <div class="ba-card-header"><h2><i class="fas fa-plus-circle"></i> Custom Clauses <span style="font-size:.65rem;font-weight:500;color:var(--ink-3);margin-left:6px;">(Optional)</span></h2></div>
      <div class="ba-card-body">
        <div class="fg">
          <label>Additional Terms / Special Conditions</label>
          <textarea class="fg-textarea" name="custom_clauses" rows="4" placeholder="e.g. Source code delivered via GitHub. Weekly status calls every Monday. Bug warranty 30 days post-launch…"></textarea>
        </div>
      </div>
    </div>

    {{-- SIGNATURES --}}
    <div class="ba-card">
      <div class="ba-card-header"><h2><i class="fas fa-signature"></i> Digital Signatures</h2></div>
      <div class="ba-card-body">
        <div class="sig-grid">
          <div>
            <div class="sig-box">
              <div class="sig-box-label">Company — Kawach Technology</div>
              <canvas class="sig-canvas" id="companySig" width="300" height="60"></canvas>
              <button class="btn btn-ghost btn-sm" type="button" onclick="clearSig('company')" style="margin-top:8px;">
                <i class="fas fa-eraser"></i> Clear
              </button>
              <div class="sig-name">Kawach Technology Pvt Ltd</div>
              <div class="sig-sub">Authorized Signatory</div>
            </div>
          </div>
          <div>
            <div class="sig-box">
              <div class="sig-box-label">Client Representative</div>
              <canvas class="sig-canvas" id="clientSig" width="300" height="60"></canvas>
              <button class="btn btn-ghost btn-sm" type="button" onclick="clearSig('client')" style="margin-top:8px;">
                <i class="fas fa-eraser"></i> Clear
              </button>
              <div class="sig-name" id="clientSigName">Client Name</div>
              <div class="sig-sub" id="clientSigCompany">Client Company</div>
            </div>
          </div>
        </div>
        {{-- Hidden inputs to carry signature data --}}
        <input type="hidden" name="company_signature" id="companySigData"/>
        <input type="hidden" name="client_signature" id="clientSigData"/>
      </div>
    </div>

  </div>{{-- /left --}}

  {{-- ════ RIGHT SIDEBAR ════ --}}
  <div class="ba-sidebar">
    <div class="bill-card">
      <div class="bill-header">
        <h3><i class="fas fa-receipt"></i> Billing Summary</h3>
        <span class="bill-inv" id="billInvNo">{{ $nextNo }}</span>
      </div>
      <div class="bill-body">

        {{-- Pills --}}
        <div class="bill-pills">
          <div class="bill-pill">
            <div class="bill-pill-val">22</div>
            <div class="bill-pill-lbl">Work Days</div>
          </div>
          <div class="bill-pill">
            <div class="bill-pill-val" id="bpHours">176</div>
            <div class="bill-pill-lbl">Hrs/Month</div>
          </div>
          <div class="bill-pill">
            <div class="bill-pill-val" id="bpDevs">0</div>
            <div class="bill-pill-lbl">Developers</div>
          </div>
          <div class="bill-pill">
            <div class="bill-pill-val" id="bpMonths">3</div>
            <div class="bill-pill-lbl">Months</div>
          </div>
        </div>

        <div class="bill-section-lbl">Developer Breakdown</div>
        <div class="bill-dev-items" id="billDevItems">
          <div style="text-align:center;padding:14px;color:var(--ink-3);font-size:.76rem;">
            <i class="fas fa-user-plus" style="display:block;font-size:1.4rem;color:var(--border);margin-bottom:5px;"></i>
            Add developers to see breakdown
          </div>
        </div>

        <div class="bill-div"></div>
        <div class="bill-section-lbl">Monthly Invoice</div>
        <div class="bill-line">
          <span class="bill-line-lbl"><i class="fas fa-users"></i> Dev Cost (pre-tax)</span>
          <span class="bill-line-val" id="bSubtotal">—</span>
        </div>
        <div class="bill-line">
          <span class="bill-line-lbl"><i class="fas fa-percent"></i> Tax / GST (<span id="bTaxPct">18</span>%)</span>
          <span class="bill-line-val" id="bTax">—</span>
        </div>
        <div class="bill-line" style="font-weight:800;">
          <span class="bill-line-lbl" style="color:var(--ink);font-weight:700;"><i class="fas fa-calendar-alt"></i> Monthly Total</span>
          <span class="bill-line-val" style="color:var(--primary);" id="bMonthly">—</span>
        </div>

        <div class="bill-div"></div>
        <div class="bill-section-lbl">Advance Payment</div>
        <div class="bill-line">
          <span class="bill-line-lbl"><i class="fas fa-hand-holding-usd"></i> Advance (<span id="bAdvPct">25</span>%)</span>
          <span class="bill-line-val" style="color:var(--amber);" id="bAdvance">—</span>
        </div>
        <div class="bill-line">
          <span class="bill-line-lbl"><i class="fas fa-balance-scale"></i> Balance after advance</span>
          <span class="bill-line-val" id="bBalance">—</span>
        </div>

        <div class="bill-div"></div>
        <div class="bill-section-lbl">Full Contract Value</div>
        <div class="bill-line">
          <span class="bill-line-lbl"><i class="fas fa-calculator"></i> Contract Total (pre-tax)</span>
          <span class="bill-line-val" id="bContractPre">—</span>
        </div>
        <div class="bill-line">
          <span class="bill-line-lbl"><i class="fas fa-plus-circle"></i> Total Tax</span>
          <span class="bill-line-val" id="bContractTax">—</span>
        </div>

        <div class="bill-total-box">
          <div class="bill-total-lbl">CONTRACT GRAND TOTAL</div>
          <div class="bill-total-val" id="bGrandTotal">—</div>
          <div class="bill-total-sub" id="bGrandSub">Add developers to calculate</div>
        </div>

        <div class="meter-track">
          <div class="meter-fill" id="bMeter" style="width:0%;background:var(--green);"></div>
        </div>
        <div class="meter-labels">
          <span>Project Scale</span>
          <span id="bMeterLbl">—</span>
        </div>
      </div>

      <div class="bill-actions">
        <button class="btn btn-success" id="sidebarSave" type="button" style="width:100%;">
          <i class="fas fa-check"></i> Save Agreement
        </button>
        <button class="btn btn-ghost" id="sidebarDraft" type="button" style="width:100%;">
          <i class="fas fa-save"></i> Save as Draft
        </button>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
          <button class="btn btn-ghost btn-sm" id="btnCopyLink" type="button">
            <i class="fas fa-link"></i> Copy Link
          </button>
          <button class="btn btn-primary btn-sm" id="btnEmailClient" type="button">
            <i class="fas fa-envelope"></i> Email Client
          </button>
        </div>
      </div>
    </div>

    {{-- Quick Rate Ref --}}
    <div class="ba-card" style="margin-top:14px;">
      <div class="ba-card-header"><h2><i class="fas fa-chart-bar"></i> Quick Rate Reference</h2></div>
      <div class="ba-card-body" style="padding:12px 16px;">
        <div style="font-size:.68rem;color:var(--ink-3);margin-bottom:8px;">5 days/wk · 8 hrs/day · ~22 days/month</div>
        @foreach(array_slice($techRates, 0, 8, true) as $tech => $rateCents)
        @php $r = $rateCents / 100; $mo = $r * 8 * 22; @endphp
        <div style="display:flex;justify-content:space-between;align-items:center;padding:4px 0;border-bottom:1px solid var(--border);font-size:.73rem;">
          <span style="font-weight:600;color:var(--ink);">{{ $techIcons[$tech] ?? '' }} {{ $tech }}</span>
          <span style="color:var(--primary);font-weight:700;font-family:'DM Mono',monospace;">${{ number_format($r, 0) }}/hr</span>
        </div>
        @endforeach
      </div>
    </div>
  </div>

</div>{{-- /ba-grid --}}
</form>

</div>{{-- /ba-wrap --}}
</div>{{-- /ba-page --}}

{{-- TOAST --}}
<div class="toast-stack" id="toastStack"></div>

@endsection

@push('scripts')
<script>
(function () {
  'use strict';

  // ── State ──────────────────────────────────────────────────────────────
  let developers = [];
  let devCounter = 0;
  let calcTimer = null;
  let savedUuid = null;

  const CALC_URL = '{{ route("billing.calculate") }}';
  const STORE_URL = '{{ route("billing.store") }}';
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

  // ── Toast ──────────────────────────────────────────────────────────────
  function toast(msg, color, icon) {
    color = color || '#2563eb'; icon = icon || 'fas fa-info-circle';
    const el = document.createElement('div');
    el.className = 'toast';
    el.innerHTML = `<i class="${icon}" style="color:${color};"></i><span>${msg}</span>`;
    document.getElementById('toastStack').appendChild(el);
    setTimeout(() => el.style.opacity = '0', 2800);
    setTimeout(() => el.remove(), 3200);
  }

  // ── Tech chip click → add developer ───────────────────────────────────
  document.getElementById('techGrid').addEventListener('click', function (e) {
    const chip = e.target.closest('.tech-chip');
    if (!chip) return;
    addDeveloper(chip.dataset.tech, chip.dataset.icon);
  });

  document.getElementById('addDevBtn').addEventListener('click', () => addDeveloper('React.js', '⚛️'));
  document.getElementById('addDevBottom').addEventListener('click', () => addDeveloper('React.js', '⚛️'));

  // ── Tech options (from server-rendered data) ───────────────────────────
  const TECH_OPTIONS = @json(array_keys($techRates));

  function buildTechOptions(selected) {
    return TECH_OPTIONS.map(t =>
      `<option value="${t}" ${t === selected ? 'selected' : ''}>${t}</option>`
    ).join('');
  }

  // ── Add developer row ──────────────────────────────────────────────────
  function addDeveloper(tech, icon) {
    devCounter++;
    const id = 'dv' + devCounter;
    developers.push({ id, tech, qty: 1 });

    document.getElementById('devEmpty').style.display = 'none';

    const row = document.createElement('div');
    row.className = 'dev-row';
    row.id = 'row_' + id;
    row.innerHTML = `
      <div class="dev-row-head">
        <div class="dev-row-title">
          <div class="dev-num">${devCounter}</div>
          <span id="icon_${id}">${icon}</span>
          <span id="lbl_${id}">${tech} Developer</span>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
          <div class="dev-row-cost" id="cost_${id}">—</div>
          <button class="btn btn-danger btn-icon btn-sm" type="button" data-id="${id}">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
      <div class="dev-fields">
        <div>
          <div class="dev-field-lbl">Technology / Role</div>
          <select class="fg-select dev-tech-sel" data-id="${id}">
            ${buildTechOptions(tech)}
          </select>
          <input type="hidden" name="developers[${id}][technology]" value="${tech}" id="inp_tech_${id}"/>
        </div>
        <div>
          <div class="dev-field-lbl">Qty (Developers)</div>
          <input type="number" class="fg-input dev-qty" data-id="${id}"
                 name="developers[${id}][quantity]" value="1" min="1" max="20"/>
        </div>
        <div>
          <div class="dev-field-lbl">Developer Name <span style="font-size:.6rem;color:var(--ink-3);">(optional)</span></div>
          <input type="text" class="fg-input dev-label" name="developers[${id}][label]" placeholder="e.g. John Doe"/>
        </div>
      </div>`;

    document.getElementById('devList').appendChild(row);

    // Tech select change
    row.querySelector('.dev-tech-sel').addEventListener('change', function () {
      const d = getDev(this.dataset.id);
      d.tech = this.value;
      document.getElementById('inp_tech_' + this.dataset.id).value = this.value;
      document.getElementById('lbl_' + this.dataset.id).textContent = this.value + ' Developer';
      scheduleCalc();
    });

    // Qty change
    row.querySelector('.dev-qty').addEventListener('input', function () {
      getDev(this.dataset.id).qty = Math.max(1, parseInt(this.value) || 1);
      scheduleCalc();
    });

    // Remove
    row.querySelector('[data-id]').addEventListener('click', function () {
      developers = developers.filter(d => d.id !== this.dataset.id);
      document.getElementById('row_' + this.dataset.id).remove();
      if (developers.length === 0) document.getElementById('devEmpty').style.display = '';
      scheduleCalc();
    });

    // Mark chip selected
    document.querySelectorAll('.tech-chip').forEach(c => {
      if (c.dataset.tech === tech) c.classList.add('active');
    });

    scheduleCalc();
    toast(`${icon} ${tech} developer added`, '#059669', 'fas fa-user-plus');
  }

  function getDev(id) { return developers.find(d => d.id === id); }

  // ── Schedule calc with debounce ────────────────────────────────────────
  function scheduleCalc() {
    clearTimeout(calcTimer);
    calcTimer = setTimeout(fetchCalc, 350);
  }

  // ── Fetch calculated totals from backend ───────────────────────────────
  async function fetchCalc() {
    if (developers.length === 0) {
      resetSummary(); return;
    }

    const payload = {
      developers: developers.map(d => ({ technology: d.tech, quantity: d.qty, label: '' })),
      daily_hours: parseInt(document.getElementById('dailyHours').value),
      duration_months: parseInt(document.getElementById('durationMonths').value),
      tax_pct: parseFloat(document.getElementById('taxPct').value) || 0,
      advance_pct: parseFloat(document.getElementById('advancePct').value) || 0,
      currency: document.getElementById('currency').value,
    };

    try {
      const res = await fetch(CALC_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(payload),
      });
      const data = await res.json();
      if (data.ok) updateSummary(data);
    } catch (e) { /* silent */ }
  }

  // ── Update sidebar summary ─────────────────────────────────────────────
  function updateSummary(d) {
    const months = parseInt(document.getElementById('durationMonths').value);
    const taxPct = document.getElementById('taxPct').value;
    const advPct = document.getElementById('advancePct').value;

    set('bpHours', d.monthly_hours);
    set('bpDevs', d.dev_count);
    set('bpMonths', months);
    set('bSubtotal', d.subtotal);
    set('bTaxPct', taxPct);
    set('bTax', d.tax);
    set('bMonthly', d.monthly_total);
    set('bAdvPct', advPct);
    set('bAdvance', d.advance);
    set('bBalance', d.balance);
    set('bContractPre', d.contract_pre);
    set('bContractTax', d.contract_tax);
    set('bGrandTotal', d.grand_total);
    set('bGrandSub', `Incl. ${taxPct}% tax · ${d.dev_count} dev${d.dev_count !== 1 ? 's' : ''} · ${months} months`);

    // Status strip
    set('si-monthly', d.monthly_total);
    set('si-hours', d.monthly_hours);
    set('si-devs', d.dev_count);
    set('si-dur', months + ' mo');

    // Dev items
    const container = document.getElementById('billDevItems');
    if (d.dev_lines && d.dev_lines.length) {
      container.innerHTML = d.dev_lines.map(dl => `
        <div class="bill-dev-item">
          <div class="bill-dev-item-name">${dl.icon} ${dl.label} ×${dl.qty}</div>
          <div class="bill-dev-item-meta">${dl.rate}/hr · ${dl.hours} hrs/mo · ${dl.qty} dev${dl.qty > 1 ? 's' : ''}</div>
          <div class="bill-dev-item-cost">${dl.monthly_cost}/mo</div>
        </div>`).join('');
    }

    // Row costs
    d.dev_lines.forEach((dl, i) => {
      const dev = developers[i];
      if (dev) {
        const el = document.getElementById('cost_' + dev.id);
        if (el) el.textContent = dl.monthly_cost;
      }
    });

    // Meter
    const raw = d.grand_total_raw;
    const pct = Math.min(100, (raw / 10000000) * 100);
    const meterEl = document.getElementById('bMeter');
    meterEl.style.width = pct + '%';
    meterEl.style.background = raw > 5000000 ? '#059669' : raw > 2000000 ? '#2563eb' : '#d97706';
    set('bMeterLbl', raw > 5000000 ? 'Enterprise' : raw > 2000000 ? 'Mid-Scale' : 'Small Project');
  }

  function resetSummary() {
    ['bSubtotal','bTax','bMonthly','bAdvance','bBalance','bContractPre','bContractTax','bGrandTotal'].forEach(id => set(id, '—'));
    set('bGrandSub', 'Add developers to calculate');
    set('bpDevs', 0); set('bpHours', '—');
    set('si-monthly', '—'); set('si-hours', '—');
    document.getElementById('billDevItems').innerHTML = `
      <div style="text-align:center;padding:14px;color:var(--ink-3);font-size:.76rem;">
        <i class="fas fa-user-plus" style="display:block;font-size:1.4rem;color:var(--border);margin-bottom:5px;"></i>
        Add developers to see breakdown
      </div>`;
    document.getElementById('bMeter').style.width = '0%';
  }

  function set(id, val) {
    const el = document.getElementById(id);
    if (el) el.textContent = val;
  }

  // ── Bind calc triggers ─────────────────────────────────────────────────
  document.querySelectorAll('.calc-trigger').forEach(el => {
    el.addEventListener('change', scheduleCalc);
    el.addEventListener('input',  scheduleCalc);
  });

  // ── Invoice no sync ────────────────────────────────────────────────────
  document.getElementById('invoiceNo').addEventListener('input', function () {
    set('inv-display', '#' + this.value);
    set('billInvNo', this.value);
    set('si-inv',  this.value);
  });

  // ── Client name → signature ────────────────────────────────────────────
  document.getElementById('clientContact').addEventListener('input', function () {
    set('clientSigName', this.value || 'Client Name');
  });
  document.getElementById('clientName').addEventListener('input', function () {
    set('clientSigCompany', this.value || 'Client Company');
  });

  // ── Signature canvases ─────────────────────────────────────────────────
  function initCanvas(canvasId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let down = false;
    ctx.strokeStyle = '#0c0f1a'; ctx.lineWidth = 1.8; ctx.lineCap = 'round';

    const pos = (e) => {
      const r = canvas.getBoundingClientRect();
      const s = e.touches ? e.touches[0] : e;
      return [s.clientX - r.left, s.clientY - r.top];
    };
    canvas.addEventListener('mousedown', e => { down = true; ctx.beginPath(); ctx.moveTo(...pos(e)); });
    canvas.addEventListener('mousemove', e => { if (!down) return; ctx.lineTo(...pos(e)); ctx.stroke(); });
    canvas.addEventListener('mouseup', () => { down = false; captureSignatures(); });
    canvas.addEventListener('mouseleave', () => { down = false; });
    canvas.addEventListener('touchstart', e => { e.preventDefault(); down = true; ctx.beginPath(); ctx.moveTo(...pos(e)); }, { passive: false });
    canvas.addEventListener('touchmove', e => { e.preventDefault(); if (!down) return; ctx.lineTo(...pos(e)); ctx.stroke(); }, { passive: false });
    canvas.addEventListener('touchend', () => { down = false; captureSignatures(); });
  }

  function captureSignatures() {
    document.getElementById('companySigData').value = document.getElementById('companySig').toDataURL();
    document.getElementById('clientSigData').value  = document.getElementById('clientSig').toDataURL();
  }

  window.clearSig = function (type) {
    const id = type === 'company' ? 'companySig' : 'clientSig';
    const c  = document.getElementById(id);
    c.getContext('2d').clearRect(0, 0, c.width, c.height);
    captureSignatures();
  };

  initCanvas('companySig');
  initCanvas('clientSig');

  // ── Collect form data ──────────────────────────────────────────────────
  function collectFormData(status) {
    captureSignatures();
    const form = document.getElementById('agreementForm');
    const fd   = new FormData(form);
    const obj  = {};
    fd.forEach((v, k) => { obj[k] = v; });

    // Rebuild developers as clean array
    delete obj._token;
    const devArr = [];
    developers.forEach((d, i) => {
      devArr.push({
        technology: d.tech,
        quantity: d.qty,
        label: form.querySelector(`[name="developers[${d.id}][label]"]`)?.value?.trim() || '',
      });
    });
    obj.developers = devArr;
    obj.status = status;
    return obj;
  }

  // ── Save ──────────────────────────────────────────────────────────────
  async function save(status) {
    if (developers.length === 0) {
      toast('Please add at least one developer', '#dc2626', 'fas fa-exclamation-circle'); return;
    }
    const cn = document.querySelector('[name="client_name"]').value.trim();
    if (!cn) {
      toast('Client name is required', '#dc2626', 'fas fa-exclamation-circle'); return;
    }

    const payload = collectFormData(status);
    const spinner = '<i class="fas fa-circle-notch spinner"></i>';

    try {
      const btn = document.getElementById('btnSave');
      btn.innerHTML = spinner + ' Saving…';

      const res = await fetch(STORE_URL, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body:    JSON.stringify(payload),
      });
      const data = await res.json();

      if (data.ok) {
        savedUuid = data.id;
        toast('Agreement saved! Invoice #' + data.invoice_no, '#059669', 'fas fa-check-circle');
        // Enable PDF and email buttons
        document.getElementById('btnEmailClient').dataset.uuid = savedUuid;
        setTimeout(() => {
          window.location.href = '/billing/' + savedUuid;
        }, 1200);
      } else {
        toast('Save failed — ' + (data.error || 'unknown error'), '#dc2626', 'fas fa-times-circle');
        btn.innerHTML = '<i class="fas fa-check"></i> Save Agreement';
      }
    } catch (e) {
      toast('Network error. Please try again.', '#dc2626', 'fas fa-times-circle');
    }
  }

  document.getElementById('btnSave').addEventListener('click',     () => save('draft'));
  document.getElementById('sidebarSave').addEventListener('click', () => save('draft'));
  document.getElementById('btnSaveDraft').addEventListener('click',  () => save('draft'));
  document.getElementById('sidebarDraft').addEventListener('click',  () => save('draft'));

  // ── Copy link ──────────────────────────────────────────────────────────
  document.getElementById('btnCopyLink').addEventListener('click', () => {
    navigator.clipboard.writeText(window.location.href).catch(() => {});
    toast('Link copied', '#2563eb', 'fas fa-link');
  });

  // ── Email client (only after save) ────────────────────────────────────
  document.getElementById('btnEmailClient').addEventListener('click', async function () {
    if (!savedUuid) {
      toast('Please save the agreement first', '#d97706', 'fas fa-exclamation-triangle');
      return;
    }
    this.innerHTML = '<i class="fas fa-circle-notch spinner"></i> Sending…';
    try {
      const res = await fetch(`/billing/${savedUuid}/send-email`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({}),
      });
      const data = await res.json();
      if (data.ok) {
        toast(data.message, '#059669', 'fas fa-envelope');
      } else {
        toast(data.error || 'Email failed', '#dc2626', 'fas fa-times-circle');
      }
    } catch (e) {
      toast('Network error', '#dc2626', 'fas fa-times-circle');
    }
    this.innerHTML = '<i class="fas fa-envelope"></i> Email Client';
  });

  // ── Initial calc ──────────────────────────────────────────────────────
  scheduleCalc();

})();
</script>
@endpush