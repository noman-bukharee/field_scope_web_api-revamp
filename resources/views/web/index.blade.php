<!DOCTYPE html>
<html lang="en">
<head>
    @section('title', 'Home')
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="{{ asset('image/logo-icon.png') }}" type="image/gif" size="16x16">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
            crossorigin="anonymous"
    />
    <link rel="stylesheet" href="{{asset("assets/css/style.css")}}" />
    <link rel="stylesheet" href="{{asset("assets/css/responsive.css")}}" />
    <title>{{env('APP_NAME')}} - @yield('title')</title>
</head>
<body>
<header>
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="logo">
                    <img src="{{asset("assets/img/logo.png")}}" alt="" />
                </div>
            </div>
            <nav>
                <ul class="d-flex align-items-center">
                    <li class="me-2"><p>Already have an Account?</p></li>
                    <li>
                        <a href="{{ url('/admin/login') }}" class="btn-theme auth-btn">Login</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
<main>
    <section class="banner-sec">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1>
                        Streamline Your Inspections with <br />
                        the Power of Technology
                    </h1>
                    <p>
                        The Cutting-Edge mobile app designed <br />for Inspection
                        Professionals
                    </p>
                    <div>
                        <img
                                src="{{asset("assets/img/banner-img.png")}}"
                                class="img-fluid"
                                alt=""
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="feature-sec">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="font-38 text-center mb-5">Key Features</h2>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-img">
                            <img src="{{asset("assets/img/convenience.png")}}" alt="" />
                        </div>
                        <div class="feature-body">
                            <h2>Mobile Convenience</h2>
                            <p>
                                Perform inspections on the go with our user-friendly mobile
                                <br />
                                app. Say goodbye to paperwork and hello to the ease of
                                <br />
                                digital inspections.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-img">
                            <img src="{{asset("assets/img/integration.png")}}" alt="" />
                        </div>
                        <div class="feature-body">
                            <h2>Media Integration</h2>
                            <p>
                                Capture and attach photos and tags directly within the
                                <br />
                                app. Visualize inspection findings and provide a more <br />
                                comprehensive overview to your clients.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-img">
                            <img src="{{asset("assets/img/storage.png")}}" alt="" />
                        </div>
                        <div class="feature-body">
                            <h2>Cloud Storage</h2>
                            <p>
                                Securely store all your inspection data in the cloud. Access
                                <br />
                                your reports from anywhere, at any time, and never worry
                                <br />
                                about losing valuable information.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="work-sec">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="font-48 color-fff text-center mb-5">
                        How FieldScope Works
                    </h2>
                </div>
            </div>
            <div class="row work-bg">
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="work-card">
                        <div class="work-img">
                            <img src="{{asset("assets/img/img-1.png")}}" alt="" />
                        </div>
                        <div class="work-body">
                            <h2>Sign Up</h2>
                            <p>
                                Create your account and Buy 
                                subscription to start your journey
                                with Field scope
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="work-card">
                        <div class="work-img">
                            <img src="{{asset("assets/img/img-2.png")}}" alt="" />
                        </div>
                        <div class="work-body">
                            <h2>Create Team</h2>
                            <p>
                                Add your inspectors to assign 
                                projects and view their created 
                                projects.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="work-card">
                        <div class="work-img">
                            <img src="{{asset("assets/img/img-3.png")}}" alt="" />
                        </div>
                        <div class="work-body">
                            <h2>Conduct Inspections</h2>
                            <p>
                                Use the app to perform inspections
                                efficiently, with the ability to capturemedia data
                                and apply editor function.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="work-card">
                        <div class="work-img">
                            <img src="{{asset("assets/img/img-4.png")}}" alt="" />
                        </div>
                        <div class="work-body">
                            <h2>Generate Reports</h2>
                            <p>
                                Instantly generate professional,
                                detailed reports with the click of 
                                a button.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="chooseus-sec">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div>
                        <img
                                src="{{asset("assets/img/choose-usimg.png")}}"
                                alt=""
                                class="img-fluid"
                        />
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <h2 class="font-38 mb-5">Why Choose FieldScope</h2>
                    <div class="col-12 col-md-12 col-lg-10 col-xl-9 col-xxl-8">
                        <div class="choose-card choose-card-blue">
                            <h2>Efficiency and Accuracy</h2>
                            <p>
                                Maximize efficiency and accuracy in your inspection
                                processes, saving both time and resources.
                            </p>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-10 col-xl-9 col-xxl-8">
                        <div class="choose-card">
                            <h2>Scalability</h2>
                            <p>
                                Grow your business with a scalable solution that adapts to
                                your evolving needs.
                            </p>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-10 col-xl-9 col-xxl-8">
                        <div class="choose-card">
                            <h2>Security</h2>
                            <p>
                                Rest easy knowing that your data is stored securely in the
                                cloud with advanced encryption measures.
                            </p>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-10 col-xl-9 col-xxl-8">
                        <div class="choose-card">
                            <h2>24/7 Support</h2>
                            <p>
                                Our dedicated support team is available around the clock to
                                assist you with any inquiries or technical issues.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<footer>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-6">
                <h2 class="font-38">Join FieldScope Today</h2>
                <p class="mt-4">
                    Revolutionize your inspection processes with FieldScope.
                    <br />
                    <span class="d-block mt-2"
                    >Sign up now to experience the future of mobile inspection
								technology.</span
                    >
                </p>
                <div class="d-flex align-items-center mt-4">
                    <div class="google-img">
                        <img src="{{asset("assets/img/google-img.png")}}" alt="" />
                    </div>
                    <div class="ms-3 appstore-img">
                        <img src="{{asset("assets/img/appstore-img.png")}}" alt="" />
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="footer-sidebar">
                    <img
                            src="{{asset("assets/img/footer-sideimg.png")}}"
                            alt=""
                            class="img-fluid"
                    />
                </div>
            </div>
            <div class="col-12">
                <p class="footer-para">
                    All Rights Reserved © FieldScope 2024 | Terms of use | Privacy
                    policy
                </p>
            </div>
        </div>
    </div>
</footer>
<script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"
></script>
<script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"
></script>
<script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"
></script>
</body>
</html>