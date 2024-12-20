
<header class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <div
                        class="d-flex align-items-center justify-content-between w-100"
                >
                    <div>
                        <button class="btn ham-btn" id="menu-toggle">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                    </div>
                    <div>
                        <ul class="header-menu d-flex align-items-center">
                            @if($roleName == 'admin')
                            <li>
                                <a href="{{ URL::to('admin/settings') }}">
                                    <img src="{{asset("assets/img/setting.png")}}" alt="" />
                                </a>
                            </li>
                            @endif
                            <li class="icon-button">
                               
                                <div class="dropdown noti-drop">
                                    <button
                                            class="user-profile profile-drop dropdown-toggle"
                                            type="button"
                                            id="actionMenu1"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                    >
                                    <img src="{{asset("assets/img/notification.png")}}" alt="" />
                                     <span class="icon-button__badge">2</span>
                                    </button>
                                        <ul
                                                class="dropdown-menu"
                                                aria-labelledby="actionMenu1"
                                        >
                                            <li>
                                                <a class="dropdown-item" href="#">A New Territory has been assigned to you</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">A New Territory has been assigned to you</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">A New Territory has been assigned to you</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">A New Territory has been assigned to you</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">A New Territory has been assigned to you</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item color-light text-center" href="#">View all</a>
                                            </li>
                                        </ul>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown ">
                                    <button
                                            class=" profile-drop dropdown-toggle"
                                            type="button"
                                            id="actionMenu1"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                    >
                                        <img class="profile-img" src="{{$userImage}}" alt="" />
                                    </button>
                                        <ul
                                                class="dropdown-menu"
                                                aria-labelledby="actionMenu1"
                                        >
                                            <li>
                                                <a
                                                        class="dropdown-item"
                                                        href="{{ URL::to('admin/logout') }}"
                                                >Logout</a
                                                >
                                            </li>
                                            
                                        </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>