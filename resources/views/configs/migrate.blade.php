<?php
$title = "Migrate";
$page_title = "Migrate";
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
            <div class="col-md-12">
                <div class="card animated zoomIn">
                    <div class="card-body">
                        <div>
                            <h4 class="card-title">Migrate From Spreadsheet</h4>
                            <h6 class="card-subtitle">Move existing records to database</h6>
                        </div>

                        <section class="my-4">
                            <div class="row justify-content-center">
                                <div class="col-md-6 my-3">
                                    <p class="text-danger">
                                        <strong>Please ensure to follow this order when uploading records:</strong>
                                    </p>

                                    <ol style="-webkit-padding-start: 10px !important;">
                                        <li>Brand (because dealers and products are dependent on it)</li>
                                        <li>Category (because sub-categories and products are dependent on it)</li>
                                        <li>Sub-Category (because products are dependent on it)</li>
                                        <li>Dealers (because products are dependent on it)</li>
                                        <li>Measurement Unit (because products are dependent on it)</li>
                                        <li>Product (because product variations are dependent on it)</li>
                                        <li>Product Variation (because batches are dependent on it)</li>
                                        <li>Product Batch (because he is the king)</li>
                                    </ol>
                                </div>

                                <div class="col-md-4 my-3">
                                    <form action="/configuration/migrate/store" method="post" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="ddlRecord">Record:</label>
                                            <select name="record" class="form-control" id="ddlRecord">
                                                <option value="batches">Batches</option>
                                                <option value="brands">Brands</option>
                                                <option value="categories">Categories</option>
                                                <option value="dealers">Dealers</option>
                                                <option value="measurement_units">Measurement Units</option>
                                                <option value="products">Products</option>
                                                <option value="product_variations">Product Variations</option>
                                                <option value="sub_categories">SubCategories</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="fileRecord">Record File:</label>
                                            <input class="form-control" type="file" name="file-record" id="fileRecord">
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-info btn-md btn-block">
                                                <i class="mdi mdi-upload"></i> Upload & Migrate
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection