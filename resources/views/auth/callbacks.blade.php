@extends('layouts.app')

@section('content')

<div class="container-fluid">
  <h2>Call Backs</h2>

  <table id="callBackTableMessage" class="table">

    <thead>
      <tr>
        <th>ID</th>
        <th>Closer</th>
        <th>Customer Name</th>
        <th>Contact No</th>
        <th>Fee</th>
        <th>Note</th>
        <th>CallBack Type</th>
        <th>CallBack Time</th>
        <th>Action</th>
      </tr>
    </thead>

    <tbody>
      @foreach ($messages as $message)
        
           @if(date("Y-m-d h:i:s",time()) < $message->callBackTime)
              <tr id="tr{{$message->id}}">
           @else
              <tr id="tr{{$message->id}}" class="bg-danger">
           @endif
        
          <td>{{$message->id}}</td>
          <td>{{$message->closer}}</td>
          <td>{{$message->customername}}</td>
          <td>{{$message->contactNo}}</td>
          <td>{{$message->fees}}</td>
          <td>{{$message->note}}</td>
          <td>{{$message->type}}</td>
          <td>{{$message->callBackTime}}</td>
          <td> 
            <a href="{{ url('admin/showMessage/'.$message->id) }}" class="btn btn-xs btn-primary">Show</a>
            <a href="{{ url('admin/editMessage/'.$message->id) }}" class="btn btn-xs btn-info">Edit</a>
            <button id="{{$message->id}}" type="button" name="delete_message" class="btn btn-xs btn-danger">Delete</button>
          </td>
        </tr>
      @endforeach
      
    </tbody>

  </table>


<script>
  var currentTime = '{{date("Y-m-d h:i:s",time())}}';   
$(document).ready(function() {
   
    $('#callBackTableMessage').DataTable( {
         
         "createdRow": function( row, data, dataIndex ) {
          
          var callBackTime = data[7];
           
            if(new Date(currentTime) > new Date(callBackTime)){
              
                $(row).css("background-color", "#d9534f");
                $(row).css("color", "#fff");
            }
          
         },
        "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
        "pageLength": 25,
        "order": [[ 0, "id" ]],
        dom: 'Bfrtip',
        buttons: [
          {
             extend: 'pdf',
             orientation: 'landscape',
             pageSize: 'A4',
             customize: function (doc) { doc.defaultStyle.fontSize = 12;  doc.styles.tableHeader.fontSize = 12; },
             title: 'All Messages',
             exportOptions: {
                 columns: 'th:not(:nth-last-child(-n+1))'
             }
          },
          {
              extend: 'csv',
              title: 'All Messages',
              exportOptions: {
                  columns: 'th:not(:nth-last-child(-n+1))'
              }

          },
          {
              extend: 'excel',
              title: 'All Messages',
              exportOptions: {
                  columns: 'th:not(:nth-last-child(-n+1))'
              }
          }
      ]
    } );
  } );

</script>
</div>


@endsection