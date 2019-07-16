@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

      @if(session()->has('message'))
      <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          {{ session()->get('message') }}
      </div>
      @endif

     
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-primary">
            <div class="panel-heading" style="height: 46px;">
              <div class="col-md-12">
                <div class="col-md-9">AGENT DASHBOARD</div>
                {{-- <div class="col-md-3">
                  <div class="checkbox checkbox-slider--b-flat">
                    <label>
                        <input type="checkbox" name="maxedout" checked><span>Maxedout</span>
                    </label>
                  </div>
                </div> --}}
              </div>
            </div>

            <div class="panel-body">

                <div class="form-group">
                  <label for="customername">Customer Name :</label>
                  @if(session()->has('customerName'))
                      <input type="text" class="form-control" id="customerName" name="customername" value={{ session()->get('customerName') }} required>
                  @else
                      <input type="text" class="form-control" id="customerName" name="customername" required>
                  @endif
                  
                   @if(session()->has('messageID'))
                      <input type="hidden" class="form-control" name="messageID" value="{{ session()->get('messageID') }}">
                  @else
                      <input type="hidden" class="form-control" name="messageID" value="-1">
                  @endif

                </div>
                
                 
                 <div class="form-group">
                  <label for="contactNo">Contact No :</label>
                  @if(session()->has('contactNo'))
                      <input type="tel" class="form-control" id="contactNo" minlength="10" maxlength="10" name="contactNo" value={{ session()->get('contactNo') }} required>
                  @else
                      <input type="tel" class="form-control" id="contactNo"  minlength="10" maxlength="10" name="contactNo" required>
                  @endif

                </div>

                <div class="form-group">
                  <label for="message">Enter Details:</label>
                  @if(session()->has('text'))
                    <textarea class="form-control" rows="20" id="Message" name="message" required>{{ session()->get('text') }}</textarea>
                  @else
                    <textarea class="form-control" rows="20" id="Message" name="message" required></textarea>
                  @endif

                </div>

                <div class="row" style="text-align:center; margin-bottom:10px;">

                    <div class="col-md-3">
                      <button type="button" name="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#modalCloser">Closer</button>
                    </div>
                    <div class="col-md-3">
                       <button type="button" name="button" id="RoughCallBack_button" class="btn btn-warning btn-block">Rough CallBack</button>
                    </div>
                    <div class="col-md-3">
                       <button type="button" name="button" id="DMP_button" class="btn btn-warning btn-block">DMP</button>
                    </div>
                    <div class="col-md-3">
                       <button type="button" name="button" id="CRB_button" class="btn btn-warning btn-block">CRB</button>
                    </div>
                  </div>

                {{-- <button type='submit' class='btn btn-block btn-primary'>Submit Message</button> --}}
            </div>
        </div>
      </div>
   

   </div>
</div>



<!--MODAL For Closer-->
<div id="modalCloser" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select Closer</h4>
      </div>
      <div class="modal-body">
        <div class="row">

          <div id="selectCloser">
            <div class="form-group col-md-offset-3 col-md-6">
             <label for="closer">Closer Name:</label>
               <select class="form-control" id="closer" name="closer">
                 <option selected disabled value="Choose Closer....">Choose Closer....</option>
                 @foreach($closers as $closer)
                   <option value="{{ $closer->name }}">{{ $closer->name }}</option>
                 @endforeach
             </select>
            </div>

          </div>

          <div id="loginFormDiv">
            <form id="closerLogin" class="form-horizontal" role="form" method="POST" action="{{ route('agentMessageSave') }}">
              <div class="row">
                  {{ csrf_field() }}

                  <input type="hidden" name="message" id="message" value="">
                  <input type="hidden" name="customername" id="customername" value="">
                  <input type="hidden" name="agentID" value="{{$agentID}}">
                  @if(session()->has('messageID'))
                      <input type="hidden" class="form-control" name="messageID" value={{ session()->get('messageID') }}>
                  @else
                      <input type="hidden" class="form-control" name="messageID" value="-1">
                  @endif
                  <input type="hidden" name="contactNo" id="contactno" value="">    
                  
                  <div class="form-group">
                    <label class="col-md-4 control-label" for="usr">Name:</label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" id="closername" name="closername" readonly>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-4 control-label" for="pwd">Password:</label>
                    <div class="col-md-6">
                      <input type="password" class="form-control" name="password" id="pwd">
                    </div>
                  </div>

                  <div class="form-group">
                      <div class="col-md-8 col-md-offset-2">
                          <button type="submit" class="btn btn-block btn-success">
                              Login
                          </button>
                      </div>
                  </div>

              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!--End Of MODAL-->

