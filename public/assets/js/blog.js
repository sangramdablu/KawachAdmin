/**
 * ============================================================
 *  BlogEditor — Production-Ready Modular JavaScript
 *  File: public/js/blog-editor.js
 *
 *  Architecture:
 *    BlogEditor (orchestrator)
 *      ├── SlugManager       — title→slug, regenerate, copy
 *      ├── CharCounter       — character counters + meters
 *      ├── SerpPreview       — live Google SERP preview
 *      ├── SocialPreview     — OG / Twitter / LinkedIn preview
 *      ├── ImageUploader     — featured image drag-drop + preview
 *      ├── TagsInput         — multi-tag pill inputs
 *      ├── SeoScorer         — 12-point SEO scoring engine
 *      ├── DraftManager      — localStorage + server autosave
 *      └── FormSubmitter     — validated form submission
 * ============================================================
 */

'use strict';

/* ────────────────────────────────────────────────
   CONFIG — edit once, applies everywhere
──────────────────────────────────────────────── */
const BlogEditorConfig = {
  autosaveInterval:  5000,   // ms — localStorage save
  serverSaveInterval: 15000, // ms — AJAX server save
  wordsPerMinute:   200,
  maxTitleLength:    70,
  titleIdealMin:     50,
  titleIdealMax:     60,
  maxMetaTitleLen:   60,
  metaTitleIdealMin: 30,
  maxMetaDescLen:    160,
  metaDescIdealMin:  120,
  maxExcerptLength:  300,
  minContentWords:   300,
  warnContentWords:  100,
  kwDensityMin:      1,
  kwDensityMax:      3,
};

