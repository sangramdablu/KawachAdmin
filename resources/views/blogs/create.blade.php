@extends('layouts.master')
@section('content')

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
          <span>New Post</span>
        </div>
        <div class="be-title-h1">✍️ Create New Blog Post</div>
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
        <i class="fas fa-rocket"></i> Publish
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
  </div>

  <form id="blogForm" method="POST" action="{{ route('blogs.store') }}" enctype="multipart/form-data">
    @csrf

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
                <input type="text" name="title" id="blogTitle" class="be-input is-title" placeholder="Enter your compelling blog title…" maxlength="70" autocomplete="off" required value="{{ old('title') }}" />
                <span class="char-counter" id="titleCounter">0/70</span>
              </div>
              <div class="be-input-hint">
                <i class="fas fa-info-circle"></i>
                Ideal length: <strong>50–60 characters</strong> for best SEO display. Avoid clickbait.
              </div>
            </div>

            {{-- URL Slug --}}
            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-link" style="color:var(--primary);font-size:.8rem;"></i> URL Slug
                <span class="lbl-badge lbl-required">Required</span>
              </label>
              <div class="slug-row">
                <span class="slug-prefix" id="slugPrefix">yourdomain.com/blog/</span>
                <input type="text" name="slug" id="blogSlug" class="slug-input" placeholder="your-post-slug" autocomplete="off" value="{{ old('slug') }}" />
                <button type="button" class="btn-icon" id="btnRegenerateSlug" title="Re-generate slug">
                  <i class="fas fa-sync-alt"></i>
                </button>
                <button type="button" class="btn-icon" id="btnCopySlug" title="Copy slug">
                  <i class="fas fa-copy"></i>
                </button>
              </div>
              <div class="be-input-hint">
                <i class="fas fa-info-circle"></i>
                Use lowercase letters, numbers and hyphens only. Keep it short and keyword-rich.
              </div>
            </div>
          </div>
        </div>

        {{-- ── RICH TEXT CONTENT ── --}}
        <div class="be-card" style="margin-top:18px;">
          <div class="be-card-header">
            <h2><i class="fas fa-pen-nib"></i> Content</h2>
            {{-- <button type="button" class="btn-be btn-outline btn-sm" id="btnFullscreen">
              <i class="fas fa-expand"></i> Fullscreen
            </button> --}}
          </div>
          <div class="be-card-body" style="padding:0;">
            <div class="ql-wrapper">
              <div id="quillEditor"></div>
            </div>
            {{-- Hidden textarea to submit Quill HTML --}}
            <textarea name="content" id="blogContent" style="display:none;">{{ old('content') }}</textarea>
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
                <textarea name="excerpt" id="blogExcerpt" class="be-textarea" placeholder="Write a brief summary of this post (shown on blog listings and in meta description if no meta is set)…" maxlength="300" rows="4" >{{ old('excerpt') }}</textarea>
                <span class="char-counter" id="excerptCounter">0/300</span>
              </div>
              <div class="be-input-hint">
                <i class="fas fa-info-circle"></i>
                Keep it under 160 characters if used as meta description. Summarise the value clearly.
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
            <div class="img-upload-zone" id="featuredImgZone">
              <input type="file" name="featured_image" id="featuredImgInput" accept="image/jpeg,image/png,image/webp,image/gif"/>
              <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
              <div class="upload-text">
                <strong>Click to upload</strong> or drag &amp; drop<br>
                <span style="font-size:.72rem;">PNG, JPG, WebP — max 5 MB · Recommended: 1200 × 630 px</span>
              </div>
            </div>

            <div class="img-preview-wrap" id="featuredPreview">
              <img src="" alt="" id="featuredPreviewImg"/>
              <div class="img-preview-actions">
                <button type="button" class="btn-be btn-outline btn-sm" id="btnChangeFeatured">
                  <i class="fas fa-exchange-alt"></i> Change
                </button>
                <button type="button" class="btn-be btn-danger-outline btn-sm" id="btnRemoveFeatured">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>

            {{-- Image SEO fields --}}
            <div class="be-form-group" style="margin-top:16px;">
              <label class="be-label">
                <i class="fas fa-tag" style="color:var(--primary);font-size:.8rem;"></i> Image Alt Text
                <span class="lbl-badge lbl-seo">SEO</span>
              </label>
              <input type="text" name="image_alt" id="imageAlt" class="be-input" placeholder="Describe the image for screen readers and search engines…" maxlength="125" value="{{ old('image_alt') }}" />
              <div class="be-input-hint">
                <i class="fas fa-info-circle"></i>
                Include your focus keyword naturally. Critical for accessibility &amp; image SEO.
              </div>
            </div>

            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-heading" style="color:var(--primary);font-size:.8rem;"></i> Image Title
                <span class="lbl-badge lbl-optional">Optional</span>
              </label>
              <input type="text" name="image_title" id="imageTitle" class="be-input" placeholder="Tooltip text shown on mouse-over…" maxlength="125" value="{{ old('image_title') }}" />
            </div>

            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-file-image" style="color:var(--primary);font-size:.8rem;"></i> Image Caption
                <span class="lbl-badge lbl-optional">Optional</span>
              </label>
              <input type="text" name="image_caption" id="imageCaption" class="be-input" placeholder="Caption displayed below the image…" value="{{ old('image_caption') }}" />
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

            {{-- Focus Keyword --}}
            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-key" style="color:var(--primary);font-size:.8rem;"></i> Focus Keyword
                <span class="lbl-badge lbl-seo">SEO</span>
              </label>
              <div style="position:relative;">
                <input type="text" name="focus_keyword" id="focusKeyword" class="be-input" placeholder="e.g. best ai tools for developers" value="{{ old('focus_keyword') }}" />
                <span class="kw-density" id="kwDensityBadge" style="display:none;position:absolute;right:8px;top:50%;transform:translateY(-50%);"></span>
              </div>
              <div class="be-input-hint">
                <i class="fas fa-info-circle"></i>
                Choose one primary keyword phrase. Aim for 1–2% density in content.
              </div>
            </div>

            {{-- Meta Title --}}
            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-heading" style="color:var(--primary);font-size:.8rem;"></i> Meta Title
                <span class="lbl-badge lbl-seo">SEO</span>
              </label>
              <div class="input-with-counter">
                <input type="text" name="meta_title" id="metaTitle" class="be-input" placeholder="Leave blank to use post title | max 60 chars" maxlength="70" value="{{ old('meta_title') }}" />
                <span class="char-counter" id="metaTitleCounter">0/60</span>
              </div>
              <div class="meter-bar"><div class="meter-fill" id="metaTitleMeter" style="width:0%;"></div></div>
            </div>

            {{-- Meta Description --}}
            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-align-left" style="color:var(--primary);font-size:.8rem;"></i> Meta Description
                <span class="lbl-badge lbl-seo">SEO</span>
              </label>
              <div class="input-with-counter">
                <textarea name="meta_description" id="metaDescription" class="be-textarea" placeholder="Compelling description shown in Google results… include your focus keyword." maxlength="170" rows="3" >{{ old('meta_description') }}</textarea>
                <span class="char-counter" id="metaDescCounter">0/160</span>
              </div>
              <div class="meter-bar"><div class="meter-fill" id="metaDescMeter" style="width:0%;"></div></div>
            </div>

            {{-- SERP Preview --}}
            <div class="be-form-group">
              <div class="seo-preview">
                <div class="seo-preview-label"><i class="fas fa-google"></i> Google SERP Preview</div>
                <div class="seo-url" id="serpUrl">yourdomain.com › blog › your-slug</div>
                <a class="seo-title-prev" id="serpTitle">Your post title will appear here…</a>
                <div class="seo-desc-prev" id="serpDesc">Your meta description will appear here. Write a compelling summary that encourages clicks from search results.</div>
              </div>
            </div>

            {{-- Meta Keywords --}}
            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-tags" style="color:var(--primary);font-size:.8rem;"></i> Meta Keywords
                <span class="lbl-badge lbl-optional">Optional</span>
              </label>
              <div class="tags-wrap" id="metaKeywordsWrap">
                <input class="tags-input" id="metaKeywordsInput" placeholder="Type keyword and press Enter or comma…" />
              </div>
              <input type="hidden" name="meta_keywords" id="metaKeywordsHidden" value="{{ old('meta_keywords') }}"/>
              <div class="be-input-hint">
                <i class="fas fa-info-circle"></i>
                Secondary keywords — ignored by Google but useful for internal search.
              </div>
            </div>

            {{-- Canonical URL --}}
            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-link" style="color:var(--primary);font-size:.8rem;"></i> Canonical URL
                <span class="lbl-badge lbl-optional">Optional</span>
              </label>
              <input type="url" name="canonical_url" id="canonicalUrl" class="be-input" placeholder="https://yourdomain.com/blog/your-slug (leave blank for auto)" value="{{ old('canonical_url') }}" />
              <div class="be-input-hint">
                <i class="fas fa-info-circle"></i>
                Use when syndicating content to avoid duplicate content penalties.
              </div>
            </div>

            {{-- Robots --}}
            <div class="be-form-group">
              <label class="be-label">
                <i class="fas fa-robot" style="color:var(--primary);font-size:.8rem;"></i> Robots Directive
                <span class="lbl-badge lbl-seo">SEO</span>
              </label>
              <select name="robots" id="robotsSelect" class="be-select">
                <option value="index, follow" {{ old('robots','index, follow') == 'index, follow' ? 'selected':'' }}>index, follow (Default — let Google crawl &amp; index)</option>
                <option value="noindex, follow" {{ old('robots') == 'noindex, follow' ? 'selected':'' }}>noindex, follow (Block indexing, follow links)</option>
                <option value="index, nofollow" {{ old('robots') == 'index, nofollow' ? 'selected':'' }}>index, nofollow (Index but don't follow links)</option>
                <option value="noindex, nofollow" {{ old('robots') == 'noindex, nofollow' ? 'selected':'' }}>noindex, nofollow (Block everything)</option>
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

            {{-- Social preview tabs --}}
            <div class="social-preview-tabs">
              <button type="button" class="sp-tab active" data-tab="facebook">
                <i class="fab fa-facebook-f"></i> Facebook
              </button>
              <button type="button" class="sp-tab" data-tab="twitter">
                <i class="fab fa-twitter"></i> Twitter
              </button>
              <button type="button" class="sp-tab" data-tab="linkedin">
                <i class="fab fa-linkedin-in"></i> LinkedIn
              </button>
            </div>
            <div class="social-preview-card">
              <div class="sp-img" id="spImgWrap">
                <i class="fas fa-image"></i>
                <img src="" alt="" id="spPreviewImg" style="display:none;"/>
              </div>
              <div class="sp-info">
                <div class="sp-site" id="spSite">YOURDOMAIN.COM</div>
                <div class="sp-title" id="spTitle">Your post title will appear here…</div>
                <div class="sp-desc" id="spDesc">Your meta description will be used here for social sharing.</div>
              </div>
            </div>

            <div class="be-form-group" style="margin-top:16px;">
              <label class="be-label"><i class="fas fa-heading" style="color:var(--primary);font-size:.8rem;"></i> OG Title <span class="lbl-badge lbl-optional">Optional</span></label>
              <input type="text" name="og_title" id="ogTitle" class="be-input" placeholder="Custom title for social sharing (leave blank to use Meta Title)" value="{{ old('og_title') }}"/>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-align-left" style="color:var(--primary);font-size:.8rem;"></i> OG Description <span class="lbl-badge lbl-optional">Optional</span></label>
              <textarea name="og_description" id="ogDescription" class="be-textarea" rows="2" placeholder="Custom description for social sharing…">{{ old('og_description') }}</textarea>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-image" style="color:var(--primary);font-size:.8rem;"></i> OG Image <span class="lbl-badge lbl-optional">Optional</span></label>
              <div class="img-upload-zone" style="padding:18px 20px;">
                <input type="file" name="og_image" id="ogImageInput" accept="image/*"/>
                <div class="upload-icon" style="font-size:1.4rem;"><i class="fas fa-cloud-upload-alt"></i></div>
                <div class="upload-text">Upload OG Image · Recommended: <strong>1200 × 630 px</strong></div>
              </div>
            </div>

            {{-- Twitter Card --}}
            <div class="be-form-group">
              <label class="be-label"><i class="fab fa-twitter" style="color:#1da1f2;font-size:.8rem;"></i> Twitter Card Type</label>
              <select name="twitter_card" class="be-select">
                <option value="summary_large_image">summary_large_image (Large image card — recommended)</option>
                <option value="summary">summary (Small square image)</option>
                <option value="app">app</option>
              </select>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fab fa-twitter" style="color:#1da1f2;font-size:.8rem;"></i> Twitter @username <span class="lbl-badge lbl-optional">Optional</span></label>
              <input type="text" name="twitter_creator" class="be-input" placeholder="@yourhandle" value="{{ old('twitter_creator') }}"/>
            </div>
          </div>
        </div>

        {{-- ── SCHEMA MARKUP ── --}}
        <div class="be-card" style="margin-top:18px;">
          <div class="be-card-header">
            <h2><i class="fas fa-code"></i> Schema / Structured Data</h2>
          </div>
          <div class="be-card-body">
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-layer-group" style="color:var(--primary);font-size:.8rem;"></i> Schema Type <span class="lbl-badge lbl-seo">SEO</span></label>
              <div class="schema-pills">
                <label class="schema-pill active">
                  <input type="radio" name="schema_type" value="Article" checked style="display:none;"/>
                  <i class="fas fa-newspaper"></i> Article
                </label>
                <label class="schema-pill">
                  <input type="radio" name="schema_type" value="BlogPosting" style="display:none;"/>
                  <i class="fas fa-blog"></i> BlogPosting
                </label>
                <label class="schema-pill">
                  <input type="radio" name="schema_type" value="HowTo" style="display:none;"/>
                  <i class="fas fa-list-ol"></i> HowTo
                </label>
                <label class="schema-pill">
                  <input type="radio" name="schema_type" value="FAQPage" style="display:none;"/>
                  <i class="fas fa-question-circle"></i> FAQPage
                </label>
                <label class="schema-pill">
                  <input type="radio" name="schema_type" value="Review" style="display:none;"/>
                  <i class="fas fa-star"></i> Review
                </label>
                <label class="schema-pill">
                  <input type="radio" name="schema_type" value="NewsArticle" style="display:none;"/>
                  <i class="fas fa-newspaper"></i> NewsArticle
                </label>
              </div>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-user" style="color:var(--primary);font-size:.8rem;"></i> Author Name</label>
              <input type="text" name="schema_author" class="be-input" placeholder="Author full name" value="{{ old('schema_author', auth()->user()->name ?? '') }}"/>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-star" style="color:var(--primary);font-size:.8rem;"></i> Rating (if Review) <span class="lbl-badge lbl-optional">Optional</span></label>
              <div style="display:flex;gap:8px;">
                <input type="number" name="schema_rating_value" class="be-input" placeholder="Rating (1-5)" min="1" max="5" step=".1" value="{{ old('schema_rating_value') }}"/>
                <input type="number" name="schema_rating_count" class="be-input" placeholder="Review count" min="0" value="{{ old('schema_rating_count') }}"/>
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
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-globe" style="color:var(--primary);font-size:.8rem;"></i> Hreflang / Language</label>
              <select name="hreflang" class="be-select">
                <option value="en">English (en)</option>
                <option value="en-us">English US (en-us)</option>
                <option value="en-gb">English UK (en-gb)</option>
                <option value="es">Spanish (es)</option>
                <option value="fr">French (fr)</option>
                <option value="de">German (de)</option>
                <option value="hi">Hindi (hi)</option>
                <option value="ar">Arabic (ar)</option>
              </select>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-sitemap" style="color:var(--primary);font-size:.8rem;"></i> Sitemap Priority</label>
              <select name="sitemap_priority" class="be-select">
                <option value="1.0">1.0 — Highest (Homepage level)</option>
                <option value="0.9" selected>0.9 — Very High</option>
                <option value="0.8">0.8 — High</option>
                <option value="0.7">0.7 — Medium-High</option>
                <option value="0.5">0.5 — Medium (Default)</option>
                <option value="0.3">0.3 — Low</option>
              </select>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-sync" style="color:var(--primary);font-size:.8rem;"></i> Sitemap Change Frequency</label>
              <select name="sitemap_changefreq" class="be-select">
                <option value="always">always</option>
                <option value="hourly">hourly</option>
                <option value="daily" selected>daily</option>
                <option value="weekly">weekly</option>
                <option value="monthly">monthly</option>
                <option value="yearly">yearly</option>
                <option value="never">never</option>
              </select>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-code" style="color:var(--primary);font-size:.8rem;"></i> Custom Head Scripts <span class="lbl-badge lbl-optional">Optional</span></label>
              <textarea name="custom_head_scripts" class="be-textarea" rows="3" placeholder="&lt;script&gt; or &lt;style&gt; injected into &lt;head&gt; for this post only…" style="font-family:monospace;font-size:.8rem;"></textarea>
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
                <strong id="seoScoreText">Start writing to see your score</strong>
                <span id="seoScoreSubtext">Fill in title, content &amp; meta fields</span>
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

            {{-- Visibility --}}
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-eye" style="color:var(--primary);font-size:.8rem;"></i> Visibility</label>
              <div class="vis-pills">
                <label class="vis-pill active">
                  <input type="radio" name="visibility" value="public" checked style="display:none;"/>
                  <i class="fas fa-globe"></i> Public
                </label>
                <label class="vis-pill">
                  <input type="radio" name="visibility" value="private" style="display:none;"/>
                  <i class="fas fa-lock"></i> Private
                </label>
                <label class="vis-pill">
                  <input type="radio" name="visibility" value="password" style="display:none;"/>
                  <i class="fas fa-key"></i> Password
                </label>
              </div>
            </div>

            {{-- Password field (hidden by default) --}}
            <div class="be-form-group" id="passwordField" style="display:none;">
              <label class="be-label"><i class="fas fa-key" style="color:var(--primary);font-size:.8rem;"></i> Post Password</label>
              <input type="password" name="post_password" class="be-input" placeholder="Enter password…"/>
            </div>

            {{-- Status --}}
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-flag" style="color:var(--primary);font-size:.8rem;"></i> Status</label>
              <select name="status" id="postStatus" class="be-select">
                <option value="draft" {{ old('status','draft') == 'draft' ? 'selected':'' }}>Draft</option>
                <option value="pending" {{ old('status') == 'pending' ? 'selected':'' }}>Pending Review</option>
                <option value="published" {{ old('status') == 'published' ? 'selected':'' }}>Published</option>
                <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected':'' }}>Scheduled</option>
              </select>
            </div>

            {{-- Schedule datetime --}}
            <div class="be-form-group" id="scheduleField" style="display:none;">
              <label class="be-label"><i class="fas fa-calendar-alt" style="color:var(--primary);font-size:.8rem;"></i> Publish Date &amp; Time</label>
              <input type="datetime-local" name="published_at" class="be-input" value="{{ old('published_at') }}"/>
            </div>

            {{-- Allow comments --}}
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-comments" style="color:var(--primary);font-size:.8rem;"></i> Comments</label>
              <select name="allow_comments" class="be-select">
                <option value="1">Allow comments</option>
                <option value="0">Disable comments</option>
              </select>
            </div>

            {{-- Reading time override --}}
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-clock" style="color:var(--primary);font-size:.8rem;"></i> Reading Time Override <span class="lbl-badge lbl-optional">Optional</span></label>
              <input type="text" name="reading_time" id="readingTimeField" class="be-input" placeholder="e.g. 5 min read" value="{{ old('reading_time') }}"/>
            </div>

            <div style="padding-top:8px;display:flex;gap:8px;flex-direction:column;">
              <button type="button" class="btn-be btn-success" id="sidebarPublish">
                <i class="fas fa-rocket"></i> Publish Now
              </button>
              <button type="button" class="btn-be btn-outline" id="sidebarDraft">
                <i class="fas fa-save"></i> Save Draft
              </button>
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
                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected':'' }}>{{ $cat->name }}</option>
              @endforeach
              @if(empty($categories))
                <option value="1">Technology</option>
                <option value="2">AI &amp; Machine Learning</option>
                <option value="3">Web Development</option>
                <option value="4">Cloud &amp; DevOps</option>
                <option value="5">Business</option>
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
              <input type="hidden" name="tags" id="postTagsHidden" value="{{ old('tags') }}"/>
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
              <option value="{{ auth()->id() ?? 1 }}">{{ auth()->user()->name ?? 'Admin User' }}</option>
              {{-- @foreach($authors ?? [] as $author)
                <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected':'' }}>{{ $author->name }}</option>
              @endforeach --}}
            </select>
          </div>
        </div>

      </div>{{-- end right --}}
    </div>{{-- end grid --}}
  </form>

</div>{{-- /be-wrap --}}
</div>{{-- /blogEditor --}}

@endsection


@push('scripts')
{{-- ── Quill rich text editor CDN ── --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
{{-- ── jQuery (if not already loaded in master) ── --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
let draftKey = sessionStorage.getItem('current_draft_key');
if (!draftKey) {
    draftKey = 'blog_draft_' + Date.now();
    sessionStorage.setItem('current_draft_key', draftKey);
}
$(function () {
    let draftId = null;

  /* ══════════════ 1. QUILL RICH TEXT EDITOR ════════════════ */
  var quill = new Quill('#quillEditor', {
    theme: 'snow',
    placeholder: 'Start writing your amazing blog post here… Use headings (H2, H3), bullet points, quotes and links to make it both readable and SEO-friendly.',
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

  // Pre-fill if old content exists
  var existingContent = $('#blogContent').val();
  if (existingContent) { quill.clipboard.dangerouslyPasteHTML(existingContent); }

  // Sync Quill → hidden textarea + update stats
  quill.on('text-change', function () {
    var html = quill.root.innerHTML;
    $('#blogContent').val(html);
    updateContentStats(html);
    updateSeoScore();
  });

  function updateContentStats(html) {
    var $temp = $('<div>').html(html);
    var text  = $temp.text().trim();
    var words = text ? text.split(/\s+/).filter(Boolean).length : 0;
    var readMinutes = Math.max(1, Math.ceil(words / 200));
    var headings = $temp.find('h1,h2,h3,h4,h5,h6').length;
    var links    = $temp.find('a').length;
    var imgs     = $temp.find('img').length;
    var paras    = $temp.find('p').length;

    $('#wordCount').text(words);
    $('#readTime').text(readMinutes + ' min');
    $('#headingCount').text(headings);
    $('#linkCount').text(links);
    $('#imgCount').text(imgs);
    $('#paraCount').text(paras);
    $('#readingTimeField').val(readMinutes + ' min read');
  }

  /* ═══════════════ AUTO SAVE (LOCAL STORAGE) ═════════════ */
    function getDraftKey() {
        return draftKey;
    }

    function saveToLocal() {
        const data = {
            title: $('#blogTitle').val(),
            slug: $('#blogSlug').val(),
            content: quill.root.innerHTML,
            excerpt: $('#blogExcerpt').val(),
            meta_title: $('#metaTitle').val(),
            meta_description: $('#metaDescription').val(),
            focus_keyword: $('#focusKeyword').val(),
            image_alt: $('#imageAlt').val(),
            timestamp: new Date().toISOString()
        };

        localStorage.setItem(getDraftKey(), JSON.stringify(data));
    }

    // Save every 5 seconds
    setInterval(saveToLocal, 5000);

    // Also save on typing
    $('#blogTitle, #blogExcerpt, #metaTitle, #metaDescription, #focusKeyword').on('input', saveToLocal);
    quill.on('text-change', saveToLocal);

    function loadFromLocal() {
        const saved = localStorage.getItem(getDraftKey());
        if (!saved) return;

        try {
            const data = JSON.parse(saved);

            if (data.title) $('#blogTitle').val(data.title);
            if (data.slug) $('#blogSlug').val(data.slug);
            if (data.excerpt) $('#blogExcerpt').val(data.excerpt);
            if (data.meta_title) $('#metaTitle').val(data.meta_title);
            if (data.meta_description) $('#metaDescription').val(data.meta_description);
            if (data.focus_keyword) $('#focusKeyword').val(data.focus_keyword);
            if (data.image_alt) $('#imageAlt').val(data.image_alt);

            if (data.content) {
                quill.root.innerHTML = data.content;
            }

            console.log('Draft restored from local storage');
        } catch (e) {
            console.error('Error loading draft', e);
        }
    }

    // Call on load
    loadFromLocal();

    function autoSaveToServer() {
        let title = $('#blogTitle').val().trim();
        let content = quill.getText().trim();

        if (!title && !content) {
            return;
        }

        $.ajax({
            url: "{{ route('blogs.fhy6adv645gv5zd5') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                draft_id: draftId,
                title: title,
                slug: $('#blogSlug').val(),
                content: quill.root.innerHTML,
                excerpt: $('#blogExcerpt').val(),
            },
            success: function(res) {
                if (res.draft_id) draftId = res.draft_id;
                console.log("Draft saved");
            },
            error: function(err) {
                console.log("Autosave error", err.responseText);
            }
        });
    }
    // Save every 15 seconds
    setInterval(autoSaveToServer, 15000);
  /* ═══════════════════════════════════════
     2. TITLE → slug auto-generate + counter
  ═══════════════════════════════════════ */
  function slugify(str) {
    return str.toLowerCase()
      .replace(/[^a-z0-9\s-]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-')
      .replace(/^-|-$/g, '');
  }

  $('#blogTitle').on('input', function () {
    var val = $(this).val();
    var len = val.length;
    var $c  = $('#titleCounter');
    $c.text(len + '/70');
    $c.removeClass('warn over good');
    if (len >= 50 && len <= 60) $c.addClass('good');
    else if (len > 60)           $c.addClass('warn');
    if (len >= 68)               $c.removeClass('warn').addClass('over');

    // Auto-slug only if user hasn't manually edited it
    if (!$('#blogSlug').data('manual')) {
      $('#blogSlug').val(slugify(val));
    }
    updateSerpPreview();
    updateSeoScore();
  });

  $('#blogSlug').on('input', function () {
    $(this).data('manual', true);
    var clean = slugify($(this).val());
    $(this).val(clean);
    updateSerpPreview();
    updateSeoScore();
  });

  $('#btnRegenerateSlug').on('click', function () {
    $('#blogSlug').data('manual', false).val(slugify($('#blogTitle').val()));
    updateSerpPreview();
    updateSeoScore();
  });

  $('#btnCopySlug').on('click', function () {
    var slug = $('#slugPrefix').text() + $('#blogSlug').val();
    navigator.clipboard.writeText(slug).catch(function(){});
    var $btn = $(this).find('i');
    $btn.removeClass('fa-copy').addClass('fa-check');
    setTimeout(function(){ $btn.removeClass('fa-check').addClass('fa-copy'); }, 1500);
  });

  /* ═══════════════════════════════════════
     3. EXCERPT counter
  ═══════════════════════════════════════ */
  $('#blogExcerpt').on('input', function () {
    var len = $(this).val().length;
    var $c  = $('#excerptCounter');
    $c.text(len + '/300');
    $c.removeClass('warn over good');
    if (len >= 120 && len <= 160) $c.addClass('good');
    else if (len > 160 && len <= 300) $c.addClass('warn');
    if (len > 295) $c.addClass('over');
    updateSeoScore();
  });

  /* ═══════════════════════════════════════
     4. META TITLE counter + meter
  ═══════════════════════════════════════ */
  $('#metaTitle').on('input', function () {
    var len = $(this).val().length;
    var $c  = $('#metaTitleCounter');
    $c.text(len + '/60');
    $c.removeClass('warn over good');
    var pct = Math.min(100, (len / 60) * 100);
    var color = '#e2e8f0';
    if (len >= 30 && len <= 60) { color = '#00c896'; $c.addClass('good'); }
    else if (len > 60)          { color = '#ff4d6d'; $c.addClass('over'); }
    else if (len > 0)           { color = '#ffb830'; $c.addClass('warn'); }
    $('#metaTitleMeter').css({ width: pct + '%', background: color });
    updateSerpPreview();
    updateSocialPreview();
    updateSeoScore();
  });

  /* ═══════════════════════════════════════
     5. META DESCRIPTION counter + meter
  ═══════════════════════════════════════ */
  $('#metaDescription').on('input', function () {
    var len = $(this).val().length;
    var $c  = $('#metaDescCounter');
    $c.text(len + '/160');
    $c.removeClass('warn over good');
    var pct = Math.min(100, (len / 160) * 100);
    var color = '#e2e8f0';
    if (len >= 120 && len <= 160) { color = '#00c896'; $c.addClass('good'); }
    else if (len > 160)           { color = '#ff4d6d'; $c.addClass('over'); }
    else if (len > 0)             { color = '#ffb830'; $c.addClass('warn'); }
    $('#metaDescMeter').css({ width: pct + '%', background: color });
    updateSerpPreview();
    updateSocialPreview();
    updateSeoScore();
  });

  /* ═══════════════════════════════════════
     6. SERP PREVIEW
  ═══════════════════════════════════════ */
  function updateSerpPreview() {
    var slug     = $('#blogSlug').val() || 'your-slug';
    var title    = $('#metaTitle').val() || $('#blogTitle').val() || 'Your post title will appear here…';
    var desc     = $('#metaDescription').val() || $('#blogExcerpt').val() || 'Your meta description will appear here. Write a compelling summary that encourages clicks from search results.';
    var domain   = window.location.hostname || 'yourdomain.com';
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
    var title = $('#ogTitle').val() || $('#metaTitle').val() || $('#blogTitle').val() || 'Your post title…';
    var desc  = $('#ogDescription').val() || $('#metaDescription').val() || $('#blogExcerpt').val() || 'Description appears here.';
    var tab   = $('.sp-tab.active').data('tab');
    var domain = window.location.hostname || 'yourdomain.com';
    $('#spSite').text(domain.toUpperCase() + (tab === 'twitter' ? ' ON TWITTER' : ''));
    $('#spTitle').text(title.substring(0, 88));
    $('#spDesc').text(desc.substring(0, 120));
  }

  $('#ogTitle, #ogDescription').on('input', updateSocialPreview);

  /* ═══════════════════════════════════════
     8. FEATURED IMAGE upload + preview
  ═══════════════════════════════════════ */
  $('#featuredImgInput').on('change', function () {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function (e) {
      $('#featuredPreviewImg').attr('src', e.target.result);
      $('#featuredImgZone').hide();
      $('#featuredPreview').show();
      // Also set OG image preview
      $('#spPreviewImg').attr('src', e.target.result).show();
      $('#spImgWrap i').hide();
      updateSeoScore();
    };
    reader.readAsDataURL(file);
  });

  $('#btnRemoveFeatured').on('click', function () {
    $('#featuredImgInput').val('');
    $('#featuredPreviewImg').attr('src', '');
    $('#featuredImgZone').show();
    $('#featuredPreview').hide();
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
    e.preventDefault();
    $(this).removeClass('drag-over');
    var files = e.originalEvent.dataTransfer.files;
    if (files.length) {
      $('#featuredImgInput')[0].files = files;
      $('#featuredImgInput').trigger('change');
    }
  });

  // OG image preview
  $('#ogImageInput').on('change', function () {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function (e) {
      $('#spPreviewImg').attr('src', e.target.result).show();
      $('#spImgWrap i').hide();
    };
    reader.readAsDataURL(file);
  });

  /* ═══════════════════════════════════════
     9. IMAGE ALT + TITLE triggers SEO score
  ═══════════════════════════════════════ */
  $('#imageAlt').on('input', updateSeoScore);

  /* ═══════════════════════════════════════
     10. TAGS INPUT (meta keywords & post tags)
  ═══════════════════════════════════════ */
  function initTagsInput(wrapId, inputId, hiddenId) {
    var tags = [];
    var $wrap = $('#' + wrapId);
    var $inp  = $('#' + inputId);
    var $hid  = $('#' + hiddenId);

    // Pre-fill from hidden
    var existing = $hid.val();
    if (existing) {
      existing.split(',').forEach(function(t){ if(t.trim()) addTag(t.trim()); });
    }

    $wrap.on('click', function(){ $inp.focus(); });

    $inp.on('keydown', function(e) {
      if ((e.key === 'Enter' || e.key === ',') && $(this).val().trim()) {
        e.preventDefault();
        addTag($(this).val().trim().replace(/,/g,''));
        $(this).val('');
      }
      if (e.key === 'Backspace' && !$(this).val() && tags.length) {
        removeTag(tags[tags.length - 1]);
      }
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

    function syncHidden() {
      $hid.val(tags.join(','));
    }

    return { addTag: addTag };
  }

  var metaKwHandler = initTagsInput('metaKeywordsWrap', 'metaKeywordsInput', 'metaKeywordsHidden');
  var postTagHandler = initTagsInput('postTagsWrap', 'postTagsInput', 'postTagsHidden');

  // Suggested tags click
  $(document).on('click', '.suggested-tag', function () {
    postTagHandler.addTag($(this).data('tag'));
  });

  /* ═══════════════════════════════════════
     11. FOCUS KEYWORD density
  ═══════════════════════════════════════ */
  $('#focusKeyword').on('input', function () {
    updateSeoScore();
    var kw = $(this).val().trim().toLowerCase();
    if (!kw) { $('#kwDensityBadge').hide(); return; }
    var text  = quill.getText().toLowerCase();
    var words = text.split(/\s+/).filter(Boolean).length;
    var count = (text.match(new RegExp(kw, 'g')) || []).length;
    var density = words > 0 ? ((count / words) * 100).toFixed(1) : 0;
    var $badge = $('#kwDensityBadge');
    $badge.show();
    var bg = '#e8f1fd', color = '#1a73e8';
    if (density >= 1 && density <= 3) { bg = '#d4f5ec'; color = '#00a87c'; }
    else if (density > 3) { bg = '#ffe2e8'; color = '#ff4d6d'; }
    $badge.text(density + '%').css({ background: bg, color: color });
  });

  /* ═══════════════════════════════════════
     12. SEO SCORE calculator
  ═══════════════════════════════════════ */
  function updateSeoScore() {
    var score = 0;
    var kw    = $('#focusKeyword').val().trim().toLowerCase();
    var title = $('#blogTitle').val().toLowerCase();
    var slug  = $('#blogSlug').val().toLowerCase();
    var metaDesc = $('#metaDescription').val();
    var metaTitle = $('#metaTitle').val();
    var imgAlt   = $('#imageAlt').val().trim();
    var contentText = quill.getText().toLowerCase();
    var contentHtml = quill.root.innerHTML;
    var words = contentText.split(/\s+/).filter(Boolean).length;
    var $temp = $('<div>').html(contentHtml);
    var hasHeading = $temp.find('h2,h3').length > 0;
    var hasLink    = $temp.find('a').length > 0;
    var hasFeatImg = $('#featuredPreview').is(':visible');

    function setCheck(id, pass, warn) {
      var $li = $('#' + id);
      $li.removeClass('pass fail warn');
      var icon = pass ? 'fa-check-circle' : (warn ? 'fa-exclamation-triangle' : 'fa-times-circle');
      var cls  = pass ? 'pass' : (warn ? 'warn' : 'fail');
      $li.addClass(cls).find('i').removeClass('fa-check-circle fa-times-circle fa-exclamation-triangle').addClass(icon);
    }

    // 1. Keyword in title
    var ck1 = kw && title.includes(kw);
    setCheck('ck-title', ck1, false);
    if (ck1) score += 10;

    // 2. Keyword in slug
    var ck2 = kw && slug.includes(kw.replace(/\s+/g,'-'));
    setCheck('ck-slug', ck2, false);
    if (ck2) score += 8;

    // 3. Meta description set
    var mdLen = metaDesc.length;
    var ck3 = mdLen >= 120 && mdLen <= 160;
    var ck3w = mdLen > 0 && !ck3;
    setCheck('ck-metadesc', ck3, ck3w);
    if (ck3) score += 10; else if (ck3w) score += 4;

    // 4. Keyword in meta description
    var ck4 = kw && metaDesc.toLowerCase().includes(kw);
    setCheck('ck-kw-metadesc', ck4, false);
    if (ck4) score += 8;

    // 5. Content length ≥ 300
    var ck5 = words >= 300;
    var ck5w = words >= 100 && words < 300;
    setCheck('ck-content-len', ck5, ck5w);
    if (ck5) score += 15; else if (ck5w) score += 5;

    // 6. Keyword in content
    var ck6 = kw && contentText.includes(kw);
    setCheck('ck-content-kw', ck6, false);
    if (ck6) score += 10;

    // 7. Heading in content
    setCheck('ck-heading', hasHeading, false);
    if (hasHeading) score += 8;

    // 8. Featured image
    setCheck('ck-img', hasFeatImg, false);
    if (hasFeatImg) score += 8;

    // 9. Image alt
    var ck9 = imgAlt.length > 0;
    setCheck('ck-img-alt', ck9, false);
    if (ck9) score += 7;

    // 10. Link in content
    setCheck('ck-links', hasLink, false);
    if (hasLink) score += 6;

    // 11. Meta title length 30-60
    var mtLen = metaTitle.length;
    var ck11 = mtLen >= 30 && mtLen <= 60;
    var ck11w = mtLen > 0 && !ck11;
    setCheck('ck-metatitle-len', ck11, ck11w);
    if (ck11) score += 6; else if (ck11w) score += 2;

    // 12. Keyword density 1-3%
    var density = 0;
    if (kw && words > 0) {
      var kwCount = (contentText.match(new RegExp(kw.replace(/[-\/\\^$*+?.()|[\]{}]/g,'\\$&'), 'g')) || []).length;
      density = (kwCount / words) * 100;
    }
    var ck12ok = density >= 1 && density <= 3;
    var ck12w  = (density > 0 && density < 1) || density > 3;
    setCheck('ck-kw-density', ck12ok, ck12w);
    if (ck12ok) score += 4; else if (ck12w) score += 1;

    // Update ring + label
    var ringColor = score >= 80 ? '#00c896' : score >= 50 ? '#ffb830' : '#ff4d6d';
    var label = score >= 80 ? 'Excellent!' : score >= 60 ? 'Good' : score >= 40 ? 'Needs Work' : 'Poor';
    var sub   = score >= 80 ? 'Your post is well-optimised' : score >= 60 ? 'A few improvements needed' : 'Fill in key SEO fields';
    $('#seoRing').text(score).css('background', ringColor);
    $('#seoScoreText').text(label);
    $('#seoScoreSubtext').text(sub);
    $('#seoScoreLabel').text(score);

    // Badge colour
    var badgeBg = score >= 80 ? '#d4f5ec' : score >= 60 ? '#fff4d6' : '#ffe2e8';
    var badgeColor = score >= 80 ? '#00a87c' : score >= 60 ? '#b8860b' : '#ff4d6d';
    $('#seoScoreBadge').css({ background: badgeBg, color: badgeColor });
  }

  /* ═══════════════════════════════════════
     13. PUBLISH / DRAFT buttons
  ═══════════════════════════════════════ */
    function submitForm(status) {
        localStorage.removeItem(AUTO_SAVE_KEY);
        $('#postStatus').val(status);
        var $form = $('#blogForm');
        $('#blogContent').val(quill.root.innerHTML);

        // Basic validation
        if (!$('#blogTitle').val().trim()) {
            alert('Please enter a post title before saving.');
            $('#blogTitle').focus();
            return;
        }
        if (!$('#blogSlug').val().trim()) {
            $('#blogSlug').val(slugify($('#blogTitle').val()));
        }
        $form[0].submit();
    }

    $('#btnPublish, #sidebarPublish').on('click', function () {
        $('#postStatus').val('published');
        submitForm('published');
    });

    $('#btnSaveDraft, #sidebarDraft').on('click', function () {
        $('#postStatus').val('draft');
        submitForm('draft');
    });

  // Preview
  $('#btnPreview').on('click', function () {
    var slug = $('#blogSlug').val();
    if (slug) {
      window.open('/blog/' + slug + '?preview=1', '_blank');
    } else {
      alert('Please set a slug first.');
    }
  });

  /* ═══════════════════════════════════════
     14. VISIBILITY toggle
  ═══════════════════════════════════════ */
  $(document).on('click', '.vis-pill', function () {
    $('.vis-pill').removeClass('active');
    $(this).addClass('active');
    var val = $(this).find('input').val();
    if (val === 'password') { $('#passwordField').slideDown(200); }
    else { $('#passwordField').slideUp(200); }
  });

  /* ═══════════════════════════════════════
     15. STATUS → schedule field
  ═══════════════════════════════════════ */
    $('#postStatus').on('change', function () {
        if ($(this).val() === 'scheduled') {
            $('#scheduleField').slideDown();
        } else {
            $('#scheduleField').slideUp();
        }
    });
//   $('#postStatus').on('change', function () {
//     if ($(this).val() === 'scheduled') { $('#scheduleField').slideDown(200); }
//     else { $('#scheduleField').slideUp(200); }
//   });

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
  $('#btnAddCat').on('click', function () {
    $('#newCatField').slideToggle(200);
    $('#newCatInput').focus();
  });

  $('#btnSaveCat').on('click', function () {
    var name = $('#newCatInput').val().trim();
    if (!name) return;
    var $opt = $('<option>').val('new_' + Date.now()).text(name).prop('selected', true);
    $('#categorySelect').append($opt);
    $('#newCatInput').val('');
    $('#newCatField').slideUp(200);
  });

  /* ═══════════════════════════════════════
     18. FULLSCREEN editor
  ═══════════════════════════════════════ */
//   $('#btnFullscreen').on('click', function () {
//     var $qlWrap = $('.ql-wrapper');
//     var isFs = $qlWrap.hasClass('be-fullscreen');
//     if (isFs) {
//       $qlWrap.removeClass('be-fullscreen');
//       $(this).html('<i class="fas fa-expand"></i> Fullscreen');
//     } else {
//       $qlWrap.addClass('be-fullscreen');
//       $(this).html('<i class="fas fa-compress"></i> Exit');
//     }
//   });

  /* ═══════════════════════════════════════
     19. ANALYTICS toolbar (view toggle)
  ═══════════════════════════════════════ */
  $('.analytics-toolbar button').on('click', function () {
    $('.analytics-toolbar button').removeClass('active');
    $(this).addClass('active');
  });

  /* ═══════════════════════════════════════
     20. LIVE SERP + Social on load
  ═══════════════════════════════════════ */
  updateSerpPreview();
  updateSocialPreview();

});
</script>

@if(session('success'))
    <script>
    $(function() {
        showToast(`{!! session('success') !!}`, '#00c896', 'fas fa-check-circle');
    });
    </script>
@endif

@if(session('error'))
    <script>
    $(function() {
        showToast(`{!! session('error') !!}`, '#ff4d6d', 'fas fa-times-circle');
    });
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

<style>
/* Fullscreen editor */
#blogEditor .ql-wrapper.be-fullscreen {
  position: fixed;
  inset: 0;
  z-index: 9999;
  border-radius: 0;
  border: none;
}
#blogEditor .ql-wrapper.be-fullscreen .ql-editor { min-height: calc(100vh - 60px); }

/* Highlight focus keyword in slug */
#blogEditor .slug-input:focus { outline: none; }

/* Analytics toolbar (reuse from dashboard) */
#blogEditor .analytics-toolbar { display:flex; gap:6px; }
#blogEditor .analytics-toolbar button {
  width:26px; height:26px; border:1.5px solid var(--border);
  background:#fff; border-radius:5px; display:flex; align-items:center;
  justify-content:center; cursor:pointer; color:var(--muted); font-size:.72rem; transition:all .15s;
}
#blogEditor .analytics-toolbar button.active,
#blogEditor .analytics-toolbar button:hover { border-color:var(--primary); color:var(--primary); background:#e8f1fd; }
</style>
@endpush