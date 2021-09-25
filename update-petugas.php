<?php
// mulai session 
session_start();

// cek apakah varibael super global session yang index nya login sudah tervalidasi
if (!isset($_SESSION["login"])) {
	header("location: login.php");
	die();
}

// buat koneksi
include("koneksi.php");

// cek koneksi gagal
if (!$conn) {
	die("koneksi gagal".mysql_connect_errno()." - ".mysql_connect_error());
}

// cek koneksi berhasil
elseif ($conn) {
	// cek apakah tombol submit sudah di tekan
	if (isset($_POST["submit"])) {
		
		if ($_POST["submit"] == "edit") {
			// nilai form berasal dari karyawan.php
			// echo "<pre>";
			// print_r($_POST);
			// echo "</pre>";
			// ambil nilai kode nip
			$nip = htmlentities(strip_tags(trim($_POST["nip"])));

			// filter data
			$nip = mysqli_real_escape_string($conn, $nip);

			// ambil semua nilai dari database yang menjadi nilai awal form
			$query = "SELECT * FROM petugas WHERE nip='$nip'";
			$result = mysqli_query($conn, $query);

			// tidak perlu melakukan perulangan karana 1 record
			$data = mysqli_fetch_assoc($result);

			// echo "<pre>";
			// print_r($data);
			// echo "</pre>";

			// ambil nilai 1 per 1 satu dari database
			$nip = $data["nip"];
			$nama = $data["nama"];
		    $no_handphone = $data["no_hp"];
		    $jabatan = $data["kode_jabatan"];

		

			// buat nilai alert kosong
			$alert_nip = "";
			$alert_nama = "";
			$alert_no_handphone = "";
			$alert_jabatan = "";

			// tampilkan pesan failed
			if (!empty($nip) && !empty($nama) && !empty($no_handphone) && !empty($jabatan)) {
				$failed = "";
			}

			// bebaskan memori
			mysqli_free_result($result);
		}

		elseif ($_POST["submit"] == "update data") {
			// ambil nilai pada form
			$nip = htmlentities(strip_tags(trim($_POST["nip"])));
			$nama = htmlentities(strip_tags(trim($_POST["nama"])));
			$no_handphone = htmlentities(strip_tags(trim($_POST["no_handphone"])));
			$jabatan = htmlentities(strip_tags(trim($_POST["jabatan"])));


			// buat query jabatan
			$query = mysqli_query($conn, "SELECT * FROM jabatan");

			// alert nip
			if (empty($nip)) {
				$alert_nip = "Nip belum di isi";
			}
			else {
				$alert_nip = "";
			}
			// alert nama
			if (empty($nama)) {
				$alert_nama = "Nama belum di isi";
			}
			else {
				$alert_nama = "";
			}
			
			// alert no handphone
			if (empty($no_handphone)) {
				$alert_no_handphone = "Nomor handphone belum di isi";
			}
			elseif (!is_numeric($no_handphone)) {
				$alert_no_handphone = "Nomor handphone harus angka";
			}
			else {
				$alert_no_handphone = "";
			}
			// alert jabatan
			if (empty($jabatan)) {
				$alert_jabatan = "Jabatan belum di pilih";
			}
			else {
				$alert_jabatan = "";
			}
			

		





			// tampilkan pesan failed
			if (empty($nip) || empty($nama) || empty($no_handphone) || !is_numeric($no_handphone) || empty($jabatan)) {
				$failed = "Data gagal di update";
			}

			

			// cek apakah seluruh inputan form sudah lolos validasi
			if ($alert_nip === "" && $alert_nama === "" && $alert_no_handphone === "" && $alert_jabatan === "") {


				//lakukan koneksi
				include("koneksi.php");

				// cek koneksi gagal
				if (!$conn) {
					die("koneksi gagal".mysql_connect_errno()." - ".mysql_connect_error());
				}
				// cek koneksi berhasil
				elseif ($conn) {
					
					
					// jalankan query
					$result = mysqli_query($conn, "UPDATE petugas SET nip='$nip', nama='$nama', no_hp='$no_handphone', kode_jabatan='$jabatan' WHERE nip='$nip'");
					// cek query
					if (!$result) {
						die("query gagal".mysqli_errno($conn)." - ".mysqli_error($conn));
					}
					elseif ($result) {
						
						// lakukan pemindahan file ke direktori foto
						$success = "Data berhasil di input";
						$failed = "";
						
						

						// cek baris yang terpengaruh dari query INSERT
						$affected = mysqli_affected_rows($conn);

						
						
						if($affected == 1) {
							echo "Data dengan nip $nip berhasil di update !";
							$pesan = urldecode("Data dengan nip $nip berhasil di update !");
							header("location: petugas.php?pesan=$pesan");
						}
						elseif ($affected == 0) {
							echo "Tidak ada data yang di update !";
							$pesan = urldecode("Tidak ada data yang di update !");
							header("location: petugas.php?pesan=$pesan");
						}
					}
					
				}

				// include("hasil.php");
				// die();
			}
		}
		
	}
	// cek apakah tombol submit di halaman update-jabatan.php belum tervalidasi
	elseif (!isset($_POST["submit"])) {
		// paksa user kembali ke halaman jabatan.php
		header("location: petugas.php");
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
				<h1>Petugas</h1>
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
					<form action="" method="post" enctype="multipart/form-data">
						<!-- JUDUL -->
						<label></label>
						<label class="form-input">FORM UPDATE PETUGAS</label>
						<label></label>

						<!-- NIP -->
						<label for="nip">NIP</label>
						<input type="text" name="nip" id="nip" autocomplete="off" value="<?php echo $nip; ?>" style="background-color: #eee;" readonly>
						<label class="error"><?php echo $alert_nip; ?></label>

						<!-- NAMA -->
						<label for="nama">Nama</label>
						<input type="text" name="nama" id="nama" autocomplete="off" value="<?php echo $nama; ?>">
						<label class="error"><?php echo $alert_nama; ?></label>


						<!-- NO HANDPHONE -->
						<label for="no hp">No Handphone</label>
						<input type="text" name="no_handphone" id="no hp" autocomplete="off" value="<?php echo $no_handphone; ?>">
						<label class="error"><?php echo $alert_no_handphone; ?></label>

						<!-- JABATAN -->
						<label for="jabatan">Jabatan</label>
						<p>
							<select name="jabatan" id="jabatan">
								<option value="">Jabatan</option>
								<?php include('koneksi.php') ?>
								<?php $query = mysqli_query($conn, "SELECT * FROM jabatan ORDER BY nama_jabatan ASC"); ?>
								<?php while ($result = mysqli_fetch_assoc($query)) : ?>
									<option value="<?php echo $result["kode_jabatan"]; ?>" <?php if ($jabatan == $result["kode_jabatan"]) {
										echo "selected";
									} ?>><?php echo $result["nama_jabatan"]; ?></option>
								<?php endwhile ?>
							</select>
						</p>
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