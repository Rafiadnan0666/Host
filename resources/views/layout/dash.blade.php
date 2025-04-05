<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>HOST N STREAM</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="{{ asset('kaiadmin') }}/assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="{{ asset('kaiadmin') }}/assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{ asset('kaiadmin') }}assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('kaiadmin') }}/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('kaiadmin') }}/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="{{ asset('kaiadmin') }}/assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ asset('kaiadmin') }}/assets/css/demo.css" />
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
              <img
                src="{{ asset('kaiadmin') }}/assets/img/kaiadmin/logo_light.svg"
                alt="navbar brand"
                class="navbar-brand"
                height="20"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              @auth
              @if (Auth::user()->role == 'a')
                  <li class="nav-item">
                      <a href="{{ route('admin') }}">
                          <i class="fas fa-file"></i>
                          <p>Dashboard</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('orderdash') }}">
                          <i class="fas fa-file"></i>
                          <p>Orders</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('usercontrol.index') }}">
                          <i class="fas fa-file"></i>
                          <p>User Card</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ url('/chat') }}">
                          <i class="fas fa-file"></i>
                          <p>Chat</p>
                      </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ url('/user') }}">
                        <i class="fas fa-file"></i>
                        <p>User Order</p>
                    </a>
                </li>
          
              @elseif (Auth::user()->role == 'p' || Auth::user()->role == 'h')
                  <li class="nav-item">
                      <a href="{{ url('host') }}">
                          <i class="fas fa-file"></i>
                          <p>Dashboard</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ url('/chat') }}">
                          <i class="fas fa-file"></i>
                          <p>Chat</p>
                      </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ url('/user') }}">
                        <i class="fas fa-file"></i>
                        <p>User Order</p>
                    </a>
                </li>
              @else
                  <li class="nav-item">
                      <p>Regis dulu le</p>
                  </li>
              @endif
          @else
              <li class="nav-item">
                  <a href="{{ route('login') }}">
                      <i class="fas fa-sign-in-alt"></i>
                      <p>Login</p>
                  </a>
              </li>
          @endauth
          
             
             
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="index.html" class="logo">
                <img
                  src="{{ asset('kaiadmin') }}/assets/img/kaiadmin/logo_light.svg"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
            <div class="container-fluid">
                <!-- Left Search (desktop only) -->
                <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="submit" class="btn btn-search pe-1">
                                <i class="fa fa-search search-icon"></i>
                            </button>
                        </div>
                        <input type="text" placeholder="Search ..." class="form-control" />
                    </div>
                </nav>
        
                <!-- Topbar Icons & User -->
                <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                    <!-- Search (Mobile) -->
                  
                    <!-- Messages -->
                   

        
                    <!-- Quick Actions -->
                    <li class="nav-item topbar-icon dropdown hidden-caret">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#"><i class="fas fa-layer-group"></i></a>
                    
                    </li>
        
                    <!-- User -->
                    <li class="nav-item topbar-user dropdown hidden-caret">
                        @if (Auth::check())
                            <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#">
                                <div class="avatar-sm">
                                    <img src="{{ Auth::user()->gambar ? asset('storage/' . Auth::user()->gambar) : asset('images/default.png') }}"
                                         class="card-img-top avatar-img rounded-circle"
                                         onerror="this.src='{{ asset('images/default.png') }}'"
                                         alt="Profile Image" />
                                </div>
                                <span class="profile-username">
                                    <span class="op-7">Hi,</span>
                                    <span class="fw-bold">{{ Auth::user()->name }}</span>
                                </span>
                            </a>
        
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li>
                                        <div class="user-box">
                                            <div class="avatar-lg">
                                                <img src="{{ Auth::user()->gambar ? asset('storage/' . Auth::user()->gambar) : asset('images/default.png') }}"
                                                     class="card-img-top avatar-img rounded"
                                                     onerror="this.src='{{ asset('images/default.png') }}'"
                                                     alt="Profile Image" />
                                            </div>
                                            <div class="u-text">
                                                <h4>{{ Auth::user()->name }}</h4>
                                                <p class="text-muted">{{ Auth::user()->email }}</p>
                                                <a href="{{ url('profile') }}" class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ url('profile') }}">My Profile</a>
                                        <a class="dropdown-item" href="#">My Balance</a>
                                        <a class="dropdown-item" href="#">Inbox</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Account Settings</a>
                                        <div class="dropdown-divider"></div>
                                        <!-- Logout -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">Logout</button>
                                        </form>
                                    </li>
                                </div>
                            </ul>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary me-2">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
                        @endif
                    </li>
        
                    @if (Auth::check())
                        <!-- Optional: Direct logout button visible -->
                        <li class="nav-item ms-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">Logout</button>
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
        
        
          <!-- End Navbar -->
        </div>

        <div class="container">
          <div class="page-inner">
            <div>
              @if (Auth::check())
        <h3 class="fw-bold mb-3">Hello {{ Auth::user()->name }}</h3>
      @else
        <h3 class="fw-bold mb-3">Welcome, please log in or register</h3>
      @endif
            </div>
            <div>
              @yield('konten')
            </div>
          </div>
        </div>
        </div>
      </div>

      <!-- Custom template | don't include it in your project! -->
     
      <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset('kaiadmin') }}/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('kaiadmin') }}/assets/js/core/popper.min.js"></script>
    <script src="{{ asset('kaiadmin') }}/assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('kaiadmin') }}/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="{{ asset('kaiadmin') }}/assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset('kaiadmin') }}/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="{{ asset('kaiadmin') }}/assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="{{ asset('kaiadmin') }}/assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    {{-- <script src="{{ asset('kaiadmin') }}/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script> --}}

    <!-- jQuery Vector Maps -->
    <script src="{{ asset('kaiadmin') }}/assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="{{ asset('kaiadmin') }}/assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('kaiadmin') }}/assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('kaiadmin') }}/assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="{{ asset('kaiadmin') }}/assets/js/setting-demo.js"></script>
    <script src="{{ asset('kaiadmin') }}/assets/js/demo.js"></script>
    <script>
      $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#177dff",
        fillColor: "rgba(23, 125, 255, 0.14)",
      });

      $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#f3545d",
        fillColor: "rgba(243, 84, 93, .14)",
      });

      $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#ffa534",
        fillColor: "rgba(255, 165, 52, .14)",
      });
    </script>
  </body>
</html>
