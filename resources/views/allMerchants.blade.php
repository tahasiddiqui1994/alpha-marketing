@extends('layouts.app')

@section('content')

  <div class="col-md-12">
    <button type="button" class="btn btn-md btn-success col-md-4 col-md-offset-4" data-toggle="modal" data-target="#addMerchantModel">Add Merchant</button>
  </div>

  <hr>
  <!--  Merchants -->
  <div class="col-md-10 col-md-offset-1">
  <div class="panel panel-primary">
    <div class="panel-heading">Merchants</div>
    <div class="panel-body">
      <table id="allMerchants" class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($allMerchants as $merchant)
            <tr>
              <td>{{$merchant->id}}</td>
              <td>{{$merchant->name}}</td>
              <td>{{$merchant->email}}</td>
              <td>
                <button id="editButton" data-id="{{$merchant->id}}" data-name="{{$merchant->name}}" data-email="{{$merchant->email}}" class="btn btn-primary">Edit</button>
                <a href="/admin/deleteMerchant/{{$merchant->id}}" class="btn btn-danger">Delete</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <!--  END of Merchants -->

</div>


</div>




<!--Add Merchant Modal -->
<div id="addMerchantModel" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="panel panel-primary">
        <div class="panel-heading text-center">ADD MERCHANT</div>

        <div class="panel-body">
            <form method="POST" action="{{ route('AddMerchant') }}">
                <div class="form-group">
                    <label for="username">Username :</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="password">Email :</label>
                    <input type="text" class="form-control" id="email" name="email" required>
                </div>
                {{ csrf_field() }}
                <input type="submit" value="Submit" class="btn btn-primary">
            </form>
        </div>

    </div>

  </div>
</div>


<!--Add Update Modal -->
<div id="editMerchantModel" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="panel panel-primary">
        <div class="panel-heading text-center">ADD MERCHANT</div>

        <div class="panel-body">
            <form method="POST" action="{{ route('UpdateMerchant') }}">
                <div class="form-group">
                    <label for="username">ID :</label>
                    <input type="text" class="form-control" id="merchantID" name="id" readonly>
                </div>
                <div class="form-group">
                    <label for="username">Username :</label>
                    <input type="text" class="form-control" id="merchantName" name="name" required>
                </div>
                <div class="form-group">
                    <label for="password">Email :</label>
                    <input type="text" class="form-control" id="merchantEmail" name="email" required>
                </div>
                {{ csrf_field() }}
                <input type="submit" value="Submit" class="btn btn-primary">
            </form>
        </div>

    </div>

  </div>
</div>

<!-- Footer -->
<footer class="w3-container w3-padding-16 w3-light-grey">
  <p>Powered by <a href="http://www.sudoware.pk/" target="_blank">Sudoware</a></p>
</footer>

<!-- End page content -->
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
    $('#allMerchants').DataTable( {
      "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
      "pageLength": 25,
      "order": [[ 0, "id" ]],
      dom: 'Bfrtip',
      buttons: [
         {
           extend: 'pdf',
           orientation: 'landscape',
           pageSize: 'A4',
           title: 'AllMerchants'+' '+new Date().toISOString().split('T')[0],
           customize: function (doc) { doc.defaultStyle.fontSize = 12;  doc.styles.tableHeader.fontSize = 12; },
           exportOptions: {
               columns: 'th:not(:last-child)'
           }
        },
        {
            extend: 'csv',
            title: 'AllMerchants'+' '+new Date().toISOString().split('T')[0],
            exportOptions: {
                columns: 'th:not(:last-child)'
            }

        },
        {
            extend: 'excel',
            title: 'AllMerchants'+' '+new Date().toISOString().split('T')[0],
            exportOptions: {
                columns: 'th:not(:last-child)'
            }
        }
    ]
    } );
  } );
</script>


<script type="text/javascript">

  $("#editButton").click(function(){
    $('#merchantID').val($(this).attr("data-id"));
    $('#merchantName').val($(this).attr("data-name"));
    $('#merchantEmail').val($(this).attr("data-email"));

    $('#editMerchantModel').modal('toggle');
  });

</script>


@endsection
