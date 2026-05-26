<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title>Agreement — {{ $agreement->invoice_no }}</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap');

  @page {
    margin: 18mm 16mm 18mm 16mm;
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --navy:      #0a0e1f;
    --navy-mid:  #111829;
    --navy-soft: #1a2340;
    --blue:      #1d6ef5;
    --blue-bright:#3b82f6;
    --cyan:      #06b6d4;
    --green:     #10b981;
    --gold:      #f59e0b;
    --text:      #e8ecf5;
    --muted:     #6b7da8;
    --border:    rgba(59,130,246,0.18);
    --glass:     rgba(255,255,255,0.04);
  }

  body {
    font-family: 'Outfit', Arial, sans-serif;
    font-size: 8.5pt;
    color: #1a1a2e;
    background: #fff;
    line-height: 1.6;
  }

  /* ══════════════════════════════════════
     HEADER
  ══════════════════════════════════════ */
  .doc-header {
    background: linear-gradient(135deg, #0a0e1f 0%, #111829 50%, #0d1730 100%);
    border-radius: 10pt;
    padding: 20pt 22pt 18pt;
    margin-bottom: 16pt;
    position: relative;
    overflow: hidden;
  }

  /* decorative grid lines */
  .doc-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
      linear-gradient(rgba(29,110,245,0.07) 1px, transparent 1px),
      linear-gradient(90deg, rgba(29,110,245,0.07) 1px, transparent 1px);
    background-size: 24pt 24pt;
    border-radius: 10pt;
  }

  /* glowing orb */
  .doc-header::after {
    content: '';
    position: absolute;
    top: -30pt;
    right: 60pt;
    width: 120pt;
    height: 120pt;
    background: radial-gradient(circle, rgba(29,110,245,0.25) 0%, transparent 70%);
    border-radius: 50%;
  }

  .header-inner { position: relative; z-index: 2; }
  .header-table { width: 100%; border-collapse: collapse; }
  .header-table td { vertical-align: top; }

  .logo-mark {
    display: inline-block;
    width: 9pt; height: 9pt;
    background: #1d6ef5;
    border-radius: 2pt;
    margin-right: 3pt;
    vertical-align: middle;
    position: relative;
    top: -1pt;
  }
  .logo-mark-2 {
    display: inline-block;
    width: 9pt; height: 9pt;
    background: #06b6d4;
    border-radius: 2pt;
    margin-right: 6pt;
    vertical-align: middle;
    position: relative;
    top: -1pt;
    margin-left: -5pt;
  }

  .company-name {
    font-size: 18pt;
    font-weight: 800;
    letter-spacing: -0.3pt;
    color: #fff;
    line-height: 1;
  }
  .company-name span { color: #1d6ef5; }
  .company-sub {
    font-size: 5pt;
    letter-spacing: 4pt;
    color: #4a6fa8;
    font-weight: 500;
    margin-top: 2pt;
    margin-left: 1pt;
    text-transform: uppercase;
  }

  .company-contact {
    margin-top: 9pt;
    font-size: 6.5pt;
    color: #5a7aa0;
    line-height: 2;
    font-family: 'JetBrains Mono', monospace;
  }
  .company-contact span { color: #8aa8cc; }

  /* right side badge */
  .badge-col { text-align: right; }

  .doc-type-badge {
    display: inline-block;
    background: linear-gradient(135deg, #1d6ef5, #06b6d4);
    color: #fff;
    font-size: 6pt;
    font-weight: 700;
    letter-spacing: 1.5pt;
    text-transform: uppercase;
    padding: 4pt 14pt;
    border-radius: 20pt;
    margin-bottom: 10pt;
  }

  .meta-grid {
    display: inline-block;
    text-align: right;
  }
  .meta-row {
    font-size: 7pt;
    color: #4a6fa8;
    line-height: 2.2;
    font-family: 'JetBrains Mono', monospace;
  }
  .meta-row strong {
    color: #8aa8cc;
    font-weight: 600;
    margin-left: 8pt;
  }
  .invoice-chip {
    display: inline-block;
    margin-top: 6pt;
    background: rgba(29,110,245,0.15);
    border: 1pt solid rgba(29,110,245,0.35);
    border-radius: 4pt;
    padding: 3pt 10pt;
    font-size: 7.5pt;
    font-weight: 700;
    color: #60a5fa;
    font-family: 'JetBrains Mono', monospace;
    letter-spacing: 1pt;
  }

  /* ══════════════════════════════════════
     SECTION LABELS
  ══════════════════════════════════════ */
  .sec-lbl {
    font-size: 5.5pt;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5pt;
    color: #1d6ef5;
    margin-bottom: 8pt;
    display: flex;
    align-items: center;
    gap: 5pt;
  }
  .sec-lbl::before {
    content: '';
    display: inline-block;
    width: 12pt;
    height: 2pt;
    background: linear-gradient(90deg, #1d6ef5, #06b6d4);
    border-radius: 1pt;
  }
  .sec-lbl::after {
    content: '';
    display: inline-block;
    flex: 1;
    height: .5pt;
    background: linear-gradient(90deg, rgba(29,110,245,0.25), transparent);
    margin-left: 4pt;
  }

  /* ══════════════════════════════════════
     PARTIES
  ══════════════════════════════════════ */
  .parties-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 8pt 0;
    margin-bottom: 14pt;
  }
  .party-box {
    width: 50%;
    background: #f5f7ff;
    border: 1pt solid #dde3f7;
    border-top: 3pt solid #1d6ef5;
    border-radius: 8pt;
    padding: 12pt;
    vertical-align: top;
    position: relative;
  }
  .party-box.client { border-top-color: #06b6d4; }

  .party-chip {
    display: inline-block;
    font-size: 5pt;
    font-weight: 700;
    letter-spacing: 1pt;
    text-transform: uppercase;
    padding: 2pt 7pt;
    border-radius: 10pt;
    margin-bottom: 7pt;
    background: rgba(29,110,245,0.1);
    color: #1d6ef5;
  }
  .party-box.client .party-chip {
    background: rgba(6,182,212,0.1);
    color: #0891b2;
  }
  .party-name  { font-size: 10pt; font-weight: 800; color: #0a0e1f; line-height: 1.2; }
  .party-meta  { font-size: 6.5pt; color: #6b7a99; margin-top: 5pt; line-height: 1.9; }

  /* ══════════════════════════════════════
     PROJECT BOX
  ══════════════════════════════════════ */
  .project-box {
    background: linear-gradient(135deg, #0a0e1f, #0d1a0d);
    border-radius: 8pt;
    padding: 14pt 16pt;
    margin-bottom: 14pt;
    position: relative;
    overflow: hidden;
  }
  .project-box::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
      linear-gradient(rgba(16,185,129,0.06) 1px, transparent 1px),
      linear-gradient(90deg, rgba(16,185,129,0.06) 1px, transparent 1px);
    background-size: 20pt 20pt;
  }
  .project-box .sec-lbl { color: #10b981; }
  .project-box .sec-lbl::before { background: linear-gradient(90deg, #10b981, #06b6d4); }
  .project-box .sec-lbl::after  { background: linear-gradient(90deg, rgba(16,185,129,0.25), transparent); }

  .proj-grid { width: 100%; border-collapse: collapse; position: relative; z-index: 2; }
  .proj-cell { width: 33%; vertical-align: top; padding: 5pt 10pt 5pt 0; }
  .proj-lbl  { font-size: 5.5pt; color: #4a7a5a; margin-bottom: 2pt; letter-spacing: .5pt; text-transform: uppercase; }
  .proj-val  { font-size: 8pt; font-weight: 700; color: #d1fae5; }

  .scope-row { border-top: .5pt solid rgba(16,185,129,0.2); margin-top: 8pt; padding-top: 8pt; position: relative; z-index: 2; }
  .scope-lbl { font-size: 5.5pt; color: #4a7a5a; letter-spacing: .5pt; text-transform: uppercase; margin-bottom: 3pt; }
  .scope-val { font-size: 7pt; color: #a7f3d0; line-height: 1.7; }

  /* ══════════════════════════════════════
     RATE TABLE
  ══════════════════════════════════════ */
  .rate-table { width: 100%; border-collapse: collapse; margin-bottom: 14pt; font-size: 7.5pt; border-radius: 8pt; overflow: hidden; }
  .rate-table thead tr { background: linear-gradient(135deg, #0a0e1f, #111829); }
  .rate-table thead th {
    padding: 8pt 9pt;
    text-align: left;
    font-size: 5.5pt;
    letter-spacing: 1pt;
    font-weight: 700;
    color: #4a6fa8;
    text-transform: uppercase;
  }
  .rate-table thead th:first-child { border-radius: 8pt 0 0 0; }
  .rate-table thead th:last-child  { border-radius: 0 8pt 0 0; }

  .rate-table tbody td {
    padding: 7pt 9pt;
    border-bottom: .5pt solid #edf0fa;
    color: #1a1a2e;
  }
  .rate-table tbody tr:nth-child(even) td { background: #f8faff; }
  .rate-table tbody tr:last-child td { border-bottom: none; }

  .dev-name {
    font-weight: 700;
    color: #0a0e1f;
    font-size: 8pt;
  }
  .dev-icon { font-size: 9pt; margin-right: 3pt; }

  .rate-table tfoot { border-top: 2pt solid #1d6ef5; }
  .rate-table tfoot td { padding: 6pt 9pt; }
  .tr-sub  td { background: #f0f4fe; font-size: 7.5pt; }
  .tr-total td {
    background: linear-gradient(135deg, #0a0e1f, #0d1630);
    color: #fff;
    font-weight: 800;
    font-size: 9pt;
  }
  .tr-total .amount { color: #60a5fa; font-size: 10pt; font-family: 'JetBrains Mono', monospace; }

  .text-right  { text-align: right; }
  .text-center { text-align: center; }
  .blue        { color: #1d6ef5; font-weight: 700; }
  .bold        { font-weight: 700; }
  .mono        { font-family: 'JetBrains Mono', monospace; }

  /* ══════════════════════════════════════
     SUMMARY
  ══════════════════════════════════════ */
  .summary-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 8pt 0;
    margin-bottom: 14pt;
  }
  .summary-left {
    width: 52%;
    background: #f5f7ff;
    border: 1pt solid #dde3f7;
    border-radius: 8pt;
    padding: 12pt;
    vertical-align: top;
  }
  .summary-right {
    width: 48%;
    background: linear-gradient(135deg, #0a0e1f 0%, #0d1630 60%, #071224 100%);
    border-radius: 8pt;
    padding: 16pt;
    vertical-align: middle;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .summary-right::before {
    content: '';
    position: absolute;
    top: -20pt; left: -20pt;
    width: 80pt; height: 80pt;
    background: radial-gradient(circle, rgba(29,110,245,0.3) 0%, transparent 70%);
  }
  .summary-right::after {
    content: '';
    position: absolute;
    bottom: -15pt; right: -15pt;
    width: 70pt; height: 70pt;
    background: radial-gradient(circle, rgba(6,182,212,0.2) 0%, transparent 70%);
  }

  .pay-row {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 3pt;
  }
  .pay-lbl { font-size: 7pt; color: #6b7a99; }
  .pay-val { font-size: 7pt; font-weight: 700; color: #0a0e1f; text-align: right; font-family: 'JetBrains Mono', monospace; }
  .pay-divider { border: none; border-top: .5pt solid #dde3f7; margin: 5pt 0; }

  .grand-inner { position: relative; z-index: 2; }
  .grand-eyebrow { font-size: 5pt; letter-spacing: 2pt; text-transform: uppercase; color: #3b5ea0; margin-bottom: 5pt; font-weight: 600; }
  .grand-amount  { font-size: 22pt; font-weight: 900; color: #fff; line-height: 1; font-family: 'JetBrains Mono', monospace; }
  .grand-amount span { font-size: 13pt; color: #60a5fa; vertical-align: super; margin-right: 1pt; }
  .grand-sub {
    margin-top: 6pt;
    font-size: 5.5pt;
    color: #3b5ea0;
    letter-spacing: .5pt;
  }
  .grand-pills { margin-top: 10pt; display: flex; justify-content: center; gap: 5pt; flex-wrap: wrap; }
  .grand-pill {
    display: inline-block;
    background: rgba(29,110,245,0.15);
    border: .5pt solid rgba(29,110,245,0.3);
    border-radius: 10pt;
    padding: 2pt 7pt;
    font-size: 5.5pt;
    color: #60a5fa;
    font-weight: 600;
    font-family: 'JetBrains Mono', monospace;
  }

  /* ══════════════════════════════════════
     TERMS
  ══════════════════════════════════════ */
  .terms-outer { margin-bottom: 14pt; }
  .terms-box {
    background: #f8faff;
    border: 1pt solid #dde3f7;
    border-left: 3pt solid #1d6ef5;
    border-radius: 0 8pt 8pt 0;
    padding: 12pt 14pt;
    font-size: 7pt;
    line-height: 1.8;
    color: #5a6888;
  }
  .terms-box h4 {
    font-size: 7pt;
    font-weight: 800;
    color: #0a0e1f;
    margin-bottom: 3pt;
    margin-top: 10pt;
    text-transform: uppercase;
    letter-spacing: .5pt;
    font-size: 6.5pt;
    display: flex;
    align-items: center;
    gap: 4pt;
  }
  .terms-box h4::before {
    content: '';
    display: inline-block;
    width: 4pt; height: 4pt;
    background: #1d6ef5;
    border-radius: 1pt;
    flex-shrink: 0;
  }
  .terms-box h4:first-child { margin-top: 0; }
  .terms-box p { margin-bottom: 2pt; }

  .custom-box {
    background: #fffbeb;
    border: 1pt solid #fde68a;
    border-left: 3pt solid #f59e0b;
    border-radius: 0 8pt 8pt 0;
    padding: 11pt 14pt;
    margin-bottom: 14pt;
    font-size: 7pt;
    line-height: 1.7;
    color: #78350f;
  }
  .custom-ttl {
    font-size: 5.5pt;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1pt;
    color: #b45309;
    margin-bottom: 5pt;
  }

  /* ══════════════════════════════════════
     SIGNATURES
  ══════════════════════════════════════ */
  .sig-section {
    margin-top: 18pt;
    padding-top: 14pt;
    border-top: 1pt solid #dde3f7;
  }
  .sig-table { width: 100%; border-collapse: separate; border-spacing: 14pt 0; }
  .sig-cell  { width: 50%; vertical-align: bottom; }

  .sig-card {
    background: #f5f7ff;
    border: 1pt solid #dde3f7;
    border-radius: 8pt;
    padding: 12pt;
  }
  .sig-chip {
    display: inline-block;
    font-size: 5pt;
    font-weight: 700;
    letter-spacing: 1pt;
    text-transform: uppercase;
    color: #1d6ef5;
    background: rgba(29,110,245,0.08);
    padding: 2pt 7pt;
    border-radius: 10pt;
    margin-bottom: 10pt;
  }
  .sig-line {
    border-bottom: 1.5pt solid #c5cfe8;
    height: 46pt;
    border-radius: 4pt 4pt 0 0;
    background: #fff;
    position: relative;
    overflow: hidden;
  }
  .sig-img { max-height: 44pt; position: absolute; bottom: 2pt; left: 6pt; }
  .sig-name { font-size: 8.5pt; font-weight: 800; color: #0a0e1f; margin-top: 6pt; }
  .sig-role { font-size: 6.5pt; color: #6b7a99; margin-top: 1pt; }
  .sig-date { font-size: 6pt; color: #9ba8c0; margin-top: 3pt; font-family: 'JetBrains Mono', monospace; }

  /* ══════════════════════════════════════
     FOOTER
  ══════════════════════════════════════ */
  .doc-footer {
    margin-top: 16pt;
    padding-top: 10pt;
    border-top: .5pt solid #dde3f7;
    text-align: center;
    font-size: 6pt;
    color: #9ba8c0;
    line-height: 2;
    font-family: 'JetBrains Mono', monospace;
  }
  .doc-footer strong { color: #6b7da8; }

  .pb-avoid { page-break-inside: avoid; }
</style>
</head>
<body>

{{-- ══ HEADER ══ --}}
<div class="doc-header">
  <div class="header-inner">
    <table class="header-table">
      <tr>
        <td>
          <div class="company-name">
            Kawach<span>TECH</span>
          </div>
          <div class="company-sub">S O L U T I O N S</div>
          <div class="company-contact">
            <span>contracts@kawachtech.com</span><br>
            <span>www.kawachtech.com</span><br>
            <span>India</span>
          </div>
        </td>
        <td class="badge-col">
          <div class="doc-type-badge">Software Development Agreement</div>
          <div style="text-align:right;">
            <div class="meta-row">Invoice No: <strong>{{ $agreement->invoice_no }}</strong></div>
            <div class="meta-row">Date: <strong>{{ $agreement->agreement_date->format('d M Y') }}</strong></div>
            <div class="meta-row">Contract Start: <strong>{{ $agreement->contract_start->format('d M Y') }}</strong></div>
          </div>
        </td>
      </tr>
    </table>
  </div>
</div>

{{-- ══ PARTIES ══ --}}
<div class="sec-lbl">Parties to this Agreement</div>
<table class="parties-table">
  <tr>
    <td class="party-box">
      <div class="party-chip">Service Provider</div>
      <div class="party-name">Kawach Technology Pvt Ltd</div>
      <div class="party-meta">
        India<br>
        contracts@kawachtech.com<br>
        www.kawachtech.com
      </div>
    </td>
    <td class="party-box client">
      <div class="party-chip">Client / Service Recipient</div>
      <div class="party-name">{{ $agreement->client_name }}</div>
      <div class="party-meta">
        @if($agreement->client_contact){{ $agreement->client_contact }}<br>@endif
        @if($agreement->client_email){{ $agreement->client_email }}<br>@endif
        @if($agreement->client_phone){{ $agreement->client_phone }}<br>@endif
        @if($agreement->client_address){{ $agreement->client_address }}@endif
      </div>
    </td>
  </tr>
</table>

{{-- ══ PROJECT INFO ══ --}}
<div class="project-box pb-avoid">
  <div class="sec-lbl">Project Information</div>
  <table class="proj-grid">
    <tr>
      <td class="proj-cell">
        <div class="proj-lbl">Project Name</div>
        <div class="proj-val">{{ $agreement->project_name ?: '—' }}</div>
      </td>
      <td class="proj-cell">
        <div class="proj-lbl">Project Type</div>
        <div class="proj-val">{{ $agreement->project_type ?: '—' }}</div>
      </td>
      <td class="proj-cell">
        <div class="proj-lbl">Duration</div>
        <div class="proj-val">{{ $agreement->duration_months }} Month{{ $agreement->duration_months > 1 ? 's' : '' }}</div>
      </td>
    </tr>
    <tr>
      <td class="proj-cell">
        <div class="proj-lbl">Working Days</div>
        <div class="proj-val">5 days/wk · {{ $agreement->monthly_days }} days/mo</div>
      </td>
      <td class="proj-cell">
        <div class="proj-lbl">Daily Hours</div>
        <div class="proj-val">{{ $agreement->daily_hours }} hrs/day</div>
      </td>
      <td class="proj-cell">
        <div class="proj-lbl">Monthly Hours/Dev</div>
        <div class="proj-val">{{ $agreement->monthly_days * $agreement->daily_hours }} hrs</div>
      </td>
    </tr>
  </table>
  @if($agreement->project_scope)
  <div class="scope-row">
    <div class="scope-lbl">Scope of Work</div>
    <div class="scope-val">{{ $agreement->project_scope }}</div>
  </div>
  @endif
</div>

{{-- ══ RATE SCHEDULE ══ --}}
<div class="sec-lbl">Rate Schedule &amp; Monthly Billing</div>
<table class="rate-table pb-avoid">
  <thead>
    <tr>
      <th>#</th>
      <th>Developer / Role</th>
      <th>Technology</th>
      <th class="text-center">Qty</th>
      <th class="text-center">Hourly Rate</th>
      <th class="text-center">Hours/Mo</th>
      <th class="text-right">Monthly Cost</th>
    </tr>
  </thead>
  <tbody>
    @foreach($agreement->developers as $i => $dev)
    <tr>
      <td class="muted mono" style="color:#9ba8c0;font-size:7pt;">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</td>
      <td>
        <span class="dev-icon">{{ $dev->tech_icon }}</span>
        <span class="dev-name">{{ $dev->developer_label }}</span>
      </td>
      <td style="color:#5a6888;">{{ $dev->technology }}</td>
      <td class="text-center bold">{{ $dev->quantity }}</td>
      <td class="text-center blue mono">{{ $agreement->currency_symbol }}{{ number_format($dev->hourly_rate_cents / 100, 2) }}/hr</td>
      <td class="text-center" style="color:#5a6888;">{{ $dev->monthly_hours }}</td>
      <td class="text-right bold blue mono">{{ $agreement->formatMoney($dev->monthly_cost_cents) }}</td>
    </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr class="tr-sub">
      <td colspan="6" class="text-right" style="color:#5a6888;font-size:7pt;">Subtotal (pre-tax)</td>
      <td class="text-right bold mono">{{ $agreement->formatMoney($agreement->subtotal_cents) }}</td>
    </tr>
    <tr class="tr-sub">
      <td colspan="6" class="text-right" style="color:#5a6888;font-size:7pt;">GST / Tax ({{ number_format($agreement->tax_pct, 0) }}%)</td>
      <td class="text-right bold mono">{{ $agreement->formatMoney($agreement->tax_cents) }}</td>
    </tr>
    <tr class="tr-total">
      <td colspan="6" class="text-right" style="color:#6b8cc4;font-size:6pt;letter-spacing:1pt;text-transform:uppercase;font-weight:600;">Monthly Total</td>
      <td class="text-right amount">{{ $agreement->formatMoney($agreement->monthly_total_cents) }}</td>
    </tr>
  </tfoot>
</table>

{{-- ══ CONTRACT SUMMARY ══ --}}
<table class="summary-table pb-avoid">
  <tr>
    <td class="summary-left">
      <div class="sec-lbl">Payment Schedule</div>
      @php
        $payLines = [
          'Payment Cycle'          => ucfirst($agreement->payment_cycle),
          'Payment Due'            => 'Net ' . $agreement->payment_due_days . ' days',
          'Advance (' . number_format($agreement->advance_pct, 0) . '%)' => $agreement->advance_formatted,
          'Balance Month 1'        => $agreement->formatMoney($agreement->monthly_total_cents - $agreement->advance_cents),
          'Late Fee'               => number_format($agreement->late_fee_pct, 2) . '%/month',
          'Payment Methods'        => $agreement->payment_methods,
        ];
      @endphp
      @foreach($payLines as $lbl => $val)
        <table class="pay-row">
          <tr>
            <td class="pay-lbl">{{ $lbl }}</td>
            <td class="pay-val">{{ $val }}</td>
          </tr>
        </table>
      @endforeach
    </td>
    <td class="summary-right">
      <div class="grand-inner">
        <div class="grand-eyebrow">Contract Grand Total</div>
        <div class="grand-amount">
          <span>{{ $agreement->currency_symbol }}</span>{{ preg_replace('/^[^\d]*/', '', $agreement->grand_total_formatted) }}
        </div>
        <div class="grand-sub">incl. {{ number_format($agreement->tax_pct, 0) }}% tax</div>
        <div class="grand-pills">
          <span class="grand-pill">{{ $agreement->duration_months }}mo</span>
          <span class="grand-pill">{{ $agreement->developers->count() }} dev{{ $agreement->developers->count() !== 1 ? 's' : '' }}</span>
          <span class="grand-pill">{{ $agreement->monthly_total_formatted }}/mo</span>
          <span class="grand-pill">Adv: {{ $agreement->advance_formatted }}</span>
        </div>
      </div>
    </td>
  </tr>
</table>

{{-- ══ TERMS ══ --}}
<div class="sec-lbl">Terms &amp; Conditions</div>
<div class="terms-outer pb-avoid">
  <div class="terms-box">
    <h4>1. Engagement &amp; Scope of Work</h4>
    <p>Kawach Technology Private Limited ("Company") agrees to provide dedicated software development resources to the Client as specified in this Agreement. The scope of work shall be defined by mutual agreement and may be updated via written change requests.</p>

    <h4>2. Developer Resources</h4>
    <p>The Client shall have access to the agreed number of dedicated developers during standard business hours (Monday–Friday, excluding public holidays). Developers work 5 days per week (22 working days/month approximately).</p>

    <h4>3. Billing &amp; Payment</h4>
    <p>Billing is calculated on an hourly basis per developer at the rates specified in the Rate Schedule. Invoices are issued at the beginning of each billing cycle. Payment is due within Net {{ $agreement->payment_due_days }} days. A late payment fee of {{ number_format($agreement->late_fee_pct, 2) }}% per month applies on overdue amounts. An advance payment of {{ number_format($agreement->advance_pct, 0) }}% of the first month's billing is required before work commences.</p>

    <h4>4. Intellectual Property</h4>
    <p>Upon full payment of all dues, all work product, code, designs, and deliverables created by the Company's developers shall be assigned exclusively to the Client. The Company retains no rights to the deliverables after full payment.</p>

    <h4>5. Confidentiality &amp; Non-Disclosure</h4>
    <p>Both parties agree to maintain strict confidentiality regarding all proprietary information, business processes, and trade secrets exchanged during and for 3 years after the term of this Agreement.</p>

    <h4>6. Non-Solicitation</h4>
    <p>During the term and for 12 months thereafter, the Client agrees not to directly recruit any developer of the Company who worked on the Client's project without prior written consent. A finder's fee of 3 months' billing equivalent applies if this clause is violated.</p>

    <h4>7. Termination</h4>
    <p>Either party may terminate with 30 days' written notice. The Client pays for all work completed to the termination date. Immediate termination is permissible in cases of material breach or insolvency.</p>

    <h4>8. Liability Limitation</h4>
    <p>The Company's total liability shall not exceed fees paid in the 3 months preceding the claim. The Company shall not be liable for indirect, consequential, or punitive damages.</p>

    <h4>9. Dispute Resolution</h4>
    <p>Disputes shall first be resolved through good-faith negotiation, then binding arbitration under Indian Arbitration &amp; Conciliation Act, 1996. Seat of arbitration: India. Language: English.</p>

    <h4>10. Governing Law</h4>
    <p>This Agreement is governed by and construed in accordance with the laws of India.</p>

    <h4>11. Force Majeure</h4>
    <p>Neither party shall be liable for delays caused by circumstances beyond their reasonable control. Affected party shall notify the other within 48 hours.</p>

    <h4>12. Entire Agreement</h4>
    <p>This Agreement constitutes the entire agreement between the parties and supersedes all prior negotiations. Amendments must be in writing and signed by both parties.</p>
  </div>
</div>

@if($agreement->custom_clauses)
<div class="custom-box">
  <div class="custom-ttl">⚡ Additional / Custom Clauses</div>
  {!! nl2br(e($agreement->custom_clauses)) !!}
</div>
@endif

{{-- ══ SIGNATURES ══ --}}
<div class="sig-section pb-avoid">
  <div class="sec-lbl">Authorized Signatures</div>
  <table class="sig-table">
    <tr>
      <td class="sig-cell">
        <div class="sig-card">
          <div class="sig-chip">Service Provider</div>
          <div class="sig-line">
            @if($agreement->company_signature)
              <img src="{{ $agreement->company_signature }}" class="sig-img" alt="Company Signature"/>
            @endif
          </div>
          <div class="sig-name">Kawach Technology Pvt Ltd</div>
          <div class="sig-role">Authorized Signatory</div>
          <div class="sig-date">{{ $agreement->agreement_date->format('d M Y') }}</div>
        </div>
      </td>
      <td class="sig-cell">
        <div class="sig-card">
          <div class="sig-chip" style="background:rgba(6,182,212,0.08);color:#0891b2;">Client</div>
          <div class="sig-line">
            @if($agreement->client_signature)
              <img src="{{ $agreement->client_signature }}" class="sig-img" alt="Client Signature"/>
            @endif
          </div>
          <div class="sig-name">{{ $agreement->client_contact }}</div>
          <div class="sig-role">{{ $agreement->client_name }}</div>
          <div class="sig-date">Date: ___________________________</div>
        </div>
      </td>
    </tr>
  </table>
</div>

{{-- ══ FOOTER ══ --}}
<div class="doc-footer">
  <strong>Kawach Technology Pvt Ltd</strong> · www.kawachtech.com · contracts@kawachtech.com<br>
  This document is legally binding upon signature by both parties.<br>
  Generated {{ now()->format('d F Y') }} · Invoice #{{ $agreement->invoice_no }}
</div>

</body>
</html>