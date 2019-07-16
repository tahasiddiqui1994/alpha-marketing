@extends('layouts.app')

@section('content')

<div class="col-md-8 col-md-offset-2">
  <hr>
    <div class="panel panel-primary">
        <div class="panel-heading text-center">UPDATE USER</div>

        <div class="panel-body">
            <form method="POST" action="{{ route('updateAdmin') }}">
                <div class="form-group">
                    <label for="username">Username :</label>
                    <input type="text" class="form-control" id="username" name="email" value="{{$admin->email}}" required>
                </div>
                <div class="form-group">
                    <label for="password">Password :</label>
                    <input type="text" class="form-control" id="password" name="password" value="">
                </div>
                <div class="form-group">
                    <label for="superadmin">Super Admin Password:</label>
                    <input type="password" class="form-control" id="superadminpassword" name="superadminpassword" value="" required>
                </div>
                {{ csrf_field() }}
                <input type="hidden" name="adminID" value="{{ $admin->id }}">
                <input type="submit" value="Submit" class="btn btn-primary">
            </form>
        </div>

    </div>
</div>

<!-- End page content -->
</div>
@endsection