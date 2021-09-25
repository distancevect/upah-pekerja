<?php
$readonly = "readonly";
// buat koneksi ke database
include("koneksi.php");

// mulai session 
session_start();

// cek apakah varibael super global session yang index nya login sudah tervalidasi
if (!isset($_SESSION["login"])) {
	header("location: login.php");
	die();
}

// cek apakah tombol submit sudah di tekan
if (isset($_POST["submit"])) {
	
	// ambil nilai pada form
	$kode_block = htmlentities(strip_tags(trim($_POST["kode_block"])));


	// query cek kode block di database
	$query = mysqli_query($conn, "SELECT*FROM block WHERE kode_block='$kode_block'");
	$check = mysqli_num_rows($query);

	// alert kode_block
	if (empty($kode_block)) {
		$alert_kode_block = "Kode block belum di isi";
	}
	elseif ($check == 1) {
		$alert_kode_block = "Kode block sudah ada di database";
	}
	else {
		$alert_kode_block = "";
	}
	
	

	// tampilkan pesan failed
	if (empty($kode_block) || $check == 1) {
		$failed = "Data gagal di input";
	}


	// cek apakah seluruh inputan form sudah tervalidasi
	if ($alert_kode_block === "") {
		// tampilkan pesan berhasil
		$query_insert = mysqli_query($conn, "INSERT INTO block VALUES ('$kode_block')");

		
		if (mysqli_affected_rows($conn) == 1) {
			$pesan = urlencode("Data baru dengan kode block $kode_block berhasil di input !");

			header("location: block.php?pesan=$pesan");
		}
		
	}
}
elseif (!isset($_POST["submit"])) {
	$kode_block = "";


	$alert_kode_block = "";
	$failed = "";
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>web admin</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
</head>
<body>
	<div class="container">
		<?php include 'header.php'; ?>


		<!-- Content -->
		<div class="content">
			<div class="judul">
				<h1>Block</h1>
			</div>
			<div class="halaman">
				<?php if ($failed == true) : ?>
				<div class="alert">
					<?php echo $failed; ?>
				</div>
				<?php else : ?>
				<div class="normal">
					<?php echo $failed; ?>
				</div>
				<?php endif ?>
				
				
				<div class="navigasi">
					
				</div>
				<div class="table">
					<form action="" method="post" enctype="multipart/form-data">
						<!-- JUDUL -->
						<label></label>
						<label class="form-input">FORM INPUT BLOCK</label>
						<label></label>
					
						<!-- KODE BLOCK -->
						<label for="kode block">Kode Block</label>
						<input type="text" name="kode_block" id="kode block" autocomplete="off" value="<?php echo $kode_block; ?>">
						<label class="error"><?php echo $alert_kode_block; ?></label>

					
						<!-- BUTTON -->
						<label></label>
						<button name="submit" value="simpan data !">SIMPAN</button>
						<label></label>
					</form>

				</div>
			</div>
		</div>
	</div>
</body>
</html>
<?php 

// // bebaskan memory
// if (!isset($_POST["submit"])) {
// 	mysqli_free_result($query);
// }

// // putuskan koneksi database
// mysqli_close($conn);


 ?>