<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{{ Config::get('app.name') }} | @yield('title')</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <meta name="_token" content="{{ csrf_token() }}" />
        <meta name="api" content="{{ url('api') }}" />
        <link href="{{ url('themes/default/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('themes/default/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('themes/default/css/all.css') }}" rel="stylesheet" type="text/css" />


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


        <script src="{{ url('themes/default/js/jquery.min.js') }}"></script>
        <script src="{{ url('themes/default/js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ url('themes/default/js/libs.js') }}" type="text/javascript"></script>
        
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        {{-- // <script src="js/AdminLTE/dashboard.js" type="text/javascript"></script> --}}

        <!-- AdminLTE for demo purposes -->
        {{-- // <script src="js/AdminLTE/demo.js" type="text/javascript"></script> --}}

                <script type="text/javascript">
            $(document).ready(function() {
                // nut = new controller('#content');
                // can.route.ready();
                api = $('meta[name="api"]').attr('content');
                $(function() {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                        }
                    });
                });
                var priFull = [
                    {id:1,text:'1 - Business is stopped'},
                    {id:2,text:'2 - User is stopped'},
                    {id:3,text:'3 - Business is hendered'},
                    {id:4,text:'4 - User is hendered'},
                    {id:5,text:'5 - Increase productivity/savings'}
                ];
                var priAbbr = [
                    {id:1,text:'1'},
                    {id:2,text:'2'},
                    {id:3,text:'3'},
                    {id:4,text:'4'},
                    {id:5,text:'5'}
                ];
                //tab linking
                var url = document.location.toString();
                if (url.match('#')) {
                    $('.nav-tabs a[href=#'+url.split('#')[1]+']').tab('show') ;
                } 
                $('.go-show-tab').click(function() {
                    $('.nav-tabs a[href="' + $(this).attr('href') + '"]').tab('show')
                })
                
                // $.fn.editable.defaults.ajaxOptions = {type: "PUT"};
                // $('.editable-priority').editable({
                //     send: 'always',
                //     type: 'select',  
                //     source: priorities,
                //     params: function(params) {
                //         //originally params contain pk, name and value
                //         var data = {'priority': params.value};
                //         // data.user_id = params.value;
                //         return data;
                //     },
                // });
                // $('.editable-users').editable({
                //     send: 'always',
                //     type: 'select2',
                //     params: function(params) {
                //         //originally params contain pk, name and value
                //         var data = {'user_id': params.value};
                //         // data.user_id = params.value;
                //         return data;
                //     },
                //     select2: {
                //         minimumInputLength: 2,
                //         ajax: {
                //             url: '../api/user?fields=id,display_name',
                //             dataType: 'json',
                //             data: function (term) {
                //                 return {
                //                     q: term // search term
                //                 };
                //             },
                //             results: function(data) {
                //                 var results = [];
                //                   $.each(data, function(index, item){
                //                     results.push({
                //                       id: item.id,
                //                       text: item.display_name
                //                     });
                //                   });
                //                   return {
                //                       results: results
                //                   };
                //             }
                //         }
                //     }
                // });
                $(".select2-users").select2({
                    minimumInputLength: 2,
                    cache: true,
                    ajax: {
                        url: api + '/users?fields=id,display_name',
                        dataType: 'json',
                        data: function (term) {
                            return {
                                q: term // search term
                            };
                        },
                        results: function(data) {
                            var results = [];
                            $.each(data, function(index, item){
                                results.push({
                                    id: item.id,
                                    text: item.display_name
                                });
                            });
                            return {
                                results: results
                            };
                        }
                    },
                    initSelection: function(element, callback) {
                        var id = $(element).val();
                        if (id !== "") {
                            $.ajax(api + '/users/' + id + '?fields=id,display_name', {
                                dataType: "json"
                            }).done(function(r) {
                                console.log(r);
                                var data = {'id': id, 'text': r.display_name};
                                callback(data); 
                            });
                        }
                        
                    }
                });
                $('.select2-priorities').select2({
                    minimumResultsForSearch: 8,
                    data: priFull
                });
              $("select.select2-default").select2({minimumResultsForSearch: 8});
              $(".status-select").select2({
                    multiple: true,
                    separator: '-',
                    data:[
                        {id:'closed',text:'Closed'},
                        {id:'open',text:'Open'},
                        {id:'new',text:'New'}
                    ]
              });
              $(".priority-select").select2({
                    multiple: true,
                    separator: '-',
                    data: priAbbr
              });
              $(".dept-select").select2({
                    multiple: true,
                    separator: '-',
                    data: [@foreach($depts as $id => $name){id:{{ $id }},text:'{{ $name }}'},@endforeach ]
              });
                $(".assigned-select").select2({
                    multiple: true,
                    separator: '-',
                    data: [@foreach(App\Staff::all() as $row){id:{{ $row->id }},text:'{{ $row->user->display_name }}'},@endforeach]
              });
                $('#createtime').daterangepicker();
                $('.daterange').daterangepicker({
                    opens: 'left',
                    ranges: {
                     'Today': [moment(), moment()],
                     'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                     'This Week': [moment().startOf('week'), moment().endOf('week')],
                     'Last Week': [moment().subtract('weeks', 1).startOf('week'), moment().subtract('weeks', 1).endOf('week')],
                     'This Month': [moment().startOf('month'), moment().endOf('month')],
                     'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                  },
                });
                $('.singledate').daterangepicker({ 
                    singleDatePicker: true, 
                    format: 'MM/DD/YYYY', 
                    drops: 'up'
                });
                $('.singledatedown').daterangepicker({ 
                    singleDatePicker: true, 
                    format: 'MM/DD/YYYY', 
                    drops: 'down'
                });
                // $(".textarea").wysihtml5({
                //         "size": "sm" // options are xs, sm, lg
                // });
                // $('#reply-date').daterangepicker({ singleDatePicker: true, timePickerIncrement: 15, format: 'MM/DD/YYYY h:mm a', timePicker: true, opens: 'right' });
            });
        </script>
    </body>
</html>
