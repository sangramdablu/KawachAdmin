@extends('layouts.master')
@section('title', isset($page) ? 'Edit Page — KawachTech' : 'Create Page — KawachTech Software Solutions')

{{-- ============================================================
     STYLES — pushed to <head> via the stack
     (Custom CSS omitted per request — re-add your existing <style> block here)
     ============================================================ --}}
@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet"/>
@endpush

@section('content')


  <style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap');

    /* ── ROOT TOKENS ── */
    :root {
      --sidebar-bg:       #0d1b3e;
      --sidebar-width:    220px;
      --navbar-h:         70px;
      --primary:          #1a73e8;
      --accent:           #2196f3;
      --bg-body:          #eef2f9;
      --white:            #ffffff;
      --card-bg:          #ffffff;
      --card-radius:      14px;
      --text-dark:        #1a1a2e;
      --text-muted:       #8a9bb5;
      --border:           #e2e8f0;
      --green:            #00c896;
      --red:              #ff4d6d;
      --yellow:           #ffb830;
      --panel-title:      #1a1a2e;
      --input-bg:         #ffffff;
      --input-border:     #e2e8f0;
      --input-color:      #1a1a2e;
      --modal-bg:         #ffffff;
      --modal-header:     #f4f6fb;
      --topbar-bg:        #0d1b3e;
    }
    [data-theme="dark"] {
      --bg-body:      #0f172a;
      --white:        #1e293b;
      --card-bg:      #1e293b;
      --text-dark:    #e2e8f0;
      --text-muted:   #64748b;
      --border:       #334155;
      --panel-title:  #e2e8f0;
      --input-bg:     #0f172a;
      --input-border: #334155;
      --input-color:  #e2e8f0;
      --modal-bg:     #1e293b;
      --modal-header: #0f172a;
    }

    #pageBuilder { font-family: 'Open Sans', sans-serif; color: var(--text-dark); background: var(--bg-body); min-height: 100vh; transition: background .3s, color .3s; }
    #pageBuilder * { box-sizing: border-box; }

    .pb-wrap { max-width: 1400px; margin: 0 auto; padding: 26px 20px 70px; }

    .pb-topbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 20px; }
    .pb-breadcrumb { font-size: .74rem; color: var(--text-muted); display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
    .pb-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 600; }
    .pb-breadcrumb a:hover { text-decoration: underline; }
    .pb-title { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.5rem; color: var(--text-dark); display: flex; align-items: center; gap: 10px; }
    .pb-topbar-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }

    .page-type-bar { background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--card-radius); padding: 14px 18px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; box-shadow: 0 2px 12px rgba(26,115,232,.06); transition: background .3s; }
    .page-type-label { font-family: 'Nunito', sans-serif; font-weight: 800; font-size: .82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; white-space: nowrap; }
    .page-type-pills { display: flex; flex-wrap: wrap; gap: 8px; flex: 1; }
    .type-pill { display: flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px; font-size: .8rem; font-weight: 700; cursor: pointer; border: 1.5px solid var(--border); background: var(--input-bg); color: var(--text-muted); transition: all .18s; user-select: none; font-family: 'Open Sans', sans-serif; }
    .type-pill i { font-size: .85rem; }
    .type-pill:hover { border-color: var(--primary); color: var(--primary); }
    .type-pill.active { background: var(--primary); border-color: var(--primary); color: #fff; box-shadow: 0 2px 10px rgba(26,115,232,.3); }

    .btn-pb { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: 8px; font-size: .84rem; font-weight: 700; cursor: pointer; border: none; transition: all .2s; font-family: 'Open Sans', sans-serif; white-space: nowrap; text-decoration: none; }
    .btn-pb:hover { transform: translateY(-1px); }
    .btn-pb-primary { background: var(--primary); color: #fff; }
    .btn-pb-primary:hover { background: #1558b0; box-shadow: 0 4px 14px rgba(26,115,232,.35); color: #fff; }
    .btn-pb-success { background: var(--green); color: #fff; }
    .btn-pb-success:hover { background: #00a87c; box-shadow: 0 4px 14px rgba(0,200,150,.3); color: #fff; }
    .btn-pb-outline { background: var(--card-bg); color: var(--text-dark); border: 1.5px solid var(--border); }
    .btn-pb-outline:hover { border-color: var(--primary); color: var(--primary); }
    .btn-pb-danger { background: #fff5f7; color: var(--red); border: 1.5px solid #ffc0cc; }
    .btn-pb-danger:hover { background: var(--red); color: #fff; border-color: var(--red); }
    .btn-pb-warning { background: var(--yellow); color: #fff; }
    .btn-pb-warning:hover { background: #e0a020; color: #fff; }
    .btn-sm { padding: 6px 13px; font-size: .77rem; }
    .btn-xs { padding: 4px 10px; font-size: .72rem; border-radius: 6px; }
    .btn-icon-sq { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 7px; border: 1.5px solid var(--border); background: var(--card-bg); color: var(--text-muted); cursor: pointer; font-size: .8rem; transition: all .18s; }
    .btn-icon-sq:hover { border-color: var(--primary); color: var(--primary); }
    .btn-icon-sq.del:hover { border-color: var(--red); color: var(--red); }

    .pb-grid { display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start; }
    @media (max-width: 1100px) { .pb-grid { grid-template-columns: 1fr; } }

    .pb-card { background: var(--card-bg); border-radius: var(--card-radius); border: 1px solid var(--border); box-shadow: 0 2px 14px rgba(26,115,232,.07); overflow: hidden; transition: background .3s; }
    .pb-card + .pb-card { margin-top: 16px; }
    .pb-card-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 1px solid var(--border); background: var(--modal-header); transition: background .3s; }
    .pb-card-header h2 { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: .92rem; color: var(--panel-title); display: flex; align-items: center; gap: 8px; margin: 0; }
    .pb-card-header h2 i { color: var(--primary); font-size: .88rem; }
    .pb-card-body { padding: 20px; }

    .pb-form-group { margin-bottom: 16px; }
    .pb-form-group:last-child { margin-bottom: 0; }
    .pb-label { display: flex; align-items: center; gap: 6px; font-size: .79rem; font-weight: 700; color: var(--text-dark); margin-bottom: 6px; }
    .pb-label i { font-size: .76rem; color: var(--primary); }
    .lbl-badge { font-size: .6rem; font-weight: 800; padding: 2px 7px; border-radius: 4px; text-transform: uppercase; letter-spacing: .4px; }
    .lbl-req  { background: #ffe2e8; color: var(--red); }
    .lbl-opt  { background: #eef2f9; color: var(--text-muted); }
    .lbl-seo  { background: #e8f1fd; color: var(--primary); }
    [data-theme="dark"] .lbl-req  { background: rgba(255,77,109,.18); }
    [data-theme="dark"] .lbl-opt  { background: rgba(107,122,153,.15); }
    [data-theme="dark"] .lbl-seo  { background: rgba(26,115,232,.18); }

    .pb-input, .pb-textarea, .pb-select { width: 100%; border: 1.5px solid var(--input-border); border-radius: 8px; padding: 9px 13px; font-size: .875rem; color: var(--input-color); background: var(--input-bg); outline: none; transition: border-color .2s, box-shadow .2s, background .3s; font-family: 'Open Sans', sans-serif; }
    .pb-input:focus, .pb-textarea:focus, .pb-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,115,232,.11); }
    .pb-input.is-title { font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 1.3rem; padding: 12px 14px; border-radius: 10px; }
    .pb-textarea { resize: vertical; min-height: 80px; line-height: 1.65; }
    .pb-select { cursor: pointer; }

    .pb-hint { font-size: .71rem; color: var(--text-muted); margin-top: 5px; display: flex; align-items: center; gap: 5px; }
    .pb-hint i { font-size: .66rem; color: var(--primary); }

    .pb-input-wrap { position: relative; }
    .pb-char-count { position: absolute; right: 9px; bottom: 9px; font-size: .66rem; font-weight: 700; color: var(--text-muted); background: var(--bg-body); padding: 1px 6px; border-radius: 4px; pointer-events: none; }
    .pb-char-count.warn { color: var(--yellow); }
    .pb-char-count.over { color: var(--red); }
    .pb-char-count.good { color: var(--green); }

    .slug-row { display: flex; align-items: center; gap: 8px; border: 1.5px solid var(--input-border); border-radius: 8px; padding: 8px 12px; background: var(--input-bg); transition: border-color .2s, background .3s; }
    .slug-row:focus-within { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,115,232,.1); }
    .slug-prefix { font-size: .78rem; color: var(--text-muted); white-space: nowrap; flex-shrink: 0; }
    .slug-input { flex: 1; border: none; outline: none; font-size: .875rem; color: var(--primary); font-weight: 600; background: transparent; font-family: 'Open Sans', sans-serif; }

    .pb-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .pb-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; }
    @media (max-width: 640px) { .pb-grid-2, .pb-grid-3 { grid-template-columns: 1fr; } }

    .ql-wrapper { border-radius: 8px; overflow: hidden; border: 1.5px solid var(--input-border); transition: border-color .2s, box-shadow .2s; }
    .ql-wrapper:focus-within { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,115,232,.11); }
    .ql-toolbar.ql-snow { border: none !important; border-bottom: 1px solid var(--border) !important; background: var(--modal-header) !important; padding: 10px 12px; }
    .ql-container.ql-snow { border: none !important; font-family: 'Open Sans', sans-serif; font-size: .9rem; }
    .ql-editor { min-height: 280px; padding: 16px 18px; line-height: 1.8; color: var(--input-color); background: var(--input-bg); }
    .ql-editor.ql-blank::before { color: var(--text-muted); font-style: italic; }
    [data-theme="dark"] .ql-snow .ql-stroke { stroke: var(--text-muted); }
    [data-theme="dark"] .ql-snow .ql-fill   { fill: var(--text-muted); }
    [data-theme="dark"] .ql-snow .ql-picker  { color: var(--text-dark); }

    .img-upload-zone { border: 2px dashed var(--border); border-radius: 10px; padding: 28px 18px; text-align: center; cursor: pointer; transition: border-color .2s, background .2s; background: var(--modal-header); position: relative; }
    .img-upload-zone:hover, .img-upload-zone.drag-over { border-color: var(--primary); background: #eef4ff; }
    [data-theme="dark"] .img-upload-zone:hover { background: rgba(26,115,232,.08); }
    .img-upload-zone input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .upload-icon { font-size: 1.8rem; color: var(--primary); opacity: .5; margin-bottom: 7px; }
    .upload-text { font-size: .8rem; color: var(--text-muted); }
    .upload-text strong { color: var(--primary); }
    .img-preview-wrap { display: none; position: relative; border-radius: 8px; overflow: hidden; margin-top: 10px; border: 1.5px solid var(--border); }
    .img-preview-wrap img { width: 100%; height: 190px; object-fit: cover; display: block; }
    .img-preview-actions { position: absolute; top: 8px; right: 8px; display: flex; gap: 6px; }
    .img-size-info { font-size: .68rem; color: var(--text-muted); margin-top: 5px; text-align: center; }

    .serp-preview { border: 1.5px solid var(--border); border-radius: 10px; padding: 14px 16px; background: var(--modal-header); margin-top: 6px; transition: background .3s; }
    .serp-label { font-size: .66rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 8px; display: flex; align-items: center; gap: 5px; }
    .serp-label i { color: var(--green); }
    .serp-url   { font-size: .74rem; color: #1a7e45; margin-bottom: 3px; word-break: break-all; }
    .serp-title { font-size: 1.05rem; color: #1a0dab; font-weight: 600; margin-bottom: 3px; line-height: 1.3; display: block; text-decoration: none; }
    .serp-title:hover { text-decoration: underline; }
    .serp-desc  { font-size: .8rem; color: #4d5156; line-height: 1.55; }
    [data-theme="dark"] .serp-url   { color: #4ade80; }
    [data-theme="dark"] .serp-title { color: #93c5fd; }
    [data-theme="dark"] .serp-desc  { color: var(--text-muted); }

    .meter-bar  { height: 4px; border-radius: 2px; background: var(--border); margin-top: 5px; overflow: hidden; }
    .meter-fill { height: 100%; border-radius: 2px; transition: width .3s, background .3s; }

    .tags-wrap { display: flex; flex-wrap: wrap; gap: 6px; border: 1.5px solid var(--input-border); border-radius: 8px; padding: 7px 10px; cursor: text; min-height: 42px; align-items: flex-start; background: var(--input-bg); transition: border-color .2s, box-shadow .2s, background .3s; }
    .tags-wrap:focus-within { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,115,232,.1); }
    .tag-pill { display: inline-flex; align-items: center; gap: 5px; background: #e8f1fd; color: var(--primary); border-radius: 20px; padding: 3px 10px; font-size: .74rem; font-weight: 700; }
    [data-theme="dark"] .tag-pill { background: rgba(26,115,232,.2); }
    .tag-pill button { background: none; border: none; cursor: pointer; color: var(--primary); font-size: .68rem; padding: 0; line-height: 1; opacity: .7; transition: opacity .15s; }
    .tag-pill button:hover { opacity: 1; }
    .tags-input { border: none; outline: none; font-size: .82rem; min-width: 110px; flex: 1; font-family: 'Open Sans', sans-serif; color: var(--input-color); background: transparent; }

    .vis-pills { display: flex; gap: 7px; flex-wrap: wrap; }
    .vis-pill { flex: 1; min-width: 75px; text-align: center; padding: 7px 8px; border-radius: 8px; border: 1.5px solid var(--border); font-size: .74rem; font-weight: 700; cursor: pointer; color: var(--text-muted); background: var(--input-bg); transition: all .18s; }
    .vis-pill.active { border-color: var(--primary); color: var(--primary); background: #e8f1fd; }
    [data-theme="dark"] .vis-pill.active { background: rgba(26,115,232,.18); }
    .vis-pill i { display: block; font-size: .95rem; margin-bottom: 3px; }

    .seo-score-wrap { display: flex; align-items: center; gap: 12px; padding: 13px 14px; background: linear-gradient(135deg, #e8f1fd, #dbeeff); border-radius: 10px; margin-bottom: 14px; border: 1px solid rgba(26,115,232,.15); }
    [data-theme="dark"] .seo-score-wrap { background: rgba(26,115,232,.12); border-color: rgba(26,115,232,.25); }
    .seo-ring { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1rem; color: #fff; flex-shrink: 0; transition: background .4s; }
    .seo-score-info strong { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: .88rem; color: var(--text-dark); display: block; }
    .seo-score-info span   { font-size: .73rem; color: var(--text-muted); }
    .seo-checklist { list-style: none; padding: 0; margin: 0; }
    .seo-checklist li { display: flex; align-items: flex-start; gap: 7px; padding: 6px 0; font-size: .76rem; color: var(--text-muted); border-bottom: 1px solid var(--border); }
    .seo-checklist li:last-child { border-bottom: none; }
    .seo-checklist li i { font-size: .78rem; margin-top: 1px; flex-shrink: 0; }
    .seo-checklist li.pass { color: var(--green); }
    .seo-checklist li.fail { color: var(--red); }
    .seo-checklist li.warn { color: var(--yellow); }
    .seo-checklist li.pass i { color: var(--green); }
    .seo-checklist li.fail i { color: var(--red); }
    .seo-checklist li.warn i { color: var(--yellow); }

    .repeatable-container { display: flex; flex-direction: column; gap: 12px; }
    .repeat-item { background: var(--modal-header); border: 1.5px solid var(--border); border-radius: 10px; padding: 16px; position: relative; transition: background .3s, border-color .2s; }
    .repeat-item:hover { border-color: var(--primary); }
    .repeat-item-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .repeat-item-title { font-family: 'Nunito', sans-serif; font-weight: 800; font-size: .84rem; color: var(--panel-title); display: flex; align-items: center; gap: 7px; }
    .repeat-item-actions { display: flex; gap: 5px; align-items: center; }
    .drag-handle { cursor: grab; color: var(--text-muted); font-size: .85rem; padding: 4px; }
    .drag-handle:active { cursor: grabbing; }
    .add-item-btn { display: flex; align-items: center; justify-content: center; gap: 7px; padding: 10px; border-radius: 8px; border: 2px dashed var(--border); background: transparent; color: var(--text-muted); cursor: pointer; font-size: .82rem; font-weight: 700; width: 100%; transition: all .18s; font-family: 'Open Sans', sans-serif; }
    .add-item-btn:hover { border-color: var(--primary); color: var(--primary); background: #f0f6ff; }
    [data-theme="dark"] .add-item-btn:hover { background: rgba(26,115,232,.08); }

    .pb-section { display: none; }
    .pb-section.active { display: block; }

    .icon-picker-wrap { position: relative; }
    .icon-preview-row { display: flex; align-items: center; gap: 8px; border: 1.5px solid var(--input-border); border-radius: 8px; padding: 8px 12px; background: var(--input-bg); transition: border-color .2s; }
    .icon-preview-row:focus-within { border-color: var(--primary); }
    .icon-preview { width: 32px; height: 32px; border-radius: 7px; background: #e8f1fd; display: flex; align-items: center; justify-content: center; font-size: 1rem; color: var(--primary); flex-shrink: 0; }
    .icon-input { flex: 1; border: none; outline: none; font-size: .84rem; color: var(--input-color); background: transparent; font-family: 'Open Sans', sans-serif; }
    .icon-btn-pick { background: none; border: none; cursor: pointer; color: var(--text-muted); font-size: .8rem; padding: 4px 8px; border-radius: 6px; transition: all .15s; font-family: 'Open Sans', sans-serif; font-weight: 600; white-space: nowrap; }
    .icon-btn-pick:hover { background: var(--border); color: var(--primary); }
    .icon-grid-dropdown { position: absolute; top: 44px; left: 0; right: 0; z-index: 200; background: var(--modal-bg); border: 1px solid var(--border); border-radius: 10px; padding: 12px; box-shadow: 0 8px 32px rgba(0,0,0,.13); display: none; }
    .icon-grid-dropdown.show { display: block; }
    .icon-grid { display: grid; grid-template-columns: repeat(8, 1fr); gap: 5px; max-height: 200px; overflow-y: auto; }
    .icon-opt { width: 34px; height: 34px; border-radius: 7px; border: none; background: transparent; display: flex; align-items: center; justify-content: center; font-size: .9rem; color: var(--text-muted); cursor: pointer; transition: all .15s; }
    .icon-opt:hover { background: var(--border); color: var(--primary); }

    .color-swatches { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 6px; }
    .color-swatch { width: 26px; height: 26px; border-radius: 6px; cursor: pointer; border: 2px solid transparent; transition: transform .15s, border-color .15s; }
    .color-swatch:hover, .color-swatch.active { transform: scale(1.18); border-color: var(--text-dark); }

    .stats-strip { display: flex; gap: 14px; flex-wrap: wrap; padding: 11px 14px; background: var(--card-bg); border-radius: 8px; margin-bottom: 18px; border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(26,115,232,.05); transition: background .3s; }
    .stat-item { display: flex; align-items: center; gap: 5px; font-size: .74rem; color: var(--text-muted); }
    .stat-item i { color: var(--primary); font-size: .7rem; }
    .stat-item strong { color: var(--text-dark); font-weight: 700; }

    .pb-toast-stack { position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; pointer-events: none; }
    .pb-toast { background: var(--card-bg); border: 1px solid var(--border); border-radius: 10px; padding: 11px 15px; display: flex; align-items: center; gap: 9px; box-shadow: 0 6px 24px rgba(0,0,0,.12); font-size: .81rem; color: var(--text-dark); pointer-events: all; animation: pbToastIn .2s ease; max-width: 300px; }
    @keyframes pbToastIn { from { opacity:0; transform:translateX(14px); } to { opacity:1; transform:none; } }
    .pb-toast i { font-size: .9rem; flex-shrink: 0; }

    .pb-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 900; align-items: center; justify-content: center; backdrop-filter: blur(3px); }
    .pb-modal-overlay.show { display: flex; }
    .pb-modal { background: var(--modal-bg); border-radius: 14px; max-width: 520px; width: 95%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,.22); animation: pbModalPop .2s ease; }
    @keyframes pbModalPop { from { opacity:0; transform:scale(.94); } to { opacity:1; transform:scale(1); } }
    .pb-modal-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
    .pb-modal-header h3 { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.05rem; color: var(--text-dark); margin: 0; }
    .pb-modal-close { width: 28px; height: 28px; border-radius: 50%; border: none; background: var(--modal-header); color: var(--text-muted); cursor: pointer; font-size: .78rem; display: flex; align-items: center; justify-content: center; transition: background .15s; }
    .pb-modal-close:hover { background: var(--border); }
    .pb-modal-body { padding: 20px; }
    .pb-modal-footer { padding: 14px 20px; border-top: 1px solid var(--border); display: flex; gap: 8px; justify-content: flex-end; }

    .schema-pills { display: flex; flex-wrap: wrap; gap: 7px; }
    .schema-pill { display: inline-flex; align-items: center; gap: 5px; padding: 5px 11px; border-radius: 20px; font-size: .74rem; font-weight: 700; border: 1.5px solid var(--border); cursor: pointer; background: var(--input-bg); color: var(--text-muted); transition: all .18s; }
    .schema-pill.active, .schema-pill:hover { border-color: var(--primary); color: var(--primary); background: #e8f1fd; }
    [data-theme="dark"] .schema-pill.active { background: rgba(26,115,232,.18); }

    .star-rating { display: flex; gap: 5px; }
    .star-btn { background: none; border: none; cursor: pointer; font-size: 1.2rem; color: var(--border); transition: color .15s; }
    .star-btn.lit, .star-btn:hover { color: var(--yellow); }

    .step-nav { display: flex; align-items: center; gap: 0; margin-bottom: 18px; overflow-x: auto; scrollbar-width: none; }
    .step-nav::-webkit-scrollbar { display: none; }
    .step-dot { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: 'Nunito', sans-serif; font-weight: 900; font-size: .75rem; border: 2px solid var(--border); background: var(--input-bg); color: var(--text-muted); flex-shrink: 0; transition: all .25s; position: relative; z-index: 1; cursor: pointer; }
    .step-dot.active { background: var(--primary); border-color: var(--primary); color: #fff; }
    .step-dot.done   { background: var(--green); border-color: var(--green); color: #fff; }
    .step-line { flex: 1; height: 2px; background: var(--border); transition: background .25s; min-width: 20px; }
    .step-line.done  { background: var(--green); }
    .step-label { font-size: .65rem; font-weight: 700; color: var(--text-muted); text-align: center; margin-top: 4px; white-space: nowrap; }
    .step-item { display: flex; flex-direction: column; align-items: center; }

    @media (max-width: 640px) {
      .pb-topbar { flex-direction: column; align-items: flex-start; }
      .pb-topbar-actions { width: 100%; }
      .btn-pb { flex: 1; justify-content: center; }
      .page-type-pills { gap: 5px; }
      .type-pill { font-size: .72rem; padding: 6px 10px; }
    }

    .pb-field-error { display: flex; align-items: center; gap: 5px; margin-top: 5px; font-size: .74rem; font-weight: 600; color: var(--red, #ff4d6d); animation: pbv-slide-in .15s ease; }
    .pb-field-error i { font-size: .7rem; flex-shrink: 0; }
    @keyframes pbv-slide-in { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: none; } }
    .pb-input-invalid { border-color: var(--red, #ff4d6d) !important; box-shadow: 0 0 0 3px rgba(255,77,109,.12) !important; }
  </style>


{{-- ============================================================
     MARKUP
     ============================================================ --}}
<div id="pageBuilder">
<div class="pb-wrap">

  {{-- ── TOP BAR ── --}}
  <div class="pb-topbar">
    <div>
      <div class="pb-breadcrumb">
        <a href="#"><i class="fas fa-home"></i> Dashboard</a>
        <i class="fas fa-chevron-right" style="font-size:.52rem;"></i>
        <a href="#">Pages</a>
        <i class="fas fa-chevron-right" style="font-size:.52rem;"></i>
        <span id="pb-bread-current">{{ isset($page) ? 'Edit Page' : 'New Page' }}</span>
      </div>
      <div class="pb-title">
        <span id="pb-title-icon">🏗️</span>
        <span id="pb-title-text">{{ isset($page) ? 'Edit Page' : 'Create New Page' }}</span>
      </div>
    </div>
    <div class="pb-topbar-actions">
      <button class="btn-pb btn-pb-outline" id="btnPbPreview" type="button">
        <i class="fas fa-eye"></i> Preview
      </button>
      <button class="btn-pb btn-pb-outline" id="btnPbDraft" type="button">
        <i class="fas fa-save"></i> Save Draft
      </button>
      <button class="btn-pb btn-pb-success" id="btnPbPublish" type="button">
        <i class="fas fa-rocket"></i> Publish
      </button>
    </div>
  </div>

  {{-- ── PAGE TYPE SELECTOR ── --}}
  <div class="page-type-bar">
    <span class="page-type-label"><i class="fas fa-layer-group" style="margin-right:5px;color:var(--primary);"></i> Page Type:</span>
    <div class="page-type-pills">
      @php $currentPageType = isset($page) ? $page->page_type : 'service'; @endphp
      <div class="type-pill {{ $currentPageType === 'service'     ? 'active' : '' }}" data-type="service"><i class="fas fa-cogs"></i> Service</div>
      <div class="type-pill {{ $currentPageType === 'casestudy'   ? 'active' : '' }}" data-type="casestudy"><i class="fas fa-briefcase"></i> Case Study</div>
      <div class="type-pill {{ $currentPageType === 'team'        ? 'active' : '' }}" data-type="team"><i class="fas fa-users"></i> Team Member</div>
      <div class="type-pill {{ $currentPageType === 'testimonial' ? 'active' : '' }}" data-type="testimonial"><i class="fas fa-quote-left"></i> Testimonial</div>
      <div class="type-pill {{ $currentPageType === 'faq'         ? 'active' : '' }}" data-type="faq"><i class="fas fa-question-circle"></i> FAQ</div>
      <div class="type-pill {{ $currentPageType === 'portfolio'   ? 'active' : '' }}" data-type="portfolio"><i class="fas fa-th-large"></i> Portfolio</div>
      <div class="type-pill {{ $currentPageType === 'blog'        ? 'active' : '' }}" data-type="blog"><i class="fas fa-pen-nib"></i> Blog Post</div>
      <div class="type-pill {{ $currentPageType === 'landing'     ? 'active' : '' }}" data-type="landing"><i class="fas fa-home"></i> Landing Page</div>
    </div>
  </div>

  {{-- ── CONTENT STATS STRIP ── --}}
  <div class="stats-strip" id="statsStrip">
    <div class="stat-item"><i class="fas fa-align-left"></i> Words: <strong id="wordCount">0</strong></div>
    <div class="stat-item"><i class="fas fa-clock"></i> Read time: <strong id="readTime">0 min</strong></div>
    <div class="stat-item"><i class="fas fa-heading"></i> Headings: <strong id="headingCount">0</strong></div>
    <div class="stat-item"><i class="fas fa-link"></i> Links: <strong id="linkCount">0</strong></div>
    <div class="stat-item"><i class="fas fa-image"></i> Images: <strong id="imgCount">0</strong></div>
    <div class="stat-item" id="typeStatItem"><i class="fas fa-layer-group"></i> Type: <strong id="currentTypeLabel">{{ ucfirst($currentPageType) }}</strong></div>
  </div>

  {{-- ── MAIN FORM ── --}}
  <form id="pbForm"
        method="POST"
        action="{{ isset($page) ? route('pages.update', $page) : route('pages.store') }}"
        enctype="multipart/form-data"
        data-page-id="{{ $page->id ?? '' }}"
        data-page-type="{{ $currentPageType }}">
    @csrf
    @isset($page)
      @method('PUT')
    @endisset
    <input type="hidden" name="page_type" id="pageTypeInput" value="{{ $currentPageType }}"/>
    <input type="hidden" id="pbStatus" value="{{ $page->status ?? 'draft' }}"/>

    <div class="pb-grid">

      {{-- ════════════════════════════════
           LEFT — Dynamic Content Sections
           ════════════════════════════════ --}}
      <div class="pb-left">

        {{-- ─── COMMON: Title & Slug ─── --}}
        <div class="pb-card">
          <div class="pb-card-header">
            <h2><i class="fas fa-heading"></i> <span id="titleCardLabel">Title</span></h2>
          </div>
          <div class="pb-card-body">
            <div class="pb-form-group">
              <div class="pb-input-wrap">
                <input type="text" name="title" id="pbTitle" class="pb-input is-title"
                  placeholder="Enter a compelling title…" maxlength="100" autocomplete="off" required
                  value="{{ old('title', $page->title ?? '') }}"/>
                <span class="pb-char-count" id="titleCounter">0/100</span>
              </div>
              <div class="pb-hint"><i class="fas fa-info-circle"></i> Ideal: <strong>50–70 characters</strong> for best display and SEO.</div>
            </div>
            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-link"></i> URL Slug <span class="lbl-badge lbl-req">Required</span></label>
              <div class="slug-row">
                <span class="slug-prefix" id="slugPrefix">{{ request()->getHost() }}/</span>
                <input type="text" name="slug" id="pbSlug" class="slug-input"
                  placeholder="your-page-slug" autocomplete="off"
                  value="{{ old('slug', $page->slug ?? '') }}"
                  data-page-id="{{ $page->id ?? '' }}"/>
                <button type="button" class="btn-icon-sq" id="btnRegenSlug" title="Re-generate"><i class="fas fa-sync-alt"></i></button>
                <button type="button" class="btn-icon-sq" id="btnCopySlug" title="Copy URL"><i class="fas fa-copy"></i></button>
              </div>
              <div class="pb-hint"><i class="fas fa-info-circle"></i> Lowercase, hyphens only. Keep it short and keyword-rich.</div>
            </div>
          </div>
        </div>

        {{-- ─── SERVICE SECTION ─── --}}
        <div class="pb-section {{ $currentPageType === 'service' ? 'active' : '' }}" id="section-service">

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-align-left"></i> Short Description</h2></div>
            <div class="pb-card-body">
              <div class="pb-form-group">
                <div class="pb-input-wrap">
                  <textarea name="short_description" id="svcShortDesc" class="pb-textarea"
                    placeholder="One or two sentences summarising this service…" rows="3" maxlength="200">{{ old('short_description', $typeData->short_description ?? '') }}</textarea>
                  <span class="pb-char-count" id="svcShortDescCount">0/200</span>
                </div>
              </div>
            </div>
          </div>

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-pen-nib"></i> Full Description</h2></div>
            <div class="pb-card-body" style="padding:0;">
              <div class="ql-wrapper"><div id="quillService"></div></div>
              <textarea name="content" id="svcContent" style="display:none;">{{ old('content', $typeData->content ?? '') }}</textarea>
            </div>
          </div>

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-check-circle"></i> Features / Benefits</h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addFeatureBtn"><i class="fas fa-plus"></i> Add Feature</button>
            </div>
            <div class="pb-card-body">
              <div class="repeatable-container" id="featuresContainer"></div>
            </div>
          </div>

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-list-ol"></i> Process / How It Works</h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addStepBtn"><i class="fas fa-plus"></i> Add Step</button>
            </div>
            <div class="pb-card-body">
              <div class="repeatable-container" id="stepsContainer"></div>
            </div>
          </div>

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-tag"></i> Pricing (Optional)</h2></div>
            <div class="pb-card-body">
              <div class="pb-grid-3">
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-dollar-sign"></i> Starting Price <span class="lbl-badge lbl-opt">Optional</span></label>
                  <input type="text" name="price_from" class="pb-input" placeholder="e.g. $999" value="{{ old('price_from', $typeData->price_from ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-sync"></i> Billing Cycle</label>
                  <select name="billing_cycle" class="pb-select">
                    <option value="">— None —</option>
                    @foreach(['one-time','monthly','yearly','per-project','hourly'] as $cycle)
                      <option value="{{ $cycle }}" {{ old('billing_cycle', $typeData->billing_cycle ?? '') === $cycle ? 'selected' : '' }}>{{ ucfirst($cycle) }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-link"></i> CTA Button URL</label>
                  <input type="url" name="cta_url" class="pb-input" placeholder="https://…" value="{{ old('cta_url', $typeData->cta_url ?? '') }}"/>
                </div>
              </div>
              <div class="pb-form-group">
                <label class="pb-label"><i class="fas fa-hand-pointer"></i> CTA Button Text</label>
                <input type="text" name="cta_text" class="pb-input" placeholder="e.g. Get Started · Request a Quote" value="{{ old('cta_text', $typeData->cta_text ?? '') }}"/>
              </div>
            </div>
          </div>

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-code"></i> Technologies / Tools</h2></div>
            <div class="pb-card-body">
              <div class="tags-wrap" id="techTagsWrap">
                <input class="tags-input" id="techTagsInput" placeholder="Add tech, press Enter…"/>
              </div>
              <input type="hidden" name="technologies" id="techTagsHidden" value="{{ old('technologies', $typeData->technologies ?? '') }}"/>
              <div class="pb-hint"><i class="fas fa-info-circle"></i> e.g. React, Laravel, AWS, Docker…</div>
            </div>
          </div>
        </div>{{-- /section-service --}}

        {{-- ═══════════════════════════════════════════════════════
             CASE STUDY SECTION  (extended with full builder parity)
             ═══════════════════════════════════════════════════════ --}}
        <div class="pb-section {{ $currentPageType === 'casestudy' ? 'active' : '' }}" id="section-casestudy">

          {{-- Project Overview --}}
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-info-circle"></i> Project Overview</h2></div>
            <div class="pb-card-body">
              <div class="pb-grid-2">
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-building"></i> Client / Company</label>
                  <input type="text" name="client_name" class="pb-input" placeholder="Client name" value="{{ old('client_name', $typeData->client_name ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-industry"></i> Industry</label>
                  <input type="text" name="client_industry" class="pb-input" placeholder="e.g. FinTech, Healthcare" value="{{ old('client_industry', $typeData->client_industry ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-calendar-alt"></i> Project Duration</label>
                  <input type="text" name="project_duration" class="pb-input" placeholder="e.g. 3 months" value="{{ old('project_duration', $typeData->project_duration ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-calendar-check"></i> Completion Date</label>
                  <input type="month" name="completion_date" class="pb-input" value="{{ old('completion_date', $typeData->completion_date ?? '') }}"/>
                </div>
                {{-- FIX: added — business size / location / business model, shown on the detail page's info card --}}
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-users"></i> Business Size</label>
                  <input type="text" name="business_size" class="pb-input" placeholder="e.g. Enterprise (3,000+ Doctors)" value="{{ old('business_size', $typeData->business_size ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-map-marker-alt"></i> Location</label>
                  <input type="text" name="location" class="pb-input" placeholder="e.g. United States (12 States)" value="{{ old('location', $typeData->location ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-layer-group"></i> Business Model</label>
                  <input type="text" name="business_model" class="pb-input" placeholder="e.g. B2C + B2B Healthcare SaaS" value="{{ old('business_model', $typeData->business_model ?? '') }}"/>
                </div>
              </div>
              <div class="pb-form-group">
                <label class="pb-label"><i class="fas fa-external-link-alt"></i> Live Project URL <span class="lbl-badge lbl-opt">Optional</span></label>
                <input type="url" name="project_url" class="pb-input" placeholder="https://…" value="{{ old('project_url', $typeData->project_url ?? '') }}"/>
              </div>
            </div>
          </div>

          {{-- The Challenge --}}
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-exclamation-circle"></i> The Challenge</h2></div>
            <div class="pb-card-body" style="padding:0;">
              <div class="ql-wrapper"><div id="quillChallenge"></div></div>
              <textarea name="challenge" id="csChallenge" style="display:none;">{{ old('challenge', $typeData->challenge ?? '') }}</textarea>
            </div>
          </div>

          {{-- FIX: NEW — Existing Challenges list (feeds the "Existing Challenges" red-list card on the detail page) --}}
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-times-circle"></i> Existing Challenges List</h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addChallengeBtn"><i class="fas fa-plus"></i> Add Item</button>
            </div>
            <div class="pb-card-body">
              <div class="repeatable-container" id="challengesContainer"></div>
            </div>
          </div>

          {{-- Our Solution --}}
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-lightbulb"></i> Our Solution</h2></div>
            <div class="pb-card-body" style="padding:0;">
              <div class="ql-wrapper"><div id="quillSolution"></div></div>
              <textarea name="solution" id="csSolution" style="display:none;">{{ old('solution', $typeData->solution ?? '') }}</textarea>
            </div>
          </div>

          {{-- FIX: NEW — Goals & Objectives --}}
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-bullseye"></i> Goals &amp; Objectives</h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addGoalBtn"><i class="fas fa-plus"></i> Add Goal</button>
            </div>
            <div class="pb-card-body">
              <div class="repeatable-container" id="goalsContainer"></div>
            </div>
          </div>

          {{-- FIX: NEW — Key Modules Delivered --}}
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-puzzle-piece"></i> Key Modules Delivered</h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addModuleBtn"><i class="fas fa-plus"></i> Add Module</button>
            </div>
            <div class="pb-card-body">
              <div class="repeatable-container" id="modulesContainer"></div>
            </div>
          </div>

          {{-- FIX: NEW — Tech Stack (grouped by category) --}}
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-layer-group"></i> Tech Stack (Grouped)</h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addTechGroupBtn"><i class="fas fa-plus"></i> Add Category</button>
            </div>
            <div class="pb-card-body">
              <div class="repeatable-container" id="techStackContainer"></div>
              <div class="pb-hint"><i class="fas fa-info-circle"></i> Enter items comma-separated, e.g. "React, Next.js, TypeScript"</div>
            </div>
          </div>

          {{-- FIX: NEW — Development Process / Timeline --}}
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-list-ol"></i> Development Process / Timeline</h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addCsStepBtn"><i class="fas fa-plus"></i> Add Step</button>
            </div>
            <div class="pb-card-body">
              <div class="repeatable-container" id="csStepsContainer"></div>
            </div>
          </div>

          {{-- FIX: NEW — Key Achievements --}}
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-award"></i> Key Achievements</h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addAchievementBtn"><i class="fas fa-plus"></i> Add Achievement</button>
            </div>
            <div class="pb-card-body">
              <div class="repeatable-container" id="achievementsContainer"></div>
            </div>
          </div>

          {{-- FIX: NEW — Features Developed --}}
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-check-circle"></i> Features Developed</h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addCsFeatureBtn"><i class="fas fa-plus"></i> Add Feature</button>
            </div>
            <div class="pb-card-body">
              <div class="repeatable-container" id="csFeaturesContainer"></div>
            </div>
          </div>

          {{-- FIX: NEW — Case Study FAQ --}}
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-question-circle"></i> FAQ</h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addCsFaqBtn"><i class="fas fa-plus"></i> Add Q&amp;A</button>
            </div>
            <div class="pb-card-body">
              <div class="repeatable-container" id="csFaqContainer"></div>
            </div>
          </div>

          {{-- FIX: NEW — Before vs After table rows --}}
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-exchange-alt"></i> Before vs After</h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addBaBtn"><i class="fas fa-plus"></i> Add Row</button>
            </div>
            <div class="pb-card-body">
              <div class="repeatable-container" id="baContainer"></div>
            </div>
          </div>

          {{-- FIX: NEW — Security / Compliance badges --}}
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-shield-alt"></i> Security / Compliance Badges <span class="lbl-badge lbl-opt">Optional</span></h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addComplianceBtn"><i class="fas fa-plus"></i> Add Badge</button>
            </div>
            <div class="pb-card-body">
              <div class="repeatable-container" id="complianceContainer"></div>
            </div>
          </div>

          {{-- Results / KPIs --}}
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-chart-bar"></i> Results / KPIs</h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addKpiBtn"><i class="fas fa-plus"></i> Add KPI</button>
            </div>
            <div class="pb-card-body">
              <div class="repeatable-container" id="kpiContainer"></div>
              <div class="pb-hint"><i class="fas fa-info-circle"></i> The first 4 KPIs also populate the hero stat card on the detail page.</div>
            </div>
          </div>

          {{-- Technologies Used --}}
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-code"></i> Technologies Used</h2></div>
            <div class="pb-card-body">
              <div class="tags-wrap" id="csTechTagsWrap">
                <input class="tags-input" id="csTechInput" placeholder="Add tech, press Enter…"/>
              </div>
              <input type="hidden" name="cs_technologies" id="csTechHidden" value="{{ old('cs_technologies', $typeData->technologies ?? '') }}"/>
            </div>
          </div>

          {{-- FIX: NEW — Screenshots / Gallery --}}
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-images"></i> Screenshots / Gallery</h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addCsGalleryBtn"><i class="fas fa-plus"></i> Add Image</button>
            </div>
            <div class="pb-card-body">
              <div id="csGalleryContainer" style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;"></div>
            </div>
          </div>

          {{-- Client Testimonial --}}
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-quote-left"></i> Client Testimonial <span class="lbl-badge lbl-opt" style="font-size:.58rem;">Optional</span></h2></div>
            <div class="pb-card-body">
              <div class="pb-form-group">
                <label class="pb-label"><i class="fas fa-comment-dots"></i> Quote</label>
                <textarea name="cs_testimonial_quote" class="pb-textarea" rows="3" placeholder="What did the client say?">{{ old('cs_testimonial_quote', $typeData->testimonial_quote ?? '') }}</textarea>
              </div>
              <div class="pb-grid-2">
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-user"></i> Client Name</label>
                  <input type="text" name="cs_testimonial_name" class="pb-input" placeholder="Jane Smith" value="{{ old('cs_testimonial_name', $typeData->testimonial_name ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-briefcase"></i> Title / Role</label>
                  <input type="text" name="cs_testimonial_role" class="pb-input" placeholder="CEO, Acme Corp" value="{{ old('cs_testimonial_role', $typeData->testimonial_role ?? '') }}"/>
                </div>
              </div>
            </div>
          </div>
        </div>{{-- /section-casestudy --}}

        {{-- ─── TEAM MEMBER SECTION ─── --}}
        <div class="pb-section {{ $currentPageType === 'team' ? 'active' : '' }}" id="section-team">

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-id-card"></i> Member Details</h2></div>
            <div class="pb-card-body">
              <div class="pb-grid-2">
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-user-tie"></i> Job Title / Role</label>
                  <input type="text" name="job_title" class="pb-input" placeholder="e.g. Lead Developer" value="{{ old('job_title', $typeData->job_title ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-building"></i> Department</label>
                  <input type="text" name="department" class="pb-input" placeholder="e.g. Engineering" value="{{ old('department', $typeData->department ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-envelope"></i> Email <span class="lbl-badge lbl-opt">Optional</span></label>
                  <input type="email" name="member_email" class="pb-input" placeholder="member@company.com" value="{{ old('member_email', $typeData->member_email ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-phone"></i> Phone <span class="lbl-badge lbl-opt">Optional</span></label>
                  <input type="text" name="member_phone" class="pb-input" placeholder="+1 234 567 8900" value="{{ old('member_phone', $typeData->member_phone ?? '') }}"/>
                </div>
              </div>
              <div class="pb-form-group">
                <label class="pb-label"><i class="fas fa-map-marker-alt"></i> Location <span class="lbl-badge lbl-opt">Optional</span></label>
                <input type="text" name="member_location" class="pb-input" placeholder="New York, NY" value="{{ old('member_location', $typeData->member_location ?? '') }}"/>
              </div>
            </div>
          </div>

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-align-left"></i> Biography</h2></div>
            <div class="pb-card-body" style="padding:0;">
              <div class="ql-wrapper"><div id="quillBio"></div></div>
              <textarea name="bio" id="memberBio" style="display:none;">{{ old('bio', $typeData->bio ?? '') }}</textarea>
            </div>
          </div>

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-star"></i> Skills</h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addSkillBtn"><i class="fas fa-plus"></i> Add Skill</button>
            </div>
            <div class="pb-card-body">
              <div class="repeatable-container" id="skillsContainer"></div>
              <div class="pb-hint" style="margin-top:8px;"><i class="fas fa-info-circle"></i> Add skill name and proficiency level (0–100)</div>
            </div>
          </div>

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-share-alt"></i> Social Links</h2></div>
            <div class="pb-card-body">
              <div class="pb-grid-2">
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fab fa-linkedin" style="color:#0077b5;"></i> LinkedIn</label>
                  <input type="url" name="social_linkedin" class="pb-input" placeholder="https://linkedin.com/in/…" value="{{ old('social_linkedin', $typeData->social_linkedin ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fab fa-twitter" style="color:#1da1f2;"></i> Twitter / X</label>
                  <input type="url" name="social_twitter" class="pb-input" placeholder="https://twitter.com/…" value="{{ old('social_twitter', $typeData->social_twitter ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fab fa-github" style="color:var(--text-dark);"></i> GitHub</label>
                  <input type="url" name="social_github" class="pb-input" placeholder="https://github.com/…" value="{{ old('social_github', $typeData->social_github ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-globe" style="color:var(--primary);"></i> Website</label>
                  <input type="url" name="social_website" class="pb-input" placeholder="https://…" value="{{ old('social_website', $typeData->social_website ?? '') }}"/>
                </div>
              </div>
            </div>
          </div>
        </div>{{-- /section-team --}}

        {{-- ─── TESTIMONIAL SECTION ─── --}}
        <div class="pb-section {{ $currentPageType === 'testimonial' ? 'active' : '' }}" id="section-testimonial">

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-quote-left"></i> Testimonial Details</h2></div>
            <div class="pb-card-body">
              <div class="pb-form-group">
                <label class="pb-label"><i class="fas fa-comment-dots"></i> Quote / Review</label>
                <textarea name="testimonial_quote" class="pb-textarea" rows="5"
                  placeholder="Write the client's testimonial here…">{{ old('testimonial_quote', $typeData->testimonial_quote ?? '') }}</textarea>
              </div>
              <div class="pb-grid-2">
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-user"></i> Client Name</label>
                  <input type="text" name="testimonial_name" class="pb-input" placeholder="Jane Smith" value="{{ old('testimonial_name', $typeData->testimonial_name ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-briefcase"></i> Title &amp; Company</label>
                  <input type="text" name="testimonial_role" class="pb-input" placeholder="CEO, Acme Corp" value="{{ old('testimonial_role', $typeData->testimonial_role ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-industry"></i> Industry</label>
                  <input type="text" name="testimonial_industry" class="pb-input" placeholder="e.g. SaaS, E-Commerce" value="{{ old('testimonial_industry', $typeData->testimonial_industry ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-link"></i> Project / Service</label>
                  <input type="text" name="testimonial_service" class="pb-input" placeholder="e.g. Web App Development" value="{{ old('testimonial_service', $typeData->testimonial_service ?? '') }}"/>
                </div>
              </div>
              <div class="pb-form-group">
                <label class="pb-label"><i class="fas fa-star"></i> Rating</label>
                @php $savedRating = old('testimonial_rating', $typeData->testimonial_rating ?? 5); @endphp
                <div class="star-rating" id="starRating">
                  @for($s = 1; $s <= 5; $s++)
                    <button type="button" class="star-btn {{ $s <= $savedRating ? 'lit' : '' }}" data-val="{{ $s }}">★</button>
                  @endfor
                </div>
                <input type="hidden" name="testimonial_rating" id="testimonialRating" value="{{ $savedRating }}"/>
              </div>
              <div class="pb-form-group">
                <label class="pb-label"><i class="fas fa-video"></i> Video Testimonial URL <span class="lbl-badge lbl-opt">Optional</span></label>
                <input type="url" name="testimonial_video" class="pb-input" placeholder="YouTube or Vimeo URL" value="{{ old('testimonial_video', $typeData->testimonial_video ?? '') }}"/>
              </div>
            </div>
          </div>
        </div>{{-- /section-testimonial --}}

        {{-- ─── FAQ SECTION ─── --}}
        <div class="pb-section {{ $currentPageType === 'faq' ? 'active' : '' }}" id="section-faq">

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-info-circle"></i> FAQ Category Info</h2></div>
            <div class="pb-card-body">
              <div class="pb-grid-2">
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-folder"></i> Category</label>
                  <select name="faq_category" class="pb-select">
                    @foreach(['general','services','pricing','technical','support','billing','legal'] as $fc)
                      <option value="{{ $fc }}" {{ old('faq_category', $typeData->faq_category ?? 'general') === $fc ? 'selected' : '' }}>{{ ucfirst($fc) }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-sort-numeric-up"></i> Sort Order</label>
                  <input type="number" name="faq_order" class="pb-input" placeholder="1" min="0" value="{{ old('faq_order', $typeData->faq_order ?? 0) }}"/>
                </div>
              </div>
            </div>
          </div>

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-list-ul"></i> FAQ Items</h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addFaqBtn"><i class="fas fa-plus"></i> Add Q&amp;A</button>
            </div>
            <div class="pb-card-body">
              <div class="repeatable-container" id="faqContainer"></div>
            </div>
          </div>
        </div>{{-- /section-faq --}}

        {{-- ─── PORTFOLIO SECTION ─── --}}
        <div class="pb-section {{ $currentPageType === 'portfolio' ? 'active' : '' }}" id="section-portfolio">

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-info-circle"></i> Project Details</h2></div>
            <div class="pb-card-body">
              <div class="pb-form-group">
                <label class="pb-label"><i class="fas fa-align-left"></i> Short Description</label>
                <div class="pb-input-wrap">
                  <textarea name="portfolio_desc" id="portfolioDesc" class="pb-textarea" rows="3"
                    placeholder="Brief project description…" maxlength="180">{{ old('portfolio_desc', $typeData->portfolio_desc ?? '') }}</textarea>
                  <span class="pb-char-count" id="portfolioDescCount">0/180</span>
                </div>
              </div>
              <div class="pb-grid-3">
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-th-large"></i> Category</label>
                  <select name="portfolio_category" class="pb-select">
                    <option value="">— Select —</option>
                    @foreach(['web'=>'Web Development','mobile'=>'Mobile App','design'=>'UI/UX Design','ai'=>'AI / ML','ecommerce'=>'E-Commerce','saas'=>'SaaS','devops'=>'Cloud / DevOps'] as $val => $lbl)
                      <option value="{{ $val }}" {{ old('portfolio_category', $typeData->portfolio_category ?? '') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-calendar-alt"></i> Year</label>
                  <input type="number" name="portfolio_year" class="pb-input" placeholder="2024" min="2000" max="2099" value="{{ old('portfolio_year', $typeData->portfolio_year ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-external-link-alt"></i> Live URL</label>
                  <input type="url" name="portfolio_url" class="pb-input" placeholder="https://…" value="{{ old('portfolio_url', $typeData->portfolio_url ?? '') }}"/>
                </div>
              </div>
              <div class="pb-form-group">
                <label class="pb-label"><i class="fas fa-code"></i> Technologies</label>
                <div class="tags-wrap" id="portfolioTechWrap">
                  <input class="tags-input" id="portfolioTechInput" placeholder="Add tech, press Enter…"/>
                </div>
                <input type="hidden" name="portfolio_tech" id="portfolioTechHidden" value="{{ old('portfolio_tech', $typeData->portfolio_tech ?? '') }}"/>
              </div>
            </div>
          </div>

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-align-left"></i> Full Description</h2></div>
            <div class="pb-card-body" style="padding:0;">
              <div class="ql-wrapper"><div id="quillPortfolio"></div></div>
              <textarea name="portfolio_content" id="portfolioContent" style="display:none;">{{ old('portfolio_content', $typeData->portfolio_content ?? '') }}</textarea>
            </div>
          </div>

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-images"></i> Project Gallery</h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addGalleryBtn"><i class="fas fa-plus"></i> Add Image</button>
            </div>
            <div class="pb-card-body">
              <div id="galleryContainer" style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;"></div>
            </div>
          </div>
        </div>{{-- /section-portfolio --}}

        {{-- ─── BLOG SECTION ─── --}}
        <div class="pb-section {{ $currentPageType === 'blog' ? 'active' : '' }}" id="section-blog">
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-pen-nib"></i> Blog Content</h2></div>
            <div class="pb-card-body" style="padding:0;">
              <div class="ql-wrapper"><div id="quillBlog"></div></div>
              <textarea name="blog_content" id="blogContent" style="display:none;">{{ old('blog_content', $typeData->blog_content ?? '') }}</textarea>
            </div>
          </div>
          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-align-left"></i> Excerpt</h2></div>
            <div class="pb-card-body">
              <div class="pb-input-wrap">
                <textarea name="excerpt" id="blogExcerpt" class="pb-textarea" rows="4"
                  placeholder="Write a brief summary…" maxlength="300">{{ old('excerpt', $typeData->excerpt ?? '') }}</textarea>
                <span class="pb-char-count" id="blogExcerptCount">0/300</span>
              </div>
            </div>
          </div>
        </div>

        {{-- ─── LANDING PAGE SECTION ─── --}}
        <div class="pb-section {{ $currentPageType === 'landing' ? 'active' : '' }}" id="section-landing">

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-star"></i> Hero Section</h2></div>
            <div class="pb-card-body">
              <div class="pb-form-group">
                <label class="pb-label"><i class="fas fa-heading"></i> Hero Headline</label>
                <input type="text" name="hero_headline" class="pb-input" placeholder="Bold, benefit-driven headline…" value="{{ old('hero_headline', $typeData->hero_headline ?? '') }}"/>
              </div>
              <div class="pb-form-group">
                <label class="pb-label"><i class="fas fa-align-left"></i> Hero Subheadline</label>
                <input type="text" name="hero_subheadline" class="pb-input" placeholder="Supporting sentence…" value="{{ old('hero_subheadline', $typeData->hero_subheadline ?? '') }}"/>
              </div>
              <div class="pb-grid-2">
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-hand-pointer"></i> Primary CTA Text</label>
                  <input type="text" name="cta_primary_text" class="pb-input" placeholder="Get Started Free" value="{{ old('cta_primary_text', $typeData->cta_primary_text ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-link"></i> Primary CTA URL</label>
                  <input type="url" name="cta_primary_url" class="pb-input" placeholder="https://…" value="{{ old('cta_primary_url', $typeData->cta_primary_url ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-hand-pointer"></i> Secondary CTA Text <span class="lbl-badge lbl-opt">Optional</span></label>
                  <input type="text" name="cta_secondary_text" class="pb-input" placeholder="See Demo" value="{{ old('cta_secondary_text', $typeData->cta_secondary_text ?? '') }}"/>
                </div>
                <div class="pb-form-group">
                  <label class="pb-label"><i class="fas fa-link"></i> Secondary CTA URL</label>
                  <input type="url" name="cta_secondary_url" class="pb-input" placeholder="https://…" value="{{ old('cta_secondary_url', $typeData->cta_secondary_url ?? '') }}"/>
                </div>
              </div>
            </div>
          </div>

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header"><h2><i class="fas fa-pen-nib"></i> Page Body Content</h2></div>
            <div class="pb-card-body" style="padding:0;">
              <div class="ql-wrapper"><div id="quillLanding"></div></div>
              <textarea name="landing_content" id="landingContent" style="display:none;">{{ old('landing_content', $typeData->landing_content ?? '') }}</textarea>
            </div>
          </div>

          <div class="pb-card" style="margin-top:16px;">
            <div class="pb-card-header">
              <h2><i class="fas fa-chart-bar"></i> Stats / Social Proof</h2>
              <button type="button" class="btn-pb btn-pb-outline btn-sm" id="addLandingStatBtn"><i class="fas fa-plus"></i> Add Stat</button>
            </div>
            <div class="pb-card-body">
              <div class="repeatable-container" id="landingStatsContainer"></div>
            </div>
          </div>
        </div>{{-- /section-landing --}}

        {{-- ─── COMMON: SEO & Meta ─── --}}
        <div class="pb-card" style="margin-top:16px;">
          <div class="pb-card-header">
            <h2><i class="fas fa-search"></i> SEO &amp; Meta</h2>
            <div id="seoScoreBadge" style="font-size:.73rem;font-weight:700;padding:4px 11px;border-radius:20px;background:#eef2f9;color:var(--text-muted);">
              Score: <span id="seoScoreLabel">0</span>/100
            </div>
          </div>
          <div class="pb-card-body">

            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-key"></i> Focus Keyword <span class="lbl-badge lbl-seo">SEO</span></label>
              <div style="position:relative;">
                <input type="text" name="focus_keyword" id="focusKeyword" class="pb-input"
                  placeholder="e.g. web development services"
                  value="{{ old('focus_keyword', $page->focus_keyword ?? '') }}"/>
                <span id="kwDensityBadge" style="display:none;position:absolute;right:8px;top:50%;transform:translateY(-50%);font-size:.7rem;font-weight:700;padding:2px 8px;border-radius:8px;"></span>
              </div>
            </div>

            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-heading"></i> Meta Title <span class="lbl-badge lbl-seo">SEO</span></label>
              <div class="pb-input-wrap">
                <input type="text" name="meta_title" id="metaTitle" class="pb-input"
                  placeholder="Leave blank to use page title" maxlength="70"
                  value="{{ old('meta_title', $page->meta_title ?? '') }}"/>
                <span class="pb-char-count" id="metaTitleCounter">0/60</span>
              </div>
              <div class="meter-bar"><div class="meter-fill" id="metaTitleMeter" style="width:0%;"></div></div>
            </div>

            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-align-left"></i> Meta Description <span class="lbl-badge lbl-seo">SEO</span></label>
              <div class="pb-input-wrap">
                <textarea name="meta_description" id="metaDescription" class="pb-textarea"
                  placeholder="Compelling description for search results…" maxlength="170" rows="3">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                <span class="pb-char-count" id="metaDescCounter">0/160</span>
              </div>
              <div class="meter-bar"><div class="meter-fill" id="metaDescMeter" style="width:0%;"></div></div>
            </div>

            <div class="pb-form-group">
              <div class="serp-preview">
                <div class="serp-label"><i class="fas fa-google"></i> Google SERP Preview</div>
                <div class="serp-url"  id="serpUrl">yourdomain.com › your-slug</div>
                <a class="serp-title" id="serpTitle">Your page title will appear here…</a>
                <div class="serp-desc" id="serpDesc">Your meta description will appear here.</div>
              </div>
            </div>

            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-tags"></i> Meta Keywords <span class="lbl-badge lbl-opt">Optional</span></label>
              <div class="tags-wrap" id="metaKeywordsWrap">
                <input class="tags-input" id="metaKeywordsInput" placeholder="Type keyword, press Enter…"/>
              </div>
              <input type="hidden" name="meta_keywords" id="metaKeywordsHidden" value="{{ old('meta_keywords', $page->meta_keywords ?? '') }}"/>
            </div>

            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-link"></i> Canonical URL <span class="lbl-badge lbl-opt">Optional</span></label>
              <input type="url" name="canonical_url" class="pb-input" placeholder="https://…"
                value="{{ old('canonical_url', $page->canonical_url ?? '') }}"/>
            </div>

            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-robot"></i> Robots <span class="lbl-badge lbl-seo">SEO</span></label>
              <select name="robots" class="pb-select">
                @foreach(['index, follow','noindex, follow','index, nofollow','noindex, nofollow'] as $r)
                  <option value="{{ $r }}" {{ old('robots', $page->robots ?? 'index, follow') === $r ? 'selected' : '' }}>{{ $r }}</option>
                @endforeach
              </select>
            </div>

            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-code"></i> Schema Type <span class="lbl-badge lbl-seo">SEO</span></label>
              <div class="schema-pills" id="schemaPills">
                @php $savedSchema = old('schema_type', $page->schema_type ?? 'WebPage'); @endphp
                @foreach(['WebPage'=>'fa-file','Service'=>'fa-cogs','Article'=>'fa-newspaper','FAQPage'=>'fa-question-circle','Person'=>'fa-user','Review'=>'fa-star','ItemList'=>'fa-list'] as $sv => $si)
                  <label class="schema-pill {{ $savedSchema === $sv ? 'active' : '' }}">
                    <input type="radio" name="schema_type" value="{{ $sv }}" {{ $savedSchema === $sv ? 'checked' : '' }} style="display:none;"/>
                    <i class="fas {{ $si }}"></i> {{ $sv }}
                  </label>
                @endforeach
              </div>
            </div>

          </div>
        </div>

      </div>{{-- /pb-left --}}

      {{-- ════════════════════════════════
           RIGHT SIDEBAR
           ════════════════════════════════ --}}
      <div class="pb-right">

        {{-- SEO Score --}}
        <div class="pb-card">
          <div class="pb-card-header"><h2><i class="fas fa-chart-pie"></i> SEO Score</h2></div>
          <div class="pb-card-body">
            <div class="seo-score-wrap">
              <div class="seo-ring" id="seoRing" style="background:#e2e8f0;color:#6b7a99;">0</div>
              <div class="seo-score-info">
                <strong id="seoScoreText">Fill in fields to score</strong>
                <span id="seoScoreSubtext">Title, meta &amp; content</span>
              </div>
            </div>
            <ul class="seo-checklist">
              <li class="fail" id="ck-title"><i class="fas fa-times-circle"></i> Focus keyword in title</li>
              <li class="fail" id="ck-slug"><i class="fas fa-times-circle"></i> Keyword in slug</li>
              <li class="fail" id="ck-metadesc"><i class="fas fa-times-circle"></i> Meta description (120–160 chars)</li>
              <li class="fail" id="ck-kw-metadesc"><i class="fas fa-times-circle"></i> Keyword in meta description</li>
              <li class="fail" id="ck-content-len"><i class="fas fa-times-circle"></i> Content ≥ 200 words</li>
              <li class="fail" id="ck-content-kw"><i class="fas fa-times-circle"></i> Keyword in content</li>
              <li class="fail" id="ck-img"><i class="fas fa-times-circle"></i> Featured image set</li>
              <li class="fail" id="ck-img-alt"><i class="fas fa-times-circle"></i> Image alt text set</li>
              <li class="fail" id="ck-metatitle-len"><i class="fas fa-times-circle"></i> Meta title 30–60 chars</li>
              <li class="warn" id="ck-kw-density"><i class="fas fa-exclamation-triangle"></i> Keyword density 1–3%</li>
            </ul>
          </div>
        </div>

        {{-- Featured Image --}}
        <div class="pb-card" style="margin-top:16px;">
          <div class="pb-card-header"><h2><i class="fas fa-image"></i> Featured Image</h2></div>
          <div class="pb-card-body">
            <div class="img-upload-zone" id="featuredImgZone" {{ isset($page) && $page->featured_image ? 'style=display:none;' : '' }}>
              <input type="file" name="featured_image" id="featuredImgInput" accept="image/jpeg,image/png,image/webp,image/gif"/>
              <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
              <div class="upload-text"><strong>Click to upload</strong> or drag &amp; drop<br><span style="font-size:.7rem;">Recommended: 1200 × 630 px · Max 5 MB</span></div>
            </div>
            <div class="img-preview-wrap" id="featuredPreview" {{ isset($page) && $page->featured_image ? 'style=display:block;' : '' }}>
              <img src="{{ isset($page) && $page->featured_image ? asset($page->featured_image) : '' }}"
                   alt="{{ $page->image_alt ?? '' }}" id="featuredPreviewImg"/>
              <div class="img-preview-actions">
                <button type="button" class="btn-pb btn-pb-outline btn-sm" id="btnChangeFeatured"><i class="fas fa-exchange-alt"></i> Change</button>
                <button type="button" class="btn-pb btn-pb-danger btn-sm"  id="btnRemoveFeatured"><i class="fas fa-trash"></i></button>
              </div>
            </div>
            <div class="pb-form-group" style="margin-top:12px;">
              <label class="pb-label"><i class="fas fa-tag"></i> Alt Text <span class="lbl-badge lbl-seo">SEO</span></label>
              <input type="text" name="image_alt" id="imageAlt" class="pb-input" placeholder="Describe the image…" maxlength="125"
                value="{{ old('image_alt', $page->image_alt ?? '') }}"/>
            </div>
            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-heading"></i> Image Title <span class="lbl-badge lbl-opt">Optional</span></label>
              <input type="text" name="image_title" class="pb-input" placeholder="Tooltip text…" maxlength="125"
                value="{{ old('image_title', $page->image_title ?? '') }}"/>
            </div>
            <div class="img-size-info" id="imgSizeInfo" style="display:none;"></div>
          </div>
        </div>

        {{-- Publish Settings --}}
        <div class="pb-card" style="margin-top:16px;">
          <div class="pb-card-header"><h2><i class="fas fa-cog"></i> Publish Settings</h2></div>
          <div class="pb-card-body">

            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-eye"></i> Visibility</label>
              @php $savedVis = old('visibility', $page->visibility ?? 'public'); @endphp
              <div class="vis-pills">
                <label class="vis-pill {{ $savedVis === 'public'   ? 'active' : '' }}"><input type="radio" name="visibility" value="public"   {{ $savedVis === 'public'   ? 'checked' : '' }} style="display:none;"/><i class="fas fa-globe"></i> Public</label>
                <label class="vis-pill {{ $savedVis === 'private'  ? 'active' : '' }}"><input type="radio" name="visibility" value="private"  {{ $savedVis === 'private'  ? 'checked' : '' }} style="display:none;"/><i class="fas fa-lock"></i> Private</label>
                <label class="vis-pill {{ $savedVis === 'password' ? 'active' : '' }}"><input type="radio" name="visibility" value="password" {{ $savedVis === 'password' ? 'checked' : '' }} style="display:none;"/><i class="fas fa-key"></i> Password</label>
              </div>
            </div>

            <div class="pb-form-group" id="passwordField" style="{{ $savedVis === 'password' ? '' : 'display:none;' }}">
              <label class="pb-label"><i class="fas fa-key"></i> Page Password</label>
              <input type="password" name="page_password" class="pb-input" placeholder="Enter password…"/>
            </div>

            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-flag"></i> Status</label>
              @php $savedStatus = old('status', $page->status ?? 'draft'); @endphp
              <select name="status" id="pageStatus" class="pb-select">
                @foreach(['draft'=>'Draft','pending'=>'Pending Review','published'=>'Published','scheduled'=>'Scheduled'] as $sv => $sl)
                  <option value="{{ $sv }}" {{ $savedStatus === $sv ? 'selected' : '' }}>{{ $sl }}</option>
                @endforeach
              </select>
            </div>

            <div class="pb-form-group" id="scheduleField" style="{{ $savedStatus === 'scheduled' ? '' : 'display:none;' }}">
              <label class="pb-label"><i class="fas fa-calendar-alt"></i> Publish Date &amp; Time</label>
              <input type="datetime-local" name="published_at" class="pb-input"
                value="{{ old('published_at', isset($page) && $page->published_at ? $page->published_at->format('Y-m-d\TH:i') : '') }}"/>
            </div>

            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-sort-numeric-up"></i> Sort / Display Order</label>
              <input type="number" name="sort_order" class="pb-input" placeholder="0" min="0"
                value="{{ old('sort_order', $page->sort_order ?? 0) }}"/>
            </div>

            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-thumbtack"></i> Featured / Pinned</label>
              <select name="is_featured" class="pb-select">
                <option value="0" {{ old('is_featured', $page->is_featured ?? 0) == 0 ? 'selected' : '' }}>No</option>
                <option value="1" {{ old('is_featured', $page->is_featured ?? 0) == 1 ? 'selected' : '' }}>Yes — show on homepage / hero</option>
              </select>
            </div>

            <div style="padding-top:8px;display:flex;gap:8px;flex-direction:column;">
              <button type="button" class="btn-pb btn-pb-success" id="sidebarPublish"><i class="fas fa-rocket"></i> Publish Now</button>
              <button type="button" class="btn-pb btn-pb-outline" id="sidebarDraft"><i class="fas fa-save"></i> Save Draft</button>
            </div>
          </div>
        </div>

        {{-- Category & Tags --}}
        <div class="pb-card" style="margin-top:16px;">
          <div class="pb-card-header">
            <h2><i class="fas fa-folder"></i> Category</h2>
            <button type="button" class="btn-pb btn-pb-outline btn-sm" id="btnAddCat">+ New</button>
          </div>
          <div class="pb-card-body">
            <div class="pb-form-group" id="newCatField" style="display:none;margin-bottom:10px;">
              <div style="display:flex;gap:6px;">
                <input type="text" id="newCatInput" class="pb-input" placeholder="Category name…"/>
                <button type="button" class="btn-pb btn-pb-primary btn-sm" id="btnSaveCat">Add</button>
              </div>
            </div>
            <select name="category_id" id="categorySelect" class="pb-select">
              <option value="">— Select Category —</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                  {{ old('category_id', $page->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                  {{ $cat->name }}
                </option>
              @endforeach
              @if($categories->isEmpty())
                <option value="" disabled>No categories yet — click "+ New" to add one</option>
              @endif
            </select>
            <div class="pb-form-group" style="margin-top:12px;">
              <label class="pb-label"><i class="fas fa-tags"></i> Tags <span class="lbl-badge lbl-opt">Optional</span></label>
              <div class="tags-wrap" id="postTagsWrap">
                <input class="tags-input" id="postTagsInput" placeholder="Add tag, press Enter…"/>
              </div>
              <input type="hidden" name="tags" id="postTagsHidden" value="{{ old('tags', $page->tags ?? '') }}"/>
            </div>
          </div>
        </div>

        {{-- OG / Social --}}
        <div class="pb-card" style="margin-top:16px;">
          <div class="pb-card-header"><h2><i class="fas fa-share-alt"></i> Social Preview</h2></div>
          <div class="pb-card-body">
            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-heading"></i> OG Title <span class="lbl-badge lbl-opt">Optional</span></label>
              <input type="text" name="og_title" id="ogTitle" class="pb-input" placeholder="Custom social title…"
                value="{{ old('og_title', $page->og_title ?? '') }}"/>
            </div>
            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-align-left"></i> OG Description <span class="lbl-badge lbl-opt">Optional</span></label>
              <textarea name="og_description" id="ogDescription" class="pb-textarea" rows="2" placeholder="Custom social description…">{{ old('og_description', $page->og_description ?? '') }}</textarea>
            </div>
            <div class="pb-form-group">
              <div style="border:1.5px solid var(--border);border-radius:8px;overflow:hidden;">
                <div id="ogImgPreview" style="height:100px;background:linear-gradient(135deg,#1a3a6e,#2196f3);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.3);font-size:1.5rem;"><i class="fas fa-image"></i></div>
                <div style="padding:8px 10px;background:#f0f2f5;">
                  <div style="font-size:.62rem;text-transform:uppercase;color:#8a8d91;margin-bottom:2px;" id="ogPreviewSite">{{ strtoupper(request()->getHost()) }}</div>
                  <div style="font-size:.78rem;font-weight:700;color:#1c1e21;" id="ogPreviewTitle">Page title…</div>
                  <div style="font-size:.7rem;color:#8a8d91;" id="ogPreviewDesc">Description…</div>
                </div>
              </div>
            </div>
            <div class="pb-form-group">
              <label class="pb-label"><i class="fab fa-twitter" style="color:#1da1f2;"></i> Twitter Card</label>
              <select name="twitter_card" class="pb-select">
                <option value="summary_large_image" {{ old('twitter_card', $page->twitter_card ?? 'summary_large_image') === 'summary_large_image' ? 'selected' : '' }}>summary_large_image (recommended)</option>
                <option value="summary" {{ old('twitter_card', $page->twitter_card ?? '') === 'summary' ? 'selected' : '' }}>summary</option>
              </select>
            </div>
          </div>
        </div>

        {{-- Advanced --}}
        <div class="pb-card" style="margin-top:16px;">
          <div class="pb-card-header"><h2><i class="fas fa-cogs"></i> Advanced</h2></div>
          <div class="pb-card-body">
            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-globe"></i> Language</label>
              <select name="hreflang" class="pb-select">
                @foreach(['en'=>'English (en)','en-us'=>'English US','hi'=>'Hindi (hi)','es'=>'Spanish (es)','fr'=>'French (fr)','ar'=>'Arabic (ar)'] as $hv => $hl)
                  <option value="{{ $hv }}" {{ old('hreflang', $page->hreflang ?? 'en') === $hv ? 'selected' : '' }}>{{ $hl }}</option>
                @endforeach
              </select>
            </div>
            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-sitemap"></i> Sitemap Priority</label>
              <select name="sitemap_priority" class="pb-select">
                @foreach(['1.0','0.9','0.8','0.5','0.3'] as $sp)
                  <option value="{{ $sp }}" {{ (string) old('sitemap_priority', $page->sitemap_priority ?? '0.9') === $sp ? 'selected' : '' }}>{{ $sp }}</option>
                @endforeach
              </select>
            </div>
            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-sync"></i> Change Frequency</label>
              <select name="sitemap_changefreq" class="pb-select">
                @foreach(['daily','weekly','monthly','yearly'] as $cf)
                  <option value="{{ $cf }}" {{ old('sitemap_changefreq', $page->sitemap_changefreq ?? 'daily') === $cf ? 'selected' : '' }}>{{ $cf }}</option>
                @endforeach
              </select>
            </div>
            <div class="pb-form-group">
              <label class="pb-label"><i class="fas fa-code"></i> Custom Head Script <span class="lbl-badge lbl-opt">Optional</span></label>
              <textarea name="custom_head" class="pb-textarea" rows="2" placeholder="&lt;script&gt;…&lt;/script&gt;" style="font-family:monospace;font-size:.78rem;">{{ old('custom_head', $page->custom_head_script ?? '') }}</textarea>
            </div>
          </div>
        </div>

      </div>{{-- /pb-right --}}
    </div>{{-- /pb-grid --}}
  </form>

</div>{{-- /pb-wrap --}}
</div>{{-- /pageBuilder --}}

{{-- Toast container --}}
<div class="pb-toast-stack" id="pbToastStack"></div>

{{-- Icon picker modal --}}
<div class="pb-modal-overlay" id="iconPickerModal">
  <div class="pb-modal" style="max-width:420px;">
    <div class="pb-modal-header">
      <h3><i class="fas fa-icons" style="color:var(--primary);margin-right:6px;"></i> Pick an Icon</h3>
      <button class="pb-modal-close" id="closeIconModal"><i class="fas fa-times"></i></button>
    </div>
    <div class="pb-modal-body">
      <input type="text" class="pb-input" id="iconSearch" placeholder="Search icons… e.g. cog, star, user"/>
      <div class="icon-grid" id="iconGrid" style="margin-top:12px;"></div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script src="{{ asset('assets/js/page-builder-validator.js') }}"></script>

{{-- ── VALIDATOR INIT ── --}}
<script>
$(function() {
  new PageBuilderValidator({
    formId:       'pbForm',
    checkSlugUrl: "{{ route('pages.check-slug') }}",
    csrfToken:    "{{ csrf_token() }}"
  });
});
</script>

{{-- ── MAIN PAGE BUILDER JS ── --}}
<script>
$(function () {

  /* ═══════════════════════════
     CONFIG
  ═══════════════════════════ */
  const QUILL_TOOLBAR = [
    [{ header: [1,2,3,4,false] }],
    ['bold','italic','underline','strike'],
    [{ color:[] },{ background:[] }],
    [{ align:[] }],
    [{ list:'ordered' },{ list:'bullet' }],
    ['blockquote','code-block'],
    ['link','image'],
    ['clean'],
  ];

  const PAGE_TYPES = {
    service:     { icon:'🔧', label:'Create Service',      slug:'services/',     schema:'Service'  },
    casestudy:   { icon:'📊', label:'Create Case Study',   slug:'case-studies/', schema:'Article'  },
    team:        { icon:'👤', label:'Add Team Member',     slug:'team/',         schema:'Person'   },
    testimonial: { icon:'💬', label:'Add Testimonial',     slug:'testimonials/', schema:'Review'   },
    faq:         { icon:'❓', label:'Create FAQ',          slug:'faq/',          schema:'FAQPage'  },
    portfolio:   { icon:'🖼️', label:'Add Portfolio Item', slug:'portfolio/',    schema:'ItemList' },
    blog:        { icon:'✍️', label:'Create Blog Post',   slug:'blog/',         schema:'Article'  },
    landing:     { icon:'🏠', label:'Create Landing Page', slug:'landing/',      schema:'WebPage'  },
  };

  const ICONS = [
    'fa-cogs','fa-rocket','fa-star','fa-heart','fa-check','fa-times','fa-plus','fa-minus',
    'fa-user','fa-users','fa-building','fa-home','fa-globe','fa-lock','fa-key','fa-shield-alt',
    'fa-chart-bar','fa-chart-line','fa-chart-pie','fa-trophy','fa-medal','fa-award','fa-crown',
    'fa-laptop-code','fa-mobile-alt','fa-cloud','fa-server','fa-database','fa-code','fa-terminal',
    'fa-pen-nib','fa-edit','fa-file-alt','fa-newspaper','fa-book','fa-bookmark','fa-tag',
    'fa-envelope','fa-phone','fa-map-marker-alt','fa-clock','fa-calendar-alt','fa-bell',
    'fa-lightbulb','fa-magic','fa-puzzle-piece','fa-wrench','fa-tools','fa-industry',
    'fa-handshake','fa-thumbs-up','fa-comments','fa-headset','fa-question-circle','fa-info-circle',
    'fa-search','fa-filter','fa-sort','fa-link','fa-share-alt','fa-download','fa-upload',
    'fa-dollar-sign','fa-euro-sign','fa-percent','fa-shopping-cart','fa-credit-card',
    'fa-brain','fa-robot','fa-microchip','fa-wifi','fa-satellite','fa-bolt','fa-fire',
    'fa-leaf','fa-recycle','fa-seedling','fa-sun','fa-moon','fa-water','fa-mountain',
  ];

  // FIX: Read page type from the hidden input (set by Blade to the actual saved type in edit mode)
  let currentType     = $('#pageTypeInput').val() || 'service';
  let quillInstances  = {};
  let activeIconTarget = null;
  let currentRating   = parseInt($('#testimonialRating').val()) || 5;

  /* ═══════════════════════════
     TOAST
  ═══════════════════════════ */
  function toast(msg, color, icon) {
    color = color || 'var(--primary)'; icon = icon || 'fas fa-info-circle';
    const $t = $(`<div class="pb-toast"><i class="${icon}" style="color:${color};"></i><span>${msg}</span></div>`);
    $('#pbToastStack').append($t);
    setTimeout(() => $t.fadeOut(300, () => $t.remove()), 3200);
  }
  window.pbToast = toast;

  /* ═══════════════════════════
     SLUG UTILITIES
  ═══════════════════════════ */
  function slugify(s) {
    return s.toLowerCase().replace(/[^a-z0-9\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-').replace(/^-|-$/g,'');
  }

  let slugManual = !!$('#pbSlug').val(); // In edit mode slug is already set
  $('#pbTitle').on('input', function () {
    updateTitleCounter($(this).val().length);
    if (!slugManual) $('#pbSlug').val(slugify($(this).val()));
    updateSerpPreview();
    updateSeoScore();
  });
  $('#pbSlug').on('input', function () {
    slugManual = true;
    $(this).val(slugify($(this).val()));
    updateSerpPreview();
    updateSeoScore();
  });
  $('#btnRegenSlug').on('click', () => {
    slugManual = false;
    $('#pbSlug').val(slugify($('#pbTitle').val()));
    $(document).trigger('pb:slugRegenerated');
    updateSerpPreview();
    updateSeoScore();
  });
  $('#btnCopySlug').on('click', () => {
    const url = $('#slugPrefix').text() + $('#pbSlug').val();
    navigator.clipboard.writeText(url).catch(() => {});
    toast('URL copied to clipboard', 'var(--green)', 'fas fa-check-circle');
  });

  // Init title counter
  updateTitleCounter($('#pbTitle').val().length);

  /* ═══════════════════════════
     CHAR COUNTERS
  ═══════════════════════════ */
  function updateTitleCounter(len) {
    const $c = $('#titleCounter');
    $c.text(`${len}/100`).removeClass('warn over good');
    if (len >= 50 && len <= 70) $c.addClass('good');
    else if (len > 70) $c.addClass('warn');
    if (len >= 95) $c.addClass('over');
  }

  function initCounter($input, $counter, max, idealMin, idealMax, $meter) {
    function refresh() {
      const len = $input.val().length;
      $counter.text(`${len}/${max}`).removeClass('warn over good');
      let color = 'var(--border)';
      if (len >= idealMin && len <= idealMax) { $counter.addClass('good'); color = 'var(--green)'; }
      else if (len > idealMax) { $counter.addClass(len > max ? 'over' : 'warn'); color = len > max ? 'var(--red)' : 'var(--yellow)'; }
      else if (len > 0) { $counter.addClass('warn'); color = 'var(--yellow)'; }
      if ($meter && $meter.length) $meter.css({ width: `${Math.min(100,(len/idealMax)*100)}%`, background: color });
      updateSeoScore();
    }
    $input.on('input', refresh);
    refresh();
  }

  initCounter($('#metaTitle'),      $('#metaTitleCounter'),   60,  30,  60,  $('#metaTitleMeter'));
  initCounter($('#metaDescription'),  $('#metaDescCounter'),  160, 120, 160, $('#metaDescMeter'));
  initCounter($('#svcShortDesc'),   $('#svcShortDescCount'),  200, 50,  200, null);
  initCounter($('#portfolioDesc'),  $('#portfolioDescCount'), 180, 30,  180, null);
  initCounter($('#blogExcerpt'),    $('#blogExcerptCount'),   300, 50,  300, null);

  // OG preview sync
  $('#ogTitle, #ogDescription, #pbTitle, #metaTitle, #metaDescription').on('input', updateOgPreview);

  /* ═══════════════════════════
     SERP PREVIEW
  ═══════════════════════════ */
  function updateSerpPreview() {
    const slug  = $('#pbSlug').val() || 'your-page-slug';
    const title = $('#metaTitle').val() || $('#pbTitle').val() || 'Your page title…';
    const desc  = $('#metaDescription').val() || 'Your meta description will appear here.';
    const host  = window.location.hostname || 'yourdomain.com';
    $('#serpUrl').text(`${host} › ${slug}`);
    $('#serpTitle').text(title.slice(0,70));
    $('#serpDesc').text(desc.slice(0,160));
  }
  updateSerpPreview();

  function updateOgPreview() {
    const title = $('#ogTitle').val() || $('#metaTitle').val() || $('#pbTitle').val() || 'Page title…';
    const desc  = $('#ogDescription').val() || $('#metaDescription').val() || 'Description…';
    $('#ogPreviewTitle').text(title.slice(0,88));
    $('#ogPreviewDesc').text(desc.slice(0,120));
    $('#ogPreviewSite').text((window.location.hostname || 'yourdomain.com').toUpperCase());
  }
  updateOgPreview();

  /* ═══════════════════════════
     PAGE TYPE SWITCHER
  ═══════════════════════════ */
  function switchType(type) {
    if ($('#pbForm').data('page-id') && type !== currentType) {
      toast('Page type cannot be changed after creation.', 'var(--yellow)', 'fas fa-exclamation-triangle');
      $('.type-pill').removeClass('active');
      $(`.type-pill[data-type="${currentType}"]`).addClass('active');
      return;
    }

    currentType = type;
    const cfg = PAGE_TYPES[type];

    $('.type-pill').removeClass('active');
    $(`.type-pill[data-type="${type}"]`).addClass('active');
    $('#pb-title-icon').text(cfg.icon);
    $('#pb-title-text').text(cfg.label);
    $('#pb-bread-current').text(cfg.label);
    $('#slugPrefix').text(window.location.hostname + '/' + cfg.slug);
    $('#pageTypeInput').val(type);
    $('#currentTypeLabel').text($(`.type-pill[data-type="${type}"]`).text().trim());

    $('.schema-pill').removeClass('active');
    $(`.schema-pill input[value="${cfg.schema}"]`).closest('.schema-pill').addClass('active');
    $(`.schema-pill input[value="${cfg.schema}"]`).prop('checked', true);

    $('.pb-section').removeClass('active');
    $(`#section-${type}`).addClass('active');

    initSectionQuills(type);

    slugManual = false;
    updateSerpPreview();
    updateSeoScore();

    $(document).trigger('pb:typeChanged', [type]);

    toast(`Switched to: ${cfg.label}`, 'var(--primary)', 'fas fa-layer-group');
  }

  $('.type-pill').on('click', function () { switchType($(this).data('type')); });

  /* ═══════════════════════════
     QUILL INIT PER SECTION
  ═══════════════════════════ */
  const QUILL_MAP = {
    service:     [{ editorId:'quillService',   hiddenId:'svcContent'        }],
    casestudy:   [{ editorId:'quillChallenge', hiddenId:'csChallenge'       },
                  { editorId:'quillSolution',  hiddenId:'csSolution'        }],
    team:        [{ editorId:'quillBio',       hiddenId:'memberBio'         }],
    portfolio:   [{ editorId:'quillPortfolio', hiddenId:'portfolioContent'  }],
    blog:        [{ editorId:'quillBlog',      hiddenId:'blogContent'       }],
    landing:     [{ editorId:'quillLanding',   hiddenId:'landingContent'    }],
  };

  function initSectionQuills(type) {
    if (!QUILL_MAP[type]) return;
    QUILL_MAP[type].forEach(({ editorId, hiddenId }) => {
      if (quillInstances[editorId]) return;
      const el = document.getElementById(editorId);
      if (!el) return;

      const q = new Quill(`#${editorId}`, {
        theme: 'snow',
        placeholder: 'Start writing here…',
        modules: { toolbar: QUILL_TOOLBAR },
      });

      const $hidden = $(`#${hiddenId}`);
      const existingContent = $hidden.val();
      if (existingContent && existingContent.trim()) {
        q.root.innerHTML = existingContent;
      }

      q.on('text-change', () => {
        $hidden.val(q.root.innerHTML);
        updateContentStats(q);
        updateSeoScore();
      });

      quillInstances[editorId] = q;
    });
  }
  // Init the current (default or saved) section on page load
  initSectionQuills(currentType);

  /* ═══════════════════════════
     CONTENT STATS
  ═══════════════════════════ */
  function updateContentStats(q) {
    const text  = q.getText().trim();
    const words = text ? text.split(/\s+/).filter(Boolean).length : 0;
    const $dom  = $('<div>').html(q.root.innerHTML);
    $('#wordCount').text(words);
    $('#readTime').text(Math.max(1, Math.ceil(words / 200)) + ' min');
    $('#headingCount').text($dom.find('h1,h2,h3,h4').length);
    $('#linkCount').text($dom.find('a').length);
    $('#imgCount').text($dom.find('img').length);
  }

  /* ═══════════════════════════
     SEO SCORE
  ═══════════════════════════ */
  function updateSeoScore() {
    let score = 0;
    const kw    = $('#focusKeyword').val().trim().toLowerCase();
    const title = $('#pbTitle').val().toLowerCase();
    const slug  = $('#pbSlug').val().toLowerCase();
    const meta  = $('#metaDescription').val();
    const mt    = $('#metaTitle').val();
    const alt   = $('#imageAlt').val().trim();
    const q     = Object.values(quillInstances)[0];
    const cText = q ? q.getText().toLowerCase() : '';
    const words = cText.split(/\s+/).filter(Boolean).length;
    const hasImg = $('#featuredPreview').is(':visible');

    function ck(id, pass, warn) {
      const $li = $(`#${id}`);
      $li.removeClass('pass fail warn');
      const icon = pass ? 'fa-check-circle' : (warn ? 'fa-exclamation-triangle' : 'fa-times-circle');
      $li.addClass(pass ? 'pass' : warn ? 'warn' : 'fail')
         .find('i').removeClass('fa-check-circle fa-times-circle fa-exclamation-triangle').addClass(icon);
    }

    const ck1 = kw && title.includes(kw); ck('ck-title', ck1); if (ck1) score += 12;
    const ck2 = kw && slug.includes(kw.replace(/\s+/g,'-')); ck('ck-slug', ck2); if (ck2) score += 10;
    const ml = meta.length;
    const ck3 = ml >= 120 && ml <= 160; ck('ck-metadesc', ck3, ml > 0 && !ck3); if (ck3) score += 12; else if (ml > 0) score += 4;
    const ck4 = kw && meta.toLowerCase().includes(kw); ck('ck-kw-metadesc', ck4); if (ck4) score += 10;
    const ck5 = words >= 200; ck('ck-content-len', ck5, words >= 50 && !ck5); if (ck5) score += 14; else if (words >= 50) score += 5;
    const ck6 = kw && cText.includes(kw); ck('ck-content-kw', ck6); if (ck6) score += 10;
    ck('ck-img', hasImg); if (hasImg) score += 10;
    const ck8 = alt.length > 0; ck('ck-img-alt', ck8); if (ck8) score += 8;
    const mtl = mt.length;
    const ck9 = mtl >= 30 && mtl <= 60; ck('ck-metatitle-len', ck9, mtl > 0 && !ck9); if (ck9) score += 7; else if (mtl > 0) score += 2;

    let density = 0;
    if (kw && words > 0) {
      const n = (cText.match(new RegExp(kw.replace(/[-\/\\^$*+?.()|[\]{}]/g,'\\$&'),'g'))||[]).length;
      density = (n / words) * 100;
    }
    const d12ok = density >= 1 && density <= 3;
    const d12w  = (density > 0 && density < 1) || density > 3;
    ck('ck-kw-density', d12ok, d12w); if (d12ok) score += 7; else if (d12w) score += 2;

    const ringColor = score >= 80 ? '#00c896' : score >= 55 ? '#ffb830' : '#ff4d6d';
    const label     = score >= 80 ? 'Excellent!' : score >= 60 ? 'Good' : score >= 40 ? 'Needs Work' : 'Poor';
    const sub       = score >= 80 ? 'Page is well-optimised' : score >= 60 ? 'A few improvements needed' : 'Fill in key SEO fields';
    const bgColor   = score >= 80 ? '#d4f5ec' : score >= 60 ? '#fff4d6' : '#ffe2e8';
    const txtColor  = score >= 80 ? '#00a87c' : score >= 60 ? '#b8860b' : '#ff4d6d';

    $('#seoRing').text(score).css('background', ringColor);
    $('#seoScoreText').text(label);
    $('#seoScoreSubtext').text(sub);
    $('#seoScoreLabel').text(score);
    $('#seoScoreBadge').css({ background: bgColor, color: txtColor });

    if (kw) {
      const { bg, col } = density >= 1 && density <= 3 ? { bg:'#d4f5ec', col:'#00a87c' }
                        : density > 3                  ? { bg:'#ffe2e8', col:'#ff4d6d' }
                                                       : { bg:'#e8f1fd', col:'var(--primary)' };
      $('#kwDensityBadge').show().text(density.toFixed(1)+'%').css({ background: bg, color: col });
    } else {
      $('#kwDensityBadge').hide();
    }
  }

  $('#focusKeyword, #imageAlt').on('input', updateSeoScore);

  /* ═══════════════════════════
     FEATURED IMAGE
  ═══════════════════════════ */
  function handleImageFile(file) {
    if (!file) return;
    if (file.size > 5 * 1024 * 1024) { toast('Image must be under 5 MB', 'var(--red)', 'fas fa-exclamation-circle'); return; }
    const allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!allowed.includes(file.type)) { toast('Please upload a JPG, PNG, WebP or GIF.', 'var(--red)', 'fas fa-exclamation-circle'); return; }
    const reader = new FileReader();
    reader.onload = e => {
      $('#featuredPreviewImg').attr('src', e.target.result);
      $('#featuredImgZone').hide();
      $('#featuredPreview').css('display', 'block');
      const img = new Image();
      img.onload = function () {
        $('#imgSizeInfo').text(`${this.width} × ${this.height} px · ${(file.size/1024).toFixed(0)} KB`).show();
      };
      img.src = e.target.result;
      updateSeoScore();
      toast('Featured image set', 'var(--green)', 'fas fa-image');
    };
    reader.readAsDataURL(file);
  }

  $('#featuredImgInput').on('change', function () { handleImageFile(this.files[0]); });
  $('#featuredImgZone').on('dragover', e => { e.preventDefault(); $('#featuredImgZone').addClass('drag-over'); });
  $('#featuredImgZone').on('dragleave', () => $('#featuredImgZone').removeClass('drag-over'));
  $('#featuredImgZone').on('drop', e => {
    e.preventDefault(); $('#featuredImgZone').removeClass('drag-over');
    const files = e.originalEvent.dataTransfer.files;
    if (files.length) handleImageFile(files[0]);
  });
  $('#btnChangeFeatured').on('click', () => $('#featuredImgInput').trigger('click'));
  $('#btnRemoveFeatured').on('click', () => {
    $('#featuredImgInput').val('');
    $('#featuredPreview').hide();
    $('#featuredImgZone').show();
    $('#imgSizeInfo').hide();
    updateSeoScore();
    toast('Image removed', 'var(--yellow)', 'fas fa-trash');
  });

  /* ═══════════════════════════
     TAGS INPUT (reusable)
  ═══════════════════════════ */
  function initTags(wrapId, inputId, hiddenId) {
    let tags = [];
    const $wrap = $(`#${wrapId}`), $inp = $(`#${inputId}`), $hid = $(`#${hiddenId}`);

    const existing = $hid.val();
    if (existing) {
      existing.split(',').forEach(t => { if (t.trim()) addTag(t.trim()); });
    }

    $wrap.on('click', () => $inp.focus());
    $inp.on('keydown', e => {
      if ((e.key === 'Enter' || e.key === ',') && $inp.val().trim()) {
        e.preventDefault(); addTag($inp.val().trim().replace(/,/g,'')); $inp.val('');
      }
      if (e.key === 'Backspace' && !$inp.val() && tags.length) removeTag(tags[tags.length-1]);
    });

    function addTag(t) {
      if (!t || tags.includes(t)) return; tags.push(t);
      const $p = $(`<span class="tag-pill">${$('<div>').text(t).html()}<button type="button"><i class="fas fa-times"></i></button></span>`);
      $p.find('button').on('click', () => removeTag(t));
      $inp.before($p); sync();
    }
    function removeTag(t) {
      tags = tags.filter(x => x !== t);
      $wrap.find('.tag-pill').filter((i,el) => $(el).text().trim() === t).remove(); sync();
    }
    function sync() { $hid.val(tags.join(',')); }
    return { add: addTag };
  }

  initTags('metaKeywordsWrap', 'metaKeywordsInput', 'metaKeywordsHidden');
  initTags('postTagsWrap',     'postTagsInput',     'postTagsHidden');
  const techTags      = initTags('techTagsWrap',      'techTagsInput',      'techTagsHidden');
  const csTechTags    = initTags('csTechTagsWrap',    'csTechInput',        'csTechHidden');
  const portfolioTech = initTags('portfolioTechWrap', 'portfolioTechInput', 'portfolioTechHidden');

  /* ═══════════════════════════
     REPEATABLE ITEMS
  ═══════════════════════════ */
  function makeRepeatItem(title, fieldsHtml, count) {
    return `
      <div class="repeat-item" data-index="${count}">
        <div class="repeat-item-header">
          <div class="repeat-item-title">
            <i class="fas fa-grip-vertical drag-handle"></i>
            ${title} <span class="repeat-num">#${count}</span>
          </div>
          <div class="repeat-item-actions">
            <button type="button" class="btn-icon-sq del remove-repeat-btn" title="Remove"><i class="fas fa-trash"></i></button>
          </div>
        </div>
        ${fieldsHtml}
      </div>`;
  }

  function bindRemove($container) {
    $container.on('click', '.remove-repeat-btn', function () {
      $(this).closest('.repeat-item').fadeOut(200, function () {
        $(this).remove();
        $container.find('.repeat-num').each((i, el) => $(el).text(`#${i+1}`));
      });
    });
  }

  // Features
  let featureCount = 0;
  function addFeature(data) {
    featureCount++;
    const h = `
      <div class="pb-grid-2">
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-cogs"></i> Feature Title</label>
          <input type="text" name="features[${featureCount}][title]" class="pb-input" placeholder="e.g. 24/7 Support" value="${data?.title || ''}"/>
        </div>
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-icons"></i> Icon Class</label>
          <div class="icon-picker-wrap">
            <div class="icon-preview-row">
              <div class="icon-preview"><i class="${data?.icon || 'fas fa-star'}"></i></div>
              <input type="text" name="features[${featureCount}][icon]" class="icon-input pb-input" value="${data?.icon || 'fas fa-star'}" style="border:none;box-shadow:none;"/>
              <button type="button" class="icon-btn-pick pick-icon-btn">Pick</button>
            </div>
          </div>
        </div>
      </div>
      <div class="pb-form-group">
        <label class="pb-label"><i class="fas fa-align-left"></i> Description</label>
        <textarea name="features[${featureCount}][description]" class="pb-textarea" rows="2">${data?.description || ''}</textarea>
      </div>`;
    $('#featuresContainer').append(makeRepeatItem('Feature', h, featureCount));
  }
  $('#addFeatureBtn').on('click', () => addFeature());
  @isset($typeData)
    @if(isset($typeData->features) && is_array($typeData->features))
      @foreach($typeData->features as $f)
        addFeature({ title: @json($f['title'] ?? ''), icon: @json($f['icon'] ?? 'fas fa-star'), description: @json($f['description'] ?? '') });
      @endforeach
    @endif
  @endisset
  if (featureCount === 0) addFeature();
  bindRemove($('#featuresContainer'));

  // Process Steps (Service)
  let stepCount = 0;
  function addStep(data) {
    stepCount++;
    const h = `
      <div class="pb-grid-2">
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-heading"></i> Step Title</label>
          <input type="text" name="process_steps[${stepCount}][title]" class="pb-input" placeholder="e.g. Discovery Call" value="${data?.title || ''}"/>
        </div>
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-clock"></i> Duration <span class="lbl-badge lbl-opt">Optional</span></label>
          <input type="text" name="process_steps[${stepCount}][duration]" class="pb-input" placeholder="e.g. 1–2 days" value="${data?.duration || ''}"/>
        </div>
      </div>
      <div class="pb-form-group">
        <label class="pb-label"><i class="fas fa-align-left"></i> Description</label>
        <textarea name="process_steps[${stepCount}][description]" class="pb-textarea" rows="2">${data?.description || ''}</textarea>
      </div>`;
    $('#stepsContainer').append(makeRepeatItem('Step', h, stepCount));
  }
  $('#addStepBtn').on('click', () => addStep());
  @isset($typeData)
    @if(isset($typeData->process_steps) && is_array($typeData->process_steps))
      @foreach($typeData->process_steps as $s)
        addStep({ title: @json($s['title'] ?? ''), duration: @json($s['duration'] ?? ''), description: @json($s['description'] ?? '') });
      @endforeach
    @endif
  @endisset
  bindRemove($('#stepsContainer'));

  // KPIs
  let kpiCount = 0;
  function addKpi(data) {
    kpiCount++;
    const h = `
      <div class="pb-grid-3">
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-hashtag"></i> Metric / KPI</label>
          <input type="text" name="kpis[${kpiCount}][label]" class="pb-input" placeholder="e.g. Revenue Growth" value="${data?.label || ''}"/>
        </div>
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-chart-line"></i> Value</label>
          <input type="text" name="kpis[${kpiCount}][value]" class="pb-input" placeholder="e.g. +150%" value="${data?.value || ''}"/>
        </div>
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-icons"></i> Icon</label>
          <input type="text" name="kpis[${kpiCount}][icon]" class="pb-input" placeholder="fas fa-chart-line" value="${data?.icon || ''}"/>
        </div>
      </div>`;
    $('#kpiContainer').append(makeRepeatItem('KPI', h, kpiCount));
  }
  $('#addKpiBtn').on('click', () => addKpi());
  @isset($typeData)
    @if(isset($typeData->kpis) && is_array($typeData->kpis))
      @foreach($typeData->kpis as $k)
        addKpi({ label: @json($k['label'] ?? ''), value: @json($k['value'] ?? ''), icon: @json($k['icon'] ?? '') });
      @endforeach
    @endif
  @endisset
  if (kpiCount === 0) addKpi();
  bindRemove($('#kpiContainer'));

  // Skills
  let skillCount = 0;
  function addSkill(data) {
    skillCount++;
    const h = `
      <div class="pb-grid-2">
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-star"></i> Skill Name</label>
          <input type="text" name="skills[${skillCount}][name]" class="pb-input" placeholder="e.g. React, Laravel" value="${data?.name || ''}"/>
        </div>
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-percent"></i> Level (0–100)</label>
          <input type="number" name="skills[${skillCount}][level]" class="pb-input" placeholder="85" min="0" max="100" value="${data?.level ?? ''}"/>
        </div>
      </div>`;
    $('#skillsContainer').append(makeRepeatItem('Skill', h, skillCount));
  }
  $('#addSkillBtn').on('click', () => addSkill());
  @isset($typeData)
    @if(isset($typeData->skills) && is_array($typeData->skills))
      @foreach($typeData->skills as $sk)
        addSkill({ name: @json($sk['name'] ?? ''), level: @json($sk['level'] ?? 0) });
      @endforeach
    @endif
  @endisset
  if (skillCount === 0) addSkill();
  bindRemove($('#skillsContainer'));

  // FAQ items
  let faqCount = 0;
  function addFaq(data) {
    faqCount++;
    const h = `
      <div class="pb-form-group">
        <label class="pb-label"><i class="fas fa-question"></i> Question</label>
        <input type="text" name="faqs[${faqCount}][question]" class="pb-input" placeholder="Frequently asked question…" value="${data?.question || ''}"/>
      </div>
      <div class="pb-form-group">
        <label class="pb-label"><i class="fas fa-comment-dots"></i> Answer</label>
        <textarea name="faqs[${faqCount}][answer]" class="pb-textarea" rows="3">${data?.answer || ''}</textarea>
      </div>`;
    $('#faqContainer').append(makeRepeatItem('Q&A', h, faqCount));
  }
  $('#addFaqBtn').on('click', () => addFaq());
  @isset($typeData)
    @if(isset($typeData->faq_items) && is_array($typeData->faq_items))
      @foreach($typeData->faq_items as $fq)
        addFaq({ question: @json($fq['question'] ?? ''), answer: @json($fq['answer'] ?? '') });
      @endforeach
    @endif
  @endisset
  if (faqCount === 0) addFaq();
  bindRemove($('#faqContainer'));

  // Landing stats
  let landingStatCount = 0;
  function addLandingStat(data) {
    landingStatCount++;
    const h = `
      <div class="pb-grid-3">
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-hashtag"></i> Stat Value</label>
          <input type="text" name="landing_stats[${landingStatCount}][value]" class="pb-input" placeholder="e.g. 500+" value="${data?.value || ''}"/>
        </div>
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-tag"></i> Label</label>
          <input type="text" name="landing_stats[${landingStatCount}][label]" class="pb-input" placeholder="e.g. Projects Delivered" value="${data?.label || ''}"/>
        </div>
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-icons"></i> Icon</label>
          <input type="text" name="landing_stats[${landingStatCount}][icon]" class="pb-input" placeholder="fas fa-trophy" value="${data?.icon || ''}"/>
        </div>
      </div>`;
    $('#landingStatsContainer').append(makeRepeatItem('Stat', h, landingStatCount));
  }
  $('#addLandingStatBtn').on('click', () => addLandingStat());
  @isset($typeData)
    @if(isset($typeData->landing_stats) && is_array($typeData->landing_stats))
      @foreach($typeData->landing_stats as $ls)
        addLandingStat({ value: @json($ls['value'] ?? ''), label: @json($ls['label'] ?? ''), icon: @json($ls['icon'] ?? '') });
      @endforeach
    @endif
  @endisset
  if (landingStatCount === 0) addLandingStat();
  bindRemove($('#landingStatsContainer'));

  // Gallery (Portfolio)
  let galleryCount = 0;
  $('#addGalleryBtn').on('click', () => {
    galleryCount++;
    const $input = $(`<input type="file" name="gallery[${galleryCount}]" accept="image/*" style="display:none;"/>`);
    $('body').append($input);
    $input.trigger('click');
    $input.on('change', function () {
      const file = this.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => {
        const $thumb = $(`
          <div style="position:relative;aspect-ratio:1;border-radius:8px;overflow:hidden;border:1.5px solid var(--border);">
            <img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;"/>
            <button type="button" style="position:absolute;top:5px;right:5px;width:24px;height:24px;border-radius:50%;border:none;background:rgba(255,77,109,.85);color:#fff;cursor:pointer;font-size:.65rem;" onclick="$(this).closest('div').remove()">
              <i class="fas fa-times"></i>
            </button>
          </div>`);
        $('#galleryContainer').append($thumb);
      };
      reader.readAsDataURL(file);
      $input.remove();
    });
  });

  /* ═══════════════════════════
     CASE STUDY — NEW REPEATERS
  ═══════════════════════════ */

  // Existing Challenges
  let challengeCount = 0;
  function addChallengeItem(data) {
    challengeCount++;
    const h = `<div class="pb-form-group">
        <label class="pb-label"><i class="fas fa-times-circle"></i> Challenge Text</label>
        <input type="text" name="existing_challenges[${challengeCount}][text]" class="pb-input" placeholder="e.g. Fragmented systems across states" value="${data?.text || ''}"/>
      </div>`;
    $('#challengesContainer').append(makeRepeatItem('Challenge', h, challengeCount));
  }
  $('#addChallengeBtn').on('click', () => addChallengeItem());
  @isset($typeData)
    @if(isset($typeData->existing_challenges) && is_array($typeData->existing_challenges))
      @foreach($typeData->existing_challenges as $c)
        addChallengeItem({ text: @json($c['text'] ?? '') });
      @endforeach
    @endif
  @endisset
  if (challengeCount === 0) addChallengeItem();
  bindRemove($('#challengesContainer'));

  // Goals
  let goalCount = 0;
  function addGoal(data) {
    goalCount++;
    const h = `
      <div class="pb-grid-3">
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-heading"></i> Title</label>
          <input type="text" name="goals[${goalCount}][title]" class="pb-input" placeholder="e.g. Reduce Wait Time" value="${data?.title || ''}"/>
        </div>
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-icons"></i> Icon</label>
          <input type="text" name="goals[${goalCount}][icon]" class="pb-input" placeholder="fas fa-user-clock" value="${data?.icon || 'fas fa-bullseye'}"/>
        </div>
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-palette"></i> Color</label>
          <select name="goals[${goalCount}][color]" class="pb-select">
            ${['cc-blue','cc-green','cc-purple','cc-yellow'].map(c => `<option value="${c}" ${data?.color===c?'selected':''}>${c}</option>`).join('')}
          </select>
        </div>
      </div>
      <div class="pb-form-group">
        <label class="pb-label"><i class="fas fa-align-left"></i> Description</label>
        <textarea name="goals[${goalCount}][desc]" class="pb-textarea" rows="2">${data?.desc || ''}</textarea>
      </div>`;
    $('#goalsContainer').append(makeRepeatItem('Goal', h, goalCount));
  }
  $('#addGoalBtn').on('click', () => addGoal());
  @isset($typeData)
    @if(isset($typeData->goals) && is_array($typeData->goals))
      @foreach($typeData->goals as $g)
        addGoal({ title: @json($g['title'] ?? ''), icon: @json($g['icon'] ?? ''), color: @json($g['color'] ?? ''), desc: @json($g['desc'] ?? '') });
      @endforeach
    @endif
  @endisset
  if (goalCount === 0) addGoal();
  bindRemove($('#goalsContainer'));

  // Solution Modules
  let moduleCount = 0;
  function addModule(data) {
    moduleCount++;
    const h = `
      <div class="pb-grid-2">
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-heading"></i> Module Name</label>
          <input type="text" name="solution_modules[${moduleCount}][name]" class="pb-input" value="${data?.name || ''}"/>
        </div>
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-icons"></i> Icon</label>
          <input type="text" name="solution_modules[${moduleCount}][icon]" class="pb-input" value="${data?.icon || 'fas fa-cube'}"/>
        </div>
      </div>
      <div class="pb-form-group">
        <label class="pb-label"><i class="fas fa-align-left"></i> Description</label>
        <textarea name="solution_modules[${moduleCount}][desc]" class="pb-textarea" rows="2">${data?.desc || ''}</textarea>
      </div>`;
    $('#modulesContainer').append(makeRepeatItem('Module', h, moduleCount));
  }
  $('#addModuleBtn').on('click', () => addModule());
  @isset($typeData)
    @if(isset($typeData->solution_modules) && is_array($typeData->solution_modules))
      @foreach($typeData->solution_modules as $m)
        addModule({ name: @json($m['name'] ?? ''), icon: @json($m['icon'] ?? ''), desc: @json($m['desc'] ?? '') });
      @endforeach
    @endif
  @endisset
  bindRemove($('#modulesContainer'));

  // Tech Stack (grouped)
  let techGroupCount = 0;
  function addTechGroup(data) {
    techGroupCount++;
    const h = `
      <div class="pb-form-group">
        <label class="pb-label"><i class="fas fa-folder"></i> Category Name</label>
        <input type="text" name="tech_stack[${techGroupCount}][category]" class="pb-input" placeholder="e.g. Frontend" value="${data?.category || ''}"/>
      </div>
      <div class="pb-form-group">
        <label class="pb-label"><i class="fas fa-code"></i> Items (comma-separated)</label>
        <input type="text" name="tech_stack[${techGroupCount}][items]" class="pb-input" placeholder="React.js, Next.js, TypeScript" value="${data?.items || ''}"/>
      </div>`;
    $('#techStackContainer').append(makeRepeatItem('Category', h, techGroupCount));
  }
  $('#addTechGroupBtn').on('click', () => addTechGroup());
  @isset($typeData)
    @if(isset($typeData->tech_stack) && is_array($typeData->tech_stack))
      @foreach($typeData->tech_stack as $t)
        addTechGroup({ category: @json($t['category'] ?? ''), items: @json($t['items'] ?? '') });
      @endforeach
    @endif
  @endisset
  bindRemove($('#techStackContainer'));

  // Process Steps (Case Study)
  let csStepCount = 0;
  function addCsStep(data) {
    csStepCount++;
    const h = `
      <div class="pb-grid-2">
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-tag"></i> Badge</label>
          <input type="text" name="cs_process_steps[${csStepCount}][badge]" class="pb-input" placeholder="e.g. Weeks 1-3" value="${data?.badge || ''}"/>
        </div>
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-heading"></i> Title</label>
          <input type="text" name="cs_process_steps[${csStepCount}][title]" class="pb-input" value="${data?.title || ''}"/>
        </div>
      </div>
      <div class="pb-form-group">
        <label class="pb-label"><i class="fas fa-align-left"></i> Description</label>
        <textarea name="cs_process_steps[${csStepCount}][desc]" class="pb-textarea" rows="2">${data?.desc || ''}</textarea>
      </div>`;
    $('#csStepsContainer').append(makeRepeatItem('Step', h, csStepCount));
  }
  $('#addCsStepBtn').on('click', () => addCsStep());
  @isset($typeData)
    @if(isset($typeData->cs_process_steps) && is_array($typeData->cs_process_steps))
      @foreach($typeData->cs_process_steps as $s)
        addCsStep({ badge: @json($s['badge'] ?? ''), title: @json($s['title'] ?? ''), desc: @json($s['desc'] ?? '') });
      @endforeach
    @endif
  @endisset
  bindRemove($('#csStepsContainer'));

  // Achievements
  let achievementCount = 0;
  function addAchievement(data) {
    achievementCount++;
    const h = `
      <div class="pb-form-group">
        <label class="pb-label"><i class="fas fa-heading"></i> Title</label>
        <input type="text" name="achievements[${achievementCount}][title]" class="pb-input" value="${data?.title || ''}"/>
      </div>
      <div class="pb-form-group">
        <label class="pb-label"><i class="fas fa-align-left"></i> Description</label>
        <textarea name="achievements[${achievementCount}][desc]" class="pb-textarea" rows="2">${data?.desc || ''}</textarea>
      </div>`;
    $('#achievementsContainer').append(makeRepeatItem('Achievement', h, achievementCount));
  }
  $('#addAchievementBtn').on('click', () => addAchievement());
  @isset($typeData)
    @if(isset($typeData->achievements) && is_array($typeData->achievements))
      @foreach($typeData->achievements as $a)
        addAchievement({ title: @json($a['title'] ?? ''), desc: @json($a['desc'] ?? '') });
      @endforeach
    @endif
  @endisset
  bindRemove($('#achievementsContainer'));

  // Features Developed (Case Study)
  let csFeatureCount = 0;
  function addCsFeature(data) {
    csFeatureCount++;
    const h = `
      <div class="pb-grid-2">
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-heading"></i> Title</label>
          <input type="text" name="cs_features[${csFeatureCount}][title]" class="pb-input" placeholder="e.g. HD Video Consultation" value="${data?.title || ''}"/>
        </div>
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-icons"></i> Icon</label>
          <input type="text" name="cs_features[${csFeatureCount}][icon]" class="pb-input" placeholder="fas fa-video" value="${data?.icon || 'fas fa-star'}"/>
        </div>
      </div>
      <div class="pb-form-group">
        <label class="pb-label"><i class="fas fa-align-left"></i> Description</label>
        <textarea name="cs_features[${csFeatureCount}][desc]" class="pb-textarea" rows="2">${data?.desc || ''}</textarea>
      </div>`;
    $('#csFeaturesContainer').append(makeRepeatItem('Feature', h, csFeatureCount));
  }
  $('#addCsFeatureBtn').on('click', () => addCsFeature());
  @isset($typeData)
    @if(isset($typeData->cs_features) && is_array($typeData->cs_features))
      @foreach($typeData->cs_features as $f)
        addCsFeature({ title: @json($f['title'] ?? ''), icon: @json($f['icon'] ?? 'fas fa-star'), desc: @json($f['desc'] ?? '') });
      @endforeach
    @endif
  @endisset
  bindRemove($('#csFeaturesContainer'));
  [
    'featuresContainer','stepsContainer','kpiContainer','skillsContainer','faqContainer',
    'landingStatsContainer','challengesContainer','goalsContainer','modulesContainer',
    'techStackContainer','csStepsContainer','achievementsContainer','baContainer',
    'complianceContainer','csFeaturesContainer','csFaqContainer',
  ].forEach(id => { /* ...unchanged... */ });

  

  // FAQ (Case Study)
  let csFaqCount = 0;
  function addCsFaq(data) {
    csFaqCount++;
    const h = `
      <div class="pb-form-group">
        <label class="pb-label"><i class="fas fa-question"></i> Question</label>
        <input type="text" name="cs_faqs[${csFaqCount}][question]" class="pb-input" placeholder="Frequently asked question…" value="${data?.question || ''}"/>
      </div>
      <div class="pb-form-group">
        <label class="pb-label"><i class="fas fa-comment-dots"></i> Answer</label>
        <textarea name="cs_faqs[${csFaqCount}][answer]" class="pb-textarea" rows="3">${data?.answer || ''}</textarea>
      </div>`;
    $('#csFaqContainer').append(makeRepeatItem('Q&A', h, csFaqCount));
  }
  $('#addCsFaqBtn').on('click', () => addCsFaq());
  @isset($typeData)
    @if(isset($typeData->cs_faqs) && is_array($typeData->cs_faqs))
      @foreach($typeData->cs_faqs as $fq)
        addCsFaq({ question: @json($fq['question'] ?? ''), answer: @json($fq['answer'] ?? '') });
      @endforeach
    @endif
  @endisset
  bindRemove($('#csFaqContainer'));

  // Before / After
  let baCount = 0;
  function addBa(data) {
    baCount++;
    const h = `
      <div class="pb-grid-2">
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-times-circle"></i> Before</label>
          <input type="text" name="before_after[${baCount}][before]" class="pb-input" value="${data?.before || ''}"/>
        </div>
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-check-circle"></i> After</label>
          <input type="text" name="before_after[${baCount}][after]" class="pb-input" value="${data?.after || ''}"/>
        </div>
      </div>`;
    $('#baContainer').append(makeRepeatItem('Row', h, baCount));
  }
  $('#addBaBtn').on('click', () => addBa());
  @isset($typeData)
    @if(isset($typeData->before_after) && is_array($typeData->before_after))
      @foreach($typeData->before_after as $b)
        addBa({ before: @json($b['before'] ?? ''), after: @json($b['after'] ?? '') });
      @endforeach
    @endif
  @endisset
  bindRemove($('#baContainer'));

  // Compliance / Trust Badges
  let complianceCount = 0;
  function addCompliance(data) {
    complianceCount++;
    const h = `
      <div class="pb-grid-2">
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-heading"></i> Title</label>
          <input type="text" name="compliance_items[${complianceCount}][title]" class="pb-input" value="${data?.title || ''}"/>
        </div>
        <div class="pb-form-group">
          <label class="pb-label"><i class="fas fa-icons"></i> Icon</label>
          <input type="text" name="compliance_items[${complianceCount}][icon]" class="pb-input" value="${data?.icon || 'fas fa-shield-alt'}"/>
        </div>
      </div>
      <div class="pb-form-group">
        <label class="pb-label"><i class="fas fa-align-left"></i> Description</label>
        <textarea name="compliance_items[${complianceCount}][desc]" class="pb-textarea" rows="2">${data?.desc || ''}</textarea>
      </div>`;
    $('#complianceContainer').append(makeRepeatItem('Badge', h, complianceCount));
  }
  $('#addComplianceBtn').on('click', () => addCompliance());
  @isset($typeData)
    @if(isset($typeData->compliance_items) && is_array($typeData->compliance_items))
      @foreach($typeData->compliance_items as $c)
        addCompliance({ title: @json($c['title'] ?? ''), icon: @json($c['icon'] ?? ''), desc: @json($c['desc'] ?? '') });
      @endforeach
    @endif
  @endisset
  bindRemove($('#complianceContainer'));

  // Case Study Gallery (mirrors portfolio gallery pattern)
  let csGalleryCount = 0;
  $('#addCsGalleryBtn').on('click', () => {
    csGalleryCount++;
    const $input = $(`<input type="file" name="cs_gallery[${csGalleryCount}]" accept="image/*" style="display:none;"/>`);
    $('body').append($input);
    $input.trigger('click');
    $input.on('change', function () {
      const file = this.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => {
        const $thumb = $(`
          <div style="position:relative;aspect-ratio:1;border-radius:8px;overflow:hidden;border:1.5px solid var(--border);">
            <img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;"/>
            <button type="button" style="position:absolute;top:5px;right:5px;width:24px;height:24px;border-radius:50%;border:none;background:rgba(255,77,109,.85);color:#fff;cursor:pointer;font-size:.65rem;" onclick="$(this).closest('div').remove()">
              <i class="fas fa-times"></i>
            </button>
          </div>`);
        $('#csGalleryContainer').append($thumb);
      };
      reader.readAsDataURL(file);
      $input.remove();
    });
  });

  /* ═══════════════════════════
     STAR RATING
  ═══════════════════════════ */
  $('#starRating .star-btn').on('click', function () {
    currentRating = parseInt($(this).data('val'));
    $('#testimonialRating').val(currentRating);
    $('#starRating .star-btn').each(function () {
      $(this).toggleClass('lit', parseInt($(this).data('val')) <= currentRating);
    });
  });

  /* ═══════════════════════════
     VISIBILITY / STATUS
  ═══════════════════════════ */
  $(document).on('click', '.vis-pill', function () {
    $('.vis-pill').removeClass('active');
    $(this).addClass('active');
    const val = $(this).find('input').val();
    $('#passwordField').toggle(val === 'password');
  });

  $('#pageStatus').on('change', function () {
    $('#scheduleField').toggle($(this).val() === 'scheduled');
  });

  /* ═══════════════════════════
     SCHEMA PILLS
  ═══════════════════════════ */
  $(document).on('click', '.schema-pill', function () {
    $('.schema-pill').removeClass('active');
    $(this).addClass('active');
  });

  /* ═══════════════════════════
     CATEGORY INLINE ADD
  ═══════════════════════════ */
  $('#btnAddCat').on('click', () => { $('#newCatField').slideToggle(200); $('#newCatInput').focus(); });
  $('#btnSaveCat').on('click', function () {
    const name     = $('#newCatInput').val().trim();
    const pageType = $('#pageTypeInput').val();
    if (!name) return;

    $.ajax({
      url:  "{{ route('pages.category.store') }}",
      type: 'POST',
      data: { _token: "{{ csrf_token() }}", name, page_type: pageType },
      success: (res) => {
        $('<option>').val(res.id).text(res.name).prop('selected', true).appendTo('#categorySelect');
        $('#newCatInput').val('');
        $('#newCatField').slideUp(200);
        toast(`Category "${res.name}" added`, 'var(--green)', 'fas fa-folder-plus');
      },
      error: (err) => {
        const msg = err.responseJSON?.message || 'Failed to save category';
        toast(msg, 'var(--red)', 'fas fa-exclamation-circle');
      },
    });
  });

  /* ═══════════════════════════
     ICON PICKER MODAL
  ═══════════════════════════ */
  function buildIconGrid(filter) {
    const $grid = $('#iconGrid').empty();
    ICONS.filter(ic => !filter || ic.includes(filter.toLowerCase())).forEach(ic => {
      $(`<button type="button" class="icon-opt" title="${ic}"><i class="fas ${ic}"></i></button>`)
        .on('click', () => selectIcon(ic))
        .appendTo($grid);
    });
  }

  function selectIcon(ic) {
    if (activeIconTarget) {
      const $wrap = activeIconTarget.closest('.icon-picker-wrap');
      $wrap.find('.icon-input').val('fas ' + ic);
      $wrap.find('.icon-preview i').attr('class', 'fas ' + ic);
    }
    $('#iconPickerModal').removeClass('show');
  }

  buildIconGrid('');

  $(document).on('click', '.pick-icon-btn', function () {
    activeIconTarget = $(this);
    $('#iconPickerModal').addClass('show');
    $('#iconSearch').val('').focus();
    buildIconGrid('');
  });

  $('#iconSearch').on('input', function () { buildIconGrid($(this).val()); });
  $('#closeIconModal').on('click', () => $('#iconPickerModal').removeClass('show'));
  $('#iconPickerModal').on('click', function (e) {
    if ($(e.target).is('#iconPickerModal')) $(this).removeClass('show');
  });

  $(document).on('input', '.icon-input', function () {
    const cls = $(this).val().trim() || 'fas fa-star';
    $(this).closest('.icon-preview-row').find('.icon-preview i').attr('class', cls);
  });

  /* ═══════════════════════════
     FORM SUBMIT
  ═══════════════════════════ */
  window.submitForm = function (status) {
      $('#pageStatus').val(status).trigger('change'); // single source of truth now

      Object.entries(QUILL_MAP).forEach(([, pairs]) => {
        pairs.forEach(({ editorId, hiddenId }) => {
          if (quillInstances[editorId]) {
            $(`#${hiddenId}`).val(quillInstances[editorId].root.innerHTML);
          }
        });
      });

      if (!$('#pbTitle').val().trim()) {
        toast('Please enter a title before saving', 'var(--red)', 'fas fa-exclamation-circle');
        return;
      }
      if (!$('#pbSlug').val().trim()) {
        $('#pbSlug').val(slugify($('#pbTitle').val()));
      }

      // Drafts skip any client-side "required field" checks — only title is enforced above.
      if (status === 'draft' && window.pageBuilderValidator?.disableFor) {
        window.pageBuilderValidator.disableFor(); // see note on validator.js below
      }

      document.getElementById('pbForm').submit();
  };

  $('#btnPbPublish, #sidebarPublish').on('click', () => window.submitForm('published'));
  $('#btnPbDraft,   #sidebarDraft').on('click',   () => window.submitForm('draft'));

  $('#btnPbPreview').on('click', () => {
    const slug = $('#pbSlug').val();
    if (slug) window.open(`/${slug}?preview=1`, '_blank');
    else toast('Set a slug before previewing', 'var(--yellow)', 'fas fa-exclamation-triangle');
  });

  /* ═══════════════════════════
     DRAG-TO-REORDER
  ═══════════════════════════ */
  [
    'featuresContainer','stepsContainer','kpiContainer','skillsContainer','faqContainer',
    'landingStatsContainer','challengesContainer','goalsContainer','modulesContainer',
    'techStackContainer','csStepsContainer','achievementsContainer','baContainer',
    'complianceContainer',
  ].forEach(id => {
    const el = document.getElementById(id);
    if (el && window.Sortable) {
      Sortable.create(el, {
        handle: '.drag-handle', animation: 150,
        onEnd: () => { $(`#${id} .repeat-num`).each((i, el) => $(el).text(`#${i+1}`)); }
      });
    }
  });

  /* ═══════════════════════════
     ESC KEY
  ═══════════════════════════ */
  $(document).on('keydown', e => {
    if (e.key === 'Escape') $('.pb-modal-overlay').removeClass('show');
  });

  /* ═══════════════════════════
     SESSION TOASTS (from controller)
  ═══════════════════════════ */
  @if (session('toast'))
    @php $t = session('toast'); @endphp
    (function () {
      const type = @json($t['type'] ?? 'info');
      const msg  = @json($t['message'] ?? '');
      const map  = {
        success : { color: 'var(--green)',   icon: 'fas fa-check-circle'        },
        error   : { color: 'var(--red)',     icon: 'fas fa-times-circle'         },
        warning : { color: 'var(--yellow)',  icon: 'fas fa-exclamation-triangle' },
        info    : { color: 'var(--primary)', icon: 'fas fa-info-circle'          },
      };
      const cfg = map[type] || map.info;
      setTimeout(() => window.pbToast(msg, cfg.color, cfg.icon), 400);
    })();
  @endif

  /* ═══════════════════════════
     LARAVEL VALIDATION ERRORS → Toast + field highlight
  ═══════════════════════════ */
  @if ($errors->any())
    (function () {
      const allErrors = @json($errors->all());
      window.pbToast(allErrors[0], 'var(--red)', 'fas fa-exclamation-circle');
      if (allErrors.length > 1) {
        setTimeout(() => window.pbToast(
          `${allErrors.length - 1} more error(s) — check highlighted fields.`,
          'var(--yellow)', 'fas fa-exclamation-triangle'
        ), 600);
      }

      @foreach ($errors->keys() as $field)
        (function () {
          const $f = $('[name="{{ $field }}"]');
          if ($f.length) {
            $f.addClass('pb-input-invalid');
            if (!$f.next('.pb-field-error').length) {
              $f.after('<div class="pb-field-error"><i class="fas fa-exclamation-circle"></i> {{ addslashes($errors->first($field)) }}</div>');
            }
            @if ($loop->first)
              $('html,body').animate({ scrollTop: $f.offset().top - 130 }, 300);
            @endif
          }
        })();
      @endforeach
    })();
  @endif

  // Trigger initial SEO score update
  updateSeoScore();

});
</script>
@endpush