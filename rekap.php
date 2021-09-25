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
		$tanggal_1 = htmlentities(strip_tags(trim($_POST["tanggal_1"])));
		$bulan_1 = htmlentities(strip_tags(trim($_POST["bulan_1"])));
		$tahun_1 = htmlentities(strip_tags(trim($_POST["tahun_1"])));


		$tanggal_2 = htmlentities(strip_tags(trim($_POST["tanggal_2"])));
		$bulan_2 = htmlentities(strip_tags(trim($_POST["bulan_2"])));
		$tahun_2 = htmlentities(strip_tags(trim($_POST["tahun_2"])));

		// gabungkan nilai string
		$cek_waktu_1 = $tahun_1."-".$bulan_1."-".$tanggal_1;
		$cek_waktu_2 = $tahun_2."-".$bulan_2."-".$tanggal_2;

		// echo $cek_waktu_1;
		// echo "<br>";
		// echo $cek_waktu_2;

		// buat query untuk cek pekerjaan
		$query = mysqli_query($conn, "SELECT 
							pekerjaan.kode_pekerjaan, 
							pekerjaan.tanggal,
							pekerjaan.kode_block,
							pekerjaan.pekerjaan,
							pekerjaan.harga,
							petugas.nama 
							FROM pekerjaan 
							INNER JOIN petugas ON petugas.nip = pekerjaan.nip
							WHERE tanggal BETWEEN '$cek_waktu_1' AND '$cek_waktu_2'");

		// buat query untuk total harga
		$total = mysqli_query($conn, "SELECT 
							SUM(harga) AS total 
							FROM pekerjaan 
							INNER JOIN petugas ON petugas.nip = pekerjaan.nip
							WHERE tanggal BETWEEN '$cek_waktu_1' AND '$cek_waktu_2'");

		// ambil nilai variabel $cek_waktu_1 dan $cek_waktu_2
		// kirim ke halaman cetak
		$waktu_1 = urldecode($cek_waktu_1);
		$waktu_2 = urldecode($cek_waktu_2);

		$query_string = "?cek_waktu_1=$waktu_1&cek_waktu_2=$waktu_2";

	}
	elseif (!isset($_POST["submit"])) {

		$tanggal_1 = "";
		$bulan_1 = "";
		$tahun_1 = "";

		$tanggal_2 = "";
		$bulan_2 = "";
		$tahun_2 = "";

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
						<label>Tanggal awal</label>
						<p>
							<!-- TANGGAL -->
							<select name="tanggal_1" id="tanggal_1" onclick="autofil()">
								<option value="">Tanggal</option>
								<?php for ($i=1; $i <= 31; $i++) : ?>
									<option value="<?php echo $i; ?>" <?php if ($tanggal_1 == $i) {
										echo "selected";
									} ?>><?php echo $i; ?></option>
								<?php endfor ?>
							</select>
							<!-- BULAN -->
							<select name="bulan_1" id="bulan_1" onclick="autofil()">
								<option value="">Bulan</option>
								<?php $array_bulan = ["01"=>"Januari", "02"=>"Februari", "03"=>"Maret", "04"=>"April", "05"=>"Mei", "06"=>"Juni", "07"=>"Juli", "08"=>"Agustus", "09"=>"September", "10"=>"Oktober", "11"=>"November", "12"=>"Desember"] ?>
								<?php foreach ($array_bulan as $key => $value) : ?>
									<option value="<?php echo $key; ?>" <?php if ($bulan_1 == $key) {
										echo "selected";
									} ?>><?php echo $value; ?></option>
								<?php endforeach ?>
							</select>

							<!-- TAHUN -->
							<select name="tahun_1" id="tahun_1" onclick="autofil()">
								<option value="">Tahun</option>
								<?php for ($i=2020; $i <= 2040; $i++) : ?>
									<option value="<?php echo $i; ?>" <?php if ($tahun_1 == $i) {
										echo "selected";
									} ?>><?php echo $i; ?></option>
								<?php endfor ?>
							</select>
						</p>
						<label></label>

						<label>Tanggal akhir</label>
						<p>
							<!-- TANGGAL -->
							<select name="tanggal_2" id="tanggal_2" onclick="autofil()">
								<option value="">Tanggal</option>
								<?php for ($i=1; $i <= 31; $i++) : ?>
									<option value="<?php echo $i; ?>" <?php if ($tanggal_2 == $i) {
										echo "selected";
									} ?>><?php echo $i; ?></option>
								<?php endfor ?>
							</select>
							<!-- BULAN -->
							<select name="bulan_2" id="bulan_2" onclick="autofil()">
								<option value="">Bulan</option>
								<?php $array_bulan = ["01"=>"Januari", "02"=>"Februari", "03"=>"Maret", "04"=>"April", "05"=>"Mei", "06"=>"Juni", "07"=>"Juli", "08"=>"Agustus", "09"=>"September", "10"=>"Oktober", "11"=>"November", "12"=>"Desember"] ?>
								<?php foreach ($array_bulan as $key => $value) : ?>
									<option value="<?php echo $key; ?>" <?php if ($bulan_2 == $key) {
										echo "selected";
									} ?>><?php echo $value; ?></option>
								<?php endforeach ?>
							</select>

							<!-- TAHUN -->
							<select name="tahun_2" id="tahun_2" onclick="autofil()">
								<option value="">Tahun</option>
								<?php for ($i=2020; $i <= 2040; $i++) : ?>
									<option value="<?php echo $i; ?>" <?php if ($tahun_2 == $i) {
										echo "selected";
									} ?>><?php echo $i; ?></option>
								<?php endfor ?>
							</select>
						</p>

						<button name="submit" value="cek data !">cek</button>
						</form>
						
						<table>
							<tr>
								<th>NO</th>
								<th>Kode pekerjaan</th>
								<th>Tanggal</th>
								<th>Block</th>
								<th>Pekerjaan</th>
								<th>Harga</th>
								<th>Pengawas</th>
							</tr>
							
							<?php $i=1; ?>

							<?php if ($query !== "") : ?>
								<?php while ($result = mysqli_fetch_assoc($query)) : ?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $result["kode_pekerjaan"]; ?></td>
								<td><?php echo date("d - F - Y", strtotime($result["tanggal"])); ?></td>
								<td><?php echo $result["kode_block"]; ?></td>
								<td><?php echo $result["pekerjaan"]; ?></td>
								<td><?php echo "Rp.".number_format($result["harga"]); ?></td>
								<td><?php echo $result["nama"]; ?></td>
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
