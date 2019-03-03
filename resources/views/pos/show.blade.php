<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="{{ asset("img/brand/favicon.png") }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>POS | EmiFoodLovers</title>

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap-glyphicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    @stack("extra-css")
    <link href="{{ asset('css/jquery.toast.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/colors/blue.css') }}" id="theme" rel="stylesheet">
</head>

<body>


<div id="sales-receipt" class="sideNav bg-white">
    <div class="container">
        <div class="row justify-content-end">
            <div class="col-md-12 px-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-left">
                            <button class="btn btn-info btn-md" onclick="window.location.reload();">
                                <i class="mdi mdi-arrow-left-bold pr-2"></i> Next Sale
                            </button>
                        </div>
                        <div class="pull-right">
                            <button id="print" class="btn btn-danger btn-md" type="button"> <span><i class="fa fa-print pr-1"></i> Print</span> </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="printableArea">
        <div class="container">
            <div class="mb-3">
                <div class="row justify-content-between">
                    <div class="col-md-12 p-5 printableArea">
                        <h4>
                            <strong>SALES RECEIPT</strong><br>
                            <label>Transaction No:</label>&nbsp;<span id="trans-no"></span>
                        </h4>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">
                                    <address>
                                        <h2 class="mb-0">
                                            <strong class="text-danger">{{ env("app_name") }}</strong>
                                        </h2>
                                        <h6 class="text-muted m-1-5">
                                            <span id="org-contact"></span><br>
                                            <span id="org-address"></span>
                                        </h6>
                                    </address>
                                </div>

                                <div class="pull-right text-right">
                                    <address>
                                        <p>
                                            <strong>Date:&nbsp;{{ \Carbon\Carbon::now(config("timezone"))->format('D, M j, Y g:i:s A') }}</strong>
                                        </p>
                                    </address>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="table-responsive m-t-40" style="clear:both">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Product</th>
                                            <th class="text-right">Quantity</th>
                                            <th class="text-right">Unit Cost (<span class="naira">N</span>)</th>
                                            <th class="text-right">Total (<span class="naira">N</span>)</th>
                                        </tr>
                                        </thead>

                                        <tbody id="receipt-table"></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="pull-right text-right">
                                    <hr>
                                    <h3 class="pl-4 mb-2"><strong>Total:&nbsp;</strong><span class="naira">N</span><span id="sell-total"></span></h3>
                                    <h6 class="pl-4"><strong>Amount Paid:&nbsp;</strong><span class="naira">N</span><span id="amount-paid"></span></h6>
                                    <h6 class="pl-4"><strong>Remaining Balance:&nbsp;</strong><span class="naira">N</span><span id="paid-balance"></span></h6>
                                    <h6 class="pl-4"><strong>Change:&nbsp;</strong><span class="naira">N</span><span id="paid-change"></span></h6>
                                    <hr>
                                </div>
                                <div class="clearfix"></div>
                                <div class="text-center">
                                    <h5 class="text-uppercase">Thank you for buying from us</h5>
                                    <p class="text-muted">Returns are only accepted within 24 working hours from the time of purchase, on show of this receipt, and given the item is in an acceptable good condition</p>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="pay-drawer" class="sideNav">
    <div class="container">
        <div class="nav-top mb-3">
            <div class="row justify-content-between">
                <div class="col-md-3">
                    <button class="btn btn-danger" onclick="closeDrawer();">
                        <i class="mdi mdi-arrow-left-bold pr-2"></i>Return to POS
                    </button>
                </div>

                <div class="col-md-3 text-center">
                    <h3>Cash Tender & Change</h3>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-success pull-right sell-button d-none">
                        Sell Products<i class="mdi mdi-arrow-right-bold pl-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="payment mt-3">
            <div class="row">
                <div class="col-md-4 payment-method pr-5">
                    <h4 class="pt-3">Select a payment method</h4>
                    <div class="">
                        <button class="btn payment-btn" data-mode="cash">Cash</button>
                        <button class="btn payment-btn" data-mode="cheque">Cheque</button>
                        <button class="btn payment-btn" data-mode="card">Credit/Debit Card</button>
                    </div>
                </div>

                <div class="col-md-8 payment-input">
                    <div class="payment-input-scroll d-block">
                        <div class="cart-total py-3">
                            <h5 class="px-5 mb-2">Total Amount:</h5>
                            <h1><span class="naira">N</span><span class="payment-total"></span></h1>
                        </div>

                        <div class="input-boxes my-5">
                            <!-- For cash payments -->
                            <div id="cash-payment" class="d-none">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="txtAmountTendered">Cash Tendered:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon naira">N</span>
                                                <input type="number" id="txtAmountTendered"
                                                       class="form-control input-lg cash-tendered"
                                                       name="cash_amount_tendered">
                                                <span class="input-group-addon">.00</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="txtChange">Change:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon naira">N</span>
                                                <input type="number" id="txtChange"
                                                       class="form-control input-lg cash-change" name="cash_change">
                                                <span class="input-group-addon">.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="txtRemainingAmount">Remaining Amount:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon naira">N</span>
                                                <input type="number" id="txtRemainingAmount"
                                                       class="form-control input-lg cash-remaining-amount" value="0"
                                                       name="cash_remaining_amount">
                                                <span class="input-group-addon">.00</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="txtRemarks">Remarks:</label>
                                            <textarea name="remarks" class="form-control input-lg" id="txtRemarks"
                                                      cols="30" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="pos-body">
    <div class="pos">
        <div class="pos-top-header">
            <div class="pos-branding">
                <img class="pos-logo img-fluid" src="{{ asset("img/brand/logo-pos.png") }}">
                @if(Auth::check())
                    <a id="switchUser" class="username dropdown-toggle waves-effect waves-dark" href="#"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span>{{ Auth::user()->username }}</span>
                    </a>
                @endif
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="switchUser">
                    <a class="dropdown-item" href="#">Switch User</a>
                    <a href="#" class="dropdown-item"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit()">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>

            @if(Auth::check())
                @if(Auth::user()->authorizeRoles(["Manager"]))
                    <div class="right-header">
                        <div class="header-button">
                            <a href="{{ route("index") }}">Return Home</a>
                        </div>
                    </div>
                @endif
            @endif
        </div>
        <div class="pos-content">
            <div class="window">
                <div class="sub-window-container">
                    <div class="sub-window-container-fix">
                        <div class="screen">
                            <div class="left-pane">
                                <div class="window">
                                    <div class="cart-window">
                                        <div class="cart-window-container">
                                            <div class="cart-window-container-fix">
                                                <div class="order-container">
                                                    <div class="order-scroll">
                                                        <div class="order">
                                                            <div class="order-list">
                                                                <div class="order-empty">
                                                                    <i class="mdi mdi-cart"></i>
                                                                    <h4>Your shopping cart is empty</h4>
                                                                </div>
                                                            </div>

                                                            <div class='summary d-none clearfix'>
                                                                <div class='line'>
                                                                    <div class='entry total'>
                                                                        <span class='label pr-2'>Total:</span>
                                                                        <span class='pr-1'><span
                                                                                    style="text-decoration: line-through">N</span></span><span
                                                                                class='value'>0.00</span>
                                                                        {{--<div class='sub-entry'>Taxes: <span style='text-decoration: line-through'>N</span><span class='value'>0.00</span></div>--}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="keypad-window collapsed">
                                        <div class="keypad-window-container">
                                            <div class="keypad-window-container-fix">
                                                <div class="action-pad">
                                                    <button id="btnClearCart" class="button clear-cart">
                                                        <i class="mdi mdi-delete"></i>
                                                        <span>Clear Cart</span>
                                                    </button>
                                                    <button id="btnPay" class="button pay">
                                                        <div class="pay-circle">
                                                            <i class="mdi mdi-chevron-right"></i>
                                                        </div>
                                                        <span>Payment</span>
                                                    </button>
                                                </div>

                                                <div class="num-pad">
                                                    <button class="input-button number-char">1</button>
                                                    <button class="input-button number-char">2</button>
                                                    <button class="input-button number-char">3</button>
                                                    <button class="mode-button selected-mode" data-mode="quantity">Qty
                                                    </button>
                                                    <br>
                                                    <button class="input-button number-char">4</button>
                                                    <button class="input-button number-char">5</button>
                                                    <button class="input-button number-char">6</button>
                                                    <button class="mode-button" data-mode="discount">Disc</button>
                                                    <br>
                                                    <button class="input-button number-char">7</button>
                                                    <button class="input-button number-char">8</button>
                                                    <button class="input-button number-char">9</button>
                                                    <button class="mode-button" data-mode="price">Price</button>
                                                    <br>
                                                    <button class="input-button number-minus">+/-</button>
                                                    <button class="input-button number-char">0</button>
                                                    <button class="input-button number-char">.</button>
                                                    <button class="input-button numpad-backspace">
                                                        <img height="21" width="24"
                                                             src="{{ asset("img/pos/backspace.png") }}"
                                                             style="pointer-events: none;">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="right-pane">
                                <table class="layout-table">
                                    <tbody>
                                    <tr class="header-row">
                                        <td class="header-cell">
                                            <div>
                                                <header class="right-pane-header">
                                                    {{--<div class="breadcrumbs">
                                                        <span class="breadcrumb">
                                                            <span class="breadcrumb-button breadcrumb-home">
                                                                <i class="mdi mdi-home"></i>
                                                            </span>
                                                        </span>
                                                    </div>--}}
                                                    {{--<div class="select-category">
                                                        <span>Search Category:</span>
                                                        <select id="ddlSelectCategory" name="prod-category">
                                                            @foreach($categories as $category)
                                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>--}}
                                                    <div class="search-box">
                                                        <form action="/product/barcode/find" onsubmit="event.preventDefault(); searchBarcode();">
                                                            <input placeholder="Search products" id="txtSearch" name="search">
                                                        </form>
                                                        <span class="search-clear"></span>
                                                    </div>
                                                </header>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="content-row">
                                        <td class="content-cell">
                                            <div class="content-container">
                                                <div class="product-list-container">
                                                    <div class="product-list-scroll touch-scroll">
                                                        <div class="product-list">
                                                            @if(isset($products))
                                                                @foreach($products as $product)
                                                                    <div class="product" id="p-{{ $product->batch_id }}"
                                                                         data-batch-id="{{ $product->batch_id }}"
                                                                         data-name="{{ $product->variation_name }}"
                                                                         data-price="{{ $product->retail_price }}">
                                                                        <div class="product-img">
                                                                            <img src="{{ asset("inventory/products/{$product->product_name}/{$product->variation_img}") }}"
                                                                                 class="img-fluid">
                                                                            <span class="price-tag">
                                                                            <strong>
                                                                                <span style="text-decoration: line-through">N</span><span class="price">{{ $product->retail_price }}</span>
                                                                            </strong>
                                                                        </span>
                                                                        </div>
                                                                        <div class="product-name">
                                                                            {{ $product->variation_name }}
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Scripts -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('js/waves.js') }}"></script>
<script src="{{ asset('js/sticky-kit.min.js') }}"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/HelperFunctions.js') }}"></script>
<script src="{{ asset('js/jquery.toast.js') }}"></script>
<script src="{{ asset('js/jquery.printArea.js') }}"></script>
<script type="text/javascript">
    /*THE CART SCRIPTS HERE IS DEFINITELY NOT THE BEST SOLUTION. BUT
    IT DOES THE JOB FOR NOW. I INTEND TO KEEP IMPROVING THIS SCRIPT
    AS OFTEN AS POSSIBLE.

        HINTS FOR IMPROVEMENT
    1.) RE-WRITE THE ALGORITHM
    2.) USE SIMPLE EFFICIENT CODE
    3.) AS MUCH AS POSSIBLE, AVOID CODE REUSE
    4.) ATTEMPT TO USE sessionStorage FOR CART COLLECTION TEMP
    STORAGE. THIS WILL BE BETTER THAN SESSION COOKIE. SO THAT EACH
    LIST IS ONLY ACCESSIBLE FROM THE TAB IT WAS CREATED, ALLOWING
    FOR THE SIMULTANEOUS CREATION OF SEVERAL DIFFERENT CART LISTS

    IN SUMMARY, USE THE SIMPLE MOST-EFFECTIVE SOLUTION
    */
    var clicked = 0;
    var remove = 0;
    var price_clicked = 0;
    var price_remove = 0;

    $(document).ready(function () {
        $("#txtSearch").change(function (e) {
            findProduct();
        });

        // Get cart items (if any), and display in cart
        getCartAndDisplay();

        // Begin processing customer payment
        $("#btnPay").click(function () {
            var jqxhr = $.ajax({
                url: "/pos/cart/process",
                type: "get"
            });

            jqxhr.done(function (response, textStatus, jqXHR) {
                if (response.success != "") {
                    var payment_total = currencyFormat.format(document.getElementsByClassName("summary")[0].firstElementChild.children[0]
                        .children[2].innerText.replace(",", "").replace(".00", "").replace(",", ""));
                    $(".payment-total").text(payment_total);

                    // On pass of process conditions, move to cash tender and change
                    // Reveal sidebar drawer
                    openDrawer();
                }
            });

            jqxhr.fail(function (textStatus, errorThrown) {
                $.toast({
                    heading: 'Error!',
                    text: "Ensure your cart is not empty or the total price is above zero (0.00)",
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'info',
                    hideAfter: 4000,
                    stack: 6
                });
            });
        });

        // Payment options buttons - Cash, Cheque, or Card
        $(".payment-btn").click(function (e) {
            e.preventDefault();
            $(".payment-btn").removeClass("selected");
            $(this).addClass("selected");
            $("#cash-payment").removeClass("d-none");
        });

        // Handle all cash tendered and remaining balance/change values
        $(".cash-tendered").keyup(function () {
            var cash_value = $(this).val();
            var cart_total_payment = document.getElementsByClassName("summary")[0].firstElementChild.children[0]
                .children[2].innerText.replace(",", "").replace(".00", "").replace(",", "");

            var change = parseInt(cart_total_payment, 10) - parseInt(cash_value, 10);
            if (change > 0) {
                $(".cash-change").val("0");
                $(".cash-remaining-amount").val(change);
            } else if (change < 0) {
                $(".cash-change").val(change.toString().replace("-", ""));
                $(".cash-remaining-amount").val("0");
            } else {
                $(".cash-change").val("0");
                $(".cash-remaining-amount").val("0");
            }

            if (parseInt($(".cash-remaining-amount").val(), 10) != 0) {
                $(".sell-button").removeClass("d-none");
            }
        });

        // Reduce product stock according to sold quantity
        $(".sell-button").click(function () {
            // Ensure cash tendered is not empty
            var tendered = $(".cash-tendered").val();
            if (parseInt(tendered, 10) == 0) {
                $.toast({
                    heading: 'Invalid Amount',
                    text: "Amount tendered cannot be zero",
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 7000,
                    stack: 6
                });
                return;
            }

            // Get change and balance remaining
            var change = $(".cash-change").val();
            var remaining = $(".cash-remaining-amount").val();
            var payment_method = $(".payment-btn.selected").data("mode");
            var remarks = $("#txtRemarks").val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var jqxhr = $.ajax({
                url: "/pos/cart/sell",
                type: "POST",
                data: {
                    "tendered": tendered, "payment_method": payment_method,
                    "change": change, "balance_remaining": remaining, "remarks": remarks
                }
            });

            jqxhr.done(function (response, textStatus, jqXHR) {
                // Clear cart from session using ajax
                $.ajax({
                    url: "/pos/cart/delete/all",
                    type: "get",
                    success: function(data, textStatus, jqXHR) {
                        // Display cart empty
                        $(".order-empty").removeClass("d-none");
                    }
                });

                // Show sales receipt page
                $("#trans-no").text(response.sales_group.receipt_no);

                // Append items to table
                var items = response.items;
                var count = 1;
                $.each(items, function (k, v) {
                    var row = "<tr>";
                    row += "<td class=\"text-center\">" + count + "</td>";
                    row += "<td>" + v.name + "</td>";
                    row += "<td class=\"text-right\">" + v.quantity + "</td>";
                    row += "<td class=\"text-right\">" + currencyFormat.format(v.price) + "</td>";
                    row += "<td class=\"text-right\">" + currencyFormat.format(v.price * v.quantity) + "</td>";

                    $("#receipt-table").append(row);
                    count++;
                });

                // Total value
                $("#sell-total").text(currencyFormat.format(response.sales_group.total_amount));
                $("#amount-paid").text(currencyFormat.format(response.sales_group.amount_tendered));
                $("#paid-balance").text(currencyFormat.format(response.sales_group.balance_due));
                $("#paid-change").text(currencyFormat.format(response.sales_group.change_amount));

                // Organisation Address & Contact
                var _address = '123 Address Street, Address.';
                var _contact = '00000000000';
                if(response.settings) {
                    _address = response.settings.org_address;
                    _contact = response.settings.org_contact;
                }


                $("#org-address").text(_address);
                $("#org-contact").text(_contact);

                closeDrawer();
                showReceipt();
            });
        });

        // Print receipt
        $("#print").click(function() {
            var mode = 'popup' //'iframe'; //popup
            var close = true;
            var options = {
                mode: mode,
                popClose: close
            };
            $("div.printableArea").printArea(options);
        });

        // Remove all items from cart
        $("#btnClearCart").click(function () {
            // Remove all cart items
            $(".order").find(".order-lines").remove();

            // Hide summary div and set total price to zero
            $(".summary").addClass("d-none");
            $(".summary").find(".value").first().text("0");

            // Clear cart from session using ajax
            var jqxhr = $.ajax({
                url: "/pos/cart/delete/all",
                type: "get"
            });

            jqxhr.done(function (response, textStatus, jqXHR) {
                $.toast({
                    heading: 'Cart Is Empty',
                    text: response.success,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'info',
                    hideAfter: 4000,
                    stack: 6
                });

                // Display cart empty
                $(".order-empty").removeClass("d-none");
            });
        });

        // Customize scroll button for cart display
        $('.order-scroll').slimScroll({
            position: 'right',
            size: "5px",
            height: '100%'
        });

        // On clicking a product card, hide the .order-empty div
        // then retrieve product details using AJAX
        $("div.product").click(function (e) {
            clicked = 0;
            var id = $(this).attr("id").toString().replace("p-", "");
            var name = $(this).data("name");
            var price = $(this).data("price");
            var qty = 1;
            if (!$(".order-empty").hasClass("d-none")) {
                $(".order-empty").addClass("d-none");
            }

            AddProductToCart([id, name, price, qty]);
        });

        // On clicking a mode button, toggle the .selected-mode class
        // to indicate the active mode
        $(".mode-button").click(function () {
            if ($(".mode-button").has("selected-mode")) {
                $(".mode-button").removeClass("selected-mode");
                $(this).addClass("selected-mode");
            }
        });

        // Numbers button
        $(".number-char").click(function () {
            if ($(".selected").length > 0) {
                clicked++;
                price_clicked++;
                var mode = $(".selected-mode").data("mode");
                var item_qty = document.getElementsByClassName("selected")[0].children[2].children[0].children[0].children[0];
                var item_unit_price = document.getElementsByClassName("selected")[0].children[2].children[0].children[2];
                var item_total_price = parseFloat(item_qty.innerText * item_unit_price.innerText);
                var cart_total_price = $(".summary").find(".value").first().text().replace(",", "").replace(".00", "");

                switch (mode) {
                    case "quantity":
                        if (price_clicked > 0) price_clicked = 0;

                        if ($(this).text() == ".") {
                            if (clicked == 1) {
                                clicked = 0;
                            }
                            return;
                        }

                        var num = parseInt($(this).text(), 10);
                        if (clicked == 1) {
                            item_qty.innerText = num;
                            clicked++;
                            remove = 0;
                        } else {
                            item_qty.innerText = item_qty.innerText + num;
                        }

                        // Re-calculate item and cart total price based on current qty
                        var updatedItemTotalPrice = parseFloat(item_qty.innerText * item_unit_price.innerText);
                        var updatedCartTotalPrice = updatedItemTotalPrice + ((parseFloat(cart_total_price) - item_total_price));

                        // Update item and cart total price
                        document.getElementsByClassName("selected")[0].children[1].children[1].innerText = currencyFormat.format(updatedItemTotalPrice);
                        $(".summary").find(".value").first().text(currencyFormat.format(updatedCartTotalPrice));
                        break;
                    case "discount":
                        if ($(this).text() == ".") {
                            return;
                        }
                        if (clicked > 0) clicked = 0;
                        if (price_clicked > 0) price_clicked = 0;
                        break;
                    case "price":
                        if ($(this).text() == ".") {
                            if (price_clicked == 1) {
                                price_clicked = 0;
                            }
                            return;
                        }
                        if (clicked > 0) clicked = 0;
                        var price = parseFloat($(this).text());

                        if (price_clicked == 1) {
                            item_unit_price.innerText = price;
                            price_clicked++;
                        } else {
                            item_unit_price.innerText = item_unit_price.innerText + price;
                        }

                        // Re-calculate item and cart total price based on current price
                        var updatedItemTotalPrice = parseFloat(item_qty.innerText * item_unit_price.innerText);
                        var updatedCartTotalPrice = updatedItemTotalPrice + ((parseFloat(cart_total_price) - item_total_price));

                        // Update item and cart total price
                        document.getElementsByClassName("selected")[0].children[1].children[1].innerText = currencyFormat.format(updatedItemTotalPrice);
                        $(".summary").find(".value").first().text(currencyFormat.format(updatedCartTotalPrice));
                        break;
                }

                var id = $(".selected").parent().data("id");
                var name = $(".selected").parent().data("name");
                UpdateProduct([id, name, item_unit_price.innerText, item_qty.innerText]);
            }
        });

        // Backward delete/remove button
        $(".numpad-backspace").click(function () {
            if ($(".selected").length > 0) {
                var mode = $(".selected-mode").data("mode");
                var item_unit_price = document.getElementsByClassName("selected")[0].children[2].children[0].children[2];
                var itemQty = document.getElementsByClassName("selected")[0].children[2].children[0].children[0].children[0];
                var item_total_price = parseFloat(itemQty.innerText) * parseFloat(item_unit_price.innerText);
                var cart_total_price = $(".summary").find(".value").first().text().replace(".00", "").replace(",", "").replace(",", "").replace(",", "");

                switch (mode) {
                    case "quantity":
                        if (remove == 0) {
                            if (itemQty.innerText.length == 1) {
                                itemQty.innerText = 0;
                                clicked = 0;
                                remove = 1;
                            } else {
                                itemQty.innerText = itemQty.innerText.substring(0, (itemQty.innerText.length - 1));
                            }

                            // Re-calculate item and cart total price based on current qty
                            var updatedItemTotalPrice = parseFloat(itemQty.innerText * item_unit_price.innerText);
                            var updatedCartTotalPrice = parseFloat(cart_total_price) - (item_total_price - updatedItemTotalPrice);

                            //UpdateProduct([item_id, item_name, item_unit_price.innerText, cart_item.innerText]);

                            // Update item and cart total price
                            document.getElementsByClassName("selected")[0].children[1].children[1].innerText = currencyFormat.format(updatedItemTotalPrice);
                            $(".summary").find(".value").first().text(currencyFormat.format(updatedCartTotalPrice));

                        } else {
                            remove = 0;

                            // Remove item from CartCollection
                            var item_id = $(".selected").parent().data("id");
                            RemoveProduct(item_id);

                            // Remove from UI
                            $(".selected").parent().remove();
                            if ($(".selected").find().length == 0 && $(".order-lines").find().prevObject.length == 0) {
                                $(".summary").addClass("d-none");
                                $(".order-empty").removeClass("d-none");
                            }
                        }
                        break;
                    case "discount":
                        break;
                    case "price":
                        if (price_remove == 0) {
                            if (item_unit_price.innerText.length == 1 || item_unit_price.innerText == "0.00") {
                                item_unit_price.innerText = "0.00";
                                clicked = 0;
                                price_clicked = 0;
                                price_remove = 1;
                            } else {
                                item_unit_price.innerText = item_unit_price.innerText.substring(0, (item_unit_price.innerText.length - 1));
                            }

                            // Re-calculate item and cart total price based on current qty
                            var updatedItemSubTotal = itemQty.innerText * item_unit_price.innerText;
                            var updatedCartTotal = parseFloat(cart_total_price) - (item_total_price - updatedItemSubTotal);

                            // Update item and cart total price
                            document.getElementsByClassName("selected")[0].children[1].children[1].innerText = currencyFormat.format(updatedItemSubTotal);
                            $(".summary").find(".value").first().text(currencyFormat.format(updatedCartTotal));
                        } else {
                            price_remove = 0;
                        }
                        break;
                }

                if ($(".selected").length > 0) {
                    var id = $(".selected").parent().data("id");
                    var name = $(".selected").parent().data("name");
                    UpdateProduct([id, name, item_unit_price.innerText, itemQty.innerText]);
                }
            }
        });
    });

    function searchBarcode() {
        var barcode = $("#txtSearch").val();
        $.ajax({
            url: "/product/barcode/find",
            type: "GET",
            data: {"barcode": barcode},
            dataType: "json",
            success: function(data) {
                if(typeof data.product == "string") {
                    $.toast({
                        heading: 'Error!',
                        text: "Barcode is not associated with any product",
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'info',
                        hideAfter: 4000,
                        stack: 6
                    });
                }

                $(".product").remove();
                var variations = data.variations;
                var url = "{{ asset('/inventory/products/') }}" + "/";
                $.each(variations, function(k, v) {
                    var pname = v.product_name.toString();
                    for(var i=0; i <= v.product_name.toString().length; i++) {
                        pname = pname.replace(" ", "%20");
                    }
                    var img_url = url + pname + "/" + v.variation_img;
                    var html = "<div class=\"product\" id=\"p-" + v.batch_id + "\" data-batch-id=\"" + v.batch_id +
                        "\" data-name=\"" + v.variation_name + "\" data-price=\"" + v.retail_price + "\"> <div class=\"product-img\"><img src=" +
                        img_url + " class=\"img-fluid\">";
                    html += "<span class=\"price-tag\"><strong><span style=\"text-decoration: line-through\">N</span>" +
                        "<span class=\"price\">" + v.retail_price + "</span></strong></span>";
                    html += "</div><div class=\"product-name\">" + v.variation_name + "</div></div>";
                    $(".product-list").append(html);
                });
            }
        });
    }

    function findProduct() {

    }

    // Open cash tender drawer
    function openDrawer() {
        document.getElementById("pay-drawer").style.width = "100%";
    }

    // Close cash tender drawer
    function closeDrawer() {
        document.getElementById("pay-drawer").style.width = "0";
    }

    function showReceipt() {
        document.getElementById("sales-receipt").style.width = "100%";
    }

    function hideReceipt() {
        document.getElementById("sales-receipt").style.width = "0";
    }

    // Retrieve cart items in session and display
    function getCartAndDisplay() {
        var jqxhr = $.ajax({
            url: "/pos/cart",
            type: "GET",
            dataType: "json"
        });

        jqxhr.done(function (response, textStatus, jqXHR) {
            // This will remove the 'selected' class from already existing cart item
            // Remember this runs only when a new product is clicked, so logically it
            // will remove highlight from the previously highlighted div element
            // to enable the newly added div element to have the 'selected' class
            if (response.length > 0) {
                if (!$(".order-empty").hasClass("d-none")) {
                    $(".order-empty").addClass("d-none");
                }

                // Loop through result, create html and append to cart
                // TODO: Add items to a sort of cart collection
                var total_price = 0.00;
                $.each(response, function (k, v) {
                    for (var key in v) {
                        var cart_item = "<ul class='order-lines' onclick='event.preventDefault(); clickedCartItem(" + v[key].id + ");' id='" + v[key].id + "' data-id='" + v[key].id + "' data-name='" + v[key].name + "'>";
                        cart_item += "<li class='order-line'><span class='product-name'>" +
                            v[key].name + "</span><span class='price'><span style='text-decoration: line-through'>N</span><span class='price-value'>"
                            + currencyFormat.format(parseFloat(v[key].quantity) * parseFloat(v[key].price)) + "</span></span><ul class='info-list'><li class='info'>" +
                            "<em>Qty: <span id='prod-quantity'>" + v[key].quantity + "</span></em>" + " at "
                            + "<span style='text-decoration: line-through'>N</span><span class='price-value'>" + v[key].price + "</span>"
                            + "</li></ul></li></ul>";

                        total_price = total_price + (parseFloat(v[key].price) * parseFloat(v[key].quantity));

                        $(".order-scroll > .order > .order-list").append(cart_item);
                    }
                });

                // Update cart total price
                document.getElementsByClassName("summary")[0].firstElementChild.children[0]
                    .children[2].innerText = currencyFormat.format(total_price);

                // Show price summary
                if ($(".summary").hasClass("d-none")) {
                    $(".summary").removeClass("d-none");
                }
            }

            // If no item in cart, length of '.order-lines' will be zero(0)
            // If zero, show the 'cart is empty' icon and text
            // Also, remove cart total price summary
            // This is important for a case where no item is in cart list
            // I think this is redundant or if not, too much work.
            // TODO: Make code more efficient.
            if (parseInt($(".order").find(".order-lines").length) == 0) {
                if ($(".order-empty").hasClass("d-none")) {
                    $(".order-empty").removeClass("d-none");
                }

                if (!$(".summary").hasClass("d-none")) {
                    $(".summary").addClass("d-none");
                }
            }
        });
    }

    // Toggle selection for cart items (on item click event)
    function clickedCartItem(id) {
        clicked = 0;
        $(".selected").removeClass("selected");
        var order = document.getElementById(id);
        $(".order-line").removeClass("selected");
        $(".order-list").find("#" + id).children(".order-line").addClass("selected");
    }

    // Logic for displaying products and details in cart
    function AddProductToCart(values) {
        var id = values[0];
        var name = values[1];
        var price = values[2];
        var quantity = values[3];

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var jqxhr = $.ajax({
            url: "/pos/cart/store",
            type: "POST",
            data: {"batch_id": id, "name": name, "price": price, "quantity": quantity}
        });

        jqxhr.done(function (response, textStatus, jqXHR) {
            if ($(".order-line").hasClass("selected")) {
                $(".order-line").removeClass("selected");
            }

            var product = $("#p-" + id);

            if(response.new_batch_exists == "true" || response.new_batch_exists == "regular") {
                if(response.new_batch) {
                    var new_batch = response.new_batch;
                    id = new_batch.id;
                    price = new_batch.retail_price;

                    // If request has exceeded current stock quantity and a new stock exists,
                    // replace batch_id of product in the list with the new batch_id
                    product.attr("id", "p-"+id);
                    product.attr("data-batch-id", id);
                    product.attr("data-price", price);
                    product.find(".price").text(price);
                }

                var item_exists = document.getElementById(id);
                if (item_exists == null) {
                    appendToCart(id, name, price, quantity);
                } else {
                    // Re-highlight already existing cart item (div)
                    if (!item_exists.firstElementChild.classList.contains("selected")) {
                        item_exists.firstElementChild.classList.add("selected");
                    }

                    // Update product quantity
                    var qty = item_exists.firstElementChild.children[2].children[0].children[0]
                        .children["prod-quantity"].innerHTML;
                    item_exists.firstElementChild.children[2].children[0].children[0]
                        .children["prod-quantity"].innerHTML = (parseInt(qty, 10) + 1).toString();

                    // update item subtotal
                    var item_price = item_exists.firstElementChild.children[2].children[0].children[2].innerText;
                    var updatedItemTotalPrice = (parseInt(qty, 10) + 1) * item_price;
                    document.getElementsByClassName("selected")[0].children[1].children[1].innerText = currencyFormat.format(updatedItemTotalPrice);

                    // Update cart total price
                    var displayed_total_unit_price = document.getElementsByClassName("summary")[0]
                        .firstElementChild.children[0].children[2].innerText.replace(",", "").replace(".00", "");
                    document.getElementsByClassName("summary")[0]
                        .firstElementChild.children[0].children[2]
                        .innerText = currencyFormat.format(parseFloat(displayed_total_unit_price) + parseFloat(item_price));

                    // Update PHP cart in session
                    var batch_id = item_exists.attributes[2].value;
                    var product_name = item_exists.attributes[4].value;
                    var product_price = parseInt(item_price, 10);
                    var product_quantity = (parseInt(qty, 10) + 1);

                    UpdateProduct([batch_id, product_name, product_price, product_quantity]);
                }

            }  else if(response.new_batch_exists == "false") {
                // remove item from product listing
                product.remove();

                // display a message
                $.toast({
                    heading: "Stock is Finished",
                    text: "Product stock has been exhausted!",
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: "info",
                    hideAfter: 6000,
                    stack: 6
                });
            }
        });
    }

    // Update an existing cart item
    function UpdateProduct(values) {
        var id = values[0];
        var name = values[1];
        var price = values[2];
        var quantity = values[3];

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var jqxhr = $.ajax({
            url: "/pos/cart/store",
            type: "POST",
            data: {"batch_id": id, "name": name, "price": price, "quantity": quantity}
        });

        // Callback handler that will be called on success
        jqxhr.done(function (response, textStatus, jqXHR) {
            /*$.each(response, function(k, v) {
                for (var key in v) {
                    var cart_item = "<ul class='order-lines' onclick='event.preventDefault(); clickedCartItem(" + v[key].id + ");' id='" + v[key].id + "' data-id='" + v[key].id + "' data-name='" + v[key].name + "'>";
                    cart_item += "<li class='order-line selected'><span class='product-name'>" +
                            name + "</span><span class='price'><span style='text-decoration: line-through'>N</span><span class='price-value'>"
                            + currencyFormat.format(v[key].price) + "</span></span><ul class='info-list'><li class='info'>" +
                            "<em>Qty: <span id='prod-quantity'>" + v[key].quantity + "</span></em>" + " at "
                            + "<span style='text-decoration: line-through'>N</span><span class='price-value'>" + v[key].price + "</span>"
                            + "</li></ul></li></ul>";
                    console.log(cart_item);
                }
            });*/
        });
    }

    // Remove a product from cart based on given id
    function RemoveProduct(id) {
        var jqxhr = $.ajax({
            url: "/pos/cart/delete/" + id,
            type: "DELETE"
        });

        jqxhr.done(function (response, textStatus, jqXHR) {
            // Show toast notification
        });
    }

    // Append new item to cart
    function appendToCart(id, name, price, quantity) {
        var cart_item = "<ul class='order-lines' onclick='event.preventDefault(); clickedCartItem(" + id + ");' id='" + id + "' data-id='" + id + "' data-name='" + name + "'>";
        cart_item += "<li class='order-line selected'><span class='product-name'>" +
            name + "</span><span class='price'><span style='text-decoration: line-through'>N</span><span class='price-value'>"
            + currencyFormat.format(price) + "</span></span><ul class='info-list'><li class='info'>" +
            "<em>Qty: <span id='prod-quantity'>" + quantity + "</span></em>" + " at "
            + "<span style='text-decoration: line-through'>N</span><span class='price-value'>" + price + "</span>"
            + "</li></ul></li></ul>";

        var total_price = document.getElementsByClassName("summary")[0].firstElementChild.children[0]
            .children[2].innerText.replace(",", "").replace(".00", "");
        total_price = parseFloat(total_price) + parseFloat(price * quantity);

        $(".order-scroll > .order > .order-list").append(cart_item);

        // Update cart total price
        document.getElementsByClassName("summary")[0].firstElementChild.children[0]
            .children[2].innerText = currencyFormat.format(total_price);

        // Show price summary
        if ($(".summary").hasClass("d-none")) {
            $(".summary").removeClass("d-none");
        }
    }
</script>
</body>
