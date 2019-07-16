@extends('layouts.app')

@section('content')

<h3 class="text-center">Monthly CRB Statistics</h3>

<div class="form-group text-center">
<form class="" action="{{route('CRBStatsMonthly')}}" method="post">
  <div class="row">
   <div class="col-md-offset-2 col-md-4">
            <label for=""></label>
              @if(is_null($from))
                <input style="border-radius: 5px; font-size: 20px; border:1px solid #28a745; background:white;" type="text" name="startDate" value="<?php echo date('m-01-Y'); ?>" placeholder="Enter Date..." />
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
                <th>CRB</th>
                <th>RNA</th>
                <th>Still RNA</th>
                <th>Callback</th>
                <th>Approved</th>
                <th>Declined</th>
                {{-- <th></th> --}}
              </tr>
            </thead>
            <tbody>
              @foreach ($agents as $agent)
                <tr>
                    <td>{{$agent->id}}</td>
                    <td>{{$agent->name}}</td>
                    <td>{{$agent->crb}}</td>
                    <td>{{$agent->crbrna}}</td>
                    <td>{{$agent->crbstillrna}}</td>
                    <td>{{$agent->crbcallback}}</td>
                    <td>{{$agent->crbapproved}}</td>
                    <td>{{$agent->crbdecine}}</td>
                </tr>
              @endforeach
              
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
          <table id="tableCloser" class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Username</th>
                <th>CRB</th>
                <th>RNA</th>
                <th>Still RNA</th>
                <th>Callback</th>
                <th>Approved</th>
                <th>Declined</th>
                {{-- <th></th> --}}
              </tr>
            </thead>
            <tbody>
              @foreach ($closers as $closer)
                <tr>
                    <td>{{$closer->id}}</td>
                    <td>{{$closer->name}}</td>
                    <td>{{$closer->crb}}</td>
                    <td>{{$closer->crbrna}}</td>
                    <td>{{$closer->crbstillrna}}</td>
                    <td>{{$closer->crbcallback}}</td>
                    <td>{{$closer->crbapproved}}</td>
                    <td>{{$closer->crbdecine}}</td>
                </tr>
                @if(!is_null($closer->CRBTCAID))
                    @foreach ($closer->CRBTCAID as $stats)
                          
                        <tr >
                            <td>{{$stats->agent->id}}</td>
                            <td>{{$stats->agent->name}}</td>
                            <td>{{$stats->count}}</td>
                            <td>---</td>
                            <td>---</td>
                            <td>---</td>
                            <td>---</td>
                            <td>---</td>          
                        </tr>

                    @endforeach
                @endif
                <tr style="background-color:#337ab7;">
                    <td>----</td>
                    <td>----</td>
                    <td>----</td>
                    <td>----</td>
                    <td>----</td>
                    <td>----</td>
                    <td>----</td>
                    <td>----</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <!--  END of Closers -->


 <script type="text/javascript">
     $( document ).ready(function() {   
    
     // $('input[name="date"]').change(function(){
        // $.ajax({
        //     url: "{{route('statsMonthly')}}",
        //     type: 'POST',
        //     data:{
        //       startDate: $('input[name="startDate"]').val(),
        //       endDate: $('input[name="endDate"]').val(),
        //       _token: "{{ csrf_token() }}"
        //     },

        //     success: function(data) {
        //         var json = data;

        //         console.log('data length: '+data[1].length);

        //         if(json.length > 0) {
        //           assignToAgentColumns(data[0]);
        //           assignToCloserColumns(data[1]);
        //         }

        //     }
        // });
       
         
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
        
      
           
            var table = $('#tableCloser').dataTable({
                "bAutoWidth" : false,
             
                "aLengthMenu": [[10, 50, -1], [10, 50, 100, "All"]],
                "pageLength": 10,
                "aaSorting": [],
                dom: 'Bfrtip',
                buttons: [
                   {
                     extend: 'pdf',
                     title: 'StatsCloser'+' '+new Date().toISOString().split('T')[0],
                     orientation: 'landscape',
                     pageSize: 'A4',
                     customize: function (doc) { doc.defaultStyle.fontSize = 12;  doc.styles.tableHeader.fontSize = 12; },

                  },
                  {
                      extend: 'csv',
                      title:  'StatsCloser'+' '+new Date().toISOString().split('T')[0],


                  },
                  {
                      extend: 'excel',
                      title: 'StatsCloser'+' '+new Date().toISOString().split('T')[0],

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
});
</script>

@endsection
