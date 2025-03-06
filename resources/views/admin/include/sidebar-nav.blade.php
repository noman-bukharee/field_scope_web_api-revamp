
<aside class="flex-shrink-0 p-3" id="sidebar">
    
        <header class="sidebar-heading">
            <div class="side-logo">
            @if($roleName == 'admin')
                <a href="{{ URL::to('admin/user_type') }}">
                    <img src="{{asset("assets/img/logo.png")}}" alt="" />
                </a>
            @elseif($roleName == 'manager')
                <a href="{{ URL::to('admin/project') }}">
                    <img src="{{asset("assets/img/logo.png")}}" alt="" />
                </a>
            @elseif($roleName == 'standard')
                <a href="{{ URL::to('admin/photo_feed') }}">
                    <img src="{{asset("assets/img/logo.png")}}" alt="" />
                </a>
            @endif
                
            </div>
        </header>
        <nav>
            <ul class="nav flex-column mt-5">
            @if($roleName == 'admin' || $roleName == 'manager' || $roleName == 'standard')
                <li class="nav-item">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <img
                                    src="{{asset("../assets/img/project-icon.png")}}"
                                    class="img-fluid"
                                    alt=""
                            />
                        </div>
                        <div class="mt-2">
                            <a class="font-16 @if(request()->is('admin/project')) active-nav @endif" href="{{ URL::to('admin/project') }}">Projects</a>
                        </div>
                    </div>
                </li>
                @endif
                @if($roleName == 'admin')
                <li class="nav-item">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <div class="accordion-header" id="headingOne">
                                <button
                                        class="accordion-button @if(!request()->is('admin/user_type') || !request()->is('admin/user_management')) collapsed @endif "
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne"
                                        aria-expanded="false"
                                        aria-controls="collapseOne"
                                >
                                    <div class="me-3">
                                        <img
                                                src="{{asset("../assets/img/user-icon.png")}}"
                                                class="img-fluid"
                                                alt=""
                                        />
                                    </div>
                                    <div class="mt-2">
                                        <a class="font-16" href="#">Users</a>
                                    </div>
                                </button>
                            </div>
                            <div
                                    id="collapseOne"
                                    class="accordion-collapse @if(request()->is('admin/user_type') || request()->is('admin/user_management')) show @else collapse @endif "
                                    aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample"
                            >
                                <div class="sub-menu">
                                    <ul>
                                        <li>
                                            <a class="font-16 @if(request()->is('admin/user_type')) active-nav @endif" href="{{ URL::to('admin/user_type') }}" class="sub-item">- User Types</a>
                                        </li>
                                        <li>
                                            <a class="font-16 @if(request()->is('admin/user_management')) active-nav @endif" href="{{ URL::to('admin/user_management') }}" class="sub-item">- User Management</a>
                                        </li>
                                        <!-- <li>
                                            <a class="font-16 @if(request()->is('admin/user_roles')) active-nav @endif" href="{{ URL::to('admin/user_roles') }}" class="sub-item">- User Roles</a>
                                        </li> -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endif
                @if($roleName == 'admin' || $roleName == 'manager' || $roleName == 'standard')
                <li class="nav-item">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <img
                                    src="{{asset("../assets/img/gallery-icon.png")}}"
                                    class="img-fluid"
                                    alt=""
                            />
                        </div>
                        <div class="mt-2">
                            <a class="@if(request()->is('admin/photo_feed')) active-nav @endif font-16" href="{{ URL::to('admin/photo_feed') }}">Photo Feeds</a>
                        </div>
                    </div>
                </li>
                @endif
                @if($roleName == 'admin')
                <li class="nav-item">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <div class="accordion-header" id="headingOne">
                                <button
                                        class="accordion-button  @if(!request()->is('admin/inspection_area') || !request()->is('admin/photo_views')) collapsed @endif "
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#inspection"
                                        aria-expanded="false"
                                        aria-controls="inspection"
                                >
                                    <div class="me-3">
                                        <img
                                                src="{{asset("../assets/img/inspection-icon.png")}}"
                                                class="img-fluid"
                                                alt=""
                                        />
                                    </div>
                                    <div class="mt-2">
                                        <a class="font-16" href="#">Inspection</a>
                                    </div>
                                </button>
                            </div>
                            <div
                                    id="inspection"
                                    class="accordion-collapse  @if(request()->is('admin/inspection_area') || request()->is('admin/photo_views')) show @else collapse @endif "
                                    aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample"
                            >
                                <div class="sub-menu">
                                    <ul>
                                        <li>
                                        <a class="font-16 @if(request()->is('admin/inspection_area')) active-nav @endif" href="{{ URL::to('admin/inspection_area') }}">- Inspection Areas</a>
                                        </li>
                                        <li>
                                        <a class="font-16 @if(request()->is('admin/photo_views')) active-nav @endif" href="{{ URL::to('admin/photo_views') }}">- Photo Views</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endif
                <!-- End Chats Dropdown -->
                 @if($roleName == 'admin')
                <li class="nav-item">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <img
                                    src="{{asset("../assets/img/gallery-icon.png")}}"
                                    class="img-fluid"
                                    alt=""
                            />
                        </div>
                        <div class="mt-2">
                            <a class="font-16 @if(request()->is('admin/required_photos')) active-nav @endif" href="{{ URL::to('admin/required_photos') }}">Required Photos</a>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <div class="accordion-header" id="headingOne">
                                <button
                                        class="accordion-button  @if(!request()->is('admin/photo_tags') || !request()->is('admin/photo_tags')) collapsed @endif "
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#tag"
                                        aria-expanded="false"
                                        aria-controls="tag"
                                >
                                    <div class="me-3">
                                        <img
                                                src="{{asset("../assets/img/tag-icon.png")}}"
                                                class="img-fluid"
                                                alt=""
                                        />
                                    </div>
                                    <div class="mt-2">
                                        <a class="font-16" href="#">Tags</a>
                                    </div>
                                </button>
                            </div>
                            <div
                                    id="tag"
                                    class="accordion-collapse  @if(request()->is('admin/photo_tags') || request()->is('admin/photo_tags')) show @else collapse @endif "
                                    aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample"
                            >
                                <div class="sub-menu">
                                    <ul>
                                        <li>
                                            <a class="font-16 @if(request()->is('admin/photo_tags')) active-nav @endif" href="{{ URL::to('admin/photo_tags') }}">- Inspection Photos Tags</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <img
                                    src="{{asset("../assets/img/report-icon.png")}}"
                                    class="img-fluid"
                                    alt=""
                            />
                        </div>
                        <div class="mt-2">
                            <a class="font-16 @if(request()->is('admin/reports')) active-nav @endif" href="{{ URL::to('admin/reports') }}">Reports </a>
                        </div>
                    </div>
                </li>
               
                <li class="nav-item">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <img
                                    src="{{asset("../assets/img/survey-icon.png")}}"
                                    class="img-fluid"
                                    alt=""
                            />
                        </div>
                        <div class="mt-2">
                            <a class="font-16 @if(request()->is('admin/questionnaire')) active-nav @endif" href="{{ URL::to('admin/questionnaire') }}">Questionnaire</a>
                        </div>
                    </div>
                </li>
                
                <li class="nav-item">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <img
                                    src="{{asset("../assets/img/subscription-icon.png")}}"
                                    class="img-fluid"
                                    alt=""
                            />
                        </div>
                        <div class="mt-2">
                            <a class="font-16 @if(request()->is('admin/subscription')) active-nav @endif" href="{{ URL::to('admin/subscription') }}">Subscription</a>
                        </div>
                    </div>
                </li>
            </ul>
            @endif
            <ul class="user-list d-flex align-items-center">
                <li>
                    <div class="user-profile">
                        <img class="profile-img" src="{{$userImage}}" alt="" />
                    </div>
                </li>
                <li style="margin-left: 10px">
                    <p class="font-16">{{$user['first_name']}} {{$user['last_name']}}</p>
                    <!-- <p class="color-light">Super Admin</p> -->
                </li>
            </ul>
        </nav>
    </aside>