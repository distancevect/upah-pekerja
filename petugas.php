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

		// ambil nilai form
		$pencarian = htmlentities(strip_tags(trim($_POST["pencarian"])));

		// filter $pencerian untuk mencegal SQL injection
		$pencarian = mysqli_real_escape_string($conn, $pencarian);

		
		// buat query pencarian
		$query = mysqli_query($conn, "SELECT nip, nama, nama_jabatan, gaji FROM jabatan JOIN petugas  ON petugas.kode_jabatan = jabatan.kode_jabatan WHERE nama LIKE '%$pencarian%'");
		


		// buat query pencarian
		// $query = mysqli_query($conn, "SELECT*FROM karyawan WHERE nip LIKE '%$pencarian%'");


	}

	// cek apakah variabel super global $_POST yang indexnya cari tidak tervalidasi
	elseif (!isset($_POST["cari"])){
		// buat query menampilkan seluruh isi table
		$query = mysqli_query($conn, "SELECT nip, nama, no_hp, nama_jabatan FROM petugas INNER JOIN jabatan ON petugas.kode_jabatan=jabatan.kode_jabatan;");
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
				<h1>Petugas</h1>
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
					<a href="insert-petugas.php"><i class="fa fa-plus"></i> Input data</a>

					<form action="" method="post">
						<input type="text" name="pencarian" placeholder="Search">
						<button name="cari" value="cari data !"><i class="fa fa-search"></i></button>
					</form>
				</div>

				<div class="table"> 
					<table>

						<tr>
							<th>No</th>
							<th>Nip</th>
							<th>Nama</th>
							<th>Jabatan</th>
							<th>No HP</th>
							<th>Aksi</th>
						</tr>
						<?php $i=1; ?>
						<!-- ambil nilai dari query -->
						<?php while ($row = mysqli_fetch_assoc($query)) : ?>
						<tr>	
							<td><?php echo $i; ?></td>
							<td><?php echo $row["nip"]; ?></td>
							<td><?php echo $row["nama"]; ?></td>
							<td><?php echo $row["nama_jabatan"]; ?></td>
							<td><?php echo $row["no_hp"]; ?></td>
							<td>
								<!-- kirim data untuk di update -->
								<form action="update-petugas.php" method="post" class="update">
									<input type="hidden" name="nip" value="<?php echo $row["nip"]; ?>">
									<button name="submit" value="edit"><i class="fa fa-sync-alt"></i>Update</button>
								</form>

								<!-- kirim data untuk di delete -->
								<form action="delete-petugas.php" method="post" class="delete">
									<input type="hidden" name="nip" value="<?php echo $row["nip"]; ?>">
									<button name="submit" value="delete" onclick="return confirm('Apakah anda yakin ingin menghapus data ini ?')"><i class="fa fa-trash-alt"></i>Delete</button>
								</form>
							</td>
						</tr>
						<?php $i++ ?>
						<?php endwhile ?>
						
					</table>
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