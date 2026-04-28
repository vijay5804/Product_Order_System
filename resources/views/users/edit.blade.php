@extends('layout')

@section('content')

<h3>Edit User</h3>

<form method="POST" action="/users/{{ $user->id }}">
@csrf
@method('PUT')

<input name="name" value="{{ $user->name }}" class="form-control mb-2">
<input name="email" value="{{ $user->email }}" class="form-control mb-2">
<input name="password" placeholder="New Password" class="form-control mb-2">

<button class="btn btn-warning">Update User</button>

</form>

@endsection