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
                    <input id="mobile_no" name="contact_number" type="text" class="form-control iti__tel-input" required="" maxlength="15" autocomplete="off" pattern="[0-9]*" inputmode="numeric">
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
        .custom-field input:focus + .placeholder.mobile_no{
            opacity: 1;
        }

        input#mobile_no[placeholder] {
            color: #8d96a7 !important;
            font-family: 'EuclidSquare-Light';
        }

    </style>
@endpush
@push("page_js")
<script>
    
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.querySelector('#mobile_no');
        const label = input.closest('.custom-field');
        const placeholderElement = document.querySelector('.placeholder');

        // Restrict input to numbers only
        input.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, ''); // Remove non-digit characters
        });

        // Function to set placeholder based on country code
        function setPhonePlaceholder(countryCode) {
            const phoneFormats = {
            'AF': '+93 XX XXX XXXX',      // Afghanistan
            'AL': '+355 XXX XXX XXX',     // Albania
            'DZ': '+213 XXX XX XX XX',    // Algeria
            'AS': '+1 (684) XXX XXXX',    // American Samoa
            'AD': '+376 XXX XXX',         // Andorra
            'AO': '+244 XXX XXX XXX',     // Angola
            'AR': '+54 (XXX) XXX-XXXX',   // Argentina
            'AM': '+374 (XX) XXX XXX',    // Armenia
            'AU': '+61 X XXXX XXXX',      // Australia
            'AT': '+43 XXXX XXXXXXX',     // Austria
            'AZ': '+994 XX XXX XX XX',    // Azerbaijan
            'BH': '+973 XXXX XXXX',       // Bahrain
            'BD': '+880 XXX XXX XXX',     // Bangladesh
            'BY': '+375 (XX) XXX XX XX',  // Belarus
            'BE': '+32 XXX XX XX XX',     // Belgium
            'BZ': '+501 XXX XXXX',        // Belize
            'BJ': '+229 XX XX XXXX',      // Benin
            'BT': '+975 XXXX XXXXX',      // Bhutan
            'BO': '+591 (X) XXX XXXX',    // Bolivia
            'BA': '+387 XX XXX XXX',      // Bosnia and Herzegovina
            'BW': '+267 XX XXX XXX',      // Botswana
            'BR': '+55 (XX) XXXXX-XXXX',  // Brazil
            'BN': '+673 XXX XXXX',        // Brunei
            'BG': '+359 XXX XXX XXX',     // Bulgaria
            'BF': '+226 XX XX XX XX',     // Burkina Faso
            'KH': '+855 XXX XXX XXX',     // Cambodia
            'CM': '+237 XXXX XXXX',       // Cameroon
            'CA': '+1 (XXX) XXX-XXXX',    // Canada
            'CV': '+238 XXX XX XX',       // Cape Verde
            'TD': '+235 XX XX XX XX',     // Chad
            'CL': '+56 X XXXX XXXX',      // Chile
            'CN': '+86 (XXX) XXXX XXXX',  // China
            'CO': '+57 (XXX) XXX XXXX',   // Colombia
            'KM': '+269 XXX XXXX',        // Comoros
            'CG': '+242 XX XXX XXXX',     // Congo
            'CR': '+506 XXXX XXXX',       // Costa Rica
            'HR': '+385 XX XXX XXXX',     // Croatia
            'CU': '+53 X XXX XXXX',       // Cuba
            'CY': '+357 XX XXX XXX',      // Cyprus
            'CZ': '+420 XXX XXX XXX',     // Czech Republic
            'DK': '+45 XX XX XX XX',      // Denmark
            'DJ': '+253 XX XX XX XX',     // Djibouti
            'DO': '+1 (809) XXX-XXXX',    // Dominican Republic
            'EC': '+593 (X) XXX XXXX',    // Ecuador
            'EG': '+20 (X) XXXX XXXX',    // Egypt
            'SV': '+503 XXXX XXXX',       // El Salvador
            'EE': '+372 XXXX XXXX',       // Estonia
            'ET': '+251 XXX XXX XXX',     // Ethiopia
            'FI': '+358 XXXX XXXXX',      // Finland
            'FR': '+33 X XX XX XX XX',    // France
            'GE': '+995 (XX) XXX XXXX',   // Georgia
            'DE': '+49 XXXX XXXXXX',      // Germany
            'GH': '+233 (XX) XXX XXXX',   // Ghana
            'GR': '+30 XXX XXX XXXX',     // Greece
            'GT': '+502 XXXX XXXX',       // Guatemala
            'GN': '+224 XX XXX XXX',      // Guinea
            'HT': '+509 XXXX XXXX',       // Haiti
            'HN': '+504 XXXX XXXX',       // Honduras
            'HK': '+852 XXXX XXXX',       // Hong Kong
            'HU': '+36 XX XXX XXXX',      // Hungary
            'IS': '+354 XXX XXXX',        // Iceland
            'IN': '+91 XXXXX XXXXX',      // India
            'ID': '+62 (XX) XXXX XXXX',   // Indonesia
            'IR': '+98 (XXX) XXX XXXX',   // Iran
            'IQ': '+964 (XXX) XXX XXXX',  // Iraq
            'IE': '+353 (XX) XXX XXXX',   // Ireland
            'IL': '+972 (X) XXX XXXX',    // Israel
            'IT': '+39 XXX XXXXXXX',      // Italy
            'JM': '+1 (876) XXX-XXXX',    // Jamaica
            'JP': '+81 (XX) XXXX XXXX',   // Japan
            'JO': '+962 X XXXX XXXX',     // Jordan
            'KZ': '+7 (XXX) XXX-XX-XX',   // Kazakhstan
            'KE': '+254 XXX XXX XXX',     // Kenya
            'KR': '+82 XX XXXX XXXX',     // South Korea
            'KW': '+965 XXXX XXXX',       // Kuwait
            'LA': '+856 XX XXX XXX',      // Laos
            'LV': '+371 XXXX XXXXX',      // Latvia
            'LB': '+961 XX XXX XXX',      // Lebanon
            'LY': '+218 XX XXX XXXX',     // Libya
            'LT': '+370 (XX) XXX XXXX',   // Lithuania
            'LU': '+352 XXXX XXXX',       // Luxembourg
            'MO': '+853 XXXX XXXX',       // Macau
            'MK': '+389 XX XXX XXX',      // North Macedonia
            'MY': '+60 XX-XXX XXXX',      // Malaysia
            'MV': '+960 XXX XXXX',        // Maldives
            'ML': '+223 XX XX XXXX',      // Mali
            'MX': '+52 (XXX) XXX XXXX',   // Mexico
            'MA': '+212 (XXX) XX XX XX',  // Morocco
            'NP': '+977 XX XXX XXX',      // Nepal
            'NL': '+31 XX XXX XXXX',      // Netherlands
            'NZ': '+64 XXX XXX XXX',      // New Zealand
            'NG': '+234 XXX XXX XXXX',    // Nigeria
            'NO': '+47 XXX XX XXX',       // Norway
            'PK': '+92 XXX XXXXXXX',      // Pakistan
            'PE': '+51 XXX XXX XXX',      // Peru
            'PH': '+63 (XXX) XXX XXXX',   // Philippines
            'PL': '+48 XXX XXX XXX',      // Poland
            'PT': '+351 XXX XXX XXX',     // Portugal
            'QA': '+974 XXXX XXXX',       // Qatar
            'RO': '+40 XXX XXX XXX',      // Romania
            'RU': '+7 (XXX) XXX-XX-XX',   // Russia
            'SA': '+966 (X) XXX XXXX',    // Saudi Arabia
            'SG': '+65 XXXX XXXX',        // Singapore
            'ZA': '+27 XX XXX XXXX',      // South Africa
            'ES': '+34 XXX XXX XXX',      // Spain
            'SE': '+46 XX XXX XX XX',     // Sweden
            'CH': '+41 XX XXX XX XX',     // Switzerland
            'TH': '+66 X XXXX XXXX',      // Thailand
            'TR': '+90 (XXX) XXX XXXX',   // Turkey
            'UA': '+380 (XX) XXX XX XX',  // Ukraine
            'AE': '+971 X XXX XXXX',      // United Arab Emirates
            'GB': '+44 XXXX XXXXXX',      // United Kingdom
            'US': '+1 (XXX) XXX-XXXX',    // United States
            'UY': '+598 XXX XXXX',        // Uruguay
            'UZ': '+998 XX XXX XXXX',     // Uzbekistan
            'VE': '+58 XXX XXX XXXX',     // Venezuela
            'VN': '+84 XX XXXX XXXX',     // Vietnam
            'ZM': '+260 XXX XXX XXX',     // Zambia
            'ZW': '+263 XXX XXX XXX',     // Zimbabwe
            // Add more if needed or use libraries like libphonenumber for better validation
        };
        const phoneFormatsCode = {
            'AF': '+93',      // Afghanistan
            'AL': '+355',     // Albania
            'DZ': '+213',    // Algeria
            'AS': '+1 (684)',    // American Samoa
            'AD': '+376',         // Andorra
            'AO': '+244',     // Angola
            'AR': '+54',   // Argentina
            'AM': '+374',    // Armenia
            'AU': '+61',      // Australia
            'AT': '+43',     // Austria
            'AZ': '+994',    // Azerbaijan
            'BH': '+973',       // Bahrain
            'BD': '+880',     // Bangladesh
            'BY': '+375',  // Belarus
            'BE': '+32',     // Belgium
            'BZ': '+501',        // Belize
            'BJ': '+229',      // Benin
            'BT': '+975',      // Bhutan
            'BO': '+591',    // Bolivia
            'BA': '+387',      // Bosnia and Herzegovina
            'BW': '+267',      // Botswana
            'BR': '+55',  // Brazil
            'BN': '+673',        // Brunei
            'BG': '+359',     // Bulgaria
            'BF': '+226',     // Burkina Faso
            'KH': '+855',     // Cambodia
            'CM': '+237',       // Cameroon
            'CA': '+1',    // Canada
            'CV': '+238',       // Cape Verde
            'TD': '+235',     // Chad
            'CL': '+56',      // Chile
            'CN': '+86',  // China
            'CO': '+57',   // Colombia
            'KM': '+269',        // Comoros
            'CG': '+242',     // Congo
            'CR': '+506',       // Costa Rica
            'HR': '+385',     // Croatia
            'CU': '+53',       // Cuba
            'CY': '+357',      // Cyprus
            'CZ': '+420',     // Czech Republic
            'DK': '+45',      // Denmark
            'DJ': '+253',     // Djibouti
            'DO': '+1 (809)',    // Dominican Republic
            'EC': '+593',    // Ecuador
            'EG': '+20',    // Egypt
            'SV': '+503',       // El Salvador
            'EE': '+372',       // Estonia
            'ET': '+251',     // Ethiopia
            'FI': '+358',      // Finland
            'FR': '+33',    // France
            'GE': '+995',   // Georgia
            'DE': '+49',      // Germany
            'GH': '+233',   // Ghana
            'GR': '+30',     // Greece
            'GT': '+502',       // Guatemala
            'GN': '+224',      // Guinea
            'HT': '+509',       // Haiti
            'HN': '+504',       // Honduras
            'HK': '+852',       // Hong Kong
            'HU': '+36',      // Hungary
            'IS': '+354',        // Iceland
            'IN': '+91',      // India
            'ID': '+62',   // Indonesia
            'IR': '+98',   // Iran
            'IQ': '+964',  // Iraq
            'IE': '+353',   // Ireland
            'IL': '+972',    // Israel
            'IT': '+39',      // Italy
            'JM': '+1 (876)',    // Jamaica
            'JP': '+81',   // Japan
            'JO': '+962',     // Jordan
            'KZ': '+7',   // Kazakhstan
            'KE': '+254',     // Kenya
            'KR': '+82',     // South Korea
            'KW': '+965',       // Kuwait
            'LA': '+856',      // Laos
            'LV': '+371',      // Latvia
            'LB': '+961',      // Lebanon
            'LY': '+218',     // Libya
            'LT': '+370',   // Lithuania
            'LU': '+352',       // Luxembourg
            'MO': '+853',       // Macau
            'MK': '+389',      // North Macedonia
            'MY': '+60',      // Malaysia
            'MV': '+960',        // Maldives
            'ML': '+223',      // Mali
            'MX': '+52',   // Mexico
            'MA': '+212',  // Morocco
            'NP': '+977',      // Nepal
            'NL': '+31',      // Netherlands
            'NZ': '+64',      // New Zealand
            'NG': '+234',    // Nigeria
            'NO': '+47',       // Norway
            'PK': '+92',      // Pakistan
            'PE': '+51',      // Peru
            'PH': '+63',   // Philippines
            'PL': '+48',      // Poland
            'PT': '+351',     // Portugal
            'QA': '+974',       // Qatar
            'RO': '+40',      // Romania
            'RU': '+7',   // Russia
            'SA': '+966',    // Saudi Arabia
            'SG': '+65',        // Singapore
            'ZA': '+27',      // South Africa
            'ES': '+34',      // Spain
            'SE': '+46',     // Sweden
            'CH': '+41',     // Switzerland
            'TH': '+66',      // Thailand
            'TR': '+90',   // Turkey
            'UA': '+380',  // Ukraine
            'AE': '+971',      // United Arab Emirates
            'GB': '+44',      // United Kingdom
            'US': '+1',    // United States
            'UY': '+598',        // Uruguay
            'UZ': '+998',     // Uzbekistan
            'VE': '+58',     // Venezuela
            'VN': '+84',     // Vietnam
            'ZM': '+260',     // Zambia
            'ZW': '+263',     // Zimbabwe
            // Add more if needed or use libraries like libphonenumber for better validation
        }
            const placeholder = phoneFormats[countryCode] || '+XX XXXXX XXXXX';
            input.value = phoneFormatsCode[countryCode];
            input.placeholder = placeholder;
        }

        // Fetch country code using IP info API
        fetch('https://ipinfo.io/json?token=8091eed19a06a0')
            .then(response => response.json())
            .then(data => {
                const countryCode = data.country;
                setPhonePlaceholder(countryCode);
                
            })
            .catch(error => {
                console.error('Error fetching country code:', error);
                input.placeholder = '+XX XXXXX XXXXX'; // Fallback placeholder
            });
    });

    // Add event listener to the form to validate the form on submit
    const inputs = document.querySelectorAll('input')

    inputs.forEach((el) => {
        el.addEventListener('blur', (e) => {
            if (e.target.value) {
                e.target.classList.add('dirty')
            } else {
                e.target.classList.remove('dirty')
            }
        })
    })

