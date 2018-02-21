<?php
$title = "Measurement Units";
$page_title = "Measurement Units";
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
            <div class="col-lg-4">
                <div class="card animated zoomIn">
                    <div class="card-header bg-info text-white">
                        Create New Measurement Unit
                    </div>
                    <div class="card-body">
                        <form action="{{ route("storeUnit") }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="txtUnitName">Measurement Unit Name:</label>
                                <input id="txtUnitName" type="text" name="name" class="form-control" required autofocus>
                            </div>

                            <input type="submit" value="Save" class="btn btn-danger btn-md btn-block">
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <h3 class="card-title">View All Units</h3>
                            <h6 class="card-subtitle">Measurement Unit and actions</h6>
                        </div>

                        <div class="responsive-table">
                            <table id="tblAllBrand"  class="display nowrap table table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Unit Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Unit Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                @if($units)
                                    @foreach($units as $unit)
                                        <tr>
                                            <td>{{ $unit->id }}</td>
                                            <td>{{ $unit->name }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#unitEdit{{ $unit->id }}">
                                                    <i class="mdi mdi-pen"></i> Edit
                                                </button>

                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#unitDelete{{$unit->id}}">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="unitEdit{{ $unit->id }}" tabindex="-1" role="dialog" aria-labelledby="lblTitleEdit{{ $unit->id }}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="lblTitleEdit{{ $unit->id }}">Edit Measurement Unit</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <form action="{{ route("editUnit", $unit->id) }}" method="post">
                                                        {{ csrf_field() }}
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="txtUnitNameEdit{{ $unit->id }}">Brand Name:</label>
                                                                <input id="txtUnitNameEdit{{ $unit->id }}" type="text" name="name" class="form-control" value="{{ $unit->name }}" required autofocus>
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

                                        <div class="modal fade" id="unitDelete{{ $unit->id}}" tabindex="-1" role="dialog" aria-labelledby="lblTitleDelete{{ $unit->id }}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="lblTitleDelete{{ $unit->id }}">Delete Measurement Unit</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <form action="{{ route("deleteUnit", $unit->id) }}" method="post">
                                                        {{ csrf_field() }}
                                                        <div class="modal-body">
                                                            <strong>Measurement Unit Name: </strong> {{ $unit->name }}
                                                            <br>
                                                            Are you sure you want to delete this measurement unit?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="submit" value="Yes, Delete" class="btn btn-info">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">No, Cancel</button>
                                                            <input type="hidden" value="{{ $unit->name }}" name="name">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
