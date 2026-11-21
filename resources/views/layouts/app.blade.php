<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.bhakt_registration')) — {{ __('messages.ashram_name') }}</title>
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --saffron:   #FF6B00;
            --dark-saf:  #c05200;
            --maroon:    #7B1E1E;
            --gold:      #D4AF37;
            --cream:     #FFF8F0;
            --light-bg:  #FFF3E6;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--cream);
            color: #2c1a0e;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ---- NAVBAR ---- */
        .navbar-ashram {
            background: linear-gradient(135deg, var(--maroon) 0%, #5c1414 100%);
            border-bottom: 3px solid var(--gold);
            padding: 0.6rem 1.5rem;
        }
        .navbar-ashram .navbar-brand {
            color: var(--gold) !important;
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: 0.5px;
        }
        .navbar-ashram .navbar-brand small {
            color: #f5d8a0;
            font-size: 0.72rem;
            display: block;
            font-weight: 400;
            line-height: 1.2;
        }
        .navbar-ashram .nav-link {
            color: #f8dba0 !important;
            font-size: 0.9rem;
            padding: 0.5rem 0.8rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .navbar-ashram .nav-link:hover, .navbar-ashram .nav-link.active {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.1);
        }
        .navbar-ashram .navbar-toggler { border-color: var(--gold); }
        .navbar-ashram .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28212,175,55,1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* ---- LANGUAGE SELECTOR PILLS ---- */
        .lang-switch-box {
            background: rgba(0, 0, 0, 0.25);
            padding: 3px;
            border-radius: 25px;
            display: inline-flex;
            gap: 2px;
            border: 1px solid rgba(212, 175, 55, 0.4);
        }
        .lang-btn {
            font-size: 0.8rem;
            padding: 3px 12px;
            border-radius: 20px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            display: inline-block;
        }
        .lang-btn.active-lang {
            background: var(--gold);
            color: var(--maroon) !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        }
        .lang-btn:not(.active-lang) {
            background: transparent;
            color: #f8dba0 !important;
        }
        .lang-btn:not(.active-lang):hover {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.15);
        }

        /* ---- OM BANNER ---- */
        .om-banner {
            background: linear-gradient(135deg, var(--maroon) 0%, #5c1414 100%);
            color: var(--gold);
            text-align: center;
            padding: 22px 10px 16px;
            border-bottom: 2px solid var(--gold);
        }
        .om-banner .om-symbol { font-size: 2.6rem; line-height: 1; }
        .om-banner h1 { font-size: 1.4rem; font-weight: 700; color: #fff; margin: 6px 0 3px; }
        .om-banner p  { font-size: 0.86rem; color: #f5d8a0; margin: 0; }

        /* ---- CARDS ---- */
        .card-ashram {
            border: 1px solid #ebd9c8;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            background: #fff;
        }
        .card-ashram .card-header {
            background: linear-gradient(90deg, var(--saffron), #e55a00);
            color: #fff;
            border-radius: 13px 13px 0 0;
            font-weight: 600;
            padding: 14px 20px;
        }

        /* ---- FORM ---- */
        .form-label { font-weight: 600; font-size: 0.88rem; color: #4a2107; }
        .form-control, .form-select {
            border-color: #d6c1ac;
            border-radius: 8px;
            font-size: 0.95rem;
            padding: 0.6rem 0.85rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--saffron);
            box-shadow: 0 0 0 3px rgba(255,107,0,.15);
        }
        .section-divider {
            border-left: 4px solid var(--saffron);
            padding-left: 12px;
            margin: 24px 0 16px;
            font-weight: 700;
            color: var(--maroon);
            font-size: 1.02rem;
            background: #fffaf5;
            padding-top: 6px;
            padding-bottom: 6px;
            border-radius: 0 6px 6px 0;
        }

        /* ---- BUTTONS ---- */
        .btn-saffron {
            background: linear-gradient(135deg, var(--saffron), #e55a00);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all .2s;
        }
        .btn-saffron:hover { background: linear-gradient(135deg, #e55a00, var(--maroon)); color: #fff; }
        .btn-maroon {
            background: var(--maroon);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-maroon:hover { background: #5c1414; color: #fff; }

        /* ---- STATUS BADGE ---- */
        .badge-status-approved   { background:#198754; color:#fff; }
        .badge-status-pending    { background:#ffc107; color:#000; }
        .badge-status-rejected   { background:#dc3545; color:#fff; }
        .badge-status-correction { background:#0dcaf0; color:#000; }

        /* ---- STAT CARDS ---- */
        .stat-card { border-radius: 12px; padding: 18px 20px; color: #fff; }
        .stat-card .stat-number { font-size: 2rem; font-weight: 800; line-height: 1; }
        .stat-card .stat-label  { font-size: 0.82rem; opacity: .9; margin-top: 4px; }
        .bg-stat-1 { background: linear-gradient(135deg, #FF6B00, #c05200); }
        .bg-stat-2 { background: linear-gradient(135deg, #198754, #0d5c38); }
        .bg-stat-3 { background: linear-gradient(135deg, #ffc107, #c79a00); color: #000 !important; }
        .bg-stat-4 { background: linear-gradient(135deg, #dc3545, #9e1a28); }
        .bg-stat-5 { background: linear-gradient(135deg, #7B1E1E, #5c1414); }
        .bg-stat-6 { background: linear-gradient(135deg, #25D366, #128C7E); }

        /* ---- TABLE ---- */
        .table-ashram thead th {
            background: var(--maroon);
            color: var(--gold);
            font-weight: 600;
            border: none;
            padding: 10px 14px;
        }
        .table-ashram tbody tr:hover { background: var(--light-bg); }
        .table-ashram td { padding: 10px 14px; }

        /* ---- FOOTER ---- */
        footer {
            background: var(--maroon);
            color: #f5d8a0;
            text-align: center;
            padding: 16px;
            font-size: 0.85rem;
            margin-top: auto;
            border-top: 2px solid var(--gold);
        }

        .req { color: var(--saffron); }

        /* ---- COMPREHENSIVE RESPONSIVENESS (MOBILE, TABLET, DESKTOP) ---- */
        @media (max-width: 991.98px) {
            .navbar-ashram { padding: 0.5rem 1rem; }
            .navbar-ashram .navbar-collapse {
                background: #5c1414;
                padding: 1rem;
                border-radius: 10px;
                margin-top: 0.6rem;
                border: 1px solid rgba(212, 175, 55, 0.35);
            }
        }

        @media (max-width: 767.98px) {
            body { font-size: 0.95rem; }
            .form-control, .form-select {
                font-size: 16px !important; /* Prevents auto-zoom on iOS */
                min-height: 44px;
            }
            .btn {
                min-height: 44px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            .card-ashram .card-body { padding: 1.25rem !important; }
            .om-banner { padding: 16px 8px 12px; }
            .om-banner .om-symbol { font-size: 2rem; }
            .om-banner h1 { font-size: 1.15rem; }
            .stat-card { padding: 14px 12px; }
            .stat-card .stat-number { font-size: 1.6rem; }
            .table-responsive {
                border-radius: 8px;
                -webkit-overflow-scrolling: touch;
            }
        }

        /* ---- SACRED LOADING OVERLAY ANIMATION ---- */
        .ashram-loading-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            background: rgba(30, 8, 8, 0.93);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #fff;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.35s ease, visibility 0.35s ease;
            text-align: center;
            padding: 20px;
        }
        .ashram-loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .sacred-spinner-box {
            position: relative;
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }
        .sacred-ring {
            position: absolute;
            width: 100%; height: 100%;
            border-radius: 50%;
            border: 3px solid rgba(212, 175, 55, 0.2);
            border-top-color: #D4AF37;
            border-bottom-color: #FF6B00;
            animation: sacredSpin 1.4s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite;
        }
        .sacred-ring-outer {
            position: absolute;
            width: 135%; height: 135%;
            border-radius: 50%;
            border: 2px dashed rgba(212, 175, 55, 0.4);
            animation: sacredSpinRev 7s linear infinite;
        }
        .sacred-om-inner {
            font-size: 3.2rem;
            color: #D4AF37;
            animation: omPulse 1.4s ease-in-out infinite alternate;
            line-height: 1;
            text-shadow: 0 0 25px rgba(212, 175, 55, 0.85);
        }
        @keyframes sacredSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes sacredSpinRev {
            0% { transform: rotate(360deg); }
            100% { transform: rotate(0deg); }
        }
        @keyframes omPulse {
            0% { transform: scale(0.9); opacity: 0.85; filter: drop-shadow(0 0 8px #FF6B00); }
            100% { transform: scale(1.1); opacity: 1; filter: drop-shadow(0 0 26px #D4AF37); }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- FULLSCREEN SACRED GENERATION LOADER -->
<div id="sacredLoaderOverlay" class="ashram-loading-overlay">
    <div class="sacred-spinner-box">
        <div class="sacred-ring-outer"></div>
        <div class="sacred-ring"></div>
        <div class="sacred-om-inner">🕉</div>
    </div>
    <h4 class="fw-bold mb-2" style="color:var(--gold); font-size:1.35rem;" id="loaderTitle">
        {{ __('messages.loader_title') }}
    </h4>
    <p class="text-light small mb-3 opacity-75" style="max-width:380px; min-height:42px;" id="loaderStepText">
        {{ __('messages.loader_step1') }}
    </p>
    <div class="progress" style="width: 260px; height: 8px; background: rgba(255,255,255,0.15); border-radius: 20px; overflow: hidden;">
        <div id="loaderProgressBar" class="progress-bar progress-bar-striped progress-bar-animated"
             style="width: 15%; background: linear-gradient(90deg, var(--saffron), var(--gold)); transition: width 0.4s ease;"></div>
    </div>
</div>
<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-ashram sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('register') }}">
            <span style="font-size:1.4rem;">🕉</span>
            <div>
                <span>{{ __('messages.ashram_name') }}</span>
                <small>{{ __('messages.tagline') }}</small>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('register') ? 'active fw-bold' : '' }}"
                       href="{{ route('register') }}">
                        <i class="bi bi-pencil-square me-1"></i>{{ __('messages.register') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('status') ? 'active fw-bold' : '' }}"
                       href="{{ route('status') }}">
                        <i class="bi bi-search me-1"></i>{{ __('messages.status') }}
                    </a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active fw-bold' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i>{{ __('messages.dashboard') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.bhakts.*') ? 'active fw-bold' : '' }}"
                       href="{{ route('admin.bhakts.index') }}">
                        <i class="bi bi-people me-1"></i>{{ __('messages.all_bhakts') }}
                    </a>
                </li>
                @endauth
            </ul>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <!-- 100% Reliable Language Switcher -->
                <div class="lang-switch-box" title="{{ __('messages.language') }}">
                    <a href="{{ route('language.switch', 'en') }}?redirect={{ urlencode(request()->fullUrl()) }}"
                       class="lang-btn {{ app()->getLocale() === 'en' ? 'active-lang' : '' }}">EN</a>
                    <a href="{{ route('language.switch', 'hi') }}?redirect={{ urlencode(request()->fullUrl()) }}"
                       class="lang-btn {{ app()->getLocale() === 'hi' ? 'active-lang' : '' }}">हिं</a>
                </div>

                @auth
                <span class="text-warning small d-none d-md-inline">
                    <i class="bi bi-person-circle me-1"></i>{{ __('messages.admin_user_label') }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i>{{ __('messages.logout') }}
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-lock me-1"></i>{{ __('messages.login') }}
                </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- OM BANNER (public pages) -->
@hasSection('show_banner')
<div class="om-banner">
    <div class="om-symbol">🕉</div>
    <h1>{{ __('messages.ashram_full_name') }}</h1>
    <p>{{ __('messages.tagline') }}</p>
</div>
@endif

<!-- ALERTS -->
<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

<!-- MAIN CONTENT -->
<main class="@yield('main_class', 'container py-4 flex-grow-1')">
    @yield('content')
</main>

<footer>
    🕉 {{ __('messages.ashram_full_name') }} &nbsp;|&nbsp; {{ __('messages.tagline') }} &nbsp;|&nbsp;
    &copy; {{ date('Y') }}
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
