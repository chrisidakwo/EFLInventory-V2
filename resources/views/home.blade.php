@extends('layouts.app')

@section("title")
    Dashboard
@endsection

@section('content')
    @include("partials.flash")

    <section class="page-header">
        <h4 class="page-title">Dashboard</h4>
    </section>

    <div class="row mb-4">
        <div class="col-sm-12 col-md-4">
            <div class="stats-card card mb-3">
                <div class="card-header">
                        <span class="card-title">
                            Total Sales for Today
                        </span>
                </div>
                <div class="card-body">
                    <h4 class="stats-card-figure">&#8358;561,920.00</h4>
                    <p class="stats-card-figure-label">
                        <i class="mdi mdi-trending-up text-success mr-2"></i>20% increase from yesterday
                    </p>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="stats-card card mb-3">
                <div class="card-header">
                        <span class="card-title">
                            Total Sales for Today
                        </span>
                </div>
                <div class="card-body">
                    <h4 class="stats-card-figure">&#8358;561,920.00</h4>
                    <p class="stats-card-figure-label">
                        <i class="mdi mdi-trending-up text-success mr-2"></i>20% increase from yesterday
                    </p>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="stats-card card mb-3">
                <div class="card-header">
                        <span class="card-title">
                            Total Sales for Today
                        </span>
                </div>
                <div class="card-body">
                    <h4 class="stats-card-figure">&#8358;561,920.00</h4>
                    <p class="stats-card-figure-label">
                        <i class="mdi mdi-trending-up text-success mr-2"></i>20% increase from yesterday
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-sm-12 col-md-6">
            <div class="card stats-card mb-3">
                <div class="card-header">
                    <span class="card-title">Low Stock</span>
                </div>

                <div class="table-responsive">
                    <table class="table stats-table table-striped">
                        <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Product</th>
                            <th>Threshold</th>
                            <th>Available</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td colspan="4" class="text-center">We're doing a good job!</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6">
            <div class="card stats-card mb-3">
                <div class="card-header">
                    <span class="card-title">Expired Products</span>
                </div>

                <div class="table-responsive">
                    <table class="table stats-table table-striped">
                        <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Product</th>
                            <th>Exp. Date</th>
                            <th>Qty</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td colspan="4" class="text-center">Let's keep it this way!</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-5 col-lg-3">
                            <form action="">
                                <label for="ddlYear">Select Year</label>
                                <select name="year" id="ddlYear" class="form-control">
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 col-sm-12">
                            <div class="bg-dark p-4 text-center">
                                <h5 class="mb-2 text-white">Total Sales for 2019</h5>
                                <img src="{{ asset("img/graph.png") }}" class="img-fluid" alt="Graph">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="bg-dark p-4 text-center">
                                <h5 class="mb-2 text-center text-white">Sales(%) by Category</h5>
                                <img src="{{ asset("img/pie-chart.png") }}" class="img-fluid" alt="Pie chart">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-sm-6 col-md-3">
            <div class="card stats-card mb-3">
                <div class="card-header">
                    <span class="card-title">
                        <i class="mdi mdi-trending-up mr-2"></i>High in Demand
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table stats-table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty Sold</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center">None yet!</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card stats-card mb-3">
                <div class="card-header">
                    <span class="card-title">
                        <i class="mdi mdi-trending-down mr-2"></i>Low in Demand
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table stats-table table-striped">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty Sold</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td colspan="4" class="text-center">None yet!</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card stats-card mb-3">
                <div class="card-header">
                        <span class="card-title">
                            <i class="mdi mdi-cash-multiple mr-2"></i>Highest Earning
                        </span>
                </div>

                <div class="table-responsive">
                    <table class="table stats-table table-striped">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty Sold</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td colspan="4" class="text-center">None yet!</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card stats-card">
                <div class="card-header">
                        <span class="card-title">
                            <i class="mdi mdi-cash-multiple mr-2"></i>Lowest Earning
                        </span>
                </div>

                <div class="table-responsive">
                    <table class="table stats-table table-striped">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty Sold</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td colspan="4" class="text-center">None yet!</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-sm-12 col-md-6 mb-3">
            <div class="card stats-card">
                <div class="card-header">
                        <span class="card-title">
                            Daily Transactions
                        </span>
                </div>

                <div class="table-responsive">
                    <table class="table stats-table table-striped">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Qty Sold</th>
                            <th>Total Sales</th>
                            <th>Total Profit</th>
                            <th>Total Loss</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td colspan="5" class="text-center">None yet!</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 mb-3">
            <div class="card stats-card">
                <div class="card-header">
                        <span class="card-title">
                            Transactions by Days
                        </span>
                </div>

                <div class="table-responsive">
                    <table class="table stats-table table-striped">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Qty Sold</th>
                            <th>Total Sales</th>
                            <th>Total Profit</th>
                            <th>Total Loss</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td colspan="5" class="text-center">None yet!</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 mb-3">
            <div class="card stats-card">
                <div class="card-header">
                        <span class="card-title">
                            Transactions by Months
                        </span>
                </div>

                <div class="table-responsive">
                    <table class="table stats-table table-striped">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Qty Sold</th>
                            <th>Total Sales</th>
                            <th>Total Profit</th>
                            <th>Total Loss</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td colspan="5" class="text-center">None yet!</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 mb-3">
            <div class="card stats-card">
                <div class="card-header">
                        <span class="card-title">
                            Transactions by Years
                        </span>
                </div>

                <div class="table-responsive">
                    <table class="table stats-table table-striped">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Qty Sold</th>
                            <th>Total Sales</th>
                            <th>Total Profit</th>
                            <th>Total Loss</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td colspan="5" class="text-center">None yet!</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
