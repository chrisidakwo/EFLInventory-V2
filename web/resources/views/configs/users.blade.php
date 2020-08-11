<?php
$title = "Users";
$page_title = "Users";
?>

@extends("layouts.app")

@section("content")
    <section>
        @if(Session::has("success"))
            <div class="alert alert-success alert-dismissible show animated zoomIn" role="alert">
                {{ Session::get("success") }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible show animated zoomIn">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-danger btn-md mb-4" data-toggle="modal" data-target="#modalCreate">
                    <i class="mdi mdi-plus-circle-outline"></i> Add New User
                </button>
            </div>

            <div class="col-md-12">
                <div class="card animated zoomIn">
                    <div class="card-body">
                        <div>
                            <h3 class="card-title">All Users</h3>
                            <h6 class="card-subtitle">User accounts and actions</h6>
                        </div>

                        <div class="responsive-table">
                            <table id="tblAllUsers" class="display nowrap table table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>User History</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>
                                                @if($user->is_superadmin)<label class="label label-primary">Admin</label>@endif
                                                @if($user->is_staff)<label class="label label-success">Staff</label>@endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($user->last_login)->format("D, M j, Y g:i:s A") }}</td>
                                            <td>
                                                <button class="btn btn-info user-history">
                                                    <i class="mdi mdi-eye"></i> View User History
                                                    {{--TODO: Add modal to show user history in form of a borderless & headerless table with columns as such: Action | Timestamp --}}
                                                </button>
                                            </td>
                                            <td>
                                                <button class="btn btn-primary btnEditUser" data-user="{{ $user->username }}">
                                                    <i class="mdi mdi-pen pr-2"></i>Edit
                                                </button>

                                                <button class="btn btn-danger btnDeleteUser" data-user="{{ $user->username }}">
                                                    <i class="fa fa-trash pr-2"></i>Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="lblDeleteTitle">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="lblDeleteTitle">Delete User Account</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="" id="frmDeleteUser" method="post">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="text-center">
                                <p style="font-weight: 700; font-size: 18px;" class="text-danger">Are you sure you want to delete <span id="lblUserName"></span>?</p>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <input type="submit" value="Delete" class="btn btn-danger">
                            <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="lblTitleEdit">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="lblTitleEdit">Edit User Account</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form id="frmEditUser" action="" method="post">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="txtName">Name:</label>
                                <input id="txtName" type="text" name="name" class="form-control" required autofocus>
                            </div>

                            <div class="form-group">
                                <label for="txtUsername">Username:</label>
                                <input type="text" id="txtUsername" name="username" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="txtPassword">Password:</label> <a href="" id="txtChangePassword" class="pull-right btn-link text-danger">Change Password</a> <a href="" id="txtCancelPassword" class="btn-link pull-right" style="display:none;">Cancel</a>
                                <input type="password" id="txtPassword" name="password" class="form-control" disabled="disabled" required>
                            </div>

                            <div class="form-group">
                                <input id="chkIsAdmin" type="checkbox" name="admin" class="filled-in chk-col-purple">
                                <label for="chkIsAdmin">Is Admin</label>
                            </div>

                            <div class="form-group">
                                <input id="chkIsStaff" type="checkbox" name="staff" class="filled-in chk-col-light-blue">
                                <label for="chkIsStaff">Is Staff</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" value="Update" class="btn btn-info">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="lblTitleCreate">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="lblTitleEdit">Create New User Account</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <form id="frmEditUser" action="/configuration/user/create" method="post">
                            {{ csrf_field() }}
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="lblName">Name:</label>
                                    <input id="lblName" type="text" name="name" class="form-control" required autofocus>
                                </div>

                                <div class="form-group">
                                    <label for="lblUsername">Username:</label>
                                    <input type="text" id="lblUsername" name="username" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="lblPassword">Password:</label>
                                    <input type="password" id="lblPassword" name="password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <input id="chkAdmin" type="checkbox" name="admin" class="filled-in chk-col-purple">
                                    <label for="chkAdmin">Is Admin</label>
                                </div>

                                <div class="form-group">
                                    <input id="chkStaff" type="checkbox" name="staff" class="filled-in chk-col-light-blue">
                                    <label for="chkStaff">Is Staff</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="submit" value="Create" class="btn btn-info">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </section>
@endsection

@push("extra-js")
    <script src="{{ asset('js/user-config.js') }}"></script>
@endpush