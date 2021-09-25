<?php
// mulai session 
session_start();

// cek apakah varibael super global session yang index nya login sudah tervalidasi
if (!isset($_SESSION["login"])) {
	header("location: login.php");
	die();
}


// buat koneksi ke database
include('koneksi.php');

// cek koneksi
if (!$conn) {
 	die('koneksi gagal'.mysqli_connect_errno()." - ".mysqli_connect_error());
}

// buat query menampilkan nilai jumlah karyawan
$query = mysqli_query($conn, "SELECT*FROM petugas");

$row = mysqli_num_rows($query);

$query1 = mysqli_query($conn, "SELECT*FROM jabatan");

$row1 = mysqli_num_rows($query1);




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
					<div class="main-informasi">
						<div class="informasi-karyawan">
						<div class="label">
							<i class="fa fa-user-hard-hat"></i>
						</div>
						<div class="value">
								<p class="angka"><?php echo $row; ?></p>
								<p class="huruf">Petugas</p>
						</div>
						</div>
						
						<div class="informasi-karyawan">
							<div class="label">
								<i class="fa fa-medal"></i>
							</div>
							<div class="value">
								<p class="angka"><?php echo $row1; ?></p>
								<p class="huruf">Jabatan</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>