@if ($paginator->hasPages())
<style>
  .admin-pagination { display:flex; flex-wrap:wrap; align-items:center; gap:5px; list-style:none; margin:0; padding:0; font-family:'Nunito',sans-serif; }
  .admin-pagination li a,
  .admin-pagination li span {
    display:flex; align-items:center; justify-content:center;
    min-width:34px; height:34px; padding:0 10px;
    border:1px solid var(--border); border-radius:8px;
    font-size:.82rem; font-weight:700; text-decoration:none;
    color:var(--text-dark); background:var(--card-bg);
    transition:all .15s;
  }
  .admin-pagination li a:hover { border-color:var(--primary); color:var(--primary); }
  .admin-pagination li.active span { background:var(--primary); border-color:var(--primary); color:#fff; }
  .admin-pagination li.disabled span { opacity:.4; cursor:not-allowed; }
  .admin-pagination li.gap span { border:none; background:transparent; min-width:20px; padding:0; }
  .admin-pagination-info { font-size:.76rem; color:var(--text-muted); margin-right:auto; }
</style>

<nav class="d-flex" style="display:flex;align-items:center;flex-wrap:wrap;gap:14px;">
  <span class="admin-pagination-info">
    Showing {{ $paginator->firstItem() ?? 0 }}&ndash;{{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }}
  </span>

  <ul class="admin-pagination">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
      <li class="disabled"><span aria-hidden="true"><i class="fas fa-chevron-left"></i></span></li>
    @else
      <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous"><i class="fas fa-chevron-left"></i></a></li>
    @endif

    {{-- Page numbers --}}
    @foreach ($elements as $element)
      @if (is_string($element))
        <li class="gap"><span>&hellip;</span></li>
      @endif
      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <li class="active"><span>{{ $page }}</span></li>
          @else
            <li><a href="{{ $url }}">{{ $page }}</a></li>
          @endif
        @endforeach
      @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
      <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next"><i class="fas fa-chevron-right"></i></a></li>
    @else
      <li class="disabled"><span aria-hidden="true"><i class="fas fa-chevron-right"></i></span></li>
    @endif
  </ul>
</nav>
@endif
