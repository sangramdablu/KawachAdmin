@extends('layouts.master')
@section('title', $design->title . ' — KawachTech Client Portal')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap');

    :root {
        --primary: #1a73e8; --primary-dark: #1558b0; --success: #00c896; --warning: #ffb830; --danger: #ff4d6d;
        --bg: #f0f4fb; --card: #ffffff; --border: #e2e8f0; --text: #1a1a2e; --muted: #6b7a99;
        --radius: 12px; --shadow: 0 2px 20px rgba(26,115,232,.08); --shadow-md: 0 6px 32px rgba(26,115,232,.13);
    }
    html[data-theme="dark"] {
        --bg: #0f172a; --card: #1e293b; --text: #e2e8f0; --border: #334155; --muted: #94a3b8;
        --shadow: 0 2px 20px rgba(0,0,0,.3); --shadow-md: 0 6px 32px rgba(0,0,0,.4);
    }

    #clientDesignShow { font-family: 'Open Sans', sans-serif; color: var(--text); background: var(--bg); min-height: 100vh; }
    #clientDesignShow .cp-wrap { max-width: 1300px; margin: 0 auto; padding: 28px 22px 70px; }
    #clientDesignShow .cp-breadcrumb { font-size: .76rem; color: var(--muted); display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
    #clientDesignShow .cp-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 600; }
    #clientDesignShow .cp-title { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 1.4rem; margin-bottom: 4px; }
    #clientDesignShow .cp-sub { font-size: .8rem; color: var(--muted); margin-bottom: 18px; }

    #clientDesignShow .btn-cp { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: 8px; font-size: .84rem; font-weight: 700; cursor: pointer; border: none; transition: all .2s; text-decoration: none; }
    #clientDesignShow .btn-primary { background: var(--primary); color: #fff; }
    #clientDesignShow .btn-outline { background: var(--card); color: var(--text); border: 1.5px solid var(--border); }
    #clientDesignShow .btn-success { background: var(--success); color: #fff; }
    #clientDesignShow .btn-sm { padding: 6px 14px; font-size: .78rem; }

    #clientDesignShow .main-grid { display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start; }
    @media (max-width: 1000px) { #clientDesignShow .main-grid { grid-template-columns: 1fr; } }

    #clientDesignShow .cp-card { background: var(--card); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; }
    #clientDesignShow .image-wrap { position: relative; display: inline-block; width: 100%; cursor: crosshair; line-height: 0; }
    #clientDesignShow .image-wrap img { width: 100%; display: block; }
    #clientDesignShow .pin { position: absolute; width: 24px; height: 24px; border-radius: 50% 50% 50% 0; background: var(--primary); color: #fff; font-size: .68rem; font-weight: 800; display: flex; align-items: center; justify-content: center; transform: translate(-50%, -100%) rotate(45deg); box-shadow: 0 2px 8px rgba(0,0,0,.3); border: 2px solid #fff; }
    #clientDesignShow .pin span { transform: rotate(-45deg); }
    #clientDesignShow .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .4px; }
    #clientDesignShow .badge-review { background: #fff4d6; color: #b8860b; }
    #clientDesignShow .badge-active { background: #d4f5ec; color: #00a87c; }
    #clientDesignShow .badge-onhold { background: #ffe2e8; color: var(--danger); }

    #clientDesignShow .panel-hdr { padding: 14px 18px; border-bottom: 1px solid var(--border); font-weight: 800; font-size: .88rem; display: flex; align-items: center; gap: 8px; }
    #clientDesignShow .panel-hdr i { color: var(--primary); }
    #clientDesignShow .version-list { padding: 10px; display: flex; flex-direction: column; gap: 6px; }
    #clientDesignShow .version-item { display: flex; align-items: center; justify-content: space-between; padding: 8px 10px; border-radius: 8px; font-size: .78rem; text-decoration: none; color: var(--text); }
    #clientDesignShow .version-item.current { background: rgba(26,115,232,.08); font-weight: 700; }
    #clientDesignShow .version-item:hover { background: var(--bg); }

    #clientDesignShow .comment-list { padding: 10px 16px; display: flex; flex-direction: column; gap: 12px; max-height: 360px; overflow-y: auto; }
    #clientDesignShow .comment-item { display: flex; gap: 10px; }
    #clientDesignShow .comment-avatar { width: 28px; height: 28px; border-radius: 50%; background: var(--primary); color: #fff; display: flex; align-items: center; justify-content: center; font-size: .68rem; font-weight: 800; flex-shrink: 0; }
    #clientDesignShow .comment-body { flex: 1; min-width: 0; }
    #clientDesignShow .comment-name { font-size: .78rem; font-weight: 700; }
    #clientDesignShow .comment-num { color: var(--primary); font-weight: 800; margin-right: 4px; }
    #clientDesignShow .comment-text { font-size: .8rem; color: var(--text); margin-top: 2px; }
    #clientDesignShow .comment-time { font-size: .66rem; color: var(--muted); margin-top: 2px; }
    #clientDesignShow .comment-form { padding: 14px 16px; border-top: 1px solid var(--border); }
    #clientDesignShow .comment-form textarea { width: 100%; min-height: 70px; border: 1.5px solid var(--border); border-radius: 8px; padding: 8px 10px; font-size: .82rem; font-family: 'Open Sans', sans-serif; color: var(--text); background: var(--bg); resize: vertical; box-sizing: border-box; }
    #clientDesignShow .comment-hint { font-size: .7rem; color: var(--muted); margin-bottom: 8px; }
    #clientDesignShow .action-row { display: flex; gap: 8px; padding: 14px 18px; border-top: 1px solid var(--border); flex-wrap: wrap; }
    #clientDesignShow .notice-bar { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 10px; font-size: .82rem; margin-bottom: 16px; }
    #clientDesignShow .notice-success { background: rgba(0,200,150,.1); color: #00a87c; border: 1.5px solid rgba(0,200,150,.3); }
    #clientDesignShow .notice-error { background: rgba(255,77,109,.1); color: var(--danger); border: 1.5px solid rgba(255,77,109,.3); }
</style>

<div id="clientDesignShow">
<div class="cp-wrap">

  <div class="cp-breadcrumb">
    <a href="{{ route('client.portal') }}"><i class="fas fa-home"></i> Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
    <a href="{{ route('client.designs.index') }}">Designs</a>
    <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
    <span>{{ $design->title }}</span>
  </div>
  <div class="cp-title">{{ $design->title }} <span style="color:var(--muted);font-weight:600;font-size:1rem;">({{ $design->version }})</span></div>
  <div class="cp-sub">{{ $design->project->project_name ?? '—' }}</div>

  @if(session('success'))
    <div class="notice-bar notice-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="notice-bar notice-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
  @endif

  <div class="main-grid">
    <div>
      <div class="cp-card" style="margin-bottom:16px;">
        <div class="panel-hdr">
          <span><i class="fas fa-image"></i> Design Preview</span>
          <span class="status-badge {{ $design->badge_class }}" style="margin-left:auto;">{{ $design->status_label }}</span>
        </div>
        <div class="image-wrap" id="designImageWrap">
          <img id="designImage" src="{{ asset($design->image_path) }}" alt="{{ $design->title }}">
          @foreach($design->comments as $i => $comment)
            @if(!is_null($comment->x_position) && !is_null($comment->y_position))
              <div class="pin" style="left:{{ $comment->x_position }}%; top:{{ $comment->y_position }}%;" title="{{ $comment->user->name ?? 'Comment' }}: {{ $comment->body }}">
                <span>{{ $i + 1 }}</span>
              </div>
            @endif
          @endforeach
        </div>
        <div class="comment-hint" style="padding:10px 16px 0;">
          <i class="fas fa-hand-pointer"></i> Click anywhere on the image to pin a comment to that exact spot.
        </div>

        {{-- Pin comment popup form (hidden until a click) --}}
        <form id="pinCommentForm" action="{{ route('client.designs.comments.store', $design->id) }}" method="POST" style="padding:10px 16px 16px; display:none;">
          @csrf
          <input type="hidden" name="x_position" id="pinX">
          <input type="hidden" name="y_position" id="pinY">
          <textarea name="body" class="cp-textarea" placeholder="Leave a comment about this spot…" required
                    style="width:100%;min-height:60px;border:1.5px solid var(--border);border-radius:8px;padding:8px 10px;font-size:.82rem;font-family:'Open Sans',sans-serif;color:var(--text);background:var(--bg);resize:vertical;box-sizing:border-box;"></textarea>
          <div style="margin-top:8px;display:flex;gap:8px;">
            <button type="submit" class="btn-cp btn-primary btn-sm"><i class="fas fa-map-pin"></i> Pin Comment</button>
            <button type="button" id="cancelPin" class="btn-cp btn-outline btn-sm">Cancel</button>
          </div>
        </form>

        @if($design->status === 'pending')
        <div class="action-row">
          <form action="{{ route('client.designs.approve', $design->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-cp btn-success"><i class="fas fa-check"></i> Approve Design</button>
          </form>
          <details style="align-self:center;">
            <summary class="btn-cp btn-outline" style="display:inline-flex;"><i class="fas fa-comment-dots"></i> Request Changes</summary>
            <form action="{{ route('client.designs.request-changes', $design->id) }}" method="POST" style="margin-top:8px;display:flex;gap:8px;">
              @csrf
              <textarea name="body" required placeholder="What needs to change?"
                        style="min-width:240px;min-height:50px;border:1.5px solid var(--border);border-radius:8px;padding:8px 10px;font-size:.8rem;background:var(--bg);color:var(--text);"></textarea>
              <button type="submit" class="btn-cp btn-outline btn-sm">Submit</button>
            </form>
          </details>
        </div>
        @endif
      </div>
    </div>

    <div>
      @if($versions->count() > 1)
      <div class="cp-card" style="margin-bottom:16px;">
        <div class="panel-hdr"><i class="fas fa-history"></i> Version History</div>
        <div class="version-list">
          @foreach($versions as $v)
            <a href="{{ route('client.designs.show', $v->id) }}" class="version-item {{ $v->id === $design->id ? 'current' : '' }}">
              <span>{{ $v->version }} &middot; {{ $v->title }}</span>
              <span style="color:var(--muted);">{{ $v->created_at->format('M d') }}</span>
            </a>
          @endforeach
        </div>
      </div>
      @endif

      <div class="cp-card">
        <div class="panel-hdr"><i class="fas fa-comments"></i> Comments ({{ $design->comments->count() }})</div>
        <div class="comment-list">
          @forelse($design->comments as $i => $comment)
            <div class="comment-item">
              <div class="comment-avatar">{{ strtoupper(substr($comment->user->name ?? '?', 0, 1)) }}</div>
              <div class="comment-body">
                <div class="comment-name">
                  @if(!is_null($comment->x_position))<span class="comment-num">#{{ $i + 1 }}</span>@endif
                  {{ $comment->user->name ?? 'Unknown' }}
                </div>
                <div class="comment-text">{{ $comment->body }}</div>
                <div class="comment-time">{{ $comment->created_at->diffForHumans() }}</div>
              </div>
            </div>
          @empty
            <div style="text-align:center;color:var(--muted);font-size:.82rem;padding:20px 0;">No comments yet.</div>
          @endforelse
        </div>
        <div class="comment-form">
          <form action="{{ route('client.designs.comments.store', $design->id) }}" method="POST">
            @csrf
            <textarea name="body" placeholder="Add a general comment…" required></textarea>
            <div style="margin-top:8px;">
              <button type="submit" class="btn-cp btn-primary btn-sm"><i class="fas fa-paper-plane"></i> Post</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>
</div>

<script>
(function () {
    var wrap = document.getElementById('designImageWrap');
    var img = document.getElementById('designImage');
    var form = document.getElementById('pinCommentForm');
    var xInput = document.getElementById('pinX');
    var yInput = document.getElementById('pinY');
    var cancelBtn = document.getElementById('cancelPin');

    if (!wrap || !img) return;

    img.addEventListener('click', function (e) {
        var rect = img.getBoundingClientRect();
        var offsetX = e.clientX - rect.left;
        var offsetY = e.clientY - rect.top;
        var xPct = (offsetX / rect.width) * 100;
        var yPct = (offsetY / rect.height) * 100;

        xInput.value = xPct.toFixed(2);
        yInput.value = yPct.toFixed(2);
        form.style.display = 'block';
        form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });

    if (cancelBtn) {
        cancelBtn.addEventListener('click', function () {
            form.style.display = 'none';
        });
    }
})();
</script>
@endsection
