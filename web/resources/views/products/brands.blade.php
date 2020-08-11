<?php
    $title = "Brands";
    $page_title = "View All Brands";
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

        @if(Session::has("error"))
            <div class="alert alert-danger alert-dismissible show animated zoomIn" role="alert">
                {{ Session::get("error") }}
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
                        Create New Brand
                    </div>
                    <div class="card-body">
                        <form action="{{ route("storeBrand") }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="txtBrandName">Brand Name:</label>
                                <input id="txtBrandName" type="text" name="name" class="form-control" required autofocus>
                            </div>

                            <div class="form-group">
                                <label for="txtBrandContact">Contact Information:</label>
                                <input id="txtBrandContact" type="text" name="contact" class="form-control" placeholder="Email or phone">
                            </div>

                            <div class="form-group">
                                <label for="txtBrandAddress">Brand Address:</label>
                                <textarea id="txtBrandAddress" name="address" class="form-control" rows="3"></textarea>
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
                            <h3 class="card-title">View All Brands</h3>
                            <h6 class="card-subtitle">Product brands and actions</h6>
                        </div>

                        <div class="responsive-table">
                            <table id="tblAllBrand"  class="display nowrap table table-hover stylish-table" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Brand Name</th>
                                    <th>Contact Info</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Brand Name</th>
                                    <th>Contact Info</th>
                                    <th>Actions</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @if($brands)
                                    @foreach($brands as $brand)
                                        <tr>
                                            <td>{{ $brand->id }}</td>
                                            <td>{{ $brand->name }}</td>
                                            <td>{{ $brand->contact_info }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#brandEdit{{ $brand->id }}">
                                                    <i class="mdi mdi-pen"></i> Edit
                                                </button>

                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#brandDelete{{$brand->id}}">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="brandEdit{{ $brand->id }}" tabindex="-1" role="dialog" aria-labelledby="lblTitleEdit{{ $brand->id }}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="lblTitleEdit{{ $brand->id }}">Edit Brand</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <form action="{{ route("editBrand", $brand->slug) }}" method="post">
                                                        {{ csrf_field() }}
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="txtBrandNameEdit{{ $brand->id }}">Brand Name:</label>
                                                                <input id="txtBrandNameEdit{{ $brand->id }}" type="text" name="name" class="form-control" value="{{ $brand->name }}" required autofocus>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="txtBrandContact">Contact Information:</label>
                                                                <input id="txtBrandContact" type="text" name="contact" class="form-control" value="{{ $brand->contact_info }}" placeholder="Email or phone">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="txtBrandAddress">Brand Address:</label>
                                                                <textarea id="txtBrandAddress" name="address" class="form-control" rows="3">{{ $brand->address }}</textarea>
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

                                        <div class="modal fade" id="brandDelete{{ $brand->id}}" tabindex="-1" role="dialog" aria-labelledby="lblTitleDelete{{ $brand->id }}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="lblTitleDelete{{ $brand->id }}">Delete Brand</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <form action="{{ route("deleteBrand", $brand->slug) }}" method="post">
                                                        {{ csrf_field() }}
                                                        <div class="modal-body">
                                                            <strong>Brand Name: </strong> {{ $brand->name }}
                                                            <br>
                                                            Are you sure you want to delete this brand?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="submit" value="Yes, Delete" class="btn btn-info">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">No, Cancel</button>
                                                            <input type="hidden" value="{{ $brand->name }}" name="name">
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
