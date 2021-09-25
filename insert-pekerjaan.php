<?php
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
// mulai session 
session_start();

// cek apakah varibael super global session yang index nya login sudah tervalidasi
if (!isset($_SESSION["login"])) {
	header("location: login.php");
	die();
}
// buat koneksi
include('koneksi.php');

// cek koneksi
if (!$conn) {
	die('koneksi gagal'.mysqli_connect_errno()." - ".mysql_connect_error());
}
elseif ($conn) {
	// cek apakah tombol submit sudah di tekan
	if (isset($_POST["submit"])) {

		// ambil nilai pada form
		$kode_pekerjaan = htmlentities(strip_tags(trim($_POST["kode_pekerjaan"])));
		$tanggal = htmlentities(strip_tags(trim($_POST["tanggal"])));
		$bulan = htmlentities(strip_tags(trim($_POST["bulan"])));
		$tahun = htmlentities(strip_tags(trim($_POST["tahun"])));
		$kode_block = htmlentities(strip_tags(trim($_POST["kode_block"])));
		$pekerjaan = htmlentities(strip_tags(trim($_POST["pekerjaan"])));
		$harga = htmlentities(strip_tags(trim($_POST["harga"])));
		$pengawas = htmlentities(strip_tags(trim($_POST["pengawas"])));

		$tanggal_pekerjaan = $tahun."-".$bulan."-".$tanggal;
			
		
		
		




		// alert Nomor slip
		if (empty($kode_pekerjaan)) {
			$alert_kode_pekerjaan = "Nomor slip belum di isi";
		}
		else {
			$alert_kode_pekerjaan = "";
		}


		$cek_waktu_gaji = mysqli_query($conn, "SELECT*FROM pekerjaan WHERE nip='$pengawas' AND tanggal='$tanggal_pekerjaan'");

		$row_waktu_gaji = mysqli_num_rows($cek_waktu_gaji);



		// alert Tanggal
		if (empty($tanggal) || empty($bulan) || empty($tahun)) {
			$alert_tanggal = "Tanggal belum di lengkapi";
		}
		else {
			$alert_tanggal = "";
		}

		// alert kode block
		if (empty($kode_block)) {
			$alert_kode_block = "Kode block belum di pilih";
		}
		else {
			$alert_kode_block = "";
		}



		// alert Pekerjaan
		if (empty($pekerjaan)) {
			$alert_pekerjaan = "Pekerjaan belum di isi";
		}
		else {
			$alert_pekerjaan = "";
		}



		// alert Harga
		if (empty($harga)) {
			$alert_harga = "Harga belum di isi";
		}
		elseif (!is_numeric($harga)) {
			$alert_harga = "Harga harus di isi dengan angka";
		}
		else {
			$alert_harga = "";
		}



		// alert Pengawas
		if (empty($pengawas)) {
			$alert_pengawas = "Pengawas belum di pilih";
		}
		else {
			$alert_pengawas = "";
		}






		// tampilkan pesan failed
		if (empty($kode_pekerjaan) || empty($tanggal) || empty($bulan) || empty($tahun) || empty($kode_block) || empty($pekerjaan) || empty($harga) || empty($pengawas) || $row_waktu_gaji > 0 || !is_numeric($harga)) {
				$failed = "Data gagal di input";

		}


		// cek apakah seluruh inputan form sudah tervalidasi
		if ($alert_kode_pekerjaan === "" && $alert_tanggal === "" && $alert_kode_block === "" && $alert_pekerjaan === "" && $alert_harga === "" && $alert_harga === "" && $alert_pengawas === "") {



			$result = mysqli_query($conn, "INSERT INTO pekerjaan (kode_pekerjaan, tanggal, kode_block, pekerjaan, harga, nip) VALUES ('$kode_pekerjaan', '$tanggal_pekerjaan', '$kode_block', '$pekerjaan', '$harga', '$pengawas')");

			// cek baris yang terpengaruh dari hasil query
			$row = mysqli_affected_rows($conn);

			// cek query gagal
			if (!$result) {
				die('query gagal total');
			}

			// jika nilai variabel $row sama dengan 1
			// paksa user kehalaman hasil_gaji.php dengan mengirim nilai nomor_slip
			if ($row == 1) {
				$pesan = urldecode("Data dengan kode pekerjaan $kode_pekerjaan berhasil di input");
				header("location: pekerjaan.php?pesan=$pesan");
			}
				


			

			// tampilkan pesan berhasil
			$failed = "";
			$success = "Data berhasil di input";

			
		}
	}
	elseif (!isset($_POST["submit"])) {
		

		$kode_pekerjaan = "";
		$tanggal = "";
		$bulan = "";
		$tahun = "";
		$kode_block = "";
		$pekerjaan = "";
		$harga = "";
		$pengawas = "";
		

		$alert_kode_pekerjaan = "";
		$alert_tanggal = "";
		$alert_kode_block = "";
		$alert_pekerjaan = "";
		$alert_harga = "";
		$alert_pengawas = "";
		$failed = "";

		
		// memebuat query kode otomatis
		// cek berapa angka terbesar dari kode jabatan
		$query = mysqli_query($conn, "SELECT max(kode_pekerjaan) AS kodeTerbesar FROM pekerjaan");
						
		// ambil nilai dari query
		$data = mysqli_fetch_assoc($query);

		// ambil nilai dari query data dengan index kodeTerbesar 
		$kode_pekerjaan = $data["kodeTerbesar"];

					
		$urutan = (int) substr($kode_pekerjaan, 1, 6);
		$urutan++;

		$huruf = "P";




		$kode_pekerjaan = $huruf.sprintf("%06s", $urutan);

		

		




		
	}
}





