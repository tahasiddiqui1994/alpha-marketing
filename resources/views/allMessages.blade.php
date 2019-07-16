@extends('layouts.app')

@section('content')

<div class="container-fluid">
   @if(session()->has('message'))
      <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          {{ session()->get('message') }}
      </div>
      @endif

  <h2>All Messages</h2>

  <div class="form-group text-center">
      <form class="" action="{{route('messageMonthly')}}" method="post">

        <div class="row ">
          <div class="col-md-offset-2 col-md-4">
            <label for=""></label>
              @if(is_null($from))
                <input style="border-radius: 5px; font-size: 20px; border:1px solid #28a745; background:white;" type="text" name="startDate" value="<?php echo date('m-d-Y'); ?>" placeholder="Enter Date..." />
              @else
                <input style="border-radius: 5px; font-size: 20px; border:1px solid #28a745; background:white;" type="text" name="startDate" value="<?php echo date('m-d-Y', strtotime($from)) ?>" placeholder="Enter Date..." />
              @endif
              <p class="help-block" style="font-weight:bold;">From Date</p>
          </div>

          <div class="col-md-4">
            <label for=""></label>
              @if(is_null($to))
                <input style="border-radius: 5px; font-size: 20px; border:1px solid #28a745; background:white;" type="text" name="endDate" value="<?php echo date('m-d-Y'); ?>" placeholder="Enter Date..." />
              @else
                <input style="border-radius: 5px; font-size: 20px; border:1px solid #28a745; background:white;" type="text" name="endDate" value="<?php echo date('m-d-Y', strtotime($to)) ?>" placeholder="Enter Date..." />
              @endif
              <p class="help-block" style="font-weight:bold;">To Date</p>
          </div>
        </div>
        {{ csrf_field() }}
        <button type="submit" class="btn btn-success btn-lg" id="Submit" name="button">Submit</button>

      </form>
  </div>
  <button class="btn" id="left-button">
      <i class="fa fa-arrow-left" aria-hidden="true"></i>
    <!--  swipe left-->
    </button>
    <button class="btn" id="right-button">
        <i class="fa fa-arrow-right" aria-hidden="true"></i>
    <!-- swipe right-->
  </button>
<div id="tableDiv" style=" overflow-x: auto;
    white-space: nowrap;">
  <table id="tableMessage" class="table table-condensed table-responsive display">

    <thead>
      <tr>
        <th>ID</th>
        <th>Agent</th>
        <th>Closer</th>
        <th>Customer Name</th>
        <th>Contact No</th>
        <th>ActionButtons</th>
        <th>Fee</th>
        <th>Final Fee</th>
        <th>ChargeBack Fee</th>
        <th>Net Fee</th>
        <th>Prev-Status</th>
        <th>Status</th>      
        <th>Notes</th>
        <th>LastUpdatedBy</th>
        <th>Select Merchant</th>
        <th>Send Email</th>
        <th>Date</th>
        
      </tr>
    </thead>

    <tbody>
      @foreach ($allMessages as $message)
        <tr id="tr{{$message->id}}">
          <td>{{$message->id}}</td>
          <td>{{$message->userName}}</td>
          <td>{{$message->closer}}</td>
          <td>{{$message->customername}}</td>
          <td>{{$message->contactNo}}</td>
          <td>
            <a href="{{ url('admin/showMessage/'.$message->id) }}" class="btn btn-primary btn-xs">Show</a>
            <a href="{{ url('admin/editMessage/'.$message->id) }}" class="btn btn-info btn-xs">Edit</a>
            <a id="{{$message->id}}" name="delete_message" class="btn btn-danger btn-xs">Del</a>
          </td>
          <td>{{$message->fees}}</td>
          <td>{{$message->finalFee}}</td>
          <td>{{$message->returnAmount}}</td>
          <td>{{$message->returnAmount+$message->finalFee}}</td>
          <td>{{$message->prevStatus}}</td>
          
          @if($message->status == 'none')
            <td>Pending</td>
          @else
            <td>{{$message->status}}</td>
          @endif
          
          
          <td>@if(is_null($message->note))----@else{{$message->note}}@endif</td>
          <td>{{$message->updatedBy}}</td>  
          <td>
              <form action="{{ route('sendMail') }}" id="message{{$message->id}}" method="post">
              <input type="hidden" name="messageBody" value="{{$message->text}}">
              <input type="hidden" name="customerName" value="{{$message->customername}}">
              <input type="hidden" name="fees" value="{{$message->fees}}">
              {{ csrf_field() }}
              <div class="form-group">
                <select class="form-control" style="width:50%;" name="merchantEmail" id="sel1{{$message->id}}">

                  @if(is_null($message->merchantID))
                    <option value="" selected>No merchant</option>
                  @else
                    <option value="{{$merchant->email}}" selected>{{$message->merchantName}}</option>
                  @endif

                  @foreach ($allMerchants as $merchant)
                  <option value="{{$merchant->email}}">{{$merchant->name}}</option>
                  @endforeach
                </select>
              </div>
              </form>
            </td>
            <td>
              <button type="submit" form="message{{$message->id}}"  class="btn btn-success btn-sm">Send</button>
            </td>
            <td>{{$message->updated_at}}</td>
        </tr>
      @endforeach
    </tbody>

  </table>
