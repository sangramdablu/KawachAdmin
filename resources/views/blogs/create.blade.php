@extends('layouts.master')
{{-- ═══════════════════════════════════════════════════════════
     Page-level meta (injected into master <head> via @stack)
═══════════════════════════════════════════════════════════════ --}}
@section('title', 'Create Blogs — KawachTech Software Solutions')

@section('content') 
{{-- ─── MARKUP ──────────────────────────────────────────────── --}}
<div id="blogEditor">
<div class="be-wrap">
  {{-- FORM --}}
  <form id="blogForm" method="POST" action="{{ route('blogs.store') }}" enctype="multipart/form-data" novalidate>
    @csrf
  {{-- TOP BAR --}}
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
      <button type="submit" name="action" value="draft" class="btn-be btn-outline js-blog-save-btn" id="topbarDraft">
        <i class="fas fa-save"></i> Save Draft
      </button>
      <button type="submit" name="action" value="publish" class="btn-be btn-success js-blog-save-btn" id="topbarPublish">
        <i class="fas fa-rocket"></i> Publish
      </button>
    </div>
  </div>

  {{-- CONTENT STATS STRIP --}}
  <div class="stats-strip">
    <div class="stat-item"><i class="fas fa-align-left"></i> Words: <strong id="wordCount">0</strong></div>
    <div class="stat-item"><i class="fas fa-clock"></i> Read time: <strong id="readTime">0 min</strong></div>
    <div class="stat-item"><i class="fas fa-heading"></i> Headings: <strong id="headingCount">0</strong></div>
    <div class="stat-item"><i class="fas fa-link"></i> Links: <strong id="linkCount">0</strong></div>
    <div class="stat-item"><i class="fas fa-image"></i> Images: <strong id="imgCount">0</strong></div>
    <div class="stat-item"><i class="fas fa-paragraph"></i> Paragraphs: <strong id="paraCount">0</strong></div>
  </div>

    <div class="be-grid">
    <input type="hidden" name="draft_id" id="draft_id" value="">
      {{-- ══════════ LEFT COLUMN ══════════ --}}
      <div class="be-left">

        {{-- Title + Slug --}}
        <div class="be-card">
          <div class="be-card-header"><h2><i class="fas fa-heading"></i> Post Title</h2></div>
          <div class="be-card-body">
            <div class="be-form-group">
              <div class="input-with-counter">
                <input type="text" name="title" id="blogTitle" class="be-input is-title"
                  placeholder="Enter your compelling blog title…" maxlength="70"
                  autocomplete="off" required value="{{ old('title') }}"/>
                <span class="char-counter" id="titleCounter">0/70</span>
              </div>
              <div class="be-input-hint"><i class="fas fa-info-circle"></i> Ideal length: <strong>50–60 characters</strong> for best SEO display.</div>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-link" style="color:var(--primary);font-size:.8rem;"></i> URL Slug <span class="lbl-badge lbl-required">Required</span></label>
              <div class="slug-row">
                <span class="slug-prefix" id="slugPrefix">{{ request()->getSchemeAndHttpHost() }}/blog/</span>
                <input type="text" name="slug" id="blogSlug" class="slug-input"
                  placeholder="your-post-slug" autocomplete="off" value="{{ old('slug') }}"/>
                <button type="button" class="btn-icon" id="btnRegenerateSlug" title="Re-generate slug"><i class="fas fa-sync-alt"></i></button>
                <button type="button" class="btn-icon" id="btnCopySlug" title="Copy URL"><i class="fas fa-copy"></i></button>
              </div>
              <div class="be-input-hint"><i class="fas fa-info-circle"></i> Lowercase, numbers and hyphens only. Keep it short and keyword-rich.</div>
            </div>
          </div>
        </div>

        {{-- Rich Text Content --}}
        <div class="be-card" style="margin-top:18px;">
          <div class="be-card-header"><h2><i class="fas fa-pen-nib"></i> Content</h2></div>
          <div class="be-card-body" style="padding:0;">
            <div class="ql-wrapper"><div id="quillEditor"></div></div>
            <textarea name="content" id="blogContent" style="display:none;">{{ old('content') }}</textarea>
          </div>
        </div>

        {{-- Excerpt --}}
        <div class="be-card" style="margin-top:18px;">
          <div class="be-card-header"><h2><i class="fas fa-align-left"></i> Excerpt / Short Description</h2></div>
          <div class="be-card-body">
            <div class="be-form-group">
              <div class="input-with-counter">
                <textarea name="excerpt" id="blogExcerpt" class="be-textarea"
                  placeholder="Write a brief summary…" maxlength="300" rows="4">{{ old('excerpt') }}</textarea>
                <span class="char-counter" id="excerptCounter">0/300</span>
              </div>
              <div class="be-input-hint"><i class="fas fa-info-circle"></i> Keep under 160 characters if used as meta description.</div>
            </div>
          </div>
        </div>

        {{-- Featured Image --}}
        <div class="be-card" style="margin-top:18px;">
          <div class="be-card-header"><h2><i class="fas fa-image"></i> Featured Image</h2></div>
          <div class="be-card-body">
            <div class="img-upload-zone" id="featuredImgZone">
              <input type="file" name="featured_image" id="featuredImgInput" accept="image/jpeg,image/png,image/webp,image/gif"/>
              <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
              <div class="upload-text"><strong>Click to upload</strong> or drag &amp; drop<br><span style="font-size:.72rem;">PNG, JPG, WebP — max 5 MB · Recommended: 1200 × 630 px</span></div>
            </div>
            <div class="img-preview-wrap" id="featuredPreview">
              <img src="" alt="" id="featuredPreviewImg"/>
              <div class="img-preview-actions">
                <button type="button" class="btn-be btn-outline btn-sm" id="btnChangeFeatured"><i class="fas fa-exchange-alt"></i> Change</button>
                <button type="button" class="btn-be btn-danger-outline btn-sm" id="btnRemoveFeatured"><i class="fas fa-trash"></i></button>
              </div>
            </div>
            <div class="be-form-group" style="margin-top:16px;">
              <label class="be-label"><i class="fas fa-tag" style="color:var(--primary);font-size:.8rem;"></i> Image Alt Text <span class="lbl-badge lbl-seo">SEO</span></label>
              <input type="text" name="image_alt" id="imageAlt" class="be-input" placeholder="Describe the image…" maxlength="125" value="{{ old('image_alt') }}"/>
              <div class="be-input-hint"><i class="fas fa-info-circle"></i> Include your focus keyword. Critical for accessibility &amp; image SEO.</div>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-heading" style="color:var(--primary);font-size:.8rem;"></i> Image Title <span class="lbl-badge lbl-optional">Optional</span></label>
              <input type="text" name="image_title" class="be-input" placeholder="Tooltip text shown on mouse-over…" maxlength="125" value="{{ old('image_title') }}"/>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-file-image" style="color:var(--primary);font-size:.8rem;"></i> Image Caption <span class="lbl-badge lbl-optional">Optional</span></label>
              <input type="text" name="image_caption" class="be-input" placeholder="Caption displayed below the image…" value="{{ old('image_caption') }}"/>
            </div>
          </div>
        </div>

        {{-- SEO & Meta --}}
        <div class="be-card" style="margin-top:18px;">
          <div class="be-card-header">
            <h2><i class="fas fa-search"></i> SEO &amp; Meta</h2>
            <div id="seoScoreBadge" style="font-size:.75rem;font-weight:700;padding:4px 12px;border-radius:20px;background:#eef2f9;color:var(--muted);">Score: <span id="seoScoreLabel">0</span>/100</div>
          </div>
          <div class="be-card-body">
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-key" style="color:var(--primary);font-size:.8rem;"></i> Focus Keyword <span class="lbl-badge lbl-seo">SEO</span></label>
              <div style="position:relative;">
                <input type="text" name="focus_keyword" id="focusKeyword" class="be-input" placeholder="e.g. best ai tools for developers" value="{{ old('focus_keyword') }}"/>
                <span class="kw-density" id="kwDensityBadge" style="display:none;position:absolute;right:8px;top:50%;transform:translateY(-50%);"></span>
              </div>
              <div class="be-input-hint"><i class="fas fa-info-circle"></i> Aim for 1–2% density in content.</div>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-heading" style="color:var(--primary);font-size:.8rem;"></i> Meta Title <span class="lbl-badge lbl-seo">SEO</span></label>
              <div class="input-with-counter">
                <input type="text" name="meta_title" id="metaTitle" class="be-input" placeholder="Leave blank to use post title | max 60 chars" maxlength="70" value="{{ old('meta_title') }}"/>
                <span class="char-counter" id="metaTitleCounter">0/60</span>
              </div>
              <div class="meter-bar"><div class="meter-fill" id="metaTitleMeter" style="width:0%;"></div></div>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-align-left" style="color:var(--primary);font-size:.8rem;"></i> Meta Description <span class="lbl-badge lbl-seo">SEO</span></label>
              <div class="input-with-counter">
                <textarea name="meta_description" id="metaDescription" class="be-textarea"
                  placeholder="Compelling description… include your focus keyword." maxlength="170" rows="3">{{ old('meta_description') }}</textarea>
                <span class="char-counter" id="metaDescCounter">0/160</span>
              </div>
              <div class="meter-bar"><div class="meter-fill" id="metaDescMeter" style="width:0%;"></div></div>
            </div>
            <div class="be-form-group">
              <div class="seo-preview">
                <div class="seo-preview-label"><i class="fas fa-google"></i> Google SERP Preview</div>
                <div class="seo-url"  id="serpUrl">yourdomain.com › blog › your-slug</div>
                <a class="seo-title-prev" id="serpTitle">Your post title will appear here…</a>
                <div class="seo-desc-prev" id="serpDesc">Your meta description will appear here.</div>
              </div>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-tags" style="color:var(--primary);font-size:.8rem;"></i> Meta Keywords <span class="lbl-badge lbl-optional">Optional</span></label>
              <div class="tags-wrap" id="metaKeywordsWrap">
                <input class="tags-input" id="metaKeywordsInput" placeholder="Type keyword, press Enter or comma…"/>
              </div>
              <input type="hidden" name="meta_keywords" id="metaKeywordsHidden" value="{{ old('meta_keywords') }}"/>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-link" style="color:var(--primary);font-size:.8rem;"></i> Canonical URL <span class="lbl-badge lbl-optional">Optional</span></label>
              <input type="url" name="canonical_url" id="canonicalUrl" class="be-input" placeholder="https://yourdomain.com/blog/your-slug (leave blank for auto)" value="{{ old('canonical_url') }}"/>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-robot" style="color:var(--primary);font-size:.8rem;"></i> Robots Directive <span class="lbl-badge lbl-seo">SEO</span></label>
              <select name="robots" id="robotsSelect" class="be-select">
                <option value="index, follow"     {{ old('robots','index, follow') == 'index, follow'     ? 'selected':'' }}>index, follow (Default)</option>
                <option value="noindex, follow"   {{ old('robots') == 'noindex, follow'   ? 'selected':'' }}>noindex, follow</option>
                <option value="index, nofollow"   {{ old('robots') == 'index, nofollow'   ? 'selected':'' }}>index, nofollow</option>
                <option value="noindex, nofollow" {{ old('robots') == 'noindex, nofollow' ? 'selected':'' }}>noindex, nofollow</option>
              </select>
            </div>
          </div>
        </div>

        {{-- Open Graph --}}
        <div class="be-card" style="margin-top:18px;">
          <div class="be-card-header"><h2><i class="fas fa-share-alt"></i> Open Graph / Social Sharing</h2></div>
          <div class="be-card-body">
            <div class="social-preview-tabs">
              <button type="button" class="sp-tab active" data-tab="facebook"><i class="fab fa-facebook-f"></i> Facebook</button>
              <button type="button" class="sp-tab" data-tab="twitter"><i class="fab fa-twitter"></i> Twitter</button>
              <button type="button" class="sp-tab" data-tab="linkedin"><i class="fab fa-linkedin-in"></i> LinkedIn</button>
            </div>
            <div class="social-preview-card">
              <div class="sp-img" id="spImgWrap"><i class="fas fa-image"></i><img src="" alt="" id="spPreviewImg" style="display:none;"/></div>
              <div class="sp-info"><div class="sp-site" id="spSite">YOURDOMAIN.COM</div><div class="sp-title" id="spTitle">Your post title…</div><div class="sp-desc" id="spDesc">Your meta description will be used here.</div></div>
            </div>
            <div class="be-form-group" style="margin-top:16px;">
              <label class="be-label"><i class="fas fa-heading" style="color:var(--primary);font-size:.8rem;"></i> OG Title <span class="lbl-badge lbl-optional">Optional</span></label>
              <input type="text" name="og_title" id="ogTitle" class="be-input" placeholder="Custom title for social sharing" value="{{ old('og_title') }}"/>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-align-left" style="color:var(--primary);font-size:.8rem;"></i> OG Description <span class="lbl-badge lbl-optional">Optional</span></label>
              <textarea name="og_description" id="ogDescription" class="be-textarea" rows="2" placeholder="Custom social description…">{{ old('og_description') }}</textarea>
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
              <select name="twitter_card" class="be-select">
                <option value="summary_large_image">summary_large_image (recommended)</option>
                <option value="summary">summary</option>
                <option value="app">app</option>
              </select>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fab fa-twitter" style="color:#1da1f2;font-size:.8rem;"></i> Twitter @username <span class="lbl-badge lbl-optional">Optional</span></label>
              <input type="text" name="twitter_creator" class="be-input" placeholder="@yourhandle" value="{{ old('twitter_creator') }}"/>
            </div>
          </div>
        </div>

        {{-- Schema --}}
        <div class="be-card" style="margin-top:18px;">
          <div class="be-card-header"><h2><i class="fas fa-code"></i> Schema / Structured Data</h2></div>
          <div class="be-card-body">
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-layer-group" style="color:var(--primary);font-size:.8rem;"></i> Schema Type <span class="lbl-badge lbl-seo">SEO</span></label>
              <div class="schema-pills">
                <label class="schema-pill active"><input type="radio" name="schema_type" value="Article" checked style="display:none;"/><i class="fas fa-newspaper"></i> Article</label>
                <label class="schema-pill"><input type="radio" name="schema_type" value="BlogPosting" style="display:none;"/><i class="fas fa-blog"></i> BlogPosting</label>
                <label class="schema-pill"><input type="radio" name="schema_type" value="HowTo" style="display:none;"/><i class="fas fa-list-ol"></i> HowTo</label>
                <label class="schema-pill"><input type="radio" name="schema_type" value="FAQPage" style="display:none;"/><i class="fas fa-question-circle"></i> FAQPage</label>
                <label class="schema-pill"><input type="radio" name="schema_type" value="Review" style="display:none;"/><i class="fas fa-star"></i> Review</label>
                <label class="schema-pill"><input type="radio" name="schema_type" value="NewsArticle" style="display:none;"/><i class="fas fa-newspaper"></i> NewsArticle</label>
              </div>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-user" style="color:var(--primary);font-size:.8rem;"></i> Author Name</label>
              <div class="be-input" style="opacity:.75;cursor:not-allowed;user-select:none;">{{ auth()->user()->name ?? 'Admin User' }}</div>
              <div class="be-input-hint"><i class="fas fa-lock"></i> Set automatically from your account.</div>
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

        {{-- Advanced SEO --}}
        <div class="be-card" style="margin-top:18px;">
          <div class="be-card-header"><h2><i class="fas fa-cogs"></i> Advanced SEO</h2></div>
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
                <option value="1.0">1.0 — Highest</option>
                <option value="0.9" selected>0.9 — Very High</option>
                <option value="0.8">0.8 — High</option>
                <option value="0.7">0.7 — Medium-High</option>
                <option value="0.5">0.5 — Medium</option>
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

      </div>{{-- /be-left --}}

      {{-- ══════════ RIGHT SIDEBAR ══════════ --}}
      <div class="be-right">

        {{-- SEO Score --}}
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
            <ul class="seo-checklist">
              <li class="fail" id="ck-title"><i class="fas fa-times-circle"></i> Focus keyword in title</li>
              <li class="fail" id="ck-slug"><i class="fas fa-times-circle"></i> Keyword in slug</li>
              <li class="fail" id="ck-metadesc"><i class="fas fa-times-circle"></i> Meta description (120–160 chars)</li>
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

        {{-- Publish Settings --}}
        <div class="be-card">
          <div class="be-card-header"><h2><i class="fas fa-cog"></i> Publish Settings</h2></div>
          <div class="be-card-body">
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-eye" style="color:var(--primary);font-size:.8rem;"></i> Visibility</label>
              <div class="vis-pills">
                <label class="vis-pill active"><input type="radio" name="visibility" value="public" checked style="display:none;"/><i class="fas fa-globe"></i> Public</label>
                <label class="vis-pill"><input type="radio" name="visibility" value="private" style="display:none;"/><i class="fas fa-lock"></i> Private</label>
                <label class="vis-pill"><input type="radio" name="visibility" value="password" style="display:none;"/><i class="fas fa-key"></i> Password</label>
              </div>
            </div>
            <div class="be-form-group" id="passwordField" style="display:none;">
              <label class="be-label"><i class="fas fa-key" style="color:var(--primary);font-size:.8rem;"></i> Post Password</label>
              <input type="password" name="post_password" class="be-input" placeholder="Enter password…"/>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-flag" style="color:var(--primary);font-size:.8rem;"></i> Status</label>
              <select name="status" id="postStatus" class="be-select">
                <option value="draft"     {{ old('status','draft') == 'draft'     ? 'selected':'' }}>Draft</option>
                <option value="pending"   {{ old('status') == 'pending'   ? 'selected':'' }}>Pending Review</option>
                <option value="published" {{ old('status') == 'published' ? 'selected':'' }}>Published</option>
                <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected':'' }}>Scheduled</option>
              </select>
            </div>
            <div class="be-form-group" id="scheduleField" style="display:none;">
              <label class="be-label"><i class="fas fa-calendar-alt" style="color:var(--primary);font-size:.8rem;"></i> Publish Date &amp; Time</label>
              <input type="datetime-local" name="published_at" class="be-input" value="{{ old('published_at') }}"/>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-comments" style="color:var(--primary);font-size:.8rem;"></i> Comments</label>
              <select name="allow_comments" class="be-select">
                <option value="1">Allow comments</option>
                <option value="0">Disable comments</option>
              </select>
            </div>
            <div class="be-form-group">
              <label class="be-label"><i class="fas fa-clock" style="color:var(--primary);font-size:.8rem;"></i> Reading Time Override <span class="lbl-badge lbl-optional">Optional</span></label>
              <input type="text" name="reading_time" id="readingTimeField" class="be-input" placeholder="e.g. 5 min read" value="{{ old('reading_time') }}"/>
            </div>
            <div style="padding-top:8px;display:flex;gap:8px;flex-direction:column;">
              <button type="submit" name="action" value="publish" class="btn-be btn-success js-blog-save-btn" id="sidebarPublish"><i class="fas fa-rocket"></i> Publish Now</button>
              <button type="submit" name="action" value="draft" class="btn-be btn-outline js-blog-save-btn" id="sidebarDraft"><i class="fas fa-save"></i> Save Draft</button>
            </div>
          </div>
        </div>

        {{-- Categories --}}
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
              <select name="subcategory_id" class="be-select"><option value="">— None —</option></select>
            </div>
          </div>
        </div>

        {{-- Tags --}}
        <div class="be-card">
          <div class="be-card-header"><h2><i class="fas fa-tags"></i> Tags</h2></div>
          <div class="be-card-body">
            <div class="be-form-group">
              <div class="tags-wrap" id="postTagsWrap">
                <input class="tags-input" id="postTagsInput" placeholder="Add tag, press Enter…"/>
              </div>
              <input type="hidden" name="tags" id="postTagsHidden" value="{{ old('tags') }}"/>
              <div class="be-input-hint"><i class="fas fa-info-circle"></i> Separate with Enter or comma</div>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:8px;">
              <span style="font-size:.7rem;color:var(--muted);width:100%;font-weight:700;">Suggested:</span>
              <span class="tag-pill suggested-tag" style="cursor:pointer;" data-tag="Technology">Technology</span>
              <span class="tag-pill suggested-tag" style="cursor:pointer;" data-tag="AI">AI</span>
              <span class="tag-pill suggested-tag" style="cursor:pointer;" data-tag="Web Dev">Web Dev</span>
              <span class="tag-pill suggested-tag" style="cursor:pointer;" data-tag="Tutorial">Tutorial</span>
              <span class="tag-pill suggested-tag" style="cursor:pointer;" data-tag="Tips">Tips</span>
            </div>
          </div>
        </div>

        {{-- Author --}}
        <div class="be-card">
          <div class="be-card-header"><h2><i class="fas fa-user-pen"></i> Author</h2></div>
          <div class="be-card-body">
            <div class="be-input" style="opacity:.75;cursor:not-allowed;user-select:none;display:flex;align-items:center;gap:8px;">
              <i class="fas fa-user-circle"></i> {{ auth()->user()->name ?? 'Admin User' }}
            </div>
            <div class="be-input-hint"><i class="fas fa-lock"></i> Author is set automatically from your account and cannot be changed.</div>
          </div>
        </div>

      </div>{{-- /be-right --}}
    </div>{{-- /be-grid --}}
  </form>

