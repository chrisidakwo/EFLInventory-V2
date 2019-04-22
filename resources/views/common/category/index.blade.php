@extends("layouts.app")

@section("title")
    Categories
@endsection

@section("content")
    @include("partials.flash")

    <section class="page-header">
        <h4 class="page-title">Categories</h4>
    </section>

    <div class="row mb-3">
        <div class="col-12">
            <a href="javascript:void(0)" onclick="$('#dvNewCategory').toggle('fade');" class="btn btn-dark">
                <i class="mdi mdi-plus-box mr-2"></i>New Category
            </a>

            <div id="dvNewCategory" class="mt-2" style="display: none;">
                <div class="row">
                    <div class="col-sm-12 col-md-8 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route("category.store") }}" method="post">
                                    @csrf

                                    <div class="form-group">
                                        <label for="txtName">Category Name</label>
                                        <input type="text" id="txtName" class="form-control" name="name" required autofocus>
                                    </div>

                                    <div class="form-group">
                                        <label for="ddlParent">Parent Category</label>
                                        <select name="parent" id="ddlParent" class="form-control" required>
                                            <option value="none">None</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="ddlType">Category Type</label>
                                        <select name="type" id="ddlType" class="form-control" required>
                                            <option value="inventory">Inventory</option>
                                            <option value="purchase">Purchase</option>
                                            <option value="sales">Sales</option>
                                        </select>
                                    </div>


                                    <button class="btn btn-success mb-1" type="submit">Submit</button>
                                    <button class="btn btn-danger mb-1" type="button" onclick="$('#dvNewCategory').hide('fade');">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STATS CARD -->
    <div class="row mb-3">
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

    <!-- INVENTORY CATEGORY -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card stats-card" style="height: 300px">
                <div class="card-header">
                    <span class="card-title">
                        <i class="mdi mdi-trending-up mr-2"></i>Inventory Category
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table stats-table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Total Products</th>
                                <th>Total Valuation</th>
                                <th>Actions</th>
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

    <!-- PURCHASE CATEGORY -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card stats-card" style="height: 300px">
                <div class="card-header">
                    <span class="card-title">
                        <i class="mdi mdi-trending-up mr-2"></i>Purchase Category
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table stats-table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Reference</th>
                                <th>Products</th>
                                <th>Total Cost</th>
                                <th>Amount Paid</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center">None yet!</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SALES CATEGORY -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card stats-card" style="height: 300px">
                <div class="card-header">
                    <span class="card-title">
                        <i class="mdi mdi-trending-up mr-2"></i>Sales Category
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table stats-table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Reference</th>
                                <th>Sold By</th>
                                <th>Products</th>
                                <th>Total Cost</th>
                                <th>Amount Paid</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center">None yet!</td>
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
@endsection
