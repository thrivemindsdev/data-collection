@extends('layouts.app')
@section('title', __('messages.all_bhakts'))

@push('styles')
<style>
/* Bootstrap pagination theming */
.pagination .page-link {
    color: var(--maroon);
    border-color: #e0c8b0;
}
.pagination .page-item.active .page-link {
    background: var(--maroon);
    border-color: var(--maroon);
    color: #fff;
}
.pagination .page-link:hover {
    background: var(--light-bg);
    color: var(--maroon);
}
.filter-badge {
    font-size: 0.78rem;
    padding: 4px 10px;
    border-radius: 20px;
    background: var(--maroon);
    color: #fff;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.filter-badge a { color: #ffd; text-decoration: none; }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0" style="color:var(--maroon);">
        <i class="bi bi-people-fill me-2"></i>{{ __('messages.all_bhakts') }}
        <span class="fs-6 fw-normal text-muted ms-2">( {{ $bhakts->total() }} {{ __('messages.bhakts_found') }} )</span>
    </h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.bhakts.export', request()->query()) }}" class="btn btn-sm btn-success">
            <i class="bi bi-download me-1"></i>{{ __('messages.export_csv') }}
        </a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-speedometer2 me-1"></i>{{ __('messages.dashboard') }}
        </a>
    </div>
</div>

{{-- FILTER / SEARCH FORM --}}
<div class="card-ashram card mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.bhakts.index') }}" id="filterForm" class="row g-2 align-items-end">

            {{-- Search --}}
            <div class="col-12 col-md-3">
                <label class="form-label small mb-1 fw-semibold">
                    <i class="bi bi-search me-1"></i>{{ __('messages.search') }}
                </label>
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="{{ __('messages.search_placeholder') }}"
                       value="{{ request()->input('search') }}">
            </div>

            {{-- Status --}}
            <div class="col-6 col-md-2">
                <label class="form-label small mb-1 fw-semibold">{{ __('messages.status_col') }}</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">{{ __('messages.all_status') }}</option>
                    <option value="pending"    {{ request()->input('status') === 'pending'    ? 'selected' : '' }}>⏳ {{ __('messages.pending') }}</option>
                    <option value="approved"   {{ request()->input('status') === 'approved'   ? 'selected' : '' }}>✅ {{ __('messages.approved') }}</option>
                    <option value="rejected"   {{ request()->input('status') === 'rejected'   ? 'selected' : '' }}>❌ {{ __('messages.rejected') }}</option>
                    <option value="correction" {{ request()->input('status') === 'correction' ? 'selected' : '' }}>🔄 {{ __('messages.send_correction') }}</option>
                </select>
            </div>

            {{-- State --}}
            <div class="col-6 col-md-2">
                <label class="form-label small mb-1 fw-semibold">{{ __('messages.state') }}</label>
                <select name="state" class="form-select form-select-sm">
                    <option value="">{{ __('messages.all_states') }}</option>
                    @foreach($states as $st)
                        @php
                            $dispSt = \App\Services\LanguageHelper::translateState($st);
                        @endphp
                        <option value="{{ $st }}" {{ request()->input('state') === $st ? 'selected' : '' }}>{{ $dispSt }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Branch --}}
            <div class="col-6 col-md-2">
                <label class="form-label small mb-1 fw-semibold">{{ __('messages.branch') }}</label>
                <select name="branch" class="form-select form-select-sm">
                    <option value="">{{ __('messages.all_branches') }}</option>
                    @foreach($branches as $br)
                        @php
                            $dispBr = \App\Services\LanguageHelper::translateBranch($br);
                        @endphp
                        <option value="{{ $br }}" {{ request()->input('branch') === $br ? 'selected' : '' }}>{{ $dispBr }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Diksha --}}
            <div class="col-6 col-md-2">
                <label class="form-label small mb-1 fw-semibold">{{ __('messages.diksha') }}</label>
                <select name="diksha" class="form-select form-select-sm">
                    <option value="">{{ __('messages.all') }}</option>
                    <option value="yes" {{ request()->input('diksha') === 'yes' ? 'selected' : '' }}>⭐ {{ __('messages.dikshit') }}</option>
                    <option value="no"  {{ request()->input('diksha') === 'no'  ? 'selected' : '' }}>{{ __('messages.not_dikshit') }}</option>
                </select>
            </div>

            {{-- Apply Button --}}
            <div class="col-12 col-md-1 d-flex gap-1 align-items-end">
                <button type="submit" class="btn btn-saffron btn-sm flex-fill">
                    <i class="bi bi-funnel-fill"></i> {{ __('messages.apply') }}
                </button>
            </div>

        </form>

        {{-- Active filter badges --}}
        @php
            $activeFilters = array_filter([
                'search'  => request()->input('search'),
                'status'  => request()->input('status'),
                'state'   => request()->input('state'),
                'branch'  => request()->input('branch'),
                'diksha'  => request()->input('diksha'),
            ]);
        @endphp
        @if(count($activeFilters))
            <div class="mt-2 d-flex flex-wrap gap-2 align-items-center">
                <span class="text-muted small">{{ __('messages.active_filters') }}</span>
                @foreach($activeFilters as $key => $val)
                    @php
                        $displayVal = $val;
                        if ($key === 'state') $displayVal = \App\Services\LanguageHelper::translateState($val);
                        if ($key === 'branch') $displayVal = \App\Services\LanguageHelper::translateBranch($val);
                        if ($key === 'status') $displayVal = \App\Services\LanguageHelper::translateStatus($val);
                        if ($key === 'diksha') $displayVal = $val === 'yes' ? __('messages.dikshit') : __('messages.not_dikshit');
                    @endphp
                    <span class="filter-badge">
                        {{ ucfirst($key) }}: <strong>{{ $displayVal }}</strong>
                        <a href="{{ route('admin.bhakts.index', array_merge(request()->except($key))) }}" title="{{ __('messages.clear_all') }}">✕</a>
                    </span>
                @endforeach
                <a href="{{ route('admin.bhakts.index') }}" class="btn btn-outline-secondary btn-sm py-0 px-2">
                    <i class="bi bi-x-circle me-1"></i>{{ __('messages.clear_all') }}
                </a>
            </div>
        @endif
    </div>
</div>

{{-- TABLE --}}
<div class="card-ashram card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-ashram mb-0 align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('messages.bhakt_id') }}</th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.mobile') }}</th>
                        <th>{{ __('messages.district_state') }}</th>
                        <th>{{ __('messages.branch') }}</th>
                        <th>{{ __('messages.diksha') }}</th>
                        <th>{{ __('messages.status_col') }}</th>
                        <th>{{ __('messages.registered_on') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($bhakts as $b)
                    <tr>
                        {{-- Row number across pages --}}
                        <td class="text-muted small">
                            {{ ($bhakts->currentPage() - 1) * $bhakts->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            @if($b->bhakt_id)
                                <code style="color:var(--maroon); font-weight:700; letter-spacing:.5px;">{{ $b->bhakt_id }}</code>
                            @else
                                <span class="badge bg-light text-dark border font-monospace">{{ $b->display_reg_number }}</span>
                            @endif
                        </td>
                        <td>
                            <strong style="color: #2b1708;">{{ $b->display_name }}</strong>
                        </td>
                        <td>
                            <a href="tel:{{ $b->mobile }}" class="text-decoration-none fw-semibold">
                                {{ $b->mobile }}
                            </a>
                        </td>
                        <td class="small">
                            {{ implode(', ', array_filter([$b->display_district, $b->display_state])) ?: '—' }}
                        </td>
                        <td class="small">{{ $b->display_branch }}</td>
                        <td>
                            @if($b->diksha_taken)
                                <span class="badge" style="background:var(--maroon); font-size:.75rem;">
                                    <i class="bi bi-stars me-1"></i>{{ __('messages.dikshit') }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-status-{{ $b->status }}">
                                {{ $b->display_status }}
                            </span>
                        </td>
                        <td class="small text-muted">
                            <div>{{ $b->created_at->format('d M Y') }}</div>
                            <div class="text-secondary" style="font-size:0.75rem;">{{ $b->created_at->format('h:i A') }}</div>
                        </td>
                        <td>
                            <div class="d-flex gap-1 align-items-center">
                                <a href="{{ route('admin.bhakts.show', $b) }}" class="btn btn-sm btn-saffron py-1 px-2">
                                    <i class="bi bi-eye me-1"></i>{{ __('messages.view') }}
                                </a>
                                <form method="POST" action="{{ route('admin.bhakts.destroy', $b) }}"
                                      onsubmit="return confirm('{{ addslashes(__('messages.delete_confirm')) }}');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="{{ __('messages.delete') }}">
                                        <i class="bi bi-trash"></i> {{ __('messages.delete') }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            {{ __('messages.no_records_found') }}
                            <br>
                            <a href="{{ route('admin.bhakts.index') }}" class="btn btn-sm btn-outline-secondary mt-2">
                                {{ __('messages.clear_all') }}
                            </a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination info footer --}}
    @if($bhakts->total() > 0)
    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2"
         style="background:#fdf8f3; border-top:1px solid #e0c8b0;">
        <span class="text-muted small">
            {{ __('messages.showing') }} <strong>{{ $bhakts->firstItem() }}–{{ $bhakts->lastItem() }}</strong>
            {{ __('messages.of') }} <strong>{{ $bhakts->total() }}</strong> {{ __('messages.all_bhakts') }}
            ({{ __('messages.page') }} {{ $bhakts->currentPage() }} {{ __('messages.of') }} {{ $bhakts->lastPage() }})
        </span>
        <div>
            {{ $bhakts->links() }}
        </div>
    </div>
    @endif
</div>

@endsection
