<?php
// mulai session 
session_start();

// cek apakah varibael super global session yang index nya login sudah tervalidasi
if (!isset($_SESSION["login"])) {
	header("location: login.php");
	die();
}

print_r($_POST);

// buat koneksi ke database
include('koneksi.php');

// cek koneksi
if (!$conn) {
 	die('koneksi gagal'.mysqli_connect_errno()." - ".mysqli_connect_error());
}

// buat query menampilkan nilai jumlah karyawan
$query = mysqli_query($conn, "SELECT*FROM karyawan");

$row = mysqli_num_rows($query);




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
				<h1>Home</h1>
			</div>
			<div class="halaman">

				<div class="normal">

				</div>
				

				<div class="navigasi"> 

				</div>

				<div class="table"> 
					<div class="gaji">
						<div class="keterangan-satu">
							
						</div>
						<div class="keterangan-dua">
							<div class="penerimaan"></div>
							<div class="potongan"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>