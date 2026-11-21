@extends('layouts.app')
@section('title', __('messages.submitted'))
@section('show_banner', true)

@push('styles')
<style>
/* ---- CELEBRATORY GENERATION ANIMATIONS ---- */
.success-checkmark-box {
    width: 90px;
    height: 90px;
    margin: 0 auto 16px;
    position: relative;
}

.checkmark-circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 3;
    stroke-miterlimit: 10;
    stroke: #198754;
    fill: none;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}

.checkmark-check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    stroke: #198754;
    stroke-width: 4;
    animation: stroke 0.4s cubic-bezier(0.65, 0, 0.45, 1) 0.6s forwards;
}

@keyframes stroke {
    100% { stroke-dashoffset: 0; }
}

/* Pulsing Celebration Glow */
.celebrate-pulse {
    animation: cardEntrance 0.7s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
}
@keyframes cardEntrance {
    0% { transform: scale(0.85); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}

/* ---- PRESTIGIOUS ASHRAM DIGITAL PASS ---- */
.ashram-digital-pass {
    background: #fffdf9;
    border: 2px solid #D4AF37;
    border-radius: 14px;
    box-shadow: 0 8px 30px rgba(123, 30, 30, 0.08);
    position: relative;
    overflow: hidden;
    padding: 22px 20px;
    margin-top: 16px;
    text-align: center;
}
.ashram-digital-pass > * {
    position: relative;
    z-index: 2;
}
.pass-watermark {
    position: absolute;
    right: -10px;
    bottom: -25px;
    font-size: 7.5rem;
    font-family: 'Yatra One', 'Noto Serif Devanagari', 'Segoe UI', serif;
    color: #D4AF37;
    opacity: 0.08;
    line-height: 1;
    pointer-events: none;
    user-select: none;
    z-index: 1;
}
.pass-header-ribbon {
    background: linear-gradient(135deg, var(--maroon) 0%, #5c1414 100%);
    color: var(--gold);
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 1px;
    display: inline-block;
    margin-bottom: 12px;
}
.pass-code-display {
    font-family: 'Courier New', Courier, monospace;
    font-size: clamp(1.2rem, 4.5vw, 1.85rem);
    font-weight: 800;
    letter-spacing: 1.5px;
    color: var(--maroon);
    background: #fff;
    padding: 8px 16px;
    border-radius: 8px;
    display: inline-block;
    border: 1px dashed #d4af37;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    max-width: 100%;
    word-break: break-all;
}
.pass-barcode {
    height: 28px;
    margin: 12px auto 6px;
    background: repeating-linear-gradient(
        90deg,
        #333 0px, #333 2px,
        transparent 2px, transparent 4px,
        #333 4px, #333 7px,
        transparent 7px, transparent 10px,
        #333 10px, #333 11px,
        transparent 11px, transparent 15px
    );
    width: 220px;
    max-width: 90%;
    opacity: 0.7;
}

@media (max-width: 576px) {
    .pass-code-display {
        font-size: 1.25rem;
        letter-spacing: 1px;
        padding: 6px 10px;
    }
    .pass-watermark {
        font-size: 5rem;
        right: -5px;
        bottom: -15px;
        opacity: 0.06;
    }
}

@media print {
    nav, footer, .no-print { display: none !important; }
    body { background: #fff !important; }
    .card-ashram { box-shadow: none !important; border: 1px solid #333 !important; }
}
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card-ashram card text-center shadow celebrate-pulse">
            <div class="card-body p-4 p-md-5">

                {{-- CELEBRATORY ANIMATED CHECKMARK --}}
                <div class="success-checkmark-box">
                    <svg class="w-100 h-100" viewBox="0 0 52 52">
                        <circle class="checkmark-circle" cx="26" cy="26" r="24"/>
                        <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                    </svg>
                </div>

                <h2 class="mt-2" style="color: var(--maroon); font-weight:700;">{{ __('messages.submitted') }}</h2>
                <p class="text-muted small mb-1">{{ __('messages.success_greeting') }}</p>

                {{-- PRESTIGIOUS DIGITAL REGISTRATION PASS --}}
                <div class="ashram-digital-pass">
                    <div class="pass-watermark" aria-hidden="true">ॐ</div>
                    <div>
                        <span class="pass-header-ribbon">
                            <i class="bi bi-shield-check me-1"></i>{{ __('messages.digital_pass_title') }}
                        </span>
                    </div>

                    <div class="pass-code-display" id="regCodeText">
                        {{ $bhakt->display_reg_number }}
                    </div>

                    <div class="pass-barcode"></div>
                    <small class="text-muted d-block font-monospace" style="font-size:0.75rem;">
                        {{ __('messages.pass_verified') }} • {{ date('d-M-Y H:i') }}
                    </small>

                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-outline-dark" onclick="copyRegId();">
                            <i class="bi bi-clipboard me-1" id="copyIcon"></i>
                            <span id="copyBtnText">{{ __('messages.copy_pass') }}</span>
                        </button>
                    </div>

                    <small class="text-muted d-block mt-3 pt-2 border-top">
                        📌 {{ __('messages.pending_note') }}
                    </small>
                </div>

                {{-- TIP --}}
                <div class="alert alert-info py-2 px-3 small text-start d-flex align-items-center gap-2 mt-3 mb-0">
                    <i class="bi bi-lightbulb-fill text-warning fs-5"></i>
                    <div>
                        {{ app()->getLocale() === 'hi' ? 'सुझाव: आप कभी भी केवल अपने पंजीकृत मोबाइल नंबर (+91 ' . $bhakt->mobile . ') से भी अपनी आवेदन स्थिति देख सकते हैं।' : 'Tip: You can check your status anytime using just your registered mobile number (+91 ' . $bhakt->mobile . ').' }}
                    </div>
                </div>

                {{-- SUMMARY DETAILS --}}
                <div class="row g-2 text-start mt-3 p-3 rounded" style="background:var(--light-bg); border:1px solid #ebd9c8;">
                    <div class="col-6">
                        <span class="text-muted small">{{ __('messages.full_name') }}:</span><br>
                        <strong>{{ $bhakt->display_name }}</strong>
                    </div>
                    <div class="col-6">
                        <span class="text-muted small">{{ __('messages.mobile') }}:</span><br>
                        <strong>{{ $bhakt->mobile }}</strong>
                    </div>
                    @if($bhakt->city || $bhakt->district)
                    <div class="col-6">
                        <span class="text-muted small">{{ __('messages.city') }} / {{ __('messages.district') }}:</span><br>
                        <strong>{{ implode(', ', array_filter([$bhakt->display_city, $bhakt->display_district])) }}</strong>
                    </div>
                    @endif
                    @if($bhakt->branch)
                    <div class="col-6">
                        <span class="text-muted small">{{ __('messages.branch') }}:</span><br>
                        <strong>{{ $bhakt->display_branch }}</strong>
                    </div>
                    @endif
                    @if($bhakt->diksha_taken)
                    <div class="col-12 mt-1">
                        <span class="badge" style="background:var(--maroon);">
                            <i class="bi bi-stars me-1"></i>{{ __('messages.dikshit') }}
                        </span>
                    </div>
                    @endif
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="d-flex gap-2 mt-4 justify-content-center flex-wrap no-print">
                    {{-- Share / Save WhatsApp --}}
                    @php
                        $shareText = "ॐ *" . __('messages.ashram_full_name') . "*\n"
                                   . "📋 *" . __('messages.digital_pass_title') . "*\n"
                                   . "• Name: " . $bhakt->display_name . "\n"
                                   . "• Pass No: " . $bhakt->display_reg_number . "\n"
                                   . "• Mobile: +91 " . $bhakt->mobile . "\n"
                                   . "• Status Tracking: " . route('status', ['mobile' => $bhakt->mobile]);
                    @endphp
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($shareText) }}"
                       target="_blank" class="btn btn-success shadow-sm">
                        <i class="bi bi-whatsapp me-1"></i>{{ __('messages.share_whatsapp') }}
                    </a>

                    {{-- Print Slip --}}
                    <button type="button" class="btn btn-outline-secondary shadow-sm" onclick="window.print();">
                        <i class="bi bi-printer me-1"></i>{{ __('messages.print_slip') }}
                    </button>

                    {{-- Check Status Link --}}
                    <a href="{{ route('status', ['mobile' => $bhakt->mobile]) }}" class="btn btn-maroon shadow-sm">
                        <i class="bi bi-search me-1"></i>{{ __('messages.check_status_link') }}
                    </a>
                </div>

                <div class="mt-3 no-print">
                    <a href="{{ route('register') }}" class="text-muted small text-decoration-none">
                        <i class="bi bi-plus-circle me-1"></i>{{ __('messages.register_another') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyRegId() {
    var regId = "{{ $bhakt->display_reg_number }}";
    navigator.clipboard.writeText(regId).then(function() {
        var btnText = document.getElementById('copyBtnText');
        var copyIcon = document.getElementById('copyIcon');
        if (btnText) btnText.textContent = "{{ __('messages.copied') }}";
        if (copyIcon) copyIcon.className = "bi bi-check-lg text-success";
        setTimeout(function() {
            if (btnText) btnText.textContent = "{{ __('messages.copy_pass') }}";
            if (copyIcon) copyIcon.className = "bi bi-clipboard";
        }, 2500);
    });
}
</script>
@endpush
