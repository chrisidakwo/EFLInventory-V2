<?php
$title = "Dealers";
$page_title = "View All Dealers";
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
                        Create New Dealer
                    </div>
                    <div class="card-body">
                        <form action="{{ route("storeDealer") }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="txtDealerName">Dealer Full Name:</label>
                                <input id="txtDealerName" type="text" name="full_name" class="form-control" required autofocus>
                            </div>

                            <div class="form-group">
                                <label for="txtPhone">Phone:</label>
                                <input id="txtPhone" type="text" name="phone" class="form-control" placeholder="Email or phone">
                            </div>

                            <div class="form-group">
                                <label for="ddlBrand">Select a Brand:</label>
                                <select id="ddlBrand" name="brands[]" class="form-control" multiple="multiple">
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
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
                            <h3 class="card-title">View All Dealers</h3>
                            <h6 class="card-subtitle">Product dealers and actions</h6>
                        </div>

                        <div class="responsive-table">
                            <table id="tblAllBrand"  class="display nowrap table table-hover stylish-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Dealer Name</th>
                                        <th>Phone</th>
                                        <th>Brand</th>
                                        <th width="30%">Actions</th>
                                    </tr>
                                </thead>

                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Dealer Name</th>
                                        <th>Phone</th>
                                        <th>Brand</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                @if($dealers)
                                    @foreach($dealers as $dealer)
                                        <tr>
                                            <td>{{ $dealer->id }}</td>
                                            <td>{{ $dealer->full_name }}</td>
                                            <td>{{ $dealer->phone }}</td>
                                            <td>
                                                @foreach($dealer->brands as $brand)
                                                    @if(count($dealer->brands) == $loop->iteration)
                                                        {{ $brand->name }}
                                                    @else
                                                        {{ $brand->name . ", " }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#dealerEdit{{ $dealer->id }}">
                                                    <i class="mdi mdi-pen"></i> Edit
                                                </button>

                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#dealerDelete{{ $dealer->id }}">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="dealerEdit{{ $dealer->id }}" tabindex="-1" role="dialog" aria-labelledby="lblTitleEdit{{ $dealer->id }}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="lblTitleEdit{{ $dealer->id }}">Edit Dealer</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <form action="{{ route("editDealer", $dealer->id) }}" method="post" enctype="multipart/form-data">
                                                        {{ csrf_field() }}
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="txtDealerNameEdit{{ $dealer->id }}">Dealer Name:</label>
                                                                <input id="txtDealerNameEdit{{ $dealer->id }}" type="text" name="full_name" class="form-control" value="{{ $dealer->full_name }}" required autofocus>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="txtPhone">Phone:</label>
                                                                <input id="txtPhone" type="text" name="phone" class="form-control" value="{{ $dealer->phone }}" placeholder="Email or phone">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="ddlBrand">Select Brand:</label>
                                                                <select id="ddlBrand" name="brands[]" class="form-control" multiple="multiple">
                                                                    {{-- TODO: Write a custom function for the action below (in_array() somehow didn't work. Check why), or work on a better algorithm (if any) --}}
                                                                    {{ $flag = false }}
                                                                    @foreach($brands as $brand)
                                                                        @foreach($dealer->brands as $brandd)
                                                                            @if($brandd->id == $brand->id)
                                                                                {{ $flag = true }}
                                                                            @endif
                                                                        @endforeach

                                                                        @if($flag)
                                                                            <option value="{{ $brand->id }}" selected>{{ $brand->name }}</option>
                                                                            {{ $flag = false }}
                                                                        @else
                                                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
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

                                        <div class="modal fade" id="dealerDelete{{ $dealer->id}}" tabindex="-1" role="dialog" aria-labelledby="lblTitleDelete{{ $dealer->id }}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="lblTitleDelete{{ $dealer->id }}">Delete Dealer</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <form action="{{ route("deleteDealer", $dealer->id) }}" method="post">
                                                        {{ csrf_field() }}
                                                        <div class="modal-body">
                                                            <strong>Dealer Full Name: </strong> {{ $dealer->full_name }}
                                                            <br>
                                                            Are you sure you want to delete this dealer?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="submit" value="Yes, Delete" class="btn btn-info">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">No, Cancel</button>
                                                            <input type="hidden" name="full_name" value="{{ $dealer->full_name }}">
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
