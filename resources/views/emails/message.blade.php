<!DOCTYPE html>
<html>
<head>
	<title>Send Email</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
</head>
<body style="color:black;">
	<div style="
	position: relative;
    padding:0 !important;
    margin-top:70px !important;
    background: #eee;
    margin-top: 15px;
    text-align:left;
    margin-bottom: 0 !important;
    white-space: pre-line;">   
    	<div style="padding:15px;">
        	<h2>Hi,</h2>
        	<p class="font-weight-bold font-italic">Customer Name: <b>{{$customerName}}</b></p>
        	<p class="font-weight-bold font-italic lead">Details: <b><i>{{$messageBody}}</i></b></p>
        	<p class="font-weight-bold font-italic">Fees: <b>{{$fees}}</b></p>
    	</div>
	</div>
</body>
</html>
