<?php
$title = "Enter New Stock";
$page_title = "Stock Entry";
?>

@extends("layouts.app")

@push("extra-css")
    <link href="{{ asset("css/bootstrap-select.min.css") }}" rel="stylesheet">
    <link href="{{ asset("css/select2.min.css") }}" rel="stylesheet">
    <link href="{{ asset("css/steps.css") }}" rel="stylesheet">
    <link href="{{ asset("css/bootstrap-material-datetimepicker.css") }}" rel="stylesheet">
@endpush

@section("content")
    <section>
        <div class="row">
            <div class="col-12">
                @if(Session::has("success"))
                    <div class="alert alert-success alert-dismissible show animated zoomIn" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ Session::get("success") }}
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
            </div>
        </div>

        <div class="row" id="validation">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add New Stock</h4>
                        <h6 class="card-subtitle">Update product stock count</h6>

                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <button class="btn btn-danger btn-barcode-scan">
                                        <i class="mdi mdi-barcode-scan"></i> Use Barcode Scanner
                                    </button>
                                    <br><small><strong>**Optional</strong></small>
                                </div>

                                <div id="barcode" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <form action="/product/barcode/find" onsubmit="event.preventDefault(); repopulateProducts();">
                                                    <div class="form-group">
                                                        <input id="txtBarcode" type="text" class="form-control" name="barcode" value="" autofocus>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="addNewStock" action="{{ route("storeStockEntry") }}" method="post" class="">
                            {{ csrf_field() }}
                            <div class="row justify-content-center mb-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ddlProduct">Select Product:</label>
                                        <select id="ddlProduct" name="product" class="form-control select2">
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="ddlProdVariation">Select Variation:</label>
                                        <select id="ddlProdVariation" name="prod-variation" class="form-control select2 required" aria-required="true">

                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtStockQty">Stock Quantity:</label>
                                        <input id="txtStockQty" name="quantity" class="form-control required" aria-required="true">
                                    </div>

                                    <div class="form-group">
                                        <label for="txtStockTotalCost">Total Cost:</label>
                                        <input id="txtStockTotalCost" name="total-cost" class="form-control" required aria-required="true">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txtStockUnitCost">Unit Cost:</label>
                                        <input id="txtStockUnitCost" name="unit-cost" class="form-control" required aria-required="true">
                                    </div>

                                    <div class="form-group">
                                        <label for="txtStockRetailPrice">Retail Price:</label>
                                        <input id="txtStockRetailPrice" name="retail-price" class="form-control" required aria-required="true">
                                    </div>

                                    <div class="form-group">
                                        <label for="txtStockWholesalePrice">Wholesale Price:</label>
                                        <input id="txtStockWholesalePrice" name="wholesale-price" class="form-control" required aria-required="true">
                                    </div>

                                    <div class="form-group">
                                        <label for="txtStockExpiryDate">Expiry Date:</label>
                                        <input id="txtStockExpiryDate" type="text" name="expiry-date" class="form-control" required placeholder="{{ \Carbon\Carbon::now("Africa/Lagos")->format("d-M-Y") }}" aria-required="true">
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-danger btn-md" data-toggle="modal" data-target="#paymentOptions">
                                            Update Payment Details
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="paymentOptions" tabindex="-1" role="dialog" aria-labelledby="titleLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="titleLabel">Payment Details</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row justify-content-center">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="txtAmountPaid">Amount Paid:</label>
                                                        <input id="txtAmountPaid" type="text" name="amount-paid" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row justify-content-center">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="ddlPaymentMethod">Payment Method:</label>
                                                        <select id="ddlPaymentMethod" class="form-control" name="payment-method">
                                                            <option value="Bank Transfer">Bank Transfer</option>
                                                            <option value="Cash" selected>Cash</option>
                                                            <option value="Cheque">Cheque</option>
                                                            <option value="POS">POS</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row justify-content-center">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="txtRemarks">Remarks:</label>
                                                        <textarea id="txtRemarks" name="remarks" class="form-control" rows="3"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="#" onclick="event.preventDefault(); document.getElementById('addNewStock').submit();"
                                               class="btn btn-info">
                                                Add New Stock
                                            </a>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push("extra-js")
    <script src="{{ asset("js/select2.full.min.js") }}"></script>
    <script src="{{ asset("js/jquery.steps.min.js") }}"></script>
    <script src="{{ asset("js/jquery.validate.min.js") }}"></script>
    {{--<script src="{{ asset("js/steps.js") }}"></script>--}}
    <script src="{{ asset("js/bootstrap-material-datetimepicker.js") }}"></script>

    <script type="text/javascript">
        function repopulateProducts() {
            var barcode = $("#txtBarcode").val();

            $.ajax({
                url: "/product/barcode/find",
                data: {"barcode": barcode},
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $("#barcode").modal("hide");
                    $("#ddlProduct").val(data.product.id).trigger('change');
                    // the implementation above is faster and more concise
                    //$('select>option[value="' + data.product.id + '"]').prop('selected', true).trigger("change");
                }
            });
        }

        function populateProductVariations(id) {
            $.ajax({
                url: "/ajax/get-product-variations/" + id,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $("#ddlProdVariation").empty();
                    $.each(data, function(key, value) {
                        $("#ddlProdVariation").append("<option value='" + value.id + "'>" + value.variation_name + "</option>");
                    });
                }
            });
        }

        $(document).ready(function() {
            $(".btn-barcode-scan").click(function (e) {
                e.preventDefault();
                $("#barcode").modal();
                $("#txtBarcode").focus();
            });


            $("#ddlProduct").select2();
            $("#ddlProdVariation").select2();

            populateProductVariations($("#ddlProduct").val());

            $("#ddlProduct").change(function() {
                var product_id = $(this).val();
                populateProductVariations(product_id);
            });

            $('#txtStockExpiryDate').bootstrapMaterialDatePicker({
                time: false
            });
        });
    </script>
@endpush
