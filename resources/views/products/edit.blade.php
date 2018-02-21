<?php
$title = "Edit Product";
$page_title = $title;
?>
@extends("layouts.app")

@push("extra-css")
    <link href="{{ asset("css/bootstrap-material-datetimepicker.css") }}" rel="stylesheet">
@endpush

@section("content")
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

    <section class="row">
        <div class="col-lg-4">
            <div class="card animated zoomIn">
                <div id="divUpdateProduct" class="card-header bg-info text-white">
                    <span id="headerTitle">Product Details</span>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <button id="btnToggleUpdate" class="btn btn-danger"><i class="mdi mdi-pen"></i> Update Product</button>
                        <button id="btnToggleDetail" class="btn btn-primary"><i class="mdi mdi-eye"></i> View Details</button>
                    </div>
                    @if($product->thumb_image_path)
                        <div class="form-group">
                            <div class="thumbnail" style="width: 100px; height: 100px;">
                                <img src="{{ asset("inventory/products/{$product->name}/$product->image_path") }}" class="img-fluid">
                            </div>
                        </div>
                    @endif


                    <div id="divProductDetails" class="product-detail animated fadeIn">
                        <div>
                            <h5 class="mb-0">Product Name:</h5>
                            <p class="mb-0">{{ $product->name }}</p>
                        </div>

                        <div>
                            <h5 class="mb-0">Category:</h5>
                            <p class="mb-0">{{ $product->subcategory->category->name }}</p>
                        </div>

                        <div>
                            <h5 class="mb-0">SubCategory:</h5>
                            <p class="mb-0">{{ $product->subcategory->name }}</p>
                        </div>

                        <div>
                            <h5 class="mb-0">Brand:</h5>
                            <p class="mb-0">{{ $product->brand }}</p>
                        </div>
                    </div>

                    <form id="formUpdateProduct" class="animated fadeIn" action="{{ route("updateProduct", $product->slug) }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="txtProductName">Product Name</label>
                            <input id="txtProductName" name="name" class="form-control" type="text" value="{{ $product->name }}">
                        </div>

                        <div class="form-group">
                            <label for="ddlProductCategory">Category:</label>
                            <div>
                                <select id="ddlProductCategory" class="form-control" name="product-category">
                                    @foreach($categories as $category)
                                        @if($product->subcategory->category == $category)
                                            <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                        @else
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ddlSubCategories">Sub-Category:</label>
                            <select id="ddlSubCategories" name="product-subcategory" class="form-control">
                                @foreach($subCategories as $subcategory)
                                    @if($subcategory->category == $product->subcategory->category)
                                        @if($subcategory == $product->subcategory)
                                            <option value="{{ $subcategory->id }}" selected>{{ $subcategory->name }}</option>
                                        @else
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="lblProductBrand">Brand:</label>
                            <select id="lblProductBrand" name="product-brand" class="form-control">
                                @foreach($brands as $brand)
                                    @if($product->brand == $brand)
                                        <option value="{{ $brand->id }}" selected>{{ $brand->name }}</option>
                                    @else
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="ddlProductVariateBy">Variate By:</label>
                            <select id="ddlProductVariateBy" name="product-variate-by" class="form-control">
                                <option value="color" @if($product->variate_by == "color") selected @endif>Color</option>
                                <option value="name" @if($product->variate_by == "name") selected @endif>Name</option>
                                <option value="size" @if($product->variate_by == "size") selected @endif>Size</option>
                                <option value="weight" @if($product->variate_by == "weight") selected @endif>Weight</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="txtProductBarcode">UPC Barcode:</label>
                            <input id="txtProductBarcode" type="text" name="product-barcode" value="{{ $product->upc_code }}" class="form-control">
                        </div>

                        <input type="submit" value="Update Product" class="btn btn-danger btn-md btn-block">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <h3 class="section-title">Product Variations</h3>
            <button class="btn btn-danger btn-md mb-4" data-toggle="modal" data-target="#AddProductVariation">
                <i class="mdi mdi-plus-circle-outline"></i> Add New Variation
            </button>
            @foreach(array_chunk($product->variations->all(), 2) as $items)
                <div class="row">
                    @foreach($items as $variation)
                        <div class="col-md-6 col-lg-6 col-xlg-6">
                            <div class="card card-body p-0 variation-box">
                                <div class="row">
                                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                        <div style="background: #e1e1e1; width: 100%; height: 100%; display: block;">
                                            <img src="{{ asset("inventory/products/{$variation->product}/{$variation->image_path}") }}" class="img-fluid" style="margin: 0 auto; display: block;">
                                        </div>
                                    </div>
                                    <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7 py-2 pl-0">
                                        <h5 class="mb-2" style="width: 230px !important; height:18px !important; overflow: hidden;">{{ $variation->variation_name }}</h5>
                                        <p class="m-b-0">SKU: {{ $variation->sku }}</p>
                                        <p class="m-b-0">Weight: {{ $variation->weight }}{{ $variation->weight_unit }}</p>
                                        <p class="m-b-0">Available Stock: {{ $variation->stock }}</p>
                                        <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#{{ $variation->id }}Modal">
                                            <i class="mdi mdi-eye"></i> View
                                        </button>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-success mt-2 dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu" data-id="{{ $variation->id }}" data-name="{{ $variation->variation_name }}" data-slug="{{ $variation->slug }}">
                                                <a class="dropdown-item btn-link" href="" data-action="edit">Edit</a>
                                                <a class="dropdown-item" href="" data-action="reserve">Reserve</a>
                                                <a class="dropdown-item" href="" data-action="damaged">Record Damaged/Expired</a>
                                                {{-- No need adding adifferent link and method for recording expired products, when logically they can also be seen as damaged products --}}
                                                {{--<a class="dropdown-item" href="" data-action="damaged">Record Expired</a>--}}
                                            </div>
                                        </div>
                                        {{--<button type="button" class="btn btn-success mt-2" data-toggle="modal" data-target="#EditProductVariation{{ $variation->id }}">
                                            <i class="mdi mdi-pen"></i> Edit
                                        </button>--}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="{{ $variation->id }}Modal" tabindex="-1" role="dialog" aria-labelledby="1ModalLbl">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="1ModalLbl">{{ $variation->variation_name }}</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <dl class="row">
                                            <dt class="col-sm-4 text-right">SKU:</dt>
                                            <dd class="col-sm-8">{{ $variation->sku }}</dd>
                                            <dt class="col-sm-4 text-right">Weight:</dt>
                                            <dd class="col-sm-8">{{ $variation->weight }}{{ $variation->weight_unit }}</dd>
                                            <dt class="col-sm-3 mt-5"></dt>
                                            <dd class="col-sm-9 mt-5">
                                                <div class="float-right">
                                                    <ul class="list-inline">
                                                        <li><label for="ddlDateLine{{ $variation->id }}">Select Period:</label></li>
                                                        <li>
                                                            <select class="ddlDateLine" id="ddlDateLine{{ $variation->id }}" onchange="dateLine('ddlDateLine{{ $variation->id }}')" name="data-period" data-variation-id="{{ $variation->id }}">
                                                                <option value="all">All History</option>
                                                                <option value="this-month">This Month</option>
                                                                <option value="1-month" selected>Last 30 days</option>
                                                                <option value="2-months">Last 2 months</option>
                                                                <option value="3-months">Last 3 months</option>
                                                                <option value="6-months">Last 6 months</option>
                                                                <option value="12-months">Last 12 months</option>
                                                            </select>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </dd>
                                            <dt class="col-sm-4 text-right">Total Purchase:</dt>
                                            <dd class="col-sm-8"><span style="text-decoration: line-through">N</span><span id="purchase-value-{{ $variation->id }}"></span></dd>
                                            <dt class="col-sm-4 text-right">Total Sales:</dt>
                                            <dd class="col-sm-8"><span style="text-decoration: line-through">N</span><span id="sales-value-{{ $variation->id }}"></span></dd>
                                            <dt class="col-sm-4 text-right">Total Profit:</dt>
                                            <dd class="col-sm-8"><span style="text-decoration: line-through">N</span><span id="profit-value-{{ $variation->id }}"></span></dd>
                                            <dt class="col-sm-4 text-right">Total Loss:</dt>
                                            <dd class="col-sm-8"><span style="text-decoration: line-through">N</span><span id="loss-value-{{ $variation->id }}"></span></dd>
                                            <dt class="col-sm-4 mt-4 text-right">Damaged Items:</dt>
                                            <dd class="col-sm-8 mt-4"><span id="damaged-value-{{ $variation->id }}"></span></dd>
                                            <dt class="col-sm-4 text-right">Expired Items:</dt>
                                            <dd class="col-sm-8"><span id="expired-value-{{ $variation->id }}"></span></dd>
                                        </dl>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>


        <div class="modal fade" id="ReserveProduct" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reserve Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form id="frmReserve" action="" method="post">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <p>You have requested to reserve: <span id="lblReserveName"></span></p>

                            <div class="form-group">
                                <label for="txtReserveQty">Quantity to Reserve:</label>
                                <input type="number" id="txtReserveQty" class="form-control" min="1" name="quantity" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info">Reserve</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="modal fade" id="DamagedProduct" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Record Damaged Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form id="frmDamaged" action="" method="post">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <p>Product: <span id="lblDamagedName"></span></p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="txtBatch">Expiry Date:</label>
                                        <input id="txtBatch" type="text" name="batch" class="form-control" placeholder="29/04/2012" required>
                                        <span class="help-block">Please ensure that this date is exact. This is how we identify the product batch, to avoid recording damages on a different batch of product.</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtDamagedQty">Quantity Damaged:</label>
                                        <input type="number" id="txtDamagedQty" class="form-control" min="1" name="damaged_quantity" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="txtDamageCause">Cause of Damage:</label>
                                        <input type="text" id="txtDamageCause" name="cause" class="form-control" maxlength="250">
                                    </div>

                                    <div class="form-group">
                                        <label for="txtDamageDescription">Description:</label>
                                        <textarea name="description" id="txtDamageDescription" cols="30" rows="5"
                                                  class="form-control" maxlength="1000"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info">Record Damaged Products</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

    <section>
        {{---------------------------------------------------------------------------------------------
        ---------------------------------------------------------------------------------------------
        Add Product Variation
        ---------------------------------------------------------------------------------------------
        ---------------------------------------------------------------------------------------------}}
        <div class="modal fade" id="AddProductVariation" tabindex="-1" role="dialog" aria-labelledby="lblTitle">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="lblTitle">Add Product Variation</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{ route("storeVariation") }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                                    <span class="btn btn-danger btn-file waves-effect waves-light m-r-10">
                                                        <span class="fileinput-new">Select Product Image</span>
                                                        <span class="fileinput-exists">Change Product Image</span>
                                                        <input id="imgVariationImage" type="file" name="variation-image">
                                                    </span>
                                                <a href="#" class="btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="txtVariationName">Variation Name:</label>
                                            <input id="txtVariationName" type="text" name="variation_name" class="form-control" autofocus>
                                        </div>

                                        <div class="form-group">
                                            <label for="txtProductSKU">SKU</label>
                                            <input id="txtProductSKU" name="product-sku" class="form-control" type="text" >
                                            <small class="help-block">Pattern: CATEGORYSUBCATEGORY-PRODUCTNAME-VARIATION-NUMBER</small>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="txtProductWeight">Weight Value</label>
                                            <input id="txtProductWeight" type="text" name="product-weight" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="ddlProductWeightUnit">Unit of Measurement:</label>
                                            <select id="ddlProductWeightUnit" name="product-weight-unit" class="form-control">
                                                @foreach($weight_units as $weight_unit)
                                                    <option value="{{ $weight_unit->id }}">{{ $weight_unit->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        @if($product->variate_by == "color")
                                            <div class="form-group">
                                                <label for="txtProductColor">Color</label>
                                                <input id="txtProductColor" type="text" name="product-color" class="form-control">
                                            </div>
                                        @endif

                                        @if($product->variate_by == "size")
                                            <div class="form-group">
                                                <label for="txtProductSize">Size</label>
                                                <select id="txtProductSize" name="product-size" class="form-control">
                                                    <option value="xs">Extra Small</option>
                                                    <option value="sm">Small</option>
                                                    <option value="md">Medium</option>
                                                    <option value="lg">Large</option>
                                                    <option value="xl">Extra Large</option>
                                                    <option value="xxl">Extra Extra Large</option>
                                                </select>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="txtProductStock">Current Stock</label>
                                            <input id="txtProductStock" type="text" class="form-control" name="product-stock">
                                        </div>

                                        <div class="form-group">
                                            <label for="txtProductStockThreshold">Stock Threshold</label>
                                            <input id="txtProductStockThreshold" type="text" class="form-control" name="product-stock-threshold">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" value="Save" class="btn btn-info">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <input type="hidden" name="product_name" value="{{ $product->name }}">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{---------------------------------------------------------------------------------------------
        ---------------------------------------------------------------------------------------------
        Edit Product Variation
        ---------------------------------------------------------------------------------------------
        ---------------------------------------------------------------------------------------------}}
        @foreach($product->variations as $variation)
            <div class="modal fade" id="EditProductVariation{{ $variation->id }}" tabindex="-1" role="dialog" aria-labelledby="lblTitle">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="lblTitle">Update Product Variation</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{ route("updateVariation", $variation->slug) }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            @if($variation->thumb_image_path)
                                                <div class="form-group">
                                                    <div class="thumbnail" style="width: 100px; height: 100px;">
                                                        <img src="{{ asset("inventory/products/{$variation->product->name}/$variation->thumb_image_path") }}" class="img-fluid">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="txtVariationNameEdit{{ $variation->id }}">Variation Name:</label>
                                            <input id="txtVariationNameEdit{{ $variation->id }}" type="text" value="{{ $variation->variation_name }}" name="variation_name" class="form-control" autofocus>
                                        </div>

                                        <div class="form-group">
                                            <label for="txtProductSKUEdit{{ $variation->id }}">SKU</label>
                                            <input id="txtProductSKUEdit{{ $variation->id }}" value="{{ $variation->sku }}" name="product-sku" class="form-control" type="text" >
                                            <small class="help-block">Pattern: CATEGORYSUBCATEGORY-PRODUCTNAME-VARIATION-NUMBER</small>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="txtProductWeightEdit{{ $variation->id }}">Weight Value</label>
                                            <input id="txtProductWeightEdit{{ $variation->id }}" value="{{ $variation->weight }}" type="text" name="product-weight" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="ddlProductWeightUnitEdit{{ $variation->id }}">Unit of Measurement:</label>
                                            <select id="ddlProductWeightUnitEdit{{ $variation->id }}" name="product-weight-unit" class="form-control">
                                                @foreach($weight_units as $weight_unit)
                                                    @if($variation->weight_unit == $weight_unit->name)
                                                        <option value="{{ $weight_unit->id }}" selected>{{ $weight_unit->name }}</option>
                                                    @else
                                                        <option value="{{ $weight_unit->id }}">{{ $weight_unit->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        @if($product->variate_by == "color")
                                            <div class="form-group">
                                                <label for="txtProductColorEdit{{ $variation->id }}">Color</label>
                                                <input id="txtProductColorEdit{{ $variation->id }}" type="text" name="product-color" class="form-control">
                                            </div>
                                        @endif

                                        @if($product->variate_by == "size")
                                            <div class="form-group">
                                                <label for="txtProductSizeEdit{{ $variation->id }}">Size</label>
                                                <select id="txtProductSizeEdit{{ $variation->id }}" name="product-size" class="form-control">
                                                    <option value="xs" @if($variation->size == "xs") selected @endif>Extra Small</option>
                                                    <option value="sm" @if($variation->size == "sm") selected @endif>Small</option>
                                                    <option value="md" @if($variation->size == "md") selected @endif>Medium</option>
                                                    <option value="lg" @if($variation->size == "lg") selected @endif>Large</option>
                                                    <option value="xl" @if($variation->size == "xl") selected @endif>Extra Large</option>
                                                    <option value="xxl" @if($variation->size == "xxl") selected @endif>Extra Extra Large</option>
                                                </select>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="txtProductStockEdit{{ $variation->id }}">Current Stock</label>
                                            <input id="txtProductStockEdit{{ $variation->id }}" type="text" value="{{ $variation->stock }}" class="form-control" name="product-stock">
                                        </div>

                                        <div class="form-group">
                                            <label for="txtProductStockThresholdEdit{{ $variation->id }}">Stock Threshold</label>
                                            <input id="txtProductStockThresholdEdit{{ $variation->id }}" value="{{ $variation->stock_threshold }}" type="text" class="form-control" name="product-stock-threshold">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" value="Save" class="btn btn-info">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <input type="hidden" value="{{ $product->id }}" name="product_id">
                            <input type="hidden" value="{{ $product->name }}" name="product_name">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </section>
@endsection


@push("extra-js")
    <script src="{{ asset("js/fileinput.min.js") }}"></script>
    <script src="{{ asset("js/bootstrap-material-datetimepicker.js") }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            // Use material date picker for date input
            $('#txtBatch').bootstrapMaterialDatePicker({
                time: false
            });

            // Actions for product variation
            $(".dropdown-item").click(function(e) {
                e.preventDefault();
                var name = $(this).parent().data("name");
                var slug = $(this).parent().data("slug");
                var action = $(this).data("action");
                switch (action) {
                    case "edit":
                        var id = $(this).parent().data("id");
                        $("#EditProductVariation" + id).modal();
                        break;
                    case "reserve":
                        $("#lblReserveName").text(name);
                        $("#frmReserve").prop("action", "/product/reserve/" + slug);
                        $("#ReserveProduct").modal();
                        break;
                    case "damaged":
                        $("#lblDamagedName").text(name);
                        $("#frmDamaged").prop("action", "/product/damaged/" + slug);
                        $("#DamagedProduct").modal();
                        break;
                }
            });

            // Load variation data
            var sb = document.getElementsByClassName("ddlDateLine");
            $.each(sb, function(k, v){
                fetchVariationData($("#" + v.id).data("variation-id"), $("#" + v.id).val());
            });

            $("#formUpdateProduct").hide();
            $("#btnToggleDetail").hide();

            // Toggle product update form
            $("#btnToggleUpdate").click(function() {
                $("#divProductDetails").hide();
                $("#formUpdateProduct").show();
                $("#btnToggleUpdate").hide();
                $("#btnToggleDetail").show();
                $("#divUpdateProduct > span").innerHTML = "Test";
            });

            // Toggle product detail
            $("#btnToggleDetail").click(function() {
                $("#divProductDetails").show();
                $("#formUpdateProduct").hide();
                $("#btnToggleUpdate").show();
                $("#btnToggleDetail").hide();
            });

            // Populate subcategory dropdown on select of a category item
            $("#ddlProductCategory").change(function() {
                var category_id = $(this).val();

                $.ajax({
                    url: "/ajax/get-sub-categories/" + category_id,
                    type: "GET",
                    dataType: "json",

                    success: function(data) {
                        $("#ddlSubCategories").empty();
                        $.each(data, function(key, value){
                            $("#ddlSubCategories").append("<option value='" + value.id + "'>" + value.name + "</option>");
                        });
                    }
                });
            });
        });

        function dateLine(id) {
            var variation_id = $("#" + id).data("variation-id");
            var period = $("#" + id).val();
            fetchVariationData(variation_id, period);
        }

        function fetchVariationData(variation_id, period) {
            $.ajax({
                url: "/ajax/get-variation-details/" + variation_id,
                data: {"period": period},
                type: "GET",
                dataType: "json",

                success: function(data) {
                    $("#purchase-value-" + variation_id).text(parseFloat(data.purchase_amount));
                    $("#sales-value-" + variation_id).text(parseFloat(data.sales_amount));
                }
            });
        }
    </script>
@endpush
