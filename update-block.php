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

	// cek apakah berasal dari block.php atau update-block.php
	if ($_POST["submit"] == "edit") {
		// nilai form berasal dari jatabatan.php
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		// ambil nilai kode block
		$kode_block = htmlentities(strip_tags(trim($_POST["kode_block"])));


		// filter data
		$kode_block = mysqli_real_escape_string($conn, $kode_block);

		// ambil semua nilai dari database yang menjadi nilai awal form
		$query = "SELECT * FROM block WHERE kode_block='$kode_block'";
		$result = mysqli_query($conn, $query);

		// tidak perlu melakukan perulangan karana 1 record
		$data = mysqli_fetch_assoc($result);

		$kode_block = $data["kode_block"];

		

		// buat nilai alert kosong
		$alert_kode_block = "";

		// tampilkan pesan failed
		if (!empty($kode_block)) {
			$failed = "";
		}

		// bebaskan memori
		mysqli_free_result($result);
	}
	elseif ($_POST["submit"] == "update data") {
		// nilai form berasal dari update-block.php

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
			$failed = "Data gagal di update";
		}
		else {
			$alert_kode_block = "";
		}
		
		


		// tampilkan pesan failed
		if (empty($kode_block)) {
			$failed = "Data gagal di update";
		}


		// cek apakah seluruh inputan form sudah tervalidasi
		if ($alert_kode_block === "") {


			// filter semua nilai
			$kode_block = mysqli_real_escape_string($conn, $kode_block);

			// buat dan jalankan query update
			$query = "UPDATE 'block' SET 'kode_block'='$kode_block' WHERE 'block'.'kode_block'='$kode_block'";
			$result = mysqli_query($conn, $query);

		

			// periksa hasil query
			if (mysqli_affected_rows($conn) > 0) {
				echo "asdasd";
				// UPDATE berhasil, tampilkan pesan ke halaman block.php
				$pesan = "Data dengan kode block $kode_block berhasil di update !";
				$pesan = urlencode($pesan);
				header("location: block.php?pesan=$pesan");
			}
			elseif (mysqli_affected_rows($conn) === 0) {
				// UPDATE berhasil, tampilkan pesan ke halaman block.php
				$pesan = "Tidak ada data yang di update !";
				$pesan = urlencode($pesan);
				header("location: block.php?pesan=$pesan");
			}
			
		}
	}
	
	
}
// cek apakah tombol submit di halaman update-block.php belum tervalidasi
elseif (!isset($_POST["submit"])) {
	// paksa user kembali ke halaman block.php
	header("location: block.php");
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
						<label class="form-input">FORM UPDATE BLOCK</label>
						<label></label>

						<!-- KODE BLOCK -->
						<label for="kode block">Kode Block</label>
						<input type="text" name="kode_block" id="kode block" autocomplete="off" value="<?php echo $kode_block; ?>">
						<label class="error"><?php echo $alert_kode_block; ?></label>

						<!-- BUTTON -->
						<label></label>
						<button name="submit" value="update data">SIMPAN</button>
						<label></label>
					</form>

				</div>
			</div>
		</div>
	</div>
</body>
</html>
<?php 


// putuskan koneksi database
mysqli_close($conn);

 ?>