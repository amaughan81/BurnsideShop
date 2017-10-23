@extends('layouts.app')

@section('content')
<h1>User Management</h1>
<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li class="active">User Management</li>
</ol>
    <table class="table table-striped" id="users-tbl">
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Role</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
             <tr>
                 <td>
                     <a class="btn btn-warning btn-sm" href="/users/{{ $user['id'] }}/edit">
                        <span class="glyphicon glyphicon-pencil"></span>
                     </a>
                 </td>
                 <td>{{ $user->forename.' '.$user->surname }}</td>
                 <td>{{ $user->role }}</td>
                 <td>{{ $user->username }}@burnsidecollege.org.uk</td>
             </tr>
            @endforeach
        </tbody>
    </table>

@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            $('#users-tbl').DataTable({
                "pageLength": 50
            });
        });
    </script>
@endsection

@section('css')
    <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet">
@endsection