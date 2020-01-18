<?php
	empty($_POST['username']) ? $username = '' : $username = $_POST['username'];
	empty($_POST['password']) ? $password = '' : $password = $_POST['password'];
	$message = "";
	$sessionname = 'digix';
	if(isset($_POST['submit'])){
		if(empty($username) || empty($password)){
			$message = "Wajib diisi semuanya!";
		}else{
			$combine_auth = MD5(SHA1($password));
			$query_db = "
				SELECT *
				FROM tbl_user a 
				LEFT JOIN tbl_person b ON (a.id_person = b.id_person)
				LEFT JOIN tbl_sekolah c ON (b.id_sekolah = c.id_sekolah)
				WHERE username = '".$username."'
				AND password = (MD5(SHA1('".$password."')))
				AND level != 'murid'
				AND status = 'web'
			";

			$count = db_num_rows($query_db);
			if($count > 0){
				$row = db_query($query_db);
				$_SESSION[$sessionname] = $row; 
				$_SESSION[$sessionname]->roles = $row->level; 
				$_SESSION[$sessionname]->auth = $combine_auth; 
				$message = 'Berhasil';
				echo redirect("home");
			} else {
                $message = "Username atau Password tidak sesuai";
			}
			
        }
    }
?>
<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>

<head>
	<title>.:: DIGI-X ::.</title>
	<!-- Meta-Tags -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<meta name="keywords" content="Elite login Form a Responsive Web Template, Bootstrap Web Templates, Flat Web Templates, Android Compatible Web Template, Smartphone Compatible Web Template, Free Webdesigns for Nokia, Samsung, LG, Sony Ericsson, Motorola Web Design">
	<script>
		addEventListener("load", function () {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
		
	</script>
	<!-- //Meta-Tags -->
	<!-- Stylesheets -->
	<link href="<?php echo url('themes/login/css/font-awesome.css')?>" rel="stylesheet">
	<link href="<?php echo url('themes/login/css/style.css')?>" rel='stylesheet' type='text/css' />
	<!--// Stylesheets -->
	<!--fonts-->
	<link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i" rel="stylesheet">
	<!--//fonts-->
</head>

<body>
	<h1>DIGI-X</h1>
	<h2><?php echo $message?></h2>
	<div class="w3ls-login">
		<!-- form starts here -->
		<form action="" method="post">
			<div class="agile-field-txt">
				<label><i class="fa fa-user" aria-hidden="true"></i> Username :</label>
				<input type="text" name="username" id="username" placeholder="Username" required="" />
			</div>
			<div class="agile-field-txt">
				<label><i class="fa fa-lock" aria-hidden="true"></i> password :</label>
				<input type="password" name="password" placeholder="Password" required="" id="password" />
				<div class="agile_label">
					<input id="check3" name="check3" type="checkbox" value="show password" onclick="myFunction()">
					<label class="check" for="check3">Show password</label>
				</div>
			</div>
			<script>
				function myFunction() {
					var x = document.getElementById("password");
					if (x.type === "password") {
						x.type = "text";
					} else {
						x.type = "password";
					}
				}
			</script>
			<!-- //script for show password -->
			<div class="w3ls-login  w3l-sub">
				<input type="submit" name="submit" value="Login">
			</div>
		</form>
	</div>
	<!-- //form ends here -->
	<!--copyright-->
	<div class="copy-wthree">
		<!--
		<p>© 2018 Elite login Form . All Rights Reserved | Design by
			<a href="http://w3layouts.com/" target="_blank">W3layouts</a>
		</p>
		!-->
		<p><strong>Copyright &copy; 2018<a> Powered by <strong><a>RPA - IT Support Bussiness JABAR</a></strong></p>
	</div>
	<!--//copyright-->
</body>

</html>

