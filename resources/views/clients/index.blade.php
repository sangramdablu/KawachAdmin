@extends('layouts.master')
@section('title', 'Clients — Kawach Technology')

@section('content')
<div style="font-family:'Sora',sans-serif;padding:28px 24px;max-width:1380px;margin:0 auto;">

  {{-- Header --}}
  <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
    <div>
      <div style="font-size:.72rem;color:#7a82a8;margin-bottom:5px;">
        <a href="{{ route('dashboard') }}" style="color:#2563eb;text-decoration:none;font-weight:600;">Dashboard</a>
        › Clients
      </div>
      <h1 style="font-size:1.4rem;font-weight:800;color:#0c0f1a;letter-spacing:-.3px;">
        <i class="fas fa-user-tie" style="color:#2563eb;font-size:1.1rem;margin-right:8px;"></i>
        Clients
      </h1>
    </div>
    @can('clients.create')
    <a href="{{ route('clients.create') }}"
       style="display:inline-flex;align-items:center;gap:7px;padding:9px 20px;background:#2563eb;color:#fff;border-radius:8px;font-size:.83rem;font-weight:700;text-decoration:none;box-shadow:0 2px 12px rgba(37,99,235,.3);">
      <i class="fas fa-plus"></i> New Client
    </a>
    @endcan
  </div>

  @if(session('success'))
  <div style="background:#ecfdf5;border:1px solid #6ee7b7;border-radius:8px;padding:11px 16px;margin-bottom:16px;font-size:.82rem;color:#059669;display:flex;align-items:center;gap:8px;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
  </div>
  @endif

  @if($errors->any())
  <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:11px 16px;margin-bottom:16px;font-size:.82rem;color:#dc2626;display:flex;align-items:center;gap:8px;">
    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
  </div>
  @endif

  {{-- Table --}}
  <div style="background:#fff;border-radius:12px;border:1px solid #e2e6f3;overflow:hidden;box-shadow:0 2px 12px rgba(37,99,235,.07);">
    <table style="width:100%;border-collapse:collapse;font-size:.82rem;">
      <thead>
        <tr style="background:#f5f6fa;border-bottom:1px solid #e2e6f3;">
          <th style="padding:11px 16px;text-align:left;font-size:.68rem;font-weight:700;color:#7a82a8;text-transform:uppercase;letter-spacing:.4px;">Name</th>
          <th style="padding:11px 16px;text-align:left;font-size:.68rem;font-weight:700;color:#7a82a8;text-transform:uppercase;letter-spacing:.4px;">Email</th>
          <th style="padding:11px 16px;text-align:center;font-size:.68rem;font-weight:700;color:#7a82a8;text-transform:uppercase;letter-spacing:.4px;">Projects</th>
          <th style="padding:11px 16px;text-align:center;font-size:.68rem;font-weight:700;color:#7a82a8;text-transform:uppercase;letter-spacing:.4px;">Status</th>
          <th style="padding:11px 16px;text-align:center;font-size:.68rem;font-weight:700;color:#7a82a8;text-transform:uppercase;letter-spacing:.4px;">Joined</th>
          <th style="padding:11px 16px;text-align:center;font-size:.68rem;font-weight:700;color:#7a82a8;text-transform:uppercase;letter-spacing:.4px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($clients as $client)
        <tr style="border-bottom:1px solid #f0f2f8;">
          <td style="padding:12px 16px;">
            <div style="font-weight:700;color:#0c0f1a;">{{ $client->name }}</div>
          </td>
          <td style="padding:12px 16px;color:#3a3f5c;">{{ $client->email }}</td>
          <td style="padding:12px 16px;">
            @forelse($client->clientPortalProjects as $cp)
              <div style="margin-bottom:4px;">
                <a href="{{ route('client-projects.show', $cp->id) }}" style="color:#2563eb;text-decoration:none;font-weight:700;font-size:.78rem;">{{ $cp->project_name }}</a>
                <span style="font-size:.68rem;color:#7a82a8;margin-left:4px;text-transform:uppercase;">{{ $cp->status_label }}</span>
              </div>
            @empty
              <span style="font-size:.74rem;color:#b8bfd4;font-style:italic;">No portal project yet</span>
            @endforelse
          </td>
          <td style="padding:12px 16px;text-align:center;">
            @php $isActive = ($client->status ?? 'active') === 'active'; @endphp
            <span style="display:inline-block;padding:3px 11px;border-radius:20px;font-size:.67rem;font-weight:700;background:{{ $isActive ? '#ecfdf5' : '#fef2f2' }};color:{{ $isActive ? '#059669' : '#dc2626' }};">
              {{ strtoupper($client->status ?? 'active') }}
            </span>
          </td>
          <td style="padding:12px 16px;text-align:center;font-size:.72rem;color:#7a82a8;">
            {{ $client->created_at->format('d M Y') }}
          </td>
          <td style="padding:12px 16px;text-align:center;">
            <div style="display:flex;gap:5px;justify-content:center;align-items:center;">
              @can('clients.edit')
              <a href="{{ route('clients.edit', $client->id) }}"
                 style="padding:5px 10px;border-radius:6px;border:1.5px solid #e2e6f3;background:#fff;font-size:.72rem;font-weight:600;color:#2563eb;text-decoration:none;" title="Edit">
                <i class="fas fa-edit"></i>
              </a>
              @endcan
              @can('clients.delete')
              <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Remove this client? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        style="padding:5px 10px;border-radius:6px;border:1.5px solid #e2e6f3;background:#fff;font-size:.72rem;font-weight:600;color:#dc2626;cursor:pointer;" title="Remove">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
              @endcan
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align:center;padding:48px;color:#7a82a8;font-size:.9rem;">
            <i class="fas fa-user-tie" style="font-size:2.5rem;color:#e2e6f3;display:block;margin-bottom:12px;"></i>
            No clients yet.
            @can('clients.create')
              <a href="{{ route('clients.create') }}" style="color:#2563eb;font-weight:700;">Add your first client →</a>
            @endcan
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($clients->hasPages())
  <div style="margin-top:18px;">
    {{ $clients->links() }}
  </div>
  @endif

</div>
@endsection
