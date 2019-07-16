@extends('layouts.app')

@section('content')

<h3 class="text-center">Monthly Salary Statistics</h3>

<!-- JQWidgets SCRIPTS and CSS-->
<link rel="stylesheet" href="{{ asset('css/jqwidgets/jqx.base.css') }}" type="text/css" />
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxcore.js') }}"></script>
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxchart.js') }}"></script>
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxchart.core.js') }}"></script>
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxdata.js') }}"></script>

    <div>



    </div>

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
                <th>Callbacks</th>
                <th>Attempts</th>
                <th>No. of Subimssions</th>
                <th>No. of Approvals</th>
                <th style="background:#FE5381; color:white;">$Submission</th>
                <th style="background:#A1FD63;">$Approve</th>
                <th>Basic Salary</th>
                <th>Commission</th>
                <th>Total Salary</th>
                <th>Ratio</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($agents as $agent)
                <tr>
                    <td>{{$agent->id}}</td>
                    <td>{{$agent->name}}</td>
                    <td>{{$agent->transfers}}</td>
                    <td>{{$agent->allowcallback}}</td>
                    <td>{{$agent->attempts}}</td>
                    <td>{{$agent->submissions}}</td>
                    <td>{{$agent->approvals}}</td>
                    <td>{{$agent->submissionAmount}}</td>
                    <td>{{$agent->approvalAmount}}</td>
                    <td>{{$agent->basicSalary}}</td>
                    <td>{{$agent->commission}}</td>
                    <td>{{$agent->totalSalary}}</td>
                    <td><div id='jqxChart{{$agent->id}}' style="width: 40px; height: 40px;"></div>
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
                    </td>
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
                <th>Callbacks</th>
                <th>Attempts</th>
                <th>No. of Subimssions</th>
                <th>No. of Approvals</th>
                <th style="background:#FE5381; color:white;">$Submission</th>
                <th style="background:#A1FD63;">$Approve</th>
                <th>Basic Salary</th>
                <th>Commission</th>
                <th>Total Salary</th>
                <th>Ratio</th>

              </tr>
            </thead>
            <tbody>
              @foreach ($closers as $closer)
                <tr>
                    <td>{{$closer->id}}</td>
                    <td>{{$closer->name}}</td>
                    <td>{{$closer->transfers}}</td>
                    <td>{{$closer->allowcallback}}</td>
                    <td>{{$closer->attempts}}</td>
                    <td>{{$closer->submissions}}</td>
                    <td>{{$closer->approvals}}</td>
                    <td>{{$closer->submissionAmount}}</td>
                    <td>{{$closer->approvalAmount}}</td>
                    <td>{{$closer->basicSalary}}</td>
                    <td>{{$closer->commission}}</td>
                    <td>{{$closer->totalSalary}}</td>


                    <td><div id='jqxChart{{$closer->id}}' style="width: 40px; height: 40px;"></div>
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
                    </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <!--  END of Closers -->
    </div>


<script type="text/javascript">

  $(document).ready(function() {
    $('#tableAgent').DataTable( {
        "scrollX": true,
        "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
        "pageLength": 25,
        "order": [[ 0, "id" ]],
        dom: 'Bfrtip',
        buttons: [
           {
             extend: 'pdf',
             orientation: 'landscape',
             title: 'AgentsSalary'+' '+new Date().toISOString().split('T')[0],
             pageSize: 'A4',
             customize: function (doc) { doc.defaultStyle.fontSize = 12;  doc.styles.tableHeader.fontSize = 12; },
             exportOptions: {
                 columns: 'th:not(:last-child)'
             }
          },
          {
              extend: 'csv',
              title: 'AgentsSalary'+' '+new Date().toISOString().split('T')[0],
              exportOptions: {
                  columns: 'th:not(:last-child)'
              }

          },
          {
              extend: 'excel',
              title: 'AgentsSalary'+' '+new Date().toISOString().split('T')[0],
              exportOptions: {
                  columns: 'th:not(:last-child)'
              }
          }
      ]
    } );
  } );

  $(document).ready(function() {
      $('#tableCloser').DataTable( {
        "scrollX": true,
        "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
        "pageLength": 25,
        "order": [[ 0, "id" ]],
        dom: 'Bfrtip',
        buttons: [
           {
             extend: 'pdf',
             orientation: 'landscape',
             pageSize: 'A4',
             title: 'ClosersSalary'+' '+new Date().toISOString().split('T')[0],
             customize: function (doc) { doc.defaultStyle.fontSize = 12;  doc.styles.tableHeader.fontSize = 12; },
             exportOptions: {
                 columns: 'th:not(:last-child)'
             }
          },
          {
              extend: 'csv',
              title: 'ClosersSalary'+' '+new Date().toISOString().split('T')[0],
              exportOptions: {
                  columns: 'th:not(:last-child)'
              }

          },
          {
              extend: 'excel',
              title: 'ClosersSalary'+' '+new Date().toISOString().split('T')[0],
              exportOptions: {
                  columns: 'th:not(:last-child)'
              }
          }
      ]
      } );
    } );
</script>
@endsection
