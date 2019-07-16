@extends('layouts.app')

@section('content')

<div class="container-fluid">
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
  
  <h2>Show Message</h2>
  <hr>
  <div>
      <!--<div class="col-md-2 text-center">-->
      <!--  <div class="messageHead"> ID </div>-->
      <!--  <div class="messageContent"><h3>{{ $message->id }}</h3></div>-->
      <!--</div>-->
       <div class="col-md-2 text-center">
        <div class="messageHead"> Contact </div>
        <div class="messageContent"><h3>{{ $message->contactNo }}</h3></div>
      </div>
      <div class="col-md-5 text-center">
        <div class="messageHead"> Agent </div>
        <div class="messageContent"><h3>{{ $message->username }}</h3></div>
      </div>
      <div class="col-md-5 text-center">
        <div class="messageHead"> Closer</div>
        <div class="messageContent"><h3>{{ $message->closer }}</h3></div>
      </div>
  </div>
 
  <div>
      <div class="col-md-2 text-center">
        <div class="messageHead"> Fee</div>
        <div class="messageContent"><h3>{{ $message->fees }}</h3></div>
      </div>
      <div class="col-md-5 text-center">
        <div class="messageHead"> Customer Name</div>
        <div class="messageContent"><h3>{{ $message->customername }}</h3></div>
      </div>
      <div class="col-md-5 text-center">
        <div class="messageHead"> Status</div>
        <div class="messageContent"><h3>{{ $message->status }}</h3></div>
      </div>
  </div>
  <div class="textHeader text-center col-md-12"><h3>Text</h3> </div>
 
  <div id="closerText" class="text  col-md-12"></div>
  <button id="agentDetails" type="button" class="btn btn-success btn-md" style="margin-top:10px">Show Agent Details</button>        
  
  <div id="agentDetailDiv" style="display:none; margin-top:10px;">
     <div class="textHeader text-center col-md-12"><h3>Agent Text</h3> </div>
    
     <div id="agentText" class="text  col-md-12"></div>
  </div>

<input type="hidden" id="bothText" value="{{$message->text}}">
<!-- End page content -->
</div>

<script>
 $(document).ready(function(){
    var text = $('#bothText').val();
    var textSplit = text.split("agentMessageEnd");
     $('#closerText').text(textSplit[0]);
     $('#agentText').text(textSplit[1]);
      
      if(typeof textSplit[1] == 'undefined'){
         $('#agentText').text(textSplit[0]);
     }
    
 });

 $("#agentDetails").click(function(){
    $('#agentDetailDiv').toggle();
 });
</script>
@endsection
