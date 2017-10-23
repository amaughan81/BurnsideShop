@extends('layouts.app')

@section('content')
<h1>Edit Account</h1>
<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li><a href="/users">User Management</a></li>
    <li class="active">Edit User</li>
</ol>
@include('flash::message')

    <form class="row" method="post" action="/users/{{ $user->id }}">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
        <div class="col-md-6">
            <div class="form-group">
                <label for="role">Role:</label>
                <select class="form-control" id="auth" name="auth" required>
                    <option value="ldap"@if($user->auth == "ldap") selected @endif>LDAP</option>
                    <option value="manual"@if($user->auth == "manual") selected @endif>Manual</option>
                </select>
            </div>
            <div class="form-group">
                <label>Forename:</label>
                <input type="text" name="forename" id="forename" value="{{ $user->forename }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Forename:</label>
                <input type="text" name="surname" id="surname" value="{{ $user->surname }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="student"@if($user->role == "student") selected @endif>Student</option>
                    <option value="manager"@if($user->role == "manager") selected @endif>Shop Manager</option>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-primary">
                    Save
                </button>
            </div>
        </div>

    </form>
@endsection