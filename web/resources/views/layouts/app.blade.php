<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset("img/brand/favicon.png") }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title><?php echo $title ?> | EmiFoodLovers</title>

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap-glyphicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    @stack("extra-css")
    <link href="{{ asset('css/theme/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/theme/colors/blue.css') }}" id="theme" rel="stylesheet">
</head>
<body class="fix-header fix-sidebar card-no-border">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
        </svg>
    </div>

    <div id="main-wrapper">
        <header class="topbar">
            <nav class="navbar fixed top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/">
                        <!-- Logo icon -->
                        <b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            {{--<img src="../assets/images/logo-icon.png" alt="homepage" class="dark-logo" />--}}
                            <!-- Light Logo icon -->
                            <img src="{{ asset("img/brand/Icon.png") }}" alt="homepage" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span>
                         <!-- dark Logo text -->
                         {{--<img src="../assets/images/logo-icon.png" alt="homepage" class="dark-logo" />--}}
                         <!-- Light Logo text -->
                         <img src="{{ asset("img/brand/Logo.png") }}" class="light-logo" alt="" /></span>
                    </a>
                </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                    </ul>
                    <ul class="navbar-nav my-lg-0">
                        @guest
                            <li class="nav-item"><a href="{{ route("login") }}" class="nav-link waves-effect waves-dark">Login</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span>{{ Auth::user()->username }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right scale-up">
                                    <ul class="dropdown-user">
                                        <li>
                                            <div class="dw-user-box">
                                                <div class="u-text">
                                                    <h4>{{ Auth::user()->name }}</h4>
                                                    <p class="text-muted">{{ Auth::user()->getRoleName() }}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit()"><i class="fa fa-power-off"></i> Logout</a></li>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </ul>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">PERSONAL</li>
                        <li><a class="has-arrow" href="/pos" ><i class="mdi mdi-counter"></i>POS</a></li>
                        @if(Auth::User()->authorizeRoles(["Manager"]))
                            <li>
                                <a class="has-arrow" href="/products" ><i class="mdi mdi-shopping"></i>PRODUCTS</a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="/brands">Brands</a></li>
                                    <li><a href="/dealers">Dealers</a></li>
                                    <li><a href="/product/create">Add New Product</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-receipt"></i>REPORT</a>
                                <ul aria-expanded="false" class="collapse">
                                    {{--<li><a href="/report/inventory-valuation">Inventory Valuation</a></li>--}}
                                    <li><a href="/report/purchase-transactions">Purchase Transactions</a></li>
                                    <li><a href="/report/sales-transactions">Sales Transactions</a></li>
                                    {{--<li><a href="/report/profit-loss-statement">Profit & Loss Statement</a></li>--}}
                                </ul>
                            </li>
                            <li>
                                <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-store-24-hour"></i>INVENTORY</a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="/inventory/categories">Category</a></li>
                                    <li><a href="/inventory/subcategories">SubCategory</a></li>
                                    <li>
                                        <a class="has-arrow" href="#" aria-expanded="false">Inventory Control</a>
                                        <ul>
                                            {{--<li><a href="/inventory/rules">Rules</a></li>--}}
                                            <li><a href="/inventory/stock-entry">Stock Entry</a></li>
                                        </ul>
                                    </li>
                                    {{--<li><a href="/inventory/backup">Backup Inventory</a></li>--}}
                                </ul>
                            </li>
                            <li>
                                <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-settings"></i>CONFIGURATIONS</a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="/configuration/users">Users</a></li>
                                    <li><a href="/configuration/units">Measurement Units</a></li>
                                    <li><a href="/configuration/migrate">Migrate Records/Inventory</a></li>
                                    {{--<li><a href="/configuration/backup-settings">Backup Settings</a></li>--}}
                                </ul>
                            </li>
                        @endif
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row page-titles animated fadeInDown">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0"><?php if(isset($page_title)) { echo $page_title; } else { echo "Dashboard"; } ?></h3>
                    </div>
                    <div class="col-md-7 col-4 align-self-center">
                        <div class="d-flex m-t-10 justify-content-end">
                            <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                                <div class="chart-text m-r-10">
                                    <h6 class="m-b-0"><small>TOTAL SALES FOR THIS MONTH</small></h6>
                                    <h4 class="m-t-0 text-info"><span style="text-decoration: line-through">N</span><span class="lblMonthSales"></span></h4></div>
                                <div class="spark-chart">
                                    <div id="monthChart"></div>
                                </div>
                            </div>
                            <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                                <div class="chart-text m-r-10">
                                    <h6 class="m-b-0"><small>TOTAL SALES FOR LAST MONTH</small></h6>
                                    <h4 class="m-t-0 text-primary"><span style="text-decoration: line-through">N</span><span class="lblLastMonthSales"></span></h4></div>
                                <div class="spark-chart">
                                    <div id="lastMonthChart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if (session('status'))
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="panel panel-default">
                                <div class="panel-heading">Status</div>
                                <div class="panel-body">
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                @yield("content")
                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->


            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer">
                &copy; {{ date("Y") }} Emi FoodLovers Integrated Services. All rights reserved.
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('js/waves.js') }}"></script>
    <script src="{{ asset('js/sticky-kit.min.js') }}"></script>
    <script src="{{ asset('js/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('js/theme/custom.min.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/HelperFunctions.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('extra-js')
</body>
</html>
