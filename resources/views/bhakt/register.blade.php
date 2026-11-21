@extends('layouts.app')
@section('title', __('messages.bhakt_registration'))
@section('show_banner', true)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">

        @if($errors->any())
            <div class="alert alert-danger shadow-sm">
                <strong><i class="bi bi-exclamation-triangle-fill me-1"></i>{{ __('messages.fix_errors') }}</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-ashram card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-person-plus-fill me-2"></i>{{ __('messages.bhakt_registration') }}
                </span>
                <small class="opacity-75">ॐ {{ __('messages.ashram_name') }}</small>
            </div>
            <div class="card-body p-4">

                <form method="POST" action="{{ route('register.store') }}" id="regForm" novalidate>
                    @csrf

                    {{-- ============ PERSONAL DETAILS ============ --}}
                    <div class="section-divider">
                        <i class="bi bi-person-circle me-2"></i>{{ __('messages.personal_details') }}
                    </div>

                    <div class="row g-3">
                        {{-- Name --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.full_name') }} <span class="req">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required maxlength="120"
                                   placeholder="{{ app()->getLocale() === 'hi' ? 'उदा. राधेश्याम शर्मा' : 'e.g. Radheshyam Sharma' }}">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Mobile (100% digits only, no alphabets) --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.mobile') }} <span class="req">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted fw-bold">+91</span>
                                <input type="tel" name="mobile" id="mobileInput"
                                       class="form-control @error('mobile') is-invalid @enderror"
                                       value="{{ old('mobile') }}" required inputmode="numeric"
                                       autocomplete="tel" maxlength="10"
                                       placeholder="{{ __('messages.mobile_placeholder') }}"
                                       onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                       oninput="sanitizeMobile(this);"
                                       onpaste="handleMobilePaste(event, this);">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <small id="mobileFeedback" class="form-text text-muted">
                                    <span id="mobileCount">0</span>/10 {{ __('messages.digits_entered') }}
                                </small>
                                <small id="mobileValidationBadge" class="badge bg-secondary" style="display:none;"></small>
                            </div>
                            @error('mobile')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.email') }}</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="example@email.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Gender --}}
                        <div class="col-md-3">
                            <label class="form-label">{{ __('messages.gender') }}</label>
                            <select name="gender" class="form-select">
                                <option value="">--</option>
                                <option value="male"   {{ old('gender') === 'male'   ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                                <option value="other"  {{ old('gender') === 'other'  ? 'selected' : '' }}>{{ __('messages.other') }}</option>
                            </select>
                        </div>

                        {{-- DOB --}}
                        <div class="col-md-3">
                            <label class="form-label">{{ __('messages.dob') }}</label>
                            <input type="date" name="dob" class="form-control"
                                   value="{{ old('dob') }}" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    {{-- ============ ADDRESS ============ --}}
                    <div class="section-divider mt-4">
                        <i class="bi bi-geo-alt me-2"></i>{{ __('messages.address_section') }}
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.city') }}</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city') }}"
                                   placeholder="{{ __('messages.city') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.district') }}</label>
                            <input type="text" name="district" class="form-control" value="{{ old('district') }}"
                                   placeholder="{{ __('messages.district') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.state') }}</label>
                            <select name="state" class="form-select">
                                <option value="">-- {{ __('messages.state') }} --</option>
                                @php
                                    $statesList = [
                                        'Andhra Pradesh'    => 'आंध्र प्रदेश',
                                        'Arunachal Pradesh' => 'अरुणाचल प्रदेश',
                                        'Assam'             => 'असम',
                                        'Bihar'             => 'बिहार',
                                        'Chhattisgarh'      => 'छत्तीसगढ़',
                                        'Goa'               => 'गोवा',
                                        'Gujarat'           => 'गुजरात',
                                        'Haryana'           => 'हरियाणा',
                                        'Himachal Pradesh'  => 'हिमाचल प्रदेश',
                                        'Jharkhand'         => 'झारखंड',
                                        'Karnataka'         => 'कर्नाटक',
                                        'Kerala'            => 'केरल',
                                        'Madhya Pradesh'    => 'मध्य प्रदेश',
                                        'Maharashtra'       => 'महाराष्ट्र',
                                        'Manipur'           => 'मणिपुर',
                                        'Meghalaya'         => 'मेघालय',
                                        'Mizoram'           => 'मिजोरम',
                                        'Nagaland'          => 'नागालैंड',
                                        'Odisha'            => 'ओडिशा',
                                        'Punjab'            => 'पंजाब',
                                        'Rajasthan'         => 'राजस्थान',
                                        'Sikkim'            => 'सिक्किम',
                                        'Tamil Nadu'        => 'तमिलनाडु',
                                        'Telangana'         => 'तेलंगाना',
                                        'Tripura'           => 'त्रिपुरा',
                                        'Uttar Pradesh'     => 'उत्तर प्रदेश',
                                        'Uttarakhand'       => 'उत्तराखंड',
                                        'West Bengal'       => 'पश्चिम बंगाल',
                                        'Delhi'             => 'दिल्ली',
                                        'Jammu & Kashmir'   => 'जम्मू और कश्मीर',
                                        'Ladakh'            => 'लद्दाख',
                                    ];
                                    $isHi = app()->getLocale() === 'hi';
                                @endphp
                                @foreach($statesList as $en => $hi)
                                    @php
                                        $val = $en;
                                        $label = $isHi ? $hi : $en;
                                    @endphp
                                    <option value="{{ $val }}" {{ old('state') === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('messages.address') }}</label>
                            <textarea name="address" class="form-control" rows="2"
                                      placeholder="{{ __('messages.address') }}..."
                                      maxlength="500">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    {{-- ============ ASHRAM ============ --}}
                    <div class="section-divider mt-4">
                        <i class="bi bi-building me-2"></i>{{ __('messages.ashram_section') }}
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.branch') }}</label>
                            <select name="branch" class="form-select">
                                <option value="">{{ __('messages.select_branch') }}</option>
                                @if(app()->getLocale() === 'hi')
                                    <option value="Shivreinarayan" {{ old('branch') === 'Shivreinarayan' ? 'selected' : '' }}>शिवरीनारायण</option>
                                    <option value="Vrindavan"      {{ old('branch') === 'Vrindavan'      ? 'selected' : '' }}>वृंदावन</option>
                                    <option value="Other Center"   {{ old('branch') === 'Other Center'   ? 'selected' : '' }}>अन्य केंद्र</option>
                                @else
                                    <option value="Shivreinarayan" {{ old('branch') === 'Shivreinarayan' ? 'selected' : '' }}>Shivreinarayan</option>
                                    <option value="Vrindavan"      {{ old('branch') === 'Vrindavan'      ? 'selected' : '' }}>Vrindavan</option>
                                    <option value="Other Center"   {{ old('branch') === 'Other Center'   ? 'selected' : '' }}>Other Center</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.seva') }}</label>
                            <input type="text" name="seva" class="form-control" value="{{ old('seva') }}"
                                   placeholder="{{ __('messages.seva_placeholder') }}">
                        </div>
                    </div>

                    {{-- ============ DIKSHA ============ --}}
                    <div class="section-divider mt-4">
                        <i class="bi bi-stars me-2"></i>{{ __('messages.diksha_section') }}
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-block">{{ __('messages.diksha_taken') }}</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="diksha_taken"
                                   id="diksha_yes" value="1"
                                   {{ old('diksha_taken') == '1' ? 'checked' : '' }}
                                   onchange="toggleDiksha(true)">
                            <label class="form-check-label fw-semibold" for="diksha_yes">
                                <i class="bi bi-check-circle-fill text-success me-1"></i>{{ __('messages.yes') }}
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="diksha_taken"
                                   id="diksha_no" value="0"
                                   {{ old('diksha_taken', '0') == '0' ? 'checked' : '' }}
                                   onchange="toggleDiksha(false)">
                            <label class="form-check-label fw-semibold" for="diksha_no">
                                <i class="bi bi-x-circle text-muted me-1"></i>{{ __('messages.no') }}
                            </label>
                        </div>
                    </div>

                    <div id="diksha_fields" class="row g-3 p-3 rounded mb-3"
                         style="background: #fff8f0; border: 1px dashed #d4af37; {{ old('diksha_taken') == '1' ? '' : 'display:none;' }}">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.diksha_date') }}</label>
                            <input type="date" name="diksha_date" class="form-control"
                                   value="{{ old('diksha_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.diksha_place') }}</label>
                            <input type="text" name="diksha_place" class="form-control"
                                   value="{{ old('diksha_place') }}"
                                   placeholder="{{ __('messages.diksha_place') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.diksha_guru') }}</label>
                            <input type="text" name="diksha_guru" class="form-control"
                                   value="{{ old('diksha_guru') }}"
                                   placeholder="{{ __('messages.diksha_guru') }}">
                        </div>
                    </div>

                    {{-- ============ FAMILY ============ --}}
                    <div class="section-divider mt-4">
                        <i class="bi bi-people me-2"></i>{{ __('messages.family_section') }}
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.family_members') }}</label>
                        <textarea name="family_members" class="form-control" rows="3"
                                  placeholder="{{ __('messages.family_placeholder') }}"
                                  maxlength="1000">{{ old('family_members') }}</textarea>
                    </div>

                    {{-- ============ CONSENT ============ --}}
                    <div class="section-divider mt-4">
                        <i class="bi bi-shield-check me-2"></i>{{ __('messages.consent_section') }}
                    </div>
                    <div class="mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="whatsapp_consent"
                                   id="whatsapp_consent" value="1"
                                   {{ old('whatsapp_consent') ? 'checked' : '' }}>
                            <label class="form-check-label" for="whatsapp_consent">
                                <i class="bi bi-whatsapp text-success me-1"></i>{{ __('messages.whatsapp_consent') }}
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input @error('consent') is-invalid @enderror"
                                   type="checkbox" name="consent" id="consent" value="1"
                                   {{ old('consent') ? 'checked' : '' }} required>
                            <label class="form-check-label fw-semibold" for="consent">
                                <span class="req">*</span> {{ __('messages.consent') }}
                            </label>
                            @error('consent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" id="submitBtn" class="btn btn-saffron btn-lg py-3 shadow">
                            <i class="bi bi-send-fill me-2"></i>{{ __('messages.submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleDiksha(show) {
    var el = document.getElementById('diksha_fields');
    if (el) el.style.display = show ? '' : 'none';
}

function sanitizeMobile(input) {
    // Strip everything except 0-9
    var cleaned = input.value.replace(/[^0-9]/g, '');
    if (cleaned.length > 10) {
        cleaned = cleaned.substring(0, 10);
    }
    input.value = cleaned;
    updateMobileUI(cleaned, input);
}

function handleMobilePaste(e, input) {
    e.preventDefault();
    var clipboardData = e.clipboardData || window.clipboardData;
    var pasted = clipboardData.getData('text');
    var digitsOnly = pasted.replace(/[^0-9]/g, '');
    // If it starts with 91 and has 12 digits, strip 91 prefix
    if (digitsOnly.length === 12 && digitsOnly.startsWith('91')) {
        digitsOnly = digitsOnly.substring(2);
    }
    digitsOnly = digitsOnly.substring(0, 10);
    input.value = digitsOnly;
    updateMobileUI(digitsOnly, input);
}

function updateMobileUI(val, input) {
    var countEl = document.getElementById('mobileCount');
    var badge = document.getElementById('mobileValidationBadge');
    if (countEl) countEl.textContent = val.length;

    if (!badge) return;

    if (val.length === 0) {
        badge.style.display = 'none';
        input.classList.remove('is-valid', 'is-invalid');
    } else if (val.length === 10) {
        if (/^[6-9][0-9]{9}$/.test(val)) {
            badge.className = 'badge bg-success';
            badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>{{ __("messages.valid_mobile") }}';
            badge.style.display = 'inline-block';
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        } else {
            badge.className = 'badge bg-warning text-dark';
            badge.innerHTML = 'Should start with 6, 7, 8 or 9';
            badge.style.display = 'inline-block';
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
        }
    } else {
        badge.className = 'badge bg-secondary';
        badge.textContent = val.length + '/10';
        badge.style.display = 'inline-block';
        input.classList.remove('is-valid', 'is-invalid');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var mobileInput = document.getElementById('mobileInput');
    if (mobileInput && mobileInput.value) {
        sanitizeMobile(mobileInput);
    }

    var checked = document.querySelector('input[name="diksha_taken"]:checked');
    if (checked) toggleDiksha(checked.value === '1');

    // Smooth & professional submission loading
    var regForm = document.getElementById('regForm');
    var isSubmitting = false;

    if (regForm) {
        regForm.addEventListener('submit', function(e) {
            if (isSubmitting) return;

            if (!this.checkValidity()) {
                this.reportValidity();
                return;
            }

            var mobile = document.getElementById('mobileInput').value.trim();
            if (mobile.length !== 10 || !/^[6-9][0-9]{9}$/.test(mobile)) {
                e.preventDefault();
                alert("{{ app()->getLocale() === 'hi' ? 'कृपया 10 अंकों का वैध मोबाइल नंबर दर्ज करें।' : 'Please enter a valid 10-digit mobile number.' }}");
                document.getElementById('mobileInput').focus();
                return;
            }

            e.preventDefault();
            isSubmitting = true;

            var overlay = document.getElementById('sacredLoaderOverlay');
            var stepText = document.getElementById('loaderStepText');
            var pBar = document.getElementById('loaderProgressBar');
            var btn = document.getElementById('submitBtn');

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>' +
                                "{{ app()->getLocale() === 'hi' ? 'कृपया प्रतीक्षा करें...' : 'Please wait...' }}";
            }

            if (overlay) {
                overlay.classList.add('active');
                pBar.style.width = '30%';
                stepText.textContent = "{{ __('messages.loader_step1') }}";

                setTimeout(function() {
                    pBar.style.width = '70%';
                    stepText.textContent = "{{ __('messages.loader_step2') }}";
                }, 700);

                setTimeout(function() {
                    pBar.style.width = '95%';
                    stepText.textContent = "{{ __('messages.loader_step3') }}";
                }, 1400);

                setTimeout(function() {
                    pBar.style.width = '100%';
                    regForm.submit();
                }, 1900);
            } else {
                regForm.submit();
            }
        });
    }
});
</script>
@endpush
