<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Alpha Management</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ios.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">

    <!--DATA TABLE EXPORT-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">

    <!--CALENDER-->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link rel="stylesheet" href="{{ asset('css/w3.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
    html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
    </style>

    <script type="text/javascript" language="javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/playSound.js') }}"></script>


    <!--DATA TABLE EXPORT-->
    {{-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script> --}}
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>

    <!--CALENDER-->
    {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap-notifications.min.css">
    <script src="//js.pusher.com/3.1/pusher.min.js"></script>


</head>
<body>
    <div id="sound"></div>
    <div id="app">
        <nav class="navbar navbar-primary navbar-static-top navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <img src="{{ asset('img/logo/512x512_icon.png') }}" class="navbar-left navbar-logo"></img>
                    @if (Auth::guest())
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    @else
                    <a class="navbar-brand" href="{{ url('/home') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    @endif
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                        @else
                         <li class="dropdown dropdown-notifications">
                            <a href="#notifications-panel" class="dropdown-toggle" data-toggle="dropdown">
                                <i data-count="0" class="glyphicon glyphicon-bell notification-icon"></i>
                            </a>
                            <div class="dropdown-container">
                                <div class="dropdown-toolbar">
                                    <div class="dropdown-toolbar-actions">
                                    <a href="#">Mark all as read</a>
                                    </div>
                                    <h3 class="dropdown-toolbar-title">Notifications (<span class="notif-count">0</span>)</h3>
                                </div>
                                <ul class="dropdown-menu">
                                </ul>
                                <div class="dropdown-footer text-center">
                                    <a href="#">View All</a>
                                </div>
                            </div>
                            </li>
                            @if(Auth::user()->name != 'admin' && Auth::user()->roles->first()->name != 'closer')
                                <li><a href="{{ route('history') }}">History</a></li>
                            @endif
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                                Logout
                                            </a>
                                            @if(Auth::user()->name == 'admin')
                                                <a href="{{ route('changePass') }}">
                                                    Change Password
                                                </a>
                                            @endif
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        {{-- ADMIN SIDEMENU --}}
        @if(!Auth::guest())
        @if(Auth::user()->name == 'admin')
        <!-- Sidebar/menu -->
        <nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:5;" id="mySidebar"><br>
            <div class="w3-container w3-row">
                <div class="w3-col s8 w3-bar">
                    <span>Welcome, <strong>{{Auth::guard('admin')->user()->name}}</strong></span><br>
                </div>
            </div>

            <hr>

            <div class="w3-container">
                <h5>Dashboard</h5>
            </div>
            <div class="w3-bar-block">
                <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
                <a href="{{url('/admin')}}" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-eye fa-fw"></i>  Overview</a>
                <a href="{{url('allUsers')}}" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-users fa-fw"></i>  All Users</a>
                @if(Auth::guard('admin')->user()->job_title != null)
                <a href="{{url('allAdmins')}}" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-users fa-fw"></i>  All Admins</a>
                @endif
                <!--<a href="{{url('allTeams')}}" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-users fa-fw"></i>  All Teams</a>-->
                <a href="{{url('allMessages')}}" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-envelope fa-fw"></i>  All Messages</a>
                <a href="{{url('allMerchants')}}" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa fa-user fa-fw"></i>All Merchants</a>
                <a href="{{route('salaryMethods')}}" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-money fa-fw"></i>  Salaries</a>
                
                <hr>

                <ul class="menu">
                    <li>
                        <a class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa fa-bar-chart fa-fw"></i>Stats </a>
                        <ul class="sub-menu">
                            <li><a href="{{route('VerifiedStats')}}" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa fa-bar-chart fa-fw"></i> Verified Stats </a></li>
                            <li><a href="{{route('DMPStats')}}" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa fa-bar-chart fa-fw"></i> DMP Stats </a></li>
                            <li><a href="{{route('CRBStats')}}" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa fa-bar-chart fa-fw"></i> CRB Stats </a></li>
                            <li><a href="{{route('AmountStats')}}" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa fa-bar-chart fa-fw"></i> Amount Stats </a></li>
                        </ul>
                    </li>
                </ul>
                <a href="{{route('callbacks')}}" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa fa-reply fa-fw"></i>  Callbacks</a>
                <a href="{{route('rnas')}}" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa fa-ban fa-fw"></i>  RNAs</a>
            </div>
        </nav>

        <!-- Overlay effect when opening sidebar on small screens -->
        <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

        <!-- !PAGE CONTENT! -->
        <div class="w3-main" style="margin-left:200px;margin-top:43px;">

        <!-- Header -->
        <header class="w3-container" style="padding-top:22px">
            <h5><b><i class="fa fa-dashboard"></i> My Dashboard</b></h5>
        </header>

        {{-- END OF ADMIN SIDEMENU --}}
        @endif
        @endif

        @yield('content')

        @if(!Auth::guest())
            @if(Auth::user()->name == 'admin')

            @endif
        @endif

        <!--<hr>-->
        <!--<hr>-->

        <div id="error"></div>

        <hr>
        <!-- Footer -->
        <footer class="footer text-center">
            <p>Powered by <a href="http://www.sudoware.pk/" target="_blank" class="sudoware">Sudoware</a></p>
        </footer>
	<script>
	$(document).ready(function() {


        var notificationsWrapper   = $('.dropdown-notifications');
        var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
        var notificationsCountElem = notificationsToggle.find('i[data-count]');
        var notificationsCount     = parseInt(notificationsCountElem.data('count'));
        var notifications          = notificationsWrapper.find('ul.dropdown-menu');

        if (notificationsCount <= 0) { 
            //alert('Came up there') ;
            notificationsWrapper.hide();
        }

        // Enable pusher logging - don't include this in production
        // Pusher.logToConsole = true;
        Pusher.logToConsole = true;
        var pusher = new Pusher('e37376279937535e6751', {
                cluster: 'ap2',
                encrypted: true
        });
        console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa");
        // Subscribe to the channel we specified in our Laravel Event
        var channel = pusher.subscribe('subscribed');

        // Bind a function to a Event (the full Laravel class)
        //App\\Events\\adminNotification
        channel.bind('pusher:subscription_succeeded', function(mem) {
            console.log('subscribed'+mem) ;
        }) ;
        channel.bind('theEvent', function(data) {
            console.log("Data ".$data);
            console.log("Data ".$data1);
        var existingNotifications = notifications.html();
        var avatar = Math.floor(Math.random() * (71 - 20 + 1)) + 20;
        var newNotificationHtml = `
                            <li class="notification active">
                                <div class="media">
                                <div class="media-left">
                                    <div class="media-object">
                                        <h4>`+data.prevStatus+`</h4>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <strong class="notification-title">`+data.customername+`</strong>
                                    <!--p class="notification-desc">Extra description can go here</p-->
                                    <div class="notification-meta">
                                    <small class="timestamp">`+data.date_time+`</small>
                                    </div>
                                </div>
                                </div>
                            </li>
        `;
        notifications.html(newNotificationHtml + existingNotifications);
        notificationsCount += 1;
        notificationsCountElem.attr('data-count', notificationsCount);
        notificationsWrapper.find('.notif-count').text(notificationsCount);
        notificationsWrapper.show();
        $.playSound('/notification/notification.mp3');
        });

        var pusher1 = new Pusher('e37376279937535e6751', {
                cluster: 'ap2',
                encrypted: true
        });

        var channel1 = pusher1.subscribe('getRecentMessages') ;

        channel1.bind('pusher:subscription_succeeded', function(members) {
            console.log('getRecentMessages successful') ;
        });
        channel.bind('pusher:subscription_error', function(status) {
            console.log('getRecentMessages error: '+ status) ;
        });
        channel1.bind('getMessages', function(data) {
            getRecentMessages() ;
        });

@if(!Auth::guest())
@if(Auth::user()->name == 'admin')
    $.ajax({
            type: "GET",
            url:  "{{route('getNotificationAdmin')}}",
            data: null,
            dataType: 'json'
        }).done(function (results) {
            if(results.length > 0)
                {
                    var existingNotifications = null ;
                    $.each(results, function(i, item) {

                        var newNotificationHtml = `
                            <li class="notification active">
                                <div class="media">
                                <div class="media-left">
                                    <div class="media-object">
                                        <h4>`+results[i].prevStatus+`</h4>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <strong class="notification-title">`+results[i].customername+`</strong>
                                    <!--p class="notification-desc">Extra description can go here</p-->
                                    <div class="notification-meta">
                                    <small class="timestamp">`+results[i].date_time+`</small>
                                    </div>
                                </div>
                                </div>
                            </li>
                        `;
                        notifications.html(newNotificationHtml + existingNotifications);
                        notificationsCount += 1;
                        notificationsCountElem.attr('data-count', notificationsCount);
                        notificationsWrapper.find('.notif-count').text(notificationsCount);
                        existingNotifications = newNotificationHtml ;
                    });

                    notificationsWrapper.show();
                    $.playSound('/notification/notification.mp3') ;
                }
        });
@endif
@endif

@if(!Auth::guest())
@if(Auth::user()->name == 'admin')
    var channel2 = pusher.subscribe('isOnline') ;

    channel2.bind('pusher:subscription_succeeded', function(members) {
        console.log('isOnline successful') ;
    }) ;
    channel.bind('pusher:subscription_error', function(status) {
        console.log('isOnline error: '+ status) ;
    });

    channel2.bind('BSDK', function(results) {
        var existingNotifications = notifications.html() ;

            $.each(results, function(i, item) {

                  var newNotificationHtml = `
                      <li class="notification active">
                          <div class="media">
                          <div class="media-left">
                              <div class="media-object">
                                  <h5>`+results[i].name+`</h5>
                              </div>
                          </div>
                          <div class="media-body">
                              <strong class="notification-title">is not online yet.</strong>
                              <!--p class="notification-desc">Extra description can go here</p-->
                              <div class="notification-meta">
                              <small class="timestamp">{{ Carbon\Carbon::now()->format('y/m/d h:i:s') }}</small>
                              </div>
                          </div>
                          </div>
                      </li>
                  `;
                  notifications.html(newNotificationHtml + existingNotifications);
                  notificationsCount += 1;
                  notificationsCountElem.attr('data-count', notificationsCount);
                  notificationsWrapper.find('.notif-count').text(notificationsCount);
                  existingNotifications = newNotificationHtml ;
            });
                
            notificationsWrapper.show();
            $.playSound('/notification/notification.mp3') ;
    }) ;
@endif
@endif

    });
	</script>
        <!-- End page content -->
    </div>

    {{-- scripts only work when in admin views --}}
    @if(!Auth::guest())
    @if(Auth::user()->name == 'admin')
        <div id="confirmModal" class="modal fade text-center">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">Delete Message</div>
                <div class="modal-body">
                    password: <input type="password" id="password">
                </div>
                <div id="error"></div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-danger" id="delete">Delete</button>
                    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                </div>
                </div>
            </div>
        </div>
    @php $url = Route::currentRouteName(); @endphp



    <!-- <script src="//js.pusher.com/3.1/pusher.min.js"></script> -->


    <script>

        $(document).ready(function() {
            // only call when on Overview
            @if($url == 'admin.dashboard')
                getRecentMessages();
            @endif

            var currentCount = {{$messageCount}};
            //sendRequest();
            function sendRequest() {
                window.setTimeout(function () {
                    $.ajax({
                        url: "/getCount",
                        success:
                            function(data) {
                            //console.log(data); insert text of test.php into your div
                            var newCount = data[0];
                            var statusType = data[1];     
                            
                            //console.log(currentCount + ":" + newCount);
                            if((currentCount != newCount) && (statusType != "RoughCall") && (statusType != "CallBack")) {
                               // $.playSound('/notification/notification.mp3');
                               
                                // only call when on Overview
                                @if($url == 'admin.dashboard')
                                getRecentMessages();
                                @endif
                                        
                                    
                                currentCount = newCount;
                            }
                            sendRequest();
                        },
                        error: function() {
                            sendRequest();
                        }
                    });
                }, 4000);
            };

            function getRecentMessages() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{route('getRecentMessages')}}",
                    type: 'GET',
                    success: function(data) {
                        var json = data;

                        //console.log('data length: '+data.length);
                        if(json.length > 0) {
                            var i=0;
                            var sessionID= "{{ Session::token() }}";
                            for(i=0; i<data.length; i++) {
                                if(json[i].prevStatus == 'Submit'){
                                    json[i].prevStatus = 'Verified'  
                                }
                                json[i].action = '<a href="admin/showMessage/'+json[i].id+'" class="btn btn-primary btn-sm">Show</a> <a href="admin/editMessage/'+json[i].id+'" class="btn btn-success btn-sm">Edit</a> </td>';
                                json[i].merchant = ' <form action={{ route("sendMail") }} id="message'+json[i].id+'"  method="post"><input type="hidden" name="messageBody" value='+'\"'+json[i].text+'\"'+'><input type="hidden" name="customerName" value='+'\"'+json[i].customername+'\"'+'><input type="hidden" name="fees" value='+'\"'+json[i].fees+'\"'+'><input type="hidden" name="_token" id="csrf-token" value='+'\"'+sessionID+'\"'+'/><div class="form-group"><select class="form-control" style="width:50%;" name="merchantEmail" id="sel1'+json[i].id +'" ><option value="" selected>No merchant</option>' @foreach ($allMerchants as $merchant) +'<option value='+"{{$merchant->email}}"+'>'+"{{$merchant->name}}"+'</option>'@endforeach +'</select></div></form>';
                                json[i].send = '<button type="submit" form="message'+json[i].id +'"  class="btn btn-success btn-sm">Send</button>'
                                
                            }
                            assignToMessagesColumns(json);
                        }
                        // assignToMessagesColumns(json);
                    }
                });
            };

            function assignToMessagesColumns(data) {
                $('#tableHome').dataTable().fnDestroy();
                var table = $('#tableHome').dataTable({
                      "scrollX": true,
                      "scrollCollapse": true,
                      "scroller":       true,
                      dom: 'Bfrtip',
                      buttons: [
                         {
                           extend: 'pdf',
                           orientation: 'landscape',
                           pageSize: 'A4',
                           customize: function (doc) { doc.defaultStyle.fontSize = 12;  doc.styles.tableHeader.fontSize = 12; },
                           title: 'All Messages',
                           exportOptions: {
                               columns: 'th:not(:nth-last-child(-n+2))'
                           }
                        },
                        {
                            extend: 'csv',
                            title: 'All Messages',
                            exportOptions: {
                                columns: 'th:not(:nth-last-child(-n+2))'
                            }

                        },
                        {
                            extend: 'excel',
                            title: 'All Messages',
                            exportOptions: {
                                columns: 'th:not(:nth-last-child(-n+2))'
                            }
                        }
                    ],
                    "bAutoWidth" : false,
                    "aaData" : data,
                    "columns" : [ {
                        "data" : "id"
                    },{
                        "data" : "username"
                    }, {
                        "data" : "closer"
                    }, {
                        "data" : "customername"
                    },{
                        "data" : "contactNo"
                    }, {
                        "data" : "fees"
                    }, {
                        "data" : "status"
                    }, {
                        "data" : "prevStatus"
                    }, {
                        "data" : "action"
                    },{
                        "data" : "merchant"
                    },{
                        "data" : "send"
                    }],
                    "aLengthMenu": [[50, 75, -1], [50, 75, 100, "All"]],
                    "pageLength": 15,
                    "order": [[ 0, "id" ]],
                   
                     "rowCallback": function( row, data, dataIndex ) {
                      
                      var callBackTime = data[6];
                      //console.log("callBack: " + callBackTime)
                      if(data[6] == "RNA"){
                            $(row).css("background-color", "#f0ad4e");
                            $(row).css("color", "#fff");
                      }
                      if(data[6] == "Approved" || data[6] == "CR Approved" ){
                            $(row).css("background-color", "#5cb85c");
                            $(row).css("color", "#fff");
                      }
                      if(data[6] == "Chargeback"){
                            $(row).css("background-color", "#d9534f");
                            $(row).css("color", "#fff");
                      }
                      
                     },
                 
                });
            }
        });

       
        var deleteMessage;
        $('button[name="delete_message"]').on('click', function(e) {
            deleteMessage = this.id;
            console.log(deleteMessage);
            e.preventDefault();
            $('#confirmModal').modal('show')
            .on('click', '#delete', function(e) {
                console.log(deleteMessage);
                var password = $('#password').val();

                $.ajax({
                    url: "/admin/deleteMessage",
                    type: 'POST',
                    data: { messageId: deleteMessage, password: password, _token: "{{csrf_token()}}" },
                    success: function(data) {
                        console.log(data);
                        var json = JSON.parse(data);
                        if(json.status == 1)
                        $('#tr'+deleteMessage).hide();
                        else
                        $('#error').html('Password Incorrect');
                    }
                });
            });
        });

        // Get the Sidebar
        var mySidebar = document.getElementById("mySidebar");

        // Get the DIV with overlay effect
        var overlayBg = document.getElementById("myOverlay");

        // Toggle between showing and hiding the sidebar, and add overlay effect
        function w3_open() {
            if (mySidebar.style.display === 'block') {
                mySidebar.style.display = 'none';
                overlayBg.style.display = "none";
            } else {
                mySidebar.style.display = 'block';
                overlayBg.style.display = "block";
            }
        }

        // Close the sidebar with the close button
        function w3_close() {
            mySidebar.style.display = "none";
            overlayBg.style.display = "none";
        }

        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};

        $(document).ready(function() {

            $('#tableHomee').DataTable( {
                
                "processing": true,
                scrollX: true,
                "ajax" : {
                    "url" : "http://127.0.0.1:8000/getRecentMessages",
                    dataSrc : '',
                    "success": function(result){
                        console.log(result);
                        },
                    "failure": function(result){
                        console.log('no messages yet');
                        }
                },
                "columns" : [ {
                    "data" : "userName"
                }, {
                    "data" : "closer"
                }, {
                    "data" : "customername"
                }, {
                    "data" : "fee"
                }, {
                    "data" : "status"
                }, {
                    "data" : "action"
                }],
                "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
                "pageLength": 25,
                "order": [[ 0, "id" ]],
                "createdRow": function( row, data, dataIndex ) {
                    alert(data[2]);
                  if ( data[6] == "Approved" || data[6] == "CR Approved") {
                    $(row).css("background-color", "#5cb85c");
                    $(row).css("color", "#fff");
                  }
                  if ( data[6] == "RNA") {
                    $(row).css("background-color", "#f0ad4e");
                    $(row).css("color", "#fff");
                  }
                  if ( data[6] == "Chargeback") {
                    $(row).css("background-color", "#d9534f");
                    $(row).css("color", "#fff");
                  }
                 
                 },

            } );

            $('#userMessageTable').DataTable( {
                "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
                "pageLength": 25,
                "order": [[ 0, "id" ]],

            });

        });



$("ul.menu").find('> li').click(
    function() {
        $(this).find('> ul').slideToggle();


        // $(this).find('> ul').toggle();
    }
);

$("ul.sub-menu").find('> li').click(
    function(e) {
        e.stopPropagation()
        $(this).find('> ul').slideToggle();
   
       // $(this).find('> ul').toggle();
    }
);
    </script>
    
    @endif
    @endif
 </div>
</body>
</html>
