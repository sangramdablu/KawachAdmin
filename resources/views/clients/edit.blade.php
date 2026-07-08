@extends('layouts.master')
@section('title', 'Edit Client — Kawach Technology')

@section('content')
<div style="font-family:'Sora',sans-serif;padding:28px 24px;max-width:760px;margin:0 auto;">

  <div style="font-size:.72rem;color:#7a82a8;margin-bottom:5px;">
    <a href="{{ route('dashboard') }}" style="color:#2563eb;text-decoration:none;font-weight:600;">Dashboard</a>
    ›
    <a href="{{ route('clients.index') }}" style="color:#2563eb;text-decoration:none;font-weight:600;">Clients</a>
    › Edit
  </div>
  <h1 style="font-size:1.4rem;font-weight:800;color:#0c0f1a;letter-spacing:-.3px;margin-bottom:22px;">
    <i class="fas fa-user-edit" style="color:#2563eb;font-size:1.1rem;margin-right:8px;"></i>
    Edit Client
  </h1>

  @if($errors->any())
  <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:11px 16px;margin-bottom:16px;font-size:.82rem;color:#dc2626;">
    <ul style="margin:0;padding-left:18px;">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <form action="{{ route('clients.update', $client->id) }}" method="POST"
        style="background:#fff;border-radius:12px;border:1px solid #e2e6f3;padding:24px;box-shadow:0 2px 12px rgba(37,99,235,.07);margin-bottom:20px;">
    @csrf
    @method('PUT')

    <div style="margin-bottom:16px;">
      <label style="display:block;font-size:.76rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Full Name</label>
      <input type="text" name="name" value="{{ old('name', $client->name) }}" required
             style="width:100%;padding:10px 12px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.85rem;box-sizing:border-box;">
    </div>

    <div style="margin-bottom:16px;">
      <label style="display:block;font-size:.76rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Email</label>
      <input type="email" name="email" value="{{ old('email', $client->email) }}" required
             style="width:100%;padding:10px 12px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.85rem;box-sizing:border-box;">
    </div>

    <div style="margin-bottom:16px;">
      <label style="display:block;font-size:.76rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">New Password <span style="font-weight:400;color:#7a82a8;">(leave blank to keep current)</span></label>
      <input type="password" name="password"
             style="width:100%;padding:10px 12px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.85rem;box-sizing:border-box;">
    </div>

    <div style="margin-bottom:22px;">
      <label style="display:block;font-size:.76rem;font-weight:700;color:#3a3f5c;margin-bottom:6px;">Confirm New Password</label>
      <input type="password" name="password_confirmation"
             style="width:100%;padding:10px 12px;border:1.5px solid #e2e6f3;border-radius:8px;font-size:.85rem;box-sizing:border-box;">
    </div>

    <div style="display:flex;gap:10px;justify-content:flex-end;">
      <a href="{{ route('clients.index') }}"
         style="padding:9px 18px;border-radius:8px;border:1.5px solid #e2e6f3;color:#3a3f5c;text-decoration:none;font-size:.83rem;font-weight:700;">Cancel</a>
      <button type="submit"
              style="padding:9px 20px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-size:.83rem;font-weight:700;cursor:pointer;">
        Save Changes
      </button>
    </div>
  </form>

  {{-- Read-only project overview --}}
  <div style="background:#fff;border-radius:12px;border:1px solid #e2e6f3;padding:20px 24px;box-shadow:0 2px 12px rgba(37,99,235,.07);">
    <h2 style="font-size:.95rem;font-weight:800;color:#0c0f1a;margin:0 0 14px;">
      <i class="fas fa-layer-group" style="color:#2563eb;margin-right:6px;"></i> Projects
    </h2>
    @forelse($projects as $project)
    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f2f8;">
      <div>
        <div style="font-weight:700;color:#0c0f1a;font-size:.85rem;">{{ $project->project_name }}</div>
        <div style="font-size:.72rem;color:#7a82a8;">{{ $project->project_type ?? 'Web Project' }} · {{ $project->progress }}% complete</div>
      </div>
      <span style="font-size:.68rem;font-weight:700;color:#2563eb;text-transform:uppercase;">{{ $project->status_label }}</span>
    </div>
    @empty
    <div style="color:#7a82a8;font-size:.82rem;">No projects assigned to this client yet.</div>
    @endforelse
  </div>

</div>
@endsection
