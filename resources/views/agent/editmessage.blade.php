@extends('layouts.app')

@section('content')
<div class="container" >
    <div class="row">

      @if(session()->has('message'))
      <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          {{ session()->get('message') }}
      </div>
      @endif

      <form method="post" action="{{url('/updateMessage')}}">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-primary">
            <div class="panel-heading" style="height: 46px;">
              <div class="col-md-12">
                <div class="col-md-9">Edit Message</div>

                
              </div>
            </div>

            <div class="panel-body">

                <div class="form-group">
                  <label for="customername">Customer Name:</label>
                  <input type="text" class="form-control" id="customername" name="customername" value="{{ $message->customername }}" readonly>
                </div>

                <div class="form-group">
                  <label for="message">Enter Details:</label>
                 
                  @if( Auth::user()->roles->first()->name == 'closer' && ((strcmp($message->allowcallback, 'agent/closer') == 0)|| (strcmp($message->allowcallback, 'closer') == 0)) )  
                    <textarea class="form-control" rows="20" id="message" name="message" required readonly>{{ $message->text }}</textarea>
                  @elseif(Auth::user()->roles->first()->name == 'agent' && (strcmp($message->allowcallback, 'agent') == 0))
                    <textarea class="form-control" rows="20" id="message" name="message" required readonly>{{ $message->text }}</textarea>
                  @else
                    <textarea class="form-control" rows="20" id="message" name="message" required >{{ $message->text }}</textarea>
                  @endif
                 
                  {{ csrf_field() }}
                </div>

                <div class="form-group col-md-6">
                  <label for="closer">Closer: </label>
                  <input class="form-control" id="closer" name="closer" value="{{ $message->closer }}" readonly>
                </div>
                <div class="form-group col-md-3">
                    <label for="status">Status:</label>
                    <select class="form-control" id="status" name="status">
                        <option @if( strcmp($message->status, 'Submit') == 0) {{ 'selected' }} @endif value="Submit">Verified</option>
                        <option @if( strcmp($message->status, 'Dropped') == 0) {{ 'selected' }} @endif value="Dropped">Dropped</option>
                        @if( Auth::user()->roles->first()->name == 'agent' && strcmp($message->status, 'RoughCall') == 0)
                            <option @if( strcmp($message->status, 'RoughCall') == 0) {{ 'selected' }} @endif value="RoughCall">Rough CallBack</option> 
                        @else
                            <option @if( strcmp($message->status, 'CallBack') == 0) {{ 'selected' }} @endif value="CallBack">CallBack</option> 
                        @endif
                        <option @if( strcmp($message->status, 'RNA') == 0) {{ 'selected' }} @endif value="RNA">RNA</option>
                        <option @if( strcmp($message->status, 'BankAuth') == 0) {{ 'selected' }} {{ 'disabled' }} @endif value="BankAuth">Bank Auth</option>
                        @if( strcmp($message->status, 'BankAuth') == 0)
                          <option value="Still BankAuth">Still Bank Auth</option>
                          <option value="BankAuth Removed">Bank Auth Removed</option>
                        @endif
                    </select>
                </div>
                <div class="form-group col-md-3">
                  <label for="fee">Fee :</label>
                  @if( Auth::user()->roles->first()->name == 'closer' && ((strcmp($message->allowcallback, 'agent/closer') == 0)|| (strcmp($message->allowcallback, 'closer') == 0)) )  
                    <input type="number" class="form-control" id="fee" name="fee" value="{{ $message->fees }}" readonly>
                  @elseif(Auth::user()->roles->first()->name == 'agent' && (strcmp($message->allowcallback, 'agent') == 0))
                    <input type="number" class="form-control" id="fee" name="fee" value="{{ $message->fees }}" readonly>
                  @else
                    <input type="number" class="form-control" id="fee" name="fee" value="{{ $message->fees }}" required >
                  @endif        
                </div>
                <input type="hidden" name="id" value="{{ $message->id }}">
                 
                 <div class="form-group">
                  <label for="password">Enter Password :</label>
                  <input type="password" class="form-control" id="password" name="password" required>
                </div>
                 <div id="dateDiv" class="col-md-offset-4 col-md-5" style="display:none;">
                     <label for="">Date/Time:</label>
                       <input style="border-radius: 5px; font-size: 20px; border:1px solid #28a745;" class="form-control" type="text" name="Date" value="<?php echo date('Y-m-d h:i:sa'); ?>" placeholder="Enter Date..." />
                       <p class="help-block" style="font-weight:bold;">From Date</p>
                 </div>
                 @if($message->note != null)
                    <textarea class="form-control" name="note" rows="4" cols="50">{{$message->note}}</textarea>
                 @else
                    <textarea class="form-control" name="note" rows="4" cols="50"></textarea>
                 @endif
                <button type='submit' class='btn btn-block btn-success' style="margin-top:20px;">Submit Message</button>

            </div>
        </div>
      </div>
    </form>

    </div>
    <script>
     
  $(function() {
    $('input[name="Date"]').daterangepicker({
    //   startDate: moment().startOf('hour'),
      timePicker: true,
      singleDatePicker: true,
      showDropdowns: false,
      drops:'up',
      locale: {
        format: 'YYYY/M/DD hh:mm:ss A'
     }
    });
  });
  $('#status').on('change', function() {
    if(this.value == 'CallBack' || this.value =='RoughCall'){
      $('#dateDiv').show();
    }else{
      $('#dateDiv').hide();
    }
  });
    </script>
</div>
@endsection
