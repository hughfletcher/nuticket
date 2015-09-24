<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{{ Config::get('app.name') }} | @yield('title')</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <meta name="_token" content="{{ csrf_token() }}" />
        <meta name="url" content="{{ url() }}" />
        <meta name="api" content="{{ url('api') }}" />
        <link href="{{ cached_asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ cached_asset('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ cached_asset('css/all.css') }}" rel="stylesheet" type="text/css" />


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-blue fixed">{{-- dd($main->roots()[1]) --}}
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="index.html" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                Support
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        
                        <!-- Notifications: style can be found in dropdown.less -->
                        
                        <!-- Tasks: style can be found in dropdown.less -->
                        
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span>{{ user('display_name') }} <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                @if (config('system.time.user_edit'))
                                <li><a href="{{ route('me.time.index') }}">Time Log</a></li>
                                @endif
                                <li class="divider"></li>
                                <li><a href="{{ route('session.index') }}">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                @include('common.sidebar', ['items' => $menu_nav->roots()])
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side" id="content">
                <!-- Content Header (Page header) -->
                    @yield('content')


            </aside>
        </div>


        <script src="{{ cached_asset('js/jquery.min.js') }}"></script>
        <script src="{{ cached_asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ cached_asset('js/libs.js') }}" type="text/javascript"></script>
        <script src="{{ cached_asset('js/app.js') }}" type="text/javascript"></script>
        <script type="text/javascript">$(document).ready(app.init('{{ camel_case(str_replace('.', '_', Request::route()->getName())) }}'));</script>
    </body>
</html>
