<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="/css/bootstrap.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title')</title>
	<style>
	body{
		
		/* background-color: rgba(0,0,0,.07); */
		padding-top: 4%;
		padding-bottom: 3%;

	}
	.container{
		background-color: white;
		/* box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%) !important; */
	}
	.form-control{
		border: none;
	}
	.garis{
		border-bottom: 2px solid black;
	}
	.hilang{
		display: none;

	}
	#foot-login{
		margin-left: 30px;
		margin-top: 60px;
	}
	#btn-ganti{
		width: 75%;
	}
	#cancel{
		width: 23%;
	}
	@media(max-width:768px )
	{
		#foot-login{
			margin-left: 70px;
			margin-top: 0px;
		}
		#login-contact{
			margin-left: 10px;
		}
		#btn-ganti{
			width: 100%;
		}
		#cancel{
			width: 100%;
		}

	}

	@media(max-width:575px )
	{
		#foot-login{
			margin-left: 40px;
			margin-top: 0px;
		}
		#login-contact{
			margin-left: 20px;
		}
		#btn-ganti{
			width: 100%;
		}
		#cancel{
			width: 100%;
		}
		body{
			padding-top: 0%;
			padding-bottom: 0%;
		}
	}
	
</style>
<link rel="stylesheet" href="/icon/css/font-awesome.min.css">
<script src="/js/jquery.js"></script>
</head>
<body>
	<div class="container pl-0">
		@yield('content-alert')
	</div>
	<div class="container p-5">
		@yield('content')
	</div>
</body>
</html>