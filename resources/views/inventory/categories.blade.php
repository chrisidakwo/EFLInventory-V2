<?php
    $title = "Product Categories";
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
                        Create New Category
                    </div>
                    <div class="card-body">
                        <form action="{{ route("storeCategory") }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="txtCategoryName">Category Name:</label>
                                <input id="txtCategoryName" type="text" name="name" class="form-control" required autofocus>
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
                            <table id="tblAllCategories"  class="display nowrap table table-hover table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Category Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Category Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @if($categories)
                                        @foreach($categories as $category)
                                            <tr>
                                                <td>{{ $category->id }}</td>
                                                <td>{{ $category->name }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#categoryEdit{{ $category->id }}">
                                                        <i class="fa fa-pencil"></i> Edit
                                                    </button>

                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#categoryDelete{{$category->id}}">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="categoryEdit{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="lblTitleEdit{{ $category->id }}">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="lblTitleEdit{{ $category->id }}">Edit Category</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <form action="{{ route("editCategory", $category->slug) }}" method="post">
                                                            {{ csrf_field() }}
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="txtCategoryNameEdit{{ $category->id }}">Category Name:</label>
                                                                    <input id="txtCategoryNameEdit{{ $category->id }}" type="text" name="name" class="form-control" value="{{ $category->name }}" required autofocus>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="submit" value="Update" class="btn btn-info">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                <input type="hidden" value="{{ $category->slug }}" name="category_slug">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="categoryDelete{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="lblTitleDelete{{ $category->id }}">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="lblTitleDelete{{ $category->id }}">Delete Category</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <form action="{{ route("deleteCategory", $category->slug) }}" method="post">
                                                            {{ csrf_field() }}
                                                            <div class="modal-body">
                                                                <strong>Category Name: </strong> {{ $category->name }}
                                                                <br>
                                                                Are you sure you want to delete this item?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="submit" value="Yes, Delete" class="btn btn-info">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">No, Cancel</button>
                                                                <input type="hidden" value="{{ $category->name }}" name="name">
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
        var table = $("#tblAllCategories").DataTable({
            dom: 'Blfrtip',
            buttons: [
                'excel', 'pdf', 'print'
            ]
        });
    });
</script>
@endpush