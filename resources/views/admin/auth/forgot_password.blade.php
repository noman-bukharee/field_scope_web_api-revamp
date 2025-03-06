@extends('admin.auth.master')
@section('content')
@section('title', 'Forgot Password')
    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
        <div class="auth-logo">
            <a href="{{ URL::to('') }}">
                <img src="{{asset("../assets/img/auth-logo.png")}}" alt="" />
            </a>
        </div>
        <div class="auth-box">
        @include('admin.error')
            <form method="post">
                {{ csrf_field() }}
                <div class="col-12">
                    <h1>Forgot Password</h1>
                    <p>Enter the email address associated with your account</p>
                </div>
                <input type="hidden" class="submit_url"  value="{{ URL::to('user/forgot/password') }}" />
                <div class="col-12">
                    <label class="custom-field two">
                        <input type="email" name="email"  placeholder="&nbsp;" id="custom-input1" required="required" autocomplete="off" />
                        <span class="placeholder">Email Address</span>
                    </label>
                </div>
                <button class="signin-btn" id="submit-btn">Submit</button>
                <p class="sigup-text">
                    Already have an account?
                    <a href="{{URL::to('admin/login')}}" class="color-blue">Sign in</a>
                </p>
            </form>
        </div>
    </div>
    @include('admin.auth.footer')
@endsection
@push("page_css")
    <style>

    </style>
@endpush
@push("page_js")
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
    </script>
<script src="{{asset('assets/js/tenant-js/common.js')}}"></script>    
@endpush