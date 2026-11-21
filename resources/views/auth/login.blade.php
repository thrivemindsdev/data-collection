@extends('layouts.app')
@section('title', __('messages.login') . ' — Admin')

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-5 col-lg-4">
        <div class="card-ashram card">
            <div class="card-header text-center">
                <i class="bi bi-shield-lock-fill fs-4 me-2"></i>{{ __('messages.admin_panel') }}
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-3">
                    <div class="ashram-logo-emblem mx-auto mb-2" style="width:58px; height:58px; font-size:2.2rem; border-radius:14px;">ॐ</div>
                    <p class="text-muted small">{{ __('messages.ashram_name') }} — {{ __('messages.staff_role') }} {{ __('messages.login') }}</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.login') }} Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email') }}" required autofocus
                                   placeholder="admin@ashram.in">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.password') }}</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-key"></i></span>
                            <input type="password" name="password" class="form-control"
                                   required placeholder="••••••••">
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember">
                        <label class="form-check-label" for="remember">{{ __('messages.remember_me') }}</label>
                    </div>
                    <button type="submit" class="btn btn-saffron w-100 py-2">
                        <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('messages.login') }}
                    </button>
                </form>

                <div class="mt-3 text-center">
                    <a href="{{ route('register') }}" class="text-muted small">
                        <i class="bi bi-arrow-left me-1"></i>{{ __('messages.back_to_register') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
