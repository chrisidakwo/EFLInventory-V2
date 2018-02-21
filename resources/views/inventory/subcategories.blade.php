<?php
$title = "Product SubCategories";
$page_title = $title;
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
                        Create New SubCategory
                    </div>
                    <div class="card-body">
                        <form action="{{ route("storeSubCategory") }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="txtSubCategoryName">SubCategory Name:</label>
                                <input id="txtSubCategoryName" type="text" name="name" class="form-control" required autofocus>
                            </div>

                            <div class="form-group">
                                <label for="ddlCategory">Select Category:</label>
                                <select id="ddlCategory" name="category_id" class="form-control">
                                    @if($categories)
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @else
                                        <option name="default" value="0">No category in database</option>
                                    @endif
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
                            <h3 class="card-title">View All Categories</h3>
                            <h6 class="card-subtitle">Product categories and actions</h6>
                        </div>

                        <div class="responsive-table">
                            <table id="tblAllSubCategories" class="display nowrap table table-hover table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>SubCategory</th>
                                    <th>Category</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>SubCategory</th>
                                    <th>Category</th>
                                    <th>Actions</th>
                                </tr>
                                </tfoot>

                                <tbody>
                                @if($subcategories)
                                    @foreach($subcategories as $subcategory)
                                        <tr>
                                            <td>{{ $subcategory->id }}</td>
                                            <td>{{ $subcategory->name }}</td>
                                            <td>{{ $subcategory->category->name }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCategoryEdit{{ $subcategory->id }}">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </button>

                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#subCategoryDelete{{$subcategory->id}}">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="subCategoryEdit{{ $subcategory->id }}" tabindex="-1" role="dialog" aria-labelledby="lblTitleEdit{{ $subcategory->id }}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="lblTitleEdit{{ $subcategory->id }}">Edit Category</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <form action="{{ route("editSubCategory", $subcategory->slug) }}" method="post">
                                                        {{ csrf_field() }}
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="txtCategoryNameEdit{{ $subcategory->id }}">Category Name:</label>
                                                                <input id="txtCategoryNameEdit{{ $subcategory->id }}" type="text" name="name" class="form-control" value="{{ $subcategory->name }}" required autofocus>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="ddlCategoryEdit">Select Category:</label>
                                                                <select id="ddlCategoryEdit" name="category_id" class="form-control">
                                                                    @if($categories)
                                                                        @foreach($categories as $category)
                                                                            @if($category == $subcategory->category)
                                                                                <option value="{{ $category->id }}" selected aria-selected="true">{{ $category->name }}</option>
                                                                            @else
                                                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="submit" value="Update" class="btn btn-info">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                            <input type="hidden" value="{{ $subcategory->slug }}" name="category_slug">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="subCategoryDelete{{ $subcategory->id }}" tabindex="-1" role="dialog" aria-labelledby="lblTitleDelete{{ $subcategory->id }}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="lblTitleDelete{{ $subcategory->id }}">Delete Category</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <form action="{{ route("deleteSubCategory", $subcategory->slug) }}" method="post">
                                                        {{ csrf_field() }}
                                                        <div class="modal-body">
                                                            <strong>SubCategory Name: </strong> {{ $subcategory->name }}
                                                            <br>
                                                            Are you sure you want to delete this subcategory?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="submit" value="Yes, Delete" class="btn btn-info">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">No, Cancel</button>
                                                            <input type="hidden" value="{{ $subcategory->name }}" name="name">
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


@push('extra-js')
<script src="{{ asset("js/jquery.dataTables.min.js") }}"></script>
<script src="{{ asset("js/dataTables.buttons.min.js") }}"></script>
<script src="{{ asset("js/buttons.flash.min.js") }}"></script>
<script src="{{ asset("js/jszip.min.js") }}"></script>
<script src="{{ asset("js/pdfmake.min.js") }}"></script>
<script src="{{ asset("js/vfs_fonts.js") }}"></script>
<script src="{{ asset("js/buttons.html5.min.js") }}"></script>
<script src="{{ asset("js/buttons.print.min.js") }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var table = $("#tblAllSubCategories").DataTable({
            dom: 'Blfrtip',
            buttons: [
                'excel', 'pdf', 'print'
            ]
        });
    });
</script>
@endpush