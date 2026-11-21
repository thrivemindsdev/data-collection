@extends('layouts.app')
@section('title', __('messages.check_status'))
@section('show_banner', true)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card-ashram card shadow-sm">
            <div class="card-header">
                <i class="bi bi-search me-2"></i>{{ __('messages.check_status') }}
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3">
                    <i class="bi bi-info-circle me-1"></i>{{ __('messages.status_instructions') }}
                </p>

                <form method="GET" action="{{ route('status') }}" id="statusForm" onsubmit="return validateStatusForm();" novalidate>
                    {{-- Mobile Number (Primary & Fastest) --}}
                    <div class="mb-3">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>
                                <i class="bi bi-phone me-1"></i>{{ __('messages.mobile') }}
                            </span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                {{ __('messages.check_by_mobile') }}
                            </span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted fw-bold">+91</span>
                            <input type="tel" name="mobile" id="statusMobile" class="form-control"
                                   value="{{ request('mobile') }}" maxlength="10" inputmode="numeric"
                                   placeholder="{{ __('messages.mobile_placeholder') }}"
                                   onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                        </div>
                    </div>

                    <div class="position-relative text-center my-3">
                        <hr class="text-muted opacity-25">
                        <span class="position-absolute top-50 start-50 translate-middle px-3 bg-white text-muted small fw-bold">
                            {{ __('messages.or_divider') }}
                        </span>
                    </div>

                    {{-- Registration / Bhakt ID (Optional) --}}
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-card-text me-1"></i>{{ __('messages.reg_id') }}
                        </label>
                        <input type="text" name="reg_id" id="statusRegId" class="form-control"
                               value="{{ request('reg_id') }}"
                               placeholder="e.g. SAD-REG-2611-39487 or SAD-SN26-XBGSL">
                    </div>

                    <div id="formClientError" class="alert alert-warning py-2 small mb-3" style="display:none;">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        {{ app()->getLocale() === 'hi' ? 'कृपया मोबाइल नंबर या आवेदन संख्या में से कम से कम एक दर्ज करें।' : 'Please enter either your Mobile Number or Registration Number.' }}
                    </div>

                    <button type="submit" class="btn btn-saffron w-100 py-2 fw-bold shadow-sm">
                        <i class="bi bi-search me-1"></i>{{ __('messages.check') }}
                    </button>
                </form>

                @if(request()->isMethod('get') && (request()->filled('mobile') || request()->filled('reg_id')))
                    <hr class="my-4">
                    @if($bhakt)
                        <div class="p-3 rounded" style="background:var(--light-bg); border:1px solid #ebd9c8;">
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                <strong style="font-size:1.15rem; color:var(--maroon);">
                                    {{ $bhakt->display_name }}
                                </strong>
                                <span class="badge badge-status-{{ $bhakt->status }} px-3 py-1 fs-6">
                                    {{ $bhakt->display_status }}
                                </span>
                            </div>

                            <div class="row g-2 mt-2 pt-2 border-top small">
                                <div class="col-6">
                                    <span class="text-muted">{{ __('messages.registration_number') }}:</span><br>
                                    <strong class="fs-6 font-monospace" style="color:var(--maroon);">
                                        {{ $bhakt->display_reg_number }}
                                    </strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted">{{ __('messages.mobile') }}:</span><br>
                                    <strong>{{ $bhakt->mobile }}</strong>
                                </div>

                                @if($bhakt->bhakt_id)
                                    <div class="col-12 mt-2 p-2 rounded bg-white border border-success">
                                        <span class="text-muted small d-block">{{ __('messages.bhakt_id') }} ({{ __('messages.official_id') }}):</span>
                                        <strong class="fs-5 text-success" style="letter-spacing:1px;">
                                            <i class="bi bi-patch-check-fill me-1"></i>{{ $bhakt->bhakt_id }}
                                        </strong>
                                    </div>
                                @endif

                                @if($bhakt->branch)
                                    <div class="col-6 mt-1">
                                        <span class="text-muted">{{ __('messages.branch') }}:</span><br>
                                        <strong>{{ $bhakt->display_branch }}</strong>
                                    </div>
                                @endif

                                @if($bhakt->city || $bhakt->state)
                                    <div class="col-6 mt-1">
                                        <span class="text-muted">{{ __('messages.state') }}:</span><br>
                                        <strong>{{ implode(', ', array_filter([$bhakt->display_city, $bhakt->display_state])) }}</strong>
                                    </div>
                                @endif

                                @if($bhakt->diksha_taken)
                                    <div class="col-12 mt-2">
                                        <span class="badge" style="background:var(--maroon);">
                                            <i class="bi bi-stars me-1"></i>{{ __('messages.dikshit') }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            @if($bhakt->admin_remarks)
                                <div class="mt-3 p-2 rounded bg-white small text-muted border">
                                    <i class="bi bi-chat-left-text me-1 text-primary"></i>
                                    <strong>{{ __('messages.remarks') }}:</strong> {{ $bhakt->admin_remarks }}
                                </div>
                            @endif

                            <div class="mt-3 pt-2 text-muted small border-top text-end">
                                {{ __('messages.registered_on') }}: {{ $bhakt->created_at->format('d M Y, h:i A') }}
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mt-2 shadow-sm">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>{{ __('messages.not_found') }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function validateStatusForm() {
    var mobile = document.getElementById('statusMobile').value.trim();
    var regId = document.getElementById('statusRegId').value.trim();
    var errorDiv = document.getElementById('formClientError');

    if (mobile === '' && regId === '') {
        if (errorDiv) errorDiv.style.display = 'block';
        return false;
    }
    if (errorDiv) errorDiv.style.display = 'none';
    return true;
}
</script>
@endpush
