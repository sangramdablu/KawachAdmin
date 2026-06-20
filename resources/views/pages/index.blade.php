@extends('layouts.master')
@section('title', 'Pages — KawachTech Software Solutions')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap');

:root {
  --primary:#1a73e8;--bg-body:#eef2f9;--card-bg:#fff;--card-radius:14px;
  --text-dark:#1a1a2e;--text-muted:#8a9bb5;--border:#e2e8f0;
  --green:#00c896;--red:#ff4d6d;--yellow:#ffb830;
  --input-bg:#fff;--input-border:#e2e8f0;--input-color:#1a1a2e;
  --modal-bg:#fff;--modal-header:#f4f6fb;
}
[data-theme="dark"]{
  --bg-body:#0f172a;--card-bg:#1e293b;--text-dark:#e2e8f0;--text-muted:#64748b;
  --border:#334155;--input-bg:#0f172a;--input-border:#334155;--input-color:#e2e8f0;
  --modal-bg:#1e293b;--modal-header:#0f172a;
}

/* ── BASE ── */
#pagesIndex{font-family:'Open Sans',sans-serif;color:var(--text-dark);background:var(--bg-body);min-height:100vh;transition:background .3s,color .3s;}
#pagesIndex *{box-sizing:border-box;}
.pi-wrap{max-width:1400px;margin:0 auto;padding:26px 22px 60px;}

/* ── TOP BAR ── */
.pi-topbar{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:22px;}
.pi-breadcrumb{font-size:.74rem;color:var(--text-muted);display:flex;align-items:center;gap:6px;margin-bottom:4px;}
.pi-breadcrumb a{color:var(--primary);text-decoration:none;font-weight:600;}
.pi-title{font-family:'Nunito',sans-serif;font-weight:900;font-size:1.55rem;color:var(--text-dark);}
.pi-topbar-actions{display:flex;gap:8px;flex-wrap:wrap;align-items:center;}

