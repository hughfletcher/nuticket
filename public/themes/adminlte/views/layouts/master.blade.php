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
    <body class="hold-transition skin-blue {{ user('is_staff') ? 'fixed sidebar-mini' :  'layout-top-nav layout-boxed' }}">
        <div class="wrapper">
        <header class="main-header">
            @if(user('is_staff'))
            <a href="index.html" class="logo">
               <!-- mini logo for sidebar mini 50x50 pixels -->
              <span class="logo-mini">{!! config('theme.adminlte.logomini', 'NU<b>T</b>') !!}</span>
              <!-- logo for regular state and mobile devices -->
              <span class="logo-lg">{!! config('theme.adminlte.logo', 'Nu<b>Ticket</b>') !!}</span>
            </a>
            @endif
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                @if(user('is_staff'))
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                @endif
                @if(!user('is_staff'))
                <div class="navbar-header">
                    <a href="../../index2.html" class="navbar-brand">{!! config('theme.adminlte.logo', 'Nu<b>Ticket</b>') !!}</a>
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                    </button>
                </div>
                @include('common.topnav', ['items' => $menu_nav->roots()])
                @endif
              <!-- Navbar Right Menu -->
              @if(user())
              <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                  <!-- Messages: style can be found in dropdown.less-->

                  <!-- /.messages-menu -->

                  <!-- Control Sidebar Toggle Button -->
                  <li class="dropdown user">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="glyphicon glyphicon-user"></i>
                                <span>{{ user('display_name') }} <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                @if (config('settings.time.enabled'))
                                <li><a href="{{ route('me.time.index') }}">Time Log</a></li>
                                <li class="divider"></li>
                                @endif
                                <li><a href="{{ route('session.index') }}">Logout</a></li>
                            </ul>
                        </li>
                </ul>
              </div>
              @endif
            </nav>
        </header>

        @if(user('is_staff'))
        <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                @include('common.sidebar', ['items' => $menu_nav->roots()])
                <!-- /.sidebar -->
            </section>
        </aside>
        @endif

            <!-- Right side column. Contains the navbar and content of the page -->
        <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                    @yield('content')


        </div>

        <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0.0-alpha 2
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2015 <a href="https://github.com/hughfletcher/nuticket">NuTicket</a>.</strong> All rights reserved.
  </footer>
    </div>


        <script src="{{ cached_asset('js/jquery.min.js') }}"></script>
        <script src="{{ cached_asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ cached_asset('js/libs.js') }}" type="text/javascript"></script>
        <script src="{{ cached_asset('js/app.js') }}" type="text/javascript"></script>
        <script type="text/javascript">$(document).ready(app.init('{{ camel_case(str_replace('.', '_', Request::route()->getName())) }}'));</script>
    </body>
</html>
