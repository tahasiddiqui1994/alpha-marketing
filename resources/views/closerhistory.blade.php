@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

      @if(session()->has('message'))
      <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          {{ session()->get('message') }}
      </div>
      @endif

      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">CLOSER History</div>

        </div>
      </div>

    </div>
</div>
@endsection
