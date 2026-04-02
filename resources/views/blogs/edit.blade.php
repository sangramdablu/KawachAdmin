@extends('layouts.master')
@section('content')
<style>
/* ═══════════════════════════════════════════════════
   BLOG EDITOR — Edit Page Additional Styles
   (Inherits all base styles from blog-create.blade.php)
═══════════════════════════════════════════════════ */

/* Last saved bar */
#blogEditor .last-saved-bar {
  display: flex;
  align-items: center;
  gap: 8px;
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 10px 18px;
  font-size: .8rem;
  color: var(--muted);
  margin-bottom: 20px;
  flex-wrap: wrap;
}

/* Status chips in last-saved bar */
#blogEditor .status-chip {
  display: inline-flex;
  align-items: center;
  padding: 2px 10px;
  border-radius: 20px;
  font-size: .72rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .04em;
}
#blogEditor .status-published { background: #d4f5ec; color: #00a87c; }
#blogEditor .status-draft     { background: #e8f1fd; color: var(--primary); }
#blogEditor .status-pending   { background: #fff4d6; color: #b8860b; }
#blogEditor .status-scheduled { background: #f3e8ff; color: #7c3aed; }

/* Current image notice */
#blogEditor .current-img-notice {
  display: flex;
  align-items: center;
  gap: 8px;
  background: #e8f1fd;
  border: 1px solid #bee0fd;
  border-radius: 8px;
  padding: 9px 14px;
  font-size: .8rem;
  color: var(--primary);
  margin-bottom: 14px;
  font-weight: 500;
}
#blogEditor .current-img-notice i { font-size: .9rem; }

/* Slug warning (colour override) */
#blogEditor .slug-warning { color: var(--warning); font-weight: 600; }

/* Post meta list (sidebar info card) */
#blogEditor .post-meta-list { display: flex; flex-direction: column; gap: 8px; }
#blogEditor .pml-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: .8rem;
  padding: 6px 0;
  border-bottom: 1px solid var(--border);
}
#blogEditor .pml-row:last-child { border-bottom: none; }
#blogEditor .pml-label { color: var(--muted); display: flex; align-items: center; gap: 6px; }
#blogEditor .pml-label i { font-size: .72rem; }
#blogEditor .pml-value { color: var(--text); font-weight: 600; }

/* Danger zone */
#blogEditor .danger-zone {
  margin-top: 18px;
  padding: 14px;
  border: 1.5px dashed #ffd0d8;
  border-radius: 10px;
  background: #fff8f9;
}
html[data-theme="dark"] #blogEditor .danger-zone {
  background: rgba(255,77,109,.07);
  border-color: rgba(255,77,109,.3);
}
#blogEditor .danger-zone-title {
  font-size: .75rem;
  font-weight: 700;
  color: var(--danger);
  text-transform: uppercase;
  letter-spacing: .06em;
  margin-bottom: 10px;
}
#blogEditor .btn-danger-outline {
  border-color: var(--danger) !important;
  color: var(--danger) !important;
}
#blogEditor .btn-danger-outline:hover {
  background: var(--danger) !important;
  color: #fff !important;
}

/* Delete modal */
#blogEditor ~ .modal-backdrop,
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.5);
  backdrop-filter: blur(4px);
  z-index: 9999;
  display: flex !important;
  align-items: center;
  justify-content: center;
}
.modal-box {
  background: var(--card);
  border-radius: 16px;
  padding: 32px 28px;
  max-width: 420px;
  width: 90%;
  text-align: center;
  box-shadow: 0 20px 60px rgba(0,0,0,.2);
  animation: modalIn .2s ease;
}
.modal-icon {
  font-size: 2.5rem;
  margin-bottom: 14px;
}
@keyframes modalIn {
  from { transform: scale(.88); opacity: 0; }
  to   { transform: scale(1);   opacity: 1; }
}

