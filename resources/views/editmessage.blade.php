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

      <div class="col-md-8 col-md-offset-1">
        <div class="panel panel-primary">
            <div class="panel-heading">ADMIN Dashboard</div>

            <div class="panel-body">
              <form method="post" action="{{route('updateMessage')}}">

                <div class="form-group col-md-8">
                  <label for="customername">Customer Name:</label>
                  <input type="text" class="form-control" id="customername" name="customername" value="{{$message->customername}}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="message">Agent Name:</label>
                    <p><b>{{$message->userName}}</b></p>
                </div>

                <div class="form-group">
                  <label for="message">Details:</label>
                  <textarea  class="form-control" rows="20" id="message" name="message" required></textarea>
                  {{ csrf_field() }}
                </div>
  
                <div class="form-group">
                    <button id="agentDetails" type="button" class="btn btn-success btn-md" style="margin-top:10px">Show Agent Details</button>
                </div>
                
                <div id="agentDetailDiv" style="display:none; margin-top:10px;" class="form-group">
                  <label for="message">Agent Details:</label>
                  <textarea  class="form-control" rows="10" id="agentText" readonly></textarea>
                </div>
                <div class="form-group col-md-3">
                  <label for="status">Status:</label>
                  <select class="form-control" id="status" name="status">
                      <option @if( strcmp($message->status, 'none') == 0) {{ 'selected' }} @endif value="none">Pending</option>
                      <option @if( strcmp($message->status, 'Submit') == 0) {{ 'selected' }} @endif value="Submit">Verified</option>
                      <option @if( strcmp($message->status, 'RoughCall') == 0) {{ 'selected' }} {{'disabled'}} @else {{'disabled'}} @endif value="RoughCall">RoughCall</option>
                      <option @if( strcmp($message->status, 'CallBack') == 0) {{ 'selected' }} @endif value="CallBack">CallBack</option>
                      <option @if( strcmp($message->status, 'RNA') == 0) {{ 'selected' }} @endif value="RNA">RNA</option>
                      <option @if( strcmp($message->status, 'Still RNA') == 0) {{ 'selected' }} {{ 'disabled' }} @endif {{'disabled'}} value="Still RNA">Still RNA</option>
                      <option @if( strcmp($message->status, 'Approved') == 0) {{ 'selected' }} @endif value="Approved">Approved</option>
                      <option @if( strcmp($message->status, 'Decline') == 0) {{ 'selected' }} @endif value="Decline">Decline</option>
                      <option @if( strcmp($message->status, 'DMP') == 0) {{ 'selected' }} {{'disabled'}} @else {{'disabled'}} @endif value="DMP">DMP</option>
                      <option @if( strcmp($message->status, 'BankAuth') == 0) {{ 'selected' }}  @endif  value="BankAuth">Bank Auth</option>
                      <option @if( strcmp($message->status, 'Still BankAuth') == 0) {{ 'selected' }}  @endif {{'disabled'}} value="Still BankAuth">Still Bank Auth</option>
                      <option @if( strcmp($message->status, 'BankAuth Removed') == 0) {{ 'selected' }} @endif {{'disabled'}} value="BankAuth Removed">Bank Auth Removed</option>
                      <option @if( strcmp($message->status, 'Connected') == 0) {{ 'selected' }} {{ 'disabled' }} @endif value="Connected">Connected</option>
                      <option @if( strcmp($message->status, 'BankAuth CallBack') == 0) {{ 'selected' }} {{ 'disabled' }} @endif value="BankAuth CallBack">Bank Auth CallBack</option>
                      <option @if( strcmp($message->status, 'CRB') == 0) {{ 'selected' }} {{'disabled'}} @else {{'disabled'}} @endif value="CRB">CreditBack Charge</option>            
                  </select><br>
                  <label for="prevStatus">Previous Status:</label>
                  <select class="form-control" id="prevStatus" name="status" readonly>
                      <option @if( strcmp($message->prevStatus, 'Submit') == 0) {{ 'selected' }} @endif value="Submit">Verified</option>
                      <option @if( strcmp($message->prevStatus, 'RoughCall') == 0) {{ 'selected' }} {{'disabled'}} @else {{'disabled'}} @endif value="RoughCall">RoughCall</option>
                      <option @if( strcmp($message->prevStatus, 'CallBack') == 0) {{ 'selected' }} @endif value="CallBack">CallBack</option>
                      <option @if( strcmp($message->prevStatus, 'RNA') == 0) {{ 'selected' }} @endif value="RNA">RNA</option>
                      <option @if( strcmp($message->prevStatus, 'Still RNA') == 0) {{ 'selected' }} {{ 'disabled' }} @endif {{'disabled'}} value="Still RNA">Still RNA</option>
                      <option @if( strcmp($message->prevStatus, 'Approved') == 0) {{ 'selected' }} @endif value="Approved">Approved</option>
                      <option @if( strcmp($message->prevStatus, 'Decline') == 0) {{ 'selected' }} @endif value="Decline">Decline</option>
                      <option @if( strcmp($message->prevStatus, 'DMP') == 0) {{ 'selected' }} {{'disabled'}} @else {{'disabled'}} @endif value="DMP">DMP</option>
                      <option @if( strcmp($message->prevStatus, 'BankAuth') == 0) {{ 'selected' }}  @endif value="BankAuth">Bank Auth</option>
                      <option @if( strcmp($message->prevStatus, 'Still BankAuth') == 0) {{ 'selected' }}  @endif {{'disabled'}} value="Still BankAuth">Still Bank Auth</option>
                      <option @if( strcmp($message->prevStatus, 'BankAuth Removed') == 0) {{ 'selected' }} @endif {{'disabled'}} value="BankAuth Removed">Bank Auth Removed</option>
                      <option @if( strcmp($message->prevStatus, 'Connected') == 0) {{ 'selected' }} {{ 'disabled' }} @endif value="Connected">Connected</option>
                      <option @if( strcmp($message->prevStatus, 'BankAuth CallBack') == 0) {{ 'selected' }} {{ 'disabled' }} @endif value="BankAuth CallBack">Bank Auth CallBack</option>
                      <option @if( strcmp($message->prevStatus, 'CRB') == 0) {{ 'selected' }} {{'disabled'}} @else {{'disabled'}} @endif value="CRB">CreditBack Charge</option>        
                  </select>
                  
                  <label for="prevStatus">Fees:</label>
                  <input type="hidden" class="form-control" id="fee" name="fee" value="{{$message->fees}}"  @if( strcmp($message->status, 'Approved') == 0) {{'readonly '}} @endif>
                  <p><b>{{$message->fees}}</b></p>
              </div>
                <div class="form-group col-md-3">
                  <label for="closer">Closer :</label>
                    <select class="form-control" id="closer" name="closer"  @if( strcmp($message->status, 'Approved') == 0) {{'readonly '}} @endif>
                      @foreach($closers as $closer)
                        <option  @if( strcmp($message->closer, $closer->name) == 0) {{ 'selected' }}@endif value ="{{ $closer->name }}" >{{ $closer->name }}</option>
                      @endforeach
                  </select>
                </div>

                <div class="form-group col-md-3">
                  <label for="fee">Final Fees:</label>
                  <input type="number" class="form-control" id="finalFee" name="finalFee" value="{{$message->finalFee}}"  @if( strcmp($message->status, 'Approved') == 0) {{'readonly '}} @endif>
                </div>
                
                <div class="form-group col-md-3">
                  <label for="contactNo">Contact No:</label>
                  <input type="text" class="form-control" id="contactNo" name="contactNo" value="{{$message->contactNo}}" required  @if( strcmp($message->status, 'Approved') == 0) {{'readonly '}} @endif>
                </div>
              


                <input type="hidden" name="id" value="{{$message->id}}">
                
                <div class="col-md-offset-4 form-group" id="noteDiv">
                  <label for="">Note : </label>
                  @if($message->note != null)
                    <textarea class="form-control" name="note" rows="5" cols="50">{{$message->note}}</textarea>
                  @else
                    <textarea class="form-control" name="note" rows="5" cols="50"></textarea>
                  @endif
                </div>

                <div  id="callback" class="form-group col-md-4" style="padding-top: 6px; padding-bottom: 6px;">
                  
                    <div class="form-group">
                        <label for="status">Allow To:</label>
                        <select class="form-control" id="allow" name="allowcallback"  @if( strcmp($message->status, 'Approved') == 0) {{'readonly'}} @endif>
                            <option @if( strcmp($message->allowcallback, 'none') == 0) {{ 'selected' }} @endif value="none">None</option>
                            <option @if( strcmp($message->allowcallback, 'agent') == 0) {{ 'selected' }} @endif value="agent">Agent Only</option>
                            <option @if( strcmp($message->allowcallback, 'closer') == 0) {{ 'selected' }} @endif value="closer">Closer Only</option>
                            <option @if( strcmp($message->allowcallback, 'agent/closer') == 0) {{ 'selected' }} @endif value="agent/closer">Agent/Closer Both</option>
                        </select>
                    </div>
                  
                </div>

                 
                 <div id="dateDiv" class="form-group col-md-5" style="display:none; padding-top: 6px; padding-bottom: 6px;">
                     <label for="">Date/Time:</label>
                       <input style="border-radius: 5px; font-size: 20px; border:1px solid #28a745;" class="form-control" type="text" name="Date" value="<?php echo date('Y-m-d h:i:sa'); ?>" placeholder="Enter Date..." />
                 </div>
                 
                 <div class="form-group col-md-3" id="callbacktype" style="padding-top: 6px; padding-bottom: 6px; ">
                    <div class="form-group">
                        <label id="notificationto" for="status">CallBack Type:</label>
                        <select class="form-control" id="callbackType" name="callbackType">
                            <option  value="none">None</option>
                            <option  value="agent">Agent Only</option>
                            <option  value="closer">Closer Only</option>
                            <option  value="agent/closer">Agent/Closer Both</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-md-8" style="padding-top: 6px; padding-bottom: 6px; ">
                  <div class="" id="refundCheck" style="display:none;"> 
                    <div class="radio-inline">
                      <label><input type="radio"  @if( strcmp($message->returnType, 'refund') == 0) {{ 'checked' }} @endif value="refund" class="radioBtnClass" name="returnType">Refund</label>
                      </div>
                    <div class="radio-inline">
                      <label><input type="radio" @if( strcmp($message->returnType, 'chargeback') == 0) {{ 'checked' }} @endif value="chargeback" class="radioBtnClass" name="returnType">ChargeBack</label>
                    </div>
                    <div class="radio-inline">
                        <label><input type="radio" @if( strcmp($message->returnType, 'none') == 0 || is_null($message->returnType)) {{ 'checked' }} @endif value="none" class="radioBtnClass" name="returnType">None</label>
                      </div>
                  </div>  
                </div>

                <div class="form-group refundFee col-md-4" style="display:none;">
                  <label for="fee">Refund/ChargeBack Fees:</label>
                   
                  @if(!is_null($message->returnAmount))
                   <input type="number" class="form-control" id="refundback" name="returnAmount" value="{{$message->returnAmount}}" required  @if( strcmp($message->status, 'Approved') == 0 && (strcmp($message->returnType, 'chargeback') == 0 || strcmp($message->returnType, 'refund') == 0 )) {{'readonly '}} @endif> 
                   @else
                   <input type="number" class="form-control" id="refundback" name="returnAmount" value="" required  @if( strcmp($message->status, 'Approved') == 0) {{'disabled'}} @endif> 
                   @endif 
                  
                </div>
                
                <button type="submit" class="btn btn-success col-md-12" id="submit">Update Message</button>
                    
                 <a class="btn btn-success col-md-offset-9 col-md-3" data-id="{{$message->id}}" style="margin-top:10px" id="downloadtext"  href="" download="<?php echo date('Y-m-d');?> {{$message->customername}}.txt">Download Message</a>


              </form>
            </div>
        </div>
      </div>

    </div>
    
    <input type="hidden" id="bothText" value="{{$message->text}}">
