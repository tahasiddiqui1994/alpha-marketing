@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

      @if(session()->has('sessionMessage'))
      <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          {{ session()->get('sessionMessage') }}
      </div>
      @endif

      @if(!empty($sessionMessage))
        <div class="alert alert-danger">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ $sessionMessage }}
        </div>
      @endif

      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-primary">
            <div class="panel-heading">CLOSER</div>

            <div id="time" style="color:white;"></div>

            <div class="panel-body">
              <form method="POST" action="{{ route('CloserMessageSave') }}">

                <div class="form-group">
                  <label for="customername">Customer Name :</label>

                  <input readonly type="text" class="form-control" id="customername" name="customername" value="{{$message->customername}}" required>
                    <input type="hidden" class="form-control" id="messageID" name="messageID" value="{{ ($message->id != '')? $message->id: '-1' }}" >
                    
                </div>
                
                <div class="form-group">
                  <label for="contactNo">Contact No :</label>
                  <input type="tel" class="form-control" id="contactNo" pattern="[0-9]{10}"  maxlength="10" name="contactNo" value="{{$message->contactNo}}" required>
                </div>
    
                
                <div class="form-group">
                  <label for="message">Agent Details:</label>
                    <input type="hidden" id="agentmessage" name="agentmessage" value="{{ $message->text }}">
                    <p>{!! nl2br($message->text) !!}</p>
                </div>
                
                <button type="button" id="appendButton" class="btn btn-success btn-block">Append Agent Details</button>
                
                
                <div class="form-group">
                  <label for="message">Enter Details:</label>
                  <textarea class="form-control" rows="20" id="message" name="message"  required></textarea>
                </div>

                {{ csrf_field() }}

                <div class="row" style="text-align:center; margin-bottom:10px;">

                    <div class="form-group col-md-4" style="margin-top:20px;">
                      <button type="button" name="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#modalAgent">Back To Agent</button>
                    </div>

                    <div class="form-group col-md-4">
                     <label for="status">Status:</label>
                     <select class="form-control" id="status" name="status">
                         <option value="Submit">Verified</option>
                         <option value="Dropped">Dropped</option>
                         <option value="CallBack">CallBack</option>
                         <option value="CRB">Credit Back</option>
                     </select>
                   </div>

                   <div class="form-group col-md-4">
                     <label for="fee">Fee :</label>
                     <input class="form-control" type="number" id="fee" name="fees" value="" required>
                   </div>

                   <div id="dateDiv" class="col-md-offset-4 col-md-4" style="display:none;">
                     <label for="">Date/Time:</label>
                       <input style="border-radius: 5px; font-size: 20px; border:1px solid #28a745;" class="form-control" type="text" name="Date" value="<?php echo date('Y-m-d h:i:sa'); ?>" placeholder="Enter Date..." />
                       <p class="help-block" style="font-weight:bold;">From Date</p>
                   </div>

                </div>

                <input type="hidden" name="agentID" value="{{$message->userID}}">
                <input type="hidden" name="closername" value="{{$message->closer}}">
                <input type="hidden" name="transferTime" value="{{$message->transferTime}}">
               
                <button type='submit' class='btn btn-block btn-primary'>Submit Message</button>
              </form>
            </div>

        </div>
      </div>

    </div>
</div>


<!--MODAL For UserLogin-->
<div id="modalAgent" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Agent Login</h4>
      </div>
      <div class="modal-body">
          <form action="{{route('agentTransfer')}}" method="post">
              {{ csrf_field() }}

                <input type="hidden" name="message" id="message" value="{{$message->text}}">
                <input type="hidden" name="customername" id="customername" value="{{$message->customername}}">
                <input type="hidden" name="agentID" value="{{$message->userID}}">
                @if(session()->has('messageID'))
                      <input type="hidden" class="form-control" name="messageID" value="{{ session()->get('messageID') }}">
                  @elseif($message->id != '')
                  <input type="hidden" name="messageID" value="{{ $message->id }}">
                  @else    
                      <input type="hidden" class="form-control" name="messageID" value="-1">
                  @endif
                <input type="hidden" name="closername" value="{{$message->closername}}">
                <input type="hidden" name="contactNo" value="{{$message->contactNo}}">    
              
              <input type="hidden" name="previousRecord" value="{{$message}}">
              <div style="margin-bottom:15px;">
                <div class="form-group">
                  <label class="col-md-4 control-label" for="usr">Agent Name:</label>
                  <div class="col-md-6">

                    <input  class="form-control" id="name" name="name" value="{{$message->userName}}" readonly>

                  </div>
                </div>
              </div>

              <div style="margin-bottom:15px;">
              <div class="form-group" style="margin-top:10px;">
                <label class="col-md-4 control-label" for="usr">Password:</label>
                <div class="col-md-6">
                  <input type="password" class="form-control" id="password" name="password" value="">
                </div>
              </div>
            </div>
              <button style="margin-top:10px;" type="submit" class="btn btn-success btn-block" name="button">Submit</button>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!--End Of MODAL-->



<script type="text/javascript">
   $('#contactNo').keyup(function(){
    var VAL = this.value;
    var contactNumber = new RegExp('^[0-9]{10}$');
    if (contactNumber.test(VAL)) {
      $(this).css("color","green");
    }
    else{
      $(this).css("color","red");
    }

  });

    $("#appendButton").click(function(){
        var userText = $('#agentmessage').val();
        var agentText = $('#message').val();
        $('#message').val('');
        $('#message').val(userText+'\n\r'+agentText);
    });    

  $(function() {
    var today = new Date(); 
    var dd = today.getDate(); 
    var mm = today.getMonth()+1; //January is 0! 
    var yyyy = today.getFullYear(); 
    if(dd<10){ dd='0'+dd } 
    if(mm<10){ mm='0'+mm } 
    var today = yyyy+'/'+mm+'/'+dd; 
  
    $('input[name="Date"]').daterangepicker({
      startDate: moment().startOf('hour'),
      minDate:today,
      timePicker: true,
      singleDatePicker: true,
      showDropdowns: false,
      drops:'up',
      locale: {
       format: 'YYYY/M/DD hh:mm A'
     }
    });
  });
  $('#status').on('change', function() {
    if(this.value == 'CallBack'){
      $('#dateDiv').show();
    }else{
      $('#dateDiv').hide();
    }
  });

</script>

<!-- Back -->

<script type="text/javascript">



(function (global) { 

if(typeof (global) === "undefined") {
    throw new Error("window is undefined");
}

var _hash = "!";
var noBackPlease = function () {
    global.location.href += "#";

    // making sure we have the fruit available for juice (^__^)
    global.setTimeout(function () {
        global.location.href += "!";
    }, 50);
};

global.onhashchange = function () {
    if (global.location.hash !== _hash) {
        global.location.hash = _hash;
    }
};

global.onload = function () {            
    noBackPlease();

    // disables backspace on page except on input fields and textarea..
    document.body.onkeydown = function (e) {
        var elm = e.target.nodeName.toLowerCase();
        if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
            e.preventDefault();
        }
        // stopping event bubbling up the DOM tree..
        e.stopPropagation();
    };          
}

})(window);

</script>


@endsection
