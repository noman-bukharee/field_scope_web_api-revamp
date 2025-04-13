@extends('admin.auth.master')
@section('content')
@section('title', 'Signup')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.5.0/build/css/intlTelInput.css">

<style>
    .custom-field {
        position: relative;
    }

    .custom-field input:focus {
        box-shadow: none; /* Remove shadow effect on focus */
    }

    .custom-field label {
        transition: all 0.2s ease; /* Smooth transition for label */
    }

    .custom-field input:focus + .placeholder,
    .custom-field input:not(:placeholder-shown) + .placeholder {
        top: -10px;
        font-size: 12px;
        color: #007bff; /* Change to your desired color */
    }

    .placeholder {
        position: absolute;
        top: 10px;
        left: 10px;
        transition: all 0.2s ease;
        color: #aaa;
    }
</style>
    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
        <div class="auth-logo">
            <a href="{{ URL::to('') }}">
                <img src="{{asset("../assets/img/auth-logo.png")}}" alt="" />
            </a>
        </div>
        @include('admin.error')
        <div class="auth-box sign-up-box">
        <form action="{{ URL::to('register').'/'.$data['plan_type'] }}" method="POST" id="register-form" 
            enctype="multipart/form-data" class="needs-validation" novalidate>
            {{ csrf_field() }}
            
            <div class="col-12">
                <h1>Create an account</h1>
                <p>Let’s get started with your basic information</p>
            </div>
            
            <div class="col-12 mb-3">
                <label class="custom-field two">
                    <input type="text" name="name" placeholder="&nbsp;" maxlength="30" class="form-control" required />
                    <span class="placeholder">Full Name</span>
                    <div class="invalid-feedback">
                        Please enter your full name.
                    </div>
                </label>
            </div>
            
            <div class="col-12 mb-3">
                <label class="custom-field two">
                    <input type="email" name="email" placeholder="&nbsp;" class="form-control" required />
                    <span class="placeholder">Email Address</span>
                    <div class="invalid-feedback">
                        Please enter a valid email address.
                    </div>
                </label>
            </div>
            
            <div class="col-12 mb-3">
                <label class="custom-field two position-relative">
                    <input type="password" id="password" name="password" placeholder="&nbsp;" class="form-control" required />
                    <span class="placeholder">Password</span>
                    <i class="bi bi-eye-slash position-absolute end-0 top-0 mt-2 me-2" id="togglePassword" style="cursor: pointer;"></i>
                    <div class="invalid-feedback" id="passwordError">
                        Password must be at least 8 characters, contain at least one uppercase letter, one lowercase letter, one number, and one special character.
                    </div>
                </label>
            </div>
            
            <div class="col-12 mb-3">
                <label class="custom-field two position-relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="&nbsp;" class="form-control" required />
                    <span class="placeholder">Confirm Password</span>
                    <i class="bi bi-eye-slash position-absolute end-0 top-0 mt-2 me-2" id="toggleConfirmPassword" style="cursor: pointer;"></i>
                    <div class="invalid-feedback">
                        Passwords do not match.
                    </div>
                </label>
            </div>
            
            <div class="col-md-12 mb-3">
                <label class="custom-field two position-relative">
                    <input id="mobile_no" name="contact_number" type="text" class="form-control iti__tel-input" required="" maxlength="15" autocomplete="off" pattern="\+[0-9]{1,4}[0-9]{6,}" inputmode="numeric">
                    <span class="country-flag" id="countryFlag"></span> <!-- Flag display -->
                    <span class="placeholder mobile_no">Contact Number</span>
                    <div class="invalid-feedback">
                        Please enter a valid contact number.
                    </div>
                </label>
            </div>
            <!-- Terms and Conditions Checkbox -->
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="" id="termsCheckbox" required>
                <label class="form-check-label" for="termsCheckbox">
                    I agree to the terms and conditions
                </label>
                <div class="invalid-feedback">
                    You must agree to the terms and conditions.
                </div>
            </div>
            
            <input type="submit" class="signin-btn" value="Sign up">
            
            <p class="sigup-text">
                Already have an account?
                <a href="{{ URL::to('admin/login') }}" class="color-blue">Sign in</a>
            </p>
        </form>
        </div>
    </div>
@endsection
@push("page_css")
    <style>
        span.placeholder.mobile_no {
            opacity: 0;
        }
        .custom-field input:focus + .placeholder.mobile_no {
            opacity: 1;
        }
        input#mobile_no[placeholder] {
            color: #8d96a7 !important;
            font-family: 'EuclidSquare-Light';
        }
        .country-flag {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 15px;
            background-size: cover;
            display: inline-block;
        }
        .custom-field input#mobile_no {
            padding-left: 40px; /* Space for the flag */
        }

    </style>