</div> 
   


</div>


<script type="text/javascript">

// $('#Submit').click(function(){
// // $('input[name="date"]').change(function(){
//    $.ajax({
//
//        type: 'POST',
//        data:{
//          startDate: $('input[name="startDate"]').val(),
//          endDate: $('input[name="endDate"]').val(),
//          _token: "{{ csrf_token() }}"
//        },
//
//        success: function(data) {
//            var json = data;
//
//
//
//            if(json.length > 0) {
//              assignToAgentColumns(data);
//            }
//
//        }
//    });
//
//    function assignToAgentColumns(data) {
//        $("#tableMessage").dataTable().fnDestroy();
//        var table = $('#tableMessage').dataTable({
//            "bAutoWidth" : false,
//            "aaData" : data,
//            "columns" : [ {
//                "data" : "id"
//            }, {
//                "data" : "userName"
//            }, {
//                "data" : "closer"
//            }, {
//                "data" : "customername"
//            }, {
//                "data" : "fees"
//            }, {
//                "data" : "status"
//            },{
//                "data" : "text","merchantID","merchantName"
//            },{
//                "data" : "submissionAmount"
//            },{
//                "data" : "approvalAmount"
//            },{
//                "data" : "ratio"
//            }
//            ],
//            "aLengthMenu": [[10, 50, -1], [10, 50, 100, "All"]],
//            "pageLength": 10,
//            "order": [[ 0, "id" ]],
//            "scrollX": true,
//            dom: 'Bfrtip',
//            buttons: [
//               {
//                 extend: 'pdf',
//                 orientation: 'landscape',
//                 pageSize: 'A4',
//                 customize: function (doc) { doc.defaultStyle.fontSize = 12;  doc.styles.tableHeader.fontSize = 12; },
//                 title: 'All Messages',
//                 exportOptions: {
//                     columns: 'th:not(:nth-last-child(-n+2))'
//                 }
//              },
//              {
//                  extend: 'csv',
//                  title: 'All Messages',
//                  exportOptions: {
//                      columns: 'th:not(:nth-last-child(-n+2))'
//                  }
//
//              },
//              {
//                  extend: 'excel',
//                  title: 'All Messages',
//                  exportOptions: {
//                      columns: 'th:not(:nth-last-child(-n+2))'
//                  }
//              }
//          ],
//
//        });
//    }

$(document).ready(function() {
    $('#tableMessage').DataTable( {
         "createdRow": function( row, data, dataIndex ) {
          
       
         
          if(data[11] == "RNA"){
                $(row).css("background-color", "#f0ad4e");
                $(row).css("color", "#fff");
          }
          if(data[11] == "Approved" || data[10] == "CR Approved" ){
                $(row).css("background-color", "#5cb85c");
                $(row).css("color", "#fff");
          }
          if(data[11] == "Chargeback"){
                $(row).css("background-color", "#d9534f");
                $(row).css("color", "#fff");
          }
          
         },
        // "scrollX": true,
        // "scrollCollapse": true,
        // "scroller":       true,
        "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
        "pageLength": 10,
        "paging": true,
        "order": [[ 0, "id" ]],
        dom: 'Bfrtip',
        scroller: {
            loadingIndicator: true
        },
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
      ]
    } );
  } );

    $('#right-button').click(function() {
      event.preventDefault();
      $('#tableDiv').animate({
        scrollLeft: "+=200px"
      }, "slow");
    });

   $('#left-button').click(function() {
      event.preventDefault();
      $('#tableDiv').animate({
        scrollLeft: "-=200px"
      }, "slow");
    });

  $(function() {
    $('input[name="startDate"]').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
    }, function(start, end, label) {

    });
    $('input[name="endDate"]').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
    }, function(start, end, label) {

    });
  });
</script>

@endsection
