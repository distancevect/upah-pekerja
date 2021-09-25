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
		if (empty($nip) || empty($nama) || empty($no_handphone) || !is_numeric($no_handphone) || empty($agama) || empty($jabatan)) {
			$failed = "Data gagal di input";
		}


		// cek apakah seluruh inputan form sudah tervalidasi
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
				$result = mysqli_query($conn, "INSERT INTO petugas (nip, nama, no_hp, kode_jabatan) VALUES ('$nip', '$nama', '$no_handphone', '$jabatan')");
				
				// cek query salah
				if (!$result) {
					die("query gagal".mysqli_errno($conn)." - ".mysqli_error($conn));
				}
				// cek query benar
				elseif ($result) {

					// cek baris yang terpengaruh dari query INSERT
					$affected = mysqli_affected_rows($conn);

					// lakukan pemindahan file ke direktori foto
					$success = "Data berhasil di input";
					$failed = "";

					
					if($affected == 1) {
						$pesan = urldecode("Data dengan nip $nip berhasil ditambah");
						header("location: petugas.php?pesan=$pesan");
					}
				}

			}

			// include("hasil.php");
			// die();
		}
	}
	elseif (!isset($_POST["submit"])) {
		$nip = "";
		$nama = "";
		$no_handphone = "";
		$jabatan = "";
		

		$alert_nip = "";
		$alert_nama = "";
		$alert_no_handphone = "";
		$alert_jabatan = "";
		$failed = "";


		// buat query jabatan
		$query = mysqli_query($conn, "SELECT * FROM jabatan");

		// while ($result = mysqli_fetch_assoc($query)) {
		// 	echo "<pre>";
		// 	echo $result["nama_jabatan"];
		// 	echo "</pre>";
		// }


		// memebuat query kode otomatis
		// cek berapa angka terbesar dari kode jabatan
		$query = mysqli_query($conn, "SELECT max(nip) AS kodeTerbesar FROM petugas");
			
		// ambil nilai dari query
		$data = mysqli_fetch_assoc($query);

		// ambil nilai dari query data dengan index kodeTerbesar 
		$nip = $data["kodeTerbesar"];

		
		$urutan = (int) substr($nip, 3, 5);
		$urutan++;

		$huruf = "1";
		$date =  date("d-m-Y");
		$digit_kedua = substr($date, 8, 2);


		$nip = $huruf.$digit_kedua.sprintf("%05s", $urutan);
			

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
						<label class="form-input">FORM INPUT PETUGAS</label>
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
mysqli_free_result($query);

// putuskan koneksi database
mysqli_close($conn);


 ?>