@endpush
@push("page_js")
<script>
document.addEventListener("DOMContentLoaded", function() {
    const input = document.querySelector('#mobile_no');
    const flagElement = document.querySelector('#countryFlag');
    const label = input.closest('.custom-field');
    const placeholderElement = document.querySelector('.placeholder');

    let countryDialCode = '+XX'; // Default fallback
    let isFirstFocus = true; // Track first focus
    let originalPlaceholder = '+XX XXXXX XXXXX'; // Store original placeholder
    let countryCode = 'xx'; // Default country code for flag

    // Function to set placeholder and fetch country code
    function setPhonePlaceholder(countryCode) {
        const phoneFormats = {
            'AF': '+93 XX XXX XXXX', 'AL': '+355 XXX XXX XXX', 'DZ': '+213 XXX XX XX XX',
            'AS': '+1 (684) XXX XXXX', 'AD': '+376 XXX XXX', 'AO': '+244 XXX XXX XXX',
            'AR': '+54 (XXX) XXX-XXXX', 'AM': '+374 (XX) XXX XXX', 'AU': '+61 X XXXX XXXX',
            'AT': '+43 XXXX XXXXXXX', 'AZ': '+994 XX XXX XX XX', 'BH': '+973 XXXX XXXX',
            'BD': '+880 XXX XXX XXX', 'BY': '+375 (XX) XXX XX XX', 'BE': '+32 XXX XX XX XX',
            'BZ': '+501 XXX XXXX', 'BJ': '+229 XX XX XXXX', 'BT': '+975 XXXX XXXXX',
            'BO': '+591 (X) XXX XXXX', 'BA': '+387 XX XXX XXX', 'BW': '+267 XX XXX XXX',
            'BR': '+55 (XX) XXXXX-XXXX', 'BN': '+673 XXX XXXX', 'BG': '+359 XXX XXX XXX',
            'BF': '+226 XX XX XX XX', 'KH': '+855 XXX XXX XXX', 'CM': '+237 XXXX XXXX',
            'CA': '+1 (XXX) XXX-XXXX', 'CV': '+238 XXX XX XX', 'TD': '+235 XX XX XX XX',
            'CL': '+56 X XXXX XXXX', 'CN': '+86 (XXX) XXXX XXXX', 'CO': '+57 (XXX) XXX XXXX',
            'KM': '+269 XXX XXXX', 'CG': '+242 XX XXX XXXX', 'CR': '+506 XXXX XXXX',
            'HR': '+385 XX XXX XXXX', 'CU': '+53 X XXX XXXX', 'CY': '+357 XX XXX XXX',
            'CZ': '+420 XXX XXX XXX', 'DK': '+45 XX XX XX XX', 'DJ': '+253 XX XX XX XX',
            'DO': '+1 (809) XXX-XXXX', 'EC': '+593 (X) XXX XXXX', 'EG': '+20 (X) XXXX XXXX',
            'SV': '+503 XXXX XXXX', 'EE': '+372 XXXX XXXX', 'ET': '+251 XXX XXX XXX',
            'FI': '+358 XXXX XXXXX', 'FR': '+33 X XX XX XX XX', 'GE': '+995 (XX) XXX XXXX',
            'DE': '+49 XXXX XXXXXX', 'GH': '+233 (XX) XXX XXXX', 'GR': '+30 XXX XXX XXXX',
            'GT': '+502 XXXX XXXX', 'GN': '+224 XX XXX XXX', 'HT': '+509 XXXX XXXX',
            'HN': '+504 XXXX XXXX', 'HK': '+852 XXXX XXXX', 'HU': '+36 XX XXX XXXX',
            'IS': '+354 XXX XXXX', 'IN': '+91 XXXXX XXXXX', 'ID': '+62 (XX) XXXX XXXX',
            'IR': '+98 (XXX) XXX XXXX', 'IQ': '+964 (XXX) XXX XXXX', 'IE': '+353 (XX) XXX XXXX',
            'IL': '+972 (X) XXX XXXX', 'IT': '+39 XXX XXXXXXX', 'JM': '+1 (876) XXX-XXXX',
            'JP': '+81 (XX) XXXX XXXX', 'JO': '+962 X XXXX XXXX', 'KZ': '+7 (XXX) XXX-XX-XX',
            'KE': '+254 XXX XXX XXX', 'KR': '+82 XX XXXX XXXX', 'KW': '+965 XXXX XXXX',
            'LA': '+856 XX XXX XXX', 'LV': '+371 XXXX XXXXX', 'LB': '+961 XX XXX XXX',
            'LY': '+218 XX XXX XXXX', 'LT': '+370 (XX) XXX XXXX', 'LU': '+352 XXXX XXXX',
            'MO': '+853 XXXX XXXX', 'MK': '+389 XX XXX XXX', 'MY': '+60 XX-XXX XXXX',
            'MV': '+960 XXX XXXX', 'ML': '+223 XX XX XXXX', 'MX': '+52 (XXX) XXX XXXX',
            'MA': '+212 (XXX) XX XX XX', 'NP': '+977 XX XXX XXX', 'NL': '+31 XX XXX XXXX',
            'NZ': '+64 XXX XXX XXX', 'NG': '+234 XXX XXX XXXX', 'NO': '+47 XXX XX XXX',
            'PK': '+92 XXX XXXXXXX', 'PE': '+51 XXX XXX XXX', 'PH': '+63 (XXX) XXX XXXX',
            'PL': '+48 XXX XXX XXX', 'PT': '+351 XXX XXX XXX', 'QA': '+974 XXXX XXXX',
            'RO': '+40 XXX XXX XXX', 'RU': '+7 (XXX) XXX-XX-XX', 'SA': '+966 (X) XXX XXXX',
            'SG': '+65 XXXX XXXX', 'ZA': '+27 XX XXX XXXX', 'ES': '+34 XXX XXX XXX',
            'SE': '+46 XX XXX XX XX', 'CH': '+41 XX XXX XX XX', 'TH': '+66 X XXXX XXXX',
            'TR': '+90 (XXX) XXX XXXX', 'UA': '+380 (XX) XXX XX XX', 'AE': '+971 X XXX XXXX',
            'GB': '+44 XXXX XXXXXX', 'US': '+1 (XXX) XXX-XXXX', 'UY': '+598 XXX XXXX',
            'UZ': '+998 XX XXX XXXX', 'VE': '+58 XXX XXX XXXX', 'VN': '+84 XX XXXX XXXX',
            'ZM': '+260 XXX XXX XXX', 'ZW': '+263 XXX XXX XXX'
        };
        const phoneFormatsCode = {
            'AF': '+93', 'AL': '+355', 'DZ': '+213', 'AS': '+1 (684)', 'AD': '+376', 'AO': '+244',
            'AR': '+54', 'AM': '+374', 'AU': '+61', 'AT': '+43', 'AZ': '+994', 'BH': '+973',
            'BD': '+880', 'BY': '+375', 'BE': '+32', 'BZ': '+501', 'BJ': '+229', 'BT': '+975',
            'BO': '+591', 'BA': '+387', 'BW': '+267', 'BR': '+55', 'BN': '+673', 'BG': '+359',
            'BF': '+226', 'KH': '+855', 'CM': '+237', 'CA': '+1', 'CV': '+238', 'TD': '+235',
            'CL': '+56', 'CN': '+86', 'CO': '+57', 'KM': '+269', 'CG': '+242', 'CR': '+506',
            'HR': '+385', 'CU': '+53', 'CY': '+357', 'CZ': '+420', 'DK': '+45', 'DJ': '+253',
            'DO': '+1 (809)', 'EC': '+593', 'EG': '+20', 'SV': '+503', 'EE': '+372', 'ET': '+251',
            'FI': '+358', 'FR': '+33', 'GE': '+995', 'DE': '+49', 'GH': '+233', 'GR': '+30',
            'GT': '+502', 'GN': '+224', 'HT': '+509', 'HN': '+504', 'HK': '+852', 'HU': '+36',
            'IS': '+354', 'IN': '+91', 'ID': '+62', 'IR': '+98', 'IQ': '+964', 'IE': '+353',
            'IL': '+972', 'IT': '+39', 'JM': '+1 (876)', 'JP': '+81', 'JO': '+962', 'KZ': '+7',
            'KE': '+254', 'KR': '+82', 'KW': '+965', 'LA': '+856', 'LV': '+371', 'LB': '+961',
            'LY': '+218', 'LT': '+370', 'LU': '+352', 'MO': '+853', 'MK': '+389', 'MY': '+60',
            'MV': '+960', 'ML': '+223', 'MX': '+52', 'MA': '+212', 'NP': '+977', 'NL': '+31',
            'NZ': '+64', 'NG': '+234', 'NO': '+47', 'PK': '+92', 'PE': '+51', 'PH': '+63',
            'PL': '+48', 'PT': '+351', 'QA': '+974', 'RO': '+40', 'RU': '+7', 'SA': '+966',
            'SG': '+65', 'ZA': '+27', 'ES': '+34', 'SE': '+46', 'CH': '+41', 'TH': '+66',
            'TR': '+90', 'UA': '+380', 'AE': '+971', 'GB': '+44', 'US': '+1', 'UY': '+598',
            'UZ': '+998', 'VE': '+58', 'VN': '+84', 'ZM': '+260', 'ZW': '+263'
        };
        const placeholder = phoneFormats[countryCode] || '+XX XXXXX XXXXX';
        input.placeholder = placeholder;
        countryDialCode = phoneFormatsCode[countryCode] || '+XX';
        originalPlaceholder = placeholder;
        setCountryFlag(countryCode.toLowerCase());
    }

    // Function to set the country flag
    function setCountryFlag(countryCode) {
        flagElement.style.backgroundImage = `url('https://flagcdn.com/20x15/${countryCode}.png')`;
    }

    // Fetch country code using IP info API
    fetch('https://ipinfo.io/json?token=8091eed19a06a0')
        .then(response => response.json())
        .then(data => {
            setPhonePlaceholder(data.country);
        })
        .catch(error => {
            console.error('Error fetching country code:', error);
            input.placeholder = '+XX XXXXX XXXXX';
            countryDialCode = '+XX';
            originalPlaceholder = '+XX XXXXX XXXXX';
            setCountryFlag('xx'); 
        });

    // Prepend country code on focus
    input.addEventListener('focus', function() {
        if (isFirstFocus && !input.value) {
            input.value = countryDialCode;
            isFirstFocus = false;
        }
    });

    // Restrict input to numbers only after country code
    input.addEventListener('input', function() {
        let value = input.value;
        if (!value.startsWith(countryDialCode)) {
            value = countryDialCode;
        }
        const numericValue = value.replace(countryDialCode, '').replace(/\D/g, '');
        input.value = countryDialCode + numericValue;
    });

    // Restore placeholder on blur if no number entered
    input.addEventListener('blur', function() {
        if (input.value === countryDialCode) {
            input.value = '';
            input.placeholder = originalPlaceholder;
            isFirstFocus = true;
        }
    });

    // Dirty state logic
    const inputs = document.querySelectorAll('input');
    inputs.forEach((el) => {
        el.addEventListener('blur', (e) => {
            if (e.target.value) {
                e.target.classList.add('dirty');
            } else {
                e.target.classList.remove('dirty');
            }
        });
    });
});
</script>
<script>
    (function () {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                const passwordInput = document.getElementById('password');
                const confirmPasswordInput = document.getElementById('password_confirmation');
                const passwordError = document.getElementById('passwordError');
                const termsCheckbox = document.getElementById('termsCheckbox');
                const mobileInput = document.getElementById('mobile_no');
                const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

                // Password validation
                if (!passwordPattern.test(passwordInput.value)) {
                    event.preventDefault();
                    event.stopPropagation();
                    passwordInput.classList.add('is-invalid');
                    passwordError.style.display = 'block';
                } else {
                    passwordInput.classList.remove('is-invalid');
                    passwordError.style.display = 'none';
                }

                // Confirm password validation
                if (passwordInput.value !== confirmPasswordInput.value) {
                    event.preventDefault();
                    event.stopPropagation();
                    confirmPasswordInput.classList.add('is-invalid');
                } else {
                    confirmPasswordInput.classList.remove('is-invalid');
                }

                // Terms checkbox validation
                if (!termsCheckbox.checked) {
                    event.preventDefault();
                    event.stopPropagation();
                    termsCheckbox.classList.add('is-invalid');
                } else {
                    termsCheckbox.classList.remove('is-invalid');
                }

                // Mobile number validation
                if (!mobileInput.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    mobileInput.classList.add('is-invalid');
                } else {
                    mobileInput.classList.remove('is-invalid');
                }

                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });
    })();

    document.getElementById('togglePassword').addEventListener('click', function () {
        var passwordInput = document.getElementById('password');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            this.classList.remove('bi-eye-slash');
            this.classList.add('bi-eye');
        } else {
            passwordInput.type = 'password';
            this.classList.remove('bi-eye');
            this.classList.add('bi-eye-slash');
        }
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
        var confirmPasswordInput = document.getElementById('password_confirmation');
        if (confirmPasswordInput.type === 'password') {
            confirmPasswordInput.type = 'text';
            this.classList.remove('bi-eye-slash');
            this.classList.add('bi-eye');
        } else {
            confirmPasswordInput.type = 'password';
            this.classList.remove('bi-eye');
            this.classList.add('bi-eye-slash');
        }
    });
</script>
@endpush