/* ────────────────────────────────────────────────
   UTILITIES
──────────────────────────────────────────────── */
const Utils = {
  slugify(str) {
    return str
      .toLowerCase()
      .replace(/[^a-z0-9\s-]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-')
      .replace(/^-|-$/g, '');
  },

  stripHtml(html) {
    return $('<div>').html(html).text().trim();
  },

  countWords(text) {
    return text ? text.split(/\s+/).filter(Boolean).length : 0;
  },

  escapeHtml(text) {
    return $('<div>').text(text).html();
  },

  copyToClipboard(text) {
    return navigator.clipboard
      ? navigator.clipboard.writeText(text)
      : Promise.reject(new Error('Clipboard API unavailable'));
  },

  debounce(fn, delay) {
    let timer;
    return function (...args) {
      clearTimeout(timer);
      timer = setTimeout(() => fn.apply(this, args), delay);
    };
  },
};


/* ────────────────────────────────────────────────
   MODULE: SlugManager
──────────────────────────────────────────────── */
class SlugManager {
  constructor() {
    this.$slug   = $('#blogSlug');
    this.$title  = $('#blogTitle');
    this.$prefix = $('#slugPrefix');
    this.$regenBtn = $('#btnRegenerateSlug');
    this.$copyBtn  = $('#btnCopySlug');
    this._manuallyEdited = !!this.$slug.val(); // lock if pre-filled (edit mode)
    this._bind();
  }

  _bind() {
    this.$slug.on('input', () => {
      this._manuallyEdited = true;
      this.$slug.val(Utils.slugify(this.$slug.val()));
    });

    this.$regenBtn.on('click', () => this._regen());
    this.$copyBtn.on('click',  () => this._copy());
  }

  updateFromTitle(title) {
    if (!this._manuallyEdited) {
      this.$slug.val(Utils.slugify(title));
    }
  }

  getSlug() {
    return this.$slug.val();
  }

  getFullUrl() {
    return this.$prefix.text() + this.$slug.val();
  }

  lockManual() {
    this._manuallyEdited = true;
  }

  _regen() {
    this._manuallyEdited = false;
    this.$slug.val(Utils.slugify(this.$title.val()));
    // notify dependants via custom event
    $(document).trigger('blog:slugChanged');
  }

  _copy() {
    const url = this.getFullUrl();
    Utils.copyToClipboard(url)
      .then(() => {
        const $i = this.$copyBtn.find('i');
        $i.removeClass('fa-copy').addClass('fa-check');
        setTimeout(() => $i.removeClass('fa-check').addClass('fa-copy'), 1500);
        $(document).trigger('blog:toast', ['URL copied to clipboard', 'var(--success)', 'fas fa-check-circle']);
      })
      .catch(() => {
        $(document).trigger('blog:toast', ['Could not copy — please copy manually', 'var(--warning)', 'fas fa-exclamation-triangle']);
      });
  }
}


/* ────────────────────────────────────────────────
   MODULE: CharCounter
──────────────────────────────────────────────── */
class CharCounter {
  /**
   * @param {string} inputSel  — jQuery selector for the input/textarea
   * @param {string} counterSel — jQuery selector for the counter span
   * @param {number} max
   * @param {number} idealMin
   * @param {number} idealMax
   * @param {string} [meterId]  — optional fill-bar id
   */
  constructor(inputSel, counterSel, max, idealMin, idealMax, meterId) {
    this.$input   = $(inputSel);
    this.$counter = $(counterSel);
    this.$meter   = meterId ? $('#' + meterId) : null;
    this.max      = max;
    this.idealMin = idealMin;
    this.idealMax = idealMax || max;
    this._bind();
    this.refresh(); // init on load
  }

  _bind() {
    this.$input.on('input', () => this.refresh());
  }

  refresh() {
    const len = this.$input.val().length;
    this.$counter.text(`${len}/${this.max}`);
    this.$counter.removeClass('warn over good');

    let meterColor = 'var(--border)';

    if (len >= this.idealMin && len <= this.idealMax) {
      this.$counter.addClass('good');
      meterColor = 'var(--success)';
    } else if (len > this.idealMax || len > this.max * 0.95) {
      this.$counter.addClass(len > this.max ? 'over' : 'warn');
      meterColor = len > this.max ? 'var(--danger)' : 'var(--warning)';
    } else if (len > 0) {
      this.$counter.addClass('warn');
      meterColor = 'var(--warning)';
    }

    if (this.$meter) {
      const pct = Math.min(100, (len / this.idealMax) * 100);
      this.$meter.css({ width: `${pct}%`, background: meterColor });
    }

    return len;
  }

  val() {
    return this.$input.val();
  }
}


/* ────────────────────────────────────────────────
   MODULE: SerpPreview
──────────────────────────────────────────────── */
class SerpPreview {
  constructor(slugManager) {
    this.slugManager = slugManager;
    this.$url   = $('#serpUrl');
    this.$title = $('#serpTitle');
    this.$desc  = $('#serpDesc');
    this.domain = window.location.hostname || 'yourdomain.com';
    this._bind();
  }

  _bind() {
    const refresh = () => this.refresh();
    $('#blogTitle, #metaTitle, #blogExcerpt, #metaDescription').on('input', refresh);
    $(document).on('blog:slugChanged', refresh);
  }

  refresh() {
    const slug  = this.slugManager.getSlug() || 'your-post-slug';
    const title = $('#metaTitle').val() || $('#blogTitle').val() || 'Your post title will appear here…';
    const desc  = $('#metaDescription').val() || $('#blogExcerpt').val() ||
                  'Your meta description will appear here. Write a compelling summary to improve click-through rate.';

    this.$url.text(`${this.domain} › blog › ${slug}`);
    this.$title.text(title.substring(0, 70));
    this.$desc.text(desc.substring(0, 160));
  }
}


/* ────────────────────────────────────────────────
   MODULE: SocialPreview
──────────────────────────────────────────────── */
class SocialPreview {
  constructor() {
    this.$site  = $('#spSite');
    this.$title = $('#spTitle');
    this.$desc  = $('#spDesc');
    this.$img   = $('#spPreviewImg');
    this.$imgWrapIcon = $('#spImgWrap > i');
    this.domain = window.location.hostname || 'yourdomain.com';
    this._bind();
  }

  _bind() {
    const refresh = () => this.refresh();
    $('.sp-tab').on('click', function () {
      $('.sp-tab').removeClass('active');
      $(this).addClass('active');
      refresh();
    });
    $('#ogTitle, #ogDescription, #blogTitle, #metaTitle, #metaDescription, #blogExcerpt').on('input', refresh);
  }

  refresh() {
    const tab   = $('.sp-tab.active').data('tab') || 'facebook';
    const title = $('#ogTitle').val() || $('#metaTitle').val() || $('#blogTitle').val() || 'Your post title…';
    const desc  = $('#ogDescription').val() || $('#metaDescription').val() || $('#blogExcerpt').val() || 'Description appears here.';

    this.$site.text(this.domain.toUpperCase() + (tab === 'twitter' ? ' ON TWITTER' : ''));
    this.$title.text(title.substring(0, 88));
    this.$desc.text(desc.substring(0, 120));
  }

  setImage(src) {
    if (src) {
      this.$img.attr('src', src).show();
      this.$imgWrapIcon.hide();
    } else {
      this.$img.hide().attr('src', '');
      this.$imgWrapIcon.show();
    }
  }
}


/* ────────────────────────────────────────────────
   MODULE: ImageUploader
──────────────────────────────────────────────── */
class ImageUploader {
  /**
   * @param {Object} opts
   *   inputId, zoneId, previewId, previewImgId, changeBtnId, removeBtnId
   * @param {SocialPreview} socialPreview — to sync OG image
   */
  constructor(opts, socialPreview) {
    this.$input     = $('#' + opts.inputId);
    this.$zone      = $('#' + opts.zoneId);
    this.$preview   = $('#' + opts.previewId);
    this.$previewImg = $('#' + opts.previewImgId);
    this.$changeBtn = $('#' + opts.changeBtnId);
    this.$removeBtn = $('#' + opts.removeBtnId);
    this.socialPreview = socialPreview;
    this.hasImage   = this.$preview.is(':visible');
    this._bind();
  }

  _bind() {
    this.$input.on('change', () => this._onFileSelected());
    this.$zone.on('dragover',  (e) => { e.preventDefault(); this.$zone.addClass('drag-over'); });
    this.$zone.on('dragleave', ()  => this.$zone.removeClass('drag-over'));
    this.$zone.on('drop', (e) => {
      e.preventDefault();
      this.$zone.removeClass('drag-over');
      const files = e.originalEvent.dataTransfer.files;
      if (files.length) {
        this.$input[0].files = files;
        this._onFileSelected();
      }
    });
    this.$changeBtn.on('click', () => this.$input.trigger('click'));
    this.$removeBtn.on('click', () => this._remove());
  }

  _onFileSelected() {
    const file = this.$input[0].files[0];
    if (!file) return;

    // Validate size (5 MB)
    if (file.size > 5 * 1024 * 1024) {
      $(document).trigger('blog:toast', ['Image must be under 5 MB', 'var(--danger)', 'fas fa-exclamation-circle']);
      return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
      const src = e.target.result;
      this.$previewImg.attr('src', src);
      this.$zone.hide();
      this.$preview.show();
      this.hasImage = true;
      if (this.socialPreview) this.socialPreview.setImage(src);
      $(document).trigger('blog:imageChanged');
      $(document).trigger('blog:toast', ['Featured image set', 'var(--success)', 'fas fa-image']);
    };
    reader.readAsDataURL(file);

    // Upload to the server so window.featuredImagePath is a real path the
    // (non-multipart) autosave request can reference. The full-quality file
    // itself still gets submitted normally via the form on the real Save/Publish.
    const formData = new FormData();
    formData.append('image', file);
    const draftIdEl = document.getElementById('draft_id');
    formData.append('draft_id', draftIdEl ? draftIdEl.value : '');

    $.ajax({
      url: '/upload-image',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
      success: (res) => {
        window.featuredImagePath = res.path;
      },
      error: (err) => {
        console.error('Featured image upload failed:', err);
      },
    });
  }

  _remove() {
    this.$input.val('');
    this.$previewImg.attr('src', '');
    this.$preview.hide();
    this.$zone.show();
    this.hasImage = false;
    if (this.socialPreview) this.socialPreview.setImage(null);
    if ($('#removeFeaturedFlag').length) $('#removeFeaturedFlag').val('1');
    $(document).trigger('blog:imageChanged');
    $(document).trigger('blog:toast', ['Featured image removed', 'var(--warning)', 'fas fa-trash']);
  }

  imageSet() {
    return this.hasImage;
  }
}


/* ────────────────────────────────────────────────
   MODULE: TagsInput
──────────────────────────────────────────────── */
class TagsInput {
  constructor(wrapId, inputId, hiddenId) {
    this.tags   = [];
    this.$wrap  = $('#' + wrapId);
    this.$input = $('#' + inputId);
    this.$hidden = $('#' + hiddenId);
    this._init();
    this._bind();
  }

  _init() {
    // Pre-fill existing values (edit mode / old input)
    const existing = this.$hidden.val();
    if (existing) {
      existing.split(',').forEach(t => { if (t.trim()) this.add(t.trim()); });
    }
  }

  _bind() {
    this.$wrap.on('click', () => this.$input.focus());
    this.$input.on('keydown', (e) => {
      if ((e.key === 'Enter' || e.key === ',') && this.$input.val().trim()) {
        e.preventDefault();
        this.add(this.$input.val().trim().replace(/,/g, ''));
        this.$input.val('');
      }
      if (e.key === 'Backspace' && !this.$input.val() && this.tags.length) {
        this.remove(this.tags[this.tags.length - 1]);
      }
    });
  }

  add(txt) {
    if (!txt || this.tags.includes(txt)) return;
    this.tags.push(txt);
    const $pill = $(`<span class="tag-pill">${Utils.escapeHtml(txt)}<button type="button"><i class="fas fa-times"></i></button></span>`);
    $pill.find('button').on('click', () => this.remove(txt));
    this.$input.before($pill);
    this._sync();
  }

  remove(txt) {
    this.tags = this.tags.filter(t => t !== txt);
    this.$wrap.find('.tag-pill').filter(function () {
      return $(this).text().trim() === txt;
    }).remove();
    this._sync();
  }

  _sync() {
    this.$hidden.val(this.tags.join(','));
  }

  getTags() {
    return [...this.tags];
  }
}


/* ────────────────────────────────────────────────
   MODULE: SeoScorer
──────────────────────────────────────────────── */
class SeoScorer {
  constructor(imageUploader, quillInstance) {
    this.imageUploader = imageUploader;
    this.quill = quillInstance;
    this.$ring      = $('#seoRing');
    this.$scoreText = $('#seoScoreText');
    this.$scoreSubtext = $('#seoScoreSubtext');
    this.$scoreLabel = $('#seoScoreLabel');
    this.$scoreBadge = $('#seoScoreBadge');
    this.$kwBadge    = $('#kwDensityBadge');
    this._bindDensityBadge();
  }

  _bindDensityBadge() {
    $('#focusKeyword').on('input', () => this._updateDensityBadge());
  }

  _updateDensityBadge() {
    const kw = $('#focusKeyword').val().trim().toLowerCase();
    if (!kw) { this.$kwBadge.hide(); return; }

    const text   = this.quill.getText().toLowerCase();
    const words  = Utils.countWords(text);
    const count  = (text.match(new RegExp(kw.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'), 'g')) || []).length;
    const density = words > 0 ? ((count / words) * 100).toFixed(1) : 0;

    const { bg, color } = this._densityColor(parseFloat(density));
    this.$kwBadge.show().text(`${density}%`).css({ background: bg, color });
  }

  _densityColor(d) {
    if (d >= BlogEditorConfig.kwDensityMin && d <= BlogEditorConfig.kwDensityMax)
      return { bg: '#d4f5ec', color: '#00a87c' };
    if (d > BlogEditorConfig.kwDensityMax)
      return { bg: '#ffe2e8', color: 'var(--danger)' };
    return { bg: '#e8f1fd', color: 'var(--primary)' };
  }

  compute() {
    let score = 0;
    const C = BlogEditorConfig;

    const kw          = $('#focusKeyword').val().trim().toLowerCase();
    const title       = $('#blogTitle').val().toLowerCase();
    const slug        = $('#blogSlug').val().toLowerCase();
    const metaDesc    = $('#metaDescription').val();
    const metaTitle   = $('#metaTitle').val();
    const imgAlt      = $('#imageAlt').val().trim();
    const contentText = this.quill.getText().toLowerCase();
    const contentHtml = this.quill.root.innerHTML;
    const words       = Utils.countWords(contentText);
    const $dom        = $('<div>').html(contentHtml);

    const results = {};

    // 1. Keyword in title (10)
    results['ck-title'] = { pass: !!(kw && title.includes(kw)), warn: false, pts: 10 };

    // 2. Keyword in slug (8)
    results['ck-slug'] = { pass: !!(kw && slug.includes(kw.replace(/\s+/g, '-'))), warn: false, pts: 8 };

    // 3. Meta description length (10/4)
    const mdLen = metaDesc.length;
    const mdOk  = mdLen >= C.metaDescIdealMin && mdLen <= C.maxMetaDescLen;
    const mdWarn = mdLen > 0 && !mdOk;
    results['ck-metadesc'] = { pass: mdOk, warn: mdWarn, pts: 10, warnPts: 4 };

    // 4. Keyword in meta description (8)
    results['ck-kw-metadesc'] = { pass: !!(kw && metaDesc.toLowerCase().includes(kw)), warn: false, pts: 8 };

    // 5. Content length (15/5)
    const lengthOk   = words >= C.minContentWords;
    const lengthWarn = words >= C.warnContentWords && !lengthOk;
    results['ck-content-len'] = { pass: lengthOk, warn: lengthWarn, pts: 15, warnPts: 5 };

    // 6. Keyword in content (10)
    results['ck-content-kw'] = { pass: !!(kw && contentText.includes(kw)), warn: false, pts: 10 };

    // 7. Has H2/H3 (8)
    results['ck-heading'] = { pass: $dom.find('h2,h3').length > 0, warn: false, pts: 8 };

    // 8. Featured image (8)
    results['ck-img'] = { pass: this.imageUploader.imageSet(), warn: false, pts: 8 };

    // 9. Image alt text (7)
    results['ck-img-alt'] = { pass: imgAlt.length > 0, warn: false, pts: 7 };

    // 10. Link in content (6)
    results['ck-links'] = { pass: $dom.find('a').length > 0, warn: false, pts: 6 };

    // 11. Meta title length (6/2)
    const mtLen = metaTitle.length;
    const mtOk  = mtLen >= C.metaTitleIdealMin && mtLen <= C.maxMetaTitleLen;
    const mtWarn = mtLen > 0 && !mtOk;
    results['ck-metatitle-len'] = { pass: mtOk, warn: mtWarn, pts: 6, warnPts: 2 };

    // 12. Keyword density (4/1)
    let density = 0;
    if (kw && words > 0) {
      const kwCount = (contentText.match(new RegExp(kw.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'), 'g')) || []).length;
      density = (kwCount / words) * 100;
    }
    const densOk   = density >= C.kwDensityMin && density <= C.kwDensityMax;
    const densWarn = (density > 0 && density < C.kwDensityMin) || density > C.kwDensityMax;
    results['ck-kw-density'] = { pass: densOk, warn: densWarn, pts: 4, warnPts: 1 };

    // Calculate score
    Object.entries(results).forEach(([id, r]) => {
      this._setCheck(id, r.pass, r.warn);
      if (r.pass) score += r.pts;
      else if (r.warn && r.warnPts) score += r.warnPts;
    });

    this._updateUI(score);
    return score;
  }

  _setCheck(id, pass, warn) {
    const $li = $('#' + id);
    $li.removeClass('pass fail warn');
    const icon = pass ? 'fa-check-circle' : (warn ? 'fa-exclamation-triangle' : 'fa-times-circle');
    const cls  = pass ? 'pass' : (warn ? 'warn' : 'fail');
    $li.addClass(cls).find('i')
       .removeClass('fa-check-circle fa-times-circle fa-exclamation-triangle')
       .addClass(icon);
  }

  _updateUI(score) {
    const ringColor = score >= 80 ? '#00c896' : score >= 50 ? '#ffb830' : '#ff4d6d';
    const label = score >= 80 ? 'Excellent!' : score >= 60 ? 'Good' : score >= 40 ? 'Needs Work' : 'Poor';
    const sub = score >= 80 ? 'Post is well-optimised 🎉' : score >= 60 ? 'A few improvements needed' : 'Fill in key SEO fields to improve';
    const badgeBg = score >= 80 ? '#d4f5ec' : score >= 60 ? '#fff4d6' : '#ffe2e8';
    const badgeColor = score >= 80 ? '#00a87c' : score >= 60 ? '#b8860b' : '#ff4d6d';

    this.$ring.text(score).css('background', ringColor);
    this.$scoreText.text(label);
    this.$scoreSubtext.text(sub);
    this.$scoreLabel.text(score);
    this.$scoreBadge.css({ background: badgeBg, color: badgeColor });
  }
}


/* ────────────────────────────────────────────────
   MODULE: DraftManager
──────────────────────────────────────────────── */
class DraftManager {
  /**
   * @param {Object} opts
   *   autosaveUrl  — server route
   *   csrfToken    — {{ csrf_token() }}
   *   quill        — Quill instance
   */
  constructor(opts) {
    this.autosaveUrl  = opts.autosaveUrl;
    this.csrfToken    = opts.csrfToken;
    this.quill        = opts.quill;
    this.draftId = document.getElementById('draft_id')?.value || null;
    this.localKey     = this._resolveLocalKey();
    this._startTimers();
    this.restore();
  }

  _resolveLocalKey() {
    // Keyed by the actual post id (not a generic per-tab session key) so
    // switching between different posts in the same tab can never restore
    // one post's stale local draft over another post's freshly-loaded content.
    return this.draftId ? ('blog_draft_' + this.draftId) : 'blog_draft_new';
  }

  _collect() {
    return {
      title:            $('#blogTitle').val(),
      slug:             $('#blogSlug').val(),
      content:          this.quill.root.innerHTML,
      excerpt:          $('#blogExcerpt').val(),
      meta_title:       $('#metaTitle').val(),
      meta_description: $('#metaDescription').val(),
      focus_keyword:    $('#focusKeyword').val(),
      image_alt:        $('#imageAlt').val(),
      timestamp:        new Date().toISOString(),
    };
  }

  saveLocal() {
    try {
      localStorage.setItem(this.localKey, JSON.stringify(this._collect()));
    } catch (e) {
      // Storage quota exceeded — degrade gracefully
      console.warn('[DraftManager] localStorage full:', e.message);
    }
  }

  restore() {
    try {
      const saved = localStorage.getItem(this.localKey);
      if (!saved) return;
      const data = JSON.parse(saved);
      if (data.title)            $('#blogTitle').val(data.title).trigger('input');
      if (data.slug)             $('#blogSlug').val(data.slug);
      if (data.excerpt)          $('#blogExcerpt').val(data.excerpt);
      if (data.meta_title)       $('#metaTitle').val(data.meta_title).trigger('input');
      if (data.meta_description) $('#metaDescription').val(data.meta_description).trigger('input');
      if (data.focus_keyword)    $('#focusKeyword').val(data.focus_keyword);
      if (data.image_alt)        $('#imageAlt').val(data.image_alt);
      if (data.content)          this.quill.root.innerHTML = data.content;
      this.quill.update();
      // console.info('[DraftManager] Draft restored from localStorage');
    } catch (e) {
      console.warn('[DraftManager] Could not restore draft:', e.message);
    }
  }

  saveServer() {
    const title = $('#blogTitle').val();
    const content = this.quill.getText().trim();
    if (!title && !content) return;
    const data = {
      title: $('#blogTitle').val(),
      content: this.quill.root.innerHTML,
      excerpt: $('#blogExcerpt').val(),
      meta_title: $('#metaTitle').val(),
      meta_description: $('#metaDescription').val(),
      focus_keyword: $('#focusKeyword').val(),
      category_id: $('#categorySelect').val(),
      tags: $('#postTagsHidden').val(),
      featured_image: window.featuredImagePath,
      og_title: $('#ogTitle').val(),
      og_description: $('#ogDescription').val(),
      canonical_url: $('#canonicalUrl').val(),
      robots: $('#robotsSelect').val(),
      schema_type: $('input[name="schema_type"]:checked').val(),
    };

    $.ajax({
      url: this.autosaveUrl,
      method: 'POST',
      data: {
        _token: this.csrfToken,
        draft_id: this.draftId,
        ...data
      },
      success: (res) => {
        if (res.draft_id && res.draft_id !== this.draftId) {
          const oldKey = this.localKey;
          this.draftId = res.draft_id;
          this.localKey = this._resolveLocalKey();
          // Move any locally-saved content over to the now-id-scoped key so
          // it isn't orphaned under the temporary 'blog_draft_new' slot.
          const existing = localStorage.getItem(oldKey);
          if (existing) {
            localStorage.setItem(this.localKey, existing);
            localStorage.removeItem(oldKey);
          }
          const input = document.getElementById('draft_id');
          if (input) input.value = res.draft_id;
        }
      }
    });
  }

  clearLocal() {
    localStorage.removeItem(this.localKey);
  }

  _startTimers() {
    // localStorage: every 5s
    setInterval(() => this.saveLocal(), BlogEditorConfig.autosaveInterval);
    // Server: every 15s
    setInterval(() => this.saveServer(), BlogEditorConfig.serverSaveInterval);
    // Also save on typing
    const debouncedSave = Utils.debounce(() => this.saveLocal(), 1500);
    $('#blogTitle, #blogExcerpt, #metaTitle, #metaDescription, #focusKeyword, #imageAlt').on('input', debouncedSave);
  }

}


/* ────────────────────────────────────────────────
   MODULE: FormSubmitter
──────────────────────────────────────────────── */
class FormSubmitter {
  constructor(quillInstance, slugManager, draftManager) {
    this.quill        = quillInstance;
    this.slugManager  = slugManager;
    this.draftManager = draftManager;
    this._bind();
  }

  _bind() {
    // Every Draft/Publish button (top bar or sidebar, create or edit page) is
    // a real `type="submit" name="action" value="draft|publish"` button, so
    // its value is reliably included in the POST — this handler only syncs
    // content + validates, and blocks the submit on failure. It never calls
    // form.submit() itself, since that would drop the clicked button's value.
    $(document).on('click', '.js-blog-save-btn', (e) => this.handleSaveClick(e));

    $('#btnPreview').on('click', () => {
      const slug = this.slugManager.getSlug();
      if (slug) {
        window.open(`/blog/${slug}?preview=1`, '_blank');
      } else {
        $(document).trigger('blog:toast', ['Set a slug before previewing', 'var(--warning)', 'fas fa-exclamation-triangle']);
      }
    });

    // Visibility toggle
    $(document).on('click', '.vis-pill', function () {
      $('.vis-pill').removeClass('active');
      $(this).addClass('active');
      const val = $(this).find('input').val();
      $('#passwordField').toggle(val === 'password');
    });

    // Status → schedule field
    $('#postStatus').on('change', function () {
      if ($(this).val() === 'scheduled') $('#scheduleField').slideDown(200);
      else $('#scheduleField').slideUp(200);
    });

    // Schema pills
    $(document).on('click', '.schema-pill', function () {
      $('.schema-pill').removeClass('active');
      $(this).addClass('active');
    });

    // Inline category add
    $('#btnAddCat').on('click', function () {
      $('#newCatField').slideToggle(200);
      $('#newCatInput').focus();
    });
    $('#btnSaveCat').on('click', () => {
      const name = $('#newCatInput').val().trim();
      if (!name) return;
      // Persist it server-side (POST /blogs/category/store) instead of just
      // appending a fake <option value="new_...">: that placeholder id never
      // matched a real row, so saving the post with it selected always
      // failed the category_id "exists:categories,id" validation rule.
      $.ajax({
        url: '/blogs/category/store',
        method: 'POST',
        data: { name },
        success: (res) => {
          $('<option>').val(res.id).text(res.name).prop('selected', true)
            .appendTo('#categorySelect');
          $('#newCatInput').val('');
          $('#newCatField').slideUp(200);
          $(document).trigger('blog:toast', [`Category "${res.name}" added`, 'var(--success)', 'fas fa-folder-plus']);
        },
        error: (xhr) => {
          const msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Could not add category';
          $(document).trigger('blog:toast', [msg, 'var(--danger)', 'fas fa-exclamation-circle']);
        },
      });
    });
  }

  handleSaveClick(e) {
    // sync hidden textarea
    $('#blogContent').val(this.quill.root.innerHTML);

    // Keep the status select visually in sync (other UI, like the schedule-
    // date field, reads this select). The button's own value is "draft" or
    // "publish" (action semantics) but the select's option values are
    // "draft"/"published" — map before assigning, since setting a value that
    // matches no <option> leaves the select with nothing selected, and a
    // <select> with no selected option is dropped from the submit entirely.
    const action = $(e.currentTarget).val();
    const statusMap = { draft: 'draft', publish: 'published' };
    if (statusMap[action]) $('#postStatus').val(statusMap[action]);

    // validation — block the native submit on failure
    if (!$('#blogTitle').val().trim()) {
      e.preventDefault();
      $(document).trigger('blog:toast', ['Please enter a post title', 'var(--danger)', 'fas fa-exclamation-circle']);
      $('#blogTitle').focus();
      return;
    }
    if (!$('#blogContent').val().trim() || this.quill.getText().trim().length < 10) {
      e.preventDefault();
      $(document).trigger('blog:toast', ['Content is too short to save', 'var(--danger)', 'fas fa-exclamation-circle']);
      return;
    }
    if (!$('#blogSlug').val().trim()) {
      this.slugManager.updateFromTitle($('#blogTitle').val());
    }

    // Clear local draft on intentional submit
    if (this.draftManager) this.draftManager.clearLocal();

    // No preventDefault beyond this point — let the button's native submit proceed.
  }
}


/* ────────────────────────────────────────────────
   MODULE: ContentStats
──────────────────────────────────────────────── */
class ContentStats {
  constructor(quillInstance) {
    this.quill = quillInstance;
  }

  update(html) {
    const $dom    = $('<div>').html(html);
    const text    = $dom.text().trim();
    const words   = Utils.countWords(text);
    const readMin = Math.max(1, Math.ceil(words / BlogEditorConfig.wordsPerMinute));

    $('#wordCount').text(words);
    $('#readTime').text(`${readMin} min`);
    $('#headingCount').text($dom.find('h1,h2,h3,h4,h5,h6').length);
    $('#linkCount').text($dom.find('a').length);
    $('#imgCount').text($dom.find('img').length);
    $('#paraCount').text($dom.find('p').length);
    $('#readingTimeField').val(`${readMin} min read`);
  }
}


/* ────────────────────────────────────────────────
   MODULE: ToastManager
──────────────────────────────────────────────── */
class ToastManager {
  constructor(stackId) {
    this.$stack = $('#' + stackId);
    if (!this.$stack.length) {
      this.$stack = $('<div id="beToastStack" class="be-toast-stack"></div>').appendTo('body');
    }
    $(document).on('blog:toast', (e, msg, color, icon) => this.show(msg, color, icon));
    // Expose globally so Blade session scripts can call it
    window.showToast = (msg, color, icon) => this.show(msg, color, icon);
  }

  show(msg, color, icon) {
    color = color || 'var(--primary)';
    icon  = icon  || 'fas fa-info-circle';
    const $t = $(`<div class="be-toast"><i class="${icon}" style="color:${color};"></i><span>${msg}</span></div>`);
    this.$stack.append($t);
    setTimeout(() => $t.fadeOut(300, () => $t.remove()), 3500);
  }
}


/* ════════════════════════════════════════════════
   ORCHESTRATOR — BlogEditor
   Wires all modules together
════════════════════════════════════════════════ */
class BlogEditor {
  /**
   * @param {Object} opts
   *   quill        — Quill instance (created by the blade view)
   *   autosaveUrl  — {{ route('blogs.fhy6adv645gv5zd5') }}
   *   csrfToken    — {{ csrf_token() }}
   */
  constructor(opts) {
    this.quill = opts.quill;

    // 1. Toast (must be first — others fire toasts)
    this.toast = new ToastManager('beToastStack');

    // 2. Core modules
    this.slug          = new SlugManager();
    this.serpPreview   = new SerpPreview(this.slug);
    this.socialPreview = new SocialPreview();
    this.imageUploader = new ImageUploader({
      inputId:     'featuredImgInput',
      zoneId:      'featuredImgZone',
      previewId:   'featuredPreview',
      previewImgId:'featuredPreviewImg',
      changeBtnId: 'btnChangeFeatured',
      removeBtnId: 'btnRemoveFeatured',
    }, this.socialPreview);

    // 3. OG image uploader (secondary — no preview swap)
    this._bindOgImage();

    // 4. Character counters
    this.titleCounter    = new CharCounter('#blogTitle',        '#titleCounter',     BlogEditorConfig.maxTitleLength,   BlogEditorConfig.titleIdealMin,  BlogEditorConfig.titleIdealMax);
    this.excerptCounter  = new CharCounter('#blogExcerpt',      '#excerptCounter',   BlogEditorConfig.maxExcerptLength, 120, 160);
    this.metaTitleCount  = new CharCounter('#metaTitle',        '#metaTitleCounter', BlogEditorConfig.maxMetaTitleLen,  BlogEditorConfig.metaTitleIdealMin, BlogEditorConfig.maxMetaTitleLen, 'metaTitleMeter');
    this.metaDescCount   = new CharCounter('#metaDescription',  '#metaDescCounter',  BlogEditorConfig.maxMetaDescLen,   BlogEditorConfig.metaDescIdealMin,  BlogEditorConfig.maxMetaDescLen,  'metaDescMeter');

    // 5. Tags
    this.metaKeywords = new TagsInput('metaKeywordsWrap', 'metaKeywordsInput', 'metaKeywordsHidden');
    this.postTags     = new TagsInput('postTagsWrap', 'postTagsInput', 'postTagsHidden');
    $(document).on('click', '.suggested-tag', (e) => {
      this.postTags.add($(e.currentTarget).data('tag'));
    });

    // 6. SEO Scorer
    this.seoScorer = new SeoScorer(this.imageUploader, this.quill);

    // 7. Draft Manager
    this.draftManager = new DraftManager({
      autosaveUrl: opts.autosaveUrl,
      csrfToken:   opts.csrfToken,
      quill:       this.quill,
    });

    // 8. Content Stats
    this.contentStats = new ContentStats(this.quill);
    // 9. Form Submitter
    this.formSubmitter = new FormSubmitter(this.quill, this.slug, this.draftManager);
    // 10. Wire Quill events
    this._bindQuill();
    // 11. Wire title → slug
    this._bindTitle();

    // 12. Initial renders
    this.serpPreview.refresh();
    this.socialPreview.refresh();
    setTimeout(() => {
      this.contentStats.update(this.quill.root.innerHTML);
      this.seoScorer.compute();
      this._initState();
    }, 200);
  }

  _bindQuill() {
    this.quill.on('text-change', () => {
      const html = this.quill.root.innerHTML;
      $('#blogContent').val(html);
      this.contentStats.update(html);
      this.seoScorer.compute();
    });
  }

  _initState() {
    const html = this.quill.root.innerHTML;

    $('#blogContent').val(html);

    this.contentStats.update(html);
    this.serpPreview.refresh();
    this.socialPreview.refresh();
    this.seoScorer.compute();
  }

  _bindTitle() {
    $('#blogTitle').on('input', (e) => {
      this.slug.updateFromTitle(e.target.value);
      this.serpPreview.refresh();
      this.seoScorer.compute();
      $(document).trigger('blog:slugChanged');
    });

    // re-score on any SEO field change
    $('#focusKeyword, #metaTitle, #metaDescription, #imageAlt, #blogExcerpt').on('input', () => this.seoScorer.compute());
    $(document).on('blog:imageChanged', () => this.seoScorer.compute());
  }

  _bindOgImage() {
    $('#ogImageInput').on('change', function () {
      const file = this.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = (e) => {
        $('#spPreviewImg').attr('src', e.target.result).show();
        $('#spImgWrap > i').hide();
      };
      reader.readAsDataURL(file);
    });
  }

}

// Expose globally so the Blade script can instantiate it
window.BlogEditor = BlogEditor;
