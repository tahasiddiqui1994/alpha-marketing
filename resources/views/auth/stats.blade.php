@extends('layouts.app')

@section('content')

<h3 class="text-center">Monthly Verified Statistics</h3>
<form class="" action="{{route('statsMonthly')}}" method="post">
<div class="form-group text-center">

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
                <th>Transfers</th>
                <th>Attempted</th>
                <th>Callbacks</th>
                <th>Dropped</th>
                <th>Verified</th>
                <th>Un-Attempted</th>
                <th>Callbacks</th>
                <th>Dropped</th>
                <th>Fees</th>                
                <th>Approved</th>
                <th>Approved Amount</th>
                <th>Return Amount</th>
                <th>Net Amount</th>
                {{-- <th></th> --}}
              </tr>
            </thead>
            <tbody>
              @foreach ($agents as $agent)
                <tr>
                    <td>{{$agent->id}}</td>
                    <td>{{$agent->name}}</td>
                    <td>{{$agent->attemptscallback+$agent->attemptsdropped+$agent->attemptsverified+$agent->unattemptscallback+$agent->unattemptsdropped}}</td>
                    <td>{{$agent->attemptscallback+$agent->attemptsdropped+$agent->attemptsverified}}</td>
                    <td>{{$agent->attemptscallback}}</td>
                    <td>{{$agent->attemptsdropped}}</td>
                    <td>{{$agent->attemptsverified}}</td>
                    <td>{{$agent->unattemptscallback+$agent->unattemptsdropped}}</td>
                    <td>{{$agent->unattemptscallback}}</td>
                    <td>{{$agent->unattemptsdropped}}</td>
                    <td>{{$agent->fees}}</td>
                    <td>{{$agent->approved}}</td>
                    @if($agent->approvedAmount == null)
                        <td>0</td>
                    @else
                        <td>{{$agent->approvedAmount}}</td>
                    @endif
                    @if($agent->returnAmount == null)
                        <td>0</td>
                    @else
                        <td>{{$agent->returnAmount}}</td>
                    @endif
                    <td>{{$agent->approvedAmount+$agent->returnAmount}}</td>
                    {{-- <td><div id='jqxChart{{$agent->id}}' style="width: 40px; height: 40px;"></div>
                        <script>
                            var approved{{$agent->id}} = ({{$agent->approvalAmount}}/{{ $agent->submissionAmount }})*100;
                            approved{{$agent->id}} = {{$agent->approvalAmount}};
                            var data{{$agent->id}} = [
                                { Messages: "Approved", Share: approved{{$agent->id}} },
                                { Messages: "Submissions", Share: {{ $agent->submissionAmount }} }
                                // { Messages: "Submissions", Share: 100-approved{{$agent->id}} }
                            ]
                            var source{{$agent->id}} = {
                                datatype: "array",
                                datafields: [
                                    { name: 'Messages' },
                                    { name: 'Share' }
                                ],
                                localdata: data{{$agent->id}}
                            };
                            var dataAdapter{{$agent->id}} = new $.jqx.dataAdapter( source{{$agent->id}}, { async: false, autoBind: true,  loadError: function (xhr, status, error) {alert('Error loading "' + source{{$agent->id}}.url + '" : ' + error);}});

                            var settings{{$agent->id}} = {
                                title: "",
                                description: "",
                                enableAnimations: true,
                                showLegend: false,
                                legendPosition: { left: 520, top: 140, width: 100, height: 100 },
                                padding: { left: 1, top: 1, right: 1, bottom: 1 },
                                titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
                                source: dataAdapter{{$agent->id}},
                                colorScheme: 'scheme02',
                                seriesGroups: [
                                    {
                                        type: 'donut',
                                        showLabels: false,
                                        series:[{
                                            dataField: 'Share',
                                            displayText: 'Message',
                                            labelRadius: 100,
                                            initialAngle: 15,
                                            radius: 15,
                                            innerRadius: 0,
                                            centerOffset: 0,
                                            formatSettings: { sufix: '%', decimalPlaces: 1 }
                                        }]
                                    }
                                ]
                            };

                            $(document).ready(function() {
                                $('#jqxChart{{$agent->id}}').jqxChart(settings{{$agent->id}});
                                $('.jqx-chart-legend-text').hide();
                            });

                        </script>
                    </td> --}}
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
                <th>Transfers</th>
                <th>Attempted</th>
                <th>Callbacks</th>
                <th>Dropped</th>
                <th>Verified</th>
                <th>Un-Attempted</th>
                <th>Callbacks</th>
                <th>Dropped</th>
                <th>Fees</th>                
                <th>Approved</th>
                <th>Approved Amount</th>
                <th>Return Amount</th>
                <th>Net Amount</th>
                {{-- <th></th> --}}
              </tr>
            </thead>
            <tbody>
              @foreach ($closers as $closer)
                <tr>
                    <td>{{$closer->id}}</td>
                    <td>{{$closer->name}}</td>
                    <td>{{$closer->attemptscallback+$closer->attemptsdropped+$closer->attemptsverified+$closer->unattemptscallback+$closer->unattemptsdropped}}</td>
                    <td>{{$closer->attemptscallback+$closer->attemptsdropped+$closer->attemptsverified}}</td>
                    <td>{{$closer->attemptscallback}}</td>
                    <td>{{$closer->attemptsdropped}}</td>
                    <td>{{$closer->attemptsverified}}</td>
                    <td>{{$closer->unattemptscallback+$closer->unattemptsdropped}}</td>
                    <td>{{$closer->unattemptscallback}}</td>
                    <td>{{$closer->unattemptsdropped}}</td>
                    <td>{{$closer->fees}}</td>
                    <td>{{$closer->approved}}</td>
                    @if($closer->approvedAmount == null)
                        <td>0</td>
                    @else
                        <td>{{$closer->approvedAmount}}</td>
                    @endif
                    @if($closer->returnAmount == null)
                        <td>0</td>
                    @else
                        <td>{{$closer->returnAmount}}</td>
                    @endif
                    <td>{{$closer->approvedAmount+$closer->returnAmount}}</td>
                    {{-- <td><div id='jqxChart{{$closer->id}}' style="width: 40px; height: 40px;"></div>
                        <script>
                            var approved{{$closer->id}} = ({{$closer->approvalAmount}}/{{ $closer->submissionAmount }})*100;
                            var data{{$closer->id}} = [
                                { Messages: "Approved", Share: approved{{$closer->id}} },
                                { Messages: "Submissions", Share: 100-approved{{$closer->id}} }
                            ]
                            var source{{$closer->id}} = {
                                datatype: "array",
                                datafields: [
                                    { name: 'Messages' },
                                    { name: 'Share' }
                                ],
                                localdata: data{{$closer->id}}
                            };
                            var dataAdapter{{$closer->id}} = new $.jqx.dataAdapter( source{{$closer->id}}, { async: false, autoBind: true,  loadError: function (xhr, status, error) {alert('Error loading "' + source{{$closer->id}}.url + '" : ' + error);}});

                            var settings{{$closer->id}} = {
                                title: "",
                                description: "",
                                enableAnimations: true,
                                showLegend: false,
                                legendPosition: { left: 520, top: 140, width: 100, height: 100 },
                                padding: { left: 1, top: 1, right: 1, bottom: 1 },
                                titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
                                source: dataAdapter{{$closer->id}},
                                colorScheme: 'scheme02',
                                seriesGroups: [
                                    {
                                        type: 'donut',
                                        showLabels: false,
                                        series:[{
                                            dataField: 'Share',
                                            displayText: 'Message',
                                            labelRadius: 100,
                                            initialAngle: 15,
                                            radius: 15,
                                            innerRadius: 0,
                                            centerOffset: 0,
                                            formatSettings: { sufix: '%', decimalPlaces: 1 }
                                        }]
                                    }
                                ]
                            };

                            $(document).ready(function() {
                                $('#jqxChart{{$closer->id}}').jqxChart(settings{{$closer->id}});
                                $('.jqx-chart-legend-text').hide();
                            });

                        </script>
                    </td> --}}
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <!--  END of Closers -->
 <script>
$(document).ready(function(){

var table = $('#tableAgent').dataTable({
       "bAutoWidth" : false,
       "aLengthMenu": [[10, 50, -1], [10, 50, 100, "All"]],
       "pageLength": 15,
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
       "pageLength": 15,
       "order": [[ 0, "id" ]],
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


});

 </script>     
 

<script>

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
