<?php
	$title = "Products";
	$page_title = title_case("View All Products");
?>
@push("extra-css")
	{{--<link rel="stylesheet" href="css/jquery.dataTables.min.css">--}}
@endpush

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

	<section>
		<div class="row">
			<div class="col-12">
				<div class="mb-4 animated fadeInDown">
					<a href="{{ route("createProduct") }}" class="btn btn-danger btn-md"><i class="mdi mdi-plus-circle-outline"></i> Add New Product</a>
				</div>
				<div class="card animated zoomIn">
					<div class="card-body">
						<h4 class="card-title">ALL PRODUCTS</h4>
						<h6 class="card-subtitle">List of all saleable products</h6>
						<div class="table-responsive m-t-30">
							<table id="tblAllProducts" class="display nowrap table table-hover table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>SKU</th>
										<th>Product Name</th>
										<th>Category</th>
										<th>SubCategory</th>
										<th>Stock</th>
										<th>Retail Price</th>
										<th>Actions</th>
									</tr>
								</thead>

								<tfoot>
									<tr>
										<th>SKU</th>
										<th>Product Name</th>
										<th>Category</th>
										<th>SubCategory</th>
										<th>Stock</th>
										<th>Retail Price</th>
										<th>Actions</th>
									</tr>
								</tfoot>

								<tbody>
										@foreach($products as $product)
											<tr>
												<td>{{ $product->sku }}</td>
												<td>{{ $product->variation_name }}</td>
												<td>{{ $product->category }}</td>
												<td>{{ $product->sub_category }}</td>
												<td>{{ $product->available_stock }}</td>
												<td>{{ $product->retail_price }}</td>
												<td>
													<button class="btn btn-primary mb-2" data-toggle="modal" data-target="#1Modal" data-sku="{{ $product->sku }}"><i class="mdi mdi-eye"></i> View</button>
													<a href="{{ route("editProduct", $product->product_slug) }}" class="btn btn-success mb-2"><i class="mdi mdi-pen"></i> Edit</a>
												</td>
											</tr>
										@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="modal fade" id="1Modal" tabindex="-1" role="dialog" aria-labelledby="1ModalLbl">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="1ModalLbl">Coke Bigger Boy</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
							</div>
							<div class="modal-body">
								<dl class="row">
									<dt class="col-sm-4 text-right">SKU:</dt>
									<dd class="col-sm-8">LQSD-COBB</dd>
									<dt class="col-sm-4 text-right">Weight:</dt>
									<dd class="col-sm-8">50cl</dd>
									<dt class="col-sm-3 mt-5"></dt>
									<dd class="col-sm-9 mt-5">
										<div class="float-right">
											<ul class="list-inline">
												<li><label for="ddlDateLine">Select Period:</label></li>
												<li>
													<select id="ddlDateLine">
														<option>All History</option>
														<option>Last 30 days</option>
														<option>Last 2 months</option>
														<option>Last 3 months</option>
													</select>
												</li>
											</ul>
										</div>
									</dd>
									<dt class="col-sm-4 text-right">Total Purchase:</dt>
									<dd class="col-sm-8"><span style="text-decoration: line-through">N</span>375,050.00</dd>
									<dt class="col-sm-4 text-right">Total Sales:</dt>
									<dd class="col-sm-8"><span style="text-decoration: line-through">N</span>350,411.65</dd>
									<dt class="col-sm-4 text-right">Total Profit:</dt>
									<dd class="col-sm-8"><span style="text-decoration: line-through">N</span>87,500.00</dd>
									<dt class="col-sm-4 text-right">Total Loss:</dt>
									<dd class="col-sm-8"><span style="text-decoration: line-through">N</span>750.00</dd>
									<dt class="col-sm-4 mt-4 text-right">Damaged Items:</dt>
									<dd class="col-sm-8 mt-4">5</dd>
									<dt class="col-sm-4 text-right">Expired Items:</dt>
									<dd class="col-sm-8">0</dd>
								</dl>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection

@push('extra-js')
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.buttons.min.js"></script>
	<script src="js/buttons.flash.min.js"></script>
	<script src="js/jszip.min.js"></script>
	<script src="js/pdfmake.min.js"></script>
	<script src="js/vfs_fonts.js"></script>
	<script src="js/buttons.html5.min.js"></script>
	<script src="js/buttons.print.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			var table = $("#tblAllProducts").DataTable({
				dom: 'Blfrtip',
				buttons: [
					'excel', 'pdf', 'print'
				]
			});
		});
	</script>
@endpush
