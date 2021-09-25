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
	$kode_jabatan = htmlentities(strip_tags(trim($_POST["kode_jabatan"])));
	$jabatan = htmlentities(strip_tags(trim($_POST["jabatan"])));
	$gaji = htmlentities(strip_tags(trim($_POST["gaji"])));



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
	
	// alert Gaji
	if (empty($gaji)) {
		$alert_gaji = "Gaji belum di isi";
	}
	elseif (!is_numeric($gaji)) {
		$alert_gaji = "Gaji harus di isi dengan angka";
	}
	else {
		$alert_gaji = "";
	}
	


	// tampilkan pesan failed
	if (empty($kode_jabatan) || empty($jabatan)) {
		$failed = "Data gagal di input";
	}


	// cek apakah seluruh inputan form sudah tervalidasi
	if ($alert_kode_jabatan === "" && $alert_jabatan === "") {
		// tampilkan pesan berhasil
		$query_insert = mysqli_query($conn, "INSERT INTO jabatan VALUES ('$kode_jabatan', '$jabatan')");

		
		if (mysqli_affected_rows($conn) == 1) {
			$pesan = urlencode("Data baru dengan kode jabatan $kode_jabatan berhasil di input !");

			header("location: jabatan.php?pesan=$pesan");
		}
		
	}
}
elseif (!isset($_POST["submit"])) {
	$jabatan = "";


	$alert_kode_jabatan = "";
	$alert_jabatan = "";
	$failed = "";

	// memebuat query kode otomatis
	// cek berapa angka terbesar dari kode jabatan
	$query = mysqli_query($conn, "SELECT max(kode_jabatan) AS kodeTerbesar FROM jabatan");
		
	// ambil nilai dari query
	$data = mysqli_fetch_assoc($query);

	// ambil nilai dari query data dengan index kodeTerbesar 
	$kode_jabatan = $data["kodeTerbesar"];


	$urutan = (int) substr($kode_jabatan, 3, 3);
	$urutan++;

	$huruf = "JBT";
	$kode_jabatan = $huruf.sprintf("%03s", $urutan);
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
						<label class="form-input">FORM INPUT JABATAN</label>
						<label></label>
					
						<!-- KODE JABATAN -->
						<label for="kode jabatan">Kode Jabatan</label>
						<input type="text" name="kode_jabatan" id="kode jabatan" autocomplete="off" value="<?php echo $kode_jabatan; ?>" style="background-color: #eee;" <?php echo $readonly; ?>>
						<label class="error"><?php echo $alert_kode_jabatan; ?></label>

						<!-- JABATAN -->
						<label for="jabatan">Nama Jabatan</label>
						<input type="text" name="jabatan" id="jabatan" autocomplete="off" value="<?php echo $jabatan; ?>">
						<label class="error"><?php echo $alert_jabatan; ?></label>

					
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

// bebaskan memory
if (!isset($_POST["submit"])) {
	mysqli_free_result($query);
}

// putuskan koneksi database
mysqli_close($conn);


 ?>