@extends('admin.auth.master')
@section('content')
@section('title', 'Signin')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<div class="col-12 col-sm-12 col-md-4 col-lg-4">
    <div class="auth-logo">
        <a href="{{ URL::to('') }}">
                <img src="{{asset("../assets/img/auth-logo.png")}}" alt="" />
        </a>
    </div>
    <div class="auth-box">
        @include('admin.error')
        <form action="{{ url('/admin/login') }}" method="post" id="login-form" autocomplete="off" class="needs-validation" novalidate>
            {{ csrf_field() }}

            <div class="col-12">
                <h1>Welcome back</h1>
                <p>Welcome back! Please enter your details</p>
            </div>

            <div class="col-12 form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label class="custom-field two">
                    <input type="email" name="email" value="{{ Cookie::get('email') }}" id="custom-input1" placeholder="&nbsp;" required="required" autocomplete="off" />
                    <span class="placeholder">Email Address</span>
                    <div class="invalid-feedback">
                        Please enter a valid email address.
                    </div>
                </label>
            </div>

            <div class="col-12 form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label class="custom-field two position-relative">
                    <input type="password" name="password" id="custom-input2" value="{{ Cookie::get('password') }}" required="required" placeholder="&nbsp;" />
                    <span class="placeholder">Enter your password</span>
                    <i class="bi bi-eye-slash position-absolute end-0 top-0 mt-2 me-2" id="togglePassword" style="cursor: pointer;"></i>
                    
                    <div class="invalid-feedback">
                        Please enter your password.
                    </div>
                </label>
            </div>

            <div class="col-12 text-end">
                <a href="{{URL::to('admin/login/forget_password')}}" class="color-blue">Forget Password</a>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <div class="form-group form-check login-form-check">
                        <input type="checkbox" {{ Cookie::get('remember_me') ? 'checked' : '' }}  name="remember_me" class="form-check-input login-ipcheck" id="exampleCheck1" />
                        <label class="form-check-label m-0" for="exampleCheck1">Remember me</label>
                    </div>
                </div>
            </div>

            <input type="submit" name="submit" class="signin-btn mt-5" value="Sign in" id="login-btn" />

            <p class="sigup-text">
                Don’t have an account? <a href="{{ URL::to('signup') }}" class="color-blue">Sign up</a>
            </p>
        </form>
    </div>
</div>
@endsection
@push("page_css")
    <style>
        .custom-field input:focus + .placeholder.mobile_no{
            opacity: 1;
        }
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
@endpush
@push("page_js")
<!-- Validation Implementtaion -->
<script>
    (function () {
        'use strict';

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation');

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    // Check if the form is valid
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                }, false);
            });
    })();

</script>
    <script>
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
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function () {
            var passwordInput = document.getElementById('custom-input2');
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
    </script>
@endpush