</div>

<div class="container-fluid">

 <!--  Agents -->
 <div class="panel panel-primary">
    <div class="panel-heading">Agents</div>
    <div class="panel-body">
      <table id="tableAgent" class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Transfers</th>
            <th>Attempted</th>           
            <th>Verified</th>
            <th>Un-Attempted</th>
            
            {{-- <th></th> --}}
          </tr>
        </thead>
        <tbody>
        
            <tr>
                <td>{{$agentStats->id}}</td>
                <td>{{$agentStats->name}}</td>
                <td>{{$agentStats->attemptscallback+$agentStats->attemptsdropped+$agentStats->attemptsverified+$agentStats->unattemptscallback+$agentStats->unattemptsdropped}}</td>
                <td>{{$agentStats->attemptscallback+$agentStats->attemptsdropped+$agentStats->attemptsverified}}</td>
                <td>{{$agentStats->attemptsverified}}</td>
                <td>{{$agentStats->unattemptscallback+$agentStats->unattemptsdropped}}</td>
            </tr>
       
        </tbody>
      </table>
    </div>
  </div>
  <!--  END of Agents -->

  <hr>

  <!--  Closers -->
  <div class="panel panel-primary">
    <div class="panel-heading">Closers</div>
    <div class="panel-body">
      <table id="tableCloser" class="table" style="overflow:auto;">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Transfers</th>
            <th>Attempted</th>  
            <th>Verified</th>
            <th>Un-Attempted</th>
            {{-- <th></th> --}}
          </tr>
        </thead>
        <tbody>
         
            <tr>
                @if(!is_null($closerStats))
                    <td>{{$closerStats->id}}</td>
                    <td>{{$closerStats->name}}</td>
                    <td>{{$closerStats->attemptscallback+$closerStats->attemptsdropped+$closerStats->attemptsverified+$closerStats->unattemptscallback+$closerStats->unattemptsdropped}}</td>
                    <td>{{$closerStats->attemptscallback+$closerStats->attemptsdropped+$closerStats->attemptsverified}}</td>
                    <td>{{$closerStats->attemptsverified}}</td>
                    <td>{{$closerStats->unattemptscallback+$closerStats->unattemptsdropped}}</td>
                @endif
            </tr>
         
        </tbody>
      </table>
    </div>
  </div>
  <!--  END of Closers -->
