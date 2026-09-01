@extends('layouts.master')
@section('title', 'Edit Blogs — KawachTech Software Solutions')
@section('content')

{{-- ================== MARKUP ================= --}}
<div id="blogEditor">
<div class="be-wrap">

  <form id="blogForm" method="POST" action="{{ route('blogs.update', $blog->id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
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
      <button type="submit" name="action" value="draft" class="btn-be btn-outline js-blog-save-btn" id="topbarDraft">
        <i class="fas fa-save"></i> Save Draft
      </button>
      <button type="submit" name="action" value="publish" class="btn-be btn-success js-blog-save-btn" id="topbarPublish">
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

    <div class="be-grid">
      <input type="hidden" id="draft_id" value="{{ $blog->id }}">
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
                  <img src="{{ $blog->featured_image ? asset($blog->featured_image) : '' }}" alt="" id="spPreviewImg" style="display:block;width:100%;height:100%;object-fit:cover;"/>
                @elseif($blog->featured_image)
                  <img src="{{ $blog->featured_image ? asset($blog->featured_image) : '' }}" alt="" id="spPreviewImg" style="display:block;width:100%;height:100%;object-fit:cover;"/>
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
              <div class="be-input" style="opacity:.75;cursor:not-allowed;user-select:none;">{{ $blog->author?->name ?? auth()->user()->name ?? 'Admin User' }}</div>
              <div class="be-input-hint"><i class="fas fa-lock"></i> Set automatically from the post's author account.</div>
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
              <button type="submit" name="action" value="publish" class="btn-be btn-success js-blog-save-btn" id="sidebarPublish">
                <i class="fas fa-rocket"></i> Update &amp; Publish
              </button>
              <button type="submit" name="action" value="draft" class="btn-be btn-outline js-blog-save-btn" id="sidebarDraft">
                <i class="fas fa-save"></i> Save as Draft
              </button>
            </div>

            {{-- Danger zone --}}
            <div class="danger-zone">
              <div class="danger-zone-title"><i class="fas fa-exclamation-triangle"></i> Danger Zone</div>
              <a href="#"
                class="btn-be btn-danger-outline btn-sm btn-delete-blog"
                data-id="{{ encrypt($blog->id) }}"
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
            <div class="be-input" style="opacity:.75;cursor:not-allowed;user-select:none;display:flex;align-items:center;gap:8px;">
              <i class="fas fa-user-circle"></i> {{ $blog->author?->name ?? auth()->user()->name ?? 'Admin User' }}
            </div>
            <div class="be-input-hint"><i class="fas fa-lock"></i> Original author is preserved and cannot be reassigned by editing.</div>
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
              <div class="pml-row">
                <span class="pml-label"><i class="fas fa-heart" style="color:var(--danger);"></i> Likes</span>
                <span class="pml-value">{{ number_format($blog->likes_count ?? 0) }}</span>
              </div>
              <div class="pml-row">
                <span class="pml-label"><i class="fas fa-comment-dots" style="color:#9333ea;"></i> Comments</span>
                <span class="pml-value">
                  {{ number_format($blog->approved_comments_count ?? 0) }}
                  @if(($blog->pending_comments_count ?? 0) > 0)
                    <span class="status-badge badge-pending" style="font-size:.6rem;padding:2px 6px;margin-left:4px;">{{ $blog->pending_comments_count }} pending</span>
                  @endif
                </span>
              </div>
            </div>
            @if(($blog->pending_comments_count ?? 0) > 0)
            <a href="{{ route('blog-comments.index', ['status' => 'pending']) }}" class="btn-be btn-outline btn-sm" style="width:100%;justify-content:center;margin-top:12px;">
              <i class="fas fa-comment-dots"></i> Moderate {{ $blog->pending_comments_count }} Pending Comment{{ $blog->pending_comments_count > 1 ? 's' : '' }}
            </a>
            @endif
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
{{-- ── Modular blog editor (versioned for cache busting) ── --}}
<script src="{{ asset('assets/js/blog.js') }}?v={{ filemtime(public_path('assets/js/blog.js')) }}"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {

    const quill = new Quill('#quillEditor', {
      theme: 'snow',
      placeholder: 'Edit your blog post content here…',
      modules: {
        toolbar: [
          [{ 'header': [1, 2, 3, 4, false] }],
          ['bold', 'italic', 'underline'],
          ['link', 'image'],
        ]
      }
    });

    // preload content
    const existingContent = document.getElementById('blogContent').value;
    if (existingContent) {
      quill.clipboard.dangerouslyPasteHTML(existingContent);
    }

    // 👉 INIT YOUR MODULAR SYSTEM HERE
    new BlogEditor({
      quill: quill,
      autosaveUrl: "{{ route('blogs.fhy6adv645gv5zd5') }}",
      csrfToken: "{{ csrf_token() }}"
    });

  });
</script>

{{-- ── Delete post (Danger Zone) ──
     The button used to be a plain <a href="{{ route('blogs.destroy', ...) }}">
     which just navigated (GET) to a route that only accepts DELETE, so every
     click 405'd instead of deleting anything. It's now wired to fire the same
     AJAX DELETE the blogs list page uses, with the id encrypted (the
     destroy() controller method calls Crypt::decrypt() on it). --}}
<script>
$(function () {
  $('.btn-delete-blog').on('click', function (e) {
    e.preventDefault();
    var $btn = $(this);
    var id = $btn.data('id');
    var title = $btn.data('title');

    if (!confirm('Delete "' + title + '"? This action cannot be undone.')) {
      return;
    }

    $.ajax({
      url: '/blogs/' + id,
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function (res) {
        if (res.success) {
          window.showToast(res.message, 'var(--danger)', 'fas fa-trash');
          setTimeout(function () {
            window.location.href = '{{ route('blogs.index') }}';
          }, 600);
        } else {
          window.showToast(res.message, '#ff4d6d', 'fas fa-exclamation-circle');
        }
      },
      error: function (xhr) {
        var msg = 'Delete failed';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          msg = xhr.responseJSON.message;
        }
        window.showToast(msg, '#ff4d6d', 'fas fa-times-circle');
      }
    });
  });
});
</script>

@if(session('toast'))
<script>
$(function(){
    const toast = @json(session('toast'));

    let color = '#00c896';
    let icon  = 'fas fa-check-circle';

    if (toast.type === 'error') {
        color = '#ff4d6d';
        icon  = 'fas fa-times-circle';
    }

    if (toast.type === 'warning') {
        color = '#ffb830';
        icon  = 'fas fa-exclamation-triangle';
    }

    window.showToast(toast.message, color, icon);
});
</script>
@endif

@endpush