<?php
    $title = "Inventory Valuation";
    $page_title = "Inventory Valuation";
?>

@extends('layouts.app')

@section('content')
    <div class="row">
        <!-- Column -->
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round round-lg align-self-center round-info"><i class="ti-wallet"></i></div>
                        <div class="m-l-10 align-self-center">
                            <h3 class="m-b-0"><span class="naira">N</span>{{ number_format($total_price, 2) }}</h3>
                            <h5 class="text-muted m-b-0">Value of Products in Stock</h5></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round round-lg align-self-center round-warning"><i class="mdi mdi-cellphone-link"></i></div>
                        <div class="m-l-10 align-self-center">
                            <h3 class="m-b-0">$2376</h3>
                            <h5 class="text-muted m-b-0">Online Revenue</h5></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
