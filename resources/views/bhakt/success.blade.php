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

                    {{-- 1. Print / Save Receipt (Opens official printable receipt in new tab - 100% reliable on every phone & browser) --}}
                    <a href="{{ route('register.receipt', $bhakt) }}" target="_blank" class="btn btn-maroon shadow-sm">
                        <i class="bi bi-printer-fill me-1"></i>{{ __('messages.print_slip') }}
                    </a>

                    {{-- 2. Save Pass Image (Modal with direct save to photos / download) --}}
                    <button type="button" class="btn btn-outline-secondary shadow-sm" onclick="showPassImageModal();">
                        <i class="bi bi-image me-1"></i>{{ __('messages.save_receipt') }}
                    </button>

                    {{-- 3. Share WhatsApp --}}
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($shareText) }}"
                       target="_blank" class="btn btn-success shadow-sm">
                        <i class="bi bi-whatsapp me-1"></i>{{ __('messages.share_whatsapp') }}
                    </a>

                    {{-- 4. Check Status Link --}}
                    <a href="{{ route('status', ['mobile' => $bhakt->mobile]) }}" class="btn btn-outline-dark shadow-sm">
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

{{-- MODAL FOR SAVING PASS IMAGE (Mobile & Desktop friendly) --}}
<div class="modal fade" id="passImageModal" tabindex="-1" aria-labelledby="passImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border: 2px solid var(--gold); border-radius: 14px;">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--maroon), #5c1414); color: #fff;">
                <h6 class="modal-title fw-bold" id="passImageModalLabel">
                    <i class="bi bi-shield-check me-2 text-warning"></i>{{ __('messages.digital_pass_title') }}
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <p class="text-muted small mb-2">
                    <i class="bi bi-info-circle me-1 text-primary"></i>
                    <strong>Mobile:</strong> Tap and hold the image below, then choose <em>"Save to Photos"</em> or <em>"Save Image"</em>.
                </p>
                <div class="p-2 bg-light rounded border text-center mb-3">
                    <img id="passModalImg" src="" alt="Pass Receipt" class="img-fluid rounded shadow-sm" style="max-height: 400px; display: none;">
                    <div id="passImgSpinner" class="py-5">
                        <div class="spinner-border text-warning" role="status"></div>
                        <p class="small text-muted mt-2">Generating High-Resolution Pass...</p>
                    </div>
                </div>
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <a id="modalDownloadLink" href="#" download="Receipt_{{ $bhakt->display_reg_number }}.png" class="btn btn-success btn-sm">
                        <i class="bi bi-download me-1"></i>Download PNG Image
                    </a>
                    <a href="{{ route('register.receipt', $bhakt) }}" target="_blank" class="btn btn-maroon btn-sm">
                        <i class="bi bi-printer me-1"></i>Open Full Printable Slip
                    </a>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
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

