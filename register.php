<?php 

// buat koneksi
include('koneksi.php');

// cek koneksi
if (!$conn) {
	die("koneksi gagal".mysqli_connect_errno()." - ".mysql_connect_error());
}
elseif ($conn) {
	
	// cek apakah variabel super global $_POST yang indexnya register tervalidasi
	if (isset($_POST["register"])) {
		
		// ambil nilai dari form
		$username = htmlentities(strip_tags(trim($_POST["username"])));
		$password = htmlentities(strip_tags(trim($_POST["password"])));
		$konfirmasi_passwod = htmlentities(strip_tags(trim($_POST["konfirmasi_passwod"])));
		// buat query cek username
		$query_select = "SELECT*FROM login WHERE username='$username'";

		// jalankan query
		$result_select = mysqli_query($conn, $query_select);

		// ambil nilai dari query
		$value_select = mysqli_fetch_assoc($result_select);


		// cek apakah username kosong
		if (empty($username)) {
			$alert = "username belum di isi";
		}
		// cek apakah password kosong
		elseif (empty($password)) {
			$alert = "password belum di isi";
		}
		// cek apakah konfirmasi password kosong
		elseif (empty($konfirmasi_passwod)) {
			$alert = "konfirmasi_passwod belum di isi";
		}
		// cek apakah password tidak sama dengan konfirmasi password
		elseif ($password !== $konfirmasi_passwod) {
			$alert = "password dan konfirmasi passwod harus sama";
		}
		// cek apakah username sudah ada di database
		elseif (mysqli_affected_rows($conn) === 1) {
			$alert = "username sudah ada";
		}
		// jika lolos validasi
		elseif (!empty($username) && !empty($password) && $password === $konfirmasi_passwod && mysqli_affected_rows($conn) === 0) {

			// encrypt password
			$encrypt_password = password_hash($password, PASSWORD_DEFAULT);

			// buat query insert
			$query_insert = "INSERT INTO login (username, password) VALUES ('$username', '$encrypt_password')";

			// jalankan query
			$result_insert = mysqli_query($conn, $query_insert);

			// cek keberhasilan
			if (mysqli_affected_rows($conn) === 1) {
				$alert = "register berhasil";
			}
			elseif (mysqli_affected_rows($conn) === 0) {
				$alert = "";
			}

		}

	}
	// cek apakah variabel super global $_POST yang indexnya register tervalidasi
	elseif (!isset($_POST["register"])) {
		$alert = "";
		$username = "";
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
			FORM REGISTER
		</div>

		<?php if ($alert === "register berhasil") : ?>
		<div class="success">
			<?php echo $alert; ?>
		</div>
		<?php elseif ($alert !== "") : ?>
		<div class="alert">
			<?php echo $alert; ?>
		</div>
		<?php elseif ($alert === "") : ?>
		<div class="normal">
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

				<p>
					<i class="fa fa-key icon"></i>
					<input type="password" name="konfirmasi_passwod" id="password" class="input" placeholder="ulangi password" autofocus="off">
				</p>

				<button name="register" value="berhasil register !">REGISTER</button>
			</form>
		</div>

		<!-- UNTUK FOOTER -->
		<div class="footer">
			
		</div>
	</div>
</body>
</html>