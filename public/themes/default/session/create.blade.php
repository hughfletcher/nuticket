 <!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>{{ Config::get('app.name') }} | Log in</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        {{-- <link href="{{ theme('assets/css/vendor.css') }}" rel="stylesheet" type="text/css" /> --}}
        <!-- Theme style -->
        <link href="{{ url('themes/default/css/all.css') }}" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="bg-black">

        <div class="form-box" id="login-box">
            <div class="header">{{ trans('session.signin') }}</div>
            <form action="{{ route('session.store') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="body bg-gray">
                    @if (Session::get('message'))
                    <div class="form-group">
                        <div class="callout callout-danger">
                            <p class="text-red">{{ Session::get('message') }}</p>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="{{ trans('session.username') }}"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="{{ trans('session.password') }}"/>
                    </div>        
                    <div class="form-group">
                        <input type="checkbox" name="remember"/> {{ trans('session.remember_me') }}
                    </div>
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block">{{ trans('session.signmein') }}</button>  
                    @if (config('site.allow_pw_reset', false))
                    <p><a href="#">{{ trans('session.iforgot') }}</a></p>
                    @endif
                    @if (config('site.user_registration', false) == 'public')
                    <a href="#" class="text-center">{{ trans('session.register') }}</a>
                    @endif
                </div>
            </form>
        </div>


        <!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <!-- // <script src="{{ 'assets/js/bootstrap.min.js' }}" type="text/javascript"></script>         -->
        {{-- // <script src="{{ 'assets/js/vendor.js' }}" type="text/javascript"></script>         --}}
        <script src="{{ js('app.js') }}" type="text/javascript"></script>        

    </body>
</html>