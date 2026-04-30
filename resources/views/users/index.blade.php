@extends('layout')

@section('content')

<div class="row mb-4">
    <div class="col-md-6">
        <h4 class="mb-0">User Management</h4>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus"></i> Add User
        </button>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped table-bordered mb-0">
            <thead class="table-secondary">
                <tr>
                    <th width="70">S.No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th width="180">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <!-- Inga thaan Serial Number automatic ah calculate aagum -->
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <button onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')" 
                                class="btn btn-primary btn-sm">
                            Edit
                        </button>
                        <button onclick="deleteUser({{ $user->id }}, this)" 
                                class="btn btn-danger btn-sm">
                            Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter name">
                    </div>
                    <div class="mb-3">
                        <label>Email ID</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter email">
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter password">
                    </div>
                    <button type="button" onclick="submitUser()" class="btn btn-warning w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title">Update User Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <input type="hidden" id="edit_id">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" id="edit_name" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" id="edit_email" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Change Password (Optional)</label>
                    <input type="password" id="edit_password" class="form-control" placeholder="Type new password">
                </div>
                <button type="button" onclick="updateUser()" class="btn btn-warning w-100">Update Changes</button>
            </div>
        </div>
    </div>
</div>

@endsection