<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Receipt - {{ $bhakt->display_reg_number }}</title>
    <style>
        @page {
            margin: 12mm 14mm;
            size: A4 portrait;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #2b1708;
            font-size: 11pt;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        .receipt-box {
            border: 2px solid #D4AF37;
            padding: 18px 22px;
            position: relative;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #7B1E1E;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }

        .ashram-title {
            color: #7B1E1E;
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
            text-align: center;
        }

        .ashram-subtitle {
            color: #D4AF37;
            font-size: 10pt;
            font-weight: bold;
            margin: 3px 0 0;
            text-align: center;
            letter-spacing: 1px;
        }

        .doc-badge {
            background-color: #7B1E1E;
            color: #ffffff;
            padding: 4px 14px;
            font-size: 9pt;
            font-weight: bold;
            text-align: center;
            margin: 10px auto 0;
            display: inline-block;
        }

        .pass-box {
            background-color: #FFFDF9;
            border: 1px dashed #D4AF37;
            padding: 12px;
            text-align: center;
            margin: 14px 0;
        }

        .pass-label {
            font-size: 9pt;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: bold;
        }

        .reg-number {
            font-size: 20pt;
            font-weight: bold;
            color: #7B1E1E;
            letter-spacing: 2px;
            margin: 4px 0;
            font-family: 'Courier New', monospace;
        }

        .verified-time {
            font-size: 8pt;
            color: #8c7664;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        .details-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #ebd9c8;
            font-size: 9.5pt;
        }

        .details-table td.label-col {
            width: 32%;
            background-color: #fdfaf7;
            color: #5a3820;
            font-weight: bold;
        }

        .details-table td.val-col {
            color: #2b1708;
        }

        .bhakt-id-row {
            background-color: #eef7f0 !important;
        }

        .footer-table {
            width: 100%;
            margin-top: 20px;
            padding-top: 12px;
            border-top: 1px solid #ebd9c8;
        }

        .seal-box {
            border: 2px dashed #7B1E1E;
            color: #7B1E1E;
            padding: 8px 10px;
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
            line-height: 1.2;
        }

        .note-text {
            font-size: 8pt;
            color: #6c757d;
            line-height: 1.3;
        }
    </style>
</head>
<body>

<div class="receipt-box">

    <!-- Header -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <div class="ashram-title">ॐ {{ __('messages.ashram_full_name') }}</div>
                <div class="ashram-subtitle">{{ __('messages.tagline') }}</div>
                <div style="text-align:center; margin-top:8px;">
                    <span class="doc-badge">{{ __('messages.digital_pass_title') }}</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Registration Pass Box -->
    <div class="pass-box">
        <div class="pass-label">{{ __('messages.registration_number') }} / Official Devotee Pass</div>
        <div class="reg-number">{{ $bhakt->display_reg_number }}</div>
        <div class="verified-time">{{ __('messages.pass_verified') }} • {{ $bhakt->created_at?->format('d-M-Y H:i') ?? date('d-M-Y H:i') }}</div>
    </div>

    <!-- Particulars Table -->
    <table class="details-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="label-col">{{ __('messages.full_name') }}</td>
            <td class="val-col" style="font-weight:bold; color:#7B1E1E; font-size:11pt;">
                {{ $bhakt->display_name }}
            </td>
        </tr>
        <tr>
            <td class="label-col">{{ __('messages.mobile') }}</td>
            <td class="val-col" style="font-weight:bold;">+91 {{ $bhakt->mobile }}</td>
        </tr>
        @if($bhakt->email)
        <tr>
            <td class="label-col">{{ __('messages.email') }}</td>
            <td class="val-col">{{ $bhakt->email }}</td>
        </tr>
        @endif
        @if($bhakt->gender)
        <tr>
            <td class="label-col">{{ __('messages.gender') }}</td>
            <td class="val-col">{{ ucfirst($bhakt->gender) }}</td>
        </tr>
        @endif
        @if($bhakt->dob)
        <tr>
            <td class="label-col">{{ __('messages.dob') }}</td>
            <td class="val-col">{{ $bhakt->dob->format('d M Y') }}</td>
        </tr>
        @endif
        <tr>
            <td class="label-col">{{ __('messages.branch') }}</td>
            <td class="val-col" style="font-weight:bold;">{{ $bhakt->display_branch }}</td>
        </tr>
        @if($bhakt->city || $bhakt->district || $bhakt->state)
        <tr>
            <td class="label-col">{{ __('messages.state') }}</td>
            <td class="val-col">
                {{ implode(', ', array_filter([$bhakt->display_city, $bhakt->display_district, $bhakt->display_state])) ?: '—' }}
            </td>
        </tr>
        @endif
        @if($bhakt->address)
        <tr>
            <td class="label-col">{{ __('messages.address') }}</td>
            <td class="val-col">{{ $bhakt->address }}</td>
        </tr>
        @endif
        <tr>
            <td class="label-col">{{ __('messages.diksha') }}</td>
            <td class="val-col">
                {{ $bhakt->diksha_taken ? __('messages.dikshit') : __('messages.not_dikshit') }}
                @if($bhakt->diksha_taken && $bhakt->diksha_date)
                    ({{ $bhakt->diksha_date->format('d M Y') }})
                @endif
            </td>
        </tr>
        @if($bhakt->seva)
        <tr>
            <td class="label-col">{{ __('messages.seva') }}</td>
            <td class="val-col">{{ $bhakt->seva }}</td>
        </tr>
        @endif
        <tr>
            <td class="label-col">{{ __('messages.status_col') }}</td>
            <td class="val-col" style="font-weight:bold;">
                {{ $bhakt->display_status }}
            </td>
        </tr>
        @if($bhakt->bhakt_id)
        <tr class="bhakt-id-row">
            <td class="label-col" style="color:#198754;">{{ __('messages.bhakt_id') }}</td>
            <td class="val-col" style="color:#198754; font-weight:bold; font-size:12pt;">
                ★ {{ $bhakt->bhakt_id }}
            </td>
        </tr>
        @endif
    </table>

    <!-- Footer & Notes -->
    <table class="footer-table" cellpadding="0" cellspacing="0">
        <tr>
            <td width="72%" valign="middle" class="note-text">
                <strong>{{ __('messages.notes') ?? 'Note' }}:</strong> {{ __('messages.pending_note') }}
                <br>
                Track application status anytime at: <strong>https://form.zodimithra.com/status</strong>
            </td>
            <td width="28%" align="right" valign="middle">
                <div class="seal-box">
                    SHRI ANANDAM DHAM<br>
                    ★ VERIFIED ★<br>
                    REGISTRY PASS
                </div>
            </td>
        </tr>
    </table>

</div>

</body>
</html>
