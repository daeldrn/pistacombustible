<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mi Aplicación')</title>

    <!--STYLESHEET-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;subset=latin" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/nifty.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/animate-css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/switchery/switchery.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('css/demo/nifty-demo.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/pace/pace.min.css') }}" rel="stylesheet">
    <script src="{{ asset('plugins/pace/pace.min.js') }}"></script>
    
    @stack('styles')
</head>

<body>
    <div id="container" class="effect mainnav-lg">

        <!--NAVBAR-->
        <header id="navbar">
            <div id="navbar-container" class="boxed">
                <div class="navbar-header">
                    <a href="{{ route('dashboard') }}" class="navbar-brand">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="brand-icon">
                        <div class="brand-title">
                            <span class="brand-text">Mi Aplicación</span>
                        </div>
                    </a>
                </div>

                <div class="navbar-content clearfix">
                    <ul class="nav navbar-top-links pull-left">
                        <li class="tgl-menu-btn">
                            <a class="mainnav-toggle" href="javascript:void(0);">
                                <i class="fa fa-navicon fa-lg"></i>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav navbar-top-links pull-right">
                        <li id="dropdown-user" class="dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                                <span class="pull-right">
                                    <img class="img-circle img-user media-object" src="{{ asset('img/av1.png') }}" alt="Profile Picture">
                                </span>
                                <div class="username hidden-xs">{{ Auth::user()->name }}</div>
                            </a>

                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right panel-default">
                                <ul class="head-list">
                                    <li>
                                        <a href="#"><i class="fa fa-user fa-fw"></i> Perfil</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa fa-cog fa-fw"></i> Configuración</a>
                                    </li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer; width: 100%; text-align: left;">
                                                <a style="display: block; padding: 10px 15px;">
                                                    <i class="fa fa-sign-out fa-fw"></i> Cerrar Sesión
                                                </a>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="boxed">
            <div id="content-container">
                <div id="page-title">
                    <h1 class="page-header text-overflow">@yield('page-title', 'Dashboard')</h1>
                </div>

                <div id="page-content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>

            <!--MAIN NAVIGATION-->
            <nav id="mainnav-container">
                <div id="mainnav">
                    <div id="mainnav-menu-wrap">
                        <div class="nano">
                            <div class="nano-content">
                                <ul id="mainnav-menu" class="list-group">
                                    <li class="list-header">Navegación</li>
                                    
                                    <li class="{{ request()->routeIs('dashboard') ? 'active-link' : '' }}">
                                        <a href="{{ route('dashboard') }}">
                                            <i class="fa fa-dashboard"></i>
                                            <span class="menu-title">Dashboard</span>
                                        </a>
                                    </li>

                                    <li class="{{ request()->routeIs('users.*') ? 'active-link' : '' }}">
                                        <a href="{{ route('users.index') }}">
                                            <i class="fa fa-users"></i>
                                            <span class="menu-title">Usuarios</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <i class="fa fa-shopping-cart"></i>
                                            <span class="menu-title">Pedidos</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <i class="fa fa-file-text"></i>
                                            <span class="menu-title">Reportes</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <i class="fa fa-cog"></i>
                                            <span class="menu-title">Configuración</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <footer id="footer">
            <div class="hide-fixed pull-right pad-rgt"></div>
            <p class="pad-lft">&#0169; {{ date('Y') }} Mi Aplicación</p>
        </footer>

        <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
    </div>

    <!--JAVASCRIPT-->
    <script src="{{ asset('js/jquery-2.1.1.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui-1.11.4.custom/jquery-ui.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/fast-click/fastclick.min.js') }}"></script>
    <script src="{{ asset('js/nifty.js') }}"></script>
    <script src="{{ asset('plugins/switchery/switchery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.js') }}"></script>
    <script src="{{ asset('js/demo/nifty-demo.js') }}"></script>

    @stack('scripts')
</body>
</html>