?>

<!DOCTYPE html>
<html>
<head>
	<title>web admin</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="fontawesome/css/all.css">
</head>
<body>
	<div class="container">
		<?php include 'header.php'; ?>


		<!-- Content -->
		<div class="content">
			<div class="judul">
				<h1>Pekerjaan</h1>
			</div>
			<div class="halaman">
				<?php if ($failed !== "") : ?>
				<div class="alert">
					<?php echo $failed; ?>
				</div>
				<?php elseif($failed === "") : ?>
				<div class="normal">
					<?php echo $failed; ?>
				</div>
				<?php endif ?>
				
				
				<div class="navigasi">
					
				</div>
				<div class="table">
					<form action="" method="post">
						<!-- JUDUL -->
						<label></label>
						<label class="form-input">FORM INPUT PEKERJAAN</label>
						<label></label>
						
						<!-- Kode pekerjaan -->
						<label for="kode pekerjaan">Kode pekerjaan</label>
						<input type="text" name="kode_pekerjaan" id="kode pekerjaan" autocomplete="off" value="<?php echo $kode_pekerjaan; ?>" style="background-color: #eee;" readonly>
						<label class="error"><?php echo $alert_kode_pekerjaan; ?></label>

						<!-- Tanggal pekerjaan -->
						<label for="tanggal-lahir">Tanggal</label>
						<p>
							<!-- TANGGAL -->
							<select name="tanggal" id="tanggal">
								<option value="">Tanggal</option>
								<?php for ($i=1; $i <= 31; $i++) : ?>
									<option value="<?php echo $i; ?>" <?php if($tanggal == $i) { echo "selected";} ?>><?php echo $i; ?></option>
								<?php endfor ?>
							</select>
							<!-- BULAN -->
							<select name="bulan" id="bulan">
								<option value="">Bulan</option>
								<?php $array_bulan = ["01"=>"Januari", "02"=>"Februari", "03"=>"Maret", "04"=>"April", "05"=>"Mei", "06"=>"Juni", "07"=>"Juli", "08"=>"Agustus", "09"=>"September", "10"=>"Oktober", "11"=>"November", "12"=>"Desember"] ?>
								<?php foreach ($array_bulan as $key => $value) : ?>
									<option value="<?php echo $key; ?>" <?php if($bulan == $key) {
										echo "selected";
									} ?>><?php echo $value; ?></option>
								<?php endforeach ?>
							</select>

							<!-- TAHUN -->
							<select name="tahun" id="tahun">
								<option value="">Tahun</option>
								<?php for ($i=2020; $i <= 2040; $i++) : ?>
									<option value="<?php echo $i; ?>" <?php if($tahun == $i) { echo "selected";} ?>><?php echo $i; ?></option>
								<?php endfor ?>
							</select>
						</p>
						<label class="error"><?php echo $alert_tanggal; ?></label>

						<!-- KODE BLOCK -->
						<label for="kode block">Kode block</label>
						<p>
							<select name="kode_block" id="kode block">
								<option value="">Kode block</option>
								<?php $query = mysqli_query($conn, "SELECT*FROM block ORDER BY kode_block ASC"); ?>
								<?php while ($result = mysqli_fetch_assoc($query)) : ?>
									<option value="<?php echo $result["kode_block"]; ?>" <?php if ($kode_block == $result["kode_block"]) {
										echo "selected";
									} ?>><?php echo $result["kode_block"]; ?></option>
								<?php endwhile ?>
							</select>
						</p>
						<label class="error"><?php echo $alert_kode_block; ?></label>

						<!-- PEKERJAAN -->
						<label for="pekerjaan">Pekerjaan</label>
						<input type="text" name="pekerjaan" for="pekerjaan" value="<?php echo $pekerjaan; ?>" autocomplete="off">
						<label class="error"><?php echo $alert_pekerjaan; ?></label>

						<!-- HARGA -->
						<label for="harga">Jumlah harga</label>
						<input type="text" name="harga" id="harga" autocomplete="off" value="<?php echo $harga; ?>">
						<label class="error"><?php echo $alert_harga; ?></label>


						<!-- pengawas -->
						<label for="pengawas">Pengawas</label>
						<p>
							<select name="pengawas" id="pengawas">
								<option value="">Nama pengawas</option>
								<?php $query = mysqli_query($conn, "SELECT*FROM petugas ORDER BY nip ASC"); ?>
								<?php while ($result = mysqli_fetch_assoc($query)) : ?>
									<option value="<?php echo $result["nip"]; ?>" <?php if ($pengawas == $result["nip"]) {
										echo "selected";
									} ?>><?php echo $result["nama"]; ?></option>
								<?php endwhile ?>
							</select>
						</p>
						<label class="error"><?php echo $alert_pengawas; ?></label>

						<!-- BUTTON -->
						<label></label>
						<button name="submit" value="simpan data">SIMPAN</button>
						<label></label>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
