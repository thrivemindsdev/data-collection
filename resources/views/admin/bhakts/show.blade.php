@extends('layouts.app')
@section('title', $bhakt->display_name . ' — ' . __('messages.bhakt_id'))

@section('content')

<div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <a href="{{ route('admin.bhakts.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>{{ __('messages.back') }}
    </a>
    <form method="POST" action="{{ route('admin.bhakts.destroy', $bhakt) }}"
          onsubmit="return confirm('{{ addslashes(__('messages.delete_confirm')) }}');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger">
            <i class="bi bi-trash me-1"></i>{{ __('messages.delete') }}
        </button>
    </form>
</div>

<div class="row g-4">

    {{-- PROFILE CARD --}}
    <div class="col-lg-4">
        <div class="card-ashram card text-center">
            <div class="card-body p-4">
                <div style="width:84px; height:84px; border-radius:50%; background:linear-gradient(135deg,var(--saffron),var(--maroon)); display:flex; align-items:center; justify-content:center; margin:0 auto 14px; font-size:2.2rem; color:white; font-weight:700; box-shadow:0 4px 12px rgba(0,0,0,0.15);">
                    {{ mb_substr($bhakt->display_name, 0, 1) }}
                </div>
                <h5 class="fw-bold mb-1" style="color:var(--maroon);">{{ $bhakt->display_name }}</h5>

                @if($bhakt->bhakt_id)
                    <div class="mb-2">
                        <span class="badge px-3 py-2 fs-6 shadow-sm" style="background:var(--maroon); letter-spacing:1px;">
                            {{ $bhakt->bhakt_id }}
                        </span>
                    </div>
                @else
                    <div class="mb-2"><span class="badge bg-light text-dark border font-monospace">{{ $bhakt->display_reg_number }}</span></div>
                @endif

                <div class="d-flex justify-content-center gap-1 flex-wrap my-2">
                    <span class="badge badge-status-{{ $bhakt->status }} px-2 py-1">
                        {{ $bhakt->display_status }}
                    </span>
                    @if($bhakt->diksha_taken)
                        <span class="badge px-2 py-1" style="background:var(--maroon);">
                            <i class="bi bi-stars me-1"></i>{{ __('messages.dikshit') }}
                        </span>
                    @endif
                    @if($bhakt->whatsapp_consent)
                        <span class="badge bg-success px-2 py-1">
                            <i class="bi bi-whatsapp me-1"></i>WhatsApp OK
                        </span>
                    @endif
                </div>

                <hr>
                <div class="text-start small">
                    <div class="mb-2"><i class="bi bi-phone text-muted me-2"></i><strong>{{ $bhakt->mobile }}</strong></div>
                    @if($bhakt->email)
                    <div class="mb-2"><i class="bi bi-envelope text-muted me-2"></i>{{ $bhakt->email }}</div>
                    @endif
                    @if($bhakt->city || $bhakt->district || $bhakt->state)
                    <div class="mb-2"><i class="bi bi-geo-alt text-muted me-2"></i>
                        {{ implode(', ', array_filter([$bhakt->display_city, $bhakt->display_district, $bhakt->display_state])) }}
                    </div>
                    @endif
                    @if($bhakt->branch)
                    <div class="mb-2"><i class="bi bi-building text-muted me-2"></i>{{ $bhakt->display_branch }}</div>
                    @endif
                    @if($bhakt->dob)
                    <div class="mb-2"><i class="bi bi-calendar text-muted me-2"></i>{{ $bhakt->dob->format('d M Y') }}</div>
                    @endif
                    @if($bhakt->gender)
                    <div class="mb-2"><i class="bi bi-person text-muted me-2"></i>{{ ucfirst($bhakt->gender) }}</div>
                    @endif
                </div>
                <div class="text-muted small mt-3 pt-2 border-top">
                    {{ __('messages.registered_on') }}: {{ $bhakt->created_at->format('d M Y, h:i A') }}
                </div>
            </div>
        </div>
    </div>

    {{-- DETAILS + ACTIONS --}}
    <div class="col-lg-8">

        {{-- FULL DETAILS --}}
        <div class="card-ashram card mb-4">
            <div class="card-header"><i class="bi bi-clipboard-data-fill me-2"></i>{{ __('messages.personal_details') }} & {{ __('messages.address_section') }}</div>
            <div class="card-body">
                <div class="row g-3">
                    @if($bhakt->address)
                    <div class="col-12">
                        <div class="text-muted small fw-semibold">{{ __('messages.address') }}</div>
                        <div class="p-2 rounded bg-light mt-1">{{ $bhakt->address }}</div>
                    </div>
                    @endif

                    @if($bhakt->diksha_taken)
                    <div class="col-12">
                        <div class="section-divider mt-2">
                            <i class="bi bi-stars me-1"></i>{{ __('messages.diksha_section') }}
                        </div>
                        <div class="row g-2">
                            @if($bhakt->diksha_date)
                            <div class="col-md-4">
                                <div class="text-muted small">{{ __('messages.diksha_date') }}</div>
                                <div class="fw-semibold">{{ $bhakt->diksha_date->format('d M Y') }}</div>
                            </div>
                            @endif
                            @if($bhakt->diksha_place)
                            <div class="col-md-4">
                                <div class="text-muted small">{{ __('messages.diksha_place') }}</div>
                                <div class="fw-semibold">{{ \App\Services\LanguageHelper::translatePlace($bhakt->diksha_place) }}</div>
                            </div>
                            @endif
                            @if($bhakt->diksha_guru)
                            <div class="col-md-4">
                                <div class="text-muted small">{{ __('messages.diksha_guru') }}</div>
                                <div class="fw-semibold">{{ $bhakt->diksha_guru }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($bhakt->family_members)
                    <div class="col-12">
                        <div class="text-muted small fw-semibold">{{ __('messages.family_members') }}</div>
                        <div class="p-2 rounded bg-light mt-1">{{ $bhakt->family_members }}</div>
                    </div>
                    @endif

                    @if($bhakt->seva)
                    <div class="col-12">
                        <div class="text-muted small fw-semibold">{{ __('messages.seva') }}</div>
                        <div class="p-2 rounded bg-light mt-1">{{ $bhakt->seva }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ADMIN ACTIONS (only for pending) --}}
        @if($bhakt->status === 'pending')
        <div class="card-ashram card mb-4">
            <div class="card-header">
                <i class="bi bi-check2-square me-2"></i>{{ __('messages.actions') }}
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">{{ __('messages.remarks') }}</label>
                    <textarea class="form-control" id="remarksInput" rows="2"
                              placeholder="Remarks..."></textarea>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <form method="POST" action="{{ route('admin.bhakts.approve', $bhakt) }}" id="approveForm">
                        @csrf
                        <input type="hidden" name="remarks" id="approveRemarks">
                        <button type="submit" class="btn btn-success"
                                onclick="document.getElementById('approveRemarks').value=document.getElementById('remarksInput').value">
                            <i class="bi bi-check-circle-fill me-1"></i>{{ __('messages.approve') }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.bhakts.correction', $bhakt) }}">
                        @csrf
                        <input type="hidden" name="remarks" id="corrRemarks">
                        <button type="submit" class="btn btn-info text-white"
                                onclick="document.getElementById('corrRemarks').value=document.getElementById('remarksInput').value">
                            <i class="bi bi-arrow-return-left me-1"></i>{{ __('messages.send_correction') }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.bhakts.reject', $bhakt) }}">
                        @csrf
                        <input type="hidden" name="remarks" id="rejRemarks">
                        <button type="submit" class="btn btn-danger"
                                onclick="document.getElementById('rejRemarks').value=document.getElementById('remarksInput').value">
                            <i class="bi bi-x-circle-fill me-1"></i>{{ __('messages.reject') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @else
        {{-- Status info if already reviewed --}}
        @if($bhakt->admin_remarks)
        <div class="alert alert-info">
            <i class="bi bi-chat-left-text-fill me-2"></i>
            <strong>{{ __('messages.remarks') }}:</strong> {{ $bhakt->admin_remarks }}
            @if($bhakt->reviewer)
                <span class="ms-2 text-muted small">— {{ $bhakt->reviewer->name }}, {{ $bhakt->reviewed_at?->format('d M Y') }}</span>
            @endif
        </div>
        @endif
        @endif

        {{-- AUDIT LOG --}}
        @if($bhakt->auditLogs->count())
        <div class="card-ashram card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>{{ __('messages.audit_history') }}
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-ashram mb-0">
                        <thead><tr><th>Action</th><th>{{ __('messages.by') }}</th><th>{{ __('messages.remarks') }}</th><th>Date</th></tr></thead>
                        <tbody>
                        @foreach($bhakt->auditLogs->sortByDesc('created_at') as $log)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $log->action }}</span></td>
                                <td>{{ $log->actor?->name ?? 'System' }}</td>
                                <td>{{ $log->remarks ?: '—' }}</td>
                                <td class="small text-muted">{{ $log->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection
