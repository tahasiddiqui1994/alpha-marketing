@extends('layouts.app')

@section('content')

<h3 class="text-center">User Statistics</h3>



<!-- JQWidgets SCRIPTS and CSS-->
<link rel="stylesheet" href="{{ asset('css/jqwidgets/jqx.base.css') }}" type="text/css" />
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxcore.js') }}"></script>
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxchart.js') }}"></script>
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxchart.core.js') }}"></script>
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxdata.js') }}"></script>

      <div class="panel panel-primary">
        <div class="panel-heading">users</div>
        <div class="panel-body">
          <table id="userTable" class="table">
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
                {{-- <th></th> --}}
              </tr>
            </thead>
            <tbody>
              
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->attemptscallback+$user->attemptsdropped+$user->attemptsverified+$user->unattemptscallback+$user->unattemptsdropped}}</td>
                    <td>{{$user->attemptscallback+$user->attemptsdropped+$user->attemptsverified}}</td>
                    <td>{{$user->attemptscallback}}</td>
                    <td>{{$user->attemptsdropped}}</td>
                    <td>{{$user->attemptsverified}}</td>
                    <td>{{$user->unattemptscallback+$user->unattemptsdropped}}</td>
                    <td>{{$user->unattemptscallback}}</td>
                    <td>{{$user->unattemptsdropped}}</td>
                    <td>{{$user->fees}}</td>
                    <td>{{$user->approved}}</td>
                    <td>{{$user->approvalAmount}}</td>
                    {{-- <td><div id='jqxChart{{$user->id}}' style="width: 40px; height: 40px;"></div>
                        <script>
                            var approved{{$user->id}} = ({{$user->approvalAmount}}/{{ $user->submissionAmount }})*100;
                            approved{{$user->id}} = {{$user->approvalAmount}};
                            var data{{$user->id}} = [
                                { Messages: "Approved", Share: approved{{$user->id}} },
                                { Messages: "Submissions", Share: {{ $user->submissionAmount }} }
                                // { Messages: "Submissions", Share: 100-approved{{$user->id}} }
                            ]
                            var source{{$user->id}} = {
                                datatype: "array",
                                datafields: [
                                    { name: 'Messages' },
                                    { name: 'Share' }
                                ],
                                localdata: data{{$user->id}}
                            };
                            var dataAdapter{{$user->id}} = new $.jqx.dataAdapter( source{{$user->id}}, { async: false, autoBind: true,  loadError: function (xhr, status, error) {alert('Error loading "' + source{{$user->id}}.url + '" : ' + error);}});

                            var settings{{$user->id}} = {
                                title: "",
                                description: "",
                                enableAnimations: true,
                                showLegend: false,
                                legendPosition: { left: 520, top: 140, width: 100, height: 100 },
                                padding: { left: 1, top: 1, right: 1, bottom: 1 },
                                titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
                                source: dataAdapter{{$user->id}},
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
                                $('#jqxChart{{$user->id}}').jqxChart(settings{{$user->id}});
                                $('.jqx-chart-legend-text').hide();
                            });

                        </script>
                    </td> --}}
                </tr>
            
            </tbody>
          </table>
        </div>
      </div>
      

 <script type="text/javascript">

    $(document).ready(function() {
      $('#userTable').DataTable( {
      searching: false,
      paging: false,
      info: false,
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

@endsection
