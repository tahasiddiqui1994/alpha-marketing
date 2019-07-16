@extends('layouts.app')

@section('content')

<div class="w3-row-padding w3-margin-bottom">
   @if(session()->has('message'))
      <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          {{ session()->get('message') }}
      </div>
   @endif
  <div class="w3-quarter">
    <div class="w3-container w3-container-custom w3-red w3-padding-16">
      <div class="w3-left"><i class="fa fa-comment w3-xxxlarge"></i></div>
      <div class="w3-right">
        <h3>{{$recentMessageCount}}</h3>
      </div>
      <div class="w3-clear"></div>
      <h4>Recent Messages</h4>
    </div>
  </div>


  <div class="w3-quarter">
    <div class="w3-container w3-container-custom w3-orange w3-text-white w3-padding-16">
      <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
      <div class="w3-right">
        <h3>{{$userCount}}</h3>
      </div>
      <div class="w3-clear"></div>
      <h4>Total Users</h4>
    </div>
  </div>
  
  <div class="w3-quarter">
    <div class="w3-container w3-container-custom w3-blue w3-text-white w3-padding-16">
      <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
      <div class="w3-right">
        <h3>{{$userCurrent}}</h3>
      </div>
      <div class="w3-clear"></div>
      <h4>Current Total Users</h4>
    </div>
  </div>
  
  <div class="w3-quarter">
    <div class="w3-container w3-container-custom w3-green w3-text-white w3-padding-16">
      <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
      <div class="w3-right">
        <h4>Agent:{{$agents_count}}/Closers:{{$closers_count}}</h4>
      </div>
      <div class="w3-clear"></div>
      <h4>Current Active Users</h4>
    </div>
  </div>
  
</div>
<div style="padding:10px 40px;" class="row">
  <h3>Todays Stats:</h3>
  <div class="col-md-3 col-sm-3 dailyStatDiv">
    <p>Number Of Transfer:<b>{{$noOfTransfer}}</b></p>
    <p>Number Of Approved:<b>{{$noOfApproved}}</b></p>    
  </div>
  <div class="col-md-3 col-md-offset-1 col-sm-offset-1 col-sm-3 dailyStatDiv">
    <p>Number Of DMP:<b>{{$noOfDMP}}</b></p>
    <p>Number Of Agent CR:<b>{{$noOfCRAgent}}</b></p>     
  </div>
  <div class="col-md-3 col-md-offset-1 col-sm-offset-1 col-sm-3 dailyStatDiv">
    <p>Number Of Closer CR:<b>{{$noOfCRCloser}}</b></p> 
    <p>Number Of Verfifed:<b>{{$noOfVerfied}}</b></p>     
  </div>
</div>

<div class="w3-container text-center" id="RecentMessage" style="white-space:nowrap">
  <h5><b>Recent Messages: (Today)</b></h5>

  <table id="tableHome" class="table">

    <thead>
        <tr>
            <th>ID</th>
            <th>Agent</th>
            <th>Closer</th>
            <th>Customer</th>
            <th>Contact No</th>
            <th>Fee</th>
            <th>Status</th>
            <th>Prev-Status</th>
            <th>Action</th>
            <th >Select Merchant</th>
            <th>Send Email</th>
            
        </tr>
    </thead>
    <!--<tfoot>-->
    <!--    <tr>-->
    <!--        <th>Username</th>-->
    <!--        <th>Closer</th>-->
    <!--        <th>Customername</th>-->
    <!--        <th>Contact No</th>-->
    <!--        <th>Fee</th>-->
    <!--        <th>Status</th>-->
    <!--        <th>Action Type</th>-->
    <!--        <th>Select Merchant</th>-->
    <!--        <th>Send Email</th>-->
    <!--    </tr>-->
    <!--</tfoot>-->
  </table>

</div>
<br>

<!-- End page content -->
</div>

<script>
    
  
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
</script>

@endsection
