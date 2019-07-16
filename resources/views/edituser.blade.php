@extends('layouts.app')

@section('content')

<div class="col-md-8 col-md-offset-2">
  <hr>
    <div class="panel panel-primary">
        <div class="panel-heading text-center">UPDATE USER</div>

        <div class="panel-body">
            <form method="POST" action="{{ route('updateUser') }}">
                <div class="form-group">
                    <label for="username">Username :</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{$user->name}}" required>
                </div>
                <div class="form-group">
                    <label for="password">Password :</label>
                    <input type="text" class="form-control" id="password" name="password" value="{{$user->showpass}}" required>
                </div>
                <div class="form-group">
                    <label for="basic-salary">Basic Salary :</label>
                    <input type="text" class="form-control" id="basic-salary" name="basicSalary" value="{{$user->basicSalary}}" required>
                </div>
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $user->id }}">
                <input type="submit" value="Submit" class="btn btn-primary">
            </form>
        </div>

    </div>
</div>

<!-- End page content -->
</div>
@endsection
