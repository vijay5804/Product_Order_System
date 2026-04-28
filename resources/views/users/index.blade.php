@extends('layout')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Users</h3>

  <button class="btn btn-warning rounded-pill px-4 shadow-sm"
    data-bs-toggle="modal"
    data-bs-target="#addUserModal">
    + Add User
</button>
</div>

<div class="card border-0 shadow rounded-4">
    <div class="card-body p-0">

        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Name</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($users as $user)
                <tr class="hover-row">

                    <td class="ps-4 fw-semibold">{{ $user->name }}</td>

                    <!-- ✅ CENTER EMAIL -->
                    <td class="text-center text-muted">{{ $user->email }}</td>

                    <td class="text-center">
                        <button onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')" 
                        class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            Edit
                        </button>

                        <button onclick="deleteUser({{ $user->id }}, this)" 
                        class="btn btn-sm btn-outline-danger rounded-pill px-3">
                            Delete
                        </button>
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>

    </div>
</div>

<!-- 🔥 ADD USER MODAL -->
<div class="modal fade" id="addUserModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">

      <div class="modal-header border-0">
        <h5 class="fw-bold">Add User</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="userForm">
            @csrf

            <input type="text" name="name" class="form-control mb-3" placeholder="Enter name" required>
            <input type="email" name="email" class="form-control mb-3" placeholder="Enter email" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Enter password" required>
        </form>
      </div>

      <div class="modal-footer border-0">
        <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button onclick="submitUser()" class="btn btn-warning px-4">Create</button>
      </div>

    </div>
  </div>
</div>

@endsection