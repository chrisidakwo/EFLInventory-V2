<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/png" href="{{ asset("img/brand/favicon.png") }}">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title><?php echo $title ?> | EmiFoodLovers</title>

		<!-- Styles -->
		<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
		<link href="{{ asset('css/colors/green.css') }}" id="theme" rel="stylesheet">
		<link href="{{ asset('css/theme/app.css') }}" rel="stylesheet">
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	</head>
	<body class="auth-page">
		<div class="preloader">
			<svg class="circular" viewBox="25 25 50 50">
				<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" ></circle>
			</svg>
		</div>

		@yield("content")

		<!-- Scripts -->
		<script src="{{ asset('js/jquery.min.js') }}"></script>
		<script src="{{ asset('js/popper.min.js') }}"></script>
		<script src="{{ asset('js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('js/theme/custom.min.js') }}"></script>
	</body>
</html>
