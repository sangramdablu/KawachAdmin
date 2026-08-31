@extends('layouts.master')
@section('title', 'Blog Comments — KawachTech Software Solutions')
@section('content')

{{-- ================== STYLES ================== --}}
<style>
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

#commentMod {
  font-family: 'Open Sans', sans-serif;
  color: var(--text);
  background: var(--bg);
  min-height: 100vh;
}
#commentMod .cm-wrap { max-width: 1280px; margin: 0 auto; padding: 28px 22px 70px; }

#commentMod .cm-breadcrumb {
  font-size: .76rem; color: var(--muted);
  display: flex; align-items: center; gap: 6px; margin-bottom: 4px;
}
#commentMod .cm-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 600; }
#commentMod .cm-breadcrumb a:hover { text-decoration: underline; }
#commentMod .cm-title {
  font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.55rem;
  color: var(--text); line-height: 1.2; margin-bottom: 20px;
}

/* Status tabs */
#commentMod .status-tabs {
  display: flex; gap: 4px; background: var(--card); border-radius: 10px;
  padding: 4px; border: 1px solid var(--border); margin-bottom: 18px;
  width: fit-content; box-shadow: var(--shadow);
}
#commentMod .status-tab {
  padding: 8px 16px; border-radius: 7px; font-size: .82rem; font-weight: 700;
  color: var(--muted); text-decoration: none; transition: all .15s; white-space: nowrap;
}
#commentMod .status-tab.active { background: var(--primary); color: #fff; }
#commentMod .status-tab:not(.active):hover { background: var(--bg); color: var(--text); }
#commentMod .status-tab .tab-count {
  display: inline-flex; align-items: center; justify-content: center;
  min-width: 18px; height: 18px; border-radius: 9px; font-size: .65rem;
  font-weight: 800; margin-left: 6px; padding: 0 5px; background: rgba(255,255,255,.25);
}
#commentMod .status-tab:not(.active) .tab-count { background: var(--bg); }

/* Table */
#commentMod .cm-table-wrap {
  background: var(--card); border-radius: var(--radius); border: 1px solid var(--border);
  box-shadow: var(--shadow); overflow: hidden;
}
#commentMod .cm-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
#commentMod .cm-table thead th {
  background: #fafbfe; padding: 12px 16px; font-family: 'Nunito', sans-serif;
  font-weight: 800; font-size: .74rem; color: var(--muted); text-transform: uppercase;
  letter-spacing: .5px; border-bottom: 1px solid var(--border); white-space: nowrap;
}
html[data-theme="dark"] #commentMod .cm-table thead th { background: #0f172a; }
#commentMod .cm-table tbody tr { border-bottom: 1px solid var(--border); }
#commentMod .cm-table tbody tr:last-child { border-bottom: none; }
#commentMod .cm-table tbody tr:hover { background: rgba(26,115,232,.03); }
#commentMod .cm-table td { padding: 14px 16px; vertical-align: top; color: var(--text); }

#commentMod .commenter-name { font-family: 'Nunito', sans-serif; font-weight: 800; font-size: .86rem; }
#commentMod .commenter-email { font-size: .74rem; color: var(--muted); }
#commentMod .comment-text-cell { max-width: 340px; font-size: .82rem; line-height: 1.5; white-space: pre-wrap; word-break: break-word; }
#commentMod .post-link { color: var(--primary); text-decoration: none; font-weight: 700; font-size: .82rem; }
#commentMod .post-link:hover { text-decoration: underline; }
#commentMod .submitted-cell { font-size: .76rem; color: var(--muted); white-space: nowrap; }

#commentMod .status-badge {
  display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px;
  font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .4px;
}
#commentMod .badge-approved { background: #d4f5ec; color: #00a87c; }
#commentMod .badge-pending  { background: #fff4d6; color: #b8860b; }
#commentMod .badge-rejected { background: #ffe2e8; color: var(--danger); }
html[data-theme="dark"] #commentMod .badge-approved { background: rgba(0,200,150,.15); }
html[data-theme="dark"] #commentMod .badge-pending  { background: rgba(255,184,48,.15); }
html[data-theme="dark"] #commentMod .badge-rejected { background: rgba(255,77,109,.15); }

#commentMod .action-cell { display: flex; gap: 6px; flex-wrap: wrap; }
#commentMod .btn-cm {
  display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 7px;
  font-size: .74rem; font-weight: 700; cursor: pointer; border: 1.5px solid var(--border);
  background: var(--card); color: var(--text); transition: all .15s; white-space: nowrap;
}
#commentMod .btn-cm:hover { transform: translateY(-1px); }
#commentMod .btn-cm.approve:hover { border-color: var(--success); color: var(--success); background: #d4f5ec; }
#commentMod .btn-cm.reject:hover  { border-color: var(--warning); color: var(--warning); background: #fff4d6; }
#commentMod .btn-cm.delete:hover  { border-color: var(--danger); color: var(--danger); background: #ffe2e8; }

