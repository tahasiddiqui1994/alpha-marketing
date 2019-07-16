@extends('layouts.app')

@section('content')
    
  @if(session()->has('message'))
  <div class="alert alert-success">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      {{ session()->get('message') }}
  </div>
  @endif        
  <div class="col-md-12">
    <a class="btn btn-md btn-success col-md-4 col-md-offset-4" href="{{url('/admin/registerAdmin')}}">Add Admin</a>
  </div>

  <div class="col-md-12" style="margin-bottom:10px;">

      @if ($status == 0)
        <button id="statusButton" class="btn btn-md btn-danger col-md-2 col-md-offset-9" data-toggle="modal" data-target="#statusModel">Disable All</button>
      @else
        <button id="statusButton"class="btn btn-md btn-warning col-md-2 col-md-offset-9" data-toggle="modal" data-target="#statusModel">Enable All</button>
      @endif


  </div>


  <!--  Admins -->
  <div class="col-md-10 col-md-offset-1">
  <div class="panel panel-primary">
    <div class="panel-heading">Admins</div>
    <div class="panel-body">
      <table id="tableAdmin" class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Email</th>
            <th>CreateAt</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        @if($admins != null)    
          @foreach ($admins as $admin)
            <tr>
              <td>{{$admin->id}}</td>
              <td>{{$admin->email}}</td>
              <td>{{$admin->created_at}}</td>
              <td><a href="/admin/editAdmin/{{$admin->id}}" class="btn btn-primary btn-sm">Edit</a> <a href="/admin/disableAdmin/{{$admin->id}}" class="btn btn-danger btn-sm">Disable</a> <a data-id="{{$admin->id}}" class="btn btn-danger btn-sm delButton" >Delete</a> </td>
            </tr>
          @endforeach
        @endif  
        </tbody>
      </table>
    </div>
  </div>
  <!--  END of Admins -->


  <!--  Disabled Admins  -->
  @if($disabledAdmins != null)
    <div class="panel panel-warning">
      <div class="panel-heading">Disabeled Admin</div>
      <div class="panel-body">
        <table id="tableDisabledAdmins" class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Email</th>
              <th>CreateAt</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($disabledAdmins as $disabledAdmin)
              <tr>
                <td>{{$disabledAdmin->id}}</td>
                <td>{{$disabledAdmin->email}}</td>
                <td>{{$disabledAdmin->created_at}}</td>
                <td> <a href="/admin/enableAdmin/{{$disabledAdmin->id}}" class="btn btn-success">Active</a></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif
  <!--  END of Disabled Admins -->


</div>

<div id="statusModel" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Authentication</h4>
      </div>
      <div class="modal-body">
        <form id="formStatus" action="/admin/ActiveToggleAdmin" method="post">
          <input type="hidden" name="status" value="{{$status}}">
          {{ csrf_field() }}
          <div class="form-group">
            <label for="uname"><b>Email</b></label>
            <input type="text" placeholder="Enter Email" class="form-control" value="{{Auth::guard('admin')->user()->email}}" name="email" readonly>
          </div>
          <div class="form-group">
            <label for="uname"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" class="form-control" name="password" required>
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


<div id="deleteModel" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Authentication</h4>
      </div>
      <div class="modal-body">
        <form id="formDelete" action="{{route('deleteAdmin')}}" method="post">
          <input type="hidden" id="adminID" name="adminID" value="">
          {{ csrf_field() }}
          <div class="form-group">
            <label for="uname"><b>Email</b></label>
            <input type="text" placeholder="Enter Email" class="form-control" value="{{Auth::guard('admin')->user()->email}}" name="email" readonly>
          </div>
          <div class="form-group">
            <label for="uname"><b>Password</b></label>
            <input type="text" placeholder="Enter Password" class="form-control" name="password" required>
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


$(document).ready(function() {
    $('#tableAdmin').DataTable( {
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
             title: 'All Agents',
             exportOptions: {
                   columns: 'th:not(:last-child)'
             }
          },
          {
              extend: 'csv',
              title: 'All Agents',
              exportOptions: {
                  columns: 'th:not(:last-child)'
              }

          },
          {
              extend: 'excel',
              title: 'All Admins',
              exportOptions: {
                    columns: 'th:not(:last-child)'
              }
          }
      ]
    } );


    $('#tableDisabledAdmins').DataTable( {
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
             title: 'Disable Admins',
             exportOptions: {
                   columns: 'th:not(:last-child)'
             }
          },
          {
              extend: 'csv',
              title: 'Disable Admins',
              exportOptions: {
                  columns: 'th:not(:last-child)'
              }

          },
          {
              extend: 'excel',
              title: 'Disable Admins',
              exportOptions: {
                    columns: 'th:not(:last-child)'
              }
          }
      ]
    });
    
    $(".delButton").click(function(){
      $('#adminID').val($(this).attr('data-id'));
       $('#deleteModel').modal('show');
    });
});
</script>

@endsection