function showPassImageModal() {
    var modalEl = document.getElementById('passImageModal');
    if (!modalEl) return;
    var modal = new bootstrap.Modal(modalEl);
    modal.show();

    var img = document.getElementById('passModalImg');
    var spinner = document.getElementById('passImgSpinner');
    var downloadLink = document.getElementById('modalDownloadLink');

    if (img.src && img.src.startsWith('data:image')) {
        img.style.display = 'inline-block';
        if (spinner) spinner.style.display = 'none';
        return;
    }

    setTimeout(function() {
        try {
            var canvas = document.createElement('canvas');
            canvas.width = 800;
            canvas.height = 540;
            var ctx = canvas.getContext('2d');

            var devoteeName = {!! json_encode($bhakt->display_name) !!};
            var regNumber   = {!! json_encode($bhakt->display_reg_number) !!};
            var mobile      = "+91 {{ $bhakt->mobile }}";
            var branch      = {!! json_encode($bhakt->display_branch) !!};
            var place       = {!! json_encode(implode(', ', array_filter([$bhakt->display_city, $bhakt->display_state])) ?: '—') !!};
            var ashramTitle = {!! json_encode(__('messages.ashram_full_name')) !!};
            var passTitle   = {!! json_encode(__('messages.digital_pass_title')) !!};
            var verified    = {!! json_encode(__('messages.pass_verified') . ' • ' . date('d-M-Y H:i')) !!};

            // Background
            ctx.fillStyle = '#FFFDF9';
            ctx.fillRect(0, 0, 800, 540);

            // Outer Gold Border
            ctx.strokeStyle = '#D4AF37';
            ctx.lineWidth = 4;
            drawRoundRect(ctx, 12, 12, 776, 516, 18);
            ctx.stroke();

            // Inner Gold Accent
            ctx.strokeStyle = '#E8D29A';
            ctx.lineWidth = 1;
            drawRoundRect(ctx, 18, 18, 764, 504, 14);
            ctx.stroke();

            // Header Maroon Ribbon
            ctx.fillStyle = '#7B1E1E';
            drawRoundRect(ctx, 18, 18, 764, 88, [14, 14, 0, 0]);
            ctx.fill();

            // Ashram Header Text
            ctx.fillStyle = '#FFFFFF';
            ctx.font = 'bold 24px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('ॐ  ' + ashramTitle, 400, 52);

            ctx.fillStyle = '#F5D8A0';
            ctx.font = '14px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif';
            ctx.fillText('🛡 ' + passTitle, 400, 80);

            // Watermark Om
            ctx.fillStyle = 'rgba(212, 175, 55, 0.08)';
            ctx.font = 'bold 180px serif';
            ctx.textAlign = 'center';
            ctx.fillText('ॐ', 400, 340);

            // Registration Pass Code Box
            ctx.fillStyle = '#FFFFFF';
            drawRoundRect(ctx, 100, 126, 600, 75, 12);
            ctx.fill();
            ctx.strokeStyle = '#D4AF37';
            ctx.lineWidth = 2;
            ctx.setLineDash([6, 4]);
            drawRoundRect(ctx, 100, 126, 600, 75, 12);
            ctx.stroke();
            ctx.setLineDash([]);

            // Registration Number
            ctx.fillStyle = '#7B1E1E';
            ctx.font = 'bold 34px "Courier New", monospace';
            ctx.textAlign = 'center';
            ctx.fillText(regNumber, 400, 175);

            // Barcode Graphic
            var barcodeY = 222;
            var barcodeX = 230;
            ctx.fillStyle = '#222222';
            var pattern = [2, 1, 3, 2, 1, 4, 2, 1, 3, 1, 2, 4, 1, 3, 2, 1, 2, 3, 1, 4, 2, 1, 3, 2, 1, 4, 1, 2, 3, 1, 2, 3, 1];
            var curX = barcodeX;
            for (var i = 0; i < pattern.length; i++) {
                ctx.fillRect(curX, barcodeY, pattern[i] * 2, 26);
                curX += (pattern[i] * 2) + 4;
            }

            // Devotee Details Panel
            var startY = 275;
            ctx.fillStyle = '#FFF8F0';
            drawRoundRect(ctx, 40, startY, 720, 165, 12);
            ctx.fill();
            ctx.strokeStyle = '#E8D5C2';
            ctx.lineWidth = 1;
            drawRoundRect(ctx, 40, startY, 720, 165, 12);
            ctx.stroke();

            // Grid Details
            ctx.textAlign = 'left';

            // Col 1: Name & Mobile
            ctx.fillStyle = '#7a6a58';
            ctx.font = '13px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif';
            ctx.fillText("{{ __('messages.name') }}:", 70, startY + 38);
            ctx.fillStyle = '#2c1a0e';
            ctx.font = 'bold 20px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif';
            ctx.fillText(devoteeName, 70, startY + 66);

            ctx.fillStyle = '#7a6a58';
            ctx.font = '13px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif';
            ctx.fillText("{{ __('messages.mobile') }}:", 70, startY + 110);
            ctx.fillStyle = '#2c1a0e';
            ctx.font = 'bold 18px "Courier New", monospace';
            ctx.fillText(mobile, 70, startY + 138);

            // Col 2: Branch & Location
            ctx.fillStyle = '#7a6a58';
            ctx.font = '13px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif';
            ctx.fillText("{{ __('messages.branch') }}:", 440, startY + 38);
            ctx.fillStyle = '#2c1a0e';
            ctx.font = 'bold 18px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif';
            ctx.fillText(branch, 440, startY + 66);

            ctx.fillStyle = '#7a6a58';
            ctx.font = '13px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif';
            ctx.fillText("{{ __('messages.state') }}:", 440, startY + 110);
            ctx.fillStyle = '#2c1a0e';
            ctx.font = 'bold 18px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif';
            ctx.fillText(place, 440, startY + 138);

            // Verified Footer
            ctx.fillStyle = '#8c7664';
            ctx.font = '13px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(verified, 400, 480);

            var dataUrl = canvas.toDataURL('image/png');
            img.src = dataUrl;
            img.style.display = 'inline-block';
            if (spinner) spinner.style.display = 'none';
            if (downloadLink) downloadLink.href = dataUrl;
        } catch(e) {
            console.error("Canvas error: ", e);
            window.location.href = "{{ route('register.receipt', $bhakt) }}";
        }
    }, 120);
}

function drawRoundRect(ctx, x, y, width, height, radius) {
    if (typeof radius === 'undefined') radius = 5;
    if (typeof radius === 'number') {
        radius = {tl: radius, tr: radius, br: radius, bl: radius};
    } else if (Array.isArray(radius)) {
        radius = {tl: radius[0], tr: radius[1], br: radius[2] || 0, bl: radius[3] || 0};
    }
    ctx.beginPath();
    ctx.moveTo(x + radius.tl, y);
    ctx.lineTo(x + width - radius.tr, y);
    ctx.quadraticCurveTo(x + width, y, x + width, y + radius.tr);
    ctx.lineTo(x + width, y + height - radius.br);
    ctx.quadraticCurveTo(x + width, y + height, x + width - radius.br, y + height);
    ctx.lineTo(x + radius.bl, y + height);
    ctx.quadraticCurveTo(x, y + height, x, y + height - radius.bl);
    ctx.lineTo(x, y + radius.tl);
    ctx.quadraticCurveTo(x, y, x + radius.tl, y);
    ctx.closePath();
}
</script>
@endpush