<!--MODAL For RoughCallBack-->
<div id="modealRoughCallBack" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Rough CallBack</h4>
      </div>
      <div class="modal-body">
          <form class="" action="{{route('agentRoughCallBack')}}" method="post">
            <input type="hidden" name="message" id="Rmessage" value="">
            <input type="hidden" name="customername" id="Rcustomername" value="">
            <input type="hidden" name="agentID" value="{{$agentID}}">
            @if(session()->has('messageID'))
                      <input type="hidden" class="form-control" name="messageID" value={{ session()->get('messageID') }}>
                  @else
                      <input type="hidden" class="form-control" name="messageID" value="-1">
                  @endif
            <input type="hidden" name="contactNo" id="RcontactNo" value="">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-md-offset-3 col-md-6">
                <label for=""></label>
                  <input style="border-radius: 5px; font-size: 20px; border:1px solid #28a745;" class="form-control" type="text" name="Date" value="<?php echo date('Y-m-d h:i:sa'); ?>" placeholder="Enter Date..." />
                  <p class="help-block" style="font-weight:bold;">From Date</p>
              </div>
            </div>
              <div class="row">
                <div class="col-md-offset-4 col-md-5">
                  <button type="submit" class="btn btn-success btn-lg" name="button">Submit</button>
                </div>
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!--End Of MODAL-->

<!--MODAL For DMP-->
<div id="modalDMP" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">DMP</h4>
      </div>
      <div class="modal-body">
          <form class="" action="{{route('agentSubmitDMP')}}" method="post">
              {{ csrf_field() }}
              <div class="row">
                <input type="hidden" name="message" id="Dmessage" value="">
                <input type="hidden" name="customername" id="Dcustomername" value="">
                <input type="hidden" name="contactNo" id="DcontactNo" value="">
                @if(session()->has('messageID'))
                      <input type="hidden" class="form-control" name="messageID" value={{ session()->get('messageID') }}>
                  @else
                      <input type="hidden" class="form-control" name="messageID" value="-1">
                  @endif
                <input type="hidden" name="agentID" value="{{$agentID}}">
              </div>
              <div class="form-group col-md-offset-3 col-md-6">

               <input type="hidden" name="status" value="DMP">
                  <h3>Are you sure you want to submit DMP ?</h3>
              </div>
              <button type="submit" class="btn btn-success btn-block" name="button">Submit</button>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!--End Of MODAL-->


