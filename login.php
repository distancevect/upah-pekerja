<?php 
// mulai session;
session_start();

// cek session
if(isset($_SESSION["login"])) {
	header("location: index.php");
	die();
}

// buat koneksi
include('koneksi.php');

// cek koneksi gagal
if (!$conn) {
	die("koneksi gagal".mysqli_connect_errno()." - ".mysql_connect_error());
}

// cek koneksi berhasil
elseif ($conn) {

	// cek apakah variabel super global post yang index nya login sudah tervalidasi
	if(isset($_POST["login"])) {
		// ambil nilai form
		$username = htmlentities(strip_tags(trim($_POST["username"])));
		$password = $_POST["password"];
		
		// buat query
		$hasil_query = "SELECT * FROM login WHERE username='$username'";
		// jalankan query
		$query = mysqli_query($conn, $hasil_query);

		// siapkan variabel untuk menampung nilai error
		$alert = "";

		// cek apakah username kosong
		if (empty($username)) {
			$alert = "username belum di isi";
		}
		// cek apakah password kosong
		elseif (empty($password)) {
			$alert = "password belum di isi";
		}
		
		// cek apakah username atau password benar
		elseif (mysqli_num_rows($query) === 1) {
			// cek password
			
			$result = mysqli_fetch_assoc($query);
			
			// cek apakah password input sama dengan password database
			if (password_verify($password, $result["password"])) {
			// cek jika lolos validasi, set cookie
				$_SESSION["login"] = true;
				header("location: index.php");
				die();
			}

			// cek apakah password input tidak sama dengan password database
			elseif (!password_verify($password, $result["password"])) {
				$alert = "password salah";
			}
		}
		// cek apakah username salah
		elseif (mysqli_num_rows($query) === 0) {
			$alert = "username atau password salah";
		}

		 
	}
	elseif (!isset($_POST["login"])) {
		$alert = "username = 'admin' dan password  = '123'";
		$username = "";
		$password = "";
	} 
}



 ?>

<!DOCTYPE html>
<html>
<head>
	<title>LOGIN</title>
	<link rel="stylesheet" type="text/css" href="login.css">
	<link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
</head>
<body>
	<div class="container">
		<!-- UNTUK TITLE -->
		<div class="title">
			FORM LOGIN
		</div>

		<?php if ($alert == "username = 'admin' dan password  = '123'") : ?>
		<div class="normal">
			<?php echo $alert; ?>
		</div>
		<?php elseif ($alert !== "") : ?>
		<div class="alert">
			<?php echo $alert; ?>
		</div>
		<?php endif ?>

		<!-- UNTUK CONTENT -->
		<div class="content">
			<form action="" method="post">
				<p>
					<i class="fa fa-user icon"></i>
					<input type="text" name="username" id="username" class="input" placeholder="username" autofocus="off" autocomplete="off" value="<?php echo $username; ?>">
				</p>

				<p>
					<i class="fa fa-key icon"></i>
					<input type="password" name="password" id="password" class="input" placeholder="password" autofocus="off">
				</p>

				<!-- <p>
					<input type="checkbox" name="read_me" id="read_me">
					<label for="read_me">Remember me</label>
				</p> -->

				<button name="login" value="berhasil login !">LOGIN</button>
			</form>
		</div>

		<!-- UNTUK FOOTER -->
		<div class="footer">
			
		</div>
	</div>
</body>
</html>