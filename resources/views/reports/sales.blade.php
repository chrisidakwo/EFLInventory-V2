<?php
$title = "Sales";
$page_title = "All Sales Transactions";
?>

@extends("layouts.app")

@section("content")
    <div class="row">
        <div class="col-md-12">
            <div class="card animated zoomIn">
                <div class="card-body">
                    <div>
                        <h3 class="card-title">View All Sales History</h3>
                        <h6 class="card-subtitle">Product sales transactions</h6>
                    </div>

                    <div class="responsive-table">
                        <table id="tblAllSales"  class="display nowrap table table-hover table-bordered" cellspacing="0" width="100%" role="grid">
                            <thead>
                                <tr>
                                    <th>Products</th>
                                    <th>Total Amount</th>
                                    <th>Amount Tendered</th>
                                    <th>Payment Method</th>
                                    <th>Balance Due</th>
                                    <th>Change</th>
                                    <th>Seller</th>
                                    <th>Receipt No</th>
                                    <th>Sales Date</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Products</th>
                                    <th>Total Amount</th>
                                    <th>Amount Tendered</th>
                                    <th>Payment Method</th>
                                    <th>Balance Due</th>
                                    <th>Change</th>
                                    <th>Seller</th>
                                    <th>Receipt No</th>
                                    <th>Sales Date</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->products }}</td>
                                    <td><span class="naira">N</span>{{ number_format($transaction->total_amount, 2) }}</td>
                                    <td><span class="naira">N</span>{{ number_format($transaction->amount_tendered, 2) }}</td>
                                    <td>{{ $transaction->payment_method }}</td>
                                    <td><span class="naira">N</span>{{ number_format($transaction->balance_due, 2) }}</td>
                                    <td><span class="naira">N</span>{{ number_format($transaction->change_amount, 2) }}</td>
                                    <td>{{ $transaction->seller }}</td>
                                    <td>{{ $transaction->receipt_no }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('D, M j, Y g:i:s A') }}</td>
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
            var table = $("#tblAllSales").DataTable({
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