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


// cek apakah tombol cari sudah di tekan
if (isset($_POST["cari"])) {
	$pencarian = htmlentities(strip_tags(trim($_POST["pencarian"])));
	echo $pencarian;

	// pagination
	$data_perhalaman = 10;
	$query = mysqli_query($conn, "SELECT 
		pekerjaan.kode_pekerjaan, 
		pekerjaan.tanggal,
		pekerjaan.pekerjaan, 
		pekerjaan.harga, 
		petugas.nama
		FROM petugas
		INNER JOIN pekerjaan ON petugas.nip = pekerjaan.nip WHERE pekerjaan LIKE '%$pencarian%'");
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
	$query = mysqli_query($conn, "SELECT 
		pekerjaan.kode_pekerjaan, 
		pekerjaan.tanggal,
		pekerjaan.kode_block,
		pekerjaan.pekerjaan, 
		pekerjaan.harga, 
		petugas.nama
		FROM petugas
		INNER JOIN pekerjaan ON petugas.nip = pekerjaan.nip WHERE pekerjaan LIKE '%$pencarian%' LIMIT $awal_data, $data_perhalaman 

	");

}
elseif (!isset($_POST["cari"])) {
	// pagination
	$data_perhalaman = 10;
	$query = mysqli_query($conn, "SELECT*FROM pekerjaan");
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
	$query = mysqli_query($conn, "SELECT 
		pekerjaan.kode_pekerjaan, 
		pekerjaan.tanggal,
		pekerjaan.kode_block,
		pekerjaan.pekerjaan, 
		pekerjaan.harga, 
		petugas.nama
		FROM petugas
		INNER JOIN pekerjaan ON petugas.nip = pekerjaan.nip LIMIT $awal_data, $data_perhalaman

	");

	



	
}
if (isset($_GET["pesan"])) {
	$pesan = $_GET["pesan"];
}
else {
	$pesan = "";
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
				<?php if ($pesan !== "") : ?>
				<div class="success">
					<?php echo $pesan; ?>
				</div>
				<?php elseif($pesan === "") : ?>
				<div class="normal">
					<?php echo $pesan; ?>
				</div>
				<?php endif ?>
				
				
				<div class="navigasi">
					<a href="insert-pekerjaan.php"><i class="fa fa-plus"></i> Input data</a>

					<form action="" method="post">
						<input type="text" name="pencarian" placeholder="Search">
						<button name="cari" value="cari data !"><i class="fa fa-search"></i></button>
					</form>	
				</div>

				<div class="table">
					<div class="responsive">
						<table>

						<tr>
							<th>No</th>
							<th>Kode Pekerjaan</th>
							<th>Block</th>
							<th>Tanggal</th>
							<th>Pekerjaan</th>
							<th>Jumlah harga</th>
							<th>Pengawas</th>
							<th>Aksi</th>

						</tr>
						<?php $i=1+$awal_data; ?>
						<!-- ambil nilai dari query -->
						<?php while ($row = mysqli_fetch_assoc($query)) : ?>
						<tr>	
							<td><?php echo $i; ?></td>
							<td><?php echo $row["kode_pekerjaan"]; ?></td>
							<td><?php echo $row["kode_block"]; ?></td>
							<td><?php echo date("d - F - Y", (strtotime($row["tanggal"]))); ?></td>
							<td><?php echo $row["pekerjaan"]; ?></td>
							<td><?php echo "Rp.".number_format($row["harga"]); ?></td>
							<td><?php echo $row["nama"]; ?></td>
							<td>
								<!-- kirim data untuk di update -->
								<form action="update-pekerjaan.php" method="post" class="update">
									<input type="hidden" name="kode_pekerjaan" value="<?php echo $row["kode_pekerjaan"]; ?>">
									<button name="submit" value="edit"><i class="fa fa-sync-alt"></i> Update</button>
								</form>

								<!-- kirim data untuk di delete -->
								<form action="delete-pekerjaan.php" method="post" class="delete">
									<input type="hidden" name="kode_pekerjaan" value="<?php echo $row["kode_pekerjaan"]; ?>">
									<button name="submit" value="delete" onclick="return confirm('Apakah anda yakin ingin menghapus data ini ?')"><i class="fa fa-trash-alt"></i> Delete</button>
								</form>
							</td>
						</tr>
						<?php $i++ ?>
						<?php endwhile ?>
						
					</table>
					</div>
					<div class="navigasi-halaman">
						<!-- <a href="?halaman=<?php echo "previous" ?>">Previous</a> -->
						<?php for ($i=1; $i <= $jumlah_halaman; $i++) : ?>
							<a href="?halaman=<?php echo $i; ?>"><?php echo $i; ?></a>
						<?php endfor ?>
						<!-- <a href="?halaman=<?php echo "previous" ?>">Next</a> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
