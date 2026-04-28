@extends('layout')

@section('content')
<div class="container mt-4">
    <div class="card shadow p-4 border-0 rounded-4">
        <h3 class="mb-3 fw-bold text-dark">Add User</h3>

        <a href="/users" class="btn btn-secondary btn-sm mb-3 rounded-pill px-3">
            <i class="bi bi-arrow-left"></i> Back to Users
        </a>

        <form id="userForm">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter name" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>

            <button type="submit" class="btn btn-warning w-100 fw-bold shadow-sm">
                Add User
            </button>
        </form>
    </div>
</div>
@endsection