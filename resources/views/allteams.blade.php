@extends('layouts.app')

@section('content')

  <div class="col-md-12">
    <a class="btn btn-md btn-success col-md-4 col-md-offset-4" data-toggle="modal" data-target="#AddModal">Add Team</a>
  </div>

  <hr>
  <!--  Teams -->
  <div class="col-md-10 col-md-offset-1">
  <div class="panel panel-primary">
    <div class="panel-heading">Teams</div>
    <div class="panel-body">
      <table id="tableteam" class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Memembers</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($teams as $team)
            <tr>
              <td>{{$team->id}}</td>
              <td>{{$team->name}}</td>
              <td><button onclick="TeamMember('{{$team->id}}','{{$team->name}}','{{$team->name}}');" class='btn btn-md btn-warning'>View Members</button></td>
              <td><a href="/admin/viewTeam/{{$team->id}}" class="btn btn-warning btn-sm">View</a> <a href="/admin/editTeam/{{$team->id}}" class="btn btn-primary btn-sm">Edit</a> <a href="/admin/disableTeam/{{$team->id}}" class="btn btn-danger btn-sm">Disable</a> <a href="/admin/deleteUser/{{$team->id}}" class="btn btn-danger btn-sm">Delete</a> </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <!--  END of Teams -->


<!-- ADD TEAM MODEL -->
<div id="AddModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Team</h4>
      </div>
      <div class="modal-body">
        <form action="{{route('registerTeam')}}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
              <label for="uname"><b>Name : </b></label>
              <input type="text" class="form-control" placeholder="Enter Team name" name="name" required>
            </div>

            <button type="submit" class="btn btn-success btn-block" name="button">Add</button>
         </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!--END MODEL-->

<!-- View Member MODEL -->
<div id="ViewMemberModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Members</h4>
      </div>
      <div class="modal-body">
        <div class="panel panel-primary">
          <h1 id="teamIDHead"></h1>
          <h2 id="teamNameHead"></h2>
          <div class="panel-heading">Members</div>
          <div class="panel-body">
            <table id="tableteamMember" class="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                {{-- @foreach ($members as $member)
                  <tr>
                    <td>{{$team->id}}</td>
                    <td>{{$team->name}}</td>
                    <td><a href="/admin/viewTeam/{{$member->id}}" class="btn btn-warning btn-sm">Remove</a> </td>
                  </tr>
                @endforeach --}}
              </tbody>
            </table>
          </div>
        </div>
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
    $('#tableteam').DataTable( {
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
             title: 'All Teams',
             exportOptions: {
                  columns: 'th:not(:nth-last-child(-n+2))'
             }
          },
          {
              extend: 'csv',
              title: 'All teams',
              exportOptions: {
                  columns: 'th:not(:nth-last-child(-n+2))'
              }

          },
          {
              extend: 'excel',
              title: 'All teams',
              exportOptions: {
                  columns: 'th:not(:nth-last-child(-n+2))'
              }
          }
      ]
    } );



});

function TeamMember(id,name,members){
  var body = "";

    foreach ($members as $member){
    <tr>
      <td>{{$team->id}}</td>
      <td>{{$team->name}}</td>
      <td><a href="/admin/viewTeam/{{$member->id}}" class="btn btn-warning btn-sm">Remove</a> </td>
    </tr>
  }

}
</script>

@endsection
