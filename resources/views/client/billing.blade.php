@extends('layouts.master')
@section('title', 'Billing & Agreement — KawachTech Client Portal')

@section('content')
<div style="font-family:'Sora',sans-serif;padding:28px 24px;max-width:1200px;margin:0 auto;">

  <div style="font-size:.72rem;color:#7a82a8;margin-bottom:5px;">
    <a href="{{ route('client.portal') }}" style="color:#2563eb;text-decoration:none;font-weight:600;">My Projects</a>
    › Billing & Agreement
  </div>
  <h1 style="font-size:1.4rem;font-weight:800;color:#0c0f1a;letter-spacing:-.3px;margin-bottom:22px;">
    <i class="fas fa-handshake" style="color:#2563eb;font-size:1.1rem;margin-right:8px;"></i>
    Billing & Agreement
  </h1>

  <div style="background:#fff;border-radius:12px;border:1px solid #e2e6f3;overflow:hidden;box-shadow:0 2px 12px rgba(37,99,235,.07);">
    <table style="width:100%;border-collapse:collapse;font-size:.82rem;">
      <thead>
        <tr style="background:#f5f6fa;border-bottom:1px solid #e2e6f3;">
          <th style="padding:11px 16px;text-align:left;font-size:.68rem;font-weight:700;color:#7a82a8;text-transform:uppercase;letter-spacing:.4px;">Invoice</th>
          <th style="padding:11px 16px;text-align:left;font-size:.68rem;font-weight:700;color:#7a82a8;text-transform:uppercase;letter-spacing:.4px;">Project</th>
          <th style="padding:11px 16px;text-align:center;font-size:.68rem;font-weight:700;color:#7a82a8;text-transform:uppercase;letter-spacing:.4px;">Status</th>
          <th style="padding:11px 16px;text-align:right;font-size:.68rem;font-weight:700;color:#7a82a8;text-transform:uppercase;letter-spacing:.4px;">Grand Total</th>
          <th style="padding:11px 16px;text-align:center;font-size:.68rem;font-weight:700;color:#7a82a8;text-transform:uppercase;letter-spacing:.4px;">Date</th>
          <th style="padding:11px 16px;text-align:center;font-size:.68rem;font-weight:700;color:#7a82a8;text-transform:uppercase;letter-spacing:.4px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($agreements as $ag)
        @php
          $statusColors = [
            'draft'     => ['#f5f6fa','#7a82a8'],
            'sent'      => ['#eff6ff','#2563eb'],
            'signed'    => ['#ecfdf5','#059669'],
            'cancelled' => ['#fef2f2','#dc2626'],
          ];
          [$sbg, $sc] = $statusColors[$ag->status] ?? ['#f5f6fa','#7a82a8'];
        @endphp
        <tr style="border-bottom:1px solid #f0f2f8;">
          <td style="padding:12px 16px;">
            <div style="font-weight:700;color:#0c0f1a;">{{ $ag->invoice_no }}</div>
            <div style="font-size:.68rem;color:#7a82a8;">{{ $ag->agreement_date->format('d M Y') }}</div>
          </td>
          <td style="padding:12px 16px;">
            <div style="color:#3a3f5c;">{{ $ag->project_name }}</div>
            <div style="font-size:.68rem;color:#7a82a8;">{{ $ag->project_type }} · {{ $ag->duration_months }}mo</div>
          </td>
          <td style="padding:12px 16px;text-align:center;">
            <span style="display:inline-block;padding:3px 11px;border-radius:20px;font-size:.67rem;font-weight:700;background:{{ $sbg }};color:{{ $sc }};">
              {{ strtoupper($ag->status) }}
            </span>
          </td>
          <td style="padding:12px 16px;text-align:right;font-weight:800;color:#2563eb;font-family:'DM Mono',monospace;font-size:.84rem;">
            {{ $ag->grand_total_formatted }}
          </td>
          <td style="padding:12px 16px;text-align:center;font-size:.72rem;color:#7a82a8;">
            {{ $ag->created_at->format('d M Y') }}
          </td>
          <td style="padding:12px 16px;text-align:center;">
            <div style="display:flex;gap:5px;justify-content:center;align-items:center;">
              <a href="{{ route('client.billing.show', $ag->uuid) }}"
                 style="padding:5px 10px;border-radius:6px;border:1.5px solid #e2e6f3;background:#fff;font-size:.72rem;font-weight:600;color:#3a3f5c;text-decoration:none;" title="View">
                <i class="fas fa-eye"></i>
              </a>
              <a href="{{ route('client.billing.pdf', $ag->uuid) }}"
                 style="padding:5px 10px;border-radius:6px;border:1.5px solid #e2e6f3;background:#fff;font-size:.72rem;font-weight:600;color:#059669;text-decoration:none;" title="Download PDF">
                <i class="fas fa-file-pdf"></i>
              </a>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align:center;padding:48px;color:#7a82a8;font-size:.9rem;">
            <i class="fas fa-file-contract" style="font-size:2.5rem;color:#e2e6f3;display:block;margin-bottom:12px;"></i>
            No agreements on file yet.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>
@endsection
