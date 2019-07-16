@extends('layouts.app')

@section('content')

  <div class="col-md-12">
    <a class="btn btn-md btn-success col-md-4 col-md-offset-4" href="{{url('/admin/register')}}">Add User</a>
  </div>

  <div class="col-md-12" style="margin-bottom:10px;">

      @if ($status == 0)
        <button id="statusButton" class="btn btn-md btn-danger col-md-2 col-md-offset-9" data-toggle="modal" data-target="#statusModel">Disable All</button>
      @else
        <button id="statusButton"class="btn btn-md btn-warning col-md-2 col-md-offset-9" data-toggle="modal" data-target="#statusModel">Enable All</button>
      @endif

    {{-- @if ($status == 0)
      <a class="btn btn-md btn-danger col-md-2 col-md-offset-9" href="{{url('/admin/ActiveToggle/'.$status)}}">Disable All</a>
    @else
      <a class="btn btn-md btn-warning col-md-2 col-md-offset-9" href="{{url('/admin/ActiveToggle/'.$status)}}">Enable All</a>
    @endif --}}

  </div>


  <!--  Agents -->
  <div class="col-md-10 col-md-offset-1">
  <div class="panel panel-primary">
    <div class="panel-heading">Agents</div>
    <div class="panel-body">
      <table id="tableAgent" class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>CreateAt</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @if($agents != null)     
          @foreach ($agents as $agent)
            <tr>
              <td>{{$agent->id}}</td>
              <td>{{$agent->name}}</td>
              <td>{{$agent->created_at}}</td>
              <td><a href="/admin/viewUser/{{$agent->id}}" class="btn btn-warning btn-sm">View</a> <a href="/admin/editUser/{{$agent->id}}" class="btn btn-primary btn-sm">Edit</a> <a href="/admin/disableUser/{{$agent->id}}" class="btn btn-danger btn-sm">Disable</a> <a href="/admin/deleteUser/{{$agent->id}}" class="btn btn-danger btn-sm">Delete</a> </td>
            </tr>
          @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>
  <!--  END of Agents -->

  <!--  Closers -->
  <div class="panel panel-primary">
    <div class="panel-heading">Closers</div>
    <div class="panel-body">
      <table id="tableCloser" class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>CreateAt</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @if($closers !=null)        
          @foreach ($closers as $closer)
            <tr>
              <td>{{$closer->id}}</td>
              <td>{{$closer->name}}</td>
              <td>{{$closer->created_at}}</td>
              <td>  <a href="/admin/viewUser/{{$closer->id}}" class="btn btn-warning btn-sm">View</a> <a href="/admin/editUser/{{$closer->id}}" class="btn btn-primary btn-sm">Edit</a> <a href="/admin/disableUser/{{$closer->id}}" class="btn btn-danger btn-sm">Disable</a> <a href="/admin/deleteUser/{{$closer->id}}" class="btn btn-danger btn-sm">Delete</a> </td>
            </tr>
          @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>
  <!--  END of Closers -->

  <hr>

  <!--  Disabled Agents  -->
  @if(count($disabledAgents)>0)
    <div class="panel panel-warning">
      <div class="panel-heading">Disabeled Agents</div>
      <div class="panel-body">
        <table id="tableDisabledAgents" class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>CreateAt</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($disabledAgents as $disabledAgent)
              <tr>
                <td>{{$disabledAgent->id}}</td>
                <td>{{$disabledAgent->name}}</td>
                <td>{{$disabledAgent->created_at}}</td>
                <td> <a href="/admin/enableUser/{{$disabledAgent->id}}" class="btn btn-success">Active</a></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif
  <!--  END of Disabled Agents -->

  <!--  Disabled Closers  -->
  @if(count($disabledClosers)>0)
    <div class="panel panel-warning">
      <div class="panel-heading">Disabeled Closers</div>
      <div class="panel-body">
        <table id="tableDisabledCloser" class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>CreateAt</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($disabledClosers as $disabledCloser)

            <tr>
              <td>{{$disabledCloser->id}}</td>
              <td>{{$disabledCloser->name}}</td>
              <td>{{$disabledCloser->created_at}}</td>
              <td> <a href="/admin/enableUser/{{$disabledCloser->id}}" class="btn btn-success">Active</a></td>
            </tr>

            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif
  <!--  END of Disabled Agents -->
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
        <form id="formStatus" action="/admin/ActiveToggle" method="post">
          <input type="hidden" name="status" value="{{$status}}">
          {{ csrf_field() }}
          <div class="form-group">
            <label for="uname"><b>Email</b></label>
            <input type="text" placeholder="Enter Email" class="form-control" name="email" required>
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
    $('#tableAgent').DataTable( {
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
              title: 'All Agents',
              exportOptions: {
                    columns: 'th:not(:last-child)'
              }
          }
      ]
    } );

    $('#tableCloser').DataTable( {
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
             title: 'All Closers',
             exportOptions: {
                   columns: 'th:not(:last-child)'
             }
          },
          {
              extend: 'csv',
              title: 'All Closers',
              exportOptions: {
                  columns: 'th:not(:last-child)'
              }

          },
          {
              extend: 'excel',
              title: 'All Closers',
              exportOptions: {
                    columns: 'th:not(:last-child)'
              }
          }
      ]
    } );

    $('#tableDisabledAgents').DataTable( {
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
             title: 'Disable Agents',
             exportOptions: {
                   columns: 'th:not(:last-child)'
             }
          },
          {
              extend: 'csv',
              title: 'Disable Agents',
              exportOptions: {
                  columns: 'th:not(:last-child)'
              }

          },
          {
              extend: 'excel',
              title: 'Disable Agents',
              exportOptions: {
                    columns: 'th:not(:last-child)'
              }
          }
      ]
    } );


    $('#tableDisabledCloser').DataTable( {
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
             title: 'Disable Closers',
             exportOptions: {
                   columns: 'th:not(:last-child)'
             }
          },
          {
              extend: 'csv',
              title: 'Disable Closers',
              exportOptions: {
                  columns: 'th:not(:last-child)'
              }

          },
          {
              extend: 'excel',
              title: 'Disable Closers',
              exportOptions: {
                    columns: 'th:not(:last-child)'
              }
          }
      ]
    } );

  } );
</script>

@endsection
