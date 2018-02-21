<?php
	$title = "Add New Product";
	$page_title = "Add New Product";
?>
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

	<section class="row">
		<div class="col-lg-4">
			<div class="card animated zoomIn">
				<div class="card-header bg-info text-white">
					Product
				</div>
				<div class="card-body">
					<form action="{{ route("storeProduct") }}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-group">
							<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
								<span class="btn btn-danger btn-file m-r-10">
									<span class="fileinput-new">Select Product Image</span>
									<span class="fileinput-exists">Change Product Image</span>
									<input id="txtProductImage" type="file" name="product-image">
								</span>
								{{--<span class="fileinput-filename"></span>--}}
								<a href="#" class="btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
							</div>


							{{--<div class="fileupload btn btn-danger btn-rounded waves-effect waves-light">
								<span>
									<i class="mdi mdi-upload"></i> Upload Product Image
								</span>
								<input id="txtProductImage" name="product-image" class="upload" type="file">
							</div>--}}
						</div>

						<div class="form-group">
							<label for="txtProductName">Product Name</label>
							<input id="txtProductName" name="name" class="form-control" type="text">
						</div>

						<div class="form-group">
							<label for="ddlProductCategory">Category:</label>
							<div>
								<select id="ddlProductCategory" class="form-control" name="product-category">
									@foreach($categories as $category)
										<option value="{{ $category->id }}">{{ $category->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="ddlSubCategories">Sub-Category:</label>
							<select id="ddlSubCategories" name="product-subcategory" class="form-control">

							</select>
						</div>

						<div class="form-group">
							<label for="lblProductBrand">Brand:</label>
							<select id="lblProductBrand" name="product-brand" class="form-control">
								@foreach($brands as $brand)
									<option value="{{ $brand->id }}">{{ $brand->name }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group">
							<label for="ddlProductVariateBy">Variate By:</label>
							<select id="ddlProductVariateBy" name="product-variate-by" class="form-control">
								<option value="color">Color</option>
								<option value="color">Name</option>
								<option value="size">Size</option>
								<option value="weight">Weight</option>
							</select>
						</div>

						<div class="form-group">
							<label for="txtProductBarcode">UPC Barcode:</label>
							<input id="txtProductBarcode" type="text" name="product-barcode" class="form-control">
						</div>

						<input type="submit" value="Save" class="btn btn-danger btn-md btn-block">
					</form>
				</div>
			</div>
		</div>

		<div class="col-lg-8">
			<div class="card animated zoomIn">
				<div class="card-body">
					<div>
						<h3 class="card-title">View All Products</h3>
						<h6 class="card-subtitle">Products and actions</h6>
					</div>

					<table id="tblAllVariants" class="display nowrap table table-hover table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Product Name</th>
								<th>Image</th>
								<th>Category</th>
								<th>SubCategory</th>
								<th>Brand</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach($products as $product)
								<tr>
									<td>{{ $product->name }}</td>
									<td>
										<img src="{{ asset("/inventory/products/{$product->name}/{$product->thumb_image_path}") }}" class="img-fluid" style="height:50px;">
									</td>
									<td>{{ $product->subcategory->category->name }}</td>
									<td>{{ $product->subcategory->name }}</td>
									<td>{{ $product->brand->name }}</td>
									<td><a href="{{ route("editProduct", $product->slug) }}" class="btn btn-primary"><i class="mdi mdi-eye"></i> View</a></td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
@endsection


@push("extra-js")
	<script src="{{ asset("js/fileinput.min.js") }}"></script>

	<script type="text/javascript">
		function populateSubCategories(id) {
			$.ajax({
				url: "/ajax/get-sub-categories/" + id,
				type: "GET",
				dataType: "json",

				success: function(data) {
					$("#ddlSubCategories").empty();
					$.each(data, function(key, value) {
						$("#ddlSubCategories").append("<option value='" + value.id + "'>" + value.name + "</option>");
					});
				}
			});
		}

		$(document).ready(function() {
			populateSubCategories($("#ddlProductCategory").val());

			$("#ddlProductCategory").change(function() {
				var category_id = $(this).val();
				populateSubCategories(category_id);
			});
		});
	</script>
@endpush
