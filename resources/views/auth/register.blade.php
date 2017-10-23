@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('auth') ? ' has-error' : '' }}">
                                <label for="auth" class="col-md-4 control-label">Auth</label>

                                <div class="col-md-6">
                                    <select id="auth" type="text" class="form-control" name="auth" required autofocus>
                                        <option value="ldap">LDAP</option>
                                        <option value="manual">Manual</option>
                                    </select>
                                    @if ($errors->has('auth'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('auth') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('forename') ? ' has-error' : '' }}">
                                <label for="forename" class="col-md-4 control-label">Forename</label>

                                <div class="col-md-6">
                                    <input id="forename" type="text" class="form-control" name="forename" value="{{ old('forename') }}" required autofocus>

                                    @if ($errors->has('forename'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('forename') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }}">
                                <label for="forename" class="col-md-4 control-label">Surname</label>

                                <div class="col-md-6">
                                    <input id="surname" type="text" class="form-control" name="surname" value="{{ old('surname') }}" required autofocus>

                                    @if ($errors->has('surname'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('surname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                                <label for="forename" class="col-md-4 control-label">Gender</label>

                                <div class="col-md-6 btn-group" data-toggle="buttons">
                                    <label class="btn btn-default">
                                        Female: <input id="gender_f" type="radio" name="gender" value="F">
                                    </label>
                                    <label class="btn btn-default">
                                        Male: <input id="gender_m" type="radio" name="gender" value="M">
                                    </label>

                                    @if ($errors->has('gender'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Role</label>

                                <div class="col-md-6">
                                    <select id="role" type="text" class="form-control" name="role" required autofocus>
                                        <option value="staff">Staff</option>
                                        <option value="priv_user">Privileged User</option>
                                        <option value="ls_admin">LS Admin</option>
                                        <option value="slt">SLT</option>
                                        <option value="admin">Admin</option>
                                        <option value="super_admin">Super Admin</option>
                                    </select>
                                    @if ($errors->has('role'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('role') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="username" class="col-md-4 control-label">Username</label>

                                <div class="col-md-6">
                                    <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required>

                                    @if ($errors->has('username'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
                                <label for="forename" class="col-md-4 control-label">Active</label>

                                <div class="col-md-6 btn-group" data-toggle="buttons">
                                    <label class="btn btn-default">
                                        Yes <input id="active_y" type="radio" name="active" value="1">
                                    </label>
                                    <label class="btn btn-default">
                                        No <input id="active_n" type="radio" name="active" value="0" checked="chec]">
                                    </label>

                                    @if ($errors->has('gender'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
