@extends('layouts.app')

@section('content')

<div class="container-fluid">
    @if(session()->has('message'))
        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ session()->get('message') }}
        </div>
    @endif

    @if(session()->has('warning'))
        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ session()->get('warning') }}
        </div>
    @endif

  
    <h2>{{ $user->name }}'s Monthly Statistics</h2>
    
    <hr>
    
    <div class="viewUserDetails">
        <div class="col-md-2 text-center">
            <div class="messageHead"> Transfers </div>
            <div class="messageContent"><h3>{{ $user->transfers }}</h3></div>
        </div>
        <div class="col-md-2 text-center">
            <div class="messageHead"> Callbacks </div>
            <div class="messageContent"><h3>{{ $user->allowcallback }}</h3></div>
        </div>
        <div class="col-md-2 text-center">
            <div class="messageHead"> Attempts </div>
            <div class="messageContent"><h3>{{ $user->attempts }}</h3></div>
        </div>
        <div class="col-md-3 text-center">
            <div class="messageHead"> No. of Subimssions </div>
            <div class="messageContent"><h3>{{ $user->submissions }}</h3></div>
        </div>
        <div class="col-md-3 text-center">
            <div class="messageHead"> No. of Approvals </div>
            <div class="messageContent"><h3>{{ $user->approvals }}</h3></div>
        </div>

        <hr class="col-md-12">

        <div class="col-md-5 text-center">
            <div class="messageHead" style="background:#FE5381"> Submission Amount </div>
            <div class="messageContent"><h3>{{ $user->submissionAmount }}</h3></div>
        </div>
        <div class="col-md-5 text-center">
            <div class="messageHead"style="background:#A1FD63; color:black;"> Approval Amount </div>
            <div class="messageContent"><h3>{{ $user->approvalAmount }}</h3></div>
        </div>
        <div class="col-md-2">
            <div> <div id='jqxChart' style="width: 100px; height: 100px;"></div> </div>
        </div>
    </div>

    <hr class="col-md-12">

    <table id="userMessageTable" class="display table">
        <thead>
            <tr>
                <th>Closer</th>
                <th>Customername</th>
                <th>Fee</th>
                <th>Status</th>
                <th>Send as Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($messages as $message)
                <tr id="tr{{$message->id}}">
                    <td>{{$message->closer}}</td>
                    <td>{{$message->customername}}</td>
                    <td>{{$message->fees}}</td>
                    <td>{{$message->status}}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Send
                            <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                            <li class="disabled"><a href="#">Select Merchant</a></li>
                            @foreach ($allMerchants as $merchant)
                                <li><a href="/{{$merchant->id}}"><b>{{$merchant->name}}</b></a></li>
                            @endforeach
                            </ul>
                        </div>
                    </td>
                    <td>
                        <a href="{{ url('admin/showMessage/'.$message->id) }}" class="btn btn-primary">Show</a>
                        <a href="{{ url('admin/editMessage/'.$message->id) }}" class="btn btn-info">Edit</a>
                        <button id="{{$message->id}}" type="button" name="delete_message" class="btn btn-danger">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Closer</th>
                <th>Customername</th>
                <th>Fee</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>

    <hr class="col-md-12">

</div>

</div>

<!-- JQWidgets SCRIPTS and CSS-->
<link rel="stylesheet" href="{{ asset('css/jqwidgets/jqx.base.css') }}" type="text/css" />
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxcore.js') }}"></script>
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxchart.js') }}"></script>
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxchart.core.js') }}"></script>
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxdata.js') }}"></script>
<script>
    var data = [
        { Messages: "Approved", Share: {{$user->approvalAmount}} },
        { Messages: "Submitted", Share: {{$user->submissionAmount}} }
    ]

    var source = {
        datatype: "array",
        datafields: [
            { name: 'Messages' },
            { name: 'Share' }
        ],
        localdata: data
    };
    var dataAdapter = new $.jqx.dataAdapter(source, { async: false, autoBind: true, loadError: function (xhr, status, error) { alert('Error loading "' + source.url + '" : ' + error); } });

    var settings = {
		title: "",
		description: "",
		enableAnimations: true,
		showLegend: false,
		legendPosition: { left: 520, top: 140, width: 100, height: 100 },
		padding: { left: 1, top: 1, right: 1, bottom: 1 },
		titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
		source: dataAdapter,
		colorScheme: 'scheme02',
		seriesGroups:
			[
				{
					type: 'donut',
					showLabels: false,
					series:
						[
							{
								dataField: 'Share',
								displayText: 'Message',
								labelRadius: 100,
								initialAngle: 15,
								radius: 40,
								innerRadius: 0,
								centerOffset: 0,
								formatSettings: { sufix: '%', decimalPlaces: 1 }
							}
						]
				}
			]
    };
    
    $(document).ready(function() {
        $('#jqxChart').jqxChart(settings);  
        $('.jqx-chart-legend-text').hide();
    });

</script>

@endsection
