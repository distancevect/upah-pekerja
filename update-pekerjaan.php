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
		$kode_pekerjaan = htmlentities(strip_tags(trim($_POST["kode_pekerjaan"])));

		// filter data
		$kode_pekerjaan = mysqli_real_escape_string($conn, $kode_pekerjaan);

		// ambil semua nilai dari database yang menjadi nilai awal form
		$query = "SELECT * FROM pekerjaan WHERE kode_pekerjaan='$kode_pekerjaan'";
		$result = mysqli_query($conn, $query);

		// tidak perlu melakukan perulangan karana 1 record
		$data = mysqli_fetch_assoc($result);

		$kode_pekerjaan = $data["kode_pekerjaan"];
		$tanggal_pekerjaan = $data["tanggal"];
		$kode_block = $data["kode_block"];
		$pekerjaan = $data["pekerjaan"];
		$harga = $data["harga"];
		$pengawas = $data["nip"];


		$tahun = substr($tanggal_pekerjaan, 0, 4);

		$bulan = substr($tanggal_pekerjaan, 5, 2);
		
		$tanggal = substr($tanggal_pekerjaan, 8, 2);


		// buat nilai alert kosong
		$alert_kode_pekerjaan = "";
		$alert_tanggal = "";
		$alert_kode_block = "";
		$alert_pekerjaan = "";
		$alert_harga = "";
		$alert_pengawas = "";


		$failed = "";


		// bebaskan memori
		mysqli_free_result($result);
	}
	elseif ($_POST["submit"] == "update data") {
		// nilai form berasal dari update-jabatan.php

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



		// alert kode_pekerjaan
		if (empty($kode_pekerjaan)) {
			$alert_kode_pekerjaan = "Kode jabatan belum di isi";
		}
		else {
			$alert_kode_pekerjaan = "";
		}

		// alert tanggal
		if (empty($tanggal) || empty($bulan) || empty($tahun)) {
			$alert_tanggal = "tanggal belum di lengkapi";
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

		// alert pekerjaan
		if (empty($pekerjaan)) {
			$alert_pekerjaan = "pekerjaan belum di isi";
		}
		else {
			$alert_pekerjaan = "";
		}

		// alert harga
		if (empty($harga)) {
			$alert_harga = "harga belum di isi";
		}
		elseif (!is_numeric($harga)) {
			$alert_harga = "harga harus di isi angka";
		}
		else {
			$alert_harga = "";
		}

		// alert pengawas
		if (empty($pengawas)) {
			$alert_pengawas = "pengawas belum di pilih";
		}
		else {
			$alert_pengawas = "";
		}
		if (empty($kode_pekerjaan) || empty($tanggal) || empty($bulan) || empty($tahun) || empty($kode_block) || empty($pekerjaan) || empty($harga) || !is_numeric($harga) || empty($pengawas)) {
			$failed = "Data gagal di update";
		}


		// cek apakah seluruh inputan form sudah tervalidasi
		if ($alert_kode_pekerjaan === "" && $alert_tanggal === "" && $alert_kode_block === "" && $alert_pekerjaan === "" && $alert_harga === "" && $alert_pengawas === "") {
			$failed = "";

			// buat dan jalankan query update
			$query = "UPDATE pekerjaan SET kode_pekerjaan='$kode_pekerjaan', tanggal='$tanggal_pekerjaan', kode_block='$kode_block', pekerjaan='$pekerjaan', harga='$harga', nip='$pengawas' WHERE kode_pekerjaan='$kode_pekerjaan'";
			$result = mysqli_query($conn, $query);

			// periksa hasil query
			if (mysqli_affected_rows($conn) > 0) {
				// UPDATE berhasil, tampilkan pesan ke halaman jabatan.php
				$pesan = "Data dengan kode pekerjaan $kode_pekerjaan berhasil di update !";
				$pesan = urlencode($pesan);
				header("location: pekerjaan.php?pesan=$pesan");
			}
			if (mysqli_affected_rows($conn) === 0) {
				// UPDATE berhasil, tampilkan pesan ke halaman jabatan.php
				$pesan = "Tidak ada data yang di update !";
				$pesan = urlencode($pesan);
				header("location: pekerjaan.php?pesan=$pesan");
			}
			
		}
	}
	
	
}
// cek apakah tombol submit di halaman update-jabatan.php belum tervalidasi
elseif (!isset($_POST["submit"])) {
	// paksa user kembali ke halaman jabatan.php
	header("location: pekerjaan.php");
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
				<h1>Pekerjaan</h1>
			</div>
			<div class="halaman">
				<?php if ($failed !== "") : ?>
				<div class="alert">
					<?php echo $failed; ?>
				</div>
				<?php elseif ($failed === "") : ?>
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
						<label class="form-input">FORM UPDATE PEKERJAAN</label>
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