/* Fullscreen editor */
#blogEditor .ql-wrapper.be-fullscreen {
  position: fixed; inset: 0; z-index: 9999;
  border-radius: 0; border: none;
}
#blogEditor .ql-wrapper.be-fullscreen .ql-editor { min-height: calc(100vh - 60px); }
</style>
{{-- ================== MARKUP ================= --}}
<div id="blogEditor">
<div class="be-wrap">

  {{-- ── TOP BAR ── --}}
  <div class="be-topbar">
    <div class="be-topbar-left">
      <div>
        <div class="be-breadcrumb">
          <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
          <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
          <a href="{{ route('blogs.index') }}">Blogs</a>
          <i class="fas fa-chevron-right" style="font-size:.55rem;"></i>
          <span>Edit Post</span>
        </div>
        <div class="be-title-h1">✏️ Edit Blog Post</div>
      </div>
    </div>
    <div class="be-topbar-actions">
      <button class="btn-be btn-outline" id="btnPreview" type="button">
        <i class="fas fa-eye"></i> Preview
      </button>
      <button class="btn-be btn-outline" id="btnSaveDraft" type="button">
        <i class="fas fa-save"></i> Save Draft
      </button>
      <button class="btn-be btn-success" id="btnPublish" type="button">
        <i class="fas fa-rocket"></i> Update Post
      </button>
    </div>
  </div>

  {{-- ── CONTENT STATS STRIP ── --}}
  <div class="stats-strip">
    <div class="stat-item"><i class="fas fa-align-left"></i> Words: <strong id="wordCount">0</strong></div>
    <div class="stat-item"><i class="fas fa-clock"></i> Read time: <strong id="readTime">0 min</strong></div>
    <div class="stat-item"><i class="fas fa-heading"></i> Headings: <strong id="headingCount">0</strong></div>
    <div class="stat-item"><i class="fas fa-link"></i> Links: <strong id="linkCount">0</strong></div>
    <div class="stat-item"><i class="fas fa-image"></i> Images: <strong id="imgCount">0</strong></div>
    <div class="stat-item"><i class="fas fa-paragraph"></i> Paragraphs: <strong id="paraCount">0</strong></div>
    <div class="stat-item" style="margin-left:auto;">
      <i class="fas fa-eye" style="color:var(--primary);"></i> Views: <strong>{{ number_format($blog->views ?? 0) }}</strong>
    </div>
    <div class="stat-item">
      <i class="fas fa-calendar-alt" style="color:var(--muted);"></i> Created: <strong>{{ $blog->created_at->format('M d, Y') }}</strong>
    </div>
  </div>

  {{-- ── LAST SAVED NOTICE ── --}}
  <div class="last-saved-bar">
    <i class="fas fa-history"></i>
    Last updated: <strong>{{ $blog->updated_at->diffForHumans() }}</strong>
    &nbsp;·&nbsp;
    <span class="status-chip status-{{ $blog->status }}">{{ ucfirst($blog->status) }}</span>
    @if($blog->status === 'published' && $blog->published_at)
      &nbsp;·&nbsp; Published: <strong>{{ $blog->published_at->format('M d, Y H:i') }}</strong>
    @endif
  </div>

  <form id="blogForm" method="POST" action="{{ route('blogs.update', $blog->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="be-grid">

      {{-- ════════════════ LEFT COLUMN — Main Content ════════════════ --}}
      <div class="be-left">

        {{-- ── TITLE ── --}}
        <div class="be-card">
          <div class="be-card-header">
            <h2><i class="fas fa-heading"></i> Post Title</h2>
          </div>
          <div class="be-card-body">
            <div class="be-form-group">
              <div class="input-with-counter">
                <input type="text" name="title" id="blogTitle" class="be-input is-title"
                  placeholder="Enter your compelling blog title…"
                  maxlength="70" autocomplete="off" required
                  value="{{ old('title', $blog->title) }}" />
                <span class="char-counter" id="titleCounter">0/70</span>
              </div>
              <div class="be-input-hint">
                <i class="fas fa-info-circle"></i>
                Ideal length: <strong>50–60 characters</strong> for best SEO display.
              </div>
            </div>

            {{-- URL Slug --}}
            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-link" style="color:var(--primary);font-size:.8rem;"></i> URL Slug
                <span class="lbl-badge lbl-required">Required</span>
              </label>
              <div class="slug-row">
                <span class="slug-prefix" id="slugPrefix">{{ config('app.url') }}/blog/</span>
                <input type="text" name="slug" id="blogSlug" class="slug-input"
                  placeholder="your-post-slug" autocomplete="off"
                  value="{{ old('slug', $blog->slug) }}" />
                <button type="button" class="btn-icon" id="btnRegenerateSlug" title="Re-generate slug">
                  <i class="fas fa-sync-alt"></i>
                </button>
                <button type="button" class="btn-icon" id="btnCopySlug" title="Copy slug">
                  <i class="fas fa-copy"></i>
                </button>
              </div>
              <div class="be-input-hint">
                <i class="fas fa-info-circle"></i>
                <span style="color:var(--warning);"><i class="fas fa-exclamation-triangle"></i> Changing the slug will break existing links and affect SEO.</span>
              </div>
            </div>
          </div>
        </div>

        {{-- ── RICH TEXT CONTENT ── --}}
        <div class="be-card" style="margin-top:18px;">
          <div class="be-card-header">
            <h2><i class="fas fa-pen-nib"></i> Content</h2>
          </div>
          <div class="be-card-body" style="padding:0;">
            <div class="ql-wrapper">
              <div id="quillEditor"></div>
            </div>
            <textarea name="content" id="blogContent" style="display:none;">{{ old('content', $blog->content) }}</textarea>
          </div>
        </div>

        {{-- ── EXCERPT / DESCRIPTION ── --}}
        <div class="be-card" style="margin-top:18px;">
          <div class="be-card-header">
            <h2><i class="fas fa-align-left"></i> Excerpt / Short Description</h2>
          </div>
          <div class="be-card-body">
            <div class="be-form-group">
              <div class="input-with-counter">
                <textarea name="excerpt" id="blogExcerpt" class="be-textarea"
                  placeholder="Write a brief summary…" maxlength="300" rows="4">{{ old('excerpt', $blog->excerpt) }}</textarea>
                <span class="char-counter" id="excerptCounter">0/300</span>
              </div>
              <div class="be-input-hint">
                <i class="fas fa-info-circle"></i>
                Keep it under 160 characters if used as meta description.
              </div>
            </div>
          </div>
        </div>

        {{-- ── FEATURED IMAGE ── --}}
        <div class="be-card" style="margin-top:18px;">
          <div class="be-card-header">
            <h2><i class="fas fa-image"></i> Featured Image</h2>
          </div>
          <div class="be-card-body">

            {{-- Current image (if exists) --}}
            @if($blog->featured_image)
            <div class="current-img-notice">
              <i class="fas fa-image"></i> Current image saved. Upload a new one to replace it.
            </div>
            @endif

            <div class="img-upload-zone {{ $blog->featured_image ? 'has-image' : '' }}" id="featuredImgZone"
              style="{{ $blog->featured_image ? 'display:none;' : '' }}">
              <input type="file" name="featured_image" id="featuredImgInput" accept="image/jpeg,image/png,image/webp,image/gif"/>
              <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
              <div class="upload-text">
                <strong>Click to upload</strong> or drag &amp; drop<br>
                <span style="font-size:.72rem;">PNG, JPG, WebP — max 5 MB · Recommended: 1200 × 630 px</span>
              </div>
            </div>

            <div class="img-preview-wrap" id="featuredPreview" style="{{ $blog->featured_image ? 'display:block;' : 'display:none;' }}">
              <img src="{{ $blog->featured_image ? asset($blog->featured_image) : '' }}"
                alt="{{ old('image_alt', $blog->image_alt ?? '') }}"
                id="featuredPreviewImg"/>
              <div class="img-preview-actions">
                <button type="button" class="btn-be btn-outline btn-sm" id="btnChangeFeatured">
                  <i class="fas fa-exchange-alt"></i> Change
                </button>
                <button type="button" class="btn-be btn-danger-outline btn-sm" id="btnRemoveFeatured">
                  <i class="fas fa-trash"></i> Remove
                </button>
              </div>
            </div>

            {{-- Remove existing image flag --}}
            <input type="hidden" name="remove_featured_image" id="removeFeaturedFlag" value="0"/>

            {{-- Image SEO fields --}}
            <div class="be-form-group" style="margin-top:16px;">
              <label class="be-label">
                <i class="fas fa-tag" style="color:var(--primary);font-size:.8rem;"></i> Image Alt Text
                <span class="lbl-badge lbl-seo">SEO</span>
              </label>
              <input type="text" name="image_alt" id="imageAlt" class="be-input"
                placeholder="Describe the image for screen readers and search engines…"
                maxlength="125" value="{{ old('image_alt', $blog->image_alt ?? '') }}" />
            </div>

            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-heading" style="color:var(--primary);font-size:.8rem;"></i> Image Title
                <span class="lbl-badge lbl-optional">Optional</span>
              </label>
              <input type="text" name="image_title" id="imageTitle" class="be-input"
                placeholder="Tooltip text shown on mouse-over…" maxlength="125"
                value="{{ old('image_title', $blog->image_title ?? '') }}" />
            </div>

            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-file-image" style="color:var(--primary);font-size:.8rem;"></i> Image Caption
                <span class="lbl-badge lbl-optional">Optional</span>
              </label>
              <input type="text" name="image_caption" id="imageCaption" class="be-input"
                placeholder="Caption displayed below the image…"
                value="{{ old('image_caption', $blog->image_caption ?? '') }}" />
            </div>
          </div>
        </div>

        {{-- ── SEO META ── --}}
        <div class="be-card" style="margin-top:18px;">
          <div class="be-card-header">
            <h2><i class="fas fa-search"></i> SEO &amp; Meta</h2>
            <div id="seoScoreBadge" style="font-size:.75rem;font-weight:700;padding:4px 12px;border-radius:20px;background:#eef2f9;color:var(--muted);">
              Score: <span id="seoScoreLabel">0</span>/100
            </div>
          </div>
          <div class="be-card-body">

            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-key" style="color:var(--primary);font-size:.8rem;"></i> Focus Keyword
                <span class="lbl-badge lbl-seo">SEO</span>
              </label>
              <div style="position:relative;">
                <input type="text" name="focus_keyword" id="focusKeyword" class="be-input"
                  placeholder="e.g. best ai tools for developers"
                  value="{{ old('focus_keyword', $blog->focus_keyword) }}" />
                <span class="kw-density" id="kwDensityBadge" style="display:none;position:absolute;right:8px;top:50%;transform:translateY(-50%);"></span>
              </div>
            </div>

            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-heading" style="color:var(--primary);font-size:.8rem;"></i> Meta Title
                <span class="lbl-badge lbl-seo">SEO</span>
              </label>
              <div class="input-with-counter">
                <input type="text" name="meta_title" id="metaTitle" class="be-input"
                  placeholder="Leave blank to use post title | max 60 chars"
                  maxlength="70" value="{{ old('meta_title', $blog->meta_title) }}" />
                <span class="char-counter" id="metaTitleCounter">0/60</span>
              </div>
              <div class="meter-bar"><div class="meter-fill" id="metaTitleMeter" style="width:0%;"></div></div>
            </div>

            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-align-left" style="color:var(--primary);font-size:.8rem;"></i> Meta Description
                <span class="lbl-badge lbl-seo">SEO</span>
              </label>
              <div class="input-with-counter">
                <textarea name="meta_description" id="metaDescription" class="be-textarea"
                  placeholder="Compelling description shown in Google results…"
                  maxlength="170" rows="3">{{ old('meta_description', $blog->meta_description) }}</textarea>
                <span class="char-counter" id="metaDescCounter">0/160</span>
              </div>
              <div class="meter-bar"><div class="meter-fill" id="metaDescMeter" style="width:0%;"></div></div>
            </div>

            {{-- SERP Preview --}}
            <div class="be-form-group">
              <div class="seo-preview">
                <div class="seo-preview-label"><i class="fas fa-google"></i> Google SERP Preview</div>
                <div class="seo-url" id="serpUrl">{{ config('app.url') }} › blog › {{ $blog->slug }}</div>
                <a class="seo-title-prev" id="serpTitle">{{ $blog->meta_title ?: $blog->title }}</a>
                <div class="seo-desc-prev" id="serpDesc">{{ $blog->meta_description ?: $blog->excerpt }}</div>
              </div>
            </div>

            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-tags" style="color:var(--primary);font-size:.8rem;"></i> Meta Keywords
                <span class="lbl-badge lbl-optional">Optional</span>
              </label>
              <div class="tags-wrap" id="metaKeywordsWrap">
                <input class="tags-input" id="metaKeywordsInput" placeholder="Type keyword and press Enter or comma…" />
              </div>
              <input type="hidden" name="meta_keywords" id="metaKeywordsHidden"
                value="{{ old('meta_keywords', $blog->seo->meta_keywords ?? '') }}"/>
            </div>

            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-link" style="color:var(--primary);font-size:.8rem;"></i> Canonical URL
                <span class="lbl-badge lbl-optional">Optional</span>
              </label>
              <input type="url" name="canonical_url" id="canonicalUrl" class="be-input"
                placeholder="https://yourdomain.com/blog/your-slug"
                value="{{ old('canonical_url', $blog->seo->canonical_url ?? '') }}" />
            </div>

            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-robot" style="color:var(--primary);font-size:.8rem;"></i> Robots Directive
                <span class="lbl-badge lbl-seo">SEO</span>
              </label>
              <select name="robots" id="robotsSelect" class="be-select">
                @php $robots = old('robots', $blog->seo->robots ?? 'index, follow'); @endphp
                <option value="index, follow" {{ $robots == 'index, follow' ? 'selected':'' }}>index, follow (Default)</option>
                <option value="noindex, follow" {{ $robots == 'noindex, follow' ? 'selected':'' }}>noindex, follow</option>
                <option value="index, nofollow" {{ $robots == 'index, nofollow' ? 'selected':'' }}>index, nofollow</option>
                <option value="noindex, nofollow" {{ $robots == 'noindex, nofollow' ? 'selected':'' }}>noindex, nofollow</option>
              </select>
            </div>

          </div>
        </div>

        {{-- ── OPEN GRAPH / SOCIAL ── --}}
        <div class="be-card" style="margin-top:18px;">
          <div class="be-card-header">
            <h2><i class="fas fa-share-alt"></i> Open Graph / Social Sharing</h2>
          </div>
          <div class="be-card-body">
            <div class="social-preview-tabs">
              <button type="button" class="sp-tab active" data-tab="facebook"><i class="fab fa-facebook-f"></i> Facebook</button>
              <button type="button" class="sp-tab" data-tab="twitter"><i class="fab fa-twitter"></i> Twitter</button>
              <button type="button" class="sp-tab" data-tab="linkedin"><i class="fab fa-linkedin-in"></i> LinkedIn</button>
            </div>
            <div class="social-preview-card">
              <div class="sp-img" id="spImgWrap">
                @if($blog->seo && $blog->seo->og_image)
                  <img src="{{ Storage::url($blog->seo->og_image) }}" alt="" id="spPreviewImg" style="display:block;width:100%;height:100%;object-fit:cover;"/>
                @elseif($blog->featured_image)
                  <img src="{{ Storage::url($blog->featured_image) }}" alt="" id="spPreviewImg" style="display:block;width:100%;height:100%;object-fit:cover;"/>
                @else
                  <i class="fas fa-image"></i>
                  <img src="" alt="" id="spPreviewImg" style="display:none;"/>
                @endif
              </div>
              <div class="sp-info">
                <div class="sp-site" id="spSite">{{ strtoupper(parse_url(config('app.url'), PHP_URL_HOST)) }}</div>
                <div class="sp-title" id="spTitle">{{ $blog->seo->og_title ?? $blog->meta_title ?? $blog->title }}</div>
                <div class="sp-desc" id="spDesc">{{ $blog->seo->og_description ?? $blog->meta_description ?? $blog->excerpt }}</div>
              </div>
            </div>

            <div class="be-form-group" style="margin-top:16px;">
              <label class="be-label"><i class="fas fa-heading" style="color:var(--primary);font-size:.8rem;"></i> OG Title <span class="lbl-badge lbl-optional">Optional</span></label>
              <input type="text" name="og_title" id="ogTitle" class="be-input"
                placeholder="Custom title for social sharing"
                value="{{ old('og_title', $blog->seo->og_title ?? '') }}"/>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-align-left" style="color:var(--primary);font-size:.8rem;"></i> OG Description <span class="lbl-badge lbl-optional">Optional</span></label>
              <textarea name="og_description" id="ogDescription" class="be-textarea" rows="2"
                placeholder="Custom description for social sharing…">{{ old('og_description', $blog->seo->og_description ?? '') }}</textarea>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-image" style="color:var(--primary);font-size:.8rem;"></i> OG Image <span class="lbl-badge lbl-optional">Optional</span></label>
              <div class="img-upload-zone" style="padding:18px 20px;">
                <input type="file" name="og_image" id="ogImageInput" accept="image/*"/>
                <div class="upload-icon" style="font-size:1.4rem;"><i class="fas fa-cloud-upload-alt"></i></div>
                <div class="upload-text">Upload OG Image · Recommended: <strong>1200 × 630 px</strong></div>
              </div>
            </div>

            <div class="be-form-group">
              <label class="be-label"><i class="fab fa-twitter" style="color:#1da1f2;font-size:.8rem;"></i> Twitter Card Type</label>
              @php $twitterCard = old('twitter_card', $blog->seo->twitter_card ?? 'summary_large_image'); @endphp
              <select name="twitter_card" class="be-select">
                <option value="summary_large_image" {{ $twitterCard == 'summary_large_image' ? 'selected':'' }}>summary_large_image (Recommended)</option>
                <option value="summary" {{ $twitterCard == 'summary' ? 'selected':'' }}>summary (Small square image)</option>
                <option value="app" {{ $twitterCard == 'app' ? 'selected':'' }}>app</option>
              </select>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fab fa-twitter" style="color:#1da1f2;font-size:.8rem;"></i> Twitter @username <span class="lbl-badge lbl-optional">Optional</span></label>
              <input type="text" name="twitter_creator" class="be-input" placeholder="@yourhandle"
                value="{{ old('twitter_creator', $blog->seo->twitter_creator ?? '') }}"/>
            </div>
          </div>
        </div>

        {{-- ── SCHEMA MARKUP ── --}}
        <div class="be-card" style="margin-top:18px;">
          <div class="be-card-header">
            <h2><i class="fas fa-code"></i> Schema / Structured Data</h2>
          </div>
          <div class="be-card-body">
            @php $schemaType = old('schema_type', $blog->seo->schema_type ?? 'Article'); @endphp
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-layer-group" style="color:var(--primary);font-size:.8rem;"></i> Schema Type <span class="lbl-badge lbl-seo">SEO</span></label>
              <div class="schema-pills">
                @foreach(['Article','BlogPosting','HowTo','FAQPage','Review','NewsArticle'] as $type)
                <label class="schema-pill {{ $schemaType === $type ? 'active' : '' }}">
                  <input type="radio" name="schema_type" value="{{ $type }}" {{ $schemaType === $type ? 'checked':'' }} style="display:none;"/>
                  <i class="fas {{ $type === 'HowTo' ? 'fa-list-ol' : ($type === 'FAQPage' ? 'fa-question-circle' : ($type === 'Review' ? 'fa-star' : 'fa-newspaper')) }}"></i> {{ $type }}
                </label>
                @endforeach
              </div>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-user" style="color:var(--primary);font-size:.8rem;"></i> Author Name</label>
              <input type="text" name="schema_author" class="be-input" placeholder="Author full name"
                value="{{ old('schema_author', $blog->seo->schema_author ?? auth()->user()->name ?? '') }}"/>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-star" style="color:var(--primary);font-size:.8rem;"></i> Rating (if Review) <span class="lbl-badge lbl-optional">Optional</span></label>
              <div style="display:flex;gap:8px;">
                <input type="number" name="schema_rating_value" class="be-input" placeholder="Rating (1-5)" min="1" max="5" step=".1"
                  value="{{ old('schema_rating_value', $blog->seo->schema_rating_value ?? '') }}"/>
                <input type="number" name="schema_rating_count" class="be-input" placeholder="Review count" min="0"
                  value="{{ old('schema_rating_count', $blog->seo->schema_rating_count ?? '') }}"/>
              </div>
            </div>
          </div>
        </div>

        {{-- ── ADVANCED SEO ── --}}
        <div class="be-card" style="margin-top:18px;">
          <div class="be-card-header">
            <h2><i class="fas fa-cogs"></i> Advanced SEO</h2>
          </div>
          <div class="be-card-body">
            @php $hreflang = old('hreflang', $blog->seo->hreflang ?? 'en'); @endphp
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-globe" style="color:var(--primary);font-size:.8rem;"></i> Hreflang / Language</label>
              <select name="hreflang" class="be-select">
                @foreach(['en' => 'English (en)', 'en-us' => 'English US (en-us)', 'en-gb' => 'English UK (en-gb)', 'es' => 'Spanish (es)', 'fr' => 'French (fr)', 'de' => 'German (de)', 'hi' => 'Hindi (hi)', 'ar' => 'Arabic (ar)'] as $val => $label)
                <option value="{{ $val }}" {{ $hreflang == $val ? 'selected':'' }}>{{ $label }}</option>
                @endforeach
              </select>
            </div>
            @php $sitemapPriority = old('sitemap_priority', $blog->seo->sitemap_priority ?? '0.9'); @endphp
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-sitemap" style="color:var(--primary);font-size:.8rem;"></i> Sitemap Priority</label>
              <select name="sitemap_priority" class="be-select">
                @foreach(['1.0' => '1.0 — Highest','0.9' => '0.9 — Very High','0.8' => '0.8 — High','0.7' => '0.7 — Medium-High','0.5' => '0.5 — Medium','0.3' => '0.3 — Low'] as $val => $label)
                <option value="{{ $val }}" {{ $sitemapPriority == $val ? 'selected':'' }}>{{ $label }}</option>
                @endforeach
              </select>
            </div>
            @php $changefreq = old('sitemap_changefreq', $blog->seo->sitemap_changefreq ?? 'daily'); @endphp
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-sync" style="color:var(--primary);font-size:.8rem;"></i> Sitemap Change Frequency</label>
              <select name="sitemap_changefreq" class="be-select">
                @foreach(['always','hourly','daily','weekly','monthly','yearly','never'] as $freq)
                <option value="{{ $freq }}" {{ $changefreq == $freq ? 'selected':'' }}>{{ $freq }}</option>
                @endforeach
              </select>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-code" style="color:var(--primary);font-size:.8rem;"></i> Custom Head Scripts <span class="lbl-badge lbl-optional">Optional</span></label>
              <textarea name="custom_head_scripts" class="be-textarea" rows="3"
                style="font-family:monospace;font-size:.8rem;"
                placeholder="&lt;script&gt; or &lt;style&gt; injected into &lt;head&gt; for this post only…">{{ old('custom_head_scripts', $blog->seo->custom_head_scripts ?? '') }}</textarea>
            </div>
          </div>
        </div>

      </div>{{-- end left --}}

      {{-- ══════════════════════════════════════════
           RIGHT COLUMN — Sidebar
           ══════════════════════════════════════════ --}}
      <div class="be-right">

        {{-- ── SEO SCORE ── --}}
        <div class="be-card">
          <div class="be-card-header"><h2><i class="fas fa-chart-pie"></i> SEO Score</h2></div>
          <div class="be-card-body">
            <div class="seo-score-wrap">
              <div class="seo-score-ring" id="seoRing" style="background:#e2e8f0;color:#6b7a99;">0</div>
              <div class="seo-score-info">
                <strong id="seoScoreText">Analysing your content…</strong>
                <span id="seoScoreSubtext">Score updates as you edit</span>
              </div>
            </div>
            <ul class="seo-checklist" id="seoChecklist">
              <li class="fail" id="ck-title"><i class="fas fa-times-circle"></i> Focus keyword in title</li>
              <li class="fail" id="ck-slug"><i class="fas fa-times-circle"></i> Keyword in slug</li>
              <li class="fail" id="ck-metadesc"><i class="fas fa-times-circle"></i> Meta description set (120–160 chars)</li>
              <li class="fail" id="ck-kw-metadesc"><i class="fas fa-times-circle"></i> Keyword in meta description</li>
              <li class="fail" id="ck-content-len"><i class="fas fa-times-circle"></i> Content ≥ 300 words</li>
              <li class="fail" id="ck-content-kw"><i class="fas fa-times-circle"></i> Keyword appears in content</li>
              <li class="fail" id="ck-heading"><i class="fas fa-times-circle"></i> Heading (H2/H3) in content</li>
              <li class="fail" id="ck-img"><i class="fas fa-times-circle"></i> Featured image set</li>
              <li class="fail" id="ck-img-alt"><i class="fas fa-times-circle"></i> Image alt text set</li>
              <li class="fail" id="ck-links"><i class="fas fa-times-circle"></i> At least 1 link in content</li>
              <li class="fail" id="ck-metatitle-len"><i class="fas fa-times-circle"></i> Meta title 30–60 chars</li>
              <li class="warn" id="ck-kw-density"><i class="fas fa-exclamation-triangle"></i> Keyword density 1–3%</li>
            </ul>
          </div>
        </div>

        {{-- ── PUBLISH SETTINGS ── --}}
        <div class="be-card">
          <div class="be-card-header"><h2><i class="fas fa-cog"></i> Publish Settings</h2></div>
          <div class="be-card-body">

            @php $visibility = old('visibility', $blog->visibility ?? 'public'); @endphp
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-eye" style="color:var(--primary);font-size:.8rem;"></i> Visibility</label>
              <div class="vis-pills">
                <label class="vis-pill {{ $visibility === 'public' ? 'active':'' }}">
                  <input type="radio" name="visibility" value="public" {{ $visibility === 'public' ? 'checked':'' }} style="display:none;"/>
                  <i class="fas fa-globe"></i> Public
                </label>
                <label class="vis-pill {{ $visibility === 'private' ? 'active':'' }}">
                  <input type="radio" name="visibility" value="private" {{ $visibility === 'private' ? 'checked':'' }} style="display:none;"/>
                  <i class="fas fa-lock"></i> Private
                </label>
                <label class="vis-pill {{ $visibility === 'password' ? 'active':'' }}">
                  <input type="radio" name="visibility" value="password" {{ $visibility === 'password' ? 'checked':'' }} style="display:none;"/>
                  <i class="fas fa-key"></i> Password
                </label>
              </div>
            </div>

            <div class="be-form-group" id="passwordField" style="{{ $visibility === 'password' ? '' : 'display:none;' }}">
              <label class="be-label"><i class="fas fa-key" style="color:var(--primary);font-size:.8rem;"></i> Post Password</label>
              <input type="password" name="post_password" class="be-input" placeholder="Enter password…"/>
            </div>

            @php $status = old('status', $blog->status ?? 'draft'); @endphp
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-flag" style="color:var(--primary);font-size:.8rem;"></i> Status</label>
              <select name="status" id="postStatus" class="be-select">
                <option value="draft" {{ $status == 'draft' ? 'selected':'' }}>Draft</option>
                <option value="pending" {{ $status == 'pending' ? 'selected':'' }}>Pending Review</option>
                <option value="published" {{ $status == 'published' ? 'selected':'' }}>Published</option>
                <option value="scheduled" {{ $status == 'scheduled' ? 'selected':'' }}>Scheduled</option>
              </select>
            </div>

            <div class="be-form-group" id="scheduleField" style="{{ $status === 'scheduled' ? '' : 'display:none;' }}">
              <label class="be-label"><i class="fas fa-calendar-alt" style="color:var(--primary);font-size:.8rem;"></i> Publish Date &amp; Time</label>
              <input type="datetime-local" name="published_at" class="be-input"
                value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}"/>
            </div>

            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-comments" style="color:var(--primary);font-size:.8rem;"></i> Comments</label>
              <select name="allow_comments" class="be-select">
                <option value="1" {{ ($blog->allow_comments ?? 1) == 1 ? 'selected':'' }}>Allow comments</option>
                <option value="0" {{ ($blog->allow_comments ?? 1) == 0 ? 'selected':'' }}>Disable comments</option>
              </select>
            </div>

            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-clock" style="color:var(--primary);font-size:.8rem;"></i> Reading Time Override <span class="lbl-badge lbl-optional">Optional</span></label>
              <input type="text" name="reading_time" id="readingTimeField" class="be-input"
                placeholder="e.g. 5 min read"
                value="{{ old('reading_time', $blog->reading_time ?? '') }}"/>
            </div>

            <div style="padding-top:8px;display:flex;gap:8px;flex-direction:column;">
              <button type="button" class="btn-be btn-success" id="sidebarPublish">
                <i class="fas fa-rocket"></i> Update &amp; Publish
              </button>
              <button type="button" class="btn-be btn-outline" id="sidebarDraft">
                <i class="fas fa-save"></i> Save as Draft
              </button>
            </div>

            {{-- Danger zone --}}
            <div class="danger-zone">
              <div class="danger-zone-title"><i class="fas fa-exclamation-triangle"></i> Danger Zone</div>
              <a href="{{ route('blogs.destroy', $blog->id) }}"
                class="btn-be btn-danger-outline btn-sm btn-delete-blog"
                data-title="{{ $blog->title }}"
                style="width:100%;justify-content:center;">
                <i class="fas fa-trash"></i> Delete This Post
              </a>
            </div>

          </div>
        </div>

        {{-- ── CATEGORIES ── --}}
        <div class="be-card">
          <div class="be-card-header">
            <h2><i class="fas fa-folder"></i> Category</h2>
            <button type="button" class="btn-be btn-outline btn-sm" id="btnAddCat">+ Add New</button>
          </div>
          <div class="be-card-body">
            <div class="be-form-group" id="newCatField" style="display:none;margin-bottom:12px;">
              <div style="display:flex;gap:6px;">
                <input type="text" id="newCatInput" class="be-input" placeholder="New category name…"/>
                <button type="button" class="btn-be btn-primary btn-sm" id="btnSaveCat">Add</button>
              </div>
            </div>
            <select name="category_id" id="categorySelect" class="be-select">
              <option value="">— Select Category —</option>
              @foreach($categories ?? [] as $cat)
                <option value="{{ $cat->id }}" {{ (old('category_id', $blog->category_id) == $cat->id) ? 'selected':'' }}>{{ $cat->name }}</option>
              @endforeach
              @if(empty($categories))
                <option value="1" {{ $blog->category_id == 1 ? 'selected':'' }}>Technology</option>
                <option value="2" {{ $blog->category_id == 2 ? 'selected':'' }}>AI &amp; Machine Learning</option>
                <option value="3" {{ $blog->category_id == 3 ? 'selected':'' }}>Web Development</option>
                <option value="4" {{ $blog->category_id == 4 ? 'selected':'' }}>Cloud &amp; DevOps</option>
                <option value="5" {{ $blog->category_id == 5 ? 'selected':'' }}>Business</option>
              @endif
            </select>
            <div class="be-form-group" style="margin-top:12px;">
              <label class="be-label"><i class="fas fa-folder-open" style="color:var(--primary);font-size:.8rem;"></i> Sub-Category <span class="lbl-badge lbl-optional">Optional</span></label>
              <select name="subcategory_id" class="be-select">
                <option value="">— None —</option>
              </select>
            </div>
          </div>
        </div>

        {{-- ── TAGS ── --}}
        <div class="be-card">
          <div class="be-card-header"><h2><i class="fas fa-tags"></i> Tags</h2></div>
          <div class="be-card-body">
            <div class="be-form-group">
              <div class="tags-wrap" id="postTagsWrap">
                <input class="tags-input" id="postTagsInput" placeholder="Add tag, press Enter…"/>
              </div>
              {{-- Pre-fill existing tags --}}
              <input type="hidden" name="tags" id="postTagsHidden"
                value="{{ old('tags', $blog->tags->pluck('name')->implode(',')) }}"/>
              <div class="be-input-hint"><i class="fas fa-info-circle"></i> Separate tags with Enter or comma</div>
            </div>
            <div id="suggestedTags" style="display:flex;flex-wrap:wrap;gap:6px;margin-top:8px;">
              <span style="font-size:.7rem;color:var(--muted);width:100%;margin-bottom:2px;font-weight:700;">Suggested:</span>
              <span class="tag-pill suggested-tag" style="cursor:pointer;" data-tag="Technology">Technology</span>
              <span class="tag-pill suggested-tag" style="cursor:pointer;" data-tag="AI">AI</span>
              <span class="tag-pill suggested-tag" style="cursor:pointer;" data-tag="Web Dev">Web Dev</span>
              <span class="tag-pill suggested-tag" style="cursor:pointer;" data-tag="Tutorial">Tutorial</span>
              <span class="tag-pill suggested-tag" style="cursor:pointer;" data-tag="Tips">Tips</span>
            </div>
          </div>
        </div>

        {{-- ── AUTHOR ── --}}
        <div class="be-card">
          <div class="be-card-header"><h2><i class="fas fa-user-pen"></i> Author</h2></div>
          <div class="be-card-body">
            <select name="author_id" class="be-select">
              <option value="{{ $blog->author_id ?? auth()->id() }}">
                {{ $blog->author->name ?? auth()->user()->name ?? 'Admin User' }}
              </option>
            </select>
          </div>
        </div>

        {{-- ── REVISION HISTORY ── --}}
        <div class="be-card">
          <div class="be-card-header"><h2><i class="fas fa-history"></i> Post Info</h2></div>
          <div class="be-card-body">
            <div class="post-meta-list">
              <div class="pml-row">
                <span class="pml-label"><i class="fas fa-hashtag"></i> Post ID</span>
                <span class="pml-value">#{{ $blog->id }}</span>
              </div>
              <div class="pml-row">
                <span class="pml-label"><i class="fas fa-eye"></i> Total Views</span>
                <span class="pml-value">{{ number_format($blog->views ?? 0) }}</span>
              </div>
              <div class="pml-row">
                <span class="pml-label"><i class="fas fa-calendar-plus"></i> Created</span>
                <span class="pml-value">{{ $blog->created_at->format('M d, Y') }}</span>
              </div>
              <div class="pml-row">
                <span class="pml-label"><i class="fas fa-edit"></i> Last Updated</span>
                <span class="pml-value">{{ $blog->updated_at->format('M d, Y H:i') }}</span>
              </div>
              @if($blog->published_at)
              <div class="pml-row">
                <span class="pml-label"><i class="fas fa-rocket"></i> Published</span>
                <span class="pml-value">{{ $blog->published_at->format('M d, Y H:i') }}</span>
              </div>
              @endif
              <div class="pml-row">
                <span class="pml-label"><i class="fas fa-clock"></i> Read Time</span>
                <span class="pml-value">{{ $blog->reading_time ?? '—' }}</span>
              </div>
            </div>
            @if($blog->status === 'published')
            <a href="{{ url('/blog/' . $blog->slug) }}" target="_blank" class="btn-be btn-outline btn-sm" style="width:100%;justify-content:center;margin-top:12px;">
              <i class="fas fa-external-link-alt"></i> View Live Post
            </a>
            @endif
          </div>
        </div>

      </div>{{-- end right --}}
    </div>{{-- end grid --}}
  </form>

