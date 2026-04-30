@extends('layout')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card border-0 shadow-sm p-4">

            <h5 class="mb-3 fw-semibold">Add User</h5>

            <form method="POST" action="/users">
                @csrf

                <!-- NAME -->
                <div class="mb-3">
                    <label class="form-label text-muted">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <!-- EMAIL -->
                <div class="mb-3">
                    <label class="form-label text-muted">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <!-- PHONE -->
                <div class="mb-3">
                    <label class="form-label text-muted">Phone</label>
                    <input type="text" name="phone" class="form-control" placeholder="Enter phone number">
                </div>

                <!-- PASSWORD -->
                <div class="mb-4">
                    <label class="form-label text-muted">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <!-- BUTTONS -->
                <div class="d-flex justify-content-between">
                    <a href="/users" class="btn btn-light border">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-warning px-4">
                        Save
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection