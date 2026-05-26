/**
 * ============================================================
 *  page-builder-validator.js  (v3 — fully fixed)
 *
 *  Fixes from v2:
 *    - submitForm wrap no longer causes infinite recursion:
 *      the blade's original submitForm() directly calls
 *      form.submit() (native), so we intercept ONLY the
 *      button clicks — not the native form submit event —
 *      to avoid the double-submit loop.
 *    - _repeatV.validate() now scoped to ACTIVE section only,
 *      so inactive page type fields are never validated.
 *    - SlugValidator debounce skips programmatic changes
 *      (only fires on real user input via 'input' not 'change').
 *    - Live feedback re-attached on pb:typeChanged event,
 *      and the blade now fires that event correctly.
 *    - All char counters (portfolio_desc, excerpt, svcShortDesc)
 *      are wired up via the validator.
 *    - Edit mode: form action + method patching handled via
 *      data attributes on the form element.
 * ============================================================
 */

'use strict';

const PBV_CONFIG = {
  titleMaxLen:     200,
  slugRegex:       /^[a-z0-9-]+$/,
  slugMaxLen:      220,
  metaTitleMax:    60,
  metaDescMax:     160,
  urlRegex:        /^(https?:\/\/)([a-zA-Z0-9\-._~:/?#[\]@!$&'()*+,;=%]+)$/,
  emailRegex:      /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
  maxImageSizeMb:  5,
  debounceMs:      500,
  errorClass:      'pb-field-error',
  inputErrorClass: 'pb-input-invalid',
  successClass:    'pb-input-valid',
};

/* ─────────────────────────────────────────────
   ErrorRenderer
───────────────────────────────────────────── */
class ErrorRenderer {
  show($field, message) {
    this.clear($field);
    $field.addClass(PBV_CONFIG.inputErrorClass).removeClass(PBV_CONFIG.successClass);

    const $msg = $(`
      <div class="${PBV_CONFIG.errorClass}" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <span>${this._esc(message)}</span>
      </div>`);

    const $wrap = $field.closest('.pb-input-wrap, .slug-row');
    ($wrap.length ? $wrap : $field).after($msg);
  }

  clear($field) {
    $field.removeClass(PBV_CONFIG.inputErrorClass);
    $field.closest('.pb-form-group, .pb-card-body, .slug-row')
      .find(`.${PBV_CONFIG.errorClass}`).remove();
  }

  markValid($field) {
    $field.addClass(PBV_CONFIG.successClass).removeClass(PBV_CONFIG.inputErrorClass);
  }

  clearAll($form) {
    $form.find(`.${PBV_CONFIG.errorClass}`).remove();
    $form.find(`.${PBV_CONFIG.inputErrorClass}`).removeClass(PBV_CONFIG.inputErrorClass);
    $form.find(`.${PBV_CONFIG.successClass}`).removeClass(PBV_CONFIG.successClass);
  }

  scrollToFirst() {
    const $first = $(`.${PBV_CONFIG.errorClass}`).first();
    if ($first.length) {
      $('html,body').animate({ scrollTop: $first.offset().top - 130 }, 280);
    }
  }

  _esc(s) { return $('<div>').text(s).html(); }
}

/* ─────────────────────────────────────────────
   FieldValidator
───────────────────────────────────────────── */
class FieldValidator {
  evaluate(rule, value, label = 'This field') {
    const v = (value ?? '').toString().trim();

    if (rule === 'required') {
      return v.length > 0 ? { valid: true }
        : { valid: false, message: `${label} is required.` };
    }
    if (rule.startsWith('maxlen:')) {
      const max = +rule.split(':')[1];
      return v.length <= max ? { valid: true }
        : { valid: false, message: `${label} must not exceed ${max} characters (${v.length}/${max}).` };
    }
    if (rule.startsWith('minlen:')) {
      const min = +rule.split(':')[1];
      if (!v.length) return { valid: true };
      return v.length >= min ? { valid: true }
        : { valid: false, message: `${label} must be at least ${min} characters.` };
    }
    if (rule === 'url') {
      if (!v.length) return { valid: true };
      return PBV_CONFIG.urlRegex.test(v) ? { valid: true }
        : { valid: false, message: `${label} must be a valid URL starting with https://.` };
    }
    if (rule === 'email') {
      if (!v.length) return { valid: true };
      return PBV_CONFIG.emailRegex.test(v) ? { valid: true }
        : { valid: false, message: `${label} must be a valid email address.` };
    }
    if (rule === 'slug') {
      if (!v.length) return { valid: false, message: `${label} is required.` };
      if (!PBV_CONFIG.slugRegex.test(v))
        return { valid: false, message: `${label} may only contain lowercase letters, numbers and hyphens.` };
      if (v.length > PBV_CONFIG.slugMaxLen)
        return { valid: false, message: `${label} must not exceed ${PBV_CONFIG.slugMaxLen} characters.` };
      return { valid: true };
    }
    if (rule.startsWith('range:')) {
      const [, min, max] = rule.split(':').map(Number);
      if (!v.length) return { valid: true };
      const n = parseFloat(v);
      if (isNaN(n) || n < min || n > max)
        return { valid: false, message: `${label} must be between ${min} and ${max}.` };
      return { valid: true };
    }
    if (rule === 'integer') {
      if (!v.length) return { valid: true };
      return Number.isInteger(Number(v)) ? { valid: true }
        : { valid: false, message: `${label} must be a whole number.` };
    }
    if (rule === 'image') {
      const file = value;
      if (!file) return { valid: true };
      const allowed = ['image/jpeg','image/png','image/webp','image/gif'];
      if (!allowed.includes(file.type))
        return { valid: false, message: `${label} must be JPG, PNG, WebP or GIF.` };
      if (file.size > PBV_CONFIG.maxImageSizeMb * 1024 * 1024)
        return { valid: false, message: `${label} must not exceed ${PBV_CONFIG.maxImageSizeMb} MB.` };
      return { valid: true };
    }
    return { valid: true };
  }

  evaluateAll(rules, value, label) {
    for (const rule of rules) {
      const r = this.evaluate(rule, value, label);
      if (!r.valid) return r;
    }
    return { valid: true };
  }
}

/* ─────────────────────────────────────────────
   TypeRuleRegistry
───────────────────────────────────────────── */
class TypeRuleRegistry {
  getRules(type) {
    const common = [
      { selector: '#pbTitle',               label: 'Title',            rules: ['required', `maxlen:${PBV_CONFIG.titleMaxLen}`] },
      { selector: '#pbSlug',                label: 'URL Slug',         rules: ['slug'] },
      { selector: '#metaTitle',             label: 'Meta Title',       rules: [`maxlen:${PBV_CONFIG.metaTitleMax}`] },
      { selector: '#metaDescription',       label: 'Meta Description', rules: [`maxlen:${PBV_CONFIG.metaDescMax}`] },
      { selector: '[name="canonical_url"]', label: 'Canonical URL',    rules: ['url'] },
    ];
    return [...common, ...this._typeFields(type)];
  }

  _typeFields(type) {
    const map = {
      service: [
        // FIX: validate the hidden textarea that Quill syncs into
        { selector: '#svcContent',                label: 'Full Description',  rules: ['required', 'minlen:20'] },
        { selector: '[name="short_description"]', label: 'Short Description', rules: ['maxlen:500'] },
        { selector: '[name="cta_url"]',           label: 'CTA URL',           rules: ['url'] },
      ],
      casestudy: [
        { selector: '[name="client_name"]', label: 'Client Name', rules: ['required', 'maxlen:150'] },
        // FIX: validate the hidden textareas that Quill syncs into
        { selector: '#csChallenge',         label: 'Challenge',   rules: ['required', 'minlen:20'] },
        { selector: '#csSolution',          label: 'Solution',    rules: ['required', 'minlen:20'] },
        { selector: '[name="project_url"]', label: 'Project URL', rules: ['url'] },
      ],
      team: [
        { selector: '[name="job_title"]',       label: 'Job Title', rules: ['required', 'maxlen:150'] },
        { selector: '[name="member_email"]',    label: 'Email',     rules: ['email'] },
        { selector: '[name="social_linkedin"]', label: 'LinkedIn',  rules: ['url'] },
        { selector: '[name="social_twitter"]',  label: 'Twitter',   rules: ['url'] },
        { selector: '[name="social_github"]',   label: 'GitHub',    rules: ['url'] },
        { selector: '[name="social_website"]',  label: 'Website',   rules: ['url'] },
      ],
      testimonial: [
        { selector: '[name="testimonial_quote"]', label: 'Quote',       rules: ['required', 'minlen:10', 'maxlen:3000'] },
        { selector: '[name="testimonial_name"]',  label: 'Client Name', rules: ['required', 'maxlen:150'] },
        { selector: '[name="testimonial_video"]', label: 'Video URL',   rules: ['url'] },
      ],
      faq: [],
      portfolio: [
        { selector: '[name="portfolio_url"]',  label: 'Project URL', rules: ['url'] },
        { selector: '[name="portfolio_desc"]', label: 'Short Desc',  rules: ['maxlen:500'] },
        { selector: '[name="portfolio_year"]', label: 'Year',        rules: ['integer', 'range:2000:2099'] },
      ],
      blog: [
        // FIX: validate the hidden textarea that Quill syncs into
        { selector: '#blogContent', label: 'Blog Content', rules: ['required', 'minlen:50'] },
        { selector: '[name="excerpt"]',      label: 'Excerpt',      rules: ['maxlen:500'] },
      ],
      landing: [
        { selector: '[name="hero_headline"]',     label: 'Hero Headline',     rules: ['required', 'maxlen:250'] },
        { selector: '[name="hero_subheadline"]',  label: 'Hero Subheadline',  rules: ['maxlen:350'] },
        { selector: '[name="cta_primary_url"]',   label: 'Primary CTA URL',   rules: ['url'] },
        { selector: '[name="cta_secondary_url"]', label: 'Secondary CTA URL', rules: ['url'] },
      ],
    };
    return map[type] ?? [];
  }
}

/* ─────────────────────────────────────────────
   SlugValidator  (async uniqueness check)
───────────────────────────────────────────── */
class SlugValidator {
  constructor(checkUrl, csrfToken, renderer, $input) {
    this._url      = checkUrl;
    this._csrf     = csrfToken;
    this._renderer = renderer;
    this._$input   = $input;
    this._ignoreId = $input.data('page-id') || null;
    this._timer    = null;
    this._last     = '';
    this._valid    = true;   // optimistic until first check
  }

  init() {
    // FIX: Only bind to real user input events, not programmatic .val() changes
    // which are set by the slug auto-generator. keydown catches Enter/Backspace.
    this._$input.on('input.slugcheck', () => {
      clearTimeout(this._timer);
      this._timer = setTimeout(() => this._check(), PBV_CONFIG.debounceMs);
    });
  }

  isValid() { return this._valid; }

  // FIX: Allow external reset when slug is regenerated programmatically
  reset() {
    this._last  = '';
    this._valid = true;
    this._renderer.clear(this._$input);
  }

  _check() {
    const val = this._$input.val().trim();
    if (!val || val === this._last) return;
    this._last = val;

    $.ajax({
      url: this._url, method: 'POST',
      data: { _token: this._csrf, slug: val, ignore_id: this._ignoreId },
      success: (res) => {
        this._valid = !!res.available;
        if (res.available) {
          this._renderer.clear(this._$input);
          this._renderer.markValid(this._$input);
        } else {
          this._renderer.show(this._$input, 'This slug is already taken — please choose another.');
        }
      },
      error: () => { this._valid = true; },
    });
  }
}

/* ─────────────────────────────────────────────
   RepeatableItemValidator
   FIX: Only validates containers inside the ACTIVE section
───────────────────────────────────────────── */
class RepeatableItemValidator {
  constructor(renderer, fv) {
    this._r  = renderer;
    this._fv = fv;
  }

  validate(type) {
    let ok = true;

    if (type === 'faq') {
      // Only validate if the section is active
      if (!$('#section-faq').hasClass('active')) return true;
      ok = this._validateContainer('#faqContainer', [
        { name: /^faqs\[\d+\]\[question\]$/, label: 'Question', rules: ['required', 'maxlen:400'] },
        { name: /^faqs\[\d+\]\[answer\]$/,   label: 'Answer',   rules: ['required', 'maxlen:5000'] },
      ]);
    }

    if (type === 'service') {
      // Only validate if the section is active
      if (!$('#section-service').hasClass('active')) return true;
      if (!this._validateContainer('#featuresContainer', [
        { name: /^features\[\d+\]\[title\]$/, label: 'Feature Title', rules: ['required', 'maxlen:150'] },
      ])) ok = false;
    }

    if (type === 'team') {
      if (!$('#section-team').hasClass('active')) return true;
      $('#skillsContainer [name]').each((i, el) => {
        const $el = $(el);
        if (/\[level\]$/.test($el.attr('name'))) {
          const res = this._fv.evaluateAll(['range:0:100'], $el.val(), 'Skill Level');
          if (!res.valid) { this._r.show($el, res.message); ok = false; }
        }
      });
    }

    return ok;
  }

  _validateContainer(sel, defs) {
    let ok = true;
    $(sel).find('input,textarea').each((i, el) => {
      const $el = $(el);
      const def = defs.find(d => d.name.test($el.attr('name') || ''));
      if (!def) return;
      const res = this._fv.evaluateAll(def.rules, $el.val(), def.label);
      if (!res.valid) { this._r.show($el, res.message); ok = false; }
    });
    return ok;
  }
}

/* ─────────────────────────────────────────────
   LiveFeedback  (blur / input)
───────────────────────────────────────────── */
class LiveFeedback {
  constructor(renderer, fv) {
    this._r  = renderer;
    this._fv = fv;
  }

  attach($field, rules, label) {
    $field.off('blur.pbv').on('blur.pbv', () => this._run($field, rules, label));
    $field.off('input.pbv change.pbv').on('input.pbv change.pbv', () => {
      if ($field.hasClass(PBV_CONFIG.inputErrorClass)) this._r.clear($field);
    });
  }

  _run($field, rules, label) {
    const val = $field[0].type === 'file' ? ($field[0].files[0] || null) : $field.val();
    const res = this._fv.evaluateAll(rules, val, label);
    if (!res.valid) this._r.show($field, res.message);
    else {
      this._r.clear($field);
      if ((val || '').toString().trim()) this._r.markValid($field);
    }
  }
}

/* ─────────────────────────────────────────────
   PageBuilderValidator  (orchestrator)
───────────────────────────────────────────── */
class PageBuilderValidator {
  constructor(opts = {}) {
    this._$form       = $(`#${opts.formId || 'pbForm'}`);
    this._opts        = opts;
    this._renderer    = new ErrorRenderer();
    this._fv          = new FieldValidator();
    this._typeRules   = new TypeRuleRegistry();
    this._repeatV     = new RepeatableItemValidator(this._renderer, this._fv);
    this._liveFb      = new LiveFeedback(this._renderer, this._fv);
    this._currentType = this._$form.find('#pageTypeInput').val() || 'service';
    this._busy        = false;

    // Slug async validator
    this._slugV = new SlugValidator(
      opts.checkSlugUrl,
      opts.csrfToken,
      this._renderer,
      this._$form.find('#pbSlug')
    );
    this._slugV.init();

    this._attachLiveFeedback();
    this._hookSubmitButtons();
    this._hookImageValidation();
    this._injectStyles();

    // FIX: Re-attach live feedback when page type changes (blade fires this)
    $(document).on('pb:typeChanged', (e, type) => {
      this._currentType = type;
      this._renderer.clearAll(this._$form);
      this._attachLiveFeedback();
    });

    // FIX: When slug is regenerated programmatically (regen button / title change),
    // reset the slug validator so it re-checks the new value.
    $(document).on('pb:slugRegenerated', () => {
      this._slugV.reset();
      // Trigger a fresh uniqueness check for the new value
      setTimeout(() => this._slugV._check(), 100);
    });
  }

  /* ── public ── */
  async validateAll() {
    this._renderer.clearAll(this._$form);
    let ok = true;
    const errors = [];

    // 1. Field rules — only for the CURRENT active type
    for (const { selector, label, rules } of this._typeRules.getRules(this._currentType)) {
      const $f = this._$form.find(selector);
      if (!$f.length) continue;

      // FIX: Skip fields that belong to inactive sections
      const $section = $f.closest('.pb-section');
      if ($section.length && !$section.hasClass('active')) continue;

      const val = $f[0].type === 'file' ? ($f[0].files[0] || null) : $f.val();
      const res = this._fv.evaluateAll(rules, val, label);
      if (!res.valid) {
        this._renderer.show($f, res.message);
        errors.push(res.message);
        ok = false;
      }
    }

    // 2. Repeatable items (scoped to active section inside the method)
    if (!this._repeatV.validate(this._currentType)) ok = false;

    // 3. Slug uniqueness (async result from last check)
    if (!this._slugV.isValid()) {
      const $slug = this._$form.find('#pbSlug');
      const msg = 'This slug is already taken — please choose another.';
      this._renderer.show($slug, msg);
      errors.push(msg);
      ok = false;
    }

    if (!ok) {
      const msg = errors[0] || 'Please fix the highlighted errors before saving.';
      window.pbToast?.(msg, 'var(--red)', 'fas fa-exclamation-circle');

      if (errors.length > 1) {
        setTimeout(() => {
          window.pbToast?.(
            `${errors.length - 1} more error${errors.length > 2 ? 's' : ''} — check highlighted fields.`,
            'var(--yellow)', 'fas fa-exclamation-triangle'
          );
        }, 500);
      }

      this._renderer.scrollToFirst();
    }

    return ok;
  }

  /* ── private ── */

  /**
   * FIX: Intercept the BUTTON CLICKS directly — do NOT intercept native form
   * submit. The blade's submitForm() ends with form.submit() (native), which
   * would trigger the submit event handler and cause an infinite loop.
   *
   * Instead: replace window.submitForm with our validated version, and that
   * validated version calls form.submit() directly (bypassing the event) after
   * validation passes.
   */
  _hookSubmitButtons() {
    const self = this;

    // FIX: Prevent the native form submit from doing anything on its own
    // (submitForm() calls form.submit() directly which does NOT fire jQuery's
    // .on('submit') — but add this guard just in case browser fires it)
    this._$form.on('submit', (e) => {
      e.preventDefault();
      e.stopImmediatePropagation();
    });

    // Wait for the blade's own $(function(){}) to define window.submitForm
    const tryWrap = (attempts = 0) => {
      if (typeof window._pbOriginalSubmit === 'function') return; // already wrapped

      if (typeof window.submitForm === 'function') {
        window._pbOriginalSubmit = window.submitForm;

        // Replace with validated version
        window.submitForm = async function (status) {
          if (self._busy) return;
          await self._doSubmit(status);
        };
      } else if (attempts < 30) {
        setTimeout(() => tryWrap(attempts + 1), 100);
      }
    };

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => tryWrap());
    } else {
      setTimeout(() => tryWrap(), 0);
    }
  }

  async _doSubmit(status) {
    if (this._busy) return;

    const ok = await this.validateAll();
    if (!ok) return; // errors already shown — stop here

    this._busy = true;
    this._setLoading(true);

    try {
      // Call the original submitForm which sets status + syncs Quill + calls form.submit()
      window._pbOriginalSubmit(status);
    } catch (err) {
      this._busy = false;
      this._setLoading(false);
      console.error('PageBuilder submit error:', err);
    }
  }

  _setLoading(on) {
    $('#btnPbPublish, #btnPbDraft, #sidebarPublish, #sidebarDraft').each(function () {
      if (on) {
        $(this).prop('disabled', true).data('orig', $(this).html())
          .html('<i class="fas fa-spinner fa-spin"></i> Saving…');
      } else {
        $(this).prop('disabled', false).html($(this).data('orig') || $(this).html());
      }
    });
  }

  _attachLiveFeedback() {
    for (const { selector, label, rules } of this._typeRules.getRules(this._currentType)) {
      const $f = this._$form.find(selector);
      if ($f.length) this._liveFb.attach($f, rules, label);
    }
  }

  _hookImageValidation() {
    this._$form.find('#featuredImgInput').on('change', (e) => {
      const file = e.target.files[0];
      if (!file) return;
      const res = this._fv.evaluate('image', file, 'Featured Image');
      if (!res.valid) {
        window.pbToast?.(res.message, 'var(--red)', 'fas fa-exclamation-circle');
        this._renderer.show($(e.target), res.message);
        e.target.value = '';
      } else {
        this._renderer.clear($(e.target));
      }
    });
  }

  _injectStyles() {
    if ($('#pb-validator-styles').length) return;
    $('head').append(`<style id="pb-validator-styles">
      .${PBV_CONFIG.errorClass} {
        display:flex; align-items:center; gap:5px;
        margin-top:5px; font-size:.74rem; font-weight:600;
        color:var(--red,#ff4d6d);
        animation: pbv-in .15s ease;
      }
      .${PBV_CONFIG.errorClass} i { font-size:.7rem; flex-shrink:0; }
      @keyframes pbv-in {
        from { opacity:0; transform:translateY(-4px); }
        to   { opacity:1; transform:none; }
      }
      .${PBV_CONFIG.inputErrorClass} {
        border-color:var(--red,#ff4d6d)!important;
        box-shadow:0 0 0 3px rgba(255,77,109,.12)!important;
      }
      .${PBV_CONFIG.successClass} { border-color:var(--green,#00c896)!important; }
      input:invalid, textarea:invalid, select:invalid { box-shadow:none; }
    </style>`);
  }
}

window.PageBuilderValidator = PageBuilderValidator;