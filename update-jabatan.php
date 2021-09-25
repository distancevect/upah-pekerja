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

	// cek apakah berasal dari jabatan.php atau update-jabatan.php
	if ($_POST["submit"] == "edit") {
		// nilai form berasal dari jatabatan.php
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		// ambil nilai kode jabatan
		$kode_jabatan = htmlentities(strip_tags(trim($_POST["kode_jabatan"])));

		// filter data
		$kode_jabatan = mysqli_real_escape_string($conn, $kode_jabatan);

		// ambil semua nilai dari database yang menjadi nilai awal form
		$query = "SELECT * FROM jabatan WHERE kode_jabatan='$kode_jabatan'";
		$result = mysqli_query($conn, $query);

		// tidak perlu melakukan perulangan karana 1 record
		$data = mysqli_fetch_assoc($result);

		$kode_jabatan = $data["kode_jabatan"];
		$jabatan = $data["nama_jabatan"];

		// buat nilai alert kosong
		$alert_kode_jabatan = "";
		$alert_jabatan = "";

		// tampilkan pesan failed
		if (!empty($kode_jabatan) && !empty($jabatan)) {
			$failed = "";
		}

		// bebaskan memori
		mysqli_free_result($result);
	}
	elseif ($_POST["submit"] == "update data") {
		// nilai form berasal dari update-jabatan.php

		// ambil nilai pada form
		$kode_jabatan = htmlentities(strip_tags(trim($_POST["kode_jabatan"])));
		$jabatan = htmlentities(strip_tags(trim($_POST["jabatan"])));



		// alert kode_jabatan
		if (empty($kode_jabatan)) {
			$alert_kode_jabatan = "Kode jabatan belum di isi";
		}
		else {
			$alert_kode_jabatan = "";
		}
		// alert Jabatan
		if (empty($jabatan)) {
			$alert_jabatan = "Jabatan belum di isi";
		}
		else {
			$alert_jabatan = "";
		}
		
		


		// tampilkan pesan failed
		if (empty($kode_jabatan) || empty($jabatan)) {
			$failed = "Data gagal di update";
		}


		// cek apakah seluruh inputan form sudah tervalidasi
		if ($alert_kode_jabatan === "" && $alert_jabatan === "") {

			// filter semua nilai
			$kode_jabatan = mysqli_real_escape_string($conn, $kode_jabatan);
			$jabatan = mysqli_real_escape_string($conn, $jabatan);

			// buat dan jalankan query update
			$query = "UPDATE jabatan SET kode_jabatan='$kode_jabatan', nama_jabatan='$jabatan' WHERE kode_jabatan='$kode_jabatan'";
			$result = mysqli_query($conn, $query);

			// periksa hasil query
			if (mysqli_affected_rows($conn) > 0) {
				// UPDATE berhasil, tampilkan pesan ke halaman jabatan.php
				$pesan = "Data dengan kode jabatan $kode_jabatan berhasil di update !";
				$pesan = urlencode($pesan);
				header("location: jabatan.php?pesan=$pesan");
			}
			if (mysqli_affected_rows($conn) === 0) {
				// UPDATE berhasil, tampilkan pesan ke halaman jabatan.php
				$pesan = "Tidak ada data yang di update !";
				$pesan = urlencode($pesan);
				header("location: jabatan.php?pesan=$pesan");
			}
			
		}
	}
	
	
}
// cek apakah tombol submit di halaman update-jabatan.php belum tervalidasi
elseif (!isset($_POST["submit"])) {
	// paksa user kembali ke halaman jabatan.php
	header("location: jabatan.php");
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
				<h1>Jabatan</h1>
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
						<label class="form-input">FORM UPDATE JABATAN</label>
						<label></label>

						<!-- KODE JABATAN -->
						<label for="kode jabatan">Kode Jabatan</label>
						<input type="text" name="kode_jabatan" id="kode jabatan" autocomplete="off" value="<?php echo $kode_jabatan; ?>" style="background-color: #eee;" <?php echo $readonly; ?>>
						<label class="error"><?php echo $alert_kode_jabatan; ?></label>

						<!-- JABATAN -->
						<label for="jabatan">Jabatan</label>
						<input type="text" name="jabatan" id="jabatan" autocomplete="off" value="<?php echo $jabatan; ?>">
						<label class="error"><?php echo $alert_jabatan; ?></label>

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