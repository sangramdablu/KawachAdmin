@extends('layouts.master')
@section('title', 'Newsroom — KawachTech Software Solutions')
@section('content')

{{-- ================== STYLES ==================
     Reuses the exact same design tokens/classes as resources/views/blogs/index.blade.php
     (same #blogList → #newsList scoping trick) so the Newsroom listing matches
     the Blog listing's visual language rather than inventing a new design
     system. Grid view / bulk actions / charts / share+stats modals from the
     Blog listing are intentionally left out here to keep this module
     focused — see the delivery report for why. --}}
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

#newsList {
  font-family: 'Open Sans', sans-serif;
  color: var(--text);
  background: var(--bg);
  min-height: 100vh;
  transition: background .3s, color .3s;
}
#newsList .bl-wrap { max-width: 1440px; margin: 0 auto; padding: 28px 22px 70px; }
#newsList .bl-topbar { display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; margin-bottom: 24px; }
#newsList .bl-breadcrumb { font-size: .76rem; color: var(--muted); display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
#newsList .bl-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 600; }
#newsList .bl-breadcrumb a:hover { text-decoration: underline; }
#newsList .bl-title { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.55rem; color: var(--text); line-height: 1.2; }
#newsList .bl-topbar-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }

#newsList .btn-bl { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: 8px; font-size: .84rem; font-weight: 700; cursor: pointer; border: none; transition: all .2s; font-family: 'Open Sans', sans-serif; white-space: nowrap; text-decoration: none; }
#newsList .btn-bl:hover { transform: translateY(-1px); }
#newsList .btn-primary { background: var(--primary); color: #fff; }
#newsList .btn-primary:hover { background: var(--primary-dark); box-shadow: 0 4px 14px rgba(26,115,232,.35); color: #fff; }
#newsList .btn-outline { background: var(--card); color: var(--text); border: 1.5px solid var(--border); }
#newsList .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
#newsList .btn-danger { background: var(--danger); color: #fff; }
#newsList .btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: 1.5px solid var(--border); background: var(--card); color: var(--muted); cursor: pointer; font-size: .8rem; transition: all .18s; text-decoration: none; }
#newsList .btn-icon:hover { border-color: var(--primary); color: var(--primary); }
#newsList .btn-icon.danger:hover { border-color: var(--danger); color: var(--danger); }
#newsList .btn-icon.success-h:hover { border-color: var(--success); color: var(--success); }