</div>{{-- /be-wrap --}}
</div>{{-- /blogEditor --}}

{{-- ── DELETE CONFIRMATION MODAL ── --}}
{{-- <div class="modal-backdrop" id="deleteModal" style="display:none;">
  <div class="modal-box">
    <div class="modal-icon" style="color:var(--danger);"><i class="fas fa-trash-alt"></i></div>
    <h3 style="margin:0 0 8px;color:var(--text);">Delete Post?</h3>
    <p style="color:var(--muted);font-size:.85rem;margin:0 0 20px;">
      You are about to permanently delete "<strong id="deleteModalTitle"></strong>". This action cannot be undone.
    </p>
    <div style="display:flex;gap:10px;">
      <button class="btn-be btn-outline" id="deleteCancelBtn" style="flex:1;">Cancel</button>
      <form id="deleteForm" method="POST" style="flex:1;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-be" style="width:100%;background:var(--danger);color:#fff;border-color:var(--danger);">
          <i class="fas fa-trash"></i> Yes, Delete
        </button>
      </form>
    </div>
  </div>
</div> --}}

@endsection


@push('scripts')
<script>
$(function () {

  /* ═══════════════════════════════════════
     1. QUILL RICH TEXT EDITOR — pre-fill existing content
  ═══════════════════════════════════════ */
  var quill = new Quill('#quillEditor', {
    theme: 'snow',
    placeholder: 'Edit your blog post content here…',
    modules: {
      toolbar: [
        [{ 'header': [1, 2, 3, 4, false] }],
        [{ 'font': [] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'align': [] }],
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        [{ 'indent': '-1' }, { 'indent': '+1' }],
        ['blockquote', 'code-block'],
        ['link', 'image', 'video'],
        ['clean']
      ]
    }
  });

  // Pre-fill existing content
  var existingContent = $('#blogContent').val();
  if (existingContent) { quill.clipboard.dangerouslyPasteHTML(existingContent); }

  // Sync Quill → hidden textarea + stats
  quill.on('text-change', function () {
    var html = quill.root.innerHTML;
    $('#blogContent').val(html);
    updateContentStats(html);
    updateSeoScore();
  });

  // Run stats immediately on load
  updateContentStats(existingContent || '');

  function updateContentStats(html) {
    var $t = $('<div>').html(html);
    var text = $t.text().trim();
    var words = text ? text.split(/\s+/).filter(Boolean).length : 0;
    $('#wordCount').text(words);
    $('#readTime').text(Math.max(1, Math.ceil(words / 200)) + ' min');
    $('#headingCount').text($t.find('h1,h2,h3,h4,h5,h6').length);
    $('#linkCount').text($t.find('a').length);
    $('#imgCount').text($t.find('img').length);
    $('#paraCount').text($t.find('p').length);
    $('#readingTimeField').val(Math.max(1, Math.ceil(words / 200)) + ' min read');
  }

  /* ═══════════════════════════════════════
     2. TITLE counter + slug
  ═══════════════════════════════════════ */
  function slugify(str) {
    return str.toLowerCase()
      .replace(/[^a-z0-9\s-]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-')
      .replace(/^-|-$/g, '');
  }

  // Init counter on load
  var initTitleLen = $('#blogTitle').val().length;
  $('#titleCounter').text(initTitleLen + '/70');
  if (initTitleLen >= 50 && initTitleLen <= 60) $('#titleCounter').addClass('good');

  $('#blogTitle').on('input', function () {
    var val = $(this).val(), len = val.length;
    var $c = $('#titleCounter');
    $c.text(len + '/70').removeClass('warn over good');
    if (len >= 50 && len <= 60) $c.addClass('good');
    else if (len > 60) $c.addClass('warn');
    if (len >= 68) $c.removeClass('warn').addClass('over');
    if (!$('#blogSlug').data('manual')) {
      $('#blogSlug').val(slugify(val));
    }
    updateSerpPreview();
    updateSeoScore();
  });

  // Mark slug as manually set on load (existing slug)
  $('#blogSlug').data('manual', true);

  $('#blogSlug').on('input', function () {
    $(this).data('manual', true).val(slugify($(this).val()));
    updateSerpPreview();
    updateSeoScore();
  });

  $('#btnRegenerateSlug').on('click', function () {
    if (confirm('Regenerating the slug will change the URL and may break existing links/SEO. Continue?')) {
      $('#blogSlug').data('manual', false).val(slugify($('#blogTitle').val()));
      updateSerpPreview();
      updateSeoScore();
    }
  });

  $('#btnCopySlug').on('click', function () {
    var slug = $('#slugPrefix').text() + $('#blogSlug').val();
    navigator.clipboard.writeText(slug).catch(function(){});
    var $i = $(this).find('i');
    $i.removeClass('fa-copy').addClass('fa-check');
    setTimeout(function(){ $i.removeClass('fa-check').addClass('fa-copy'); }, 1500);
  });

  /* ═══════════════════════════════════════
     3. EXCERPT counter — init on load
  ═══════════════════════════════════════ */
  function initCounter(id, counterId, max) {
    var $el = $('#' + id), $c = $('#' + counterId);
    function update() {
      var len = $el.val().length;
      $c.text(len + '/' + max).removeClass('warn over good');
    }
    update();
    $el.on('input', function(){ update(); updateSeoScore(); });
  }
  initCounter('blogExcerpt', 'excerptCounter', 300);

  /* ═══════════════════════════════════════
     4. META TITLE counter + meter — init
  ═══════════════════════════════════════ */
  function updateMetaTitleUI() {
    var len = $('#metaTitle').val().length;
    var $c = $('#metaTitleCounter');
    $c.text(len + '/60').removeClass('warn over good');
    var pct = Math.min(100, (len / 60) * 100);
    var color = '#e2e8f0';
    if (len >= 30 && len <= 60) { color = '#00c896'; $c.addClass('good'); }
    else if (len > 60)          { color = '#ff4d6d'; $c.addClass('over'); }
    else if (len > 0)           { color = '#ffb830'; $c.addClass('warn'); }
    $('#metaTitleMeter').css({ width: pct + '%', background: color });
    updateSerpPreview(); updateSocialPreview(); updateSeoScore();
  }
  updateMetaTitleUI();
  $('#metaTitle').on('input', updateMetaTitleUI);

  /* ═══════════════════════════════════════
     5. META DESC counter + meter — init
  ═══════════════════════════════════════ */
  function updateMetaDescUI() {
    var len = $('#metaDescription').val().length;
    var $c = $('#metaDescCounter');
    $c.text(len + '/160').removeClass('warn over good');
    var pct = Math.min(100, (len / 160) * 100);
    var color = '#e2e8f0';
    if (len >= 120 && len <= 160) { color = '#00c896'; $c.addClass('good'); }
    else if (len > 160)           { color = '#ff4d6d'; $c.addClass('over'); }
    else if (len > 0)             { color = '#ffb830'; $c.addClass('warn'); }
    $('#metaDescMeter').css({ width: pct + '%', background: color });
    updateSerpPreview(); updateSocialPreview(); updateSeoScore();
  }
  updateMetaDescUI();
  $('#metaDescription').on('input', updateMetaDescUI);

  /* ═══════════════════════════════════════
     6. SERP PREVIEW
  ═══════════════════════════════════════ */
  function updateSerpPreview() {
    var slug   = $('#blogSlug').val() || 'your-slug';
    var title  = $('#metaTitle').val() || $('#blogTitle').val() || 'Your post title…';
    var desc   = $('#metaDescription').val() || $('#blogExcerpt').val() || 'Your meta description will appear here.';
    var domain = window.location.hostname || 'yourdomain.com';
    $('#serpUrl').text(domain + ' › blog › ' + slug);
    $('#serpTitle').text(title.substring(0, 70));
    $('#serpDesc').text(desc.substring(0, 160));
  }

  /* ═══════════════════════════════════════
     7. SOCIAL PREVIEW
  ═══════════════════════════════════════ */
  $('.sp-tab').on('click', function () {
    $('.sp-tab').removeClass('active');
    $(this).addClass('active');
    updateSocialPreview();
  });
  function updateSocialPreview() {
    var title  = $('#ogTitle').val() || $('#metaTitle').val() || $('#blogTitle').val() || 'Your post title…';
    var desc   = $('#ogDescription').val() || $('#metaDescription').val() || $('#blogExcerpt').val() || 'Description appears here.';
    var tab    = $('.sp-tab.active').data('tab');
    var domain = window.location.hostname || 'yourdomain.com';
    $('#spSite').text(domain.toUpperCase() + (tab === 'twitter' ? ' ON TWITTER' : ''));
    $('#spTitle').text(title.substring(0, 88));
    $('#spDesc').text(desc.substring(0, 120));
  }
  $('#ogTitle, #ogDescription').on('input', updateSocialPreview);

  /* ═══════════════════════════════════════
     8. FEATURED IMAGE — upload, change, remove
  ═══════════════════════════════════════ */
  $('#featuredImgInput').on('change', function () {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function (e) {
      $('#featuredPreviewImg').attr('src', e.target.result);
      $('#featuredImgZone').hide();
      $('#featuredPreview').show();
      $('#removeFeaturedFlag').val('0');
      $('#spPreviewImg').attr('src', e.target.result).show();
      $('#spImgWrap i').hide();
      updateSeoScore();
    };
    reader.readAsDataURL(file);
  });

  $('#btnRemoveFeatured').on('click', function () {
    if (!confirm('Remove the featured image?')) return;
    $('#featuredImgInput').val('');
    $('#featuredPreviewImg').attr('src', '');
    $('#featuredImgZone').show();
    $('#featuredPreview').hide();
    $('#removeFeaturedFlag').val('1');
    $('#spPreviewImg').hide();
    $('#spImgWrap i').show();
    updateSeoScore();
  });

  $('#btnChangeFeatured').on('click', function () { $('#featuredImgInput').trigger('click'); });

  // Drag & drop
  var $zone = $('#featuredImgZone');
  $zone.on('dragover', function(e){ e.preventDefault(); $(this).addClass('drag-over'); });
  $zone.on('dragleave', function(){ $(this).removeClass('drag-over'); });
  $zone.on('drop', function(e){
    e.preventDefault(); $(this).removeClass('drag-over');
    var files = e.originalEvent.dataTransfer.files;
    if (files.length) { $('#featuredImgInput')[0].files = files; $('#featuredImgInput').trigger('change'); }
  });

  $('#ogImageInput').on('change', function () {
    var file = this.files[0]; if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e){ $('#spPreviewImg').attr('src', e.target.result).show(); $('#spImgWrap i').hide(); };
    reader.readAsDataURL(file);
  });

  /* ═══════════════════════════════════════
     9. IMAGE ALT triggers SEO score
  ═══════════════════════════════════════ */
  $('#imageAlt').on('input', updateSeoScore);

  /* ═══════════════════════════════════════
     10. TAGS INPUT — pre-populate existing tags
  ═══════════════════════════════════════ */
  function initTagsInput(wrapId, inputId, hiddenId) {
    var tags = [];
    var $wrap = $('#' + wrapId), $inp = $('#' + inputId), $hid = $('#' + hiddenId);
    // Pre-fill from hidden value
    var existing = $hid.val();
    if (existing) { existing.split(',').forEach(function(t){ if(t.trim()) addTag(t.trim()); }); }

    $wrap.on('click', function(){ $inp.focus(); });
    $inp.on('keydown', function(e) {
      if ((e.key === 'Enter' || e.key === ',') && $(this).val().trim()) {
        e.preventDefault();
        addTag($(this).val().trim().replace(/,/g,''));
        $(this).val('');
      }
      if (e.key === 'Backspace' && !$(this).val() && tags.length) { removeTag(tags[tags.length-1]); }
    });

    function addTag(txt) {
      if (!txt || tags.includes(txt)) return;
      tags.push(txt);
      var $pill = $('<span class="tag-pill">' + $('<div>').text(txt).html() + '<button type="button"><i class="fas fa-times"></i></button></span>');
      $pill.find('button').on('click', function(){ removeTag(txt); });
      $inp.before($pill);
      syncHidden();
    }
    function removeTag(txt) {
      tags = tags.filter(function(t){ return t !== txt; });
      $wrap.find('.tag-pill').filter(function(){ return $(this).text().trim() === txt; }).remove();
      syncHidden();
    }
    function syncHidden() { $hid.val(tags.join(',')); }
    return { addTag: addTag };
  }

  var metaKwHandler  = initTagsInput('metaKeywordsWrap', 'metaKeywordsInput', 'metaKeywordsHidden');
  var postTagHandler = initTagsInput('postTagsWrap', 'postTagsInput', 'postTagsHidden');

  $(document).on('click', '.suggested-tag', function () { postTagHandler.addTag($(this).data('tag')); });

  /* ═══════════════════════════════════════
     11. FOCUS KEYWORD density
  ═══════════════════════════════════════ */
  $('#focusKeyword').on('input', function () {
    updateSeoScore();
    var kw = $(this).val().trim().toLowerCase();
    if (!kw) { $('#kwDensityBadge').hide(); return; }
    var text = quill.getText().toLowerCase();
    var words = text.split(/\s+/).filter(Boolean).length;
    var count = (text.match(new RegExp(kw, 'g')) || []).length;
    var density = words > 0 ? ((count / words) * 100).toFixed(1) : 0;
    var $b = $('#kwDensityBadge').show();
    var bg = '#e8f1fd', color = '#1a73e8';
    if (density >= 1 && density <= 3) { bg = '#d4f5ec'; color = '#00a87c'; }
    else if (density > 3)             { bg = '#ffe2e8'; color = '#ff4d6d'; }
    $b.text(density + '%').css({ background: bg, color: color, padding: '2px 8px', borderRadius: '12px', fontSize: '.72rem', fontWeight: 700 });
  });

  /* ═══════════════════════════════════════
     12. SEO SCORE — runs on load
  ═══════════════════════════════════════ */
  function updateSeoScore() {
    var score = 0;
    var kw    = $('#focusKeyword').val().trim().toLowerCase();
    var title = $('#blogTitle').val().toLowerCase();
    var slug  = $('#blogSlug').val().toLowerCase();
    var metaDesc  = $('#metaDescription').val();
    var metaTitle = $('#metaTitle').val();
    var imgAlt    = $('#imageAlt').val().trim();
    var contentText = quill.getText().toLowerCase();
    var contentHtml = quill.root.innerHTML;
    var words = contentText.split(/\s+/).filter(Boolean).length;
    var $t = $('<div>').html(contentHtml);
    var hasHeading = $t.find('h2,h3').length > 0;
    var hasLink    = $t.find('a').length > 0;
    var hasFeatImg = $('#featuredPreview').is(':visible');

    function setCheck(id, pass, warn) {
      var $li = $('#' + id).removeClass('pass fail warn');
      var icon = pass ? 'fa-check-circle' : (warn ? 'fa-exclamation-triangle' : 'fa-times-circle');
      $li.addClass(pass ? 'pass' : (warn ? 'warn' : 'fail'))
         .find('i').removeClass('fa-check-circle fa-times-circle fa-exclamation-triangle').addClass(icon);
    }

    var ck1 = kw && title.includes(kw); setCheck('ck-title', ck1, false); if (ck1) score += 10;
    var ck2 = kw && slug.includes(kw.replace(/\s+/g,'-')); setCheck('ck-slug', ck2, false); if (ck2) score += 8;
    var mdLen = metaDesc.length;
    var ck3 = mdLen >= 120 && mdLen <= 160, ck3w = mdLen > 0 && !ck3;
    setCheck('ck-metadesc', ck3, ck3w); if (ck3) score += 10; else if (ck3w) score += 4;
    var ck4 = kw && metaDesc.toLowerCase().includes(kw); setCheck('ck-kw-metadesc', ck4, false); if (ck4) score += 8;
    var ck5 = words >= 300, ck5w = words >= 100 && words < 300;
    setCheck('ck-content-len', ck5, ck5w); if (ck5) score += 15; else if (ck5w) score += 5;
    var ck6 = kw && contentText.includes(kw); setCheck('ck-content-kw', ck6, false); if (ck6) score += 10;
    setCheck('ck-heading', hasHeading, false); if (hasHeading) score += 8;
    setCheck('ck-img', hasFeatImg, false); if (hasFeatImg) score += 8;
    var ck9 = imgAlt.length > 0; setCheck('ck-img-alt', ck9, false); if (ck9) score += 7;
    setCheck('ck-links', hasLink, false); if (hasLink) score += 6;
    var mtLen = metaTitle.length;
    var ck11 = mtLen >= 30 && mtLen <= 60, ck11w = mtLen > 0 && !ck11;
    setCheck('ck-metatitle-len', ck11, ck11w); if (ck11) score += 6; else if (ck11w) score += 2;
    var density = 0;
    if (kw && words > 0) {
      var kwCount = (contentText.match(new RegExp(kw.replace(/[-\/\\^$*+?.()|[\]{}]/g,'\\$&'), 'g')) || []).length;
      density = (kwCount / words) * 100;
    }
    var ck12ok = density >= 1 && density <= 3, ck12w = (density > 0 && density < 1) || density > 3;
    setCheck('ck-kw-density', ck12ok, ck12w); if (ck12ok) score += 4; else if (ck12w) score += 1;

    var ringColor = score >= 80 ? '#00c896' : score >= 50 ? '#ffb830' : '#ff4d6d';
    var label = score >= 80 ? 'Excellent!' : score >= 60 ? 'Good' : score >= 40 ? 'Needs Work' : 'Poor';
    var sub   = score >= 80 ? 'Well optimised' : score >= 60 ? 'A few improvements needed' : 'Fill in key SEO fields';
    $('#seoRing').text(score).css('background', ringColor);
    $('#seoScoreText').text(label);
    $('#seoScoreSubtext').text(sub);
    $('#seoScoreLabel').text(score);
    var badgeBg = score >= 80 ? '#d4f5ec' : score >= 60 ? '#fff4d6' : '#ffe2e8';
    var badgeColor = score >= 80 ? '#00a87c' : score >= 60 ? '#b8860b' : '#ff4d6d';
    $('#seoScoreBadge').css({ background: badgeBg, color: badgeColor });
  }
  // Run SEO score on page load
  setTimeout(updateSeoScore, 400);

  /* ═══════════════════════════════════════
     13. FORM SUBMIT — publish / draft
  ═══════════════════════════════════════ */
  function submitForm(status) {
    $('#postStatus').val(status);
    $('#blogContent').val(quill.root.innerHTML);
    if (!$('#blogTitle').val().trim()) {
      alert('Please enter a post title before saving.');
      $('#blogTitle').focus();
      return;
    }
    if (!$('#blogSlug').val().trim()) { $('#blogSlug').val(slugify($('#blogTitle').val())); }
    $('#blogForm')[0].submit();
  }

  $('#btnPublish, #sidebarPublish').on('click', function () { submitForm('published'); });
  $('#btnSaveDraft, #sidebarDraft').on('click', function ()  { submitForm('draft'); });

  $('#btnPreview').on('click', function () {
    var slug = $('#blogSlug').val();
    if (slug) window.open('/blog/' + slug + '?preview=1', '_blank');
    else alert('Please set a slug first.');
  });

  /* ═══════════════════════════════════════
     14. VISIBILITY toggle
  ═══════════════════════════════════════ */
  $(document).on('click', '.vis-pill', function () {
    $('.vis-pill').removeClass('active');
    $(this).addClass('active');
    var val = $(this).find('input').val();
    if (val === 'password') $('#passwordField').slideDown(200);
    else $('#passwordField').slideUp(200);
  });

  /* ═══════════════════════════════════════
     15. STATUS → schedule field
  ═══════════════════════════════════════ */
  $('#postStatus').on('change', function () {
    if ($(this).val() === 'scheduled') $('#scheduleField').slideDown(200);
    else $('#scheduleField').slideUp(200);
  });

  /* ═══════════════════════════════════════
     16. SCHEMA PILLS
  ═══════════════════════════════════════ */
  $(document).on('click', '.schema-pill', function () {
    $('.schema-pill').removeClass('active');
    $(this).addClass('active');
  });

  /* ═══════════════════════════════════════
     17. ADD CATEGORY inline
  ═══════════════════════════════════════ */
  $('#btnAddCat').on('click', function () { $('#newCatField').slideToggle(200); $('#newCatInput').focus(); });
  $('#btnSaveCat').on('click', function () {
    var name = $('#newCatInput').val().trim(); if (!name) return;
    $('#categorySelect').append($('<option>').val('new_' + Date.now()).text(name).prop('selected', true));
    $('#newCatInput').val('');
    $('#newCatField').slideUp(200);
  });

  /* ═══════════════════════════════════════
     18. DELETE POST confirmation modal
  ═══════════════════════════════════════ */
//   $(document).on('click', '.btn-delete-blog', function (e) {
//     e.preventDefault();
//     var title  = $(this).data('title');
//     var action = $(this).attr('href');
//     $('#deleteModalTitle').text(title);
//     $('#deleteForm').attr('action', action);
//     $('#deleteModal').fadeIn(200);
//   });

//   $('#deleteCancelBtn').on('click', function () { $('#deleteModal').fadeOut(200); });
//   $('#deleteModal').on('click', function (e) {
//     if ($(e.target).is('#deleteModal')) $(this).fadeOut(200);
//   });

  /* ═══════════════════════════════════════
     19. LIVE SERP + Social on load
  ═══════════════════════════════════════ */
  updateSerpPreview();
  updateSocialPreview();

});
</script>

@if(session('success'))
    <script>
    $(function() { showToast(`{!! session('success') !!}`, '#00c896', 'fas fa-check-circle'); });
    </script>
@endif

@if(session('error'))
    <script>
    $(function() { showToast(`{!! session('error') !!}`, '#ff4d6d', 'fas fa-times-circle'); });
    </script>
@endif

@if ($errors->any())
    <script>
        $(function() {
            @foreach ($errors->all() as $error)
            showToast(`{{ $error }}`, '#ff4d6d', 'fas fa-exclamation-circle');
            @endforeach
        });
    </script>
@endif

@endpush