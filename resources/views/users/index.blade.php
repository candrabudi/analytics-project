@extends('layouts.app')

@section('content')
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">Manajemen Pengguna</h1>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                Buat Pengguna
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div id="alert-container" class="mb-4">

            </div>

            <div class="table-responsive mb-4">
                <table class="table text-nowrap">
                    <thead>
                        <tr>
                            <th scope="col">Username</th>
                            <th scope="col">Tanggal Dibuat</th>
                            <th scope="col">Role</th>
                            <th scope="col" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-body">
                        <div id="loading" style="display: none;">
                            <p>Loading...</p>
                        </div>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createUserForm">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="admin">Admin</option>
                                <option value="kol">Kol</option>
                                <option value="advertiser">Advertiser</option>
                                <option value="user" selected>User</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateUserForm">
                        @csrf
                        <input type="hidden" id="update-user-id">
                        <div class="mb-3">
                            <label for="update-username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="update-username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="update-password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="update-password" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="update-role" class="form-label">Role</label>
                            <select class="form-select" id="update-role" name="role">
                                <option value="admin">Admin</option>
                                <option value="kol">Kol</option>
                                <option value="advertiser">Advertiser</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this user?</p>
                    <input type="hidden" id="delete-user-id">
                    <button type="button" class="btn btn-danger" id="delete-user-btn">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            function checkLogin() {
                var loggedIn = false;
                $.ajax({
                    url: '/check-login',
                    method: 'GET',
                    async: false,
                    success: function(response) {
                        loggedIn = response.loggedIn;
                    },
                    error: function(xhr, status, error) {
                        loggedIn = false;
                    }
                });
                return loggedIn;
            }

            $(document).on('click', '.edit-btn', function() {
                if (!checkLogin()) {
                    alert('Anda harus login terlebih dahulu!');
                    window.location.href = '/login';
                    return;
                }

                var userId = $(this).data('user-id');
                $.ajax({
                    url: '/users/' + userId,
                    method: 'GET',
                    success: function(response) {
                        $('#update-user-id').val(response.id);
                        $('#update-username').val(response.username);
                        $('#update-role').val(response.role);
                        $('#updateModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        alert('Error fetching user data');
                    }
                });
            });

            $(document).on('click', '.delete-btn', function() {
                if (!checkLogin()) {
                    alert('Anda harus login terlebih dahulu!');
                    window.location.href = '/login';
                    return;
                }

                var userId = $(this).data('user-id');
                $('#delete-user-id').val(userId);
                $('#deleteModal').modal('show');
            });

            $('#createUserForm').submit(function(e) {
                e.preventDefault();

                if (!checkLogin()) {
                    alert('Anda harus login terlebih dahulu!');
                    window.location.href = '/login';
                    return;
                }

                var formData = $(this).serialize();

                $.ajax({
                    url: '/users',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#createModal').modal('hide');
                        $('#alert-container').html(
                            '<div class="alert alert-success">User created successfully!</div>'
                        );
                        loadUsers();
                    },
                    error: function(xhr, status, error) {
                        $('#alert-container').html('<div class="alert alert-danger">Error: ' +
                            error + '</div>');
                    }
                });
            });

            $('#updateUserForm').submit(function(e) {
                e.preventDefault();

                if (!checkLogin()) {
                    alert('Anda harus login terlebih dahulu!');
                    window.location.href = '/login';
                    return;
                }

                var formData = $(this).serialize();
                var userId = $('#update-user-id').val();

                $.ajax({
                    url: '/users/' + userId,
                    method: 'PUT',
                    data: formData,
                    success: function(response) {
                        $('#updateModal').modal('hide');
                        $('#alert-container').html(
                            '<div class="alert alert-success">User updated successfully!</div>'
                        );
                        loadUsers();
                    },
                    error: function(xhr, status, error) {
                        $('#alert-container').html('<div class="alert alert-danger">Error: ' +
                            error + '</div>');
                    }
                });
            });

            $('#delete-user-btn').click(function() {
                if (!checkLogin()) {
                    alert('Anda harus login terlebih dahulu!');
                    window.location.href = '/login';
                    return;
                }

                var userId = $('#delete-user-id').val();

                $.ajax({
                    url: '/users/' + userId,
                    method: 'DELETE',
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        $('#alert-container').html(
                            '<div class="alert alert-success">User deleted successfully!</div>'
                        );
                        loadUsers();
                    },
                    error: function(xhr, status, error) {
                        $('#alert-container').html('<div class="alert alert-danger">Error: ' +
                            error + '</div>');
                    }
                });
            });

            function loadUsers() {
                if (!checkLogin()) {
                    alert('Anda harus login terlebih dahulu!');
                    window.location.href = '/login';
                    return;
                }

                $('#loading').show();
                $('#users-table-body').hide();

                $.ajax({
                    url: '/users',
                    method: 'GET',
                    success: function(response) {
                        var rows = '';
                        response.data.forEach(function(user) {
                            var createdAt = new Date(user.created_at);
                            var formattedDate = createdAt.getFullYear() + '-' +
                                ('0' + (createdAt.getMonth() + 1)).slice(-2) + '-' +
                                ('0' + createdAt.getDate()).slice(-2) + ' ' +
                                ('0' + createdAt.getHours()).slice(-2) + ':' +
                                ('0' + createdAt.getMinutes()).slice(-2) + ':' +
                                ('0' + createdAt.getSeconds()).slice(-2);

                            rows += `
                    <tr>
                        <td>${user.username}</td>
                        <td>${formattedDate}</td>
                        <td>${user.role}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" data-user-id="${user.id}" data-bs-toggle="modal" data-bs-target="#updateModal">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn" data-user-id="${user.id}" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                        </td>
                    </tr>
                `;
                        });

                        $('#loading').hide();
                        $('#users-table-body').html(rows).show();
                    },
                    error: function(xhr, status, error) {
                        $('#alert-container').html(
                            '<div class="alert alert-danger">Error loading users!</div>');
                        $('#loading').hide();
                    }
                });
            }

            loadUsers();
        });
    </script>
@endsection
