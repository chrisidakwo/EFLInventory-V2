@extends('layouts.auth')

<?php $title = "Login" ?>

@section('content')
    <section id="wrapper">
        <div class="login-register" style="background-image: url('{{ asset("img/auth/pexels_photo_26.jpeg") }}');">

            <div class="mb-4 animated bounceIn" style="width: 150px; margin: 0 auto;">
                <div class="" style="width: 100%;">
                    <img src="{{ asset("img/brand/logo.png") }}" class="img-fluid justify-content-center">
                </div>
            </div>

            <div class="login-box card animated bounceIn">
                <div class="card-body">
                    <form id="==" action="{{ route('login') }}" method="POST" class="form-horizontal form-material">
                        {{ csrf_field() }}
                        <h3 class="text-center">LOGIN</h3>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control{{ $errors->has('username') ? ' has-error' : '' }}" type="text" name="username" required autofocus placeholder="Username">
                                @if ($errors->has('username'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" name="password" required placeholder="Password">
                                @if ($errors->has('password'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Login</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
