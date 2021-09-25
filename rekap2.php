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

// cek apakah koneksi gagal
if (!$conn) {
	die('koneksi gagal'.mysqli_connect_errno()." - ".mysqli_connect_error());
}
// cek apakah koneksi berhasil
elseif ($conn) {
	$alert = "";

	// cek apakah tombol cek ditekan
	if (isset($_POST["submit"])) {
		$pengawas = htmlentities(strip_tags(trim($_POST["pengawas"])));
		$kode_block = htmlentities(strip_tags(trim($_POST["kode_block"])));



		// echo $cek_waktu_1;
		// echo "<br>";
		// echo $cek_waktu_2;

		// buat query untuk cek pekerjaan
		$query = mysqli_query($conn, "SELECT 
							pekerjaan.kode_pekerjaan, 
							pekerjaan.kode_block,
							pekerjaan.pekerjaan,
							pekerjaan.harga,
							petugas.nama 
							FROM pekerjaan 
							INNER JOIN petugas ON petugas.nip = pekerjaan.nip
							WHERE petugas BETWEEN '$pengawas' AND '$kode_block'");

		// buat query untuk total harga
		$total = mysqli_query($conn, "SELECT 
							SUM(harga) AS total 
							FROM pekerjaan 
							INNER JOIN petugas ON petugas.nip = pekerjaan.nip
							WHERE tanggal BETWEEN '$pengawas' AND '$kode_block'");

		// ambil nilai variabel $cek_waktu_1 dan $cek_waktu_2
		// kirim ke halaman cetak
		$waktu_1 = urldecode($cek_waktu_1);
		$waktu_2 = urldecode($cek_waktu_2);

		$query_string = "?cek_waktu_1=$waktu_1&cek_waktu_2=$waktu_2";

	}
	elseif (!isset($_POST["submit"])) {


		$pengawas = "";
		$kode_block = "";
		$total = "";

		$query = "";
	}
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
				<h1>Rekap</h1>
			</div>
			<div class="halaman">

				<?php if($alert !== "") : ?>
					<div class="success">
						<?php echo $alert; ?>
					</div>
				<?php elseif ($alert === "") : ?>
					<div class="normal">
						<?php echo $alert; ?>
					</div>
				<?php endif ?>				

				<div class="navigasi">
					<a href="cetak.php<?php  echo $query_string; ?>" target="_blank"><i class="fa fa-print"></i> Cetak</a>
				</div>

				<div class="table"> 
					<div class="tanggal-pekerjaan">
						<form action="" method="post">
						<!-- PETUGAS -->
						<label for="pengawas">Petugas</label>
						<p>
							<select name="pengawas" id="pengawas">
								<option value="">Nama Petugas</option>
								<?php $query = mysqli_query($conn, "SELECT*FROM petugas ORDER BY nip ASC"); ?>
								<?php while ($result = mysqli_fetch_assoc($query)) : ?>
									<option value="<?php echo $result["nip"]; ?>" <?php if ($pengawas == $result["nip"]) {
										echo "selected";
									} ?>><?php echo $result["nama"]; ?></option>
								<?php endwhile ?>
							</select>
						</p>
						<label></label>

						<!-- BLOCK -->
						<label for="block">block</label>
						<p>
							<select name="block" id="block">
								<option value="">Block</option>
								<?php $query = mysqli_query($conn, "SELECT*FROM block ORDER BY kode_block ASC"); ?>
								<?php while ($result = mysqli_fetch_assoc($query)) : ?>
									<option value="<?php echo $result["kode_block"]; ?>" <?php if ($kode_block == $result["kode_block"]) {
										echo "selected";
									} ?>><?php echo $result["kode_block"]; ?></option>
								<?php endwhile ?>
							</select>
						</p>
						<label></label>

						<button name="submit" value="cek data !">cek</button>
						</form>
						
						<table>
							<tr>
								<th>NO</th>
								<th>Kode pekerjaan</th>
								<th>Pekerjaan</th>
								<th>Harga</th>
							</tr>
							
							<?php $i=1; ?>

							<?php if ($query !== "") : ?>
								<?php while ($result = mysqli_fetch_assoc($query)) : ?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $result["kode_pekerjaan"]; ?></td>
								<td><?php echo $result["pekerjaan"]; ?></td>
								<td><?php echo "Rp.".number_format($result["harga"]); ?></td>
							</tr>
							<?php $i++; ?>
							<?php endwhile ?>

							<?php while ($hasil = mysqli_fetch_assoc($total)) : ?>
								<tr>
									<td colspan="5"><b class="bold">TOTAL</b></td>
									<td><b class="bold"><?php echo "Rp.".number_format($hasil["total"]); ?></b></td>
									<td></td>
								</tr>
							<?php endwhile ?>
							<?php endif ?>
							
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
