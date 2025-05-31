


<!DOCTYPE html>
<html lang="en">
<head>
	<title>login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('template/temp/vendor/bootstrap/css/bootstrap.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('template/temp/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('template/temp/vendor/animate/animate.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{ asset('template/temp/vendor/css-hamburgers/hamburgers.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('template/temp/vendor/select2/select2.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('template/temp/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('template/temp/css/main.css')}}">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic">
					<img src="{{ asset('template/temp/images/logos.png')}}" alt="IMG">
				     <p>Register !</p>
        </div>
        
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div>
            <label for="name">Name:</label>
            <input class="input100" type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input class="input100" type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="divisi">Divisi:</label>
            <select class="input100" id="divisi" name="divisi" required>
                <option value="event partnership">Event Partnership</option>
                <option value="graphic desain">Graphic Desain</option>
                <option value="content creator">Content creator</option>
				<option value="sme relation">Sme relation</option>
				<option value="copy writer">copy writer</option>

            </select>
        </div>
		<div>
            <label for="tgl_expired">Tanggal Berakhir Internship:</label>
            <input class="input100" type="date" id="tgl_expired" name="tgl_expired" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input class="input100" type="password" id="password" name="password" required>
        </div>
        <div>
            <label for="password_confirmation">Confirm Password:</label>
            <input class="input100" type="password" id="password_confirmation" name="password_confirmation" required>
        </div>
        <div class="container-login100-form-btn">
						<button class="login100-form-btn" type="submit" name="submit">
							Register
						</button>
					</div>
    </form>
			</div>
		</div>
	</div>

			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="{{ asset('template/temp/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('template/temp/vendor/bootstrap/js/popper.js')}}"></script>
	<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('template/temp/vendor/select2/select2.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('template/temp/vendor/tilt/tilt.jquery.min.js')}}"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="{{ asset('template/js/main.js')}}"></script>

</body>
</html>