#newsList .stat-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 22px; }
@media (max-width: 900px) { #newsList .stat-row { grid-template-columns: repeat(2, 1fr); } }
#newsList .stat-ov-card { background: var(--card); border-radius: var(--radius); padding: 16px 18px; border: 1px solid var(--border); box-shadow: var(--shadow); display: flex; align-items: center; gap: 14px; transition: transform .2s, box-shadow .2s, background .3s; }
#newsList .stat-ov-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
#newsList .stat-ov-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0; }
#newsList .stat-ov-val { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.45rem; color: var(--text); line-height: 1; margin-bottom: 2px; }
#newsList .stat-ov-lbl { font-size: .72rem; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }

#newsList .filter-bar { background: var(--card); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); padding: 14px 18px; margin-bottom: 18px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
#newsList .search-wrap { position: relative; flex: 1; min-width: 200px; max-width: 360px; }
#newsList .search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: .82rem; }
#newsList .search-wrap input { width: 100%; border: 1.5px solid var(--border); border-radius: 8px; padding: 8px 36px 8px 34px; font-size: .84rem; color: var(--text); background: var(--bg); outline: none; font-family: 'Open Sans', sans-serif; }
#newsList .search-wrap input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,115,232,.1); }

#newsList .status-tabs { display: flex; gap: 4px; background: var(--bg); border-radius: 8px; padding: 3px; border: 1px solid var(--border); }
#newsList .status-tab { padding: 6px 14px; border-radius: 6px; font-size: .78rem; font-weight: 700; cursor: pointer; color: var(--muted); border: none; background: transparent; transition: all .18s; white-space: nowrap; font-family: 'Open Sans', sans-serif; }
#newsList .status-tab.active { background: var(--card); color: var(--primary); box-shadow: 0 1px 6px rgba(26,115,232,.12); }
#newsList .status-tab .tab-count { display: inline-flex; align-items: center; justify-content: center; min-width: 18px; height: 18px; border-radius: 9px; font-size: .62rem; font-weight: 800; margin-left: 5px; padding: 0 5px; }

#newsList .bl-table-wrap { background: var(--card); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; }
#newsList .bl-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
#newsList .bl-table thead th { background: #fafbfe; padding: 12px 16px; font-family: 'Nunito', sans-serif; font-weight: 800; font-size: .75rem; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid var(--border); white-space: nowrap; }
html[data-theme="dark"] #newsList .bl-table thead th { background: #0f172a; }
#newsList .bl-table tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
#newsList .bl-table tbody tr:last-child { border-bottom: none; }
#newsList .bl-table tbody tr:hover { background: rgba(26,115,232,.03); }
html[data-theme="dark"] #newsList .bl-table tbody tr:hover { background: rgba(255,255,255,.03); }
#newsList .bl-table td { padding: 14px 16px; vertical-align: middle; color: var(--text); }

#newsList .post-thumb-cell { display: flex; align-items: center; gap: 12px; }
#newsList .post-thumb { width: 60px; height: 46px; border-radius: 8px; object-fit: cover; flex-shrink: 0; background: linear-gradient(135deg, #1a3a6e, #2196f3); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: rgba(255,255,255,.35); }
#newsList .post-thumb img { width:100%; height:100%; object-fit:cover; border-radius:8px; }
#newsList .post-title-link { font-family: 'Nunito', sans-serif; font-weight: 800; font-size: .9rem; color: var(--text); text-decoration: none; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.35; }
#newsList .post-title-link:hover { color: var(--primary); }
#newsList .post-cat-tag { display: inline-flex; align-items: center; gap: 4px; font-size: .68rem; font-weight: 700; color: var(--primary); background: #e8f1fd; padding: 2px 8px; border-radius: 10px; margin-top: 3px; }
html[data-theme="dark"] #newsList .post-cat-tag { background: rgba(26,115,232,.2); }
#newsList .source-tag { display: inline-flex; align-items: center; gap: 4px; font-size: .68rem; font-weight: 700; color: #9333ea; background: #f3e8ff; padding: 2px 8px; border-radius: 10px; margin-top: 3px; margin-left: 4px; }
html[data-theme="dark"] #newsList .source-tag { background: rgba(147,51,234,.2); }

#newsList .stat-cell { display: flex; align-items: center; gap: 5px; font-size: .8rem; font-weight: 600; color: var(--text); white-space: nowrap; }
#newsList .stat-cell i { font-size: .72rem; color: var(--muted); }

#newsList .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: .7rem; font-weight: 800; text-transform: uppercase; letter-spacing: .4px; }
#newsList .badge-published { background: #d4f5ec; color: #00a87c; }
#newsList .badge-draft      { background: #eef2f9; color: var(--muted); }
#newsList .badge-scheduled  { background: #e8f1fd; color: var(--primary); }
html[data-theme="dark"] #newsList .badge-published { background: rgba(0,200,150,.15); }
html[data-theme="dark"] #newsList .badge-draft      { background: rgba(100,116,139,.15); }
html[data-theme="dark"] #newsList .badge-scheduled  { background: rgba(26,115,232,.15); }

#newsList .action-cell { display: flex; gap: 5px; align-items: center; }

#newsList .empty-row { text-align: center; padding: 60px 20px; color: var(--muted); }
#newsList .empty-row i { font-size: 2.2rem; margin-bottom: 10px; display: block; color: var(--border); }

/* Delete modal */
#newsList .bl-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 9000; align-items: center; justify-content: center; backdrop-filter: blur(3px); }
#newsList .bl-modal-overlay.show { display: flex; }
#newsList .bl-modal { background: var(--card); border-radius: 16px; padding: 0; max-width: 420px; width: 95%; box-shadow: 0 24px 64px rgba(0,0,0,.25); }
#newsList .modal-header { padding: 20px 24px 16px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
#newsList .modal-header h3 { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.1rem; color: var(--text); margin: 0; display: flex; align-items: center; gap: 8px; }
#newsList .modal-close { width: 30px; height: 30px; border-radius: 50%; border: none; background: var(--bg); color: var(--muted); font-size: .8rem; cursor: pointer; display: flex; align-items: center; justify-content: center; }
#newsList .modal-body { padding: 22px 24px; }
#newsList .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; gap: 8px; justify-content: flex-end; }
#newsList .delete-warn-icon { width: 64px; height: 64px; border-radius: 50%; background: #ffe2e8; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: var(--danger); margin: 0 auto 16px; }

#newsList .bl-toast-stack { position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; pointer-events: none; }
#newsList .bl-toast { background: var(--card); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; display: flex; align-items: center; gap: 10px; box-shadow: 0 6px 24px rgba(0,0,0,.15); font-size: .82rem; color: var(--text); pointer-events: all; max-width: 300px; }
#newsList .bl-toast i { font-size: .95rem; flex-shrink: 0; }

@media (max-width: 900px) { #newsList .hide-mobile { display: none !important; } }
@media (max-width: 640px) { #newsList .bl-topbar { flex-direction: column; align-items: flex-start; } #newsList .filter-bar { gap: 8px; } }
</style>

{{-- ================= MARKUP ================== --}}
<div id="newsList">
<div class="bl-wrap">

  {{-- ── TOPBAR ── --}}
  <div class="bl-topbar">
    <div>
      <div class="bl-breadcrumb">
        <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
        <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
        <span>Newsroom</span>
      </div>
      <div class="bl-title">📰 Newsroom</div>
    </div>
    <div class="bl-topbar-actions">
      @can('news.create')
        <a href="{{ route('news.create') }}" class="btn-bl btn-primary">
          <i class="fas fa-plus"></i> New Article
        </a>
      @endcan
    </div>
  </div>

  {{-- ── OVERVIEW STAT CARDS ── --}}
  <div class="stat-row">
    <div class="stat-ov-card">
      <div class="stat-ov-icon" style="background:#e8f1fd;color:var(--primary);"><i class="fas fa-newspaper"></i></div>
      <div class="stat-ov-body">
        <div class="stat-ov-val">{{ $counts['all'] }}</div>
        <div class="stat-ov-lbl">Total Articles</div>
      </div>
    </div>
    <div class="stat-ov-card">
      <div class="stat-ov-icon" style="background:#d4f5ec;color:var(--success);"><i class="fas fa-check-circle"></i></div>
      <div class="stat-ov-body">
        <div class="stat-ov-val">{{ $counts['published'] }}</div>
        <div class="stat-ov-lbl">Published</div>
      </div>
    </div>
    <div class="stat-ov-card">
      <div class="stat-ov-icon" style="background:#eef2f9;color:var(--muted);"><i class="fas fa-file-alt"></i></div>
      <div class="stat-ov-body">
        <div class="stat-ov-val">{{ $counts['draft'] }}</div>
        <div class="stat-ov-lbl">Drafts</div>
      </div>
    </div>
    <div class="stat-ov-card">
      <div class="stat-ov-icon" style="background:#fff0f3;color:var(--danger);"><i class="fas fa-eye"></i></div>
      <div class="stat-ov-body">
        <div class="stat-ov-val">{{ number_format($counts['views']) }}</div>
        <div class="stat-ov-lbl">Total Views</div>
      </div>
    </div>
  </div>

  {{-- ── FILTER BAR ── --}}
  <div class="filter-bar">
    <div class="status-tabs">
      <button class="status-tab active" data-status="all">All <span class="tab-count" style="background:#e8f1fd;color:var(--primary);">{{ $counts['all'] }}</span></button>
      <button class="status-tab" data-status="published">Published <span class="tab-count" style="background:#d4f5ec;color:var(--success);">{{ $counts['published'] }}</span></button>
      <button class="status-tab" data-status="draft">Draft <span class="tab-count" style="background:#eef2f9;color:var(--muted);">{{ $counts['draft'] }}</span></button>
      <button class="status-tab" data-status="scheduled">Scheduled <span class="tab-count" style="background:#e8f1fd;color:var(--primary);">{{ $counts['scheduled'] }}</span></button>
    </div>
    <div class="search-wrap">
      <i class="fas fa-search"></i>
      <input type="text" id="newsSearch" placeholder="Search articles, sources…" autocomplete="off"/>
    </div>
  </div>

  {{-- ── TABLE ── --}}
  <div class="bl-table-wrap">
    <table class="bl-table">
      <thead>
        <tr>
          <th>Article</th>
          <th class="hide-mobile">Status</th>
          <th class="hide-mobile"><i class="fas fa-eye"></i> Views</th>
          <th class="hide-mobile">Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="newsTableBody">
        @forelse($posts as $post)
        <tr data-status="{{ $post->status }}" data-id="{{ $post->id }}" data-search="{{ strtolower($post->title . ' ' . $post->external_source_name) }}">
          <td>
            <div class="post-thumb-cell">
              <div class="post-thumb">
                @if(!empty($post->featured_image))
                  <img src="{{ asset($post->featured_image) }}" alt="{{ $post->image_alt ?? $post->title }}">
                @else
                  <i class="fas fa-newspaper" style="position:relative;z-index:1;"></i>
                @endif
              </div>
              <div>
                <a href="{{ route('news.edit', $post->id) }}" class="post-title-link">{{ $post->title }}</a>
                <div>
                  <span class="post-cat-tag"><i class="fas fa-folder"></i> {{ $post->category->name ?? 'Uncategorized' }}</span>
                  @if($post->external_source_name)
                    <span class="source-tag"><i class="fas fa-external-link-alt"></i> {{ $post->external_source_name }}</span>
                  @endif
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
            <div class="stat-cell"><i class="fas fa-eye"></i> {{ number_format($post->views ?? 0) }}</div>
          </td>
          <td class="hide-mobile" style="font-size:.78rem;color:var(--muted);white-space:nowrap;">
            {{ $post->created_at->format('M d, Y') }}
          </td>
          <td>
            <div class="action-cell">
              @if($post->status === 'published')
              <a href="{{ url('/newsroom/' . $post->slug) }}" target="_blank" class="btn-icon success-h" title="View live">
                <i class="fas fa-eye"></i>
              </a>
              @endif
              @can('news.edit')
                <a href="{{ route('news.edit', $post->id) }}" class="btn-icon" title="Edit article">
                    <i class="fas fa-pen"></i>
                </a>
              @endcan
              @can('news.delete')
                <button class="btn-icon danger" title="Delete article" onclick="openDeleteModal('{{ encrypt($post->id) }}', '{{ addslashes($post->title) }}')">
                  <i class="fas fa-trash"></i>
                </button>
              @endcan
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5">
            <div class="empty-row">
              <i class="fas fa-newspaper"></i>
              No newsroom articles yet. Click "New Article" to publish your first company announcement.
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>{{-- /bl-wrap --}}

{{-- ── DELETE CONFIRM MODAL ── --}}
<div class="bl-modal-overlay" id="deleteModal">
  <div class="bl-modal modal-sm">
    <div class="modal-header">
      <h3><i class="fas fa-trash-alt"></i> Delete Article</h3>
      <button class="modal-close" onclick="closeModal('deleteModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body" style="text-align:center;">
      <div class="delete-warn-icon"><i class="fas fa-exclamation-triangle"></i></div>
      <p style="color:var(--muted);font-size:.86rem;">You are about to permanently delete "<strong id="deletePostTitle"></strong>". This action cannot be undone.</p>
    </div>
    <div class="modal-footer">
      <button class="btn-bl btn-outline" onclick="closeModal('deleteModal')">Cancel</button>
      <button class="btn-bl btn-danger" id="confirmDeleteBtn"><i class="fas fa-trash"></i> Yes, Delete</button>
    </div>
  </div>
</div>

<div class="bl-toast-stack" id="newsToastStack"></div>

</div>{{-- /newsList --}}

@endsection

@push('scripts')
<script>
$(function () {

  /* ── Status tab filter ── */
  $('.status-tab').on('click', function () {
    $('.status-tab').removeClass('active');
    $(this).addClass('active');
    const status = $(this).data('status');
    $('#newsTableBody tr[data-id]').each(function () {
      $(this).toggle(status === 'all' || $(this).data('status') === status);
    });
  });

  /* ── Search ── */
  $('#newsSearch').on('input', function () {
    const term = $(this).val().toLowerCase();
    $('#newsTableBody tr[data-id]').each(function () {
      const hay = $(this).data('search') || '';
      $(this).toggle(hay.toString().includes(term));
    });
  });

  /* ── Toast ── */
  function toast(msg, color, icon) {
    const $t = $(`<div class="bl-toast"><i class="${icon}" style="color:${color};"></i><span>${msg}</span></div>`);
    $('#newsToastStack').append($t);
    setTimeout(() => $t.fadeOut(300, () => $t.remove()), 3500);
  }
  window.showToast = toast;

  /* ── Delete modal (same encrypted-id AJAX DELETE pattern as Blog) ── */
  var deletePostId = null;
  window.openDeleteModal = function (id, title) {
    deletePostId = id;
    $('#deletePostTitle').text(title);
    $('#deleteModal').addClass('show');
  };
  window.closeModal = function (id) { $('#' + id).removeClass('show'); };

  $('#confirmDeleteBtn').on('click', function () {
    if (!deletePostId) return;
    $.ajax({
      url: '/news/' + deletePostId,
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success: function (res) {
        if (res.success) {
          $('tr[data-id]').filter(function () {
            return $(this).find('.btn-icon.danger').attr('onclick') && $(this).find('.btn-icon.danger').attr('onclick').includes(deletePostId);
          }).fadeOut(300, function () { $(this).remove(); });
          toast(res.message, 'var(--danger)', 'fas fa-trash');
          setTimeout(() => location.reload(), 700);
        } else {
          toast(res.message, '#ff4d6d', 'fas fa-exclamation-circle');
        }
      },
      error: function (xhr) {
        const msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Delete failed';
        toast(msg, '#ff4d6d', 'fas fa-times-circle');
      }
    });
    closeModal('deleteModal');
    deletePostId = null;
  });

  $(document).on('keydown', function (e) {
    if (e.key === 'Escape') $('.bl-modal-overlay.show').removeClass('show');
  });

});
</script>

@if(session('success'))
<script>$(function(){ window.showToast && window.showToast(`{!! session('success') !!}`, '#00c896', 'fas fa-check-circle'); });</script>
@endif
@if(session('error'))
<script>$(function(){ window.showToast && window.showToast(`{!! session('error') !!}`, '#ff4d6d', 'fas fa-times-circle'); });</script>
@endif
@endpush