</div>
</div>

{{-- Toast container --}}
<div class="be-toast-stack" id="beToastStack"></div>

@endsection


@push('scripts')

{{-- ── Modular blog editor (versioned for cache busting) ── --}}
<script src="{{ asset('assets/js/blog.js') }}?v={{ filemtime(public_path('assets/js/blog.js')) }}"></script>

<script>
$(function () {

  /* ── 1. Initialise Quill ── */
  const quill = new Quill('#quillEditor', {
    theme: 'snow',
    placeholder: 'Start writing your amazing blog post here…',
    modules: {
      toolbar: [
        [{ header: [1, 2, 3, 4, false] }],
        [{ font: [] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ color: [] }, { background: [] }],
        [{ align: [] }],
        [{ list: 'ordered' }, { list: 'bullet' }],
        [{ indent: '-1' }, { indent: '+1' }],
        ['blockquote', 'code-block'],
        ['link', 'image', 'video'],
        ['clean'],
      ],
    },
  });

  /* Pre-fill old() content if validation failed */
  const oldContent = $('#blogContent').val();
  if (oldContent) quill.clipboard.dangerouslyPasteHTML(oldContent);

  /* ── 2. Boot the editor with all modules ── */
  new BlogEditor({
    quill,
    autosaveUrl: '{{ route("blogs.fhy6adv645gv5zd5") }}',
    csrfToken:   '{{ csrf_token() }}',
  });

});
</script>

{{-- ── Session flash toasts (uses window.showToast exposed by ToastManager) ── --}}
@if(session('success'))
<script>$(function(){ window.showToast(`{!! session('success') !!}`, '#00c896', 'fas fa-check-circle'); });</script>
@endif

@if(session('error'))
<script>$(function(){ window.showToast(`{!! session('error') !!}`, '#ff4d6d', 'fas fa-times-circle'); });</script>
@endif

@if($errors->any())
<script>
$(function(){
  @foreach($errors->all() as $error)
  window.showToast(`{{ $error }}`, '#ff4d6d', 'fas fa-exclamation-circle');
  @endforeach
});
</script>
@endif
@endpush
