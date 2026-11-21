<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.ashram_full_name') }} — {{ $bhakt->display_reg_number }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --saffron: #FF6B00;
            --maroon:  #7B1E1E;
            --gold:    #D4AF37;
        }
        body {
            background: #f7f3ed;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #2b1708;
            margin: 0;
            padding: 0;
        }

        /* Top Action Bar (Fixed, hidden during print) */
        .top-action-bar {
            background: #fff;
            border-bottom: 2px solid var(--gold);
            padding: 12px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Receipt Sheet */
        .receipt-container {
            max-width: 780px;
            margin: 24px auto 40px;
            padding: 0 16px;
        }
        .receipt-card {
            background: #fff;
            border: 3px double var(--gold);
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
            padding: 32px;
        }

        /* Background Watermark */
        .receipt-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 16rem;
            color: var(--gold);
            opacity: 0.05;
            pointer-events: none;
            user-select: none;
            line-height: 1;
            z-index: 0;
        }

        .receipt-content {
            position: relative;
            z-index: 1;
        }

        .header-emblem {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--saffron), var(--gold));
            color: #fff;
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1;
        }

        .receipt-title-box {
            background: #fffaf4;
            border: 1px dashed var(--gold);
            border-radius: 10px;
            padding: 14px 20px;
            margin: 20px 0;
            text-align: center;
        }

        .reg-number-highlight {
            font-family: 'Courier New', Courier, monospace;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 2px;
            color: var(--maroon);
            margin: 6px 0 0;
        }

        .table-receipt {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }
        .table-receipt td {
            padding: 9px 12px;
            border-bottom: 1px solid #f0e4d7;
            font-size: 0.95rem;
        }
        .table-receipt td.label {
            width: 32%;
            font-weight: 600;
            color: #633e24;
            background: #fdfaf7;
        }
        .table-receipt td.value {
            font-weight: 500;
            color: #2b1708;
        }

        .barcode-box {
            height: 32px;
            margin: 16px auto 6px;
            background: repeating-linear-gradient(
                90deg,
                #333 0px, #333 2px,
                transparent 2px, transparent 4px,
                #333 4px, #333 7px,
                transparent 7px, transparent 10px,
                #333 10px, #333 11px,
                transparent 11px, transparent 15px
            );
            width: 260px;
            max-width: 90%;
            opacity: 0.75;
        }

        .ashram-seal {
            border: 2px dashed var(--maroon);
            color: var(--maroon);
            border-radius: 50%;
            width: 95px;
            height: 95px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 8px;
            line-height: 1.2;
            opacity: 0.85;
            transform: rotate(-10deg);
            margin: 0 auto;
        }

        /* PRINT STYLES */
        @media print {
            body { background: #fff !important; }
            .top-action-bar, .no-print { display: none !important; }
            .receipt-container { margin: 0 !important; max-width: 100% !important; padding: 0 !important; }
            .receipt-card { border: 2px solid #333 !important; box-shadow: none !important; border-radius: 0 !important; padding: 20px !important; }
            .reg-number-highlight { color: #000 !important; }
            .receipt-watermark { display: none !important; }
        }
    </style>
</head>
<body>

    <!-- TOP ACTION TOOLBAR (Hidden when printing) -->
    <div class="top-action-bar d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('register.success', $bhakt) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>{{ __('messages.back') }}
            </a>
            <span class="fw-bold d-none d-md-inline" style="color:var(--maroon);">
                {{ __('messages.digital_pass_title') }}
            </span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-success btn-sm shadow-sm" onclick="window.print();">
                <i class="bi bi-printer-fill me-1"></i>{{ __('messages.print_slip') }}
            </button>
            <a href="{{ route('status', ['mobile' => $bhakt->mobile]) }}" class="btn btn-outline-dark btn-sm">
                <i class="bi bi-search me-1"></i>{{ __('messages.check_status_link') }}
            </a>
        </div>
    </div>

    <!-- MAIN RECEIPT SHEET -->
    <div class="receipt-container">
        <div class="receipt-card" id="printableCard">
            <div class="receipt-watermark" aria-hidden="true">ॐ</div>

            <div class="receipt-content">
                <!-- Ashram Letterhead -->
                <div class="text-center pb-2 border-bottom">
                    <div class="header-emblem mb-2">ॐ</div>
                    <h3 class="fw-bold mb-1" style="color:var(--maroon);">
                        {{ __('messages.ashram_full_name') }}
                    </h3>
                    <p class="text-muted small mb-0">{{ __('messages.tagline') }}</p>
                    <span class="badge px-3 py-1 mt-2 text-uppercase" style="background:var(--maroon); font-size:0.75rem; letter-spacing:1px;">
                        {{ __('messages.digital_pass_title') }}
                    </span>
                </div>

                <!-- Registration Pass Box -->
                <div class="receipt-title-box">
                    <span class="text-muted small text-uppercase fw-semibold d-block">
                        {{ __('messages.registration_number') }} / Official Devotee Pass
                    </span>
                    <div class="reg-number-highlight">
                        {{ $bhakt->display_reg_number }}
                    </div>
                    <div class="barcode-box"></div>
                    <small class="text-muted font-monospace" style="font-size:0.75rem;">
                        {{ __('messages.pass_verified') }} • {{ $bhakt->created_at->format('d-M-Y H:i') }}
                    </small>
                </div>

                <!-- Status Badge -->
                <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                    <span class="small text-muted">
                        <strong>{{ __('messages.status_col') }}:</strong>
                        <span class="badge badge-status-{{ $bhakt->status }} ms-1">
                            {{ $bhakt->display_status }}
                        </span>
                    </span>
                    <span class="small text-muted">
                        <i class="bi bi-calendar-check me-1"></i>{{ $bhakt->created_at->format('d M Y') }}
                    </span>
                </div>

                <!-- Devotee Details Table -->
                <table class="table-receipt">
                    <tbody>
                        <tr>
                            <td class="label">{{ __('messages.full_name') }}</td>
                            <td class="value fw-bold" style="color:var(--maroon); font-size:1.05rem;">
                                {{ $bhakt->display_name }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">{{ __('messages.mobile') }}</td>
                            <td class="value font-monospace fw-bold">+91 {{ $bhakt->mobile }}</td>
                        </tr>
                        @if($bhakt->email)
                        <tr>
                            <td class="label">{{ __('messages.email') }}</td>
                            <td class="value">{{ $bhakt->email }}</td>
                        </tr>
                        @endif
                        @if($bhakt->gender)
                        <tr>
                            <td class="label">{{ __('messages.gender') }}</td>
                            <td class="value">{{ ucfirst($bhakt->gender) }}</td>
                        </tr>
                        @endif
                        @if($bhakt->dob)
                        <tr>
                            <td class="label">{{ __('messages.dob') }}</td>
                            <td class="value">{{ $bhakt->dob->format('d M Y') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="label">{{ __('messages.branch') }}</td>
                            <td class="value fw-semibold">{{ $bhakt->display_branch }}</td>
                        </tr>
                        @if($bhakt->city || $bhakt->district || $bhakt->state)
                        <tr>
                            <td class="label">{{ __('messages.state') }}</td>
                            <td class="value">
                                {{ implode(', ', array_filter([$bhakt->display_city, $bhakt->display_district, $bhakt->display_state])) ?: '—' }}
                            </td>
                        </tr>
                        @endif
                        @if($bhakt->address)
                        <tr>
                            <td class="label">{{ __('messages.address') }}</td>
                            <td class="value small">{{ $bhakt->address }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="label">{{ __('messages.diksha') }}</td>
                            <td class="value">
                                @if($bhakt->diksha_taken)
                                    <span class="badge" style="background:var(--maroon);">
                                        <i class="bi bi-stars me-1"></i>{{ __('messages.dikshit') }}
                                    </span>
                                    @if($bhakt->diksha_date)
                                        <span class="ms-2 small text-muted">({{ $bhakt->diksha_date->format('d M Y') }})</span>
                                    @endif
                                @else
                                    <span class="text-muted">{{ __('messages.not_dikshit') }}</span>
                                @endif
                            </td>
                        </tr>
                        @if($bhakt->seva)
                        <tr>
                            <td class="label">{{ __('messages.seva') }}</td>
                            <td class="value">{{ $bhakt->seva }}</td>
                        </tr>
                        @endif
                        @if($bhakt->bhakt_id)
                        <tr style="background:#eef7f0;">
                            <td class="label" style="color:#198754;">{{ __('messages.bhakt_id') }}</td>
                            <td class="value fw-bold font-monospace text-success fs-5">
                                <i class="bi bi-patch-check-fill me-1"></i>{{ $bhakt->bhakt_id }}
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <!-- Bottom Seal & Verification -->
                <div class="row align-items-center mt-4 pt-3 border-top g-3">
                    <div class="col-8">
                        <p class="small text-muted mb-1">
                            📌 <strong>{{ __('messages.notes') ?? 'Note' }}:</strong> {{ __('messages.pending_note') }}
                        </p>
                        <p class="small text-muted mb-0">
                            🔍 Track registration status anytime with your registered mobile at:
                            <br><strong class="font-monospace">{{ route('status') }}</strong>
                        </p>
                    </div>
                    <div class="col-4 text-center">
                        <div class="ashram-seal">
                            SHRI ANANDAM DHAM<br>★ VERIFIED ★<br>REGISTRY
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if(request()->has('print'))
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 300);
        });
    </script>
    @endif

</body>
</html>