</script>
<script>
    // Validation on Fields Implementation
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    // Custom password validation
                    const passwordInput = document.getElementById('password');
                    const confirmPasswordInput = document.getElementById('password_confirmation');
                    const passwordError = document.getElementById('passwordError');
                    const termsCheckbox = document.getElementById('termsCheckbox');

                    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

                    // Check if password meets the criteria
                    if (!passwordPattern.test(passwordInput.value)) {
                    event.preventDefault();
                    event.stopPropagation();
                    passwordInput.classList.add('is-invalid');
                    passwordError.style.display = 'block';
                    } else {
                    passwordInput.classList.remove('is-invalid');
                    passwordError.style.display = 'none';
                    }

                    // Check if passwords match
                    if (passwordInput.value !== confirmPasswordInput.value) {
                    event.preventDefault();
                    event.stopPropagation();
                    confirmPasswordInput.classList.add('is-invalid');
                    } else {
                    confirmPasswordInput.classList.remove('is-invalid');
                    }

                    // Check if the terms checkbox is checked
                    if (!termsCheckbox.checked) {
                    event.preventDefault();
                    event.stopPropagation();
                    termsCheckbox.classList.add('is-invalid');
                    } else {
                    termsCheckbox.classList.remove('is-invalid');
                    }

                    if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                }, false);
                })
            })();

        // Toggle password visibility
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