<!--MODAL For CR back-->
<div id="modalCRB" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">CRB</h4>
      </div>
      <div class="modal-body">
          <form class="" action="{{route('agentSubmitCRB')}}" method="post">
              {{ csrf_field() }}
              <div class="row">
                <input type="hidden" name="message" id="CRBmessage" value="">
                <input type="hidden" name="customername" id="CRBcustomername" value="">
                <input type="hidden" name="contactNo" id="CRBcontactNo" value="">
                @if(session()->has('messageID'))
                      <input type="hidden" class="form-control" name="messageID" value={{ session()->get('messageID') }}>
                @else
                      <input type="hidden" class="form-control" name="messageID" value="-1">
                @endif
                <input type="hidden" name="agentID" value="{{$agentID}}">
              </div>
              <div class="form-group col-md-offset-3 col-md-5">
                <label for="fee">Fee :</label>
                <input class="form-control" type="number" id="fee" name="fees" value="" required>
              </div>
              <div class="form-group col-md-offset-3 col-md-6"> 
               <input type="hidden" name="status" value="CRB">
                  <h3>Are you sure you want to submit Credit back ?</h3>
              </div>
              <button type="submit" class="btn btn-success btn-block" name="button">Submit</button>
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
  
  $(document).ready(function(){


    $('#selectCloser').show();
    $('#loginFormDiv').hide();
    $('#closer').val('Choose Closer....');
  });

  $("#modalCloser").on("hidden.bs.modal", function () {
    $('#modalCloser .modal-title').text('Select Closer');
    $('#selectCloser').show();
    $('#loginFormDiv').hide();
    $('#closer').val('Choose Closer....');
  });

  $('#closer').on('change', function() {
    if(this.value != 'Choose Closer....'){
      $('#modalCloser .modal-title').text('Login Closer');
      $('#loginFormDiv').show();
      $('#selectCloser').hide();
      $('#closername').val(this.value);
      $('#customername').val($('#customerName').val());
      $('#message').val($('#Message').val());
      $('#contactno').val($('#contactNo').val());

    }
    else{
      $('#modalCloser .modal-title').text('Select Closer');
      $('#selectCloser').show();
      $('#loginFormDiv').hide();
    }
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
    //   startDate: moment().startOf('hour'),
      minDate:today,
      timePicker: true,
      singleDatePicker: true,
      showDropdowns: false,
      locale: {
        format: 'YYYY/M/DD hh:mm:ss'
     }
    });
  });



  $('#closerLogin').submit(function(e) {
  
    e.preventDefault();
    if($('#message').val()== '' || $('#customername').val()== ''  ||  $('#contactNo').val()== '' || $('#contactNo').val().length<10){

       if($('#customerName').val()== ''){
        alert('Please Enter Customer Name Feild...')
      }
      if($('#Message').val()== ''){
        alert('Please Enter Message Feild...')
      }
      if($('#contactNo').val()== ''){
        alert('Please Enter Contact No Feild...')
      }
      if($('#contactNo').val().length<10){
        alert('Please Enter Correct Contact No Format...')
      }

    }
    else{
      this.submit();
    }

  });

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

  $('#DcontactNo').val($('#contactNo').val());

  $('#DMP_button').click(function(){
    $('#Dcustomername').val($('#customerName').val());
    $('#Dmessage').val($('#Message').val());
    $('#DcontactNo').val($('#contactNo').val());
    
    if($('#Message').val()== '' || $('#customerName').val()== '' ||  $('#contactNo').val()== ''){

      if($('#customerName').val()== ''){
        alert('Please Enter Customer Name Feild...')
      }
      if($('#Message').val()== ''){
        alert('Please Enter Message Feild...')
      }
      if($('#contactNo').val()== ''){
        alert('Please Enter Contact No Feild...')
      }


    }
    else{
      $('#modalDMP').modal();
    }
  });

  $('#CRB_button').click(function(){
    $('#CRBcustomername').val($('#customerName').val());
    $('#CRBmessage').val($('#Message').val());
    $('#CRBcontactNo').val($('#contactNo').val());
    
    if($('#Message').val()== '' || $('#customerName').val()== '' ||  $('#contactNo').val()== ''){

      if($('#customerName').val()== ''){
        alert('Please Enter Customer Name Feild...')
      }
      if($('#Message').val()== ''){
        alert('Please Enter Message Feild...')
      }
      if($('#contactNo').val()== ''){
        alert('Please Enter Contact No Feild...')
      }


    }
    else{
      $('#modalCRB').modal();
    }
  });

  $('#RoughCallBack_button').click(function(){
    $('#Rcustomername').val($('#customerName').val());
    $('#Rmessage').val($('#Message').val());
    $('#RcontactNo').val($('#contactNo').val());
    
    if($('#Message').val()== '' || $('#customerName').val()== '' ||  $('#contactNo').val()== ''){

      if($('#customerName').val()== ''){
        alert('Please Enter Customer Name Feild...')
      }
      if($('#Message').val()== ''){
        alert('Please Enter Message Feild...')
      }
      if($('#contactNo').val()== ''){
        alert('Please Enter Contact No Feild...')
      }
    }
    else{
      $('#modealRoughCallBack').modal();
    }
  
  });
</script>

<script>

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
