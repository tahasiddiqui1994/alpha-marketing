@extends('layouts.app')

@section('content')

<div class="container-fluid">
  <h2>RNAs</h2>

   <table id="tableMessage" class="table">

    <thead>
      <tr>
        <th>ID</th>
        <th>Agent</th>
        <th>Closer</th>
        <th>Customer Name</th>
        <th>Contact No</th>
        <th>Fee</th>
        <th>Status</th>
        {{-- <th>Date</th> --}}
        <th>Notes</th>
        <th>Select Merchant</th>
        <th>Send Email</th>
        <th>Action</th>
      </tr>
    </thead>

    <tbody>
      @foreach ($messages as $message)
        <tr id="tr{{$message->id}}">
          <td>{{$message->id}}</td>
          <td>{{$message->userName}}</td>
          <td>{{$message->closer}}</td>
          <td>{{$message->customername}}</td>
          <td>{{$message->contactNo}}</td>
          <td>{{$message->fees}}</td>
          <td>{{$message->status}}</td>
          <td>@if(is_null($message->note))----@else{{$message->note}}@endif</td>
          {{-- <td>{{$message->created_at}}</td>  --}}

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

          <td>
            <a href="{{ url('admin/showMessage/'.$message->id) }}" class="btn btn-sm btn-primary btn-xs">Show</a>
            <a href="{{ url('admin/editMessage/'.$message->id) }}" class="btn btn-sm btn-info btn-xs">Edit</a>
            <a id="{{$message->id}}" name="delete_message" class="btn btn-danger btn-xs">Delete</a>
          </td>
        </tr>
      @endforeach
    </tbody>

  </table>
  <button class="btn btn-lg btn-success" onclick="exportTableToCSV('allmessages.csv')" style="margin:15px !important;">Export HTML Table To CSV File</button>
  </div>
  
  <script>
  $(document).ready(function() {
   
    $('#tableMessage').DataTable( {
         
        
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

  </script>

@endsection