#commentMod .cm-empty { padding: 60px 20px; text-align: center; color: var(--muted); }
#commentMod .cm-empty i { font-size: 2.2rem; margin-bottom: 12px; display: block; color: var(--border); }

#commentMod .cm-pagination { margin-top: 18px; }
</style>

{{-- ================= MARKUP ================== --}}
<div id="commentMod">
<div class="cm-wrap">

  <div class="cm-breadcrumb">
    <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
    <a href="{{ route('blogs.index') }}">Blog Posts</a>
    <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
    <span>Comments</span>
  </div>
  <div class="cm-title">💬 Comment Moderation</div>

  {{-- Status tabs (query-string filters) --}}
  <div class="status-tabs">
    <a href="{{ route('blog-comments.index', ['status' => 'all']) }}" class="status-tab {{ $status === 'all' ? 'active' : '' }}">
      All <span class="tab-count">{{ $counts['all'] }}</span>
    </a>
    <a href="{{ route('blog-comments.index', ['status' => 'pending']) }}" class="status-tab {{ $status === 'pending' ? 'active' : '' }}">
      Pending <span class="tab-count">{{ $counts['pending'] }}</span>
    </a>
    <a href="{{ route('blog-comments.index', ['status' => 'approved']) }}" class="status-tab {{ $status === 'approved' ? 'active' : '' }}">
      Approved <span class="tab-count">{{ $counts['approved'] }}</span>
    </a>
    <a href="{{ route('blog-comments.index', ['status' => 'rejected']) }}" class="status-tab {{ $status === 'rejected' ? 'active' : '' }}">
      Rejected <span class="tab-count">{{ $counts['rejected'] }}</span>
    </a>
  </div>

  <div class="cm-table-wrap">
    @if($comments->isEmpty())
      <div class="cm-empty">
        <i class="fas fa-comment-slash"></i>
        No comments {{ $status !== 'all' ? "with status \"$status\"" : '' }} yet.
      </div>
    @else
      <table class="cm-table">
        <thead>
          <tr>
            <th>Commenter</th>
            <th>Comment</th>
            <th>Post</th>
            <th>Submitted</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="commentsTableBody">
          @foreach($comments as $comment)
          <tr data-id="{{ $comment->id }}">
            <td>
              <div class="commenter-name">{{ $comment->name }}</div>
              <div class="commenter-email">{{ $comment->email }}</div>
            </td>
            <td class="comment-text-cell">{{ $comment->comment }}</td>
            <td>
              @if($comment->blog)
                <a href="{{ route('blogs.edit', $comment->blog->id) }}" class="post-link">{{ \Illuminate\Support\Str::limit($comment->blog->title, 40) }}</a>
              @else
                <span class="text-muted">Deleted post</span>
              @endif
            </td>
            <td class="submitted-cell">{{ $comment->created_at->format('M d, Y H:i') }}</td>
            <td>
              @php
                $badgeClass = match($comment->status) {
                    'approved' => 'badge-approved',
                    'rejected' => 'badge-rejected',
                    default    => 'badge-pending',
                };
              @endphp
              <span class="status-badge {{ $badgeClass }}">{{ ucfirst($comment->status) }}</span>
            </td>
            <td>
              <div class="action-cell">
                @if($comment->status !== 'approved')
                <form method="POST" action="{{ route('blog-comments.approve', $comment->id) }}" class="d-inline">
                  @csrf
                  <button type="submit" class="btn-cm approve"><i class="fas fa-check"></i> Approve</button>
                </form>
                @endif
                @if($comment->status !== 'rejected')
                <form method="POST" action="{{ route('blog-comments.reject', $comment->id) }}" class="d-inline">
                  @csrf
                  <button type="submit" class="btn-cm reject"><i class="fas fa-times"></i> Reject</button>
                </form>
                @endif
                <button type="button" class="btn-cm delete" onclick="deleteComment({{ $comment->id }})"><i class="fas fa-trash"></i> Delete</button>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

  <div class="cm-pagination">
    {{ $comments->links() }}
  </div>

</div>{{-- /cm-wrap --}}
</div>{{-- /commentMod --}}

@push('scripts')
<script>
$(function () {
  @if(session('success'))
    window.showToast('{{ session('success') }}', 'var(--success)', 'fas fa-check-circle');
  @endif
  @if(session('error'))
    window.showToast('{{ session('error') }}', 'var(--danger)', 'fas fa-exclamation-circle');
  @endif

  window.deleteComment = function (id) {
    if (!confirm('Delete this comment permanently? This cannot be undone.')) return;
    $.ajax({
      url: '/blog-comments/' + id,
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success: function (res) {
        if (res.success) {
          $('tr[data-id="' + id + '"]').fadeOut(250, function () { $(this).remove(); });
          window.showToast(res.message, 'var(--danger)', 'fas fa-trash');
        } else {
          window.showToast(res.message, 'var(--danger)', 'fas fa-exclamation-circle');
        }
      },
      error: function (xhr) {
        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Delete failed';
        window.showToast(msg, 'var(--danger)', 'fas fa-times-circle');
      }
    });
  };
});
</script>
@endpush
@endsection