</div>
<script>

  //Download Message
  $("#downloadtext").click(function(){

    var customername = "Customer Name : " + $('#customername').val();
    var message = "Details : " + "\r\n\n\t" + $('#message').val();
    var fee = "Fees : " +  $('#fee').val();
    var text = "\t \t \t\"Message ID: " + $(this).attr("data-id").toString()+ "\"" + "\r\n\r\n" + customername + "\r\n\r\n" + message + "\r\n\r\n" + fee;

    this.href = "data:text/plain;charset=UTF-8,"  + encodeURIComponent(text);
  });


  $(document).ready(function() {
 
  
    $('#callback').hide();
    $('#dateDiv').hide();
    $('#callbacktype').hide();
    $('#ifbankauth').hide();

    if($('#status :selected').text() == 'Approved') {
       $('#refundCheck').show(); 
       $('#callbacktype').show();
       $('#callback').hide();
       
    }

    if($("input[type='radio'].radioBtnClass").is(':checked')) {
      var type = $("input[type='radio'].radioBtnClass:checked").val();
      if(type != 'none'){
        $('.refundFee').show();
      }
      else{
        $('.refundFee').hide();
      }
    }

    var text = $('#bothText').val();
    var textSplit = text.split("agentMessageEnd");
     $('#message').text(textSplit[0]);
     $('#agentText').text(textSplit[1]);
     
      if(typeof textSplit[1] == 'undefined'){
         $('#agentText').text(textSplit[0]);
     }
     
    var allowcallback = '{{$message->allowcallback}}';
    if(allowcallback == 'yes') {
      $('#allowcallback').prop('checked', true);
    }

    console.log('current:'+$('#status :selected').text());

      if($('#status :selected').text() != 'CallBack') {
        $('#callback').hide();
        $('#submit').removeClass('col-md-9');
        $('#submit').addClass('col-md-12');
      }
      

    $('#status').on('change', function() {
      
      $('#callback').hide();
      $('#dateDiv').hide();
      $('#callbacktype').hide();
      $('#ifbankauth').hide();
      $('#refundCheck').hide();
      $('.refundFee').hide();

      $('#submit').removeClass('col-md-9');
      $('#submit').addClass('col-md-12');
      $("#finalFee").prop('required',false);
      
      if (this.value == 'BankAuth') {
        $('#callbacktype').show();
        $('#notificationto').text('Notification To:')
      }
      else if (this.value == 'BankAuth' || this.value == 'Decline') {
        $('#callbacktype').show();
        $('#notificationto').text('Notification To:')
      }
      else if( this.value == 'Approved' || this.value == 'CR Approved' || this.value == 'DMP Approved'){
        $("#finalFee").prop('required',true);
        $('#callbacktype').show();
        $('#notificationto').text('Notification To:');
        $('#refundCheck').show();
        $('.refundFee').show();
        
      }
      else if(this.value == 'Submit'){
        $("#finalFee").prop('required',true);
        $('#callbacktype').show();
        $('#notificationto').text('Notification To:'); 
      }

      else if(this.value == 'CallBack') {
        
        // $('#callback').show();    
        
        $('#dateDiv').show();
        
        $('#callbacktype').show();
      }
      else if(this.value == 'RNA'){
          $('#callback').show();
          $('#dateDiv').hide();
          $('#callbacktype').hide();    
          
      }
       else{
        $('#dateDiv').hide();
        $('#callback').hide();
        $('#callbacktype').hide();
       }

    });
    
    $('#callbackType').on('change', function(){
        if(this.value != 'Admin'){
            $('#callback').show();   
        }
        else{
            $('#callback').hide();   
        }
    });

  });

   $("input[type='radio'].radioBtnClass").change(function(){
    if($("input[type='radio'].radioBtnClass").is(':checked')) {
      var type = $("input[type='radio'].radioBtnClass:checked").val();
      if(type != 'none'){
        $('.refundFee').show();
      }
      else{
        $('.refundFee').hide();
      }
    }
  });
  
  $("#agentDetails").click(function(){
    $('#agentDetailDiv').toggle();
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
      drops:'up',
      locale: {
        format: 'YYYY/M/DD hh:mm:ss'
     }
    });
  });
  
</script>



@endsection
