@extends('layouts.app')
@section('title', __('messages.dashboard'))

@section('content')

{{-- STAT CARDS --}}
<h4 class="mb-3" style="color:var(--maroon);">
    <i class="bi bi-speedometer2 me-2"></i>{{ __('messages.dashboard') }}
</h4>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card bg-stat-1">
            <div class="stat-number">{{ $stats['total'] }}</div>
            <div class="stat-label"><i class="bi bi-people-fill me-1"></i>{{ __('messages.total_bhakts') }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card bg-stat-2">
            <div class="stat-number">{{ $stats['approved'] }}</div>
            <div class="stat-label"><i class="bi bi-check-circle-fill me-1"></i>{{ __('messages.approved') }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card bg-stat-3" style="color:#000;">
            <div class="stat-number">{{ $stats['pending'] }}</div>
            <div class="stat-label"><i class="bi bi-clock-fill me-1"></i>{{ __('messages.pending') }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card bg-stat-4">
            <div class="stat-number">{{ $stats['rejected'] }}</div>
            <div class="stat-label"><i class="bi bi-x-circle-fill me-1"></i>{{ __('messages.rejected') }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card bg-stat-5">
            <div class="stat-number">{{ $stats['dikshit'] }}</div>
            <div class="stat-label"><i class="bi bi-stars me-1"></i>{{ __('messages.dikshit_bhakts') }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card bg-stat-6">
            <div class="stat-number">{{ $stats['whatsapp'] }}</div>
            <div class="stat-label"><i class="bi bi-whatsapp me-1"></i>{{ __('messages.whatsapp_consented') }}</div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- STATE DISTRIBUTION --}}
    <div class="col-lg-6">
        <div class="card-ashram card h-100">
            <div class="card-header">
                <i class="bi bi-map-fill me-2"></i>{{ __('messages.state_distribution') }}
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-ashram mb-0">
                        <thead><tr><th>{{ __('messages.state') }}</th><th>{{ __('messages.total_bhakts') }}</th></tr></thead>
                        <tbody>
                        @forelse($stateStats as $row)
                            @php
                                $locState = \App\Services\LanguageHelper::translateState($row->state);
                            @endphp
                            <tr>
                                <td>{{ $locState ?: '—' }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height:8px; background:#f0e6d8;">
                                            <div class="progress-bar" style="background:var(--saffron);width:{{ $stats['total'] > 0 ? ($row->total/$stats['total']*100) : 0 }}%"></div>
                                        </div>
                                        <span class="fw-bold">{{ $row->total }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-muted text-center py-3">No data yet</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- BRANCH DISTRIBUTION --}}
    <div class="col-lg-6">
        <div class="card-ashram card h-100">
            <div class="card-header">
                <i class="bi bi-building me-2"></i>{{ __('messages.branch_distribution') }}
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-ashram mb-0">
                        <thead><tr><th>{{ __('messages.branch') }}</th><th>{{ __('messages.total_bhakts') }}</th></tr></thead>
                        <tbody>
                        @forelse($branchStats as $row)
                            @php
                                $locBranch = \App\Services\LanguageHelper::translateBranch($row->branch);
                            @endphp
                            <tr>
                                <td>{{ $locBranch ?: '—' }}</td>
                                <td><span class="badge" style="background:var(--maroon); font-size:0.9rem;">{{ $row->total }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-muted text-center py-3">No data yet</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT REGISTRATIONS --}}
    <div class="col-12">
        <div class="card-ashram card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>{{ __('messages.recent_registrations') }}</span>
                <a href="{{ route('admin.bhakts.index') }}" class="btn btn-sm btn-outline-light">
                    {{ __('messages.all_bhakts') }} →
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-ashram mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.name') }}</th>
                                <th>{{ __('messages.mobile') }}</th>
                                <th>{{ __('messages.district_state') }}</th>
                                <th>{{ __('messages.branch') }}</th>
                                <th>{{ __('messages.diksha') }}</th>
                                <th>{{ __('messages.status_col') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($recent as $b)
                            <tr>
                                <td class="text-muted small">
                                    @if($b->bhakt_id)
                                        <code style="color:var(--maroon); font-weight:700;">{{ $b->bhakt_id }}</code>
                                    @else
                                        <span class="badge bg-light text-dark border font-monospace">{{ $b->display_reg_number }}</span>
                                    @endif
                                </td>
                                <td><strong>{{ $b->display_name }}</strong></td>
                                <td>{{ $b->mobile }}</td>
                                <td>{{ implode(', ', array_filter([$b->display_district, $b->display_state])) ?: '—' }}</td>
                                <td>{{ $b->display_branch }}</td>
                                <td>
                                    @if($b->diksha_taken)
                                        <span class="badge" style="background:var(--maroon);">
                                            <i class="bi bi-stars me-1"></i>{{ __('messages.dikshit') }}
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-status-{{ $b->status }}">{{ $b->display_status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.bhakts.show', $b) }}" class="btn btn-sm btn-saffron">
                                        {{ __('messages.view') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">No registrations yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
