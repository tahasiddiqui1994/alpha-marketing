@extends('layouts.app')

@section('content')

<h3 class="text-center">Monthly Amount Statistics</h3>
<form class="" action="{{route('AmountStatsMonthly')}}" method="post">
<div class="form-group text-center">

  <div class="row">
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



<!-- JQWidgets SCRIPTS and CSS-->
<link rel="stylesheet" href="{{ asset('css/jqwidgets/jqx.base.css') }}" type="text/css" />
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxcore.js') }}"></script>
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxchart.js') }}"></script>
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxchart.core.js') }}"></script>
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxdata.js') }}"></script>

      <!--  Agents -->
      <div class="panel panel-primary">
        <div class="panel-heading">Agents</div>
        <div class="panel-body">
          <table id="tableAgent" class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Total Cr/Vr</th>
                <th>Agent Verified</th>
                <th>CRB's</th>
                <th>Total(Cr+Vr)</th>
                <th>CRB's Approved</th>
                <th>Verified Approved</th>
                <th>Total(Cr+Vr) Approved</th>
                {{-- <th></th> --}}
              </tr>
            </thead>
            <tbody>
              @foreach ($agents as $agent)
                <tr>
                    <td>{{$agent->id}}</td>
                    <td>{{$agent->name}}</td>
                    <td>{{$agent->crvr}}</td>
                    <td>{{$agent->attemptsverified}}</td>
                    <td>{{$agent->crb}}</td>
                    <td>{{$agent->totalcrvr}}</td>
                    <td>{{$agent->crbapproved}}</td>
                    <td>{{$agent->approved}}</td>
                    <td>{{$agent->totalcrvrapproved}}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <!--  END of Agents -->


 <script type="text/javascript">

var table = $('#tableAgent').dataTable({
        "bAutoWidth" : false,
        "aLengthMenu": [[10, 50, -1], [10, 50, 100, "All"]],
        "pageLength": 10,
        "order": [[ 0, "id" ]],
        "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'pdf',
                title: 'StatsAgent'+' '+new Date().toISOString().split('T')[0],
                orientation: 'landscape',
                pageSize: 'A4',
                customize: function (doc) { doc.defaultStyle.fontSize = 12;  doc.styles.tableHeader.fontSize = 12; },

            },
            {
                extend: 'csv',
                title:  'StatsAgent'+' '+new Date().toISOString().split('T')[0],

            },
            {
                extend: 'excel',
                title: 'StatsAgent'+' '+new Date().toISOString().split('T')[0],

            }
        ],

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
