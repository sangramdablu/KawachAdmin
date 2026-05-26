@extends('layouts.master')
@section('title', 'Client Agreements — Kawach Technology')

@section('content')
<div style="font-family:'Sora',sans-serif;padding:28px 24px;max-width:1380px;margin:0 auto;">

  {{-- Header --}}
  <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
    <div>
      <div style="font-size:.72rem;color:#7a82a8;margin-bottom:5px;">
        <a href="{{ route('dashboard') }}" style="color:#2563eb;text-decoration:none;font-weight:600;">Dashboard</a>
        › Agreements
      </div>
      <h1 style="font-size:1.4rem;font-weight:800;color:#0c0f1a;letter-spacing:-.3px;">
        <i class="fas fa-file-contract" style="color:#2563eb;font-size:1.1rem;margin-right:8px;"></i>
        Client Agreements
      </h1>
    </div>
    <a href="{{ route('billing.create') }}"
       style="display:inline-flex;align-items:center;gap:7px;padding:9px 20px;background:#2563eb;color:#fff;border-radius:8px;font-size:.83rem;font-weight:700;text-decoration:none;box-shadow:0 2px 12px rgba(37,99,235,.3);">
      <i class="fas fa-plus"></i> New Agreement
    </a>
  </div>

  @if(session('success'))
  <div style="background:#ecfdf5;border:1px solid #6ee7b7;border-radius:8px;padding:11px 16px;margin-bottom:16px;font-size:.82rem;color:#059669;display:flex;align-items:center;gap:8px;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
  </div>
  @endif

  {{-- Table --}}
  <div style="background:#fff;border-radius:12px;border:1px solid #e2e6f3;overflow:hidden;box-shadow:0 2px 12px rgba(37,99,235,.07);">
    <table style="width:100%;border-collapse:collapse;font-size:.82rem;">
      <thead>
        <tr style="background:#f5f6fa;border-bottom:1px solid #e2e6f3;">
          <th style="padding:11px 16px;text-align:left;font-size:.68rem;font-weight:700;color:#7a82a8;text-transform:uppercase;letter-spacing:.4px;">Invoice</th>
          <th style="padding:11px 16px;text-align:left;font-size:.68rem;font-weight:700;color:#7a82a8;text-transform:uppercase;letter-spacing:.4px;">Client</th>
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
        <tr style="border-bottom:1px solid #f0f2f8;" onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background=''">
          <td style="padding:12px 16px;">
            <div style="font-weight:700;color:#0c0f1a;">{{ $ag->invoice_no }}</div>
            <div style="font-size:.68rem;color:#7a82a8;">{{ $ag->agreement_date->format('d M Y') }}</div>
          </td>
          <td style="padding:12px 16px;">
            <div style="font-weight:600;color:#1a1a2e;">{{ $ag->client_name }}</div>
            <div style="font-size:.7rem;color:#7a82a8;">{{ $ag->client_contact }}</div>
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
              <a href="{{ route('billing.show', $ag->uuid) }}"
                 style="padding:5px 10px;border-radius:6px;border:1.5px solid #e2e6f3;background:#fff;font-size:.72rem;font-weight:600;color:#3a3f5c;text-decoration:none;" title="View">
                <i class="fas fa-eye"></i>
              </a>
              <a href="{{ route('billing.pdf', $ag->uuid) }}"
                 style="padding:5px 10px;border-radius:6px;border:1.5px solid #e2e6f3;background:#fff;font-size:.72rem;font-weight:600;color:#059669;text-decoration:none;" title="Download PDF">
                <i class="fas fa-file-pdf"></i>
              </a>
              <a href="{{ route('billing.edit', $ag->uuid) }}"
                 style="padding:5px 10px;border-radius:6px;border:1.5px solid #e2e6f3;background:#fff;font-size:.72rem;font-weight:600;color:#2563eb;text-decoration:none;" title="Edit">
                <i class="fas fa-edit"></i>
              </a>
              <button onclick="sendEmail('{{ $ag->uuid }}')"
                 style="padding:5px 10px;border-radius:6px;border:1.5px solid #e2e6f3;background:#fff;font-size:.72rem;font-weight:600;color:#d97706;cursor:pointer;" title="Send Email">
                <i class="fas fa-envelope"></i>
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:48px;color:#7a82a8;font-size:.9rem;">
            <i class="fas fa-file-contract" style="font-size:2.5rem;color:#e2e6f3;display:block;margin-bottom:12px;"></i>
            No agreements yet. <a href="{{ route('billing.create') }}" style="color:#2563eb;font-weight:700;">Create your first agreement →</a>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  @if($agreements->hasPages())
  <div style="margin-top:16px;">
    {{ $agreements->links() }}
  </div>
  @endif
</div>

<div id="toastStack" style="position:fixed;bottom:22px;right:22px;z-index:9999;display:flex;flex-direction:column;gap:8px;"></div>

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;

function toast(msg, color, icon) {
  const el = document.createElement('div');
  el.style.cssText = 'background:#fff;border:1px solid #e2e6f3;border-radius:10px;padding:11px 15px;display:flex;align-items:center;gap:8px;box-shadow:0 6px 24px rgba(0,0,0,.1);font-size:.8rem;color:#1a1a2e;animation:toastIn .18s ease;max-width:300px;font-family:Arial,sans-serif;';
  el.innerHTML = `<i class="${icon}" style="color:${color};font-size:.9rem;flex-shrink:0;"></i><span>${msg}</span>`;
  document.getElementById('toastStack').appendChild(el);
  setTimeout(() => el.remove(), 3500);
}

async function sendEmail(uuid) {
  if (!confirm('Send agreement PDF to the client by email?')) return;
  try {
    const res = await fetch(`/billing/${uuid}/send-email`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({}),
    });
    const data = await res.json();
    if (data.ok) toast(data.message, '#059669', 'fas fa-check-circle');
    else toast(data.error || 'Send failed', '#dc2626', 'fas fa-times-circle');
  } catch (e) {
    toast('Network error', '#dc2626', 'fas fa-times-circle');
  }
}
</script>
@endpush
@endsection