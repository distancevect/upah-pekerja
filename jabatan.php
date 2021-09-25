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

	// cek apakah variabel super global $_POST yang indexnya cari sudah tervalidasi
	if (isset($_POST["cari"])) {

		// pagination
		$data_perhalaman = 10;
		$query = mysqli_query($conn, "SELECT*FROM jabatan");
		$row = mysqli_num_rows($query);
		$jumlah_halaman = ceil($row/$data_perhalaman);

		if (isset($_GET["halaman"])) {
			$halaman_aktif = $_GET["halaman"];
		}
		elseif (!isset($_GET["halaman"])) {
			$halaman_aktif = 1;
		}
		$awal_data = ($data_perhalaman * $halaman_aktif) - $data_perhalaman;

		// ambil nilai form
		$pencarian = htmlentities(strip_tags(trim($_POST["pencarian"])));

		// filter $pencerian untuk mencegal SQL injection
		$pencarian = mysqli_real_escape_string($conn, $pencarian);

		// cek nilai dari pencarian kosong
		if ($_POST["pencarian"] === "") {
			$query = mysqli_query($conn, "SELECT * FROM jabatan ORDER BY `jabatan`.`nama_jabatan` ASC LIMIT $awal_data, $data_perhalaman");
		}
		// cek nilai dari pencarian tidak kosong
		elseif ($_POST["pencarian"] !== "") {
			
			// pagination
			$data_perhalaman = 10;
			$query = mysqli_query($conn, "SELECT*FROM jabatan WHERE nama_jabatan LIKE '%$pencarian%'");
			$row = mysqli_num_rows($query);
			$jumlah_halaman = ceil($row/$data_perhalaman);

			if (isset($_GET["halaman"])) {
				$halaman_aktif = $_GET["halaman"];
			}
			elseif (!isset($_GET["halaman"])) {
				$halaman_aktif = 1;
			}
			$awal_data = ($data_perhalaman * $halaman_aktif) - $data_perhalaman;
			
			// buat query untuk menampilkan data
			$query = mysqli_query($conn, "SELECT * FROM jabatan WHERE nama_jabatan LIKE '%$pencarian%' LIMIT $awal_data, $data_perhalaman");
		}

		
		




	}

	// cek apakah variabel super global $_POST yang indexnya cari tidak tervalidasi
	elseif (!isset($_POST["cari"])){
		
		// pagination
		$data_perhalaman = 10;
		$query = mysqli_query($conn, "SELECT*FROM jabatan");
		$row = mysqli_num_rows($query);
		$jumlah_halaman = ceil($row/$data_perhalaman);
		

		if (isset($_GET["halaman"])) {
			$halaman_aktif = $_GET["halaman"];
		}
		elseif (!isset($_GET["halaman"])) {
			$halaman_aktif = 1;
		}

		$awal_data = ($data_perhalaman * $halaman_aktif) - $data_perhalaman;

		// membuat jumlah link perhalaman yang aktif sebelum dan sesudah link yang aktif 
		$jumlah_nomor = 2;
		$awal_nomor = ($halaman_aktif > $jumlah_nomor)? $halaman_aktif - $jumlah_nomor : 1;
		$akhir_nomor = ($halaman_aktif < ($jumlah_halaman - $jumlah_nomor))? $data_perhalaman + $jumlah_nomor : $jumlah_halaman;


		


		// buat query menampilkan seluruh isi table
		$query = mysqli_query($conn, "SELECT * FROM jabatan LIMIT $awal_data, $data_perhalaman");

		
	}

	// cek apakah ada nilai pesan di link
	if (isset($_GET["pesan"])) {
		$alert = $_GET["pesan"];
	}
	else {
		$alert = "";
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
				<h1>Jabatan</h1>
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
					<a href="insert-jabatan.php"><i class="fa fa-plus"></i> Input data</a>

					<form action="" method="post">
						<input type="text" name="pencarian" placeholder="Search">
						<button name="cari" value="cari data !"><i class="fa fa-search"></i></button>
					</form>
				</div>

				<div class="table"> 
					<table>

						<tr>
							<th>No</th>
							<th>Kode jabatan</th>
							<th>Nama jabatan</th>
							<th>Aksi</th>
						</tr>
						<?php $i=1+$awal_data; ?>
						<!-- ambil nilai dari query -->
						<?php while ($row = mysqli_fetch_assoc($query)) : ?>
						<tr>	
							<td><?php echo $i; ?></td>
							<td><?php echo $row["kode_jabatan"]; ?></td>
							<td><?php echo $row["nama_jabatan"]; ?></td>
							<td>
								<!-- kirim data untuk di update -->
								<form action="update-jabatan.php" method="post" class="update">
									<input type="hidden" name="kode_jabatan" value="<?php echo $row["kode_jabatan"]; ?>">
									<button name="submit" value="edit"><i class="fa fa-sync-alt"></i>Update</button>
								</form>

								<!-- kirim data untuk di delete -->
								<form action="delete-jabatan.php" method="post" class="delete">
									<input type="hidden" name="kode_jabatan" value="<?php echo $row["kode_jabatan"]; ?>">
									<button name="submit" value="delete" onclick="return confirm('Apakah anda yakin ingin menghapus data ini ?')"><i class="fa fa-trash-alt"></i>Delete</button>
								</form>
							</td>
						</tr>
						<?php $i++ ?>
						<?php endwhile ?>
						
					</table>

<!-- 					<div class="navigasi-halaman">
						<?php for ($i=$awal_nomor; $i <= $akhir_nomor; $i++) : ?>
							<a href="?halaman=<?php echo $i; ?>"><?php echo $i; ?></a>
						<?php endfor ?>
					</div> -->
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