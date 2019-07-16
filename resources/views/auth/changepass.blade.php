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
    @if(session()->has('message'))
    <div class="alert alert-success">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session()->get('message') }}
    </div>
  @endif

  @if(session()->has('warning'))
    <div class="alert alert-danger">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session()->get('warning') }}
    </div>
  @endif

<!-- Header -->
<header class="w3-container" style="padding-top:22px">
  <h5><b><i class="fa fa-dashboard"></i> My Dashboard</b></h5>
</header>

<div class="col-md-8 col-md-offset-2">
    <hr>
    <div class="panel panel-primary">
        <div class="panel-heading text-center">UPDATE PASSWORD</div>

        <div class="panel-body">
            <form method="POST" action="{{ route('updatePass') }}">
                <div class="form-group">
                    <label for="oldpass">Old Password :</label>
                    <input type="password" class="form-control" id="oldpass" name="oldpass"required>
                </div>
                <div class="form-group">
                    <label for="newpass">New Password :</label>
                    <input type="password" class="form-control" id="newpass" name="newpass" required>
                </div>
                <div class="form-group">
                    <label for="confirmpass">Confirm New Password :</label>
                    <input type="password" class="form-control" id="confirmpass" name="confirmpass" required>
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