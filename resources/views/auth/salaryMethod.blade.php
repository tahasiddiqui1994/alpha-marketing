@extends('layouts.app')

@section('content')



<!-- JQWidgets SCRIPTS and CSS-->
<link rel="stylesheet" href="{{ asset('css/jqwidgets/jqx.base.css') }}" type="text/css" />
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxcore.js') }}"></script>
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxchart.js') }}"></script>
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxchart.core.js') }}"></script>
<script type="text/javascript"  src="{{ asset('js/jqwidgets/jqxdata.js') }}"></script>

<div class="row">
  <div class="col-md-offset-2 col-md-8">
    <div class="panel panel-primary">
      <div class="panel-heading">Salary Method</div>
      <div class="panel-body text-center" >
        <div class="row">
          <button class="btn btn-info btn-md" type="button" data-toggle="modal" data-target="#approvalModal">Calculation on the basis of Approval</button>
        </div>
        <hr>
        <div class="row">
          <button class="btn btn-warning btn-md" type="button" name="button" data-toggle="modal" data-target="#submissionModal">Calculation on the basis of Submission</button>
        </div>
        <hr>
        <div class="row">
          <button class="btn btn-success btn-md" type="button">Not Yet Confirmed</button>
        </div>
        <hr>
        <div class="row">
          <button class="btn btn-primary btn-md" type="button" name="button">Not Yet Confirmed</button>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Approval-Input Modal -->
<div class="modal fade" id="approvalModal" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Approval Method</h4>
      </div>
      <div class="modal-body">
        <form action="{{route('salaryMethodApproval')}}" method="post">
          {{ csrf_field() }}
          <div class="form-group">
            <label for="">Enter Commision Percentage : </label>
            <input type="number" class="form-control" name="percentage" step="0.1" required placeholder="Enter in percentage (75)...">
            <p class="help-block">*Percentage of total approval amount to be given as commision</p>
          </div>

          <button type="submit" name="button" class="btn btn-success btn-block">Proceed</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--End of Model>

 <!-- Submission-Input Modal -->
<div class="modal fade" id="submissionModal" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Submission Method</h4>
      </div>
      <div class="modal-body">
        <form action="{{route('salaryMethodSubmission')}}" method="post">
          {{ csrf_field() }}

          <div class="">
            <label for="">Enter Range For Brackets 1 :</label>
            <div class="form-group">
              <div class="col-md-4">
                  <label for="">Min</label>
                  <input type="number" class="form-control" name="rangeMin1" required>
              </div>
              <div class="col-md-4">
                <label for="">Max</label>
                <input type="number" class="form-control" name="rangeMax1" required>
              </div>
              <div class="col-md-4">
                <label for="">Enter Commision % : </label>
                <input type="number" class="form-control" name="percentage1" required placeholder="Enter in percentage (75)...">
              </div>
            </div>
            <p class="help-block">*Range for Bracket: 1</p>
          </div>

          <div class="">
            <label for="">Enter Range For Brackets 2 :</label>
            <div class="form-group">
              <div class="col-md-4">
                  <label for="">Min</label>
                  <input type="number" class="form-control" name="rangeMin2" required>
              </div>
              <div class="col-md-4">
                <label for="">Max</label>
                <input type="number" class="form-control" name="rangeMax2" required>
              </div>
              <div class="col-md-4">
                <label for="">Enter Commision % : </label>
                <input type="number" class="form-control" name="percentage2" required placeholder="Enter in percentage (75)...">
              </div>
            </div>
            <p class="help-block">*Range for Bracket: 2</p>
          </div>

          <div class="">
            <label for="">Enter Range For Brackets 3 :</label>
            <div class="form-group">
              <div class="col-md-4">
                  <label for="">Min</label>
                  <input type="number" class="form-control" name="rangeMin3" required>
              </div>
              <div class="col-md-4">
                <label for="">Max</label>
                <input type="number" class="form-control" name="rangeMax3" required>
              </div>
              <div class="col-md-4">
                <label for="">Enter Commision % : </label>
                <input type="number" class="form-control" name="percentage3" required placeholder="Enter in percentage (75)...">
              </div>
            </div>
            <p class="help-block">*Range for Bracket: 3</p>
          </div>

          <div class="">
            <label for="">Enter Range For Brackets 4 :</label>
            <div class="form-group">
              <div class="col-md-4">
                  <label for="">Min</label>
                  <input type="number" class="form-control" name="rangeMin4" required>
              </div>
              <div class="col-md-4">
                <label for="">Max</label>
                <input type="number" class="form-control" name="rangeMax4" required>
              </div>
              <div class="col-md-4">
                <label for="">Enter Commision % : </label>
                <input type="number" class="form-control" name="percentage4" required placeholder="Enter in percentage (75)...">
              </div>
            </div>
            <p class="help-block">*Range for Bracket: 4</p>
          </div>

          <div class="">
            <label for="">Enter Range For Brackets 5 :</label>
            <div class="form-group">
              <div class="col-md-4">
                  <label for="">Min</label>
                  <input type="number" class="form-control" name="rangeMin5" required>
              </div>
              <div class="col-md-4">
                <label for="">Max</label>
                <input type="number" class="form-control" name="rangeMax5" required>
              </div>
              <div class="col-md-4">
                <label for="">Enter Commision % : </label>
                <input type="number" class="form-control" name="percentage3" required placeholder="Enter in percentage (75)...">
              </div>
            </div>
            <p class="help-block">*Range for Bracket: 5</p>
          </div>

          <button type="submit" name="button" class="btn btn-success btn-block">Proceed</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--End of Model>
@endsection
