<?php
$title = "Backup Settings";
$page_title = "Backup Settings";
?>

@extends("layouts.app")

@section("content")
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

    <section class="row">
        <div class="col-md-12">
            <div class="card animated fadeInDown">
                <div class="card-body">
                    <h3 class="card-title">Inventory Backup Settings</h3>
                    <h6 class="card-subtitle">Setup a remote account for backup</h6>

                    <div class="row">
                        <div class="col-md-6 col-sm-12" style="border-right: 1px solid #e5e5e5;">
                            <section class="my-5">
                                <form action="/configuration/backup-settings/create" method="post">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txtEmail">Your Google Email:</label>
                                                <input id="txtEmail" type="email" class="form-control" name="email" placeholder="Ex: johndoe@gmail.com" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txtPassword">Your Google Password:</label>
                                                <input type="password" class="form-control" id="txtPassword" name="password" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txtFolderName">Folder Name:</label>
                                                <input type="text" class="form-control" id="txtFolderName" name="folder_name">
                                                <span class="help-block">The preferred name of the folder where all backups should be stored.</span>
                                            </div>
                                        </div>

                                        {{--<div class="col-md-6">
                                            <div class="form-group">
                                                <input id="chkEscapeSlash" type="checkbox" name="escape_slash" class="filled-in chk-col-light-blue">
                                                <label for="chkEscapeSlash">Escape Slashes</label><br>
                                                <span class="help-block">By default, slashes in your folder name are taken as subdirectories. But when checked, they are ignored and passed as the folder name.</span>
                                            </div>
                                        </div>--}}
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-block btn-md btn-info" value="Save Details">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </section>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <section class="my-5">
                                <h4>Backup Accounts</h4>
                                <div class="responsive-table">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Account</th>
                                                <th>Folder Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @if(count($configs) > 0)
                                                @foreach($configs as $item)
                                                    <tr>
                                                        <td>{{ $item->account }}</td>
                                                        <td>{{ $item->folder_name }}</td>
                                                        <td>
                                                            <button class="btn btn-info">
                                                                <i class="mdi mdi-pen"></i> Edit
                                                            </button>

                                                            <button class="btn btn-danger">
                                                                <i class="fa fa-trash"></i> Delete
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="3">No backup account</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection