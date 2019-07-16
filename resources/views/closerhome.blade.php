@extends('layouts.app')

@section('content')

  @if(session()->has('message'))
  <div class="alert alert-success">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      {{ session()->get('message') }}
  </div>
  @endif

<div class="container">
    <div class="row">

        <div class="">
            <div class="panel panel-success">
                <div class="panel-heading" style="height: 46px;">
                    <div class="col-md-12">
                    <div class="col-md-9">CLOSER DASHBOARD</div>
                    </div>
                </div>


            <div class="panel-body">
                    <table id="closertableMessage" class="table">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Closername</th>
                            <th>Customername</th>
                            <th>ContactNo</th>
                            <th>Fee</th>
                            <th>Status</th>
                            <th>CallBack Time</th>
                            <th>Note</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($messages as $message)

                        <tr>
                         
                            <td>{{$message->id}}</td>   
                            <td>{{$message->username}}</td>
                            <td>{{$message->closer}}</td>
                            <td>{{$message->customername}}</td>
                            <td>{{$message->contactNo}}</td>
                            <td>{{$message->fees}}</td>
                            <td>{{$message->finalFee}}</td>
                            <td>{{$message->status}}</td>
                            <td>{{$message->callBackTime}}</td>
                            @if($message->note != null)
                                <td>{{$message->note}}</td>
                            @else
                                <td>-----</td>
                            @endif

                            
                            @if ( $message->allowcallback == 'closer' || $message->allowcallback == 'agent/closer' || $message->prevStatus == 'Callback' )
                             <td><a href="{{ url('/editMessage').'/'.$message->id }}" class="btn btn-primary btn-md">Edit</td>
                             
                            @elseif(($message->status == 'CallBack' &&  ($message->callBackType == 'Agent/Closer' ||  $message->callBackType == 'Closer')) || $message->callBackType == 'Dropped')
                                <td>
                                  <div class="form-group">
                                 
                                    <select class="form-control status" id="status" style="width:70%;" name="status">
                                         <option selected disabled>Select One</option>
                                         <option   value="Submit" >Verified</option>
                                         <option   value="Dropped">Dropped</option>
                                         <option   value="CallBack">CallBack</option>
                                    </select>
                                     <input type="hidden" id="messageID{{$message->id}}" value="{{$message->id}}">
                                     
                                 </div>
                                </td>
                            
                            @elseif($message->status == 'BankAuth' || $message->status == 'Still BankAuth' ||  $message->status == 'BankAuth CallBack')
                                <td>
                                  <div class="form-group">
                                 
                                     <select class="form-control status" id="status" style="width:70%;" name="status">
                                         <option selected disabled>Select One</option>
                                         <option   value="Still BankAuth" >Still Bank Auth</option>
                                         <option   value="BankAuth Removed">BankAuth Removed</option>
                                         <option   value="BankAuth CallBack">BankAuth CallBack</option>
                                     </select>
                                     <input type="hidden" id="messageID{{$message->id}}" value="{{$message->id}}">
                                     
                                 </div>
                              </td>
                            @elseif($message->status == 'RNA' || $message->status == 'Still RNA')
                                <td>
                                  <div class="form-group">
                                 
                                     <select class="form-control status" id="status" style="width:70%;" name="status">
                                         <option selected disabled>Select One</option>
                                         <option   value="Connected" >Connected</option>
                                         <option   value="Dropped">Dropped</option>
                                         <option   value="Still RNA">Still RNA</option>
                                     </select>
                                     <input type="hidden" id="messageID{{$message->id}}" value="{{$message->id}}">
                                     
                                 </div>
                                </td>
                          
                            @elseif($message->status == 'Decline')
                                <td>
                                  <div class="form-group">
                                 
                                     <select class="form-control status" id="status" style="width:70%;" name="status">
                                         <option selected disabled>Select One</option>
                                         <option   value="Submit" >Verified</option>
                                         <option   value="CallBack">CallBack</option>
                                     </select>
                                     <input type="hidden" id="messageID{{$message->id}}" value="{{$message->id}}">
                                     
                                 </div>
                                </td>  
                              
                              
                            @else
                                <td></td> 
                            @endif
                                
                          </tr>

                          @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>

<!--MODAL For Status-->
<div id="modalStatus" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Status Change</h4>
      </div>
      <div class="modal-body">
          <form class="" action="{{route('updateStatus')}}" method="post">
              {{ csrf_field() }}
              <div class="row">
                <input type="hidden" name="messageID" id="messageID" value="">
                <input type="hidden" name="ID" value="{{ Auth::user()->id }}">
                 <div class="form-group col-md-6">
                 <label for="fee">Status :</label>
                 <input class="form-control" type="text" id="statusType" name="status" value="" readonly>
                </div>
                <div class="form-group col-md-6">
                 <label for="fee">Name :</label>
                 <input class="form-control" type="text" id="name" name="name" value="{{Auth::user()->name}}" required readonly>
                </div>
                <div class="form-group col-md-6">
                 <label for="fee">Password :</label>
                 <input class="form-control" type="password" id="password" name="password" value="" required>
                </div>
              </div>
              <div class="form-group col-md-offset-3 col-md-6">
              
                  <div id="dateDiv" class="form-group col-md-12" style="display:none;">
                         <label for="">Date/Time:</label>
                           <input style="border-radius: 5px; font-size: 20px; border:1px solid #28a745;" class="form-control" type="text" name="Date" value="<?php echo date('Y-m-d h:i:sa'); ?>" placeholder="Enter Date..." />
                  </div>             
             </div>             
              <button type="submit" class="btn btn-success btn-block" name="button">Submit</button>
          </form>
     
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
</div>
<!--End Of MODAL-->

<script>
   
$(document).ready(function() {
    $('.status').on('change', function(){
       
       $('#statusType').val($(this).val());
       $('#messageID').val($(this).next().val());
       $('#modalStatus').modal('show');
       if($(this).val()=="CallBack" || $(this).val()=='BankAuth CallBack'){
           $('#dateDiv').show();
       }
       else{
           $('#dateDiv').hide();
       }
    });
     
     $('#closertableMessage').DataTable( {
        
          "createdRow": function( row, data, dataIndex ) {
          
          var callBackTime = data[6];
          if(data[6] == "RNA"){
                $(row).css("background-color", "#f0ad4e");
                $(row).css("color", "#fff");
          }
          if(data[6] == "Approved" || data[6] == "CR Approved" || data[6] == "DMP Approved"){
                $(row).css("background-color", "#5cb85c");
                $(row).css("color", "#fff");
          }
          if(data[6] == "Chargeback"){
                $(row).css("background-color", "#d9534f");
                $(row).css("color", "#fff");
          }
          if(data[6] == "CallBack"){
                $(row).css("background-color", "#428bca");
                $(row).css("color", "#fff");
          }
          
         },
        "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
        "pageLength": 25,
        "scrollX": true,
        "scrollCollapse": true,
        "paging": false,
        "order": [[ 0, "id" ]],
        dom: 'Bfrtip',
        scroller: {
            loadingIndicator: true
        },
        
        
  });   

});


 $(function() {
    $('input[name="Date"]').daterangepicker({
      startDate: moment().startOf('hour'),
      timePicker: true,
      singleDatePicker: true,
      showDropdowns: false,
      drops:'up',
      locale: {
       format: 'YYYY/M/DD hh:mm A'
     }
    });
 });        
    
</script>
@endsection
