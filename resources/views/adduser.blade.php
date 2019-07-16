@extends('layouts.app')

@section('content')
      <!-- Sidebar/menu -->
<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
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
  <a href="{{url('allMessages')}}" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-envelope fa-fw"></i>  All Messages</a>

</div>
</nav>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

<!-- Header -->
<header class="w3-container" style="padding-top:22px">
  <h5><b><i class="fa fa-dashboard"></i> My Dashboard</b></h5>
</header>

<div class="col-md-8 col-md-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading text-center">ADD USER</div>

        <div class="panel-body">
            <form method="POST" action="{{ route('insertUser') }}">
                <div class="form-group">
                    <label for="username">Username :</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password :</label>
                    <input type="password" class="form-control" id="password" required>
                </div>
                {{ csrf_field() }}
                <input type="submit" value="Submit" class="btn btn-primary">
            </form>
        </div>
    
    </div>
</div>    



<!-- Footer -->
<footer class="w3-container w3-padding-16 w3-light-grey">
  <p>Powered by <a href="http://www.sudoware.pk/" target="_blank">Sudoware</a></p>
</footer>

<!-- End page content -->
</div>
@endsection