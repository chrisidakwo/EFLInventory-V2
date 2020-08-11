<?php
$title = "Purchases";
$page_title = "All Purchase Transactions";
?>

@extends("layouts.app")

@section("content")
    <div class="row">
        <div class="col-md-12">
            <div class="card animated zoomIn">
                <div class="card-body">
                    <div>
                        <h3 class="card-title">View All Purchase History</h3>
                        <h6 class="card-subtitle">Product purchase transactions</h6>
                    </div>

                    <div class="responsive-table">
                        <table id="tblAllPurchases"  class="display nowrap table table-hover table-bordered" cellspacing="0" width="100%" role="grid">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Batch ID</th>
                                    <th>Quantity Purchased</th>
                                    <th>Total Cost</th>
                                    <th>Amount Paid</th>
                                    <th>Balance Due</th>
                                    <th>Purchase Date</th>
                                </tr>
                            </thead>
{{--                            <tfoot>--}}
{{--                            <tr>--}}
{{--                                <th>Product</th>--}}
{{--                                <th>Batch ID</th>--}}
{{--                                <th>Quantity Purchased</th>--}}
{{--                                <th>Total Cost</th>--}}
{{--                                <th>Amount Paid</th>--}}
{{--                                <th>Balance</th>--}}
{{--                                <th>Change</th>--}}
{{--                                <th>Purchase Date</th>--}}
{{--                            </tr>--}}
{{--                            </tfoot>--}}
                            <tbody>
                                @foreach($transactions as $transaction)
                                    <tr>
                                        @foreach($variations as $variation)
                                            @if($transaction->variation_id == $variation->id)
                                                <td>{{ $variation->variation_name }}</td>
                                            @endif
                                        @endforeach
                                        @foreach($batches as $batch)
                                            @if($transaction->batch_id == $batch->id)
                                                <td>{{ $transaction->batch_id }}</td>
                                                <td>{{ $batch->ordered_quantity }}</td>
                                                <td>{{ number_format($transaction->total_amount, 2) }}</td>
                                                <td>{{ number_format($transaction->amount_paid, 2) }}</td>
                                            @endif
                                        @endforeach
                                        <td>{{$transaction->balance_due}}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaction->created_at)->toFormattedDateString() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('extra-js')
<script src="{{ asset("js/jquery.dataTables.min.js") }}"></script>
<script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('js/jszip.min.js') }}"></script>
<script src="{{ asset('js/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/vfs_fonts.js') }}"></script>
<script src="{{ asset('js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('js/buttons.print.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var table = $("#tblAllPurchases").DataTable({
            dom: 'Blfrtip',
            buttons: [
                'excel', 'pdf', 'print'
            ],
            "order": [
                    [1, "asc"]
            ],
            "displayLength":25
        });
    });
</script>
@endpush