/* ── BUTTONS ── */
.btn-pi{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:8px;font-size:.84rem;font-weight:700;cursor:pointer;border:none;transition:all .2s;font-family:'Open Sans',sans-serif;white-space:nowrap;text-decoration:none;}
.btn-pi:hover{transform:translateY(-1px);}
.btn-pi-primary{background:var(--primary);color:#fff;}
.btn-pi-primary:hover{background:#1558b0;box-shadow:0 4px 14px rgba(26,115,232,.35);color:#fff;}
.btn-pi-success{background:var(--green);color:#fff;}
.btn-pi-success:hover{background:#00a87c;color:#fff;}
.btn-pi-outline{background:var(--card-bg);color:var(--text-dark);border:1.5px solid var(--border);}
.btn-pi-outline:hover{border-color:var(--primary);color:var(--primary);}
.btn-pi-danger{background:#fff5f7;color:var(--red);border:1.5px solid #ffc0cc;}
.btn-pi-danger:hover{background:var(--red);color:#fff;border-color:var(--red);}
.btn-sm{padding:6px 13px;font-size:.77rem;}
.btn-xs{padding:4px 9px;font-size:.72rem;border-radius:6px;}

/* ── STAT CARDS ── */
.pi-stats-row{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:22px;}
@media(max-width:1100px){.pi-stats-row{grid-template-columns:repeat(3,1fr);}}
@media(max-width:640px){.pi-stats-row{grid-template-columns:repeat(2,1fr);}}
.pi-stat-card{background:var(--card-bg);border-radius:var(--card-radius);border:1px solid var(--border);padding:16px 18px;box-shadow:0 2px 12px rgba(26,115,232,.06);cursor:pointer;transition:all .2s;position:relative;overflow:hidden;}
.pi-stat-card::before{content:'';position:absolute;top:0;left:0;width:3px;height:100%;background:var(--border);border-radius:0 2px 2px 0;transition:background .2s;}
.pi-stat-card:hover{box-shadow:0 4px 20px rgba(26,115,232,.12);transform:translateY(-2px);}
.pi-stat-card.active{border-color:var(--primary);box-shadow:0 4px 20px rgba(26,115,232,.18);}
.pi-stat-card.active::before{background:var(--primary);}
.stat-card-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;}
.stat-icon{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0;}
.stat-lbl{font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.4px;margin-bottom:3px;}
.stat-val{font-family:'Nunito',sans-serif;font-weight:900;font-size:1.8rem;color:var(--text-dark);line-height:1.1;}
.stat-sub{font-size:.7rem;color:var(--text-muted);margin-top:4px;display:flex;align-items:center;gap:4px;}

/* ── TYPE TAB BAR ── */
.pi-type-bar{background:var(--card-bg);border:1px solid var(--border);border-radius:var(--card-radius);padding:6px 10px;margin-bottom:18px;display:flex;align-items:center;gap:4px;flex-wrap:wrap;box-shadow:0 2px 10px rgba(26,115,232,.05);transition:background .3s;}
.pi-type-tab{display:flex;align-items:center;gap:7px;padding:8px 14px;border-radius:8px;font-size:.82rem;font-weight:700;cursor:pointer;border:none;background:transparent;color:var(--text-muted);transition:all .18s;font-family:'Open Sans',sans-serif;}
.pi-type-tab i{font-size:.85rem;}
.pi-type-tab .tab-count{background:var(--border);color:var(--text-muted);font-size:.62rem;font-weight:800;padding:1px 6px;border-radius:8px;min-width:18px;text-align:center;transition:all .18s;}
.pi-type-tab:hover{background:var(--modal-header);color:var(--text-dark);}
.pi-type-tab.active{background:var(--primary);color:#fff;box-shadow:0 2px 10px rgba(26,115,232,.3);}
.pi-type-tab.active .tab-count{background:rgba(255,255,255,.25);color:#fff;}
.pi-tab-divider{width:1px;height:20px;background:var(--border);margin:0 4px;flex-shrink:0;}

/* ── FILTER BAR ── */
.pi-filter-bar{background:var(--card-bg);border:1px solid var(--border);border-radius:var(--card-radius);padding:12px 16px;margin-bottom:18px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;box-shadow:0 2px 10px rgba(26,115,232,.05);transition:background .3s;}
.pi-status-pills{display:flex;gap:6px;flex-wrap:wrap;}
.pi-status-pill{padding:5px 12px;border-radius:20px;font-size:.76rem;font-weight:700;cursor:pointer;border:1.5px solid var(--border);background:var(--input-bg);color:var(--text-muted);transition:all .15s;font-family:'Open Sans',sans-serif;}
.pi-status-pill:hover{border-color:var(--primary);color:var(--primary);}
.pi-status-pill.active{background:var(--primary);border-color:var(--primary);color:#fff;}
.pi-search-wrap{flex:1;min-width:200px;max-width:340px;position:relative;}
.pi-search-wrap>i{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:.82rem;pointer-events:none;}
.pi-search-input{width:100%;border:1.5px solid var(--input-border);border-radius:8px;padding:8px 36px 8px 34px;font-size:.84rem;color:var(--input-color);background:var(--input-bg);outline:none;font-family:'Open Sans',sans-serif;transition:border-color .2s;}
.pi-search-input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(26,115,232,.1);}
.pi-search-clear{position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:.75rem;display:none;padding:3px;}
.pi-select{border:1.5px solid var(--input-border);border-radius:8px;padding:8px 30px 8px 12px;font-size:.82rem;color:var(--input-color);background:var(--input-bg);outline:none;cursor:pointer;font-family:'Open Sans',sans-serif;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238a9bb5' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;transition:border-color .2s;}
.pi-select:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(26,115,232,.1);}
.pi-view-toggle{display:flex;gap:4px;margin-left:auto;}
.pi-view-btn{width:34px;height:34px;border-radius:7px;display:flex;align-items:center;justify-content:center;border:1.5px solid var(--border);background:var(--input-bg);color:var(--text-muted);cursor:pointer;font-size:.82rem;transition:all .15s;}
.pi-view-btn.active{background:var(--primary);border-color:var(--primary);color:#fff;}
.pi-view-btn:hover:not(.active){border-color:var(--primary);color:var(--primary);}

/* ── BULK BAR ── */
.pi-bulk-bar{display:none;background:linear-gradient(90deg,#e8f1fd,#dbeeff);border:1px solid rgba(26,115,232,.2);border-radius:10px;padding:10px 16px;margin-bottom:14px;align-items:center;gap:12px;flex-wrap:wrap;animation:bulkSlide .15s ease;}
[data-theme="dark"] .pi-bulk-bar{background:rgba(26,115,232,.12);}
@keyframes bulkSlide{from{opacity:0;transform:translateY(-6px);}to{opacity:1;transform:none;}}
.pi-bulk-bar.show{display:flex;}
.bulk-count{font-family:'Nunito',sans-serif;font-weight:900;font-size:.88rem;color:var(--primary);}
.bulk-actions{display:flex;gap:7px;flex-wrap:wrap;}

/* ── GRID ── */
.pi-grid{display:none;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:18px;}
.pi-grid.active{display:grid;}
.pi-card{background:var(--card-bg);border-radius:var(--card-radius);border:1px solid var(--border);box-shadow:0 2px 12px rgba(26,115,232,.06);overflow:hidden;transition:all .22s;display:flex;flex-direction:column;position:relative;}
.pi-card:hover{box-shadow:0 8px 32px rgba(26,115,232,.14);transform:translateY(-3px);border-color:rgba(26,115,232,.3);}
.pi-card.selected{border-color:var(--primary);box-shadow:0 0 0 3px rgba(26,115,232,.15);}
.pi-card-thumb{height:160px;position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.pi-card-thumb img{width:100%;height:100%;object-fit:cover;transition:transform .35s;}
.pi-card:hover .pi-card-thumb img{transform:scale(1.04);}
.thumb-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:2.4rem;color:rgba(255,255,255,.4);}
.pi-card-thumb-overlay{position:absolute;inset:0;background:rgba(0,0,0,0);transition:background .22s;display:flex;align-items:center;justify-content:center;gap:8px;}
.pi-card:hover .pi-card-thumb-overlay{background:rgba(0,0,0,.45);}
.thumb-overlay-btn{display:flex;align-items:center;gap:5px;padding:7px 14px;border-radius:7px;border:none;background:rgba(255,255,255,.92);color:var(--text-dark);font-size:.78rem;font-weight:700;cursor:pointer;opacity:0;transform:translateY(8px);transition:all .2s;font-family:'Open Sans',sans-serif;}
.pi-card:hover .thumb-overlay-btn{opacity:1;transform:none;}
.thumb-overlay-btn:hover{background:var(--primary);color:#fff;}
.pi-card-check{position:absolute;top:10px;left:10px;z-index:5;width:20px;height:20px;border-radius:6px;border:2px solid rgba(255,255,255,.7);background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .15s;font-size:.7rem;color:#fff;}
.pi-card-check.checked{background:var(--primary);border-color:var(--primary);}
.pi-card-status-badge{position:absolute;top:10px;right:10px;z-index:5;font-size:.65rem;font-weight:800;padding:3px 9px;border-radius:20px;text-transform:uppercase;letter-spacing:.3px;}
.badge-published{background:rgba(0,200,150,.9);color:#fff;}
.badge-draft{background:rgba(255,184,48,.9);color:#fff;}
.badge-pending{background:rgba(33,150,243,.9);color:#fff;}
.badge-scheduled{background:rgba(107,70,255,.9);color:#fff;}
.pi-type-badge{position:absolute;bottom:10px;left:10px;z-index:5;display:flex;align-items:center;gap:5px;font-size:.68rem;font-weight:800;padding:3px 9px;border-radius:20px;background:rgba(0,0,0,.55);color:#fff;backdrop-filter:blur(3px);}
.pi-card-body{padding:16px 16px 12px;flex:1;display:flex;flex-direction:column;}
.pi-card-title{font-family:'Nunito',sans-serif;font-weight:900;font-size:.95rem;color:var(--text-dark);margin-bottom:5px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.pi-card-slug{font-size:.72rem;color:var(--primary);font-weight:600;margin-bottom:8px;display:flex;align-items:center;gap:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.pi-card-excerpt{font-size:.78rem;color:var(--text-muted);line-height:1.55;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;flex:1;margin-bottom:10px;}
.pi-card-meta{display:flex;align-items:center;gap:10px;font-size:.7rem;color:var(--text-muted);flex-wrap:wrap;}
.pi-card-meta i{font-size:.65rem;color:var(--primary);}
.pi-card-footer{padding:10px 16px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:8px;background:var(--modal-header);transition:background .3s;}
.pi-card-footer-left{display:flex;gap:6px;}
.pi-action-btn{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:6px;font-size:.74rem;font-weight:700;cursor:pointer;border:1.5px solid var(--border);background:var(--card-bg);color:var(--text-muted);transition:all .15s;font-family:'Open Sans',sans-serif;}
.pi-action-btn:hover{border-color:var(--primary);color:var(--primary);}
.pi-action-btn.del:hover{border-color:var(--red);color:var(--red);}
.pi-toggle{display:flex;align-items:center;gap:6px;cursor:pointer;}
.pi-toggle-track{width:34px;height:18px;border-radius:10px;background:var(--border);position:relative;transition:background .2s;flex-shrink:0;}
.pi-toggle-track.on{background:var(--green);}
.pi-toggle-thumb{position:absolute;top:2px;left:2px;width:14px;height:14px;border-radius:50%;background:#fff;transition:left .2s;box-shadow:0 1px 3px rgba(0,0,0,.15);}
.pi-toggle-track.on .pi-toggle-thumb{left:18px;}
.pi-toggle-label{font-size:.7rem;font-weight:700;color:var(--text-muted);}

/* ── LIST VIEW ── */
.pi-list{display:none;flex-direction:column;}
.pi-list.active{display:flex;}
.pi-list-row{background:var(--card-bg);border:1px solid var(--border);border-bottom:none;padding:14px 18px;display:flex;align-items:center;gap:14px;transition:background .15s;position:relative;}
.pi-list-row:first-child{border-radius:var(--card-radius) var(--card-radius) 0 0;}
.pi-list-row:last-child{border-bottom:1px solid var(--border);border-radius:0 0 var(--card-radius) var(--card-radius);}
.pi-list-row:only-child{border-radius:var(--card-radius);}
.pi-list-row:hover{background:rgba(26,115,232,.03);}
.pi-list-row.selected{background:rgba(26,115,232,.06);}
.list-check{width:18px;height:18px;border-radius:5px;border:1.5px solid var(--border);background:var(--input-bg);display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;font-size:.65rem;color:transparent;transition:all .15s;}
.list-check.checked{background:var(--primary);border-color:var(--primary);color:#fff;}
.list-thumb{width:52px;height:42px;border-radius:7px;overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:rgba(255,255,255,.4);}
.list-thumb img{width:100%;height:100%;object-fit:cover;}
.list-body{flex:1;min-width:0;}
.list-title{font-family:'Nunito',sans-serif;font-weight:800;font-size:.88rem;color:var(--text-dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:3px;}
.list-slug{font-size:.72rem;color:var(--primary);font-weight:600;}
.list-meta{display:flex;align-items:center;gap:12px;flex-shrink:0;}
.list-badge{font-size:.65rem;font-weight:800;padding:3px 9px;border-radius:20px;text-transform:uppercase;white-space:nowrap;}
.list-type-chip{display:flex;align-items:center;gap:4px;font-size:.72rem;font-weight:700;color:var(--text-muted);white-space:nowrap;}
.list-date{font-size:.72rem;color:var(--text-muted);white-space:nowrap;}
.list-actions{display:flex;gap:6px;flex-shrink:0;}

/* ── EMPTY ── */
.pi-empty{display:none;flex-direction:column;align-items:center;justify-content:center;padding:70px 24px;text-align:center;background:var(--card-bg);border-radius:var(--card-radius);border:1px solid var(--border);}
.pi-empty.show{display:flex;}
.pi-empty-icon{font-size:3.5rem;color:var(--border);margin-bottom:18px;}
.pi-empty h3{font-family:'Nunito',sans-serif;font-weight:900;font-size:1.2rem;color:var(--text-dark);margin-bottom:8px;}
.pi-empty p{font-size:.85rem;color:var(--text-muted);margin-bottom:20px;}

/* ── PAGINATION ── */
.pi-pagination{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-top:22px;}
.pi-page-info{font-size:.8rem;color:var(--text-muted);}
.pi-page-btns{display:flex;gap:4px;}
.pi-page-btn{width:34px;height:34px;border-radius:7px;display:flex;align-items:center;justify-content:center;border:1.5px solid var(--border);background:var(--card-bg);color:var(--text-muted);cursor:pointer;font-size:.82rem;font-weight:700;transition:all .15s;font-family:'Nunito',sans-serif;}
.pi-page-btn:hover{border-color:var(--primary);color:var(--primary);}
.pi-page-btn.active{background:var(--primary);border-color:var(--primary);color:#fff;}
.pi-page-btn:disabled{opacity:.4;pointer-events:none;}

/* ── MODAL ── */
.pi-modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:900;align-items:center;justify-content:center;backdrop-filter:blur(3px);}
.pi-modal-overlay.show{display:flex;}
.pi-modal{background:var(--modal-bg);border-radius:14px;max-width:440px;width:95%;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.22);animation:piModalPop .2s ease;}
@keyframes piModalPop{from{opacity:0;transform:scale(.94);}to{opacity:1;transform:scale(1);}}
.pi-modal-header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;background:var(--modal-header);}
.pi-modal-header h3{font-family:'Nunito',sans-serif;font-weight:900;font-size:1rem;color:var(--text-dark);margin:0;display:flex;align-items:center;gap:8px;}
.pi-modal-header h3 i{color:var(--red);}
.pi-modal-close{width:28px;height:28px;border-radius:50%;border:none;background:transparent;color:var(--text-muted);cursor:pointer;font-size:.8rem;display:flex;align-items:center;justify-content:center;transition:background .15s;}
.pi-modal-close:hover{background:var(--border);}
.pi-modal-body{padding:20px;font-size:.88rem;color:var(--text-dark);line-height:1.65;}
.pi-modal-footer{padding:14px 20px;border-top:1px solid var(--border);display:flex;gap:8px;justify-content:flex-end;}

/* ── TOAST ── */
.pi-toast-stack{position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.pi-toast{background:var(--card-bg);border:1px solid var(--border);border-radius:10px;padding:11px 15px;display:flex;align-items:center;gap:9px;box-shadow:0 6px 24px rgba(0,0,0,.12);font-size:.81rem;color:var(--text-dark);pointer-events:all;animation:piToastIn .2s ease;max-width:320px;}
@keyframes piToastIn{from{opacity:0;transform:translateX(14px);}to{opacity:1;transform:none;}}
.pi-toast i{font-size:.9rem;flex-shrink:0;}

/* ── RESULT BAR ── */
.pi-result-bar{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:8px;}
.pi-result-count{font-size:.8rem;color:var(--text-muted);font-weight:600;}
.pi-result-count strong{color:var(--text-dark);}

/* ── SKELETON ── */
.pi-skeleton{background:linear-gradient(90deg,var(--border) 25%,var(--modal-header) 50%,var(--border) 75%);background-size:200% 100%;animation:piSkelAnim 1.4s ease infinite;border-radius:6px;}
@keyframes piSkelAnim{0%{background-position:200% 0;}100%{background-position:-200% 0;}}

/* ── LOADING OVERLAY ── */
.pi-loading{position:relative;min-height:200px;}
.pi-loading::after{content:'';position:absolute;inset:0;background:rgba(var(--bg-body),.5);display:flex;align-items:center;justify-content:center;border-radius:var(--card-radius);}
.pi-spinner{display:flex;align-items:center;justify-content:center;padding:40px;color:var(--text-muted);font-size:1.5rem;}

@media(max-width:768px){
  .pi-filter-bar{flex-direction:column;align-items:flex-start;}
  .pi-search-wrap{max-width:100%;width:100%;}
  .pi-view-toggle{margin-left:0;}
  .list-meta{display:none;}
  .pi-grid{grid-template-columns:1fr;}
}
</style>

<div id="pagesIndex">
<div class="pi-wrap">

  {{-- TOP BAR --}}
  <div class="pi-topbar">
    <div>
      <div class="pi-breadcrumb">
        <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
        <i class="fas fa-chevron-right" style="font-size:.52rem;"></i>
        <span>Pages</span>
      </div>
      <div class="pi-title">🏗️ All Pages</div>
    </div>
    <div class="pi-topbar-actions">
      {{-- <button class="btn-pi btn-pi-outline" id="btnBulkToggle">
        <i class="fas fa-tasks"></i> Bulk Actions
      </button> --}}
      @can('pages.create')
        <a href="{{ route('pages.create') }}" class="btn-pi btn-pi-primary" id="btnNewPage">
          <i class="fas fa-plus"></i> New Page
        </a>
      @endcan
    </div>
  </div>

  {{-- STAT CARDS --}}
  <div class="pi-stats-row">
    <div class="pi-stat-card active" data-stat-type="all">
      <div class="stat-card-top">
        <div class="stat-icon" style="background:#e8f1fd;color:var(--primary);"><i class="fas fa-layer-group"></i></div>
        <span class="stat-lbl">Total</span>
      </div>
      <div class="stat-val" id="statTotal">—</div>
      <div class="stat-sub"><i class="fas fa-file-alt"></i> All page types</div>
    </div>
    <div class="pi-stat-card" data-stat-type="published">
      <div class="stat-card-top">
        <div class="stat-icon" style="background:#d4f5ec;color:#00a87c;"><i class="fas fa-check-circle"></i></div>
        <span class="stat-lbl">Published</span>
      </div>
      <div class="stat-val" id="statPublished">—</div>
      <div class="stat-sub"><i class="fas fa-globe"></i> Live on site</div>
    </div>
    <div class="pi-stat-card" data-stat-type="draft">
      <div class="stat-card-top">
        <div class="stat-icon" style="background:#fff4d6;color:#b8860b;"><i class="fas fa-file-alt"></i></div>
        <span class="stat-lbl">Drafts</span>
      </div>
      <div class="stat-val" id="statDraft">—</div>
      <div class="stat-sub"><i class="fas fa-edit"></i> In progress</div>
    </div>
    <div class="pi-stat-card" data-stat-type="scheduled">
      <div class="stat-card-top">
        <div class="stat-icon" style="background:#ede9fe;color:#6d28d9;"><i class="fas fa-clock"></i></div>
        <span class="stat-lbl">Scheduled</span>
      </div>
      <div class="stat-val" id="statScheduled">—</div>
      <div class="stat-sub"><i class="fas fa-calendar-alt"></i> Queued</div>
    </div>
    <div class="pi-stat-card" data-stat-type="pending">
      <div class="stat-card-top">
        <div class="stat-icon" style="background:#ffe2e8;color:var(--red);"><i class="fas fa-hourglass-half"></i></div>
        <span class="stat-lbl">Pending</span>
      </div>
      <div class="stat-val" id="statPending">—</div>
      <div class="stat-sub"><i class="fas fa-user-clock"></i> Awaiting review</div>
    </div>
  </div>

  {{-- TYPE TABS --}}
  <div class="pi-type-bar">
    <button class="pi-type-tab active" data-type="service"><i class="fas fa-cogs"></i> Services <span class="tab-count" data-count-type="service">0</span></button>
    <button class="pi-type-tab" data-type="casestudy"><i class="fas fa-briefcase"></i> Case Studies <span class="tab-count" data-count-type="casestudy">0</span></button>
    <button class="pi-type-tab" data-type="team"><i class="fas fa-users"></i> Team <span class="tab-count" data-count-type="team">0</span></button>
    <button class="pi-type-tab" data-type="testimonial"><i class="fas fa-quote-left"></i> Testimonials <span class="tab-count" data-count-type="testimonial">0</span></button>
    <div class="pi-tab-divider"></div>
    <button class="pi-type-tab" data-type="faq"><i class="fas fa-question-circle"></i> FAQs <span class="tab-count" data-count-type="faq">0</span></button>
    <button class="pi-type-tab" data-type="portfolio"><i class="fas fa-th-large"></i> Portfolio <span class="tab-count" data-count-type="portfolio">0</span></button>
    <button class="pi-type-tab" data-type="blog"><i class="fas fa-pen-nib"></i> Blog Posts <span class="tab-count" data-count-type="blog">0</span></button>
    <button class="pi-type-tab" data-type="landing"><i class="fas fa-home"></i> Landing Pages <span class="tab-count" data-count-type="landing">0</span></button>
  </div>

  {{-- FILTER BAR --}}
  <div class="pi-filter-bar">
    <div class="pi-status-pills">
      <button class="pi-status-pill active" data-status="all">All</button>
      <button class="pi-status-pill" data-status="published">Published</button>
      <button class="pi-status-pill" data-status="draft">Draft</button>
      <button class="pi-status-pill" data-status="pending">Pending</button>
      <button class="pi-status-pill" data-status="scheduled">Scheduled</button>
    </div>
    <div class="pi-search-wrap">
      <i class="fas fa-search"></i>
      <input type="text" class="pi-search-input" id="piSearch" placeholder="Search pages…" autocomplete="off"/>
      <button class="pi-search-clear" id="piSearchClear"><i class="fas fa-times"></i></button>
    </div>
    <select class="pi-select" id="piSort">
      <option value="newest">Newest first</option>
      <option value="oldest">Oldest first</option>
      <option value="title_asc">Title A–Z</option>
      <option value="title_desc">Title Z–A</option>
      <option value="published">Published first</option>
      <option value="featured">Featured first</option>
    </select>
    <select class="pi-select" id="piFeatured">
      <option value="">All Pages</option>
      <option value="1">Featured Only</option>
      <option value="0">Non-featured</option>
    </select>
    <div class="pi-view-toggle">
      <button class="pi-view-btn active" id="btnListView" title="List view"><i class="fas fa-list"></i></button>
      <button class="pi-view-btn" id="btnGridView" title="Grid view"><i class="fas fa-th-large"></i></button>
    </div>
  </div>

  {{-- BULK BAR --}}
  <div class="pi-bulk-bar" id="piBulkBar">
    <span class="bulk-count"><span id="bulkCountNum">0</span> selected</span>
    <div class="bulk-actions">
      <button class="btn-pi btn-pi-success btn-sm" id="bulkPublish"><i class="fas fa-rocket"></i> Publish</button>
      <button class="btn-pi btn-pi-outline btn-sm" id="bulkDraft"><i class="fas fa-file-alt"></i> Set Draft</button>
      <button class="btn-pi btn-pi-danger btn-sm" id="bulkDelete"><i class="fas fa-trash"></i> Delete</button>
    </div>
    <button class="btn-pi btn-pi-outline btn-sm" id="bulkClear" style="margin-left:auto;"><i class="fas fa-times"></i> Clear</button>
  </div>

  {{-- RESULT BAR --}}
  <div class="pi-result-bar">
    <div class="pi-result-count">
      Showing <strong id="resultFrom">—</strong>–<strong id="resultTo">—</strong>
      of <strong id="resultTotal">—</strong> pages
    </div>
    <div style="font-size:.76rem;color:var(--text-muted);" id="activeFilterLabel"></div>
  </div>

  {{-- CONTENT AREAS --}}
  <div id="piLoadingSpinner" class="pi-spinner" style="display:none;">
    <i class="fas fa-spinner fa-spin"></i>&nbsp; Loading…
  </div>

  <div class="pi-grid" id="piGridView"></div>
  <div class="pi-list" id="piListView"></div>

  <div class="pi-empty" id="piEmpty">
    <div class="pi-empty-icon"><i class="fas fa-layer-group"></i></div>
    <h3 id="piEmptyTitle">No pages found</h3>
    <p id="piEmptyMsg">No pages match your filters. Try a different type or search term.</p>
    <a href="{{ route('pages.create') }}" class="btn-pi btn-pi-primary" id="btnEmptyCreate">
      <i class="fas fa-plus"></i> Create First Page
    </a>
  </div>

  {{-- PAGINATION --}}
  <div class="pi-pagination" id="piPagination" style="display:none;">
    <div class="pi-page-info" id="piPageInfo">Page 1 of 1</div>
    <div class="pi-page-btns" id="piPageBtns"></div>
  </div>

</div>
</div>

{{-- DELETE MODAL --}}
<div class="pi-modal-overlay" id="deleteModal">
  <div class="pi-modal">
    <div class="pi-modal-header">
      <h3><i class="fas fa-exclamation-triangle"></i> Delete Page</h3>
      <button class="pi-modal-close" id="deleteModalClose"><i class="fas fa-times"></i></button>
    </div>
    <div class="pi-modal-body">
      <p>Are you sure you want to delete <strong id="deletePageTitle">"Untitled"</strong>?</p>
      <p style="margin-top:8px;font-size:.82rem;color:var(--text-muted);">
        This moves the page to trash. You can restore it later.
      </p>
    </div>
    <div class="pi-modal-footer">
      <button class="btn-pi btn-pi-outline btn-sm" id="deleteModalCancel">Cancel</button>
      <button class="btn-pi btn-pi-danger btn-sm" id="deleteModalConfirm">
        <i class="fas fa-trash"></i> Move to Trash
      </button>
    </div>
  </div>
</div>

<div class="pi-toast-stack" id="piToastStack"></div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(function () {

  /* ══════════════════════════════════════════════
     CONSTANTS
  ══════════════════════════════════════════════ */
  const CSRF     = '{{ csrf_token() }}';
  const ROUTES   = {
    index      : '{{ route("pages.index") }}',
    create     : '{{ route("pages.create") }}',
    bulkAction : '{{ route("pages.bulk") }}',
  };

  const TYPE_META = {
    service:     { icon:'fa-cogs',          label:'Service',      gradient:'linear-gradient(135deg,#1a3a6e,#1a73e8)' },
    casestudy:   { icon:'fa-briefcase',     label:'Case Study',   gradient:'linear-gradient(135deg,#0f2027,#203a43,#2c5364)' },
    team:        { icon:'fa-user',          label:'Team Member',  gradient:'linear-gradient(135deg,#134e5e,#71b280)' },
    testimonial: { icon:'fa-quote-left',    label:'Testimonial',  gradient:'linear-gradient(135deg,#373b44,#4286f4)' },
    faq:         { icon:'fa-question-circle',label:'FAQ',         gradient:'linear-gradient(135deg,#c94b4b,#4b134f)' },
    portfolio:   { icon:'fa-th-large',      label:'Portfolio',    gradient:'linear-gradient(135deg,#0f0c29,#302b63,#24243e)' },
    blog:        { icon:'fa-pen-nib',       label:'Blog Post',    gradient:'linear-gradient(135deg,#1a1a2e,#16213e,#0f3460)' },
    landing:     { icon:'fa-home',          label:'Landing Page', gradient:'linear-gradient(135deg,#005c97,#363795)' },
  };

  const STATUS_STYLE = {
    published : { bg:'rgba(0,200,150,.9)',  color:'#fff' },
    draft     : { bg:'rgba(255,184,48,.9)', color:'#fff' },
    pending   : { bg:'rgba(33,150,243,.9)', color:'#fff' },
    scheduled : { bg:'rgba(107,70,255,.9)', color:'#fff' },
  };

  /* ══════════════════════════════════════════════
     STATE
  ══════════════════════════════════════════════ */
  let state = {
    type      : 'service',
    status    : 'all',
    sort      : 'newest',
    search    : '',
    featured  : '',
    page      : 1,
    isGrid    : false,
    bulkMode  : false,
    selected  : new Set(),
    loading   : false,
    delId     : null,
    delTitle  : '',
    // Last fetched data — used to optimistically update toggles without re-fetching
    pages     : [],
  };

  /* ══════════════════════════════════════════════
     TOAST
  ══════════════════════════════════════════════ */
  function toast(msg, type) {
    const map = {
      success : { color:'var(--green)',   icon:'fas fa-check-circle'        },
      error   : { color:'var(--red)',     icon:'fas fa-times-circle'        },
      warning : { color:'var(--yellow)',  icon:'fas fa-exclamation-triangle' },
      info    : { color:'var(--primary)', icon:'fas fa-info-circle'         },
    };
    const cfg = map[type || 'info'];
    const $t = $(`<div class="pi-toast">
      <i class="${cfg.icon}" style="color:${cfg.color};"></i>
      <span>${msg}</span>
    </div>`);
    $('#piToastStack').append($t);
    setTimeout(() => $t.fadeOut(300, () => $t.remove()), 3500);
  }

  /* ══════════════════════════════════════════════
     DATE HELPERS
  ══════════════════════════════════════════════ */
  function timeAgo(iso) {
    if (!iso) return '—';
    const diff = Math.floor((Date.now() - new Date(iso)) / 1000);
    if (diff < 60)      return diff + 's ago';
    if (diff < 3600)    return Math.floor(diff / 60) + 'm ago';
    if (diff < 86400)   return Math.floor(diff / 3600) + 'h ago';
    if (diff < 2592000) return Math.floor(diff / 86400) + 'd ago';
    return new Date(iso).toLocaleDateString('en-US', { day:'numeric', month:'short', year:'numeric' });
  }

  /* ══════════════════════════════════════════════
     FETCH DATA FROM API
  ══════════════════════════════════════════════ */
  let fetchController = null;   // lets us cancel in-flight requests

  function fetchPages() {
    if (state.loading) return;
    state.loading = true;

    // Cancel any previous in-flight request
    if (fetchController) fetchController.abort();

    showSpinner();

    const params = {
      type    : state.type,
      status  : state.status !== 'all' ? state.status : undefined,
      search  : state.search || undefined,
      sort    : state.sort,
      featured: state.featured !== '' ? state.featured : undefined,
      page    : state.page,
    };

    // Remove undefined keys
    Object.keys(params).forEach(k => params[k] === undefined && delete params[k]);

    $.ajax({
      url     : ROUTES.index,
      method  : 'GET',
      data    : params,
      headers : { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      success : function (res) {
        state.loading = false;
        hideSpinner();

        if (!res.success) {
          toast('Failed to load pages.', 'error');
          return;
        }

        // Cache pages for optimistic UI (toggle)
        state.pages = res.pages.data || [];

        renderCounts(res.counts);
        renderPages(state.pages, res.meta);
      },
      error   : function (xhr) {
        state.loading = false;
        hideSpinner();
        if (xhr.statusText === 'abort') return;   // intentional cancel
        const msg = xhr.responseJSON?.message || 'Network error. Please try again.';
        toast(msg, 'error');
        showEmpty('Error loading pages', 'Something went wrong. Please refresh.');
      },
    });
  }

  function showSpinner() {
    $('#piLoadingSpinner').show();
    $('#piGridView, #piListView, #piEmpty').hide();
    $('#piPagination').hide();
  }

  function hideSpinner() {
    $('#piLoadingSpinner').hide();
    if (state.isGrid) {
      $('#piGridView').show();
      $('#piListView').hide();
    } else {
      $('#piListView').show();
      $('#piGridView').hide();
    }
  }

  /* ══════════════════════════════════════════════
     RENDER COUNTS
  ══════════════════════════════════════════════ */
  function renderCounts(counts) {
    $('#statTotal').text(counts.all ?? 0);
    $('#statPublished').text(counts.published ?? 0);
    $('#statDraft').text(counts.draft ?? 0);
    $('#statScheduled').text(counts.scheduled ?? 0);
    $('#statPending').text(counts.pending ?? 0);

    // Type tab badges
    const byType = counts.by_type || {};
    Object.keys(TYPE_META).forEach(t => {
      $(`.tab-count[data-count-type="${t}"]`).text(byType[t] ?? 0);
    });
  }

  /* ══════════════════════════════════════════════
     RENDER PAGES (grid or list)
  ══════════════════════════════════════════════ */
  function renderPages(pages, meta) {
    const meta_type = TYPE_META[state.type] || TYPE_META.service;

    // Result bar
    $('#resultFrom').text(meta.from ?? 0);
    $('#resultTo').text(meta.to ?? 0);
    $('#resultTotal').text(meta.total ?? 0);

    // Filter label
    let label = `<i class="fas ${meta_type.icon}" style="color:var(--primary);margin-right:4px;"></i> ${meta_type.label}s`;
    if (state.status !== 'all') label += ` · Status: <strong>${state.status}</strong>`;
    if (state.search)           label += ` · "<strong>${esc(state.search)}</strong>"`;
    $('#activeFilterLabel').html(label);

    if (!pages.length) {
      showEmpty(
        `No ${meta_type.label}s found`,
        state.search
          ? `No ${meta_type.label}s match "${state.search}". Try a different search.`
          : `No ${meta_type.label}s exist yet. Create your first one!`
      );
      return;
    }

    $('#piEmpty').removeClass('show').hide();

    if (state.isGrid) {
      renderGrid(pages, meta_type);
    } else {
      renderList(pages, meta_type);
    }

    renderPagination(meta.current_page, meta.last_page);
  }

  function esc(s) {
    return $('<div>').text(s).html();
  }

  function showEmpty(title, msg) {
    $('#piGridView').html('').removeClass('active');
    $('#piListView').html('').removeClass('active');
    $('#piPagination').hide();
    $('#piEmpty h3').text(title);
    $('#piEmpty #piEmptyMsg').text(msg);
    $('#piEmpty').addClass('show').show();
  }

  /* ── GRID ── */
  function renderGrid(pages, meta_type) {
    let html = '';
    pages.forEach(p => {
      const sel  = state.selected.has(p.id);
      const ss   = STATUS_STYLE[p.status] || STATUS_STYLE.draft;
      const isOn = p.status === 'published';
      html += `
      <div class="pi-card${sel ? ' selected' : ''}" data-id="${p.id}">
        <div class="pi-card-thumb" style="background:${meta_type.gradient};">
          ${p.featured_image
            ? `<img src="${esc(p.featured_image)}" alt="${esc(p.title)}" loading="lazy"/>`
            : `<div class="thumb-placeholder"><i class="fas ${meta_type.icon}"></i></div>`}
          <div class="pi-card-thumb-overlay">
            <button class="thumb-overlay-btn js-edit" data-url="${esc(p.edit_url)}">
              <i class="fas fa-edit"></i> Edit
            </button>
            <button class="thumb-overlay-btn js-view" data-url="${esc(p.view_url)}">
              <i class="fas fa-eye"></i> View
            </button>
          </div>
          <div class="pi-card-check${sel ? ' checked' : ''}" data-id="${p.id}">
            ${sel ? '<i class="fas fa-check"></i>' : ''}
          </div>
          <span class="pi-card-status-badge badge-${p.status}">${p.status}</span>
          <span class="pi-type-badge"><i class="fas ${meta_type.icon}"></i> ${meta_type.label}</span>
          ${p.is_featured ? `<span style="position:absolute;bottom:10px;right:10px;z-index:5;font-size:.65rem;padding:3px 9px;border-radius:20px;background:rgba(255,184,48,.9);color:#fff;font-weight:800;">⭐ Featured</span>` : ''}
        </div>
        <div class="pi-card-body">
          <div class="pi-card-title">${esc(p.title)}</div>
          <div class="pi-card-slug"><i class="fas fa-link"></i> /${esc(p.slug)}</div>
          <div class="pi-card-excerpt">${esc(p.meta_description || 'No description set.')}</div>
          <div class="pi-card-meta">
            <span><i class="fas fa-calendar-alt"></i> ${timeAgo(p.created_at)}</span>
            ${p.published_at && p.status === 'published' ? `<span><i class="fas fa-rocket"></i> ${timeAgo(p.published_at)}</span>` : ''}
          </div>
        </div>
        <div class="pi-card-footer">
          <div class="pi-card-footer-left">
            <button class="pi-action-btn js-edit" data-url="${esc(p.edit_url)}"><i class="fas fa-edit"></i> Edit</button>
            <button class="pi-action-btn del js-del" data-id="${p.id}" data-title="${esc(p.title)}"><i class="fas fa-trash"></i></button>
          </div>
          <div class="pi-toggle js-toggle" data-id="${p.id}" data-url="${esc(p.toggle_url)}" data-status="${p.status}" title="Toggle publish">
            <div class="pi-toggle-track${isOn ? ' on' : ''}"><div class="pi-toggle-thumb"></div></div>
            <span class="pi-toggle-label">${isOn ? 'Live' : 'Off'}</span>
          </div>
        </div>
      </div>`;
    });
    $('#piGridView').html(html).addClass('active').show();
    $('#piListView').html('').removeClass('active').hide();
  }

  /* ── LIST ── */
  function renderList(pages, meta_type) {
    let html = '';
    pages.forEach(p => {
      const sel  = state.selected.has(p.id);
      const ss   = STATUS_STYLE[p.status] || STATUS_STYLE.draft;
      html += `
      <div class="pi-list-row${sel ? ' selected' : ''}" data-id="${p.id}">
        <div class="list-check${sel ? ' checked' : ''}" data-id="${p.id}">
          ${sel ? '<i class="fas fa-check"></i>' : ''}
        </div>
        <div class="list-thumb" style="background:${meta_type.gradient};">
          ${p.featured_image ? `<img src="${esc(p.featured_image)}" alt="${esc(p.title)}" loading="lazy"/>` : `<i class="fas ${meta_type.icon}"></i>`}
        </div>
        <div class="list-body">
          <div class="list-title">
            ${esc(p.title)}
            ${p.is_featured ? `<span style="font-size:.62rem;background:#fff4d6;color:#b8860b;padding:1px 6px;border-radius:4px;font-weight:800;margin-left:5px;">⭐ Featured</span>` : ''}
          </div>
          <div class="list-slug"><i class="fas fa-link" style="font-size:.65rem;color:var(--primary);margin-right:3px;"></i>/${esc(p.slug)}</div>
        </div>
        <div class="list-meta">
          <span class="list-badge" style="background:${ss.bg};color:${ss.color};">${p.status}</span>
          <span class="list-type-chip"><i class="fas ${meta_type.icon}"></i> ${meta_type.label}</span>
          <span class="list-date"><i class="fas fa-calendar-alt" style="font-size:.65rem;color:var(--primary);margin-right:3px;"></i>${timeAgo(p.created_at)}</span>
        </div>
        <div class="list-actions">
          <button class="pi-action-btn js-edit" data-url="${esc(p.edit_url)}"><i class="fas fa-edit"></i> Edit</button>
          <button class="pi-action-btn js-view" data-url="${esc(p.view_url)}"><i class="fas fa-eye"></i></button>
          <button class="pi-action-btn del js-del" data-id="${p.id}" data-title="${esc(p.title)}"><i class="fas fa-trash"></i></button>
        </div>
      </div>`;
    });
    $('#piListView').html(html).addClass('active').show();
    $('#piGridView').html('').removeClass('active').hide();
  }

  /* ── PAGINATION ── */
  function renderPagination(page, last) {
    if (last <= 1) { $('#piPagination').hide(); return; }
    $('#piPagination').show();
    $('#piPageInfo').text(`Page ${page} of ${last}`);

    let btns = `<button class="pi-page-btn js-page" data-page="${page - 1}" ${page === 1 ? 'disabled' : ''}>
      <i class="fas fa-chevron-left"></i></button>`;

    const range = [];
    for (let i = Math.max(1, page - 2); i <= Math.min(last, page + 2); i++) range.push(i);

    if (range[0] > 1) {
      btns += `<button class="pi-page-btn js-page" data-page="1">1</button>`;
      if (range[0] > 2) btns += `<button class="pi-page-btn" disabled>…</button>`;
    }
    range.forEach(n => {
      btns += `<button class="pi-page-btn js-page${n === page ? ' active' : ''}" data-page="${n}">${n}</button>`;
    });
    if (range[range.length - 1] < last) {
      if (range[range.length - 1] < last - 1) btns += `<button class="pi-page-btn" disabled>…</button>`;
      btns += `<button class="pi-page-btn js-page" data-page="${last}">${last}</button>`;
    }

    btns += `<button class="pi-page-btn js-page" data-page="${page + 1}" ${page === last ? 'disabled' : ''}>
      <i class="fas fa-chevron-right"></i></button>`;

    $('#piPageBtns').html(btns);
  }

  /* ══════════════════════════════════════════════
     AJAX ACTIONS
  ══════════════════════════════════════════════ */

  // ── Toggle status ────────────────────────────
  function doToggle(id, url) {
    // Optimistic update in DOM
    const page = state.pages.find(p => p.id === id);

    $.ajax({
      url    : url,
      method : 'PATCH',
      data   : { _token: CSRF },
      success: function (res) {
        if (res.success) {
          const label  = res.status === 'published' ? 'Published ✅' : 'Set to Draft';
          toast(label, res.status === 'published' ? 'success' : 'warning');
          // Full refresh to get accurate counts + updated data
          fetchPages();
        }
      },
      error  : function () { toast('Failed to update status.', 'error'); fetchPages(); },
    });
  }

  // ── Delete ───────────────────────────────────
  function doDelete(id) {
    $.ajax({
      url    : `/pages/${id}`,
      method : 'DELETE',
      data   : { _token: CSRF },
      success: function (res) {
        $('#deleteModal').removeClass('show');
        $('#deleteModalConfirm').prop('disabled', false).html('<i class="fas fa-trash"></i> Move to Trash');
        if (res.success) {
          toast(res.message, 'success');
          // If current page is now empty, go back one
          if (state.pages.length === 1 && state.page > 1) state.page--;
          fetchPages();
        } else {
          toast(res.message || 'Delete failed.', 'error');
        }
      },
      error  : function (xhr) {
        $('#deleteModal').removeClass('show');
        $('#deleteModalConfirm').prop('disabled', false).html('<i class="fas fa-trash"></i> Move to Trash');
        toast(xhr.responseJSON?.message || 'Delete failed.', 'error');
      },
    });
  }

  // ── Bulk action ──────────────────────────────
  function doBulk(action) {
    if (!state.selected.size) return;

    const ids = [...state.selected];

    $.ajax({
      url    : ROUTES.bulkAction,
      method : 'POST',
      data   : { _token: CSRF, action: action, ids: ids },
      success: function (res) {
        if (res.success) {
          toast(res.message, 'success');
          state.selected.clear();
          updateBulkBar();
          fetchPages();
        } else {
          toast(res.message || 'Action failed.', 'error');
        }
      },
      error  : function (xhr) {
        toast(xhr.responseJSON?.message || 'Bulk action failed.', 'error');
      },
    });
  }

  /* ══════════════════════════════════════════════
     BULK BAR
  ══════════════════════════════════════════════ */
  function updateBulkBar() {
    const count = state.selected.size;
    $('#bulkCountNum').text(count);
    if (count > 0 && state.bulkMode) {
      $('#piBulkBar').addClass('show');
    } else {
      $('#piBulkBar').removeClass('show');
    }
  }

  /* ══════════════════════════════════════════════
     DEBOUNCED FETCH
  ══════════════════════════════════════════════ */
  let searchTimer;
  function debouncedFetch(delay) {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(fetchPages, delay || 0);
  }

  /* ══════════════════════════════════════════════
     EVENT BINDINGS
  ══════════════════════════════════════════════ */

  // Type tabs
  $('.pi-type-tab').on('click', function () {
    state.type = $(this).data('type');
    state.page = 1;
    state.selected.clear();
    updateBulkBar();
    $('.pi-type-tab').removeClass('active');
    $(this).addClass('active');
    const meta = TYPE_META[state.type];
    $('#btnNewPage, #btnEmptyCreate').attr('href', `${ROUTES.create}?type=${state.type}`);
    debouncedFetch();
  });

  // Stat cards
  $('.pi-stat-card').on('click', function () {
    const t = $(this).data('stat-type');
    state.status = t;
    state.page   = 1;
    $('.pi-stat-card').removeClass('active');
    $(this).addClass('active');
    $('.pi-status-pill').removeClass('active');
    $(`.pi-status-pill[data-status="${t}"]`).addClass('active');
    debouncedFetch();
  });

  // Status pills
  $('.pi-status-pill').on('click', function () {
    state.status = $(this).data('status');
    state.page   = 1;
    $('.pi-status-pill').removeClass('active');
    $(this).addClass('active');
    $('.pi-stat-card').removeClass('active');
    $(`.pi-stat-card[data-stat-type="${state.status}"]`).addClass('active');
    debouncedFetch();
  });

  // Search
  $('#piSearch').on('input', function () {
    state.search = $(this).val().trim();
    state.page   = 1;
    $('#piSearchClear').toggle(state.search.length > 0);
    debouncedFetch(320);
  });
  $('#piSearchClear').on('click', function () {
    $('#piSearch').val('');
    state.search = '';
    state.page   = 1;
    $(this).hide();
    debouncedFetch();
  });

  // Sort
  $('#piSort').on('change', function () {
    state.sort = $(this).val();
    state.page = 1;
    debouncedFetch();
  });

  // Featured filter
  $('#piFeatured').on('change', function () {
    state.featured = $(this).val();
    state.page = 1;
    debouncedFetch();
  });

  // View toggle
  $('#btnGridView').on('click', function () {
    state.isGrid = true;
    $(this).addClass('active');
    $('#btnListView').removeClass('active');
    if (state.pages.length) {
      const meta_type = TYPE_META[state.type];
      renderGrid(state.pages, meta_type);
    }
  });
  $('#btnListView').on('click', function () {
    state.isGrid = false;
    $(this).addClass('active');
    $('#btnGridView').removeClass('active');
    if (state.pages.length) {
      const meta_type = TYPE_META[state.type];
      renderList(state.pages, meta_type);
    }
  });

  // Pagination
  $(document).on('click', '.js-page:not([disabled])', function () {
    state.page = parseInt($(this).data('page'));
    fetchPages();
    $('html,body').animate({ scrollTop: 0 }, 180);
  });

  // Edit button
  $(document).on('click', '.js-edit', function (e) {
    e.stopPropagation();
    window.location.href = $(this).data('url');
  });

  // View button
  $(document).on('click', '.js-view', function (e) {
    e.stopPropagation();
    window.open($(this).data('url'), '_blank');
  });

  // Delete button
  $(document).on('click', '.js-del', function (e) {
    e.stopPropagation();
    state.delId    = parseInt($(this).data('id'));
    state.delTitle = $(this).data('title');
    $('#deletePageTitle').text(`"${state.delTitle}"`);
    $('#deleteModal').addClass('show');
  });

  // Toggle status
  $(document).on('click', '.js-toggle', function (e) {
    e.stopPropagation();
    const id  = parseInt($(this).data('id'));
    const url = $(this).data('url');
    doToggle(id, url);
  });

  // Selection (checkbox)
  $(document).on('click', '.pi-card-check, .list-check', function (e) {
    e.stopPropagation();
    const id = parseInt($(this).data('id'));
    if (state.selected.has(id)) {
      state.selected.delete(id);
    } else {
      state.selected.add(id);
    }
    updateBulkBar();
    // Re-render just the card/row without a full fetch
    const meta_type = TYPE_META[state.type];
    if (state.isGrid) renderGrid(state.pages, meta_type);
    else renderList(state.pages, meta_type);
  });

  // Bulk mode toggle
  $('#btnBulkToggle').on('click', function () {
    state.bulkMode = !state.bulkMode;
    if (!state.bulkMode) {
      state.selected.clear();
      $('#piBulkBar').removeClass('show');
    }
    $(this).toggleClass('btn-pi-primary btn-pi-outline');
    toast(state.bulkMode ? 'Bulk mode — click cards to select' : 'Bulk mode off', 'info');
  });
  $('#bulkClear').on('click', () => { state.selected.clear(); updateBulkBar(); });
  $('#bulkPublish').on('click', () => doBulk('publish'));
  $('#bulkDraft').on('click',   () => doBulk('draft'));
  $('#bulkDelete').on('click',  () => {
    if (!state.selected.size) return;
    if (confirm(`Delete ${state.selected.size} selected page(s)?`)) doBulk('delete');
  });

  // Delete modal
  $('#deleteModalClose, #deleteModalCancel').on('click', () => {
    $('#deleteModal').removeClass('show');
    state.delId = null;
  });
  $('#deleteModal').on('click', function (e) {
    if ($(e.target).is(this)) { $(this).removeClass('show'); state.delId = null; }
  });
  $('#deleteModalConfirm').on('click', function () {
    if (!state.delId) return;
    $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting…');
    doDelete(state.delId);
  });

  // ESC key
  $(document).on('keydown', e => {
    if (e.key === 'Escape') { $('#deleteModal').removeClass('show'); state.delId = null; }
  });

  /* ══════════════════════════════════════════════
     SESSION TOAST (from store/update redirect)
  ══════════════════════════════════════════════ */
  @if (session('toast'))
    @php $t = session('toast'); @endphp
    (function () {
      const type = @json($t['type'] ?? 'info');
      const msg  = @json($t['message'] ?? '');
      setTimeout(() => toast(msg, type), 400);
    })();
  @endif

  /* ══════════════════════════════════════════════
     INIT — load first page of data
  ══════════════════════════════════════════════ */
  fetchPages();

});
</script>
@endpush